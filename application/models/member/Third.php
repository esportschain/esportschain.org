<?php
/**
*/
class Third extends CI_Model
{
    

    const STATUS_NORMAL = 1;

    const STATUS_REMOVE = 5;

    const THIRDTYPE_STEAM = 1; // steam
    const THIRDTYPE_FACEBOOK = 2; // facebook

    private static $_table = 'member_third';

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    /**
     * [edit]
     * @DateTime 2018-06-13
     * @param    [type]     $id  [description]
     * @param    [type]     $data [description]
     * @return   [type]           [description]
     */
    public function edit($id, $data)
    {
        $this->db->where('id', $id);
        return $this->db->update(self::$_table, $data);
    }

    /**
     * [add]
     * @DateTime 2018-06-13
     * @param    [type]     $data [description]
     */
    public function add($data)
    {
        if(empty($data['status'])) {
            $data['status'] = self::STATUS_NORMAL;
        }

        $this->db->insert(self::$_table, $data);
        return $this->db->insert_id();
    }

    /**
     * [getByUnionid]
     * @DateTime 2018-06-13
     * @param    [type]     $unionid [description]
     * @param    string     $fields  [description]
     * @return   [type]              [description]
     */
    public function getByUnionid($unionid, $fields = '')
    {
        return $this->db->from(self::$_table)
                ->where(['unionid' => $unionid, 'status !=' => self::STATUS_REMOVE])
                ->select($fields)
                ->get()
                ->row_array();
    }

    /**
     * [getByUid]
     * @Author   hq
     * @DateTime 2018-07-16
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
}