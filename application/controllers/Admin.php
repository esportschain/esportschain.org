<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends CI_Controller    {

    /**
     * 后台首页
     */
    public function index()
    {
        $this->load->view('admin/index');
    }

    /**
     * 登陆页面
     */
    public function login()
    {
        $this->load->library('session');
        if($this->session->is_login){
            $this->load->helper('url');
            redirect('c=admin&m=index','location');
        }else{
            $csrf = array(
                'csrf_name' => $this->security->get_csrf_token_name(),
                'csrf_hash' => $this->security->get_csrf_hash()
            );
            $this->load->view('admin/login',$csrf);
        }
    }

    /**
     * 登陆验证
     */
    public function checkLogin()
    {
        $name = 'wanplus_esc';
        $pass = 'esc_wanplus';
        $post = $this->input->post();
        if($post['username'] == $name && $post['password'] == $pass){
            $this->load->library('session');
            $this->session->set_userdata('is_login', true);
            echo '<script>alert("登陆成功！");window.location.href="/index.php?c=admin&m=index";</script>';
        }else{
            echo '<script>alert("登陆失败！");window.location.href="/index.php?c=admin&m=login";</script>';
        }
    }

    /**
     * 新闻列表
     */
    public function news()
    {
        date_default_timezone_set('GMT');
        $data['list'] = [];
        $this->load->database();
        $this->db->from('est_news');
        $rst = $this->db->get()->result_array();
        // 处理发布时间为此格式 ：Tue,15 May 2018 10:05:23 GMT
        foreach ($rst as &$v){
            $v['publish_time'] = date('D,d M Y H:i:s',$v['publish_time']).' GMT';
        }
        $data['list'] = $rst;
        $this->load->view('admin/news',$data);
    }

    /**
     * 编辑新闻
     */
    public function editnews()
    {
        date_default_timezone_set('PRC');
        $this->load->database();
        if($this->input->method() == 'post') {
            $post = $this->input->post();
            $post['publish_time'] = strtotime($post['publish_time']);
            $post['add_time'] = strtotime($post['add_time']);
            $this->db->replace('est_news', $post);
            $this->load->helper('url');
            echo '<script>alert("修改成功！");window.location.href="/index.php?c=admin&m=news";</script>';
//            redirect('/index.php?c=admin&m=news','location');
        }else{
            $newsid = $this->input->get('newsid');
            $this->db->from('est_news');
            $this->db->where('id',$newsid);
            $rst = $this->db->get()->row_array();
            // 处理发布时间为此格式 ：Tue,15 May 2018 10:05:23 GMT
            $rst['publish_time'] = date('Y-m-d H:i:s',$rst['publish_time']);
            $rst['add_time'] = date('Y-m-d H:i:s',$rst['add_time']);
            $rst['csrf_name'] = $this->security->get_csrf_token_name();
            $rst['csrf_hash'] = $this->security->get_csrf_hash();
            $this->load->view('admin/newsdetail',$rst);
        }

    }
    /**
     * 添加新闻
     */
    public function addnews()
    {
        date_default_timezone_set('PRC');
        if($this->input->method() == 'post') {
            $post = $this->input->post();
            $post['publish_time'] = strtotime($post['publish_time']);
            $post['add_time'] = time();
            $this->load->database();
            $this->db->insert('est_news', $post);
            $this->load->helper('url');
            echo '<script>alert("添加成功！");window.location.href="/index.php?c=admin&m=news";</script>';
//            redirect('/index.php?c=admin&m=news','location');
        }else{
            $csrf = array(
                'csrf_name' => $this->security->get_csrf_token_name(),
                'csrf_hash' => $this->security->get_csrf_hash()
            );
            $this->load->view('admin/addnews',$csrf);
        }

    }
}
