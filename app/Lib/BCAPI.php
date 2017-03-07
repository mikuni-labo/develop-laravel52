<?php

namespace App\Lib;

use App\Lib\cURL;
use App\Lib\Util;
use App\Models\Program;
use App\Models\Episode;
use App\Models\Config;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Carbon\Carbon;

/**
 * ブライトコーブAPI関連共通処理クラス
 * 
 * @author Kuniyasu Wada
 */
class BCAPI
{
    /** 動画ID */
    private $videoId;
    
    /** CMS URL */
    private $cmsUrl;
    
    /** DI URL */
    private $diUrl;
    
    /** API AUTH URL */
    private $authUrl;
    
    /** API PROXY URL */
    private $proxyUrl;
    
    /** VIDEOCLOUD ACCOUNT ID */
    private $accountId;
    
    /** VIDEOCLOUD CLIENT ID */
    private $clientId;
    
    /** VIDEOCLOUD CLIENT SECRET */
    private $clientSecret;
    
    /** ACCESS TOKEN */
    private $accessToken;
    
    /** トークン有効期限 */
    private $expiresIn;
    
    /** VIDEOCLOUD CALLBACK URL */
    private $callbackUrl;
    
    /** REQUEST METHOD */
    private $method;
    
    /** 番組データ */
    private $Program;
    
    /** 配信枠データ */
    private $Episode;
    
    /** シンジケーションデータ */
    private $Syndication;
    
    /** 出力用データ */
    private $Data;
    
    /** 出力モード */
    private $submitMode;
    
    /** 出力時有効/無効ステータス */
    private $enableStatus;
    
    /** Log オブジェクト */
    private $Log;
    
    /** BAMPデータ取得元 */
    private $bampSource;
    
    /**
     * コンストラクタ...
     */
    public function __construct()
    {
        $this->cmsUrl       = config('BCAPI.BRIGHTCOVE_API.CMS_URL');
        $this->diUrl        = config('BCAPI.BRIGHTCOVE_API.DI_URL');
        $this->proxyUrl     = config('BCAPI.BRIGHTCOVE_API.BC_PROXY');
        $this->accountId    = config('BCAPI.VIDEOCLOUD.ACCOUNT_ID');
        $this->clientId     = config('BCAPI.VIDEOCLOUD.CLIENT_ID');
        $this->clientSecret = config('BCAPI.VIDEOCLOUD.CLIENT_SECRET');
        $this->callbackUrl  = config('BCAPI.BRIGHTCOVE_API.CALLBACK_URL');
        $this->authUrl      = config('BCAPI.BRIGHTCOVE_API.AUTH_URL');
        $this->Log = new Logger('VideoCloud');
        $this->Log->pushHandler(new StreamHandler(storage_path('logs/videocloud.log'), Logger::WARNING));
        $this->authenticate();
    }
    
    /**
     * Request to CMS API for Create Folder
     * CMS APIに接続して、VideoCloudフォルダ登録処理を行う
     * 
     * @return object $result
     */
    public function createFolder()
    {
        // 放送種別によって接頭辞を分ける
        if($this->bampSource == 1 || $this->bampSource == 2)
            $prefix = '[CU_OA]';// 放送番組設定時:[CU/OA]{番組キー}
        else 
            $prefix = '[CU_OG]';// オリジナル番組設定時:[CU/OG]{番組キー}
        
        $url = "{$this->cmsUrl}{$this->accountId}/folders";
        $this->setMethod('POST');
        $param = [
                "name" => "{$prefix}{$this->Program->official_dir}",// アカウント内で必ずユニークに
        ];
        
        $header = [
                'Content-type: application/json',
                "Authorization: Bearer {$this->accessToken}",
        ];
        
        $result = $this->call($param, $header, $url);
        
        if(empty($result->id))
            $this->putLog($result, "[Program.id: {$this->Program->id}] [CreateFolder]");
        
        return $result;
    }
    
    /**
     * Request to CMS API for Move Video to Folder
     * CMS APIに接続して、VideoCloudフォルダ登録処理を行う
     * 
     * @return object $result
     */
    public function moveVideoToFolder()
    {
        $url = "{$this->cmsUrl}{$this->accountId}/folders/{$this->Program->bc_folder_id}/videos/{$this->videoId}";
        $this->setMethod('PUT');
        $param = [
                "name" => $this->Program->bc_folder_id,// アカウント内で必ずユニークに
        ];
        
        $header = [
                'Content-type: application/json',
                "Authorization: Bearer {$this->accessToken}",
        ];
        
        $result = $this->call($param, $header, $url, 'test');
        
        // 成功時はリターン無し
        if(!empty($result))
            $this->putLog($result, "[Program.id: {$this->Program->id}] [MoveVideoToFolder]");
        
        return $result;
    }
    
    /**
     * Request to CMS API for Update video object
     * CMS APIに接続して動画メタデータを更新する
     *
     * @return object $result
     */
    public function updateVideo()
    {
        $this->setMethod('PATCH');
        $url = "{$this->cmsUrl}{$this->accountId}/videos/{$this->videoId}";
        $header = [
                'Content-type: application/json',
                "Authorization: Bearer {$this->accessToken}",
                ];
        
        $param = [
                'name'               => $this->Data->name,
                'state'              => $this->Data->state,
                'description'        => $this->Data->description,
                'reference_id'       => $this->Data->reference_id,
                'link' => [
                        'text'       => $this->Data->link_text,
                        'url'        => $this->Data->link_url,
                ],
                
                'custom_fields' => [
                        'programKey' => $this->Data->cf_programKey,
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
                        //'name'       => 'abc',
                        'type'         => $this->Data->cue_points_type,
                        'time'         => Util::timeToMillisecond($val),
                        //'metadata'   => '',             // default: null 128バイトまで
                        //'force-stop' => false,          // default: false
                ];
            }
        }
        
        $result = $this->call($param, $header, $url);
        
        if(empty($result->id))
            $this->putLog($result, "[video_id: {$this->videoId}] [updateVideo]");
        
        return $result;
    }
    
    /**
     * Request to CMS API for Create video object
     * CMS APIに接続して動画メタを作成し、動画IDを発行する
     * 
     * @param array $inputs
     * @return object $result
     */
    public function createVideo($inputs)
    {
        $oa_date = new Carbon($this->Episode->oa_date);
        $this->setMethod('POST');
        //$state   = ($this->Episode->output_to_tx == 1) ? "ACTIVE" : "INACTIVE";/* 配信ステータスを廃止にしたので、有効ステータスの仕様再確認 */
        $url = "{$this->cmsUrl}{$this->accountId}/videos";
        $header = [
                'Content-type: application/json',
                "Authorization: Bearer {$this->accessToken}",
        ];
        
        $param = [
                'name'              => "{$this->Program->title}{$oa_date->format('Y/m/d')}", // {番組名}{放送日} ※登録時必須項目
                
                'geo' => [
                        'countries'  => [
                                'jp',
                        ],
                        'restricted' => true,
                ],
        ];
        
        // キューポイントは分割して、ポイント毎に渡す
        if(!empty($inputs['tx_cue_point']))
        {
            foreach (explode(',', $inputs['tx_cue_point']) as $val)
            {
                $param['cue_points'][] = [
                        //'name'       => 'abc',
                        'type'         => 'AD',           // default: 'AD' ※必須
                        'time'         => Util::timeToMillisecond($val),
                        //'metadata'   => '',             // default: null 128バイトまで
                        //'force-stop' => false,          // default: false
                ];
            }
        }
        
        $result = $this->call($param, $header, $url);
        
        if(empty($result->id))
            $this->putLog($result, "[Episode.id: {$this->Episode->id}] [CreateVideo]");
        
        return $result;
    }
    
    /**
     * Request to CMS API for get video object
     * CMS APIに接続して動画メタデータを取得する
     *
     * @return object $result
     */
    public function getVideo()
    {
        $this->setMethod('GET');
        $url = "{$this->cmsUrl}{$this->accountId}/videos/{$this->videoId}";
        $param = [];
        $header = [
                "Authorization: Bearer {$this->accessToken}",
        ];
        
        $result = $this->call($param, $header, $url);
        
        if(empty($result->id))
            $this->putLog($result, "[video_id: {$this->videoId}] [getVideo]");
        
        return $result;
    }
    
    /**
     * Request to CMS API for Disable video object
     * CMS APIに接続して動画を無効化する
     *
     * @return object $result
     */
    public function disableVideo()
    {
        $this->setMethod('PATCH');
        $url    = "{$this->cmsUrl}{$this->accountId}/videos/{$this->videoId}";
        $header = [
                'Content-type: application/json',
                "Authorization: Bearer {$this->accessToken}",
                ];
        
        $param = [
                'state' => 'INACTIVE',
        ];
        
        $result = $this->call($param, $header, $url);
        
        if(empty($result->id))
            $this->putLog($result, "[video.id: {$this->videoId}] [disableVideo]");
        
        return $result;
    }
    
    /**
     * Request to DI API for Dynamic Ingest
     * DI APIに接続して動画を転送し、コールバックURL登録をする
     *
     * @param array $inputs
     * @return void
     */
    public function dynamicIngest($inputs)
    {
        $this->setMethod('POST');
        $url = "{$this->diUrl}{$this->accountId}/videos/{$this->videoId}/ingest-requests";
        $header = [
                'Content-type: application/json',
                "Authorization: Bearer {$this->accessToken}",
        ];
        $param = [
                "master" => [
                        "url" => $inputs['tx_video_url'],
                ],
                "callbacks" => [
                        $this->callbackUrl,
                ],
                "profile"        => Config::find(1)->video_cloud_profile,
                "capture-images" =>  false,//自動生成オフ
        ];
        
        // スチル画像
        if(!empty($inputs['tx_bc_still_img_url']))
        {
            $param['poster'] = [
                    "url"    => $inputs['tx_bc_still_img_url'],
                    "width"  => 640,
                    "height" => 480
            ];
        }
        
        // サムネイル
        if(!empty($inputs['tx_bc_thumbnail_url']))
        {
            $param['thumbnail'] = [
                    "url"    => $inputs['tx_bc_thumbnail_url'],
                    "width"  => 640,
                    "height" => 480
            ];
        }
        
        $result = $this->call($param, $header, $url);
        
        if(empty($result->id))
            $this->putLog($result, "[Episode.id: {$this->Episode->id}] [dynamicIngest]");
        
        return $result;
    }
    
    /**
     * cURLライブラリで接続後、JSONをパースして返す
     */
    private function call($param, $header, $url, $isAuth = false)
    {
        $modeName = $isAuth === true ? ' Auth' : '';
        
        $ch = new cURL();
        $ch->init();
        $ch->setUrl($url);
        $ch->setIsJson(true);
        $ch->setMethod($this->method);
        $ch->setUserPwd($this->clientId, $this->clientSecret);
        $ch->setHeader($header);
        $ch->setParameterFromArray($param);
        $ch->setSslVerifypeer(false);
        $response = $ch->exec();
        $ch->close();
        
        if (empty($response) && $this->method !== 'PUT')
            $this->putLog($ch->getErrorMessage(), "CURL{$modeName}");
            
        return json_decode($response);// 第2引数trueで配列型
    }
    
    /**
     * authentication...
     */
    private function authenticate()
    {
        // Set up request for access token
        $url    = $this->authUrl;
        $header = [
                'Content-type: application/x-www-form-urlencoded',
        ];
        $param  = [];
        
        $this->setMethod('POST');
        $data = $this->call($param, $header, $url, true);
        
        $this->setAccessToken($data->access_token);
        $this->setExpiresIn($data->expires_in);
    }
    
    /**
     * VideoCloudへの更新用データを取得する
     * 
     * @return object $Data
     */
    public function makeSubmitDataForVideoCloud()
    {
        $this->Data = (object)[];
        
        /**
         * Common
         */
        $this->Data->parent_episode_id = $this->Episode->id;
        $this->Data->tx_unique_id = $this->Syndication->tx_unique_id;
        $this->Data->tx_branch_id = $this->Syndication->tx_branch_id;
        $this->Data->submit_user_id = auth()->guard('user')->user()->id;
        $this->Data->tx_video_id = $this->Syndication->tx_video_id;
        $this->Data->submit_mode = $this->submitMode;
        $this->Data->enable_status = $this->enableStatus;
        
        if($this->enableStatus === 'off') return $this->Data;
        
        $oa_date = Carbon::parse($this->Episode->oa_date);
        
        /**
         * General...
         */
        /* 動画名: {番組名}{放送日} ※登録時必須項目 */
        $this->Data->name = "{$this->Program->title}{$oa_date->format('Y/m/d')}";
        
        /* 公開ステータス: {有効ステータスON時はACTIVE} */
        $this->Data->state = 'ACTIVE';
        
        /* 短い説明: {WEBエピソード詳細} */
        $this->Data->description = $this->Syndication->tx_web_content;
        
        /* 参照ID: {放送日}_{番組キー}_{放送回}_{再配信回数}_{枝番} */
        $this->Data->reference_id = "{$oa_date->format('Ymd')}_{$this->Program->official_dir}_{$this->Episode->number}_{$this->Episode->reprovide_num}_{$this->Syndication->tx_branch_id}";
        
        /* リンクテキスト */
        $this->Data->link_text = $this->Program->title;
        
        /* リンクURL */
        $this->Data->link_url = $this->Program->official_url;
        
        /* タグ */
        $this->Data->tags = "txcu,{$oa_date->format('Ymd')},{$this->Program->official_dir}";
        
        /* 公開開始日 (ISO 8601形式) */
        $this->Data->starts_at = ($this->submitMode === 'submit_tx_production' && !empty($this->Episode->tx_oa_start_date) && !empty($this->Episode->tx_oa_start_time)) ? Carbon::parse("{$this->Episode->tx_oa_start_date} {$this->Episode->tx_oa_start_time}") : null;
        
        /* 公開終了日 (ISO 8601形式) */
        $this->Data->ends_at = ($this->submitMode === 'submit_tx_production' && !empty($this->Episode->tx_oa_end_date) && !empty($this->Episode->tx_oa_end_time)) ? Carbon::parse("{$this->Episode->tx_oa_end_date} {$this->Episode->tx_oa_end_time}") : null;
        
        /* 国コード */
        $this->Data->geo_countries = null;// 登録時以外送信しない
        
        /* 国別視聴制限許可 */
        $this->Data->geo_restricted = null;// 登録時以外送信しない
        
        /* キューポイント [type] */
        $this->Data->cue_points_type = 'AD';
        
        /* キューポイント [time] */
        $this->Data->cue_points_time = $this->Syndication->tx_cue_point;
            
        /**
         * Custom Fields...
         */
        /* 番組キー */
        $this->Data->cf_programKey = $this->Program->official_dir;
        
        return $this->Data;
    }
    
    /**
     * VideoCloudのバリデーション属性を取得する
     *
     * @return multitype:string
     */
    public static function getVideoCloudAttributes()
    {
        return [
                /**
                 * General...
                 */
                'name'               => '動画名',
                'state'              => '公開ステータス',
                'description'        => '短い説明',
                'reference_id'       => '参照ID',
                'link_text'          => 'リンクテキスト',
                'link_url'           => 'リンクURL',
                'tags'               => 'タグ',
                'starts_at'          => '公開開始日',
                'ends_at'            => '公開終了日',
                'geo_countries'      => '国',
                'geo_restricted'     => '国別視聴制限許可',
                'cue_points_type'    => 'キューポイント [type]',
                'cue_points_time'    => 'キューポイント [time]',
                
                /**
                 * Custom Fields...
                 */
                'cf_programKey'      => '番組キー',
        ];
    }
    
    /**
     * CMS API (プロキシ経由方式 )
     */
    private function legacyCreateObject($param, $url)
    {
        $request_url  = "{$this->cmsUrl}{$this->accountId}$url";
        $request_body = json_encode($param);
        
        $post_fields = "client_id=" . $this->clientId     . "&" .
                "client_secret="    . $this->clientSecret . "&" .
                "url="              . $request_url  . "&" .
                "requestBody="      . $request_body . "&" .
                "requestType="      . "POST";
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->proxyUrl);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_fields);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $response = curl_exec($ch);
        
        //dd(curl_getinfo($ch));
        curl_close($ch);
        
        return json_decode($response);
    }
    
    /**
     * DI API (プロキシ経由方式 )
     */
    private function legacyDynamicIngest($param, $url)
    {
        $request_url  = urlencode("{$this->diUrl}{$this->accountId}$url");
        $request_body = json_encode($param);
        
        $post_fields = "client_id=" . $this->clientId     . "&" .
                "client_secret="    . $this->clientSecret . "&" .
                "url="              . $request_url  . "&" .
                "requestBody="      . $request_body . "&" .
                "requestType="      . "POST";
            
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->proxyUrl);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_fields);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $response = curl_exec($ch);
        
        //dd(curl_getinfo($ch));
        curl_close($ch);
        
        return json_decode($response);
    }
    
    /**
     * ログをファイルへ書き込む
     *
     * @param string $data
     * @param string $filepath
     * @return void
     */
    public function putLog($data, $name)
    {
        $message = [];
        if(is_array($data))
        {
            foreach ($data as $key => $val)
            {
                $message[$key] = [
                        'error_code' => !empty($val->error_code) ? $val->error_code : 'none',
                        'message'    => !empty($val->message) ? $val->message : 'none',
                ];
            }
        }
        elseif (is_object($data))
        {
            $message = [
                    'error_code' => !empty($data->error_code) ? $data->error_code : 'none',
                    'message'    => !empty($data->message) ? $data->message : 'none',
            ];
        }
        elseif (is_null($data))
            $message = ["[name: {$name}] [msg: Response is null.]"];
        else
            $message = [$data];
        
        $this->Log->error($name, $message);
    }
    
    /**
     * APIレスポンスメッセージをブラウザアラート表示用に組み立てる
     *
     * @param string $data
     * @param string $name
     * @return string
     */
    public function buildMessage($data, $name)
    {
        $msg = "[". Carbon::now(). "]: [{$name}]<br>";
        
        if(is_array($data))
        {
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
        else
            $msg .= "{$data}<br>";
        
        return $msg;
    }
    
    /**
     * Output To File...
     * ※現在未使用
     *
     * @param string $data
     * @param string $name
     * @param string $mode
     * @return void
     */
    public function fwrite($data, $name, $mode = 'a+')
    {
        $fp = fopen($this->logPath, $mode);
        fwrite($fp, "[". Carbon::now(). "]: [{$name}]\n");
        
        if(is_array($data))
        {
            foreach ($data as $val)
            {
                $errCode = !empty($val->error_code) ? $val->error_code : 'none';
                $message = !empty($val->message) ? $val->message : 'none';
                fwrite($fp, "[error_code: {$errCode}] [message: {$message}]\n");
            }
        }
        elseif (is_object($data))
        {
            $errCode = !empty($data->error_code) ? $data->error_code : 'none';
            $message = !empty($data->message) ? $data->message : 'none';
            fwrite($fp, "[error_code: {$errCode}] [message: {$message}]\n");
        }
        else 
            fwrite($fp, "{$data}\n");
        
        fclose($fp);
    }
    
    /**
     * Setter...
     */
    public function setVideoId($videoId)
    {
        $this->videoId = $videoId;
        return $this;
    }
    
    public function setCmsUrl($cmsUrl)
    {
        $this->cmsUrl = $cmsUrl;
        return $this;
    }
    
    public function setDIUrl($diUrl)
    {
        $this->diUrl = $diUrl;
        return $this;
    }
    
    public function setProxyUrl($proxyUrl)
    {
        $this->proxyUrl = $proxyUrl;
        return $this;
    }
    
    public function setAuthUrl($authUrl)
    {
        $this->authUrl = $authUrl;
        return $this;
    }
    
    public function setAccountId($accountId)
    {
        $this->accountId = $accountId;
        return $this;
    }
    
    public function setClientId($clientId)
    {
        $this->clientId = $clientId;
        return $this;
    }
    
    public function setClientSecret($clientSecret)
    {
        $this->clientSecret = $clientSecret;
        return $this;
    }
    
    public function setAccessToken($accessToken)
    {
        $this->accessToken = $accessToken;
        return $this;
    }
    
    public function setExpiresIn($expiresIn)
    {
        $this->expiresIn = $expiresIn;
        return $this;
    }
    
    public function setCallbackUrl($callbackUrl)
    {
        $this->callbackUrl = $callbackUrl;
        return $this;
    }
    
    public function setMethod($method)
    {
        $this->method = $method;
        return $this;
    }
    
    public function setProgram($Program)
    {
        $this->Program = $Program;
        return $this;
    }
    
    public function setEpisode($Episode)
    {
        $this->Episode = $Episode;
        return $this;
    }
    
    public function setSyndication($Syndication)
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
    
    public function setEnableStatus($enableStatus)
    {
        $this->enableStatus = $enableStatus;
        return $this;
    }
    
    public function setLog($Log)
    {
        $this->Log = $Log;
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
    public function getVideoId()
    {
        return $this->videoId;
    }
    
    public function getCmsUrl()
    {
        return $this->cmsUrl;
    }
    
    public function getDIUrl()
    {
        return $this->diUrl;
    }
    
    public function getProxyUrl()
    {
        return $this->proxyUrl;
    }
    
    public function getAuthUrl()
    {
        return $this->authUrl;
    }
    
    public function getAccountId()
    {
        return $this->accountId;
    }
    
    public function getClientId()
    {
        return $this->clientId;
    }
    
    public function getClientSecret()
    {
        return $this->clientSecret;
    }
    
    public function getAccessToken()
    {
        return $this->accessToken;
    }
    
    public function getExpiresIn()
    {
        return $this->expiresIn;
    }
    
    public function getCallbackUrl()
    {
        return $this->callbackUrl;
    }
    
    public function getMethod()
    {
        return $this->method;
    }
    
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
    
    public function getEnableStatus()
    {
        return $this->enableStatus;
    }
    
    public function getLog()
    {
        return $this->Log;
    }
    
    public function getBampSource()
    {
        return $this->bampSource;
    }
    
}
