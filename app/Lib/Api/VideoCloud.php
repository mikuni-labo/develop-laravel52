<?php

namespace App\Lib\Api;

use App\Lib\Api\cURL;

/**
 * VideoCloud操作クラス
 * ※対応バージョン: v1
 * 
 * @author Kuniyasu Wada
 */
class VideoCloud
{
    /** @var cURLインスタンス */
    private $ch;

    /** @var CMS URL */
    private $cmsUrl;

    /** @var DI URL */
    private $diUrl;

    /** @var API AUTH URL */
    private $authUrl;

    /** @var API PROXY URL */
    private $proxyUrl;

    /** @var VIDEOCLOUD ACCOUNT ID */
    private $accountId;

    /** @var VIDEOCLOUD CLIENT ID */
    private $clientId;

    /** @var VIDEOCLOUD CLIENT SECRET */
    private $clientSecret;

    /** @var ACCESS TOKEN */
    private $accessToken;

    /** @var トークン有効期限 */
    private $expiresIn;

    /** @var VIDEOCLOUD CALLBACK URL */
    private $callbackUrl;

    /** @var REQUEST METHOD */
    private $method;

    /** @var 動画ID */
    private $videoId;

    /** @var アセットID */
    private $assetsId;

    /** @var フォルダID */
    private $folderId;

    /** @var 動画プロファイル */
    private $videoProfile;

    /**
     * Create a new class instance.
     * 
     * @return void
     */
    public function __construct()
    {
        $this->ch = app()->make(cURL::class);
    }

    /**
     * Request to CMS API for Create Folder...
     * 
     * @param  array $param
     * @return mixed
     */
    public function createFolder($param = array())
    {
        $this->setMethod('POST');
        $url = "{$this->cmsUrl}/v1/accounts/{$this->accountId}/folders";
        $header = [
            'Content-type: application/json',
            "Authorization: Bearer {$this->accessToken}",
        ];
        
        return $this->call($url, $header, $param);
        
    }

    /**
     * Request to CMS API for Move Video to Folder...
     * 
     * @param  array $param
     * @return mixed
     */
    public function moveVideoToFolder($param = array())
    {
        $this->setMethod('PUT');
        $url = "{$this->cmsUrl}/v1/accounts/{$this->accountId}/folders/{$this->folderId}/videos/{$this->videoId}";
        $header = [
            'Content-type: application/json',
            "Authorization: Bearer {$this->accessToken}",
        ];
        
        return $this->call($url, $header, $param);
    }

    
    /**
     * Request to CMS API for Create Video Object...
     * 
     * @param  array $param
     * @return mixed
     */
    public function createVideo($param = array())
    {
        $this->setMethod('POST');
        $url = "{$this->cmsUrl}/v1/accounts/{$this->accountId}/videos";
        $header = [
            'Content-type: application/json',
            "Authorization: Bearer {$this->accessToken}",
        ];
        
        return $this->call($url, $header, $param);
    }
    /**
     * Request to CMS API for Update Video Object...
     * 
     * @param  array $param
     * @return mixed
     */
    public function updateVideo($param = array())
    {
        $this->setMethod('PATCH');
        $url = "{$this->cmsUrl}/v1/accounts/{$this->accountId}/videos/{$this->videoId}";
        $header = [
            'Content-type: application/json',
            "Authorization: Bearer {$this->accessToken}",
        ];
        
        return $this->call($url, $header, $param);
    }

    /**
     * Request to CMS API for get video object...
     * 
     * @param  array $param
     * @return mixed
     */
    public function getVideos($param = array())
    {
        $this->setMethod('GET');
        $url = "{$this->cmsUrl}/v1/accounts/{$this->accountId}/videos";
        $header = [
            "Authorization: Bearer {$this->accessToken}",
        ];
        
        return $this->call($url, $header, $param);
    }

    /**
     * Request to CMS API for get video object...
     * 
     * @return mixed
     */
    public function getVideo()
    {
        $this->setMethod('GET');
        $url = "{$this->cmsUrl}/v1/accounts/{$this->accountId}/videos/{$this->videoId}";
        $header = [
            "Authorization: Bearer {$this->accessToken}",
        ];
        
        return $this->call($url, $header);
    }

    /**
     * Request to CMS API for get video object...
     *
     * @return mixed
     */
    public function getVideoCount()
    {
        $this->setMethod('GET');
        $url = "{$this->cmsUrl}/v1/accounts/{$this->accountId}/counts/videos";
        $header = [
            "Authorization: Bearer {$this->accessToken}",
        ];
        
        return $this->call($url, $header);
    }

    /**
     * Request to CMS API for Disable video object...
     * 
     * @param  array $param
     * @return mixed
     */
    public function deleteVideo()
    {
        $this->setMethod('DELETE');
        $url    = "{$this->cmsUrl}/v1/accounts/{$this->accountId}/videos/{$this->videoId}";
        $header = [
            'Content-type: application/json',
            "Authorization: Bearer {$this->accessToken}",
        ];
        
        return $this->call($url, $header);
    }

    /**
     * ポスター画像リスト取得（リモートアセット）
     * 
     * @return mixed
     */
    public function getRemotePoster()
    {
        $this->setMethod('GET');
        $url = "{$this->cmsUrl}/v1/accounts/{$this->accountId}/videos/{$this->videoId}/assets/poster";
        $header = [
            'Content-type: application/json',
            "Authorization: Bearer {$this->accessToken}",
        ];
        
        return $this->call($url, $header);
    }

    /**
     * ポスター画像登録（リモートアセット）
     * 
     * @param  array $param
     * @return mixed
     */
    public function addRemotePoster($param = array())
    {
        $this->setMethod('POST');
        $url = "{$this->cmsUrl}/v1/accounts/{$this->accountId}/videos/{$this->videoId}/assets/poster";
        $header = [
            'Content-type: application/json',
            "Authorization: Bearer {$this->accessToken}",
        ];
        
        return $this->call($url, $header, $param);
    }

    /**
     * ポスター画像更新（リモートアセット）
     * 
     * @param  array  $param
     * @return mixed
     */
    public function updateRemotePoster($param = array())
    {
        $this->setMethod('PATCH');
        $url = "{$this->cmsUrl}/v1/accounts/{$this->accountId}/videos/{$this->videoId}/assets/poster/{$this->assetsId}";
        $header = [
            'Content-type: application/json',
            "Authorization: Bearer {$this->accessToken}",
        ];
        
        return $this->call($url, $header, $param);
    }

    /**
     * ポスター画像削除（リモートアセット）
     * 
     * @return mixed
     */
    public function deleteRemotePoster()
    {
        $this->setMethod('DELETE');
        $url = "{$this->cmsUrl}/v1/accounts/{$this->accountId}/videos/{$this->videoId}/assets/poster/{$this->assetsId}";
        $header = [
            'Content-type: application/json',
            "Authorization: Bearer {$this->accessToken}",
        ];
        
        return $this->call($url, $header);
    }

    /**
     * サムネイルリスト取得（リモートアセット）
     * 
     * @return mixed
     */
    public function getRemoteThumbnail()
    {
        $this->setMethod('GET');
        $url = "{$this->cmsUrl}/v1/accounts/{$this->accountId}/videos/{$this->videoId}/assets/thumbnail";
        $header = [
            'Content-type: application/json',
            "Authorization: Bearer {$this->accessToken}",
        ];
        
        return $this->call($url, $header);
    }

    /**
     * サムネイル登録（リモートアセット）
     * 
     * @param  array $param
     * @return mixed
     */
    public function addRemoteThumbnail($param = array())
    {
        $this->setMethod('POST');
        $url = "{$this->cmsUrl}/v1/accounts/{$this->accountId}/videos/{$this->videoId}/assets/thumbnail";
        $header = [
            'Content-type: application/json',
            "Authorization: Bearer {$this->accessToken}",
        ];
        
        return $this->call($url, $header, $param);
    }

    /**
     * サムネイル更新（リモートアセット）
     * 
     * @param  array  $param
     * @return mixed
     */
    public function updateRemoteThumbnail($param = array())
    {
        $this->setMethod('PATCH');
        $url = "{$this->cmsUrl}/v1/accounts/{$this->accountId}/videos/{$this->videoId}/assets/thumbnail/{$this->assetsId}";
        $header = [
            'Content-type: application/json',
            "Authorization: Bearer {$this->accessToken}",
        ];
        
        return $this->call($url, $header, $param);
    }

    /**
     * サムネイル削除（リモートアセット）
     * 
     * @return mixed
     */
    public function deleteRemoteThumbnail()
    {
        $this->setMethod('DELETE');
        $url = "{$this->cmsUrl}/v1/accounts/{$this->accountId}/videos/{$this->videoId}/assets/thumbnail/{$this->assetsId}";
        $header = [
            'Content-type: application/json',
            "Authorization: Bearer {$this->accessToken}",
        ];
        
        return $this->call($url, $header);
    }

    /**
     * Request to Dynamic Ingest API for Ingest Video...
     * 
     * @param  array $param
     * @return mixed
     */
    public function dynamicIngest($param = array())
    {
        $this->setMethod('POST');
        $url = "{$this->diUrl}/v1/accounts/{$this->accountId}/videos/{$this->videoId}/ingest-requests";
        $header = [
            'Content-type: application/json',
            "Authorization: Bearer {$this->accessToken}",
        ];
        
        /** XXX APIから原因不明のInternal Server Errorが返される時は、プロファイル設定を要チェック */
        return $this->call($url, $header, $param);
    }

    /**
     * Authentication for access token...
     * 
     * @return void
     */
    public function authenticate()
    {
        $this->setMethod('POST');
        $url = "{$this->authUrl}/v3/access_token?grant_type=client_credentials";
        $header = [
            'Content-type: application/x-www-form-urlencoded',
        ];
        
        $result = $this->call($url, $header);
        
        if ( !empty($result->access_token) )
        {
            $this->accessToken = $result->access_token;
            $this->expiresIn   = $result->expires_in;
        }
        
        return $result;
    }

    /**
     * cURLライブラリで接続後、JSONをパースして返す
     * 
     * @param  string $url
     * @param  array  $header
     * @param  array  $param
     * @return mixed
     */
    private function call($url, $header, $param = array())
    {
        $this->ch->init();
        $this->ch->setUrl($url);
        $this->ch->setIsJson(true);
        $this->ch->setMethod($this->method);
        $this->ch->setUserPwd($this->clientId, $this->clientSecret);
        $this->ch->setHeader($header);
        $this->ch->setParameterFromArray($param);
        $this->ch->setSslVerifypeer(false);
        $response = $this->ch->exec();
        $this->ch->close();
        
        return json_decode($response);// 第2引数trueで配列型
    }

    /**
     * CMS API (プロキシ経由方式 )
     * 
     * @param  array $param
     * @param  string $url
     * @return mixed
     */
    protected function legacyCreateObject($param, $url)
    {
        $request_url  = "{$this->cmsUrl}/v1/accounts/{$this->accountId}$url";
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
        curl_close($ch);
        
        return json_decode($response);
    }

    /**
     * DI API (プロキシ経由方式 )
     * 
     * @param  array $param
     * @param  string $url
     * @return mixed
     */
    protected function legacyDynamicIngest($param, $url)
    {
        $request_url  = urlencode("{$this->diUrl}/v1/accounts/{$this->accountId}$url");
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
        curl_close($ch);
        
        return json_decode($response);
    }

    /**
     * VideoCloud 出力用の属性値を取得する
     *
     * @return array
     */
    public static function getAttributes()
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
            
            'poster_url'         => 'BCサムネイルURL（大）',
            'thumbnail_url'      => 'BCサムネイルURL（小）',
        ];
    }

    /**
     * Setter...
     */
    public function setVideoId($videoId)
    {
        $this->videoId = $videoId;
        return $this;
    }

    public function setAssetsId($assetsId)
    {
        $this->assetsId = $assetsId;
        return $this;
    }

    public function setFolderId($folderId)
    {
        $this->folderId = $folderId;
        return $this;
    }

    public function setVideoProfile($videoProfile)
    {
        $this->videoProfile = $videoProfile;
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

    public function setCh($ch)
    {
        $this->ch = $ch;
        return $this;
    }

    public function setMethod($method)
    {
        $this->method = $method;
        return $this;
    }

    /**
     * Getter...
     */
    public function getVideoId()
    {
        return $this->videoId;
    }

    public function getAssetsId()
    {
        return $this->assetsId;
    }

    public function getFolderId()
    {
        return $this->folderId;
    }

    public function getVideoProfile()
    {
        return $this->videoProfile;
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

    public function getCh()
    {
        return $this->ch;
    }

    public function getMethod()
    {
        return $this->method;
    }

}
