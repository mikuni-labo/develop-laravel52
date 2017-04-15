<?php

namespace App\Lib\Api\VideoCloud;

/**
 * VideoCloud Asset Resources
 * 
 * @author Kuniyasu Wada
 */
Trait AssetsClient
{
    /** @var string The Assets ID */
    private $assetsId;

    /**
     * ポスター画像リスト取得（リモートアセット）
     * 
     * @return mixed
     */
    public function getRemotePoster()
    {
        $url = "{$this->getCmsUrl()}/v1/accounts/{$this->getAccountId()}/videos/{$this->getVideoId()}/assets/poster";
        $header = [
            'Content-type: application/json',
            "Authorization: Bearer {$this->getAccessToken()}",
        ];
        
        return $this->call('GET', $url, $header);
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
        $url = "{$this->getCmsUrl()}/v1/accounts/{$this->getAccountId()}/videos/{$this->getVideoId()}/assets/poster";
        $header = [
            'Content-type: application/json',
            "Authorization: Bearer {$this->getAccessToken()}",
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
        $url = "{$this->getCmsUrl()}/v1/accounts/{$this->getAccountId()}/videos/{$this->getVideoId()}/assets/poster/{$this->getAssetsId()}";
        $header = [
            'Content-type: application/json',
            "Authorization: Bearer {$this->getAccessToken()}",
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
        $url = "{$this->getCmsUrl()}/v1/accounts/{$this->getAccountId()}/videos/{$this->getVideoId()}/assets/poster/{$this->getAssetsId()}";
        $header = [
            'Content-type: application/json',
            "Authorization: Bearer {$this->getAccessToken()}",
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
        $url = "{$this->getCmsUrl()}/v1/accounts/{$this->getAccountId()}/videos/{$this->getVideoId()}/assets/thumbnail";
        $header = [
            'Content-type: application/json',
            "Authorization: Bearer {$this->getAccessToken()}",
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
        $url = "{$this->getCmsUrl()}/v1/accounts/{$this->getAccountId()}/videos/{$this->getVideoId()}/assets/thumbnail";
        $header = [
            'Content-type: application/json',
            "Authorization: Bearer {$this->getAccessToken()}",
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
        $url = "{$this->getCmsUrl()}/v1/accounts/{$this->getAccountId()}/videos/{$this->getVideoId()}/assets/thumbnail/{$this->getAssetsId()}";
        $header = [
            'Content-type: application/json',
            "Authorization: Bearer {$this->getAccessToken()}",
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
        $url = "{$this->getCmsUrl()}/v1/accounts/{$this->getAccountId()}/videos/{$this->getVideoId()}/assets/thumbnail/{$this->getAssetsId()}";
        $header = [
            'Content-type: application/json',
            "Authorization: Bearer {$this->getAccessToken()}",
        ];
        
        return $this->call($url, $header);
    }

    public function setAssetsId($assetsId)
    {
        $this->getAssetsId() = $assetsId;
        return $this;
    }

    public function getAssetsId()
    {
        return $this->getAssetsId();
    }

}
