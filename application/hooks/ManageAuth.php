<?php
/**
 * Created by PhpStorm.
 * @author wuxin
 * @date 2015 15/12/3 下午2:26
 * @copyright 7659.com
 */
class ManageAuth {

    private $CI;
    private $auth;
    private $notAuth = array(
        'login'
    );

    public function __construct() {
        $this->CI = &get_instance();
        $this->CI->load->config('manage_auth');
        $this->CI->auth = $this->CI->config->item('manageAuth');
    }
    /**
     * 权限认证
     */
    public function auth() {
        //$this->CI->session->set_userdata('userInfo',array('id'=>10,'username'=>'wuxin','email'=>'opdss@qq.com'));
        if(in_array($this->CI->router->class,$this->notAuth)){return;}
        $userInfo = $this->CI->session->userdata('userInfo');
        if(empty($userInfo)){
            if($this->CI->input->is_ajax_request()){
                return array('code' => -1, 'msg'=>'please login');
            }
            header('location:'.site_url('login'));exit;
        }

        $router = array($this->CI->router->class,$this->CI->router->method);
        //判断此url资源需不需言授权
        if($this->_checkNeedAuth($router)){
            //判断该用户有没有授权
            if(!$this->_checkAuth($userInfo['privilege'],$router)){
                if($this->CI->input->is_ajax_request()){
                    return array('code' => 2, 'msg'=>'permission denied');
                }
                exit('未授权');
            }
        }

        $memu = $this->_createMenu($userInfo['privilege']);

        $this->CI->_G['userInfo'] = $userInfo;
        $this->CI->_G['menu'] = $memu;
    }

    private function _checkNeedAuth($router){
        return
            !isset($this->CI->auth[$router[0]])
                ? false
                : (
                    isset($this->CI->auth[$router[0]][$router[1]])
                        ? true
                        : false
                );
        /*
        $menu = $this->CI->auth;
        $flog = false;
        foreach($router as $val){
            if(isset($menu[$val])){
                $menu = $menu[$val];
                $flog = true;
            }else{
                return false;
            }
        }
        return $flog;
        */
    }

    private function _checkAuth($privilege,$router){
        return in_array(implode('.',$router),$privilege);
        /*
        foreach($router as $val){
            if(isset($privilege[$val])){
                $privilege = $privilege[$val];
            }else{
                return false;
            }
        }
        return true;
        */
    }

    private function _createMenu($privilege){
        $menu = array();
        foreach($privilege as $val){
            $p = explode('.',$val);
            //isset($this->CI->auth[$p[0]])
            if(!isset($menu[$p[0]])){
                $menu[$p[0]]['_all'] = $this->CI->auth[$p[0]]['_all'];
            }
            $menu[$p[0]][$p[1]] = $this->CI->auth[$p[0]][$p[1]];
        }
        //var_dump($menu);exit;
        /*
        foreach($this->CI->auth as $k=>$v){
            if(isset($privilege[$k])){
                $menu[$k]['_all'] = $v['_all'];
                foreach($v as $_k=>$_v){
                    //in_array($_k,$privilege[$k]) and $menu[$k][$_k] = $_v;
                    isset($privilege[$k][$_k]) and $menu[$k][$_k] = $_v;
                }
            }
        }
        */
        return $menu;
    }

}