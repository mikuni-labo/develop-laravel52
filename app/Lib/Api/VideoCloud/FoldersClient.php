<?php

namespace App\Lib\Api\VideoCloud;

/**
 * VideoCloud Folder Resources
 * 
 * @author Kuniyasu Wada
 */
Trait FoldersClient
{
    /** @var string フォルダID */
    private $folderId;

    /**
     * Request to CMS API for Create Folder...
     * 
     * @param  array $param
     * @return mixed
     */
    public function createFolder($param = array())
    {
        $url = "{$this->getCmsUrl()}/v1/accounts/{$this->getAccountId()}/folders";
        $header = [
            'Content-type: application/json',
            "Authorization: Bearer {$this->getAccessToken()}",
        ];
        
        return $this->call('POST', $url, $header, $param);
        
    }

    /**
     * Request to CMS API for Move Video to Folder...
     * 
     * @param  array $param
     * @return mixed
     */
    public function moveVideoToFolder($param = array())
    {
        $url = "{$this->getCmsUrl()}/v1/accounts/{$this->getAccountId()}/folders/{$this->getFolderId()}/videos/{$this->getVideoId()}";
        $header = [
            'Content-type: application/json',
            "Authorization: Bearer {$this->getAccessToken()}",
        ];
        
        return $this->call('PUT', $url, $header, $param);
    }

    public function setFolderId($folderId)
    {
        $this->folderId = $folderId;
        return $this;
    }

    public function getFolderId()
    {
        return $this->folderId;
    }

}
