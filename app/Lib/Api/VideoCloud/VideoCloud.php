<?php

namespace App\Lib\Api\VideoCloud;

use App\Lib\Api\VideoCloud\AssetsClient;
use App\Lib\Api\VideoCloud\AuthClient;
use App\Lib\Api\VideoCloud\FoldersClient;
use App\Lib\Api\VideoCloud\NotificationsClient;
use App\Lib\Api\VideoCloud\PlaylistsClient;
use App\Lib\Api\VideoCloud\VideosClient;

/**
 * VideoCloud操作クラス
 * ※対応バージョン: v1
 * 
 * @author Kuniyasu Wada
 */
class VideoCloud
{
    /** @var AssetsClient $assetsClient */
    private $assetsClient;
    
    /** @var AuthClient $authClient */
    private $authClient;
    
    /** @var FoldersClient $foldersClient */
    private $foldersClient;
    
    /** @var NotificationsClient $notificationsClient */
    private $notificationsClient;
    
    /** @var PlaylistsClient $playlistsClient */
    private $playlistsClient;
    
    /** @var VideosClient $videosClient */
    private $videosClient;
    
    /**
     * Create a new class instance.
     * 
     * @return void
     */
    public function __construct()
    {
        $this->setAssetsClient( new AssetsClient );
        $this->setAuthClient( new AuthClient );
        $this->setFoldersClient( new FoldersClient );
        $this->setNotificationsClient( new NotificationsClient );
        $this->setPlaylistsClient( new PlaylistsClient );
        $this->setVideosClient( new VideosClient );
    }

    /**
     * Setter...
     */
     private function setAssetsClient($assetsClient)
     {
         $this->assetsClient = $assetsClient;
         return $this;
     }
 
    private function setAuthClient($authClient)
    {
        $this->authClient = $authClient;
        return $this;
    }

    private function setFoldersClient($foldersClient)
    {
        $this->foldersClient = $foldersClient;
        return $this;
    }

    private function setNotificationsClient($notificationsClient)
    {
        $this->notificationsClient = $notificationsClient;
        return $this;
    }

    private function setPlaylistsClient($playlistsClient)
    {
        $this->playlistsClient = $playlistsClient;
        return $this;
    }

    private function setVideosClient($videosClient)
    {
        $this->videosClient = $videosClient;
        return $this;
    }

    public function setVideoId($videoId)
    {
        $this->videoId = $videoId;
        return $this;
    }

    /**
     * Getter...
     */
    public function getAssetsClient()
    {
        return $this->assetsClient;
    }

    public function getAuthClient()
    {
        return $this->authClient;
    }

    public function getFoldersClient()
    {
        return $this->foldersClient;
    }

    public function getNotificationsClient()
    {
        return $this->notificationsClient;
    }

    public function getPlaylistsClient()
    {
        return $this->playlistsClient;
    }

    public function getVideosClient()
    {
        return $this->videosClient;
    }

    public function getVideoId()
    {
        return $this->videoId;
    }

}
