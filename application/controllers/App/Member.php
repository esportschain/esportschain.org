<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Member extends EST_Controller
{

    public function detail()
    {
        if(!$this->beforeAction()) {
            return $this->showMessage('Unauthorized', [], 401, 1);
        }

        if(!$this->uid) {
            return $this->showMessage('User not logged in', [], 400, 1);
        }

        $this->load->model('member/user');
        $user = $this->user->getByUid($this->uid);
        if(empty($user)) {
            return $this->showMessage('User does not exist', [], 400, 2);
        }

        
        $page = intval($this->input->post('page'));

        $user_msg = '';
        $account_list = [];
        if(!$user['steam_status']) {
            $user_status = 1; 
        }else {
            $this->config->load('common');
            $games = $this->config->item('est_common')['gametypes'];
            $user_games = empty($user['games']) ? [] : explode(',', $user['games']);

            $user_games && $user_games = array_flip($user_games);
            $user_games = array_intersect_key($games, $user_games);

            if(empty($user_games)) {
                $user_status = 2; 
                $user_msg = 'We have not got your game list by your steam account, you have to make sure whether you have purchased PUBG in your steam. If you have purchased PUBG, you can log into steam and set your game list public， then we could get your game list when you start EST application again.';
            }else {
                $user_status = 3; 

                $this->load->model('member/gameUserAccount');
                $list = $this->gameUserAccount->listByUid($this->uid);
                $list && $list = array_combine(array_column($list, 'gametype'), $list);

                foreach ($user_games as $k => $v) {
                    $tmp = [
                        'icon'           => $this->getGameIcon($v['key']),
                        'name'           => $v['steam_app_name'],
                        'gametype'       => $v['id'],
                        'account_status' => 0,    
                        'account_msg'    => '',
                        'detail'         => (object)[],
                    ];

                    if(empty($list[$v['id']])) {
                        $tmp['account_status'] = 4;  
                    }else {
                        $account = $list[$v['id']];

                        if($account['status'] == GameUserAccount::STATUS_NORMAL) {
                            $tmp['account_status'] = 1;  
                            if($v['id'] == 9) {
                                $detail = $this->pubgAccount($account['accountname']);
                            }else {
                                continue;
                            }
                            $tmp['detail'] = $detail;
                        }else if($account['status'] == GameUserAccount::STATUS_NOT_FOUND) {
                            $tmp['account_status'] = 2;  
                            $tmp['account_msg'] = $account['accountname'] . ' the nickname you submitted last time is not exist, please resubmit PUBG nickname.';
                        }else if($account['status'] == GameUserAccount::STATUS_TODO_VERIFIED) {
                            $tmp['account_status'] = 3;  
                        }else {
                            continue;
                        }
                    }

                    $account_list[] = $tmp;
                }
            }
        }

        $rst = [
            'user' => [
                'uid'      => $this->uid,
                'nickname' => $user['nickname'],
                'avatar'   => $this->getAvatar($this->uid),
                'money'    => strval($user['money']),
                'status'   => $user_status,
                'msg'      => $user_msg,
                'popup'    => User::formatPopup($user['popup']),
            ],
            'account_list' => $account_list,
        ];

        return $this->showMessage('success', $rst);
    }


    private function pubgAccount($accountname)
    {
        $this->load->model('pubg/playerStats');
        $filter = [
            'account_name' => $accountname,
            'server'       => '',
            'season'       => '',
            'mode'         => '',
            'queue_size'   => 0,
        ];
        $fields = 'match_num,win_num,top_10_num,kdr';
        $stats = $this->playerStats->listByFilter($filter, $fields);

        $data = [
            'accountname' => $accountname,
            'uri'         => [
                'd'           => 'App',
                'c'           => 'Pubg',
                'm'           => 'detail',
                'accountname' => $accountname,
            ],
            'stats'       => [],
        ];
        if(!empty($stats)) {
            $data['stats'] = [
                [
                    'field' => 'Games',
                    'val'   => strval($stats[0]['match_num']),
                ],
                [
                    'field' => 'Win',
                    'val'   => strval($stats[0]['win_num']),
                ],
                [
                    'field' => 'Top10',
                    'val'   => strval($stats[0]['top_10_num']),
                ],
                [
                    'field' => 'KDR',
                    'val'   => sprintf('%.2f', $stats[0]['kdr']),
                ],
            ];
        }

        return $data;
    }

  
    public function thirdLogin()
    {

        $thirdtype = intval($this->input->get('thirdtype'));
        $code = trim($this->input->get('code'));
        if($thirdtype == 2 && !empty($code)) {
            
            $state = trim($this->input->get('state'));
            $tmp = explode('-estextra-', $state);
            $tmp1 = explode('&', trim(urldecode($tmp[1]), '&'));
            $tmp2 = [];
            foreach ($tmp1 as $v) {
                $s = explode('=', $v);
                $tmp2[$s[0]] = $s[1];
            }
            if(empty($tmp2['_param'])) {
                return $this->showMessage('params error', [], 400, 2);
            }
            $this->params = explode('|', rawurldecode($tmp2['_param']));
            $this->version = empty($this->params[3]) ? '' : $this->params[3];
            $this->platform = empty($this->params[1]) ? '' : $this->params[1];
        }else {
            $this->parseParam();
        }

        $this->setUserInfo();

        if($thirdtype == 1) {
            // steam
            $data = $this->steamLogin();
        }else if($thirdtype == 2) {
            // facebook
            $data = $this->facebookLogin();
        }else {
            return $this->showMessage('Unknown Type', [], 400, 1);
        }

        if($data['code'] != 0) {
            return $this->showMessage($data['message'], [], $data['code'], $data['subcode']);
        }

        $user = $this->getUser($data['data']);
        if(isset($user['code'])) {
            return $this->showMessage($user['message'], [], $user['code'], $user['subcode']);
        }

        $token = $this->getToken($user['uid']);
        $authkey = $this->getAuthkey(false);
        $data = [
            'uid' => intval($user['uid']),
            'nickname' => $user['nickname'],
            'token' => $token,
            'authkey' => $authkey,
            'avatar' => $this->getAvatar($user['uid']),
        ];
        return $this->load->view('app/member/third_login', [
            'rst' => [
                'ret'  => 0,
                'code' => 0,
                'msg'  => 'success',
                'data' => $data
            ],
        ]);
    }

   
    private function steamLogin()
    {
        $this->config->load('common');
        $params = $this->config->item('est_common')['third_login']['steam'];
        $this->load->library('Steam/Client', ['host' => $_SERVER['SERVER_NAME'], 'api_key' => $params['api_key']]);

        if(!$this->client->center->mode) {
            $this->client->center->identity = 'https://steamcommunity.com/openid';
            header('Location: ' . $this->client->center->authUrl());die;
        } elseif ($this->client->center->mode == 'cancel') {
            return ['code' => 500, 'subcode' => 1, 'message' => 'User has canceled authentication!'];
        } else {
            if($this->client->center->validate()) { 
                $id = $this->client->center->identity;
                $ptn = "/^https?:\/\/steamcommunity\.com\/openid\/id\/(7[0-9]{15,25}+)$/";
                preg_match($ptn, $id, $matches);
                if(empty($matches[1])) {
                    return ['code' => 500, 'subcode' => 2, 'message' => 'Authorization failure'];
                }
                $steamid = $matches[1];
            } else {
                return ['code' => 500, 'subcode' => 3, 'message' => 'User is not logged in'];
            }
        }

        if(empty($steamid)) {
            return ['code' => 500, 'subcode' => 4, 'message' => 'User log in failed'];
        }

        $content = $this->client->getAccount($steamid);
        if(empty($content['response']['players'][0])) {
            return ['code' => 500, 'subcode' => 4, 'message' => 'User Detail get failed'];
        }

        $data = [
            'openid'        => '',
            'unionid'       => $steamid,
            'thirdnickname' => $content['response']['players'][0]['personaname'],
            'thirdavatar'   => $content['response']['players'][0]['avatarfull'],
            'thirdtype'     => 1,
            'games'         => $this->client->getUserGames($steamid),
        ];

        return ['code' => 0, 'data' => $data];
    }


    private function facebookLogin()
    {

        $code = trim($this->input->get('code'));
        $this->config->load('common');
        $params = $this->config->item('est_common')['third_login']['facebook'];
        $redirectUri = $this->config->item('base_url') . '/app.php?d=App&c=Member&m=thirdLogin&thirdtype=2';
        if(empty($code)) {
            $this->load->library('Facebooksdk/Client', $params, 'facebook');
            $oauth2_client = $this->facebook->center->getOAuth2Client();

            $state = md5('Esportschain-' . date('Y-m-d H:i:s') . rand(100, 999)) . '-estextra-' . $_SERVER['QUERY_STRING'];
            $url = $oauth2_client->getAuthorizationUrl($redirectUri, $state);

            $this->load->library('session');
            $this->session->set_userdata('state', $state);
            header('Location: ' . $url);die;
        }

        $state = trim($this->input->get('state'));
        $this->load->library('session');
        if(empty($state) || $state != $this->session->userdata('state')) {
            return ['code' => 500, 'subcode' => 1, 'message' => 'Authorization error'];
        }

        try {
            $this->load->library('Facebooksdk/Client', $params, 'facebook');
            $oauth2_client = $this->facebook->center->getOAuth2Client();
            $aObj = $oauth2_client->getAccessTokenFromCode($code, $redirectUri);
            $debugData = $oauth2_client->debugToken($aObj);
            if(empty($aObj->getValue()) || $debugData->getAppId() != $params['app_id']) {
                return ['code' => 500, 'subcode' => 2, 'message' => 'Failed to get token'];
            }

            $accesstoken = $aObj->getValue();
            $response = $this->facebook->center->get('/me?fields=id,name,picture', $accesstoken);

            $me = $response->getGraphUser();

            $data = [
                'openid'        => '',
                'unionid'       => $me->getId(),
                'thirdnickname' => $me->getName(),
                'thirdavatar'   => $me->getPicture()->getUrl(),
                'thirdtype'     => 2,
            ];

            return ['code' => 0, 'data' => $data];
        } catch (\Facebook\Exceptions\FacebookSDKException $e) {
            return ['code' => 500, 'subcode' => 3, 'message' => $e->getMessage()];
        }
    }

   
    private function getUser($data)
    {
        $this->load->model('member/third');
        $this->load->model('member/user');
        $third = $this->third->getByUnionid($data['unionid']);
        $steam_status = $data['thirdtype'] == 1 ? 1 : 0;
        $user_games = [];
        if(!empty($data['games']['response']['games'])) {
            $this->config->load('common');
            $all_games = $this->config->item('est_common')['gametypes'];
            $all_games = array_column($all_games, 'id', 'steam_app_id');

            foreach ($data['games']['response']['games'] as $value) {
                !empty($all_games[$value['appid']]) && $user_games[] = $all_games[$value['appid']];
            }
        }

        if($this->uid) {
            $user = $this->user->getByUid($this->uid);
            if(empty($user)) {
                return ['code' => 400, 'subcode' => 1, 'message' => 'not found user'];
            }

           
            if(empty($third)) {
                
                $third_data = [
                    'uid'           => $this->uid,
                    'openid'        => $data['openid'],
                    'unionid'       => $data['unionid'],
                    'thirdnickname' => $data['thirdnickname'],
                    'thirdavatar'   => $data['thirdavatar'],
                    'thirdtype'     => $data['thirdtype'],
                    'status'        => 1,
                ];
                $this->third->add($third_data);
            }else {
                if($third['uid'] && $this->uid != $third['uid']) {
                    return ['code' => 400, 'subcode' => 2, 'message' => 'The account has been bound'];
                }

               
                $third_data_u = [
                    'uid' => $this->uid,
                ];
                $this->third->edit($third['id'], $third_data_u);
            }

            $user_data_u = [
                'steam_status'  => $steam_status,
            ];
            $this->user->edit($this->uid, $user_data_u);
            $uid = $this->uid;
        }else {
         
            if(empty($third)) {
               
                $user_data = [
                    'email'    => $data['unionid'],
                    'nickname' => $data['thirdnickname'],
                    'password' => '',
                    'money'    => 0,
                    'slat'     => '',
                    'steam_status' => $steam_status,
                    'status'   => 1,
                ];
                $result = $this->user->add($user_data);
                if(!$result) {
                    return ['code' => 500, 'subcode' => 1, 'message' => 'Registered user failed'];
                }
                $third_data = [
                    'uid'           => $result,
                    'openid'        => $data['openid'],
                    'unionid'       => $data['unionid'],
                    'thirdnickname' => $data['thirdnickname'],
                    'thirdavatar'   => $data['thirdavatar'],
                    'thirdtype'     => $data['thirdtype'],
                    'status'        => 1,
                ];
                $this->third->add($third_data);
                $uid = $result;
            }else {
             
                $uid = $third['uid'];
            }

            if($uid && $data['thirdavatar']) {
                $dir = ROOT_PATH . '/web/uploads/avatar/';
                $this->downImage($data['thirdavatar'], $dir, $uid . '.png');
            }
        }

        $user = $this->user->getByUid($uid);
        if(empty($user)) {
            return ['code' => 400, 'subcode' => 3, 'message' => 'not found user'];
        }

        if(!empty($user_games)) {
            $user['games'] = !empty($user['games']) ? explode(',', $user['games']) : [];
            $user['games'] = array_merge($user['games'], $user_games);
            $this->user->edit($uid, [
                'games' => !empty($user['games']) ? implode(',', array_unique($user['games'])) : '',
            ]);
        }

        return $user;
    }

 
    public function sendVcode()
    {
        if(!$this->beforeAction()) {
            return $this->showMessage('Unauthorized', [], 401, 1);
        }

        $email = trim($this->input->post('email'));
        if (!preg_match("/([\w\-]+\@[\w\-]+\.[\w\-]+)/", $email)) {
            return $this->showMessage('Please check email information', [], 400, 1);
        }

        $this->load->model('member/user');
        $user = $this->user->getUserByEmail($email);
        if(!empty($user)) {
            return $this->showMessage('The mailbox is already registered', [], 400, 2);
        }

        $data = [
            'vcode'   => $this->generateVcode(4),
            'created' => time(),
        ];

        $this->load->library('email');
        $this->email->from('noreply@esportschain.org', 'Esports Chain');
        $this->email->to($email);

        $this->email->subject('Registration verification code');
        $this->email->message('Your verification code is：' . $data['vcode'] . '，valid for 15 minutes, please verify in time！');
        $res = $this->email->send();
        if(!$res) {
            return $this->showMessage('Mail failed to send', [], 400, 3);
        }

        $this->load->driver('cache');
        $this->cache->redis->save('Est_User_Email_Vcode-' . $email, $data, 900);
        return $this->showMessage('success');
    }


    public function checkVcode() {

        if(!$this->beforeAction()) {
            return $this->showMessage('Unauthorized', [], 401, 1);
        }

 
        $email = trim($this->input->post('email'));
        $vcode = strtolower(trim($this->input->post('vcode')));
 
        $this->load->driver('cache');
        $vData = $this->cache->redis->get('Est_User_Email_Vcode-' . $email);
        if(empty($vData)) {
            return $this->showMessage('Please get the verification code first', [], 400, 1);
        }

        if($vcode != strtolower($vData['vcode'])) {
            return $this->showMessage('Verification code error', [], 400, 2);
        }

        if(time() > $vData['created'] + 15 * 60) {
            return $this->showMessage('Verification code has expired', [], 403, 3);
        }

        $key = md5($vcode.'secretKey');
        $this->cache->redis->save('Est_User_Email_Key-' . $email, $key, 1800);
        return $this->showMessage('success', array('key' => $key));
    }

  
    public function modifyPwd()
    {

        if(!$this->beforeAction()) {
            return $this->showMessage('Unauthorized', [], 401, 1);
        }

        if(!$this->uid) {
            return $this->showMessage('User not logged in', [], 400, 1);
        }

        $oldPwd = trim($this->input->post('old_pwd'));
        $newPwd = trim($this->input->post('new_pwd'));
        $rNewPwd = trim($this->input->post('rnew_pwd'));

        $this->load->model('member/user');
        $user = $this->user->getByUid($this->uid);

        if(empty($user)) {
            return $this->showMessage('Requested user not found', [], 400, 2);
        }
        if(empty($user['password'])) {
            return $this->showMessage('Third-party login users cannot change passwords', [], 400, 6);
        }
        if($newPwd != $rNewPwd) {
            return $this->showMessage('Inconsistent input twice', [], 400, 3);
        }
        if($user['password'] != md5($oldPwd . $user['slat'])) {
            return $this->showMessage('Old password is incorrect', [], 400, 4);
        }

        $result = $this->user->edit($this->uid, ['password' => md5($newPwd . $user['slat'])]);

        if($result) {
            return $this->showMessage('success');
        }
        return $this->showMessage('Password change failed', [], 400, 5);
    }

    public function register()
    {

        if(!$this->beforeAction()) {
            return $this->showMessage('Unauthorized', [], 401, 1);
        }

        $email = trim($this->input->post('email'));
        $nickname = trim($this->input->post('nickname'));
        $newPwd = trim($this->input->post('new_pwd'));
        $rNewPwd = trim($this->input->post('rnew_pwd'));
        $key = trim($this->input->post('key'));

        if(empty($email) || empty($nickname) || empty($newPwd) || empty($rNewPwd) || empty($key)) {
            return $this->showMessage('Lack of necessary information', [], 400, 3);
        }

        if(!preg_match("/^[0-9A-Za-z_]{6,20}$/i", $nickname)) {
            return $this->showMessage('Please enter a 6-20 digit number, letter, and underscore username', [], 400, 1);
        }

        if($newPwd != $rNewPwd) {
            return $this->showMessage('Inconsistent input twice', [], 400, 4);
        }

        $this->load->model('member/user');
        $user = $this->user->getByNickname($nickname);
        if(!empty($user)) {
            return $this->showMessage('Username already exists', [], 400, 6);
        }

        $this->load->driver('cache');
        $trueKey = $this->cache->redis->get('Est_User_Email_Key-' . $email);
        if(empty($trueKey) || $trueKey != $key) {
            return $this->showMessage('Parameter error', [], 400, 5);
        }

 
        $file_name = str_replace('.', '_', $email) . '_' . date('mdHis');
        $this->load->library('upload');
        $config['upload_path'] = './uploads/avatar/';
    
        $config['allowed_types'] = 'gif|jpg|png';
        $config['file_name'] = $file_name . '.png';
        $config['overwrite'] = true;
        $config['max_size'] = '2048';
        $this->upload->initialize($config);

        if ($this->upload->do_upload('avatar')) {
            $info = $this->upload->data();
            $passport_front = $info['full_path'];
        } else {
            $message = $this->upload->display_errors();
            return $this->showMessage($message, [], 400, 7);
        }

        $rand = $this->generateVcode(6);
        $data = [
            'email'    => $email,
            'nickname' => $nickname,
            'password' => md5($newPwd . $rand),
            'money'    => 0,
            'slat'     => $rand,
        ];

        $this->load->model('member/user');
        $result = $this->user->add($data);
        if($result) {
            $re_config['image_library'] = 'gd2';
            $re_config['source_image'] = $passport_front;
            $re_config['new_image'] = str_replace($file_name, $result, $passport_front);
            $re_config['width'] = 180;
            $re_config['height'] = 180;

            $this->load->library('image_lib', $re_config);
            if($this->image_lib->resize()) {
                unlink($passport_front);
            }

            return $this->showMessage('success');
        }
        return $this->showMessage('registration failed', [], 400, 8);
    }

   
    public function login()
    {

        if(!$this->beforeAction()) {
            return $this->showMessage('Unauthorized', [], 401, 1);
        }

        $email = trim($this->input->post('email'));
        $pwd = trim($this->input->post('pwd'));

        $this->load->model('member/user');
        $user = $this->user->getUserByEmail($email);
        if(empty($user)) {
            return $this->showMessage('User does not exist', [], 400, 2);
        }

        if($user['password'] != md5($pwd . $user['slat'])) {
            return $this->showMessage('wrong password', [], 400, 3);
        }

        $token = $this->getToken($user['uid']);
        $authkey = $this->getAuthkey(false);
        $data = [
            'uid' => intval($user['uid']),
            'nickname' => $user['nickname'],
            'token' => $token,
            'authkey' => $authkey,
            'avatar' => $this->getAvatar($user['uid']),
        ];
        return $this->showMessage('success', $data);
    }

   
    public function modifyAvatar()
    {
        if(!$this->beforeAction()) {
            return $this->showMessage('Unauthorized', [], 401, 1);
        }

        if(!$this->uid) {
            return $this->showMessage('User not logged in', [], 400, 1);
        }

        $this->load->library('upload');
        $config['upload_path'] = './uploads/avatar/';
      
        $config['allowed_types'] = 'gif|jpg|png';
        $config['file_name'] = $this->uid . '.png';
        $config['overwrite'] = true;
        $config['max_size'] = '2048';
        $this->upload->initialize($config);

        if ($this->upload->do_upload('avatar')) {
            $info = $this->upload->data();
            $passport_front = $info['full_path'];
        } else {
            $message = $this->upload->display_errors();
            return $this->showMessage($message, [], 400, 2);
        }

        return $this->showMessage('success', ['avatar' => $this->getAvatar($this->uid)]);
    }

   
    public function getGameAuthDetail()
    {
        if(!$this->beforeAction()) {
            return $this->showMessage('Unauthorized', [], 401, 1);
        }

        if(!$this->uid) {
            return $this->showMessage('User not logged in', [], 400, 1);
        }

        $bindid = $this->input->get('bindid');
        $this->load->model('member/gameUserAccount');
        $account = $this->gameUserAccount->getById($bindid);
        if(empty($account) || $account['status'] != GameUserAccount::STATUS_NORMAL) {
            return $this->showMessage('account does not exist', [], 400, 2);
        }

        if($account['auth_status'] == GameUserAccount::AUTH_STATUS_SUCCESS) {
            return $this->showMessage('This account has been reviewed', [], 400, 3);
        }

        $this->config->load('common');
        $game_auth_text = $this->config->item('est_common')['game_auth_text'];
        $data = $game_auth_text[$account['gametype']];

        return $this->showMessage('success', $data);
    }

  
    private function downImage($url, $dir, $filename)
    {

        if(empty($url)){
            return 'Address cannot be empty';
        }

        if(!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }
        $dir = realpath($dir);
        
        $filename = $dir . '/' . $filename;
        
        ob_start();
        readfile($url);
        $img = ob_get_contents();
        ob_end_clean();
        $size = strlen($img);
        $fp2 = fopen($filename , "a");
        fwrite($fp2, $img);
        fclose($fp2);
        return false;
    }

   
    public function updated()
    {

        if(!$this->beforeAction()) {
            return $this->showMessage('Unauthorized', [], 401, 1);
        }

        $device_token = trim($this->input->post('device_token'));
        if(empty($device_token) || empty($this->platform)) {
            return $this->showMessage('Lack of necessary information', [], 400, 2);
        }

        if(!$this->uid) {
            return $this->showMessage('User not logged in', [], 400, 3);
        }

        $this->load->model('member/user');
        $user = $this->user->getByUid($this->uid);
        if(empty($user)) {
            return $this->showMessage('User does not exist', [], 400, 4);
        }

        $data = [
            'platform'     => $this->platform,
            'device_token' => $device_token,
        ];
        $this->user->edit($this->uid, $data);

        return $this->showMessage('success');
    }


    public function submitGameAuth()
    {
        if(!$this->beforeAction()) {
            return $this->showMessage('Unauthorized', [], 401, 1);
        }

        if(!$this->uid) {
            return $this->showMessage('User not logged in', [], 400, 1);
        }

        $bindid = $this->input->get('bindid');
        $this->load->model('member/gameUserAccount');
        $account = $this->gameUserAccount->getById($bindid);
        if(empty($account) || $account['status'] != GameUserAccount::STATUS_NORMAL) {
            return $this->showMessage('account does not exist', [], 400, 2);
        }

        if($account['auth_status'] == GameUserAccount::AUTH_STATUS_SUCCESS) {
            return $this->showMessage('This account has been reviewed', [], 400, 3);
        }

   
        $file_name = 'game_auth_id_' . $bindid . '_' . date('Ymd_His');
        $this->load->library('upload');
        $config['upload_path'] = './uploads/gameauth/';

        $config['allowed_types'] = 'gif|jpg|png';
        $config['file_name'] = $file_name . '.png';
        $config['overwrite'] = true;
        $config['max_size'] = '2048';
        $this->upload->initialize($config);

        if ($this->upload->do_upload('authimg')) {
            $info = $this->upload->data();
            $passport_front = $info['full_path'];
        } else {
            $message = $this->upload->display_errors();
            return $this->showMessage($message, [], 400, 4);
        }

        $update = [
            'auth_status' => GameUserAccount::AUTH_STATUS_ING,
            'auth_img'    => $file_name,
        ];
        $res = $this->gameUserAccount->edit($bindid, $update);
        if($res) {
            return $this->showMessage('success');
        }

        return $this->showMessage('Submit review failed, please try again later', [], 400, 5);
    }


    public function bindAccountTpl()
    {
        if(!$this->beforeAction()) {
            return $this->showMessage('Unauthorized', [], 401, 1);
        }

        if(!$this->uid) {
            return $this->showMessage('User not logged in', [], 400, 1);
        }

        $gametype = intval($this->input->get('gametype'));
        if(!$gametype) {
            return $this->showMessage('Parameter error', [], 400, 2);
        }

        $maps = [
            9 => [
                'title'     => 'Bind PUBG Account',
                'sub_title' => 'IMPORTANT STATEMENTS',
                'content'   => 'You have to make sure this PUBG account belongs to you, if you use others PUBG account, you will get into trouble when you withdraw EST to your ETH address. We need to verify your PUBG account when you withdraw EST. Once your nickname is confirmed, you could\'t change account anymore.',
            ],
        ];

        if(empty($maps[$gametype])) {
            return $this->showMessage('Parameter error', [], 400, 2);
        }

        $this->load->view('app/member/bind_account', [
            'data'     => $maps[$gametype],
            'gametype' => $gametype,
        ]);
    }

 
    public function bindAccount()
    {
        if(!$this->beforeAction()) {
            return $this->showMessage('Unauthorized', [], 401, 1);
        }

        if(!$this->uid) {
            return $this->showMessage('User not logged in', [], 400, 1);
        }

        $gametype = $this->input->post('gametype');
        $data = $this->input->post('data');
        if(empty($gametype) || empty($data)) {
            return $this->showMessage('Parameter error', [], 400, 2);
        }

        $data = json_decode($data, true);
        if(empty($data['account_name'])) {
            return $this->showMessage('Parameter error', [], 400, 3);
        }

        $this->load->model('member/gameUserAccount');
        $list = $this->gameUserAccount->listByUid($this->uid);
        $list && $list = array_combine(array_column($list, 'gametype'), $list);
        !empty($list[$gametype]) && $account = $list[$gametype];

      
        if(!empty($account) && in_array($account['status'], [GameUserAccount::STATUS_NORMAL, GameUserAccount::STATUS_TODO_VERIFIED])) {
            return $this->showMessage('Account already exists or account is verifying', [], 400, 4);
        }

        $add = [
            'uid'      => $this->uid,
            'gametype' => $gametype,
            'accountname' => $data['account_name'],
            'ispulled'    => 0,
            'status'      => GameUserAccount::STATUS_TODO_VERIFIED,
            'auth_status' => 0,
            'auth_img'    => '',
        ];

        $lastid = $this->gameUserAccount->add($add);
        if(!$lastid) {
            return $this->showMessage('Submission Failed', [], 400, 5);
        }

        if(!empty($account)) {
            $update = [
                'status' => GameUserAccount::STATUS_REMOVE,
            ];
            $this->gameUserAccount->edit($account['id'], $update);
        }

        if($gametype == 9) {
            $this->gameUserAccount->pubgQueue($data['account_name']);
        }

        return $this->showMessage('success');
    }

    public function readMsg()
    {
        if(!$this->beforeAction()) {
            return $this->showMessage('Unauthorized', [], 401, 1);
        }

        if(!$this->uid) {
            return $this->showMessage('User not logged in', [], 400, 1);
        }

        $pid = trim($this->input->get('pid'));
        if(empty($pid)) {
            return $this->showMessage('Parameter error', [], 400, 2);
        }

        $this->load->model('member/user');
        $user = $this->user->getByUid($this->uid);
        if(empty($user)) {
            return $this->showMessage('User does not exist', [], 400, 3);
        }

        $this->user->delPopupByPid($this->uid, $pid);

        return $this->showMessage('success');
    }
}