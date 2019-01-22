<?php

namespace KriosMane\HiveOs;

use Psr\Http\Message\ResponseInterface;
use KriosMane\HiveOs\HiveClient;

/**
 * TODO
 */
class HiveResponse {

    /**
     * @var integer
     */
    protected $http_status_code;

    /**
     * @var object
     */
    protected $body;

    /**
     * @var object
     */
    protected $headers;

    /**
     * @var array
     */
    protected $decoded_body;

    /**
     * @var HiveClient
     */
    protected $request;

    /**
     *  
     */
    const RESPONSE_OK_CODE              = 200;
    const RESPONSE_CREATE_OK            = 201;
    const RESPONSE_TRANSFER_OK_CODE     = 204;
    const RESPONSE_FARM_UPDATED_OK_CODE = 204;
    const RESPONSE_ACTION_OK            = 204;
    const RESPONSE_DELETE_OK            = 204;
    const FLIGHT_SHEET_USED_BY_WORKERS  = 409;


    /**
     * @param KriosMane\HiveOs\HiveClient $request
     * @param Psr\Http\Message\ResponseInterface $response
     */
    public function __construct(HiveClient $request, ResponseInterface $response) {

        $this->request = $request;

        $this->http_status_code = $response->getStatusCode();

        $this->setBody($response->getBody());

        $this->setHeaders($response->getHeaders());

        $this->decodeBody();


    }

    /**
     * @param integer $http_status_code
     */
    public function setHttpStatusCode($http_status_code)
    {
        $this->http_status_code = $http_status_code;
    }

    /**
     * @return integer $http_status_code
     */
    public function getHttpStatusCode()
    {
        return $this->http_status_code;
    }

    /**
     * @param object $body
     */
    public function setBody($body)
    {
        $this->body = $body;
    }

    /**
     * @return $body
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * @param object $heders
     */
    public function setHeaders($headers)
    {
        $this->headers = $headers;
    }

    /**
     * @return $heders
     */
    public function getHeaders()
    {
        return $this->headers;
    }

    /**
     * 
     */
    public function decodeBody()
    {
        $this->decoded_body = json_decode($this->getBody(), true);
    }

    /**
     * @return array 
     */
    public function getDecodedBody()
    {
        return $this->decoded_body;
    }

    /**
     * 
     */
    public function isOk()
    {
        if($this->getHttpStatusCode() >= 200 && $this->getHttpStatusCode() < 300){

            return true;
        
        } else {

            return false;

        }
    }

}



?>