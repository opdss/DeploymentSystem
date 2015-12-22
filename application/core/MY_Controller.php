<?php  defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * @author wuxin
 * @date 2015 15/5/7 下午5:33
 * @copyright 7659.com
 */
class MY_Controller extends CI_Controller {

    protected static $pageSize = 10;
    public $_G = array(
        'title'=>'部署系统'
    );

	public function __construct() {
		parent::__construct();
        $this->load->language('deploy');
	}

	static public function jsonMsg($code, $data_msg = '', $type = false) {
		$msg = array(
			-1=> 'please login',
			0 => 'error',
			1 => 'success',
			2 => 'permission denied',
		);
		$data['code'] = $code;
		$data['msg'] = $code == 1?'success':(!empty($data_msg)?(string)$data_msg:(isset($msg[$code])?$msg[$code]:'error'));
		$code == 1 and $data['data'] = $data_msg;
		if ($type) {
			return $data;
		} else {
            echo json_encode($data);
			exit;
		}
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

    protected function error($message,$code=0,$template=null,$data=null){
        if($this->input->is_ajax_request()){
            self::jsonMsg($code,$message);
        }
        $this->_G['message'] = $message;
        $tpl = 'public/error';
        if($template != null){
            $tpl = $template;
            $this->_G['tplVars'] = $data;
        }
        $str = $this->load->view($tpl,$this->_G,true);
        echo $str;exit;
    }

    protected function success($message,$code=1){
        if($this->input->is_ajax_request()){
            self::jsonMsg($code,$message);
        }
        $this->_G['message'] = $message;
        $str = $this->load->view('public/success',$this->_G,true);
        echo $str;exit;
    }

}