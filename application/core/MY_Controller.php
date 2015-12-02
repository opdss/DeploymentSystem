<?php if (!defined('BASEPATH')) {exit('No direct script access allowed');
}

/**
 * Created by PhpStorm.
 * @author wuxin
 * @date 2015 15/5/7 下午5:33
 * @copyright 7659.com
 */
class MY_Controller extends CI_Controller {

	public function __construct() {
		parent::__construct();
	}

	static public function jsonMsg($code, $data_msg = '',$callback = false, $type = false) {
		$msg = array(
			-1=> 'please login',
			0 => 'error',
			1 => 'success',
			2 => 'permission denied',
		);
		$data['code'] = $code;
		$data['msg'] = $code == 1?'success':(!empty($data_msg)?(string)$data_msg:(isset($msg[$code])?$msg[$code]:'error'));
		$code != 1 || $data['data'] = $data_msg;
		if ($type) {
			return $data;
		} else {
            echo $callback ? $callback.'('.json_encode($data).')' : json_encode($data);
			exit;
		}
	}

    public function view($temp,$data = array(),$wap=false){
//        $this->load->library('mobile_Detect');
//        if($this->mobile_detect->is('iOS') || $this->mobile_detect->is('AndroidOS') || strpos($_SERVER["SERVER_NAME"],'m.10wfuli.com') !== false)
//        {
//            define('ISWAP',true);
//        }
//        else
//        {
//            define('ISWAP',false);
//        }
        //if (!file_exists(VIEWPATH . $temp . '.php')) {
        //    exit('no template');
            //show_404();
        //}
        isset($data['title']) || $data['title'] = '';
        $data['_G'] = $this->_G;
        $wap && ISWAP && (file_exists(VIEWPATH . 'wap/'.$temp . '.php') ? $temp = 'wap/'.$temp : show_404());

        //log_message('debug',$wap.'--'.intval(ISWAP).'--'.VIEWPATH . 'wap/'.$temp . '.php'.'--'.$temp);
        //log_message('debug',serialize($_SERVER));
        //$temp = $wap && file_exists(VIEWPATH . $temp . '.php') ? 'wap/'.$temp : $wap;
        //$this->load->view('public/header', $data);
        $this->load->view($temp, $data);
        //$this->load->view('public/footer');
    }

    protected function _chenkRePost($t=5){
        //if(self::$isAjax){
            $re = md5(serialize($_POST));
            $re_val = $this->session->userdata($re);
            $this->session->set_userdata($re,TIMESTAMP);
            if($re_val && (TIMESTAMP-$re_val<$t)){
                //self::jsonMsg(0,'你提交数据的次数太频繁');
                exit();
            }
        //}
    }

    protected function _checkLogin(){
        if(empty($this->_G['userInfo'])){
            self::$isAjax ? self::jsonMsg(-1) : redirect('login/index');
        }
    }
}