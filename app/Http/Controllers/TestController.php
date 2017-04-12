<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Lib\BAMP;
use App\Lib\cURL;
use App\Models\Program;
use App\Models\Code;
use App\Models\Episode;
use App\Models\User;
use App\Models\TxVideo;
use App\Models\GyaoVideo;
use App\Models\NicoVideo;
use Illuminate\Contracts\Mail\Mailer;
use Mail;
use Storage;
use ZendPdf\PdfDocument;
use ZendPdf\Font;
use ZendPdf\Page;
use ZendPdf\Resource\Extractor;
use Goutte\Client;
use Cart;

class TestController extends Controller
{
    const SAMPLE_PROGRAMS = [
            'konno_dance' => [
                    'id'           => 'konno_dance',
                    'title'        => '紺野、今から踊るってよ',
                    'overview'     => 'テレビ東京アナウンサー・紺野あさ美が美女と踊るだけの番組！',// 概要
                    'genre'        => 'バラエティ',
                    'site_url'     => 'http://www.tv-tokyo.co.jp/konno_dance/',
                    'keywords'     => 'アナウンサー,ダンス,テレ東,紺野,紺野、今から踊るってよ,美女,踊るってよ,見逃し配信,無料,動画',
                    'copyright'    => 'Copyright(c)TV TOKYO Corporation All rights reserved.',
                    'vod_url'      => '',
                    'vod_title'    => '',
                    'programImage' => 'http://video.tv-tokyo.co.jp/konno_dance/tver/images/pg.jpg',
                    'logoImage'    => 'http://video.tv-tokyo.co.jp/konno_dance/tver/images/logo.jpg',
                    'vodImage'     => '',
            ],
            'chimata' => [
                    'id'           => 'chimata',
                    'title'        => 'test2',
                    'overview'     => 'test2',
                    'genre'        => 'test2',
                    'site_url'     => 'test2',
                    'keywords'     => 'test2',
                    'copyright'    => 'test2',
                    'vod_url'      => 'test2',
                    'vod_title'    => 'test2',
                    'programImage' => 'test2',
                    'logoImage'    => 'test2',
                    'vodImage'     => 'test2',
            ],
    ];
    
    const SAMPLE_EPISODES = [
            'konno_dance' => [// 番組と紐付くキー
                    '4719976511001' => [
                            'movie'          => '4719976511001',// 放送回のBC動画ID
                            'thumbnailImage' => 'http://tool03.tv-tokyo.co.jp/video/konno_dance/images/20160127_konno_dance_01_b.jpg?1453700072',
                            'episodeTitle'   => '和紙を使った妖艶な灯りの中で電気グルーヴ「Shangri-La」を京都美人と踊ってみた',
                            'desctiption'    => 'テレビ東京アナウンサー・紺野あさ美が美女と踊るだけの番組！京都らしくて可愛い…和紙を使った照明器具に囲まれながら電気グルーヴ「Shangri-La」を中橋実希と踊ってみた！▽紺野、ドラマに出てきそうな街並みに思わずうっとり…　テレビ東京にて毎週水・木曜深夜1時30分より放送中！',
                            'cast'           => '',
                            'staff'          => '',
                            'broadcastDate'  => '2016-01-27 25:30',// 放送日
                            'publishDate'    => '2016-01-27 25:35',// 公開日
                            'endDate'        => '2016-02-03 25:34',// 終了日
                            'deleteFlg'      => '0',
                    ],
                    '000000000001' => [
                            'movie'          => '000000000001',
                            'thumbnailImage' => 'test1',
                            'episodeTitle'   => 'test1',
                            'desctiption'    => 'test1',
                            'cast'           => 'test1',
                            'staff'          => 'test1',
                            'broadcastDate'  => '0000-00-00 00:00',
                            'publishDate'    => '0000-00-00 00:00',
                            'endDate'        => '0000-00-00 00:00',
                            'deleteFlg'      => '1',
                    ],
            ],
            'chimata' => [
                    '4840054864001' => [
                            'movie'          => '4840054864001',
                            'thumbnailImage' => 'test2',
                            'episodeTitle'   => 'スポーツ特集',
                            'desctiption'    => 'test2',
                            'cast'           => 'K.Wada',
                            'staff'          => 'K.Wada',
                            'broadcastDate'  => '0000-00-00 00:00',
                            'publishDate'    => '0000-00-00 00:00',
                            'endDate'        => '0000-00-00 00:00',
                            'deleteFlg'      => '1',
                    ],
            ],
    ];
    
    /**
     * コンストラクタ
     */
    public function __construct()
    {
        $this->middleware('guest:user', ['except' => ['login', 'auth']]);
    
        $this->imagePath     = base_path() . '/public/uploads/product/';
        $this->thumbnailPath = base_path() . '/public/uploads/product/thumbnail/';
        $this->imageMod      = '644';
        $this->imageNum      = 3;
        $this->thumbWidth    = 180;
        $this->prefix        = 'product_';
    }
    
    /**
     * GET Test
     * 
     * @method GET
     */
    public function getTest(Request $request)
    {
        return view('test.test');
    }
    
    /**
     * POST Test
     * 
     * @method POST
     */
    public function postTest(Request $request, $id = null)
    {
        /**
         * バリデーション
         */
        $this->doValidate($request);
        
        dd('ok');
        
/*
        $inputs = $request->all();
    
        // 画像アップロード有り
        if ($request->hasFile('image'))
        {
            $filepath = $this->prefix . date("YmdHis") . '.' . $request->file('image')->getClientOriginalExtension();
            $inputs["image"] = $filepath;
                
            $Image = \Image::make($request->file('image')->getRealPath());
            $Image->save($this->imagePath . $filepath)
            ->resize($this->thumbWidth, null, function ($constraint) {$constraint->aspectRatio();})
            ->save($this->thumbnailPath . 'thumb-' . $filepath);
                
            // パーミッションを変更しておく
            //            chmod($this->imagePath . $filepath, $this->imageMod);
            //            chmod($this->thumbnailPath . 'thumb-' . $filepath, $this->imageMod);
        }
        else
            unset($inputs["image"]);
    
        // add
        if(is_null($id))
        {
            Product::create($inputs);
                
            \Flash::success('製品情報を登録しました。');
        }
        // edit
        else {
            $product = Product::find($id);
                
            // 古い画像の削除
            if ( $product['image'] && ($request->hasFile('image') || isset($inputs['del_image'])) )
            {
                \File::delete($this->imagePath . $product['image']);
                \File::delete($this->thumbnailPath . 'thumb-' . $product['image']);

                // 新規アップロード画像がなく、削除フラグが立っていればDBの画像パスを削除
                if( !$request->hasFile('image') && isset($inputs['del_image']) && $inputs['del_image'] == 1)
                    $inputs["image"] = null;
            }
                
            $product->update($inputs);
            \Flash::success('製品情報を更新しました。');
        }
*/
        \Flash::success('Through the Validation!');
        return redirect('test');
    }
    
    /**
     * Send Mail Test
     * 
     * @method GET
     * @param Mailer $mailer
     * @param User $user
     */
    public function sendTestMail(Request $request, User $user)
    {
        Mail::send('emails.test', ['user' => $user], function ($mail) use ($user) {
            $mail->to('wada@n-di.co.jp', '和田');
            $mail->from('wada@n-di.co.jp', '管理者');
            $mail->subject('Hello World!');
            
//            $mail->from($address, $name = null);
//            $mail->sender($address, $name = null);
//            $mail->to($address, $name = null);
//            $mail->cc($address, $name = null);
//            $mail->bcc($address, $name = null);
//            $mail->replyTo($address, $name = null);
//            $mail->subject($subject);
//            $mail->priority($level);
//            $mail->attach($pathToFile, array $options = []);
            
//            // $data文字列をそのまま付属ファイルへ…
//            $mail->attachData($data, $name, array $options = []);
            
//            // 裏で動作するSwiftMailerメッセージインスタンスの取得…
//            $mail->getSwiftMessage();
        });
    /*
        // swift mailer
        $mailer->send(
                'emails.confirm',
                ['user' => $user, 'token' => $user->confirmation_token],
                function($message) use ($user) {
                    $message->to('wada@n-di.co.jp', 'K.Wada')->subject('Send Mail Test');
                }
        );
    */
        \Flash::success('Sent!');
        return redirect('test');
    }
    
    /**
     * JSON取得テスト
     * 
     * @method GET
     */
    public function getJSON(Request $request)
    {
        /*
        $ch = new cURL();
        //dd($request);
        
        $ch->init();
        $ch->setUrl('https://bamp.api.tv-tokyo.co.jp/videocms/get.pl');
        //$ch->setUrl('https://cms:xi9XD5pE@bamp.api.tv-tokyo.co.jp/videocms/get.pl');
        //$ch->setUrl('http://www.yahoo.co.jp/');
        // https://cms:xi9XD5pE@bamp.api.tv-tokyo.co.jp/videocms/get.pl?txcms_program_id=82
        //curl --anyauth --user cms:xi9XD5pE https://bamp.api.tv-tokyo.co.jp/videocms/get.pl?txcms_program_id=82
        
        $ch->setUserAgent('Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1');
        
        $ch->setMethod('POST');
        
        $ch->setUserPwd('cms', 'xi9XD5pE');
        $ch->setParameter('txcms_program_id', '82,1205418');
        //$ch->setHeader($header);
        
        $response = $ch->exec();
        
        
        //print_r(curl_getinfo($ch));
        
//        if(curl_errno($ch))
//        {
//            echo curl_errno('error: '.$ch);exit();
//        }
        
        $ch->close();
        
        $data = json_decode($response);
        
        //print_r($data);exit();
        */


        /*
         * cURL通信サンプル
         */
//        $username = 'cms';
//        $password = 'xi9XD5pE';
        
//        //$url = "https://www.n-di.co.jp/";
//        $url = "https://bamp.api.tv-tokyo.co.jp/videocms/get.pl";
        
//        $headers = [
//                "HTTP/1.0",
//                "Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8",
//                "Accept-Encoding:gzip ,deflate",
//                "Accept-Language:ja,en-us;q=0.7,en;q=0.3",
//                "Connection:keep-alive",
//                "User-Agent:Mozilla/5.0 (Macintosh; Intel Mac OS X 10.9; rv:26.0) Gecko/20100101 Firefox/26.0"
//        ];
        
        
//        $post_fields = [
//                'txcms_program_id' => '82',
//        ];
        
//        echo phpinfo();exit();
        
//        $ch = curl_init(); // 宣言
//        curl_setopt($ch, CURLOPT_URL, $url);
//        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
//        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);// 変数に保存する
//        curl_setopt($ch, CURLOPT_POST, true);
//        curl_setopt($ch, CURLOPT_USERPWD, $username . ":" . $password);
//        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post_fields));
//        curl_setopt($ch, CURLOPT_HEADER, true);
//        //curl_setopt($ch, CURLOPT_PROXYPORT, 3128);
//        //curl_setopt($ch, CURLOPT_PROXY, "192.168.0.1");// IPかURL

//        $response = curl_exec($ch);
        
//        if(curl_errno($ch))
//        {
//            echo curl_errno('error: '.$ch);exit();
//        }
        
//        //print_r(curl_getinfo($ch));exit();
        
//        curl_close($ch);
        
//        //$objRes = json_decode($response);
        
//        print $response;exit();
//        //print (mb_detect_encoding($response));exit();
//        print(htmlentities($response));exit();
        
        
        /*
         * ここからJSON取得したと仮定
         */
        $json = file_get_contents(base_path('public/data/get.json'));
        
        $data = json_decode($json);
        
        try {
            // 取り込み開始
            if(count($data->programs) > 0)
            {
                $source = array_flip(\Config::get('TX.source'));
                $mode = 'BAMP';
                
                \DB::beginTransaction();
                
                /*
                 * プログラムループ
                */
                foreach ($data->programs as $key => $program)
                {
if($key == 5){
                    /* ここから */
                    if($program->delete_flag === true)
                        continue;
                    
                    // 既存データの存在確認
                    $resultProgram = Program::getProgramWithTxCmsProgramID($program->txcms_program_id, $source[$mode]);
                    $inputs = [];
                    $inputs['source'] = $source[$mode];
                    $objProgram = false;// 後に必要なので定義しておく
                    
                    // INSERT
                    if(count($resultProgram) == 0)
                    {
                        // キーマッピング配列を取得
                        $keyMap = Program::getInsertKeyMap();
                        
                        foreach ($program as $_k => $_v)
                        {
                            // 値があればマッピングしたカラム名で格納
                            if( !empty($_v) && array_key_exists($_k, $keyMap) )
                                $inputs[$keyMap[$_k]] = $_v;
                        }
                        
                        $inputs = BAMP::cleanFormat($inputs, 'program');
                        $objProgram = Program::create($inputs);
                    }
                    // UPDATE
                    elseif ($program->modified_on != $resultProgram[0]->bamp_modified_on)// BAMP側で更新があったかを判定
                    {
                        // 論理削除されていないデータ
                        if( !empty($objProgram = Program::find($resultProgram[0]->id)) )
                        {
                            // キーマッピング配列を取得
                            $keyMap = Program::getUpdateKeyMap();
                                
                            foreach ($program as $_k => $_v)
                            {
                                // 値があればマッピングしたカラム名で格納
                                if( !empty($_v) && array_key_exists($_k, $keyMap) )
                                    $inputs[$keyMap[$_k]] = $_v;
                            }
                            $inputs = BAMP::cleanFormat($inputs, 'program');
                            $objProgram->update($inputs);
                        }
                    }
                        
                    $mainFlagOnCodes = [];
                    
                    /*
                     * コードループ
                    */
                    foreach ($program->codes as $code)
                    {
                        $resultCode = Code::getCodeWithEpgAndTxCmsProgramID($program->txcms_program_id, $code->code, $source[$mode]);
                        $inputs = [];
                        $inputs['source'] = $source[$mode];
                        
                        // INSERT
                        if(count($resultCode) == 0)
                        {
                            // キーマッピング配列を取得
                            $keyMap = Code::getInsertKeyMap();
                                
                            foreach ($code as $_k => $_v)
                            {
                                // 値があればマッピングしたカラム名で格納
                                if( !empty($_v) && array_key_exists($_k, $keyMap) )
                                    $inputs[$keyMap[$_k]] = $_v;
                            }
                            // TXWEB番組マスタID
                            $inputs['txcms_program_id'] = $program->txcms_program_id;
                            $objCode = Code::create($inputs);
                        }
                        // UPDATE
                        elseif ($code->modified_on != $resultCode[0]->bamp_modified_on)// BAMP側で更新があったかを判定
                        {
                            // 論理削除されていないデータ
                            if( !empty($objCode = Code::find($resultCode[0]->id)) )
                            {
                                // キーマッピング配列を取得
                                $keyMap = Code::getUpdateKeyMap();
                                
                                foreach ($code as $_k => $_v)
                                {
                                    // 値があればマッピングしたカラム名で格納
                                    if( !empty($_v) && array_key_exists($_k, $keyMap))
                                        $inputs[$keyMap[$_k]] = $_v;
                                }
                                $objCode->update($inputs);
                            }
                        }
                        
                        // BSJからのデータはmain_flag が false固定なので、全て取得する
                        if($mode === 'BSJ')
                            $mainFlagOnCodes[] = $code->code;
                        
                        // 本編フラグがTRUEのコードを配列へ
                        elseif( $code->main_flag === true )
                        $mainFlagOnCodes[] = $code->code;
                    }
                    
                    /*
                     * エピソードループ
                     */
                    foreach ($program->episodes as $k => $episode)
                    {
                        $oa_date = \Carbon::parse($episode->oa_date);
                        $resultEpisodes = Episode::getEpisode($program->txcms_program_id, $episode->code, $oa_date->format('Y-m-d'), $source[$mode]);
                        $inputs = [];
                        $inputs['source'] = $source[$mode];
                        
                        // 本編フラグ がTRUE, かつ再放送フラグ がFALSE, かつ削除フラグがFALSE
                        if ( in_array( $episode->code, $mainFlagOnCodes )
                                && $episode->oa_repeat_flag === false
                                && $episode->delete_flag === false )
                        {
                            // INSERT
                            if(count($resultEpisodes) == 0)
                            {
                                // キーマッピング配列を取得
                                $keyMap = Episode::getInsertKeyMap();
                                
                                foreach ($episode as $_k => $_v)
                                {
                                    // 値があればマッピングしたカラム名で格納
                                    if( !empty($_v) && array_key_exists($_k, $keyMap))
                                        $inputs[$keyMap[$_k]] = $_v;
                                }
                                $inputs = BAMP::cleanFormat($inputs, 'episode');
                                    
                                // TXWEB番組マスタID
                                $inputs['txcms_program_id'] = $program->txcms_program_id;
                                $objEpisode = Episode::create($inputs);
                                
                                // 各シンジケーションテーブルへ、ユニークIDとともに新規登録
                                TxVideo::InsertWithTxUniqueID($objEpisode->id);
                                GyaoVideo::InsertWithGyaoUniqueID($objEpisode->id);
                                NicoVideo::InsertWithNicoUniqueID($objEpisode->id);
                                
                            }
                            // UPDATE
                            elseif ($episode->modified_on == $resultEpisodes[0]->bamp_modified_on)// BAMP側で更新があったかを判定
                            {
                                // 論理削除されていないデータ
                                if( !empty($objEpisode = Episode::find($resultEpisodes[0]->id)) )
                                {
                                    // キーマッピング配列を取得
                                    $keyMap = Episode::getUpdateKeyMap();
                                    
                                    foreach ($episode as $_k => $_v)
                                    {
                                        // 値があればマッピングしたカラム名で格納
                                        if( !empty($_v) && array_key_exists($_k, $keyMap))
                                            $inputs[$keyMap[$_k]] = $_v;
                                    }
                                    $inputs = BAMP::cleanFormat($inputs, 'episode');
                                    
                                    $objEpisode->update($inputs);
                                }
                            }
                        }
                        // 削除フラグが立っていて、かつEPGコードがメインフラグでないエピソードは紐付け解除で保留対象データ
                        elseif ( !in_array( $episode->code, $mainFlagOnCodes)
                                && $episode->delete_flag === false
                                && count($resultEpisodes) > 0 )
                        {
                            // 論理削除されていないデータ
                            if( count($objEpisode = Episode::find($resultEpisodes[0]->id)) > 0 )
                            {
                                // 各配信先で一箇所でも配信中なら保留フラグを立てる
                                if( (bool)$objEpisode->tx_provide_status === true
                                        || (bool)$objEpisode-> gyao_provide_status === true
                                        || (bool)$objEpisode->nico_provide_status === true
                                        || (bool)$objEpisode->tver_provide_status === true )
                                {
                                    $objEpisode->update(['hold_flag' => true]);
                                }
                                else
                                    $objEpisode->delete();
                            }
                        }
                    }
                    /* ここまで */
}
                }
                \DB::commit();
                \Flash::success('EPG情報を取得しました。');
                //return true;
            }
            else {
                \Flash::error('取得可能なEPG情報がありませんでした。');
            }
        }
        catch (\Exception $e) {
            \Flash::error($e->getMessage());
        }
        return redirect('/test');
    }
    
    /**
     * JSON表示
     * 
     * @method GET
     */
    public function showJSON(Request $request)
    {
        $json = file_get_contents(base_path('public/data/get.json'));
        
        $data = json_decode($json);
        
        // キーを整列
        //rsort($data->programs);
        
        dd($data->programs);
    }
    
    /**
     * XML生成テスト
     * 
     * @method GET
     */
    public function outputXML(Request $request)
    {
        /*
         * 相互運用性その 2 -- SimpleXML がDOM をインポートする
         */
        
        // 番組テーブルから取得したデータ配列サンプル
        $arrProgramData = self::SAMPLE_PROGRAMS;
        
        // 放送テーブルから取得したデータ配列サンプル
        $arrEpisodeData = self::SAMPLE_EPISODES;
        
        $dom = new \DomDocument('1.0', 'UTF-8');
        $dom->formatOutput = true;
        $dom->preserveWhiteSpace = false;
        $programs = $dom->appendChild($dom->createElement('programs'));
        
        // 番組毎
        foreach ($arrProgramData as $key => $val)
        {
            $program[$key] = $programs->appendChild($dom->createElement('program'));
            $program[$key]->setAttribute('id', $key);// 番組のid属性に番組コードを付与
            
            $title = $program[$key]->appendChild($dom->createElement('title'));
            $title->appendChild($dom->createTextNode($val['title']));
            
            $overview = $program[$key]->appendChild($dom->createElement('overview'));
            $overview->appendChild($dom->createTextNode($val['overview']));
            
            $genre = $program[$key]->appendChild($dom->createElement('genre'));
            $genre->appendChild($dom->createTextNode($val['genre']));
            
            $site_url = $program[$key]->appendChild($dom->createElement('site_url'));
            $site_url->appendChild($dom->createTextNode($val['site_url']));
            
            $keywords = $program[$key]->appendChild($dom->createElement('keywords'));
            $keywords->appendChild($dom->createTextNode($val['keywords']));
            
            $copyright = $program[$key]->appendChild($dom->createElement('copyright'));
            $copyright->appendChild($dom->createTextNode($val['copyright']));
            
            $vod_url = $program[$key]->appendChild($dom->createElement('vod_url'));
            $vod_url->appendChild($dom->createTextNode($val['vod_url']));
            
            $vod_title = $program[$key]->appendChild($dom->createElement('vod_title'));
            $vod_title->appendChild($dom->createTextNode($val['vod_title']));
            
            $programImage = $program[$key]->appendChild($dom->createElement('programImage'));
            $programImage->appendChild($dom->createTextNode($val['programImage']));
            
            $logoImage = $program[$key]->appendChild($dom->createElement('logoImage'));
            $logoImage->appendChild($dom->createTextNode($val['logoImage']));
            
            $vodImage = $program[$key]->appendChild($dom->createElement('vodImage'));
            $vodImage->appendChild($dom->createTextNode($val['vodImage']));
            
            // TODO データを取得する際に、エピソードが空の番組データを持つのかどうかで、ここで条件分岐が必要
            if(count($arrEpisodeData[$key]) > 0)
            {
                $episodes = $program[$key]->appendChild($dom->createElement('episodes'));
                
                // 番組に紐付く放送毎
                foreach ($arrEpisodeData[$key] as $k => $v)
                {
                    $episode[$k] = $episodes->appendChild($dom->createElement('episode'));
                    
                    $movie = $episode[$k]->appendChild($dom->createElement('movie'));
                    $movie->setAttribute('id', $k);// movieのid属性にブライトコーブIDを付与
                    
                    $thumbnailImage = $episode[$k]->appendChild($dom->createElement('thumbnailImage'));
                    $thumbnailImage->appendChild($dom->createTextNode($v['thumbnailImage']));
                    
                    $episodeTitle = $episode[$k]->appendChild($dom->createElement('episodeTitle'));
                    $episodeTitle->appendChild($dom->createTextNode($v['episodeTitle']));
                    
                    $desctiption = $episode[$k]->appendChild($dom->createElement('desctiption'));
                    $desctiption->appendChild($dom->createTextNode($v['desctiption']));
                    
                    $cast = $episode[$k]->appendChild($dom->createElement('cast'));
                    $cast->appendChild($dom->createTextNode($v['cast']));
                    
                    $staff = $episode[$k]->appendChild($dom->createElement('staff'));
                    $staff->appendChild($dom->createTextNode($v['staff']));
                    
                    $broadcastDate = $episode[$k]->appendChild($dom->createElement('broadcastDate'));
                    $broadcastDate->appendChild($dom->createTextNode($v['broadcastDate']));
                    
                    $publishDate = $episode[$k]->appendChild($dom->createElement('publishDate'));
                    $publishDate->appendChild($dom->createTextNode($v['publishDate']));
                    
                    $endDate = $episode[$k]->appendChild($dom->createElement('endDate'));
                    $endDate->appendChild($dom->createTextNode($v['endDate']));
                    
                    $deleteFlg = $episode[$k]->appendChild($dom->createElement('deleteFlg'));
                    $deleteFlg->appendChild($dom->createTextNode($v['deleteFlg']));
                }
            }
        }
        
        Storage::disk('local')->put('xml/test.xml', chr(239) . chr(187) . chr(191) . $dom->saveXML());
        
        return response()->download( storage_path('app/xml/test.xml'), 'test.xml', [
            'Content-Type' => 'text/xml',
        ]);
        
        dd('here');
        
//         $simpleXML = simplexml_import_dom($dom);
//         Storage::disk('local')->put('xml/test.xml', chr(239) . chr(187) . chr(191) . $simpleXML->asXML());
//         $simpleXML->asXML(base_path('public/data/programs.xml'));
    }
    
    /**
     * OANDA API TEST
     *
     * @method GET
     */
    public function oandaTest(Request $request)
    {
//         $url = 'http://www.gaitameonline.com/rateaj/getrate';
//         $param = [
//                 //
//         ];
        
//         $header = [
//                 //'Content-type: application/json',
// //                 'Content-Type: application/x-www-form-urlencoded',
// //                 "Authorization: Bearer {$token}",
//                 ];
        
//         $ch = new cURL();
//         $ch->init();
//         $ch->setUrl($url);
//         $ch->setUserAgent('Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1');
//         $ch->setMethod('GET');
//         //$ch->setSslVerifypeer(false);
//         //$ch->setUserPwd($this->authId, $this->authPass);
//         //$ch->setParameterFromArray($param);
//         $ch->setHeader($header);
        
//         //$ch->setIsBuildQuery(false);
//         //$ch->setIsJson(true);
        
//         $response = $ch->exec();
//         //dd($response);
        
//         //$ch->getInfo();
//         $ch->getErrorMessage();
        
//         $ch->close();
        
//         $result = json_decode($response);
        
//         \Storage::disk('local')->put('app/test/gaitame.json', $response);
//         dd($result);
        
        
        
        
        
        
        
        
        /**
         * 認証
         */
        $token  = '9f115789328b60354d791744fe939e88-8d55cda34df04882838c53eb37e5d507';
        $header = [
                //'Content-type: application/json',
                'Content-Type: application/x-www-form-urlencoded',
                'Access-Control-Allow-Methods',
                "Authorization: Bearer {$token}",
        ];
        
        /**
         * ベース
         */
        $accountId = '9836455';
        $version = 'v1';
        $format = 'api';
//         $format = 'stream';
        
//         $base = "http://{$format}-sandbox.oanda.com/{$version}";
        $base = "https://{$format}-fxpractice.oanda.com/{$version}";
//         $base = "https://{$format}-fxtrade.oanda.com/{$version}";
        
        /**
         * エンドポイント
         */
//         $endPoint = "/accounts";   // アカウント情報
        $endPoint = "/instruments";// 通貨リスト
//         $endPoint = "/candles";    // レート情報
//         $endPoint = "/prices";     // レート情報 (eTag?)
//         $endPoint = '/';
        
        $param = [
                'instrument'  => 'USD_JPY',// 米ドル ok 
                'instrument'  => 'EUR_JPY',// ユーロ ok 
                'instrument'  => 'CNY_JPY',// 中国元 ng 
                
                'instrument'  => 'TWD_JPY',// 台湾ドル ng
                'instrument'  => 'HKD_JPY',// 香港ドル ok
                'instrument'  => 'KRW_JPY',// 韓国ウォン ng
                
                'instrument'  => 'GBP_JPY',// 英ポンド ok
                'instrument'  => 'CHF_JPY',// スイスフラン ok
                'instrument'  => 'AUD_JPY',// 豪ドル ok
                
                'instrument'  => 'CAD_JPY',// 加ドル ok
                'instrument'  => 'THB_JPY',// タイ・バーツ ok
                'instrument'  => 'SGD_JPY',// シンガポール・ドル ok
                
//                 'start'       => 137849394,
                'count'       => 1,
        ];
        
        $countries = [
                'USD_JPY',// 米ドル
                'EUR_JPY',// ユーロ
                'CNY_JPY',// 中国元
                
                'TWD_JPY',// 台湾ドル
                'HKD_JPY',// 香港ドル
                'KRW_JPY',// 韓国ウォン ng
                
                'GBP_JPY',// 英ポンド
                'CHF_JPY',// スイスフラン
                'AUD_JPY',// 豪ドル
                
                'CAD_JPY',// 加ドル
                'THB_JPY',// タイ・バーツ
                'SGD_JPY',// シンガポール・ドル
        ];
        
        $param = [
                'instruments' => implode(',', $countries),
//                 'start'       => 137849394,
                'count'       => 1,
        ];
        
        $param = [
                'accountId'   => $accountId,
        ];
        
        $ch = new cURL();
        $ch->init();
        $ch->setUrl($base.$endPoint);
        $ch->setUserAgent('Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1');
        $ch->setMethod('GET');
        $ch->setSslVerifypeer(false);
//         $ch->setUserPwd($this->authId, $this->authPass);
        
        $ch->setParameterFromArray($param);
        $ch->setHeader($header);
        
//         $ch->setIsBuildQuery(false);
//         $ch->setIsJson(true);
        
        $response = $ch->exec();
//         /dd($response);
        \Storage::disk('local')->put('json/oanda.json', $response);
        
        //$ch->getInfo();
        $ch->getErrorMessage();
        
        $ch->close();
        
        $result = json_decode($response);
        dd($result);
    }
    
    /**
     * CurrencyLayer API TEST
     *
     * @method GET
     */
    public function currencyLayerTest(Request $request)
    {
        /**
         * 認証
         */
        $token  = '03ec93074f83e3574d7ebec844a6e79c';
        $header = [
                //'Content-type: application/json',
                'Content-Type: application/x-www-form-urlencoded',
//                 'Access-Control-Allow-Methods',
//                 "Authorization: Bearer {$token}",
                ];
    
        /**
         * ベース
         */
        $base = "http://apilayer.net/api";
        
        /**
         * エンドポイント
         */
//         $endPoint = '/live';        // 最新の為替レート取得
        /*
            ? access_key = YOUR_ACCESS_KEY
            & source = GBP
            & currencies = USD,AUD,CAD,PLN,MXN
            & format = 1
         */
        
        $endPoint = '/historical';  // 特定の日の履歴レートをリクエストする
        /*
            ? access_key = YOUR_ACCESS_KEY
            & date = YYYY-MM-DD
            & source = EUR
            & currencies = USD,AUD,CAD,PLN,MXN
            & format = 1
         */
        
        
        $endPoint = '/convert';     // リアルタイム為替レートを使用して、1つの通貨から別の通貨に任意の金額を変換する
        /*
            ? access_key = YOUR_ACCESS_KEY
            & from = USD
            & to = EUR
            & amount = 25
            & format = 1
         */
        
        
        $endPoint = '/timeframe';   // 特定の期間の為替レートを要求する
        /*
            ? access_key = YOUR_ACCESS_KEY
            & start_date = YYYY-MM-DD
            & end_date = YYYY-MM-DD
            & source = EUR
            & currencies = USD,AUD,CAD,PLN,MXN
            & format = 1
         */
        
        
        $endPoint = '/change';      // 任意の通貨の変更パラメータを要求する（マージンオプションで2つの指定日の間
        /*
            ? access_key = YOUR_ACCESS_KEY
            & source = GBP
            & currencies = USD,AUD,CAD,PLN,MXN
            & format = 1
         */
        
        
        $param = [
                'access_key' => $token,
//                 'source' => 'JPY',
//                 'currencies' => '',
        ];
        
        $ch = new cURL();
        $ch->init();
        $ch->setUrl($base.$endPoint);
        $ch->setUserAgent('Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1');
        $ch->setMethod('GET');
        $ch->setSslVerifypeer(false);
//         $ch->setUserPwd($this->authId, $this->authPass);
        
        $ch->setParameterFromArray($param);
        $ch->setHeader($header);
        
//         $ch->setIsBuildQuery(false);
//         $ch->setIsJson(true);
        
        $response = $ch->exec();
//         /dd($response);
//         \Storage::disk('local')->put('json/oanda.json', $response);
        
//         $ch->getInfo();
//         $ch->getErrorMessage();
        
        $ch->close();
        
        $result = json_decode($response);
        dd($result);
    }
    
    /**
     * OpenExchangeRates API TEST
     *
     * @method GET
     */
    public function openExchangeRatesTest(Request $request)
    {
        /**
         * 認証
         */
        $token  = '694d232043c5483590c2e917eae36445';
        $header = [
//                 'Content-type: application/json',
                'Content-Type: application/x-www-form-urlencoded',
//                 'Access-Control-Allow-Methods',
//                 "Authorization: Bearer {$token}",
        ];
        
        /**
         * ベース
         */
        $base = "https://openexchangerates.org/api";
        
        /**
         * エンドポイント
         */
        $endPoint = '/latest.json';
//         $endPoint = '/currencies.json';
        
        $param = [
                'app_id' => $token,
                'base' => 'USD',
//                 'currencies' => '',
        ];
        
        $ch = new cURL();
        $ch->init();
        $ch->setUrl($base.$endPoint);
        $ch->setUserAgent('Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1');
        $ch->setMethod('GET');
        $ch->setSslVerifypeer(false);
//         $ch->setUserPwd($this->authId, $this->authPass);
        
        $ch->setParameterFromArray($param);
        $ch->setHeader($header);
        
//         $ch->setIsBuildQuery(false);
//         $ch->setIsJson(true);
        
        $response = $ch->exec();
//         /dd($response);
//         \Storage::disk('local')->put('json/oanda.json', $response);
        
//         $ch->getInfo();
//         $ch->getErrorMessage();
        
        $ch->close();
        
        $result = json_decode($response);
        dd($result);
    }
    
    /**
     * ハッシュテスト
     *
     * @method GET
     */
    public function hashTest()
    {
        //
    }
    
    /**
     * 物件概要書取り込みテスト
     *
     * @method GET
     */
    public function importPDF()
    {
        //PDFを読み込む
        $pdf = PdfDocument::load( storage_path('app/pdf/sample.pdf') );
        
//         $str = mb_convert_encoding($pdf->render(), 'UTF-8', 'SJIS-WIN');
        
        foreach ($pdf->pages as $page)
        {
            $str = mb_convert_encoding($page, 'UTF-8', 'SJIS-WIN');
            dd($str);
        }
        
        
        //ブラウザに表示
//         header ('Content-Type:', 'application/pdf');
//         header ('Content-Disposition:', 'inline;');
//         echo $pdf->render();
        
        $Disk = Storage::disk('local');
        //$pdf = $Disk->get('pdf/bukkengaiyousyo.pdf');
        $pdf = readfile('bukkengaiyousyo.pdf', true, file_get_contents( storage_path('app/pdf/bukkengaiyousyo.pdf') ));
    }
    
    /**
     * PDF変換テスト
     *
     * @method GET
     */
    public function pdf2txt()
    {
        $base_name = "bukkengaiyousyo";
        $now       = \Carbon::now()->format('YmdHis');
        $from_name = storage_path("app/pdf/{$base_name}.pdf");
        $to_name   = storage_path("app/pdf/{$base_name}_{$now}.txt");
        
//         $option   = "-layout";
        $option   = "";
        $command  = "pdftotext {$option} {$from_name} {$to_name}";
//         $command  = "pdfinfo {$option} {$from_name}";
        
        $output   = [];
        exec($command, $output, $return);
        
        dd($return);
        
        
//         $Disk = Storage::disk('local');
        //$pdf = $Disk->get('pdf/bukkengaiyousyo.pdf');
//         $pdf = readfile('bukkengaiyousyo.pdf', true, file_get_contents( storage_path('app/pdf/bukkengaiyousyo.pdf') ));
    }
    
    /**
     * XML レスポンステスト
     *
     * @method GET
     */
    public function responseXML()
    {
//         $json = file_get_contents(base_path('public/data/get.json'));
//         dd($json);
//         $data = json_decode($json);
        
        $data = self::SAMPLE_PROGRAMS;
        $status = 200;
        $header = [
                'Content-Type' => 'application/xml',
        ];
        
        /*
        $xml = new \SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><response/>');
        */
        
        $str = $this->array2string4xml('root', $data);
        $xml = simplexml_load_string($str);
        
        return \Response::make($xml->asXML(), 200, $header);
    }
    
    /**
     * 配列を再帰的にXML用の文字列に変換
     *
     * foreachの$valueの値が配列でなくなるまで再帰
     * ※ ['key' => array()]という場合も考慮
     *
     * @param  string $name 要素の名前
     * @param  array or string $data 配列もしくは要素の中身
     * @return string $str
     */
    private function array2string4xml($name = '', $data)
    {
        $str = '';
        
        if(!empty($name))
            $str .= "<".$name.">";
        
        if( !is_array($data) )
        {
            $str .= $data;
        }
        else {
            foreach ($data as $key => $val)
            {
                if(is_numeric($key))
                {
                    $str .= $this->array2string4xml('', $val);
                }
                else
                {
                    if(is_array($val) && !empty($val))
                    {
                        $str .= $this->array2string4xml($key, $val);
                    }
                    else
                    {
                        $str .= "<".$key.">";
                        $str .= (empty($val)) ? "" : $val;
                        $str .= "</".$key.">";
                    }
                }
            }
        }
        
        if(!empty($name))
            $str .= "</".$name.">";
        
        return $str;
    }
    
    /**
     * Scraping Test
     *
     * @method GET
     */
    public function scraping()
    {
        // みずほ銀行
        $this->getWebSite("http://www.mizuhobank.co.jp/rate/market/cash.html", "tbody");
        
        // 大黒屋
        $this->getWebSite("http://gaika.e-daikoku.com/", "tbody");
        
        // ドルユーロ
        $this->getWebSite("https://doru.jp/ex_multi/", "table");
        $this->getWebSite("https://doru.jp/gaika_kaitori/", "div#conts div.innner");
    }
    
    private function getWebSite($url, $tag)
    {
        // Create Goutte Object
        $client = new Client();
        
        // Get Data Source
        $crawler = $client->request('GET', $url);
        
        $crawler->filter($tag)->each(function ($node)
        {
            $text = $node->text();
            $arrText = explode("\n", $text);
//             $arrText = explode("\r\n", $text);
            
            dd($arrText);
        });
    }
    
    /**
     * Cart TEst
     *
     * @method GET
     */
    public function cart()
    {
//         if (Request::isMethod('post'))
//         {
//             $product_id = Request::get('product_id');
//             $product = Product::find($product_id);
//             Cart::add(array('id' => $product_id, 'name' => $product->name, 'qty' => 1, 'price' => $product->price));
//         }
        
        $cart = Cart::content();
        dd($cart);
        
        return view('cart', array('cart' => $cart, 'title' => 'Welcome', 'description' => '', 'page' => 'home'));
    }
    
    /**
     * phpinfo
     * 
     * @method GET
     */
    public function phpinfo()
    {
        echo phpinfo();
    }
    
    /**
     * JavaScript
     * 正規表現テスト
     *
     * @method GET
     */
    public function javascriptRegExp($number)
    {
        return view("test.regexp.{$number}");
    }
    
    /**
     * バリデーションオプションをセットしてバリデーションを実行する
     * 
     * @param  Request $request
     * @param  string $mode
     * @return void
     * @author Kuniyasu.Wada
     */
    private function doValidate($request, $mode = '')
    {
        $yesterday = date("Y-m-d", strtotime("-1 Day", time()));
        
        // モードによってルールを切り分ける
        switch ($mode) {
            case ('search'):
                $rules = [
                        'search'           => 'min:3',// キーワード無しなら全件ヒットさせるために、requireを外します
                ];
                break;
                
            default:
                $rules = [
                        'name'                 => 'max:255',
                        'age'                  => 'numeric|digits_between:1,3',
                        'price'                => 'numeric|digits_between:1,11',
                        'birth_day'            => 'date',
                        'image'                => 'image|mimes:jpg,jpeg,gif,png|max:10000',
                        'select'               => 'max:255|numeric|max:4',// SelectBoxは数値を取得
                        'text'                 => 'max:255',
                        'url'                  => 'url',// active_url : 有効なURL判定のはずだが、動作不良だった...
                        'start_date'           => "after:{$yesterday}|before:end_date",
                        'end_date'             => 'after:start_date',
                        
                ];
                // end_dateが空ならルールを変更
                if(strlen($request->end_date) == 0)
                    $rules['start_date'] = "after:{$yesterday}";
        }
        
        $messages = [
        
        ];
        
        $customAttributes = [
        
        ];
        
        $this->validate($request, $rules, $messages, $customAttributes);
    }
    
}
