<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Api extends CI_Controller {

    /**
     * 获取新闻列表接口
     */
    public function getnews()
    {
        header('Access-Control-Allow-Origin: *');
        // 数据库中存的时间戳为北京时间时间戳，要展示的为gmt时区的时间
        date_default_timezone_set('GMT');
        $lang = $this->input->get('lang');
        if(!$lang || !in_array($lang,['kr','en','cn'])){
            return $this->output->set_content_type('application/json')->set_output(json_encode([]));
        }
        $this->load->database();
        $this->db->from('est_news');
        $this->db->where('is_del', '0');
        $this->db->where($lang.'_title !=', '');
        $this->db->where($lang.'_url !=', '');
        $this->db->order_by('sort','desc');// 现在按照排序字段排序
        $this->db->order_by('publish_time','desc');// 再按照publish_time排序
        $this->db->limit(10);
        $rst = $this->db->get()->result_array();
        // 处理发布时间为此格式 ：Tue,15 May 2018 10:05:23 GMT
        foreach ($rst as &$v){
            $v['publish_time'] = date('D,d M Y H:i:s',$v['publish_time']).' GMT';
        }
        return $this->output->set_content_type('application/json')->set_output(json_encode($rst));

    }
}
