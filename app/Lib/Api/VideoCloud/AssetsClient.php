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
        $url = "{$this->getCmsUrl()}/v1/accounts/{$this->getAccountId()}/videos/{$this->getVideoId()}/assets/poster";
        $header = [
            'Content-type: application/json',
            "Authorization: Bearer {$this->getAccessToken()}",
        ];
        
        return $this->call('POST', $url, $header, $param);
    }

    /**
     * ポスター画像更新（リモートアセット）
     * 
     * @param  array  $param
     * @return mixed
     */
    public function updateRemotePoster($param = array())
    {
        $url = "{$this->getCmsUrl()}/v1/accounts/{$this->getAccountId()}/videos/{$this->getVideoId()}/assets/poster/{$this->getAssetsId()}";
        $header = [
            'Content-type: application/json',
            "Authorization: Bearer {$this->getAccessToken()}",
        ];
        
        return $this->call('PATCH', $url, $header, $param);
    }

    /**
     * ポスター画像削除（リモートアセット）
     * 
     * @return mixed
     */
    public function deleteRemotePoster()
    {
        $url = "{$this->getCmsUrl()}/v1/accounts/{$this->getAccountId()}/videos/{$this->getVideoId()}/assets/poster/{$this->getAssetsId()}";
        $header = [
            'Content-type: application/json',
            "Authorization: Bearer {$this->getAccessToken()}",
        ];
        
        return $this->call('DELETE', $url, $header);
    }

    /**
     * サムネイルリスト取得（リモートアセット）
     * 
     * @return mixed
     */
    public function getRemoteThumbnail()
    {
        $url = "{$this->getCmsUrl()}/v1/accounts/{$this->getAccountId()}/videos/{$this->getVideoId()}/assets/thumbnail";
        $header = [
            'Content-type: application/json',
            "Authorization: Bearer {$this->getAccessToken()}",
        ];
        
        return $this->call('GET', $url, $header);
    }

    /**
     * サムネイル登録（リモートアセット）
     * 
     * @param  array $param
     * @return mixed
     */
    public function addRemoteThumbnail($param = array())
    {
        $url = "{$this->getCmsUrl()}/v1/accounts/{$this->getAccountId()}/videos/{$this->getVideoId()}/assets/thumbnail";
        $header = [
            'Content-type: application/json',
            "Authorization: Bearer {$this->getAccessToken()}",
        ];
        
        return $this->call('POST', $url, $header, $param);
    }

    /**
     * サムネイル更新（リモートアセット）
     * 
     * @param  array  $param
     * @return mixed
     */
    public function updateRemoteThumbnail($param = array())
    {
        $url = "{$this->getCmsUrl()}/v1/accounts/{$this->getAccountId()}/videos/{$this->getVideoId()}/assets/thumbnail/{$this->getAssetsId()}";
        $header = [
            'Content-type: application/json',
            "Authorization: Bearer {$this->getAccessToken()}",
        ];
        
        return $this->call('PATCH', $url, $header, $param);
    }

    /**
     * サムネイル削除（リモートアセット）
     * 
     * @return mixed
     */
    public function deleteRemoteThumbnail()
    {
        $url = "{$this->getCmsUrl()}/v1/accounts/{$this->getAccountId()}/videos/{$this->getVideoId()}/assets/thumbnail/{$this->getAssetsId()}";
        $header = [
            'Content-type: application/json',
            "Authorization: Bearer {$this->getAccessToken()}",
        ];
        
        return $this->call('DELETE', $url, $header);
    }

    public function setAssetsId($assetsId)
    {
        $this->assetsId = $assetsId;
        return $this;
    }

    public function getAssetsId()
    {
        return $this->assetsId;
    }

}
