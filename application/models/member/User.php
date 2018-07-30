<?php
/**
 */
class User extends CI_Model
{

    const STATUS_NORMAL = 1;

    const STATUS_REMOVE = 5;

    private static $_table = 'member_user';

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    /**
     * [getUserByEmail]
     * @DateTime 2018-06-11
     * @param    [type]     $email  [description]
     * @param    string     $fields [description]
     * @return   [type]             [description]
     */
    public function getUserByEmail($email, $fields = '')
    {
        return $this->db->from(self::$_table)
                ->where(['email' => $email, 'status !=' => self::STATUS_REMOVE])
                ->select($fields)
                ->get()
                ->row_array();
    }

    /**
     * [getByUid]
     * @DateTime 2018-06-11
     * @param    [type]     $uid    [description]
     * @param    string     $fields [description]
     * @return   [type]             [description]
     */
    public function getByUid($uid, $fields = '')
    {
        return $this->db->from(self::$_table)
                ->where(['uid' => $uid, 'status !=' => self::STATUS_REMOVE])
                ->select($fields)
                ->get()
                ->row_array();
    }

    /**
     * [getByNickname]
     * @DateTime 2018-06-11
     * @param    [type]     $nickname [description]
     * @param    string     $fields   [description]
     * @return   [type]               [description]
     */
    public function getByNickname($nickname, $fields = '')
    {
        return $this->db->from(self::$_table)
                ->where(['nickname' => $nickname, 'status !=' => self::STATUS_REMOVE])
                ->select($fields)
                ->get()
                ->row_array();
    }

    /**
     * [edit]
     * @DateTime 2018-06-11
     * @param    [type]     $uid  [description]
     * @param    [type]     $data [description]
     * @return   [type]           [description]
     */
    public function edit($uid, $data)
    {
        if(empty($data['updated'])) {
            $data['updated'] = time();
        }
        $this->db->where('uid', $uid);
        return $this->db->update(self::$_table, $data);
    }

    /**
     * [add]
     * @DateTime 2018-06-11
     * @param    [type]     $data [description]
     */
    public function add($data)
    {
        if(empty($data['created'])) {
            $data['created'] = time();
        }

        if(empty($data['status'])) {
            $data['status'] = self::STATUS_NORMAL;
        }

        $this->db->insert(self::$_table, $data);
        return $this->db->insert_id();
    }

    /**
     * [decr]
     * @Author   hq
     * @DateTime 2018-07-02
     * @param    [type]     $uid   [description]
     * @param    [type]     $field [description]
     * @param    integer    $unit  [description]
     * @return   [type]            [description]
     */
    public function decr($uid, $field, $unit = 1)
    {
        $this->db->where('uid', $uid);
        $this->db->set($field, $field . ' - ' . $unit, FALSE);
        return $this->db->update(self::$_table);
    }

    /**
     * [incr]
     * @Author   hq
     * @DateTime 2018-07-02
     * @param    [type]     $uid   [description]
     * @param    [type]     $field [description]
     * @param    integer    $unit  [description]
     * @return   [type]            [description]
     */
    public function incr($uid, $field, $unit = 1)
    {
        $this->db->where('uid', $uid);
        $this->db->set($field, $field . ' + ' . $unit, FALSE);
        return $this->db->update(self::$_table);
    }

    /**
     * [getByUidForUpdate]
     * @Author   hq
     * @DateTime 2018-07-03
     * @param    [type]     $uid [description]
     * @return   [type]          [description]
     */
    public function getByUidForUpdate($uid)
    {
        $sql = "SELECT * FROM " . self::$_table . " WHERE uid = ? AND status != ? FOR UPDATE";
        return $this->db->query($sql, array($uid, self::STATUS_REMOVE))->row_array();
    }

    /**
     * [formatPopup]
     * @Author   hq
     * @DateTime 2018-07-15
     * @param    [type]     $popup [description]
     * @return   [type]            [description]
     */
    public static function formatPopup($popup)
    {
        $data = [];
        if(empty($popup)) {
            return $data;
        }

        $maps = [
            '1_1' => [
                'pid'  => '1_1',
                'type' => 1,  
                'msg'  => 'Congratulations! Your PUBG account verification is finished，you can withdraw your EST now！',  
            ],
            '1_2' => [
                'pid'  => '1_2',
                'type' => 1,  
                'msg'  => 'Your PUBG account verification is failed，you have to resubmit your information！',  
            ],
        ];

        $popup = json_decode($popup, true);
        foreach ($popup as $k => $v) {
            !empty($maps[$k]) && $data[] = $maps[$k];
        }
        return $data;
    }

    /**
     * [setPopup]
     * @Author   hq
     * @DateTime 2018-07-16
     * @param    [type]     $uid   [description]
     * @param    [type]     $popup [description]
     */
    public function setPopup($uid, $popup)
    {
        $user = $this->getByUid($uid, 'popup');
        $tmp = !empty($user['popup']) ? json_decode($user['popup'], true) : [];
        $data = array_merge($tmp, $popup);
        return $this->edit($uid, ['popup' => json_encode($data)]);
    }

    /**
     * [delPopupByPid ]
     * @Author   hq
     * @DateTime 2018-07-16
     * @param    [type]     $uid [description]
     * @param    [type]     $pid [description]
     * @return   [type]          [description]
     */
    public function delPopupByPid($uid, $pid)
    {
        $user = $this->getByUid($uid, 'popup');
        $tmp = !empty($user['popup']) ? json_decode($user['popup'], true) : [];
        $popup = array_diff_key($tmp, [$pid => '']);
        return $this->edit($uid, ['popup' => json_encode($popup)]);
    }

    /**
     * [addPush]
     * @Author   hq
     * @DateTime 2018-07-17
     * @param    [type]     $uid  [description]
     * @param    [type]     $body [description]
     */
    public function addPush($uid, $body)
    {

        $user = $this->getByUid($uid, 'platform,device_token');
        if(empty($body) || empty($user['platform']) || empty($user['device_token'])) {
            return false;
        }

        $body = array_merge($body, ['platform' => $user['platform'], 'device_token' => $user['device_token']]);

        $umeng_queue_key = 'umeng-queue';
        $this->load->driver('rediscli');
        return $this->rediscli->default->rpush($umeng_queue_key, json_encode($body));
    }
}