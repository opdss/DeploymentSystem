<?php
/**
 * Created by PhpStorm.
 * @author wuxin
 * @date 2015 15/12/2 下午7:19
 * @copyright 7659.com
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends MY_Controller{

    function __construct(){
        parent::__construct();
        //var_dump($this->session->userdata('userInfo'));exit;
        $this->load->model('user_model');
    }

    function index(){
        ($page = (int)$this->input->get('page')) or $page=1;

        $offset = ($page - 1) * self::$pageSize;

        $count = $this->user_model->getTotal('user');
        $tplVars = array('list'=>array(),'count'=>$count,'totalPage'=>ceil($count/self::$pageSize));

        if($count>0 && $count>$offset){
            $tplVars['list'] = $this->user_model->getList(
                'user',
                array(
                    'limit'=>array($offset,self::$pageSize),
                    'order'=>'id desc',
                    'callback'=>function($arr){
                        $arr['createTime']=date('Y-m-d H:i:s',$arr['createTime']);
                        return $arr;
                    }
                )
            );
        }
        $this->load->helper('page_helper');
        $tplVars['pageBar'] = getPageBar($count,$page,self::$pageSize);
        $this->_G['tplVars'] = $tplVars;
        $this->load->view('user/user',$this->_G);
    }

    function add($act=''){
        if($act == 'save'){
            $data = $this->_verifyInputUserInfo();
            $res = $this->user_model->getOne('user',array('where'=>array('email'=>$data['email'])));
            if ($res > 0) {
                $this->error(lang('user_email_isuse'));
            }
            $data['password'] = md5($data['password']);
            $data['createTime'] = $data['updateTime'] = TIMESTAMP;
            $uid = $this->user_model->insertKeyUp('user',$data);
            $uid ? $this->success(lang('user_add_success').',userid = '.$uid) : $this->error(lang('user_add_error'));
        }
        $this->load->view('user/user',$this->_G);
    }
    //删除用户记录，并删除用户与项目的绑定关系
    function del($id=0){
        ($id = intval($id)) or $this->error(lang('user_id_error'));
        if($id == $this->_G['userInfo']['id']){
            $this->error(lang('user_not_del_self'));
        }
        $del1 = $this->user_model->delete('user',array('id'=>$id));
        $del2 = $this->user_model->delete('user_project',array('userId'=>$id));
        if($del1 && $del2){
            $this->success(lang('user_del_success'));
        }else{
            $this->error(lang('user_del_error'));
        }
    }
    //查看用户详情和绑定的项目列表
    function show($id=0){
        $this->_G['tplVars']['userInfo'] = $this->_getUserInfo($id);
        $this->load->view('user/user',$this->_G);
    }

    function edit($id=0,$act=''){
        $userInfo = $this->_getUserInfo($id);
        if($act == 'save'){
            $data = $this->_verifyInputUserInfo($id);
            $res = $this->user_model->getOne('user',array('select'=>'id','where'=>array('email'=>$data['email'])));
            if ($res > 0 && $res['id'] != $id) {
                $this->error(lang('user_email_isuse'));
            }
            $data['password'] = $data['password'] == $userInfo['password'] ? $data['password'] : md5($data['password']);
            $data['updateTime'] = TIMESTAMP;
            $this->user_model->update('user',$data,array('id'=>$id))
                ? $this->success(lang('user_edit_success'))
                : $this->error(lang('user_edit_error'));
        }
        $this->_G['tplVars']['userInfo'] = $userInfo;
        $this->load->view('user/user',$this->_G);
    }
    //点击为用户授权操作，调用此controller，获取用户信息和用户已经有的操作权限以及没有的操作权限
    function privilege($id=0,$act=''){
        ($id = intval($id)) or $this->error(lang('user_id_error'));
        if($act == 'save'){
            $newPermitOps = $this->input->post('newPermitOps');
            if(empty($newPermitOps)){
                $this->error(lang('user_privilege_no'));
            }
            $oldPermitOps = $this->user_model->getList(
                'privilege',
                array(
                    'where' => array('userId'=>$id),
                    'callback' => function($arr){
                        return $arr['permitOperator'];
                    }
                )
            );
            $toAddOps = array_diff($newPermitOps, $oldPermitOps);
            $toDelOps = array_diff($oldPermitOps, $newPermitOps);
            if (count($toAddOps) > 0) {
                foreach($toAddOps as $k=>$v){
                    $this->user_model->insertKeyUp('privilege',array('userId'=>$id,'permitOperator'=>$v));
                }
            }
            if (count($toDelOps) > 0) {
                foreach($toDelOps as $k=>$v){
                    $this->user_model->delete('privilege',array('userId'=>$id,'permitOperator'=>$v));
                }
            }
            $this->success(lang('user_privilege_success'));
        }


        $userInfo = $this->user_model->getOne('user',array('where'=>array('id'=>$id)));
        if(empty($userInfo)){
            $this->error(lang('user_info_no'));
        }

        $_permitOperators = $this->user_model->getList('privilege', array('where' => array('userId'=>$id)));
        $permitOperators = array();
        foreach($_permitOperators as $k=>$v){
            $key = explode('.',$v['permitOperator']);
            $permitOperators[$v['permitOperator']] = $this->auth[$key[0]]['_all'].'-'.$this->auth[$key[0]][$key[1]];
        }

        $notPermitOperators = array();
        foreach($this->auth as $c => $m){
            foreach($m as $k=>$v){
                $key = $c.'.'.$k;
                if($k == '_all' || isset($permitOperators[$key]))
                    continue;
                $notPermitOperators[$key] = $this->auth[$c]['_all'].'-'.$v;
            }
        }
        $this->_G['tplVars'] = array(
            'userInfo' => $userInfo,
            'permitOperators' => $permitOperators,
            'notPermitOperators' => $notPermitOperators
        );
        $this->load->view('user/user',$this->_G);
    }
    //Controller 在主机查询页面，点击增加项目权限链接，进入此控制器，获取用户名、已赋予权限的project列表和没有权限的PROJECT列表
    function bindProject($id=0,$act=''){
        if($act == 'save'){
            ($id = intval($id)) or $this->error(lang('user_id_error'));
            $newBindPrjIds = $this->input->post('newBindProjects');
            if(empty($newBindPrjIds)){
                $this->user_model->delete('user_project',array('userId'=>$id));
                $this->success(lang('user_del_bind_project_success'));
            }
            $oldBindPrjIds = $this->user_model->getList(
                'user_project',
                array(
                    'where' => array('userId'=>$id),
                    'callback' => function($arr){
                        return $arr['projectId'];
                    }
                )
            );
            $toAddPrjIds = array_diff($newBindPrjIds, $oldBindPrjIds);
            $toDelPrjIds = array_diff($oldBindPrjIds, $newBindPrjIds);
            if (count($toAddPrjIds) > 0) {
                foreach($toAddPrjIds as $k=>$v){
                    $this->user_model->insertKeyUp('user_project',array('userId'=>$id,'projectId'=>$v));
                }
            }
            if (count($toDelPrjIds) > 0) {
                foreach($toDelPrjIds as $k=>$v){
                    $this->user_model->delete('user_project',array('userId'=>$id,'projectId'=>$v));
                }
            }
            $this->success(lang('user_add_bind_project_success'));
        }
        $userInfo = $this->_getUserInfo($id);
        if(empty($userInfo)){
            $this->error(lang('user_info_no'));
        }
        //获取未绑定的HOST列表
        $where = empty($userInfo['bindProjects'])
            ? ''
            : 'id NOT IN('. trim(
                array_reduce(
                    $userInfo['bindProjects'],
                    function($a,$b){
                        $a .= ','.$b['id'];
                        return $a;
                    }),
                ',') .')';

        $notBindProjects = $this->user_model->getList('project',array('where'=>$where));
        $this->_G['tplVars'] = array(
            'userInfo' => $userInfo,
            'notBindProjects' => $notBindProjects
        );
        $this->load->view('user/user',$this->_G);
    }


    //检验提交上来的用户数据
    private function _verifyInputUserInfo(&$id=null){
        $data = array();
        if($id !== null){
            ($id = intval($id)) or $this->error(lang('user_id_error'));
            $data['id'] = $id;
        }
        foreach(array(
                    'username' => array(1),
                    'password' => array(1),
                    'mobile' => array(1),
                    'email' => array(1),
                ) as $key=>$val){
            $data[$key] = $this->input->post($key);
            if(strlen($data[$key]) == 0 && $val[0] == 1){
                $this->error(lang($key).lang('field_empty'));
            }
        }
        return $data;
    }
    /*
    //检验提交上来的权限数据
    private function _verifyInputPrivilege(&$id=null){
        $data = array();
        if($id !== null){
            ($id = intval($id)) or $this->error(lang('user_id_error'));
            $data['id'] = $id;
        }
        foreach(array(
                    'username' => array(1),
                    'password' => array(1),
                    'mobile' => array(1),
                    'email' => array(1),
                ) as $key=>$val){
            $data[$key] = $this->input->post($key);
            if(strlen($data[$key]) == 0 && $val[0] == 0){
                $this->error($val.'字段不应为空');
            }
        }
        return $data;
    }
    */
    private function _getUserInfo(&$id){
        ($id = intval($id)) or $this->error(lang('user_id_error'));
        $detail = $this->user_model->getOne('user',array('where'=>array('id'=>$id)));
        if(empty($detail)){
            $this->error(lang('user_info_no'));
        }
        $detail['bindProjects'] = $this->user_model->getUserProjects($id);
        return $detail;
    }
}