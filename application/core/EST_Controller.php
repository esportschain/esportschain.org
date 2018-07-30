<?php
/**
* common class
*/
class EST_Controller extends CI_Controller
{

    public $uid = 0;

    public $member = array();

    public $params = array();

    public $platform = '';

    public $version = '';

    const PLATFORM_ANDROID = 'and';
    const PLATFORM_IOS = 'ios';
    
    public function __construct()
    {
        parent::__construct();
    }

    protected function beforeAction()
    {
        $this->parseParam();

        if(!$this->checkSig()) {
            return false;
        }

        $this->setUserInfo();

        return true;
    }

    /**
     * [showMessage]
     * @DateTime 2018-06-07
     * @param    [type]     $message [description]
     * @param    array      $data    [description]
     * @param    integer    $ret     [description]
     * @param    integer    $subCode [description]
     * @param    array      $exData  [description]
     * @return   [type]              [description]
     */
    protected function showMessage($message, $data = array(), $ret = 0, $subCode = 0, $exData = array())
    {

        $data = empty($data) && is_array($data) ? (object)$data : $data;
        $result = array(
            'ret' => $ret,
            'code' => $subCode,
            'msg' => $message,
            'data' => $data
        );

        if($exData) {
            $result = array_merge($result, $exData);
        }

        $this->output->set_content_type('application/json')->set_output(json_encode($result));
    }

    /**
     * [getAvatar]
     * @DateTime 2018-06-07
     * @param    [type]     $uid [description]
     * @return   [type]          [description]
     */
    protected function getAvatar($uid)
    {
        return file_exists(ROOT_PATH.'/web/uploads/avatar/'.$uid.'.png') 
                ? $this->config->item('base_url') . '/uploads/avatar/' . $uid . '.png' 
                : $this->config->item('base_url') . '/images/app/default_avatar.png';
    }

    /**
     * [getGameIcon]
     * @DateTime 2018-06-08
     * @param    [type]     $gameKey [description]
     * @return   [type]              [description]
     */
    protected function getGameIcon($gameKey)
    {
        return $this->config->item('base_url') . '/images/' . $gameKey . '.png';
    }

    /**
     * [generateVcode ]
     * @DateTime 2018-06-06
     * @param    integer    $length  [description]
     * @param    boolean    $onlyNum [description]
     * @return   [type]              [description]
     */
    protected function generateVcode($length = 6, $onlyNum = false) {

        $code = "";
        $chars = array(
            '*****',
            '*****',
        );

        if(!$onlyNum) {
            $chars[] = 'abcdefghijklmnopqrstuvwxyz';
            $chars[] = 'ABCDEFGHIJKLMNOPRQSTUVWXYZ';
        }

        while (strlen($code) < $length) {
            $keys = array_rand($chars, count($chars));
            foreach ($keys as $key) {
                if (strlen($code) < $length) {
                    $str = $chars[$key];
                    $code .= $str[mt_rand(0, strlen($str) -1)];
                }
            }
        }

        return $code;
    }

    /**
     * [setUserInfo ]
     * @DateTime 2018-06-07
     */ 
    protected function setUserInfo() {

        if(!$this->checkToken()) {
            return false;
        }

        $this->uid = $this->params[6];
        $this->load->model('member/user');
        $this->member = $this->user->getByUid($this->uid);
    }

    protected function getToken($uid) {

        $this->load->model('member/user');
        $user = $this->user->getByUid($uid);
        if(empty($user)) {
            return false;
        }
        $str = $uid . "\t" . md5(substr($user['password'], 0, 8) . $user['slat']);
        return $this->tokencode($str, 'ENCODE');
    }

    protected function checkToken() {

        if(!$this->params) {
            return false;
        }

        $token = $this->params[7];
        $uid = $this->params[6];
        return $uid && $token && $this->getToken($uid) === $token;
    }

    /**
     * [getAuthkey]
     * @DateTime 2018-06-07
     * @param    boolean    $isExcludeToken [description]
     * @return   [type]                     [description]
     */
    protected function getAuthkey($isExcludeToken = true) {

        if(empty($this->params)) {
            return -1;
        }

        if($isExcludeToken && empty($this->params[7])) {
            return -1;
        }

        $strParams = '';
        $this->config->load('common');
        $arrayIndex = $this->config->item('est_common')['app']['auth_index'];
        foreach($arrayIndex as $index) {
            $strParams .= $this->params[$index];
        }
        $strParams .= $this->config->item('est_common')['app']['private_key'];

        return md5(md5($strParams));
    }

    /**
     * [parseParam ]
     * @DateTime 2018-06-07
     * @return   [type]     [description]
     */
    protected function parseParam() {
        $params = $this->input->get('_param');
        if(empty($params)) {
            return false;
        }

        $this->params = explode('|', rawurldecode($params));
        $this->version = empty($this->params[3]) ? '' : $this->params[3];
        $this->platform = empty($this->params[1]) ? '' : $this->params[1];
    }

    protected function tokencode($string, $operation) {

        $this->config->load('common');
        return $this->_tokencode($string, $operation, $this->config->item('est_common')['session']['publicKey']);
    }

    protected function utc2Local($dateStr, $format='Y-m-d H:i:s', $localTimeZone = 'RPC') {
        date_default_timezone_set('UTC');
        $ts = strtotime($dateStr);
        date_default_timezone_set('PRC');
        $date = date($format, $ts);
        return $date;
    }
}