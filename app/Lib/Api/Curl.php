<?php

namespace App\Lib\Api;

/**
 * Curl Client
 *
 * @name    Curly
 * @version 1.0.0
 * @license MIT
 * @author  Kuniyasu Wada @mikuni_labo
 * @link    https://github.com/mikuni-labo
 * @since   Sun, 16 Apr 2017 08:16:01 +0900
 */
class Curl
{
    /** @var Curl */
    private $ch;

    /** @var string */
    private $url;

    /** @var string */
    private $method = 'GET';

    /** @var string */
    private $userAgent;

    /** @var string */
    private $userpwd;

    /** @var bool */
    private $returnTransfer = true;

    /** @var bool */
    private $sslVerifypeer = false;

    /** @var bool */
    private $jsonTransfer = false;

    /** @var bool */
    private $buildQuery = true;

    /** @var array */
    private $header = array();

    /** @var array */
    private $parameter = array();

    /** @var string */
    private $errorMessage;

    public function __construct()
    {
    }

    public function init()
    {
        $this->ch = curl_init();
    }

    public function exec()
    {
        try {
            switch($this->method)
            {
                case ('POST'):
                    if( $this->jsonTransfer ) {
                        $this->parameter = json_encode($this->parameter);
                    }
                    elseif( $this->buildQuery ) {
                        $this->parameter = http_build_query($this->parameter);
                    }

                    curl_setopt($this->ch, CURLOPT_POST, true);
                    curl_setopt($this->ch, CURLOPT_POSTFIELDS, $this->parameter);
                    break;

                case ('PUT'):
                    if( $this->jsonTransfer ) {
                        $this->parameter = json_encode($this->parameter);
                    }
                    elseif( $this->buildQuery ) {
                        $this->parameter = http_build_query($this->parameter);
                    }

                    curl_setopt($this->ch, CURLOPT_CUSTOMREQUEST , 'PUT');
                    curl_setopt($this->ch, CURLOPT_POSTFIELDS, $this->parameter);
                    break;

                case ('PATCH'):
                    if( $this->jsonTransfer ) {
                        $this->parameter = json_encode($this->parameter);
                    }
                    elseif( $this->buildQuery ) {
                        $this->parameter = http_build_query($this->parameter);
                    }

                    curl_setopt($this->ch, CURLOPT_CUSTOMREQUEST , 'PATCH');
                    curl_setopt($this->ch, CURLOPT_POSTFIELDS, $this->parameter);
                    break;

                case ('DELETE'):
                    if( $this->jsonTransfer ) {
                        $this->parameter = json_encode($this->parameter);
                    }
                    elseif( $this->buildQuery ) {
                        $this->parameter = http_build_query($this->parameter);
                    }

                    curl_setopt($this->ch, CURLOPT_CUSTOMREQUEST , 'DELETE');
                    curl_setopt($this->ch, CURLOPT_POSTFIELDS, $this->parameter);
                    break;

                case ('GET'):
                default:
                    curl_setopt($this->ch, CURLOPT_HTTPGET, true);
                    $this->setUrl($this->url . '?' . http_build_query($this->parameter));
            }

            if(isset($this->userAgent)) {
                curl_setopt($this->ch, CURLOPT_USERAGENT, $this->userAgent);
            }

            if(isset($this->userpwd)) {
                curl_setopt($this->ch, CURLOPT_USERPWD, $this->userpwd);
            }

            if(isset($this->header)) {
                curl_setopt($this->ch, CURLOPT_HTTPHEADER, $this->header);
            }

            curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, $this->returnTransfer);
            curl_setopt($this->ch, CURLOPT_SSL_VERIFYPEER, $this->sslVerifypeer);
            curl_setopt($this->ch, CURLOPT_URL, $this->url);

            return curl_exec($this->ch);

        } catch (\Exception $e) {
            $this->errorMessage = $e->getMessage();
        }

        return null;
    }

    public function close()
    {
        curl_close($this->ch);
    }

    public function reset()
    {
        curl_reset($this->ch);
    }

    public function setUrl($url)
    {
        $this->url = $url;
        return $this;
    }

    public function getUrl()
    {
        return $this->url;
    }

    public function setMethod($method)
    {
        $this->method = $method;
        return $this;
    }

    public function getMethod()
    {
        return $this->method;
    }

    public function setUserAgent($userAgent)
    {
        $this->userAgent = $userAgent;
        return $this;
    }

    public function getUserAgent()
    {
        return $this->userAgent;
    }

    public function setUserPwd($user, $pwd)
    {
        $this->userpwd = $user. ':'. $pwd;
        return $this;
    }

    public function getUserPwd()
    {
        return $this->userpwd;
    }

    public function setReturnTransfer($returnTransfer)
    {
        $this->returnTransfer = $returnTransfer;
        return $this;
    }

    public function getReturnTransfer()
    {
        return $this->returnTransfer;
    }

    public function setSslVerifypeer($sslVerifypeer)
    {
        $this->sslVerifypeer = $sslVerifypeer;
        return $this;
    }

    public function getSslVerifypeer()
    {
        return $this->sslVerifypeer;
    }

    public function setJsonTransfer($jsonTransfer)
    {
        $this->jsonTransfer = $jsonTransfer;
        return $this;
    }

    public function getJsonTransfer()
    {
        return $this->jsonTransfer;
    }

    public function setBuildQuery($buildQuery)
    {
        $this->buildQuery = $buildQuery;
        return $this;
    }

    public function getBuildQuery()
    {
        return $this->buildQuery;
    }

    public function setParameter($key, $value)
    {
        $this->parameter[$key] = $value;
        return $this;
    }

    public function setParameterFromArray($parameter)
    {
        $this->parameter = $parameter;
        return $this;
    }

    public function getParameter($key)
    {
        return $this->parameter[$key];
    }

    public function setHeader($header)
    {
        $this->header[] = $header;
        return $this;
    }

    public function setHeaderFromArray($header)
    {
        $this->header = $header;
        return $this;
    }

    public function getHeader()
    {
        return $this->header;
    }

    public function getErrorMessage()
    {
        return $this->errorMessage;
    }

}
