<?php

namespace App\Services;

use MikuniLabo\VideoCloudy\VideoCloudy;
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
    public function __construct(VideoCloudy $VideoCloudy)
    {
        $this->VideoCloud = $VideoCloudy;
        $this->Log = $this->createLogger('VideoCloud', storage_path('logs/videocloud'));

        $this->VideoCloud->setAccountId(    config('api.videocloud.account_id') );
        $this->VideoCloud->setClientId(     config('api.videocloud.client_id') );
        $this->VideoCloud->setClientSecret( config('api.videocloud.client_secret') );
        $this->VideoCloud->setVideoProfile( config('api.videocloud.video_profile') );
        $this->VideoCloud->setCallbackUrl(  config('api.videocloud.callback_url') );
        $this->VideoCloud->setAuthUrl(      config('api.videocloud.auth_url') );
        $this->VideoCloud->setCmsUrl(       config('api.videocloud.cms_url') );
        $this->VideoCloud->setDIUrl(        config('api.videocloud.di_url') );

//         if( ! $this->checkAuth() )
//         {
            $this->VideoCloud->authenticate();

//             if( empty($this->VideoCloud->getAccessToken()) ) {
//                 $this->Log->error($this->VideoCloud->getCh()->getErrorMessage(), ["CURL 認証エラー}"]);
//             } else {
//                 session()->put('videocloud.auth.access_token', $this->VideoCloud->getAccessToken());
//                 session()->put('videocloud.auth.expires_on', $this->VideoCloud->getExpiresOn());
//             }
//         }
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
        $result = $this->VideoCloud->getVideos();
        dd($result);


        $this->VideoCloud->setVideoId('5400047085001');
//         $this->VideoCloud->setFolderId('57a1904ce4b01010036e4137');
//         $this->VideoCloud->setPlaylistId('5398944324001');
            $this->VideoCloud->setAssetsId('5400117229001');
//             $this->VideoCloud->setSubscriptionId('');

//         $result = $this->VideoCloud->getThumbnail('nhb_tablet_02');
//         $result = $this->VideoCloud->deleteVideo('nhb_tablet_02');
//             $result = $this->VideoCloud->getRendition('livetest');

        $result = $this->VideoCloud->getCaption();
//         $result = $this->VideoCloud->updateCaption([
//             'reference_id' => 'updateCaptionupdateCaptionupdateCaption',
//             'remote_url'   => 'updateCaptionupdateCaptionupdateCaptionupdateCaption',
//         ]);
//         print_r($result);exit;
        dd($result);

        $result = $this->VideoCloud->createVideo([
            'reference_id' => \Carbon::now()->format('r'),
            'name'         => 'test!_18',
        ]);

        if( empty($result->id) ) dd($result);

        $this->VideoCloud->setVideoId($result->id);

        $param = [
            "master" => [
//                 'url' => 'https://s3-ap-northeast-1.amazonaws.com/s3-cci/videos/Brightcove_sample.mp4',
                'url' => 'https://s3-ap-northeast-1.amazonaws.com/s3-cci/videos/summer-sun.mp4',
//                 'url' => 'https://s3-ap-northeast-1.amazonaws.com/s3-cci/videos/beautiful-sight.mp4',
            ],
            'poster' => [
//                 "url"    => 'https://s3-ap-northeast-1.amazonaws.com/s3-cci/images/640x360.png',
                "url"    => 'https://s3-ap-northeast-1.amazonaws.com/s3-cci/images/640x360.jpg',
                "width"  => 640,
                "height" => 480,
            ],
            'thumbnail' => [
                "url"    => 'https://s3-ap-northeast-1.amazonaws.com/s3-cci/images/640x360.png',
//                 "url"    => 'https://s3-ap-northeast-1.amazonaws.com/s3-cci/images/640x360.jpg',
                "width"  => 160,
                "height" => 120,
            ],
            "callbacks" => [
                $this->VideoCloud->getCallbackUrl(),
            ],
            "profile"        => $this->VideoCloud->getVideoProfile(),
            "capture-images" =>  false,
        ];

        $result = $this->VideoCloud->dynamicIngestMediaAsset($param);

//         dd('here');
        print_r($result);exit;


//         $result = $this->VideoCloud->getVideos([
//             'q' => 'name:summer-sun',
//         ]);


        /**
         * 動画リスト
         */
//         dd( $this->VideoCloud->getVideos([
//             'limit'   => 20,
//             'offset'  => 0,
//             'q'       => 'name:所さん',
//         ]) );

        /**
         * 動画削除
         */
//         $arr = [

//         ];

//         foreach ($arr as $videoId)
//         {
//             $this->VideoCloud->setVideoId($videoId);
//             $result = $this->VideoCloud->deleteVideo();
//         }
//         dd( 'end' );

        return $result;
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

}
