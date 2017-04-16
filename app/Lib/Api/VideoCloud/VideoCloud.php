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
 * Operation for VideoCloud
 *
 * Support OAuth Version: v3
 * Support CMS Version: v1
 * Support Dynamic Injest Version: v1
 *
 * @name    VideoCloudy
 * @version 1.0.0
 * @license MIT
 * @author  Kuniyasu Wada @mikuni_labo
 * @link    https://github.com/mikuni-labo
 * @since   Sat, 15 Apr 2017 09:47:32 +0900
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
