<?php
/**
 * Created by PhpStorm.
 * @author wuxin
 * @date 2015 15/12/20 下午10:10
 * @copyright 7659.com
 */

defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends MY_Controller{

    function __construct(){
        parent::__construct();
    }

    function index(){
        $this->session->userdata('userInfo',false);
        $this->load->view('login/index',array('message'=>''));
    }

    function verify(){
        $username = $this->input->post('username');
        $password = $this->input->post('password');
        $this->load->model('user_model');
        $userInfo = $this->user_model->getOne('user',array('where'=>array('username'=>$username)));

        if(empty($userInfo)){
            $this->load->view('login/index',array('message'=>'您输入的用户不存在'));
            //$this->error('您输入的用户不存在');
        }else{
            if($userInfo['password'] != md5($password)){
                $this->load->view('login/index',array('message'=>'您输入的账号密码错误'));
                //$this->error('您输入的账号密码错误');
            }else {
                $userInfo['privilege'] = $this->user_model->getList(
                    'privilege',
                    array(
                        'where' => array('userId' => $userInfo['id']),
                        'callback' => function ($arr) {
                            return $arr['permitOperator'];
                        }
                    )
                );
                $this->session->set_userdata('userInfo', $userInfo);

                header('location:' . site_url('project/index'));
            }
        }
    }

    function logout(){
        $this->session->userdata('userInfo',false);
        header('location:'.site_url('login'));
    }
}