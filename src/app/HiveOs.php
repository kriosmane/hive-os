<?php

namespace KriosMane\HiveOs\app;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ConnectException;

/**
 * 
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
     * 
     */
    protected $endpoint = 'https://api2.hiveos.farm/api/v2/';

    /**
     * 
     */
    protected $http_client = '';

    /**
     * 
     */
    protected $response_code = 0;

    /**
     * 
     */
    protected $user_agent = '';

    /**
     * 
     */
    protected $debug = false;

    /**
     * 
     */
    protected $verify = false;

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
     * 
     */
    public function __construct() {

        $this->_init();

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
     * @return string $access_token
     */
    public function getAccessToken()
    {
        return $this->access_token;
    }

    /**
     * Set api's endpoint
     * @param string $endpoint
     * @return void
     */
    public function setEndPoint($endpoint)
    {
        $this->endpoint = $endpoint;
    }

    /**
     * Get api's endpoint
     * @return string $endpoint
     */
    public function getEndPoint()
    {
        return $this->endpoint;
    }

    /**
     * @param boolean $boolean
     * @return void
     */
    public function setDebug($boolean)
    {
        $this->debug = $boolean;
    }

    /**
     * @param boolean $boolean
     * @return void
     */
    public function setVerify($boolean)
    {
        $this->verify = $boolean;
    }

    /**
     * Initialize class's attributes
     * @return void
     */
    private function _init()
    {

        $this->login        = config('hiveos.login');
        
        $this->password     = config('hiveos.password');
        
        $this->access_token = config('hiveos.access_token');
        
        $this->endpoint     = config('hiveos.endpoint');

        $this->http_client  = new Client([

            'base_uri' => $this->endpoint
        
        ]);
        

        if( $this->access_token == '' ){
            
            $this->authLogin();

        }
    }

    /**
     * Make call to hive os
     * @param string  $type 
     * @param string  $method
     * @param array   $params
     * @param boolean $auth
     * @return 
     */
    public function _call($type = 'POST', $method, $params = [], $auth = true)
    {
        $headers = [];

        $this->response_code = 0;

        if($auth){

            $headers['Authorization'] = 'Bearer '.$this->access_token;

            $headers['User-Agent']    = $this->user_agent;

        }

        try{

            /**
             *  POST calls 
             */
            $params_key_name = 'form_params';

            if($type == 'GET'){

                /**
                 * GET calls
                 */
                $params_key_name = 'query';

            }
            
            $request = $this->http_client->request($type, $method, [

                'headers'        => $headers,

                $params_key_name => $params,

                'verify'         => $this->verify,

                'debug'          => $this->debug
                
            ]);

            $this->response_code =  $request->getStatusCode(); 
            
            return json_decode($request->getBody(), true);

        } catch(ClientException $e) {

            return json_decode($e->getResponse()->getBody(), true);

        } catch(ConnectException $e){
            
        }

    }

    /**
     *  generic call for debug purpose
     * @param string $method
     * @param string $type
     * @param array $params
     * @param boolean $auth
     * @return HiveOsResponse
     */
    public function generic($method, $type = 'GET', $params = [], $auth= true)
    {
        return $this->_call($type, $method, $params, $auth);
    }

    /**
     * Get call response code
     * @return integer response code
     */
    public function getResponseCode()
    {
        return $this->response_code;
    }

    /**
     * Create auth token (sign in)
     * @return boolean
     */
    public function authLogin()
    {
        $params = [

            'login' => $this->login,

            'password' => $this->password
        ];

        $response = $this->_call('POST', 'auth/login', $params, false);

        if(!is_null($response)){

            $this->access_token = $response['access_token'];

            return true;
        }

        return false;
    }

    /**
     * List of accessed farms
     * @return HiveOsResponse
     */
    public function farms()
    {
        return $this->_call('GET', 'farms');
    }

    /**
     * Create new farm
     * @param array $params
     * @return HiveOsResponse
     */
    public function createFarm($params)
    {
        return $this->_call('POST', 'farms', $params);
    }

    /**
     * Farm info
     * 
     * @param int $farm_id Farm ID
     * @return Array
     * @return HiveOsResponse
     */
    public function getFarm($farm_id)
    {
        return $this->_call('GET', 'farms/'.$farm_id);
    }

    /**
     * Edit farm
     * @param int $farm_id
     * @param array $params
     * @return object
     */
    public function editFarm($farm_id, $params)
    {
        return $this->_call('PATCH', 'farms/'.$farm_id, $params);
    }

    /**
     * Delete farm
     * @return HiveOsResponse
     */
    public function deleteFarm($farm_id)
    {

    }

    /**
     * Farm's statistics
     * @param int $farm_id
     * @return HiveOsResponse
     */
    public function getFarmStats($farm_id)
    {
        return $this->_call('GET', 'farms/'.$farm_id.'/stats');
    }

    /**
     * Farm's metrics
     * @param int $farm_id
     * @return HiveOsResponse
     */
    public function getFarmMetrics($farm_id)
    {
        return $this->_call('GET', 'farms/'.$farm_id.'/metrics');
    }

    /**
     * Farm events
     * @param int $farm_id Farm ID
     * @return HiveOsResponse
     */
    public function events($farm_id)
    {
        return $this->_call('GET', 'farms/'.$farm_id.'/events');
    }

    /**
     * List farm's workers
     * @param integer $farm_id
     * @return HiveOsResponse
     */
    public function getWorkers($farm_id)
    {
        return $this->_call('GET', 'farms/'.$farm_id.'/workers');
    }

    /**
     * Create new worker
     * @param int $farm_id
     * @param array $params
     * @return HiveOsResponse
     */
    public function createWorker($farm_id, $params)
    {
        return $this->_call('POST', 'farms/'.$farm_id.'/workers', $params);
    }

    /**
     * Worker info
     * @param int $farm_id
     * @param int $worker_id
     * @return HiveOsResponse
     */
    public function getWorker($farm_id, $worker_id)
    {
        return $this->_call('GET', 'farms/'.$farm_id.'/workers/'.$worker_id);
    }

    /**
     * Edit worker
     * @param int $farm_id
     * @param int $worker_id
     * @param array $params
     * @return HiveOsResponse
     */
    public function editWorker($farm_id, $worker_id, $params)
    {
        return $this->_call('PATCH', 'farms/'.$farm_id.'/workers/'.$worker_id, $params);
    }

    /**
     * Delete worker
     * @param int $farm_id
     * @param int $worker_id
     * @return HiveOsResponse
     */
    public function deleteWorker($farm_id, $worker_id)
    {
        return $this->_call('DELETE', 'farms/'.$farm_id.'/workers/'.$worker_id);
    }

    /**
     * Extended overclocking. Allows to overlock individual GPUs of different workers in one request
     * @param int $farm_id
     * @param array $workers
     * @param array $params
     * @return HiveOsResponse
     */
    public function workersOverclocking($farm_id, $workers, $params)
    {
        return $this->_call('POST', 'farms/'.$farm_id.'/workers/overclock', $params);
    }

    /**
     * Execute command on multiple workers
     * @param int $farm_id
     * @param array|string $workers_id
     * @param array $data
     * @return HiveOsResponse
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

        return $this->_call('POST', 'farms/'.$farm_id.'/workers/command', $params);
    }

    /**
     * Transfer worker to another farm
     * @param int $farm_id
     * @param int $worker_id
     * @param int $target_farm_id
     * @return HiveOsResponse
     */
    public function transferWorker($farm_id, $worker_id, $target_farm_id)
    {
        $params = [

            "target_farm_id" => $target_farm_id
        ];

        return $this->_call('POST', 'farms/'.$farm_id.'/workers/'.$worker_id.'/transfer', $params);
    }

    /**
     * Transfer multiple workers to another farm
     * @param int $farm_id
     * @param array $workers_id
     * @param int $target_farm_id
     * @return HiveOsResponse
     */
    public function transferWorkers($farm_id, $workers_id, $target_farm_id)
    {

        $params = [

            'worker_ids' => $workers_id,

            'target_farm_id' => $target_farm_id

        ];

        return $this->_call('POST', 'farms/'.$farm_id.'/workers/transfer', $params);

    }

    /**
     * Get miner log
     * @param int $farm_id
     * @param string $workers_id
     * @return HiveOsResponse
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
     * @param int $farm_id
     * @param int $worker_id  
     * @return HiveOsResponse
     */
    public function getWorkerMessages($farm_id, $worker_id)
    {
        return $this->_call('GET', 'farms/'.$farm_id.'/workers/'.$worker_id.'/messages');
    }

    /**
     * Worker message
     * @param int $farm_id
     * @param int $worker_id
     * @param int $message_id
     * @return HiveOsResponse
     */
    public function getWorkerMessage($farm_id, $worker_id, $message_id)
    {
        return $this->_call('GET', 'farms/'.$farm_id.'/workers/'.$worker_id.'/messages/'.$message_id);
    }

    /**
     * Worker metrics
     * @param int $farm_id
     * @param int $worker_id
     * @return HiveOsResponse
     */
    public function getWorkerMetrics($farm_id, $worker_id)
    {
        return $this->_call('GET', 'farms/'.$farm_id.'/workers/'.$worker_id.'/metrics');
    }

    /**
     * Farm workers GPU LIST
     * @param int $farm_id
     * @param array $workers_id
     * @return HiveOsResponse
     */
    public function workersGpus($farm_id, $workers_ids = array())
    {

        $params = [

            'worker_ids' => implode(',', $workers_ids)

        ];

        return $this->_call('GET', 'farms/'.$farm_id.'/workers/gpus', $params);
    }

    /**
     * Worker GPUS LIST
     * @param int $farm_id
     * @param int $worker_id
     * @return HiveOsResponse
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
     * @param int $farm_id
     * @return HiveOsResponse
     */
    public function getFlightSheets($farm_id)
    {
        return $this->_call('GET', 'farms/'.$farm_id.'/fs');
    }

    /**
     * Create new flight sheet
     * @param int $farm_id
     * @param array $params
     * @return HiveOsResponse
     */
    public function createFlightSheet($farm_id, $params)
    {
        return $this->_call('POST', 'farms/'.$farm_id.'/fs', $params);
    }

    /**
     * Flight sheet info
     * @param int $farm_id
     * @param int $fs_id
     * @return HiveOsResponse
     */
    public function getFlightSheet($farm_id, $fs_id)
    {
        return $this->_call('GET', 'farms/'.$farm_id.'/fs/'.$fs_id);
    }

    /**
     * Edit flight sheet
     * @param int $farm_id
     * @param int $fs_id
     * @param array $params
     * @return HiveOsResponse
     */
    public function editFlightSheet($farm_id, $fs_id, $params)
    {
        return $this->_call('PATCH', 'farms/'.$farm_id.'/fs/'.$fs_id, $params);
    }

    /**
     * Delete flight sheet
     * @param int $farm_id
     * @param int $fs_id
     * @return HiveOsResponse
     */
    public function deleteFlightSheet($farm_id, $fs_id)
    {
        return $this->_call('DELETE', 'farms/'.$farm_id.'/fs/'.$fs_id);
    }

    /**
     * Farm OC list
     * @param int $farm_id
     * @return HiveOsResponse
     */
    public function getOC($farm_id, $oc_id = '')
    {   
        $endpoint = 'farms/'.$farm_id.'/oc';

        if($oc_id != ''){

            $endpoint .= '/'.$oc_id;

        }

        return $this->_call('GET', $endpoint);
    }

    /**
     * Farm wallets list
     * @param int $farm_id
     * @return HiveOsResponse
     */
    public function getWallets($farm_id)
    {
        return $this->_call('GET', 'farms/'.$farm_id.'/wallets');
    }

    /**
     * Create new wallet
     * @param int $farm_id
     * @param array $params
     * @return HiveOsResponse
     */
    public function createWallet($farm_id, $params)
    {
        return $this->_call('POST', 'farms/'.$farm_id.'/wallets', $params);
    }

    /**
     * Edit Wallet
     * @param int $farm_id
     * @param int $wallet_id
     * @param array $params
     * @return HiveOsResponse
     */
    public function updateWallet($farm_id, $wallet_id, $params)
    {
        return $this->_call('PATCH', 'farms/'.$farm_id.'/wallets/'.$wallet_id, $params);
    }

    /**
     * Delete wallet
     * @param int $farm_id
     * @param int $wallet_id
     * @return HiveOsResponse
     */
    public function deleteWallet($farm_id, $wallet_id)
    {
        return $this->_call('DELETE', 'farms/'.$farm_id.'/wallets/'.$wallet_id);
    }

    /**
     * Available pools list and coins that we have in pools
     * @return HiveOsResponse
     */
    public function pools()
    {
        return $this->_call('GET', 'pools', [], false);
    }

    /**
     * Pool templates
     * @param string $pool_name
     * @return HiveOsResponse
     */
    public function getPoolTemplatesByName($pool_name)
    {
        return $this->_call('GET', 'pools/by_name/'.$pool_name, [], false);
    }

    /**
     * Pool templates which suit coin name
     * @param string $coin_symbol
     * @return HiveOsResponse
     */
    public function getPoolTemplatesByCoin($coin_symbol)
    {
        return $this->_call('GET', 'pools/by_coin/'.$coin_symbol, [], false);
    }

    /**
     * List of Hive OS versions
     * @return HiveOsResponse
     */
    public function versions()
    {
        return $this->_call('GET', 'hive/versions', [], false);
    }

    /**
     * Hive OS version info
     * @param string $version
     * @return HiveOsResponse
     */
    public function getVersion($version)
    {
        return $this->_call('GET', 'hive/versions/'.$version, [], false);
    }

    /**
     * List of mirror URLs
     * @return HiveOsResponse
     */
    public function mirrorUrls()
    {
        return $this->_call('GET', 'hive/mirror_urls', [], false);
    }

    /**
     * List of available miners
     * @return HiveOsResponse
     */
    public function miners()
    {
        return $this->_call('GET', 'hive/miners', [], false);
    }

    /**
     * List of available algorithms
     * @return HiveOsResponse
     */
    public function algos()
    {
        return $this->_call('GET', 'hive/algos', [], false);
    }
    
    /**
     * Tags list
     * Returns tags that belong to given farm along with tags that belong to farm’s owner
     * @param int $farm_id
     * @return HiveOsResponse
     */
    public function tags($farm_id)
    {
        return $this->_call('GET', 'farms/'.$farm_id.'/tags');
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
     * @param $array $params
     * @return HiveOsResponse
     */
    public function overclocks($params = [])
    {
        
        return $this->_call('GET', 'hive/overclocks', $params, false);
    }

    /**
     * Get Hive statistics
     * @return HiveOsResponse
     */
    public function hiveStats()
    {
        return $this->_call('GET', 'hive/stats', [], false);
    }

    /**
     * List of supported exchanges
     * @return HiveOsResponse
     */
    public function exchanges()
    {
        return $this->_call('GET', 'hive/exchanges', [], false);
    }


    /**
     * 
     */
    public function test()
    {
        echo "test".PHP_EOL;
    }

}

?>