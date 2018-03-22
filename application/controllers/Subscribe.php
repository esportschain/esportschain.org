<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Subscribe extends CI_Controller {

    /**
     *  Index Page for this controller.
     */
	public function index()
	{
	    if($this->input->method() == 'post') {
            $data =  [
                'result' => "Fail",
                'message' => 'Email address error or repeated submission.'
            ];

            // 检查邮件
            $your_email = $this->input->post('youremail');
            if (preg_match("/([\w\-]+\@[\w\-]+\.[\w\-]+)/", $your_email)) {
                // 入库
                $this->load->database();
                $rows = [];
                $rows['email'] = $your_email;
                $rows['login_ip'] = $ip = $this->input->ip_address();
                $rows['submit_time'] =  date("Y-m-d H:i:s",time());

                $rst = $this->db->from('est_user_subscribe')->where('email', $your_email)->get()->result();
                if(empty($rst)) {
                   $flag = $this->db->insert('est_user_subscribe', $rows);
                   if(!empty($flag)) {
                       $data['result'] = 'success ';
                       $data['message'] = '<strong>Thank you</strong> for Subscribing our update.';
                   }
                }

            } else {
                $data['message'] = 'Email address error.';
             }
            $this->output->set_content_type('application/json')
                ->set_output(json_encode($data));
        }
	}
}
