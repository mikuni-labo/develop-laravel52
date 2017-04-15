<?php

namespace App\Lib\Api\VideoCloud;

/**
 * VideoCloud Video Resources
 * 
 * @author Kuniyasu Wada
 */
Trait VideosClient
{
    /** @var string Video ID */
    private $videoId;

    /** @var string Callback URL */
    private $callbackUrl;

    /** @var string Video Profile */
    private $videoProfile;

    /**
     * Request to CMS API for Create Video Object...
     * 
     * @param  array $param
     * @return mixed
     */
    public function createVideo($param = array())
    {
        $url = "{$this->getCmsUrl()}/v1/accounts/{$this->getAccountId()}/videos";
        $header = [
            'Content-type: application/json',
            "Authorization: Bearer {$this->getAccessToken()}",
        ];
        
        return $this->call('POST', $url, $header, $param);
    }

    /**
     * Request to CMS API for Update Video Object...
     * 
     * @param  array $param
     * @return mixed
     */
    public function updateVideo($param = array())
    {
        $url = "{$this->getCmsUrl()}/v1/accounts/{$this->getAccountId()}/videos/{$this->getVideoId()}";
        $header = [
            'Content-type: application/json',
            "Authorization: Bearer {$this->getAccessToken()}",
        ];
        
        return $this->call('PATCH', $url, $header, $param);
    }

    /**
     * Request to CMS API for get video object...
     * 
     * @param  array $param
     * @return mixed
     */
    public function getVideos($param = array())
    {
        $url = "{$this->getCmsUrl()}/v1/accounts/{$this->getAccountId()}/videos";
        $header = [
            "Authorization: Bearer {$this->getAccessToken()}",
        ];
        
        return $this->call('GET', $url, $header, $param);
    }

    /**
     * Request to CMS API for get video object...
     * 
     * @return mixed
     */
    public function getVideo()
    {
        $url = "{$this->getCmsUrl()}/v1/accounts/{$this->getAccountId()}/videos/{$this->getVideoId()}";
        $header = [
            "Authorization: Bearer {$this->getAccessToken()}",
        ];
        
        return $this->call('GET', $url, $header);
    }

    /**
     * Request to CMS API for get video object...
     *
     * @return mixed
     */
    public function getVideoCount()
    {
        $url = "{$this->getCmsUrl()}/v1/accounts/{$this->getAccountId()}/counts/videos";
        $header = [
            "Authorization: Bearer {$this->getAccessToken()}",
        ];
        
        return $this->call('GET', $url, $header);
    }

    /**
     * Request to CMS API for Disable video object...
     * 
     * @param  array $param
     * @return mixed
     */
    public function deleteVideo()
    {
        $url    = "{$this->getCmsUrl()}/v1/accounts/{$this->getAccountId()}/videos/{$this->getVideoId()}";
        $header = [
            'Content-type: application/json',
            "Authorization: Bearer {$this->getAccessToken()}",
        ];
        
        return $this->call('DELETE', $url, $header);
    }

    /**
     * Request to Dynamic Ingest API for Ingest Video...
     *
     * @param  array $param
     * @return mixed
     */
    public function dynamicIngest($param = array())
    {
        $url = "{$this->getDiUrl()}/v1/accounts/{$this->getAccountId()}/videos/{$this->getVideoId()}/ingest-requests";
        $header = [
            'Content-type: application/json',
            "Authorization: Bearer {$this->getAccessToken()}",
        ];
        
        /** XXX APIから原因不明のInternal Server Errorが返される時は、プロファイル設定を要チェック */
        return $this->call('POST', $url, $header, $param);
    }

    public function setVideoId($videoId)
    {
        $this->videoId = $videoId;
        return $this;
    }

    public function getVideoId()
    {
        return $this->getVideoId();
    }

    public function setVideoProfile($videoProfile)
    {
        $this->videoProfile = $videoProfile;
        return $this;
    }

    public function getVideoProfile()
    {
        return $this->videoProfile;
    }

    public function setCallbackUrl($callbackUrl)
    {
        $this->callbackUrl = $callbackUrl;
        return $this;
    }

    public function getCallbackUrl()
    {
        return $this->callbackUrl;
    }

}
