<?php

namespace App\Lib\Api\VideoCloud;

use App\Lib\Api\VideoCloud\VideoCloudConnection;

/**
 * VideoCloud 認証クラス
 * 
 * @author Kuniyasu Wada
 */
class AuthClient extends VideoCloudConnection
{
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
    
    /**
     * Create a new class instance.
     * 
     * @return void
     */
    public function __construct()
    {
        //
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
     * Setter...
     */
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

    /**
     * Getter...
     */
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

}
