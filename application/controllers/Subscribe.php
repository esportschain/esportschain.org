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

    /**
     *  Index Page for this controller.
     */
    public function schedule() {
        $data =  [
            'result' => 0, 'message' => '.',
            'start_status' => "Fail",
            'sell_status' => 0, 'eth_num' => "- ETH",
            'start_time' => "2018-03-26 10:00:00",
            'end_time' => "2018-04-16 18:00:00",
            'go_buy' => "buy.html",
            'show_width' => "0%",
        ];

        $refer = '';
        if (!empty($_SERVER['HTTP_REFERER'])) {
             $refer = $_SERVER['HTTP_REFERER'];
        }

        $now_time = time();
        $start_time = strtotime($data['start_time']);
        $end_time = strtotime($data['end_time']);
        $data['start_status'] = 'processing';
        if($start_time > $now_time) {
            $data['start_status'] = 'not started';
            $data['sell_status'] = -1;
        } elseif ($now_time > $end_time) {
            $data['start_status'] = 'has ended';
            $data['sell_status'] = 1;
        }

        if(stripos($refer, 'cn.html') !== FALSE){
            $data['start_status'] = $this->en2cn($data['start_status']);
            $data['go_buy'] = 'buy_cn.html';
        } elseif (stripos($refer,'kr.html') !== FALSE) {
            $data['start_status'] = $this->en2kr($data['start_status']);
            $data['go_buy'] = 'buy_kr.html';
        }

        if($data['sell_status'] != -1) {
            // Get Ethereum Number
            $total_num = 11000000000000000000000;
            $left_num = file_get_contents('http://127.0.0.1:8000/salestate');
            $data['eth_num'] = ($total_num  - $left_num) / pow(10, 18) . ' ETH';
            $data['show_width'] = ($total_num  - $left_num) / $total_num * 100 . '%';
        }

        $this->output->set_content_type('application/json')
            ->set_output(json_encode($data));
    }

    private function en2kr ($key) {
        $lang = [
            'not started' => "대기 중",
            'processing' => "진행 중",
            'has ended' => "종료 됨",
        ];

        return $lang[$key];
    }

    private function en2cn ($key) {
        $lang = [
            'not started' => "未开始",
            'processing' => "进行中",
            'has ended' => "已结束",
        ];

        return $lang[$key];
    }
}
