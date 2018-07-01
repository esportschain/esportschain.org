<?php defined('BASEPATH') OR exit('No direct script access allowed');

require_once('SteamAuthentication-master/steamauth/openid.php');
/**
 * 调用Auth
 */
class Client
{

    protected $api_key;

    public $center;

    public function __construct($params)
    {

        $this->api_key = !empty($params['api_key']) ? $params['api_key'] : '';
        $host = !empty($params['host']) ? $params['host'] : '';
        $proxy = !empty($params['proxy']) ? $params['proxy'] : null;
        $this->center = new LightOpenID($host, $proxy);

    }

    /**
     * [getAccount 获取帐号信息]
     * @DateTime 2018-06-13
     * @param    [type]     $steamid [description]
     * @return   [type]              [description]
     */
    public function getAccount($steamid)
    {
        $url = file_get_contents("https://api.steampowered.com/ISteamUser/GetPlayerSummaries/v0002/?key=". $this->api_key ."&steamids=".$steamid);
        return json_decode($url, true);
    }
}