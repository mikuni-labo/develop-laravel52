<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Lib\cURL;

/**
 * Api Response...
 * 
 * @author Kuniyasu_Wada
 */
class MedistockApiController extends Controller
{
    /**
     * コンストラクタ
     */
    public function __construct()
    {
        //
    }
    
    /**
     * 受注取得
     */
    public function medistockApi()
    {
        /**
         * ベース
         */
        $ip       = '192.168.99.100';
        $version  = 'v1';
        $resource = 'order';
//         $resource = 'product';
        $crud     = 'get';
        $site     = 'medistock';

        $url = "http://{$ip}/{$site}/api/{$version}/{$resource}/{$crud}";

        $param = [
            /**
             * Common
             */
            'key_id'       => 'k3NEWYug',
            
            /**
             * Order
             */
            'created_at_start'  => '2016-03-01 00:00:00',// 注文日開始
            'create_at_end'     => '2016-04-19 23:59:59',// 注文日終了
            'updated_at_start'  => '2016-03-01 00:00:00',// 更新日開始
            'updated_at_end'    => '2016-04-19 23:59:59',// 更新日終了
//             'limit'             => 1,// 最大取得件数 Default: 20
//             'offset'            => 2,// オフセット   Default: 0
//             'sort'              => '',// ソート条件   Default: 更新日 desc
            
            /**
             * Product
             */
            'product_id'   => 1,
            'product_code' => 'ice-01',
        ];

        $header = [
//             'Content-type: application/json',
//             'Content-Type: application/x-www-form-urlencoded',
//             'Access-Control-Allow-Methods',
//             "Authorization: Bearer {$token}",
        ];

        $ch = new cURL();
        $ch->init();
        $ch->setUrl($url);
        $ch->setUserAgent('Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1');
        $ch->setMethod('GET');
        $ch->setSslVerifypeer(false);
//         $ch->setUserPwd($this->authId, $this->authPass);
        
        $ch->setParameterFromArray($param);
        $ch->setHeader($header);
        
//         $ch->setIsBuildQuery(false);
//         $ch->setIsJson(true);
        
        $response = $ch->exec();
//         echo $response;exit;
//         dd( $response );
//         dd( unserialize($response) );

//         \Storage::disk('local')->put('json/oanda.json', $response);

//         dd( $ch->getInfo() );

//         dd( $ch->getErrorMessage() );

        $ch->close();

        $result = json_decode($response);
        dd( $result );
    }
    
    /**
     * setter/getter生成テスト
     *
     * @method GET
     */
    public function showSetterAndGetter()
    {
        $result1 = $result2 = '';
        
        $properties = [
            'order_id',
            'order_temp_id',
            'customer_id',
            'message',
            'order_name01',
            'order_name02',
            'order_kana01',
            'order_kana02',
            'order_company_name',
            'order_email',
            'order_tel01',
            'order_tel02',
            'order_tel03',
            'order_fax01',
            'order_fax02',
            'order_fax03',
            'order_zip01',
            'order_zip02',
            'order_zipcode',
            'order_country_id',
            'order_pref',
            'order_addr01',
            'order_addr02',
            'order_sex',
            'order_birth',
            'order_job',
            'subtotal',
            'discount',
            'deliv_id',
            'deliv_fee',
            'charge',
            'use_point',
            'add_point',
            'birth_point',
            'tax',
            'total',
            'payment_total',
            'payment_id',
            'payment_method',
            'note',
            'status',
            'create_date',
            'update_date',
            'commit_date',
            'payment_date',
            'device_type_id',
            'del_flg',
            'memo01',
            'memo02',
            'memo03',
            'memo04',
            'memo05',
            'memo06',
            'memo07',
            'memo08',
            'memo09',
            'memo10',
        ];
        
        $properties = [
            'authClient',
            'videosClient',
            'playlistsClient',
            'assetsClient',
            'foldersClient',
            'notificationsClient',
        ];
        
        foreach ($properties as $val)
        {
//             dd( studly_case($val) );
//             dd( camel_case($val) );

        /* 以下、キャメルケース */
//             $result1 .= '
//     private $'.camel_case($val).';
// ';
//             $result2 .='
//     public function set'.studly_case($val).'($'.camel_case($val).')
//     {
//         $this->'.camel_case($val).' = $'.camel_case($val).';
//         return $this;
//     }

//     public function get'.studly_case($val).'()
//     {
//         return $this->'.camel_case($val).';
//     }
// ';
        /* 以下、スネークケース */
            $result1 .= '
    private $'.$val.';
';
            
            $result2 .='
    public function set'.studly_case($val).'($'.$val.')
    {
        $this->'.$val.' = $'.$val.';
        return $this;
    }
            
    public function get'.studly_case($val).'()
    {
        return $this->'.$val.';
    }
';
        }
        
//         dd($result1);
        dd($result2);
        print_r($result2);exit;
    
//         return \Response::make($xml->asXML(), 200, $header);
    }

}
