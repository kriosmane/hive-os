<?php

namespace KriosMane\HiveOs;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\RequestException;

/**
 * @TODO
 */
class HiveClient {

    /**
     * @const string HIVE OS API URL.
     */
    const BASE_API_URL = 'https://api2.hiveos.farm/api/v2/';

    /**
     * @var string
     */
    protected $access_token;

    /**
     * @var GuzzleHttp\Client;
     */
    protected $http_client = null;

    /**
     * @var string
     */
    protected $endpoint;

    /**
     * @var string
     */
    protected $method;

    /**
     * @var array 
     */
    protected $params;

    /**
     * @var array
     */
    protected $http_headers = [];

    /**
     * @var string
     */
    protected $user_agent = 'HiveClient 1.0';

    /**
     * @var boolean
     */
    protected $debug = false;

    /**
     * @var boolean
     */
    protected $verify = false;

    /**
     * @var integer
     */
    protected $timeout;

    /**
     * @var integer
     */
    protected $connection_timeout;

    /**
     * 
     */
    public function __construct($access_token) {


        $this->http_client  = new Client([

            'base_uri' => self::BASE_API_URL
        
        ]);

        $this->setAccessToken($access_token);

    }

    /**
     * @param string $access_token
     */
    public function setAccessToken($access_token)
    {
        $this->access_token = $access_token;
    }

    /**
     * @return string $access_token
     */
    public function getAccessToken()
    {
        return $this->access_token;
    }

    /**
     * @param string $endpint
     */
    public function setEndpoint($endpint)
    {
        $this->endpoint = $endpint;
    }

    /**
     * @return string $endpoint
     */
    public function getEndpoint()
    {
        return $this->endpoint;
    }

    /**
     * @param string $method
     */
    public function setMethod($method)
    {
        $this->method = $method;
    }

    /**
     * @return string $method
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * @param string $params
     */
    public function setParams($params)
    {
        $this->params = $params;
    }

    /**
     * @return string $params
     */
    public function getParams()
    {
        return $this->params;
    }

    /**
     * @param string $user_agent
     */
    public function setUserAgent($user_agent)
    {
        $this->user_agent = $user_agent;
    }

    /**
     * @return string $user_agent
     */
    public function getUserAgent()
    {
        return $this->user_agent;
    }

    /**
     * @param string $debug
     */
    public function setDebug($debug)
    {
        $this->debug = $debug;
    }

    /**
     * @return string $debug
     */
    public function getDebug()
    {
        return $this->debug;
    }

    /**
     * @param string $verify
     */
    public function setVerify($verify)
    {
        $this->verify = $verify;
    }

    /**
     * @return string $verify
     */
    public function getVerify()
    {
        return $this->verify;
    }

    /**
     * @param string $timeout
     */
    public function setTimeout($timeout)
    {
        $this->timeout = $timeout;
    }

    /**
     * @return string $timeout
     */
    public function getTimeout()
    {
        return $this->timeout;
    }

    /**
     * @param string $connection_timeout
     */
    public function setConnectionTimeout($connection_timeout)
    {
        $this->connection_timeout = $connection_timeout;
    }

    /**
     * @return string $connection_timeout
     */
    public function getConnectionTimeout()
    {
        return $this->connection_timeout;
    }

    /**
     * @param string $name header name
     * @param string $value heder value
     */
    public function setHeader($name, $value)
    {
        $this->headers[$name] = $value;
    }

    /**
     * 
     */
    public function getHeaders()
    {
        return $this->headers;
    }

    /**
     * Remove from header
     * 
     * @param string $header_key
     */
    public function removeHeader($header_key)
    {
        if(isset($this->headers[$header_key])) {

            unset($this->headers[$header_key]);

        }
    }

    /**
     * @param string $method The HTTP method
     * 
     * @return string option name
     */
    public function getParamsOptionName($method)
    {
        $option = '';

        switch($method){

            case 'GET':

                $option = 'query';

                break;

            case 'POST':

            case 'PUT':

            case 'DELETE':

            case 'PATCH':

                $option = 'form_params';

                break;
        }

        return $option;

    }

    /**
     * 
     * @param string  $method   The HTTP method for this request GET|POST|PUT|DELETE|PATCH
     * @param string  $endpoint The API endpoint for this request
     * @param array   $params   The parameters to send with this request
     * @param boolean $auth     Set if needed authentication
     */
    public function request($method = 'GET', $endpoint, $params = [], $auth = true){

        if($auth){

            $this->setHeader('Authorization', 'Bearer '.$this->getAccessToken());

        }else{

            $this->removeHeader('Authorization');

        }

        $this->setHeader('User-Agent', $this->getUserAgent());

        $this->setMethod($method);

        $this->setEndpoint($endpoint);


        $options = [

            'headers' => $this->getHeaders(),

            'verify'  => $this->getVerify(),

            'debug'  => $this->getDebug(),

            $this->getParamsOptionName($method) => $params
        ];

        try{

            $request = $this->http_client->request($method, $endpoint, $options);

            return new \KriosMane\HiveOs\HiveResponse($this, $request);
            
        } catch(ClientException $e) {

            return new \KriosMane\HiveOs\HiveResponse($this, $e->getResponse());

        } catch(ConnectException $e){

            return new \KriosMane\HiveOs\HiveResponse($this, $e->getResponse());
            
        } catch(RequestException $e){
            
            return new \KriosMane\HiveOs\HiveResponse($this, $e->getResponse());

        } 

    }

}



?>