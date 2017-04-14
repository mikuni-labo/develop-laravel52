<?php

namespace App\Services;

use App\Lib\Api\VideoCloud;
use App\Traits\Log;

/**
 * VideoCloudデータ連携処理クラス
 * 
 * @author Kuniyasu Wada
 */
class BcApiService extends VideoCloud
{
    use Log;
    
    /**
     * Create a new class instance.
     * 
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        
        $this->Log = $this->createLogger('VideoCloud', storage_path('logs/videocloud'));
        
        $this->setAccountId(    config('api.videocloud.account_id') );
        $this->setClientId(     config('api.videocloud.client_id') );
        $this->setClientSecret( config('api.videocloud.client_secret') );
        $this->setVideoProfile( config('api.videocloud.video_profile') );
        $this->setCallbackUrl(  config('api.videocloud.callback_url') );
        $this->setAuthUrl(      config('api.videocloud.auth_url') );
        $this->setCmsUrl(       config('api.videocloud.cms_url') );
        $this->setDIUrl(        config('api.videocloud.di_url') );
        $this->setProxyUrl(     config('api.videocloud.bc_proxy_url') );
        
        $result = $this->authenticate();
        
        if ( empty($result->access_token) )
        {
            $this->putLog($this->getCh()->getErrorMessage(), "CURL 認証エラー}");
        }
    }

    /**
     * test
     *
     * @return mixed
     */
    public function test()
    {
        dd('here!!');
    }

    /**
     * CMS APIへ接続して動画メタ情報を操作
     * 
     * {@inheritDoc}
     * @see \App\Services\Submit\SubmitServiceInterface::doSubmit()
     */
    public function doSubmit()
    {
        if( $this->enableStatus === 'on' )
        {
            $param = $this->buildCmsParam();
            
            if( empty( $this->getVideoId()) )
            {
                $result = $this->createVideo($param);
                
                if(empty($result->id)) {
                    $this->putLog($result, " [VideoId: {$this->videoId}][CreateVideo]");
                }
            }
            else {
                $result = $this->updateVideo($param);
                
                if(empty($result->id)) {
                    $this->putLog($result, "[VideoId: {$this->videoId}] [{UpdateVideo}]");
                }
            }
        }
        else {
            $param = [
                'state' => 'INACTIVE',
            ];
            
            $result = $this->disableVideo($param);
            
            if(empty($result->id))
            {
                $this->putLog($result, "[VideoId: {$this->videoId}] [DisableVideo]");
            }
        }
        
        return $result;
    }

    /**
     * 動画エンティティ取得
     *
     * @return mixed
     */
    public function getVideoData()
    {
        $result = $this->getVideo();
        
        if(empty($result->id))
        {
            $this->putLog($result, "[VideoId: {$this->videoId}] [GetVideo]");
        }
        
        return $result;
    }

    /**
     * CMS APIへ接続してフォルダを操作
     *
     * @return mixed
     */
    public function operationFolder()
    {
        if( !empty( $this->getFolderId()) )
        {
            $result = $this->moveVideoToFolder([
                "name" => $this->Program->bc_folder_id,
            ]);
            
            // 成功時はNULLが返る
            if( !is_null($result) )
            {
                $this->putLog($result, "[Program.id: {$this->Program->id}] [MoveVideoToFolder]");
            }
        }
        else {
            /**
             * 放送種別によってプレフィックスを分ける
             * ※BCアカウント内で必ずユニークにする必要有り
             * 放送番組設定時: [CU/OA]{番組キー}
             * オリジナル番組設定時: [CU/OG]{番組キー}
             */
            $prefix = $this->bampSource === 'BAMP' || $this->bampSource === 'BSJ' ? '[CU_OA]' : '[CU_OG]';
            
            $result = $this->createFolder([
                "name" => $prefix . $this->Program->official_dir,
            ]);
            
            if( empty($result->id) )
            {
                $this->putLog($result, "[Program.id: {$this->Program->id}] [CreateFolder]");
            }
        }
        
        return $result;
    }

    /**
     * CMS APIへ接続してポスター画像を操作
     * ※リモートアセット用
     *
     * @return mixed
     */
    public function crudRemotePoster()
    {
        $param = [
//          'reference_id' => $this->Data->reference_id,
            'remote_url'   => $this->Data->poster_url,
        ];
        
        $res = $this->getRemotePoster();
        
        if( !empty($res->id) )
        {
            $this->updateRemotePoster($param, $res->id);
        }
        else {
            $this->addRemotePoster($param);
        }
    }

    /**
     * CMS APIへ接続してポスター画像を操作
     * ※リモートアセット用
     *
     * @return mixed
     */
    public function crudRemoteThumbnail()
    {
        $param = [
//          'reference_id' => $this->Data->reference_id,
            'remote_url'   => $this->Data->thumbnail_url,
        ];
        
        $res = $this->getRemoteThumbnail();
        
        if( !empty($res->id) )
        {
            $this->updateRemoteThumbnail($param, $res->id);
        }
        else {
            $this->addRemoteThumbnail($param);
        }
    }

    /**
     * DI APIへ接続して動画・画像等アセットを操作
     *
     * @return mixed
     */
    public function ingestVideo()
    {
        $param = $this->buildDiParam();
        
        $result = $this->dynamicIngest($param);
        
        if(empty($result->id))
        {
            $this->putLog($result, "[Episode.id: {$this->Episode->id}] [DynamicIngest]");
        }
        
        return $result;
    }

    /**
     * DI APIへ接続してアセットを操作
     * 
     * @return mixed
     */
    public function ingestAssets()
    {
        $param = [];
        
        if( !empty($this->Data->poster_url) )
        {
            $param['poster'] = [
                "url"    => $this->Data->poster_url,
                "width"  => 640,
                "height" => 480,
            ];
        }
        
        if( !empty($this->Data->thumbnail_url) )
        {
            $param['thumbnail'] = [
                "url"    => $this->Data->thumbnail_url,
                "width"  => 640,
                "height" => 480,
            ];
        }
        
        $result = $this->dynamicIngest($param);
        
        if(empty($result->id))
        {
            $this->putLog($result, "[Episode.id: {$this->Episode->id}] [DynamicIngest][Assets]");
        }
        
        return $result;
    }

    /**
     * CMS接続用のパラメータを構築
     * 
     * @return array
     */
    private function buildCmsParam()
    {
        $param = [
            'name'          => $this->Data->name,
            'state'         => $this->Data->state,
            'description'   => $this->Data->description,
            'reference_id'  => $this->Data->reference_id,
            'link' => [
                'text' => $this->Data->link_text,
                'url'  => $this->Data->link_url,
            ],
            /* カスタムフィールド */
            'custom_fields' => [
                'programKey' => $this->Data->cf_programKey,
            ],
            /* 国別制限関連項目 */
            'geo' => [
                'restricted' => true,
                'countries'  => [
                    'jp',
                ],
            ],
        ];
        
        // BC本番モードは公開開始・終了日を送信 (ISO 8601形式)
        if($this->Data->submit_mode === 'submit_tx_production')
        {
            $param['schedule'] = [
                'starts_at' => $this->Data->starts_at->format('c'),
                'ends_at'   => $this->Data->ends_at->format('c'),
            ];
        }
        
        // タグを分割して送信
        if(!empty($this->Data->tags))
        {
            foreach (explode(',', $this->Data->tags) as $val)
            {
                $param['tags'][] = $val;
            }
        }
        
        // キューポイントは分割して、ポイント毎に渡す
        if(!empty($this->Data->cue_points_time))
        {
            foreach (explode(',', $this->Data->cue_points_time) as $val)
            {
                $param['cue_points'][] = [
//                  'name'       => 'abc',
                    'type'         => $this->Data->cue_points_type,
                    'time'         => \Util::timeToMillisecond($val),
//                  'metadata'   => '',    // default: null 128バイトまで
//                  'force-stop' => false, // default: false
                ];
            }
        }
        
        return $param;
    }

    /**
     * CMS接続用のパラメータを構築
     *
     * @return array
     */
    private function buildDiParam()
    {
        $param = [
            "master" => [
                "url" => $this->Syndication->tx_video_url,
            ],
            "callbacks" => [
                $this->callbackUrl,
            ],
            "profile"        => $this->videoProfile,
            "capture-images" =>  false,//自動生成オフ
        ];
        
        // スチル画像
        if( !empty($this->Syndication->tx_bc_still_img_url) )
        {
            $param['poster'] = [
                "url"    => $this->Syndication->tx_bc_still_img_url,
                "width"  => 640,
                "height" => 480,
            ];
        }
        
        // サムネイル
        if( !empty($this->Syndication->tx_bc_thumbnail_url) )
        {
            $param['thumbnail'] = [
                "url"    => $this->Syndication->tx_bc_thumbnail_url,
                "width"  => 640,
                "height" => 480,
            ];
        }
        
        return $param;
    }

    /**
     * APIレスポンスメッセージをブラウザアラート表示用に組み立てる
     *
     * @param  string $data
     * @param  string $name
     * @return string
     */
    public function buildMessage($data, $name)
    {
        $msg = "[". \Carbon::now(). "]: [{$name}]<br>";
        
        if(is_array($data)) {
            foreach ($data as $val)
            {
                $errCode = !empty($val->error_code) ? $val->error_code : 'none';
                $message = !empty($val->message) ? $val->message : 'none';
                $msg .= "[error_code: {$errCode}] [message: {$message}]<br>";
            }
        }
        elseif (is_object($data))
        {
            $errCode = !empty($data->error_code) ? $data->error_code : 'none';
            $message = !empty($data->message) ? $data->message : 'none';
            $msg .= "[error_code: {$errCode}] [message: {$message}]<br>";
        }
        else {
            $msg .= "{$data}<br>";
        }
        
        return $msg;
    }

    /**
     * ログをファイルへ書き込む
     *
     * @param  string|object|array $data
     * @param  string $name
     * @return void
     */
    public function putLog($data, $name)
    {
        $message = [];
        
        if(is_array($data)) {
            foreach ($data as $key => $val)
            {
                $message[$key] = [
                    'error_code' => !empty($val->error_code) ? $val->error_code : 'none',
                    'message'    => !empty($val->message) ? $val->message : 'none',
                ];
            }
        }
        elseif (is_object($data)) {
            $message = [
                'error_code' => !empty($data->error_code) ? $data->error_code : 'none',
                'message'    => !empty($data->message) ? $data->message : 'none',
            ];
        }
        elseif (is_null($data)) {
            $message = ["[name: {$name}] [msg: Response is null.]"];
        }
        else {
           $message = [$data];
        }
        
        $this->Log->error($name, $message);
    }

    /**
     * Setter...
     */
    public function setProgram(Program $Program)
    {
        $this->Program = $Program;
        return $this;
    }

    public function setEpisode(Episode $Episode)
    {
        $this->Episode = $Episode;
        return $this;
    }

    public function setSyndication(TxVideo $Syndication)
    {
        $this->Syndication = $Syndication;
        return $this;
    }

    public function setData($Data)
    {
        $this->Data = $Data;
        return $this;
    }

    public function setSubmitMode($submitMode)
    {
        $this->submitMode = $submitMode;
        return $this;
    }

    public function setLog($Log)
    {
        $this->Log = $Log;
        return $this;
    }

    public function setEnableStatus($enableStatus)
    {
        $this->enableStatus = $enableStatus;
        return $this;
    }

    public function setBampSource($bampSource)
    {
        $this->bampSource = $bampSource;
        return $this;
    }

    /**
     * Getter...
     */
    public function getProgram()
    {
        return $this->Program;
    }

    public function getEpisode()
    {
        return $this->Episode;
    }

    public function getSyndication()
    {
        return $this->Syndication;
    }

    public function getData()
    {
        return $this->Data;
    }

    public function getSubmitMode()
    {
        return $this->submitMode;
    }

    public function getLog()
    {
        return $this->Log;
    }

    public function getEnableStatus()
    {
        return $this->enableStatus;
    }

    public function getBampSource()
    {
        return $this->bampSource;
    }

}
