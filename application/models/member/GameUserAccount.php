<?php
/**
*/
class GameUserAccount extends CI_Model
{

    const STATUS_NORMAL = 1;

    const STATUS_NOT_FOUND = 2;

    const STATUS_TODO_VERIFIED = 3;

    const STATUS_REMOVE = 5;

    const AUTH_STATUS_SUCCESS = 1;

    const AUTH_STATUS_ING = 2;

    const AUTH_STATUS_FAILED = 3;
    
    private static $_table = 'game_user_account';

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    /**
     * [getByUidAndName]
     * @param    [type]     $uid         [description]
     * @param    [type]     $gametype    [description]
     * @param    [type]     $accountname [description]
     * @param    string     $fields      [description]
     * @return   [type]                  [description]
     */
    public function getByUidAndName($uid, $gametype, $accountname, $fields = '')
    {
        return $this->db->from(self::$_table)
                ->where(['uid' => $uid, 'gametype' => $gametype, 'accountname' => $accountname, 'status !=' => self::STATUS_REMOVE])
                ->select($fields)
                ->get()
                ->row_array();
    }

    /**
     * [listByUid]
     * @DateTime 2018-06-11
     * @param    [type]     $uid    [description]
     * @param    string     $fields [description]
     * @return   [type]             [description]
     */
    public function listByUid($uid, $fields = '')
    {
        return $this->db->from(self::$_table)
                ->where(['uid' => $uid, 'status !=' => self::STATUS_REMOVE])
                ->select($fields)
                ->get()
                ->result_array();
    }

    /**
     * [getById]
     * @Author   hq
     * @DateTime 2018-07-15
     * @param    [type]     $id     [description]
     * @param    string     $fields [description]
     * @return   [type]             [description]
     */
    public function getById($id, $fields = '')
    {
        return $this->db->from(self::$_table)
                ->where(['id' => $id, 'status !=' => self::STATUS_REMOVE])
                ->select($fields)
                ->get()
                ->row_array();
    }

    /**
     * [add]
     * @Author   hq
     * @DateTime 2018-07-15
     * @param    [type]     $data [description]
     */
    public function add($data)
    {
        if(empty($data['created'])) {
            $data['created'] = time();
        }

        $this->db->insert(self::$_table, $data);
        return $this->db->insert_id();
    }

    /**
     * [edit]
     * @Author   hq
     * @DateTime 2018-07-15
     * @param    [type]     $id   [description]
     * @param    [type]     $data [description]
     * @return   [type]           [description]
     */
    public function edit($id, $data)
    {
        if(empty($data['updated'])) {
            $data['updated'] = time();
        }
        $this->db->where('id', $id);
        return $this->db->update(self::$_table, $data);
    }

    /**
     * [pubgQueue]
     * @Author   hq
     * @DateTime 2018-07-15
     * @param    [type]     $accountname [description]
     * @return   [type]                  [description]
     */
    public function pubgQueue($accountname)
    {
        $pubg_queue_key_distinct = 'pull-pubg-data-distinct';
        $pubg_queue_key = 'pull-pubg-data-queue';
        $this->load->driver('rediscli');
        if($this->rediscli->default->sadd($pubg_queue_key_distinct, $accountname)) {
            $this->rediscli->default->rpush($pubg_queue_key, $accountname);
        }

        return true;
    }

    /**
     * [countAll]
     * @Author   hq
     * @DateTime 2018-07-15
     * @param    [type]     $status [description]
     * @return   [type]             [description]
     */
    public function countAll($status)
    {
        $query = $this->db->from(self::$_table);
        if($status) {
            $query->where(['status' => $status]);
        }else {
            $query->where(['status !=' => self::STATUS_REMOVE]);
        }
        return $query->count_all_results();
    }

    /**
     * [listAll]
     * @Author   hq
     * @DateTime 2018-07-15
     * @param    [type]     $status      [description]
     * @param    [type]     $pageOptions [description]
     * @param    string     $fields      [description]
     * @param    string     $order       [description]
     * @return   [type]                  [description]
     */
    public function listAll($status, $pageOptions, $fields = '', $order = '')
    {
        $query = $this->db->from(self::$_table);
        if($status) {
            $query->where(['status' => $status]);
        }else {
            $query->where(['status !=' => self::STATUS_REMOVE]);
        }

        return $query->select($fields)
                ->order_by($order)
                ->offset($pageOptions['perPage'] * ($pageOptions['currentPage'] - 1))
                ->limit($pageOptions['perPage'])
                ->get()
                ->result_array();
    }
}