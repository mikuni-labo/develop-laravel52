<?php

namespace App\Lib\Api\VideoCloud;

use App\Lib\Api\Curl;

/**
 * VideoCloud接続クラス
 * 
 * @author Kuniyasu Wada
 */
class VideoCloudConnection
{
    /** @var cURLインスタンス */
    private $ch;

    /** @var REQUEST METHOD */
    private $method;

    /**
     * Create a new class instance.
     * 
     * @return void
     */
    public function __construct()
    {
        $this->ch = new Curl;
    }

    /**
     * cURLライブラリで接続後、JSONをパースして返す
     * 
     * @param  string $url
     * @param  array  $header
     * @param  array  $param
     * @return mixed
     */
    protected function call($url, $header, $param = array())
    {
        $this->ch->init();
        $this->ch->setUrl($url);
        $this->ch->setIsJson(true);
        $this->ch->setMethod($this->method);
        $this->ch->setUserPwd($this->clientId, $this->clientSecret);
        $this->ch->setHeader($header);
        $this->ch->setParameterFromArray($param);
        $this->ch->setSslVerifypeer(false);
        $response = $this->ch->exec();
        $this->ch->close();
        
        return json_decode($response);// 第2引数trueで配列型
    }

    /**
     * CMS API (プロキシ経由方式 )
     * 
     * @param  array $param
     * @param  string $url
     * @return mixed
     */
    protected function legacyCreateObject($param, $url)
    {
        $request_url  = "{$this->cmsUrl}/v1/accounts/{$this->accountId}$url";
        $request_body = json_encode($param);
        
        $post_fields = "client_id=" . $this->clientId     . "&" .
                "client_secret="    . $this->clientSecret . "&" .
                "url="              . $request_url  . "&" .
                "requestBody="      . $request_body . "&" .
                "requestType="      . "POST";
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->proxyUrl);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_fields);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $response = curl_exec($ch);
        curl_close($ch);
        
        return json_decode($response);
    }

    /**
     * DI API (プロキシ経由方式 )
     * 
     * @param  array $param
     * @param  string $url
     * @return mixed
     */
    protected function legacyDynamicIngest($param, $url)
    {
        $request_url  = urlencode("{$this->diUrl}/v1/accounts/{$this->accountId}$url");
        $request_body = json_encode($param);
        
        $post_fields = "client_id=" . $this->clientId     . "&" .
                "client_secret="    . $this->clientSecret . "&" .
                "url="              . $request_url  . "&" .
                "requestBody="      . $request_body . "&" .
                "requestType="      . "POST";
            
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->proxyUrl);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_fields);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $response = curl_exec($ch);
        curl_close($ch);
        
        return json_decode($response);
    }

    /**
     * VideoCloud 出力用の属性値を取得する
     *
     * @return array
     */
    public static function getAttributes()
    {
        return [
            /**
             * General...
             */
            'name'               => '動画名',
            'state'              => '公開ステータス',
            'description'        => '短い説明',
            'reference_id'       => '参照ID',
            'link_text'          => 'リンクテキスト',
            'link_url'           => 'リンクURL',
            'tags'               => 'タグ',
            'starts_at'          => '公開開始日',
            'ends_at'            => '公開終了日',
            'geo_countries'      => '国',
            'geo_restricted'     => '国別視聴制限許可',
            'cue_points_type'    => 'キューポイント [type]',
            'cue_points_time'    => 'キューポイント [time]',
            
            /**
             * Custom Fields...
             */
            'cf_programKey'      => '番組キー',
            
            'poster_url'         => 'BCサムネイルURL（大）',
            'thumbnail_url'      => 'BCサムネイルURL（小）',
        ];
    }

    /**
     * Setter...
     */
    public function setCh($ch)
    {
        $this->ch = $ch;
        return $this;
    }

    public function setMethod($method)
    {
        $this->method = $method;
        return $this;
    }

    /**
     * Getter...
     */
    public function getCh()
    {
        return $this->ch;
    }

    public function getMethod()
    {
        return $this->method;
    }

}
