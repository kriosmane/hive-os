<?php

namespace KriosMane\HiveOs;

use KriosMane\HiveOs\HiveClient;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ConnectException;

/**
 * Class HiveOs
 */
class HiveOs {

    /**
     * 
     */
    protected $login = '';

    /**
     * 
     */
    protected $password = '';

    /**
     * 
     */
    protected $access_token = '';

    /**
     * @var KriosMane\HiveOs\HiveClient
     */
    protected $client;

    /**
     * @var KriosMane\HiveOs\HiveResponse
     */
    protected $response;

    /**
     * @var boolean
     */
    protected $debug = false;

    /**
     * @var boolean
     */
    protected $verify = false;


    /**
     * 
     * @param string $access_token
     * 
     */
    public function __construct($access_token = '') {

        $this->setAccessToken($access_token);

        $this->client = new HiveClient($this->access_token);

    }

    /**
     * Set user login
     * @param string $login
     * @return void 
     */
    public function setLogin($login)
    {
        $this->login = $login;
    }

    /**
     * Get user login
     * @return string $login
     */
    public function getLogin()
    {
        return $this->login;
    }

    /**
     * Set user password
     * @param string $password
     * @return void
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }

    /**
     * Get user password
     * @return string $password
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set user access token
     * @param string $access_token
     * @return void
     */
    public function setAccessToken($access_token)
    {
        $this->access_token = $access_token;
    }

    /**
     * Get user access token
     * 
     * @return string $access_token
     */
    public function getAccessToken()
    {
        return $this->access_token;
    }

    /**
     * Set api's endpoint
     * 
     * @param string $endpoint
     * 
     * @return void
     */
    public function setEndPoint($endpoint)
    {
        $this->endpoint = $endpoint;
    }

    /**
     * Get api's endpoint
     * 
     * @return string $endpoint
     */
    public function getEndPoint()
    {
        return $this->endpoint;
    }

    /**
     * @param boolean $boolean
     * 
     * @return void
     */
    public function setDebug($boolean)
    {
        $this->debug = $boolean;
    }

    /**
     * @return boolean
     */
    public function getDebug()
    {
        return $this->debug;
    }

    /**
     * @param boolean $boolean
     * 
     * @return void
     */
    public function setVerify($boolean)
    {
        $this->verify = $boolean;
    }

    /**
     * @return boolean
     */
    public function getVerify()
    {
        return $this->verify;
    }

    /**
     * Wrapper for HiveClient->request()
     * 
     * @param string  $method   The HTTP method for this request GET|POST|PUT|DELETE|PATCH
     * @param string  $endpoint The API endpoint for this request
     * @param array   $params   The parameters to send with this request
     * @param boolean $auth     Set if needed authentication
     *  
     * @return KriosMane\HiveOs\HiveResponse
     */
    public function request($method = 'GET', $endpoint, $params = [], $auth = true)
    {

        $this->client->setDebug($this->getDebug());

        $this->client->setVerify($this->getVerify());

        $this->response =  $this->client->request($method, $endpoint, $params, $auth);

        return $this->response->getDecodedBody();
    }


    /**
     *  generic call for debug purpose
     * 
     * @param string  $method   The HTTP method for this request GET|POST|PUT|DELETE|PATCH
     * @param string  $endpoint The API endpoint for this request
     * @param array   $params   The parameters to send with this request
     * @param boolean $auth     Set if needed authentication
     * 
     * @return KriosMane\HiveOs\HiveResponse
     */
    public function generic($method, $type = 'GET', $params = [], $auth= true)
    {
        return $this->request($type, $method, $params, $auth);
    }

    /**
     * Create auth token (sign in)
     * 
     * @param string $login
     * @param string $password
     * 
     * @return boolean
     */
    public function authLogin( $login, $password )
    {

        $this->setLogin($login);

        $this->setPassword($password);

        $params = [

            'login' => $this->getLogin(),

            'password' => $this->getPassword()
        ];

        $response = $this->request('POST', 'auth/login', $params, false);

        if(!is_null($response)){

            $this->access_token = $response['access_token'];

            $this->client->setAccessToken($this->access_token);

            return true;
        }

        return false;
    }

    /**
     * List of accessed farms
     * 
     * @return KriosMane\HiveOs\HiveResponse
     */
    public function farms()
    {   
        return $this->request('GET', 'farms');
    }

    /**
     * Create new farm
     * 
     * @param array $params
     * 
     * @return KriosMane\HiveOs\HiveResponse
     */
    public function createFarm($params)
    {
        return $this->request('POST', 'farms', $params);
    }

    /**
     * Farm info
     * 
     * @param int $farm_id Farm ID
     * 
     * @return KriosMane\HiveOs\HiveResponse
     */
    public function getFarm($farm_id)
    {
        return $this->request('GET', 'farms/'.$farm_id);
    }

    /**
     * Edit farm
     * 
     * @param int $farm_id
     * @param array $params
     * 
     * @return KriosMane\HiveOs\HiveResponse
     */
    public function editFarm($farm_id, $params)
    {
        return $this->request('PATCH', 'farms/'.$farm_id, $params);
    }

    /**
     * Delete farm
     * 
     * @return KriosMane\HiveOs\HiveResponse
     */
    public function deleteFarm($farm_id)
    {

    }

    /**
     * Farm's statistics
     * 
     * @param int $farm_id
     * @param array $params
     * 
     * @return KriosMane\HiveOs\HiveResponse
     */
    public function getFarmStats($farm_id, $params = [])
    {
        return $this->request('GET', 'farms/'.$farm_id.'/stats', $params);
    }

    /**
     * Farm's metrics
     * 
     * @param int $farm_id
     * @param array $params
     * 
     * @return KriosMane\HiveOs\HiveResponse
     */
    public function getFarmMetrics($farm_id, $params = [])
    {
        return $this->request('GET', 'farms/'.$farm_id.'/metrics', $params);
    }

    /**
     * Farm events
     * 
     * @param int $farm_id Farm ID
     * @param array $params
     * 
     * @return KriosMane\HiveOs\HiveResponse
     */
    public function events($farm_id, $params = [])
    {
        return $this->request('GET', 'farms/'.$farm_id.'/events', $params);
    }

    /**
     * List farm's workers
     * 
     * @param integer $farm_id
     * @param array $params
     * 
     * @return KriosMane\HiveOs\HiveResponse
     */
    public function getWorkers($farm_id, $params = [])
    {
        return $this->request('GET', 'farms/'.$farm_id.'/workers', $params);
    }

    /**
     * Create new worker
     * 
     * @param int $farm_id
     * @param array $params
     * 
     * @return KriosMane\HiveOs\HiveResponse
     */
    public function createWorker($farm_id, $params)
    {
        return $this->request('POST', 'farms/'.$farm_id.'/workers', $params);
    }

    /**
     * Worker info
     * 
     * @param int $farm_id
     * @param int $worker_id
     * 
     * @return KriosMane\HiveOs\HiveResponse
     */
    public function getWorker($farm_id, $worker_id)
    {
        return $this->request('GET', 'farms/'.$farm_id.'/workers/'.$worker_id);
    }

    /**
     * Edit worker
     * 
     * @param int $farm_id
     * @param int $worker_id
     * @param array $params
     * 
     * @return KriosMane\HiveOs\HiveResponse
     */
    public function editWorker($farm_id, $worker_id, $params)
    {
        return $this->request('PATCH', 'farms/'.$farm_id.'/workers/'.$worker_id, $params);
    }

    /**
     * Delete worker
     * 
     * @param int $farm_id
     * @param int $worker_id
     * 
     * @return KriosMane\HiveOs\HiveResponse
     */
    public function deleteWorker($farm_id, $worker_id)
    {
        return $this->request('DELETE', 'farms/'.$farm_id.'/workers/'.$worker_id);
    }

    /**
     * Extended overclocking. Allows to overlock individual GPUs of different workers in one request
     * 
     * @param int $farm_id
     * @param array $workers
     * @param array $params
     * 
     * @return KriosMane\HiveOs\HiveResponse
     */
    public function workersOverclocking($farm_id, $workers, $params)
    {
        return $this->request('POST', 'farms/'.$farm_id.'/workers/overclock', $params);
    }

    /**
     * Execute command on multiple workers
     * 
     * @param int $farm_id
     * @param array|string $workers_id
     * @param array $data
     * 
     * @return KriosMane\HiveOs\HiveResponse
     */
    public function workersCommand($farm_id, $workers_id, $command, $data = array())
    {   

        if(!is_array($workers_id)){

            $workers_id =  [$workers_id ];

        }

        $params = array(

            'worker_ids' =>  $workers_id ,

            'data' => [

                'command' => $command,

                'data' => $data
            ]

        );

        return $this->request('POST', 'farms/'.$farm_id.'/workers/command', $params);
    }

    /**
     * Transfer worker to another farm
     * 
     * @param int $farm_id
     * @param int $worker_id
     * @param int $target_farm_id
     * 
     * @return KriosMane\HiveOs\HiveResponse
     */
    public function transferWorker($farm_id, $worker_id, $target_farm_id)
    {
        $params = [

            "target_farm_id" => $target_farm_id
        ];

        return $this->request('POST', 'farms/'.$farm_id.'/workers/'.$worker_id.'/transfer', $params);
    }

    /**
     * Transfer multiple workers to another farm
     * 
     * @param int   $farm_id
     * @param array $workers_id
     * @param int   $target_farm_id
     * 
     * @return KriosMane\HiveOs\HiveResponse
     */
    public function transferWorkers($farm_id, $workers_id, $target_farm_id)
    {

        $params = [

            'worker_ids' => $workers_id,

            'target_farm_id' => $target_farm_id

        ];

        return $this->request('POST', 'farms/'.$farm_id.'/workers/transfer', $params);

    }

    /**
     * Get miner log
     * 
     * @param int    $farm_id
     * @param string $workers_id
     * 
     * @return KriosMane\HiveOs\HiveResponse
     * 
     */
    public function getWorkerMinerLog($farm_id, $workers_id)
    {

        $data = array(
            'action' => 'log'
        );

        return $this->workersCommand($farm_id, $workers_id, 'miner', $data);

    }

    /**
     * Worker's messages
     * 
     * @param int $farm_id
     * @param int $worker_id 
     * @param array $params
     *  
     * @return KriosMane\HiveOs\HiveResponse
     */
    public function getWorkerMessages($farm_id, $worker_id, $params = [])
    {
        return $this->request('GET', 'farms/'.$farm_id.'/workers/'.$worker_id.'/messages', $params);
    }

    /**
     * Worker message
     * 
     * @param int $farm_id
     * @param int $worker_id
     * @param int $message_id
     * 
     * @return KriosMane\HiveOs\HiveResponse
     */
    public function getWorkerMessage($farm_id, $worker_id, $message_id)
    {
        return $this->request('GET', 'farms/'.$farm_id.'/workers/'.$worker_id.'/messages/'.$message_id);
    }

    /**
     * Delete all worker messages
     * 
     * @param int $farm_id
     * @param int $worker_id
     * @param int $message_id
     * 
     * @return KriosMane\HiveOs\HiveResponse
     */
    public function deleteWorkerMessages($farm_id, $worker_id)
    {
        return $this->request('DELETE', 'farms/'.$farm_id.'/workers/'.$worker_id.'/messages');
    }

    /**
     * Worker metrics
     * 
     * @param int $farm_id
     * @param int $worker_id
     * @param array $params
     * 
     * @return KriosMane\HiveOs\HiveResponse
     */
    public function getWorkerMetrics($farm_id, $worker_id, $params = [])
    {
        return $this->request('GET', 'farms/'.$farm_id.'/workers/'.$worker_id.'/metrics', $params);
    }

    /**
     * Farm workers GPU LIST
     * 
     * @param int   $farm_id
     * @param array $workers_id
     * 
     * @return KriosMane\HiveOs\HiveResponse
     */
    public function workersGpus($farm_id, $workers_ids = array())
    {

        $params = [

            'worker_ids' => implode(',', $workers_ids)

        ];

        return $this->request('GET', 'farms/'.$farm_id.'/workers/gpus', $params);
    }

    /**
     * Worker GPUS LIST
     * 
     * @param int $farm_id
     * @param int $worker_id
     * 
     * @return KriosMane\HiveOs\HiveResponse
     */
    public function workerGpus($farm_id, $worker_id)
    {
        $all_gpus = $this->workersGpus($farm_id)['data'];

        $gpus = [];

        foreach($all_gpus as $gpu)
        {
            if($gpu['worker']['id'] == $worker_id && $gpu['worker']['farm_id'] == $farm_id){

                $gpus [] = $gpu;

            }
        }

        return $gpus;

    }

    /**
     * Returns flight sheets that belong to given farm along with flight sheets that belong to farm’s owner
     * 
     * @param int $farm_id
     * 
     * @return KriosMane\HiveOs\HiveResponse
     */
    public function getFlightSheets($farm_id)
    {
        return $this->request('GET', 'farms/'.$farm_id.'/fs');
    }

    /**
     * Create new flight sheet
     * 
     * @param int   $farm_id
     * @param array $params
     * 
     * @return KriosMane\HiveOs\HiveResponse
     */
    public function createFlightSheet($farm_id, $params)
    {
        return $this->request('POST', 'farms/'.$farm_id.'/fs', $params);
    }

    /**
     * Flight sheet info
     * 
     * @param int $farm_id
     * @param int $fs_id
     * 
     * @return KriosMane\HiveOs\HiveResponse
     */
    public function getFlightSheet($farm_id, $fs_id)
    {
        return $this->request('GET', 'farms/'.$farm_id.'/fs/'.$fs_id);
    }

    /**
     * Edit flight sheet
     * 
     * @param int   $farm_id
     * @param int   $fs_id
     * @param array $params
     * 
     * @return KriosMane\HiveOs\HiveResponse
     */
    public function editFlightSheet($farm_id, $fs_id, $params)
    {
        return $this->request('PATCH', 'farms/'.$farm_id.'/fs/'.$fs_id, $params);
    }

    /**
     * Delete flight sheet
     * 
     * @param int $farm_id
     * @param int $fs_id
     * 
     * @return KriosMane\HiveOs\HiveResponse
     */
    public function deleteFlightSheet($farm_id, $fs_id)
    {
        return $this->request('DELETE', 'farms/'.$farm_id.'/fs/'.$fs_id);
    }

    /**
     * Farm OC list
     * 
     * @param int $farm_id
     * 
     * @return KriosMane\HiveOs\HiveResponse
     */
    public function getOC($farm_id, $oc_id = '')
    {   
        $endpoint = 'farms/'.$farm_id.'/oc';

        if($oc_id != ''){

            $endpoint .= '/'.$oc_id;

        }

        return $this->request('GET', $endpoint);
    }

    /**
     * Farm wallets list
     * 
     * @param int $farm_id
     * 
     * @return KriosMane\HiveOs\HiveResponse
     */
    public function getWallets($farm_id)
    {
        return $this->request('GET', 'farms/'.$farm_id.'/wallets');
    }

    /**
     * Create new wallet
     * 
     * @param int   $farm_id
     * @param array $params
     * 
     * @return KriosMane\HiveOs\HiveResponse
     */
    public function createWallet($farm_id, $params)
    {
        return $this->request('POST', 'farms/'.$farm_id.'/wallets', $params);
    }

    /**
     * Edit Wallet
     * 
     * @param int   $farm_id
     * @param int   $wallet_id
     * @param array $params
     * 
     * @return KriosMane\HiveOs\HiveResponse
     */
    public function updateWallet($farm_id, $wallet_id, $params)
    {
        return $this->request('PATCH', 'farms/'.$farm_id.'/wallets/'.$wallet_id, $params);
    }

    /**
     * Delete wallet
     * 
     * @param int $farm_id
     * @param int $wallet_id
     * 
     * @return KriosMane\HiveOs\HiveResponse
     */
    public function deleteWallet($farm_id, $wallet_id)
    {
        return $this->request('DELETE', 'farms/'.$farm_id.'/wallets/'.$wallet_id);
    }

    /**
     * Available pools list and coins that we have in pools
     * 
     * @return KriosMane\HiveOs\HiveResponse
     */
    public function pools()
    {
        return $this->request('GET', 'pools', [], false);
    }

    /**
     * Pool templates
     * 
     * @param string $pool_name
     * 
     * @return KriosMane\HiveOs\HiveResponse
     */
    public function getPoolTemplatesByName($pool_name)
    {
        return $this->request('GET', 'pools/by_name/'.$pool_name, [], false);
    }

    /**
     * Pool templates which suit coin name
     * 
     * @param string $coin_symbol
     * 
     * @return KriosMane\HiveOs\HiveResponse
     */
    public function getPoolTemplatesByCoin($coin_symbol)
    {
        return $this->request('GET', 'pools/by_coin/'.$coin_symbol, [], false);
    }

    /**
     * List of Hive OS versions
     * 
     * @return KriosMane\HiveOs\HiveResponse
     */
    public function versions()
    {
        return $this->request('GET', 'hive/versions', [], false);
    }

    /**
     * Hive OS version info
     * 
     * @param string $version
     * 
     * @return KriosMane\HiveOs\HiveResponse
     */
    public function getVersion($version)
    {
        return $this->request('GET', 'hive/versions/'.$version, [], false);
    }

    /**
     * List of mirror URLs
     * 
     * @return KriosMane\HiveOs\HiveResponse
     */
    public function mirrorUrls()
    {
        return $this->request('GET', 'hive/mirror_urls', [], false);
    }

    /**
     * List of available miners
     * 
     * @return KriosMane\HiveOs\HiveResponse
     */
    public function miners()
    {
        return $this->request('GET', 'hive/miners', [], false);
    }

    /**
     * List of available algorithms
     * 
     * @return KriosMane\HiveOs\HiveResponse
     */
    public function algos()
    {   
        return $this->request('GET', 'hive/algos', [], false);
    }
    
    /**
     * Tags list
     * Returns tags that belong to given farm along with tags that belong to farm’s owner
     * 
     * @param int $farm_id
     * @param array $params
     * 
     * @return @return KriosMane\HiveOs\HiveResponse
     */
    public function tags($farm_id, $params = [])
    {
        return $this->request('GET', 'farms/'.$farm_id.'/tags', $params);
    }

    /**
     * Get popular overclock settings for different GPUs and algos. Result is sorted by rating
     * Possible params:
     *  - gpu_brand
     *  - gpu_model
     *  - gpu_mem
     *  - algo
     *  - page
     *  - per_page (default=15, max=50)
     * 
     * @param $array $params
     * 
     * @return @return KriosMane\HiveOs\HiveResponse
     */
    public function overclocks($params = [])
    {
        
        return $this->request('GET', 'hive/overclocks', $params, false);
    }

    /**
     * Get Hive statistics
     * 
     * @return @return KriosMane\HiveOs\HiveResponse
     */
    public function hiveStats()
    {
        return $this->request('GET', 'hive/stats', [], false);
    }

    /**
     * List of supported exchanges
     * 
     * @return @return KriosMane\HiveOs\HiveResponse
     */
    public function exchanges()
    {
        return $this->request('GET', 'hive/exchanges', [], false);
    }

    /**
     * Get last response object
     * 
     * @return KriosMane\HiveOs\HiveResponse
     */
    public function getResponse()
    {
        return $this->response;
    }

}

?>
