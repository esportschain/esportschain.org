<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends CI_Controller    {

    /**
     *  检测是否登陆
     */
    public function __islogin()
    {
        $this->load->helper('cookie');
        if(get_cookie('is_login')){
            return true;
        }else{
            return false;
        }
    }
    /**
     * 后台首页
     */
    public function index()
    {
        if(!$this->__islogin()){
            $this->load->helper('url');
            redirect('c=admin&m=login','location');
        }
        $this->load->view('admin/index');
    }

    /**
     * 登陆页面
     */
    public function login()
    {
        $this->load->helper('cookie');
        if(get_cookie('is_login')){
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
     * 登陆页面
     */
    public function loginout()
    {
        $this->load->helper('cookie');
        delete_cookie('is_login');
        echo '<script>alert("已退出！");window.location.href="/index.php?c=admin&m=login";</script>';
    }

    /**
     * 登陆验证
     */
    public function checkLogin()
    {
        $post = $this->input->post();
        if(!$post['username'] || !$post['password']){
            echo '<script>alert("请输入用户名和密码！");window.location.href="/index.php?c=admin&m=login";</script>';
        }
        $this->config->load('user', TRUE);
        $configname = $this->config->item('username',"user");
        $configpass = $this->config->item('password',"user");
        if($post['username'] == $configname && md5($post['password']) == $configpass){
            $this->load->helper('cookie');
            set_cookie('is_login','1',86400);
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
        if(!$this->__islogin()){
            $this->load->helper('url');
            redirect('c=admin&m=login','location');
        }
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
        if(!$this->__islogin()){
            $this->load->helper('url');
            redirect('c=admin&m=login','location');
        }
        date_default_timezone_set('PRC');
        $this->load->database();
        if($this->input->method() == 'post') {
            // 获取数据
            $post = $this->input->post();
            // 请最少填写一组对应的标题和链接
            if(!(($post['cn_title']&&$post['cn_url'])||($post['en_title']&&$post['en_url'])||($post['kr_title']&&$post['kr_url']))){
                echo '<script>alert("请最少填写一组对应的标题和链接！");window.location.href="/index.php?c=admin&m=editnews&newsid='.$post['id'].'";</script>';exit;
            }
            // 发布时间不能为0 格式必须正确
            $post['publish_time'] = strtotime($post['publish_time']);
            if(!$post['publish_time']){
                echo '<script>alert("请按照规定格式填写发布时间！");window.location.href="/index.php?c=admin&m=editnews&newsid='.$post['id'].'";</script>';exit;
            }
            $post['add_time'] = strtotime($post['add_time']);
            // update数据库
            $this->db->replace('est_news', $post);
            $this->load->helper('url');
            echo '<script>alert("修改成功！");window.location.href="/index.php?c=admin&m=news";</script>';
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
        if(!$this->__islogin()){
            $this->load->helper('url');
            redirect('c=admin&m=login','location');
        }
        date_default_timezone_set('PRC');
        if($this->input->method() == 'post') {
            $post = $this->input->post();
            // 请最少填写一组对应的标题和链接
            if(!(($post['cn_title']&&$post['cn_url'])||($post['en_title']&&$post['en_url'])||($post['kr_title']&&$post['kr_url']))){
                echo '<script>alert("请最少填写一组对应的标题和链接！");window.location.href="/index.php?c=admin&m=addnews";</script>';exit;
            }
            // 发布时间不能为0 格式必须正确
            $post['publish_time'] = strtotime($post['publish_time']);
            if(!$post['publish_time']){
                echo '<script>alert("请按照规定格式填写发布时间！");window.location.href="/index.php?c=admin&m=addnews";</script>';exit;
            }
            $post['add_time'] = time();
            // insert 数据库
            $this->load->database();
            $this->db->insert('est_news', $post);
            $this->load->helper('url');
            echo '<script>alert("添加成功！");window.location.href="/index.php?c=admin&m=news";</script>';
        }else{
            $csrf = array(
                'csrf_name' => $this->security->get_csrf_token_name(),
                'csrf_hash' => $this->security->get_csrf_hash()
            );
            $this->load->view('admin/addnews',$csrf);
        }

    }
}
