<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class WhiteList extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */
	public function index() {
        // is_check
        $data = [
            'title' => 'Prompt information',
            'message' => 'Illegal submission',
            'url' => 'https://esportschain.org/'
        ];
        $is_pass = true;
        if($this->input->method() != 'post') {
            $is_pass = false;
            $data['message'] = 'Unknown source submission';
        }

        // 检查邮件
        $email = $this->input->post('email');
        if (!preg_match("/([\w\-]+\@[\w\-]+\.[\w\-]+)/", $email)) {
            $data['message'] = 'Please check email, user, account information';
            $is_pass = false;
        }

        if(!$this->input->post('termsAgreement') || !$this->input->post('countryAgreement')) {

            $data['message'] = 'Must agree to the agreement before they can submit';
            $is_pass = false;
        }

        $passport_back = $passport_front = '';
        if($this->input->method() == 'post') {
            $this->load->library('upload');
            $config['upload_path'] = './uploads/';
            // 设置允许上传的类型
            $config['allowed_types'] = '*';
            $config['max_size'] = '10240';
            // 如果是图片还可以设置最大高度和宽度
            $config['max_height'] = 1024;
            $config['max_width'] = 1024;
            $this->upload->initialize($config);

            if ($this->upload->do_upload('passport_front')) {
                $info = $this->upload->data();
                $passport_front = $info['full_path'];
            } else {
                $data['message'] = 'passport_front: '.$this->upload->display_errors();
                $is_pass = false;
            }

            if ($this->upload->do_upload('passport_back')) {
                $info = $this->upload->data();
                $passport_back = $info['full_path'];
            } else {
                $data['message'] = 'passport_back: '.$this->upload->display_errors();
                $is_pass = false;
            }
        }

        if ($is_pass) {
            $name = $this->input->post('name');
            $amount = $this->input->post('amount');
            $wallet = $this->input->post('wallet');
            $country = $this->input->post('country');


            $rows = [];
            $rows['email'] = $email;
            $rows['name'] = $name;
            $rows['amount'] = intval($amount);
            $rows['wallet'] = $wallet;
            $rows['country'] = $country;
            $rows['passport_front'] = $passport_front;
            $rows['passport_back'] = $passport_back;
            $rows['is_check'] = 1;
            $rows['login_ip'] = $ip = $this->input->ip_address();
            $rows['submit_time'] = date("Y-m-d H:i:s", time());

            // 入库
            $this->load->database();
            $rst = $this->db->from('est_user_whitelist')->where('wallet', $wallet)->get()->result();
            if (empty($rst)) {
                $flag = $this->db->insert('est_user_whitelist', $rows);
                if (!empty($flag)) {
                    $data['title'] = 'success ';
                    $data['message'] = 'submit success';
                    $data['url'] = 'buy.html';
                }
            }
        } else {
            $data['url'] = 'submit.html';
        }

        $this->load->view('welcome_message', $data);
    }

    public function check() {
        // is_check
        $data = [
            'result' => 0,
            'message' => 'Illegal submission',
        ];
        $is_pass = true;
        // 检查邮件
        $wallet = $this->input->get('wallet');
        if (!preg_match("/[0x]{2}[0-9a-zA-Z]{40,}/", $wallet)) {
            $data['message'] = 'Please check the account information';
            $is_pass = false;
            $data['result'] = -2;
        }

        if ($is_pass) {
            $wallet = $this->input->get('wallet');
            $this->load->database();
            $rst = $this->db->from('est_user_whitelist')->where('wallet', $wallet)->get()->result();
            $data['result'] = 0;
            if (empty($rst)) {
                $data['message'] = 'Has not yet submitted a whitelist';
            } elseif ($rst[0]->is_check == 0) {
                $data['message'] = 'checking';
            } else {
                $data['message'] = 'checked';
            }

        }

        $this->output->set_content_type('application/json')
            ->set_output(json_encode($data));
    }
}
