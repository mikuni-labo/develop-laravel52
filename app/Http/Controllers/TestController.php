<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Lib\Api\Curl;
use App\Models\User;
use App\Services\BcApiService;
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
    /**
     * コンストラクタ
     */
    public function __construct()
    {
        $this->middleware('guest:user', ['except' => ['login', 'auth']]);
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
     * AWS S3 Test
     *
     * @method GET
     */
    public function testAwsS3()
    {
        $Disk = Storage::disk('s3-ndi');

        // ディレクトリ作成
//         $Disk->makeDirectory($directory_name);

        // 指定ディレクトリのディレクトリ一覧
        $res = $Disk->directories();

        // ディレクトリ一覧（再帰的）（おそらく -R オプション）
//         $Disk->allDirectories($directory_name);

        // 指定ディレクトリのファイル一覧
//         $res = $Disk->files('tx');

        // 指定ディレクトリの全ファイル一覧（再帰的）※階層は全てスラッシュで表現され、レコード単位で返される
//         $res = $Disk->allFiles();

        // ファイルの存在確認
//         $Disk->has('tx/test.txt');

        // ファイルのアップロード
//         $Disk->put('vr/test.txt', file_get_contents(storage_path('app/vr/test.txt')));

        // ファイルの取得
//         $Disk->get('vr/test.txt');

        // ファイルのコピー
//         $Disk->copy('vr/test.txt', 'vr/test2.txt');

        // ファイルの移動・リネーム
//         $Disk->move('vr/test.txt', 'vr/test2.txt');

        // ファイルのフルURL取得
//         $Disk->url('tx/test.txt');

        // ファイルのサイズ取得（バイト）
//         $Disk->size('tx/test.txt');

        // ファイルの最終更新時間（UNIX秒）
//         $Disk->lastModified('tx/test.txt');

        // ファイルの先頭に挿入
//         $Disk->prepend('tx/test.txt', 'Prepended Text');

        // ファイルの末尾に挿入
//         $Disk->append('tx/test.txt', 'Appended Text');

        // ファイルの抽象的視認性確認（?）
//         $Disk->getVisibility('tx/test.txt');

        // ファイルの抽象的視認性セット（?）
//         $Disk->setVisibility('tx/test.txt', 'private');

        // ファイルの削除
//         $res = $Disk->delete([
//             "app/notification.jsonnotification.json",
//         ]);

        dd( $res );

//         foreach ($Disk->files('tx') as $file) {
//             $Disk->delete($file);
//         }

        \Flash::success('ok!');
        return redirect()->route('test');
    }

    /**
     * VideoCloud Test
     *
     * @param  BcApiService $BcApiService
     * @method GET
     */
    public function testVideoCloud(BcApiService $BcApiService)
    {
        return response()->json( $BcApiService->test() );
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
     * Chatwork API TEST
     *
     * @method GET
     */
    public function chatworkTest(Request $request)
    {
        dd('here');

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
