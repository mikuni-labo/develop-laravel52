<?php

namespace App\Services;

use App\Lib\Api\VideoCloud\VideoCloud;
use App\Traits\Log;

/**
 * VideoCloudデータ連携処理クラス
 * 
 * @author Kuniyasu Wada
 */
class BcApiService
{
    use Log;
    
    private $Log;
    private $VideoCloud;
    
    /**
     * Create a new class instance.
     * 
     * @return void
     */
    public function __construct(VideoCloud $VideoCloud)
    {
        $this->VideoCloud = $VideoCloud;
        $this->Log = $this->createLogger('VideoCloud', storage_path('logs/videocloud'));
        
        $this->VideoCloud->setAccountId(    config('api.videocloud.account_id') );
        $this->VideoCloud->setClientId(     config('api.videocloud.client_id') );
        $this->VideoCloud->setClientSecret( config('api.videocloud.client_secret') );
        $this->VideoCloud->setVideoProfile( config('api.videocloud.video_profile') );
        $this->VideoCloud->setCallbackUrl(  config('api.videocloud.callback_url') );
        $this->VideoCloud->setAuthUrl(      config('api.videocloud.auth_url') );
        $this->VideoCloud->setCmsUrl(       config('api.videocloud.cms_url') );
        $this->VideoCloud->setDIUrl(        config('api.videocloud.di_url') );
        
        if( ! $this->checkAuth() )
        {
            $this->VideoCloud->authenticate();
            
            if( empty($this->VideoCloud->getAccessToken()) ) {
                $this->Log->error($this->VideoCloud->getCh()->getErrorMessage(), ["CURL 認証エラー}"]);
            } else {
                session()->put('videocloud.auth.access_token', $this->VideoCloud->getAccessToken());
                session()->put('videocloud.auth.expires_on', $this->VideoCloud->getExpiresOn());
            }
        }
    }

    /**
     * Check Auth
     * 
     * @return bool
     */
    private function checkAuth()
    {
        if( session()->has('videocloud.auth.access_token')
            && session()->has('videocloud.auth.expires_on')
            && intVal( session()->get('videocloud.auth.expires_on')) >= time() )
        {
            $this->VideoCloud->setAccessToken(session()->get('videocloud.auth.access_token'));
            return true;
        }
        return false;
    }
    
    /**
     * test
     *
     * @return mixed
     */
    public function test()
    {
        /**
         * test
         */
        $this->VideoCloud->setVideoId('5244105250001');
        
        $result = $this->VideoCloud->getCustomFields();
//         $result = $this->VideoCloud->getPlaylists('nhb_slow_motion_drop_hd_stock_video');
        
        return response()->json($result);
        
        /**
         * 動画リスト
         */
        dd( $this->VideoCloud->getVideos([
            'limit'   => 20,
            'offset'  => 0,
        ]) );
        
        /**
         * 動画削除
         */
//         $videoIds = [
            
//         ];
        
//         foreach ($videoIds as $videoId)
//         {
//             $this->VideoCloud->setVideoId($videoId);
//             $this->VideoCloud->deleteVideo();
//         }
        
//         dd( 'end' );
    }

}
