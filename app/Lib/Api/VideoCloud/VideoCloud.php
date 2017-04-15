<?php

namespace App\Lib\Api\VideoCloud;

use App\Lib\Api\VideoCloud\AssetsClient as Assets;
use App\Lib\Api\VideoCloud\AuthClient as Auth;
use App\Lib\Api\VideoCloud\Connection;
use App\Lib\Api\VideoCloud\FoldersClient as Folders;
use App\Lib\Api\VideoCloud\NotificationsClient as Notifications;
use App\Lib\Api\VideoCloud\PlaylistsClient as Playlists;
use App\Lib\Api\VideoCloud\VideosClient as Videos;

/**
 * VideoCloud操作クラス
 * ※対応バージョン: v1
 * 
 * @author Kuniyasu Wada
 */
class VideoCloud extends Connection
{
    use Assets;
    use Auth;
    use Folders;
    use Notifications;
    use Playlists;
    use Videos;
    
    /**
     * Create a new class instance.
     * 
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

}
