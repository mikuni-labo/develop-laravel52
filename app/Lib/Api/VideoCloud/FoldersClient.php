<?php

namespace App\Lib\Api\VideoCloud;

use App\Lib\Api\VideoCloud\VideoCloudConnection;

/**
 * VideoCloud Folders 操作クラス
 * 
 * @author Kuniyasu Wada
 */
class FoldersClient extends VideoCloudConnection
{
    /** @var フォルダID */
    private $folderId;
    
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
     * Setter...
     */
    public function setFolderId($folderId)
    {
        $this->folderId = $folderId;
        return $this;
    }

    /**
     * Getter...
     */
    public function getFolderId()
    {
        return $this->folderId;
    }

}
