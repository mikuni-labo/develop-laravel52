<?php

namespace App\Lib\Api\VideoCloud;

use App\Lib\Api\Curl;

/**
 * VideoCloud接続クラス
 * 
 * @author Kuniyasu Wada
 */
class Connection
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
        $this->setCh(new Curl);
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
        $this->ch->setMethod($this->getMethod());
        $this->ch->setUserPwd($this->getClientId(), $this->getClientSecret());
        $this->ch->setHeader($header);
        $this->ch->setParameterFromArray($param);
        $this->ch->setSslVerifypeer(false);
        $response = $this->ch->exec();
        $this->ch->close();
        
        return json_decode($response);// 第2引数trueで配列型
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
