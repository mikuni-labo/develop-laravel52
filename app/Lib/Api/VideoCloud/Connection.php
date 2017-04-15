<?php

namespace App\Lib\Api\VideoCloud;

use App\Lib\Api\Curl;

/**
 * Connection for VideoCloud
 * 
 * @author Kuniyasu Wada
 */
class Connection
{
    /** @var Curl $ch */
    private $ch;

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
     * @param  string $method
     * @param  string $url
     * @param  array  $header
     * @param  array  $param
     * @return mixed
     */
    protected function call($method, $url, $header = array(), $param = array())
    {
        $this->ch->init();
        $this->ch->setUrl($url);
        $this->ch->setIsJson(true);
        $this->ch->setMethod($method);
        $this->ch->setUserPwd($this->getClientId(), $this->getClientSecret());
        $this->ch->setHeader($header);
        $this->ch->setParameterFromArray($param);
        $this->ch->setSslVerifypeer(false);
        $response = $this->ch->exec();
        $this->ch->close();
        
        return json_decode($response);
    }

    /**
     * Setter...
     */
    protected function setCh($ch)
    {
        $this->ch = $ch;
        return $this;
    }

    /**
     * Getter...
     */
    public function getCh()
    {
        return $this->ch;
    }

}
