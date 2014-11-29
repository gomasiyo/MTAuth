<?php
/**
 *  MTAuth  DataAPI Helper Class
 *  @author Goma:NanoHa
 *  @Version 1.0 alpha
 */

class MTAuth
{

    private $config;
    private $accessToken;
    private $params;
    public $response;

    public function __construct($config = array())
    {

        $this->config = array_merge(
            array(
                'url'       =>  null,
                'clientId'  =>  'MTAuth'
            ),
            $config
        );

        try{
            if(empty($this->config['url'])){
                throw new Exception('Empty URL');
            }
        } catch (Exception $e) {
            exit( $e->getMessage());
        }
    }

    /**
     *  Login method
     *  @param  String  Username
     *  @param  String  Password
     *  @return boolean true or false
     */
    public function login($username = null, $password = null)
    {

        $url = '/v1/authentication';

        if(empty($username) || empty($password)) {
            $this->response['error'] = 'EmptyUserName_or_EmptyPassword';
            return false;
        }

        $params = array(
            'username'  => $username,
            'password'  => $password,
            'clientId'  => $this->config['clientId']
        );

        $status = $this->userRequest(array(
            'method'        => 'post',
            'url'           => $url,
            'json_params'   => false,
            'login'         => false,
            'params'        => $params
        ));

        if($status) {
            $this->accessToken = $this->response['response']['accessToken'];
            return true;
        } else {
            return false;
        }


    }

    /**
     * InsertEntries method
     * @param Int    BlogID
     * @param String Title
     * @param String Body
     * @param String More
     * @return boolean true or false
     */
    public function insertEntries($blogid = null, $title = null, $body = null, $more = null)
    {

        $url = "/v1/sites/{$blogid}/entries";

        if(empty($blogid) || empty($body)) {
            $this->response['error'] = 'EmptyBlogID_or_EmptyBody';
            return false;
        }

        $defaultParams = array(
            'title' => null,
            'body'  => null,
            'more'  => null
        );

        $setParams = array(
            'title' => $title,
            'body'  => $body,
            'more'  => $more
        );

        $params = array_merge($defaultParams, $setParams);

        $status = $this->userRequest(array(
            'method'        => 'post',
            'url'           => $url,
            'request'       => 'entry',
            'json_params'   => true,
            'login'         => true,
            'params'        => $params
        ));

        if($status) {
            return true;
        } else {
            return false;
        }

    }

    /**
     *  Other Request
     *  @param  array   method url login otherParam
     *  @return boolean true or false
     */
    public function userRequest($params = array())
    {

        $this->params = array_merge($this->defaultParams(), $params);

        if(empty($this->params['url'])) {
            $this->response['error'] = 'EmptyURL';
            return false;
        }

        $this->curlit();

        if($this->response['code'] === 200) {
            return true;
        } else {
            return false;
        }

    }

    /**
     *  Default Params
     *  return array    method url json_params login params
     */
    private function defaultParams()
    {
        return array(
            'method'        => 'post',
            'url'           => null,
            'request'       => null,
            'json_params'   => true,
            'login'         => false,
            'params'        => array(),
        );
    }

    /**
     *  Replace Json
     *  @param String   Json
     *  @return array
     */
    private function decodeJson($response = null)
    {

        $temp = json_decode($response, true);
        if(!$temp) {
            return $response;
        } else {
            return $temp;
        }

    }

    /**
     *  Connect DataAPI
     *  @return void
     */
    private function curlit()
    {

        $c = curl_init();

        if($this->params['login']) {
            $header = array('X-MT-Authorization: MTAuth accessToken=' . $this->accessToken);
        } else {
            $header = array();
        }

        if($this->params['method'] != 'get') {
            if($this->params['json_params']){
                $this->params['params'] = json_encode($this->params['params']);
            }
        }

        switch($this->params['method']) {
            case 'get':
                if(!empty($this->params['params'])) {
                    $this->params['url'] = $this->params['url'] . '?' . http_build_query($this->params['params']);
                }
                break;
            case 'post':
                curl_setopt($c, CURLOPT_POST, true);
                if(!empty($this->params['params'])) {
                    if(!empty($this->params['request'])) {
                        $this->params['params'] = array($this->params['request'] => $this->params['params']);
                    }
                } else {
                    $this->params['params'] = array();
                }
                curl_setopt($c, CURLOPT_POSTFIELDS, $this->params['params']);
                break;
            default:
                curl_setopt($c, CURLOPT_POST, true);
                if(!empty($this->params['params'])) {
                    if(!empty($this->params['request'])) {
                        $this->params['params'] = array($this->params['request'] => $this->params['params']);
                    }
                } else {
                    $this->params['params'] = array();
                }
                curl_setopt($c, CURLOPT_POSTFIELDS, $this->params['params']);
                break;
        }

        curl_setopt_array($c, array(
            CURLOPT_URL             => $this->config['url'] . $this->params['url'],
            CURLOPT_RETURNTRANSFER  => true,
            CURLOPT_HTTPHEADER      => $header
        ));

        $response = curl_exec($c);
        $code = curl_getinfo($c, CURLINFO_HTTP_CODE);
        $info = curl_getinfo($c);
        $error = curl_error($c);
        $errno = curl_errno($c);
        curl_close($c);

        $this->response['code'] = $code;
        $this->response['response'] = $this->decodeJson($response);
        $this->response['info'] = $info;
        $this->response['error'] = $error;
        $this->response['errno'] = $errno;

    }

}
