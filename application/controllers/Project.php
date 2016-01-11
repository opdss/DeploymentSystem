<?php
/**
 * Created by PhpStorm.
 * @author wuxin
 * @date 2015 15/12/2 下午7:19
 * @copyright 7659.com
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Project extends MY_Controller{

    function __construct(){
        parent::__construct();
        $this->load->model('project_model');
    }

    function index(){
        // 清理一下退出lock
        if (($clearlock = $this->input->get('clearlock')) && $clearlock == 'me' && ($fromPid = intval($this->input->get('frompid')))) {
            $this->load->model('deploy_model');
            $this->deploy_model->releaseDeployLock($fromPid,$this->_G['userInfo']['id'],true);
        }

        ($page = (int)$this->input->get('page')) or $page=1;

        $offset = ($page - 1) * self::$pageSize;

        $count = $this->project_model->getUserProjectTotal($this->_G['userInfo']['id']);

        $tplVars = array('list'=>array(),'count'=>$count,'totalPage'=>ceil($count/self::$pageSize));

        if($count>0 && $count>$offset){
            $tplVars['list'] = $this->project_model->getUserProjectList($this->_G['userInfo']['id'],$offset,self::$pageSize);
        }
        $this->load->helper('page_helper');
        $tplVars['pageBar'] = getPageBar($count,$page,self::$pageSize);
        $this->_G['tplVars'] = $tplVars;
        $this->load->view('project/project',$this->_G);
    }
    //获取项目的详细信息
    function show($id=0){
        $this->_G['tplVars']['projectInfo'] = $this->_getProjectInfo($id);
        $this->load->view('project/project',$this->_G);
    }
    //增加项目记录
    function add($act=''){
        if ($act == 'save') {
            $data = $this->_verifyInputProjectInfo();
            $res1 = $this->project_model->getTotal('project',array('name'=>$data['name']));
            $res2 = $this->project_model->getTotal('project',array('deployPath'=>$data['deployPath']));
            $res3 = $this->project_model->getTotal('project',array('prodPath'=>$data['prodPath']));
            if ($res1 > 0) {
                $this->error(lang('project_name_already_exists'));
            }
            if ($res2 > 0) {
                $this->error(lang('project_deploy_path_already_exists'));
            }
            if ($res3 > 0) {
                $this->error(lang('project_line_path_already_exists'));
            }
            if (is_dir($data['deployPath'])) {
                $this->error(lang('project_deploy_dir_already_exists'));
            }
            $data['createTime'] = $data['updateTime'] = TIMESTAMP;
            $res = $this->project_model->insertKeyUp('project',$data);
            if($res){
                $this->success(lang('project_add_success').',id='.$res);
            }else{
                $this->error(lang('db_insert_error'));
            }
        }
        $this->load->view('project/project', $this->_G);
    }

    //删除项目记录,并 删除与主机的绑定关系，并删除与用户的绑定关系
    function del($id=0){
        ($id = intval($id)) or $this->error(lang('project_id_error'));
        $del1 = $this->host_model->delete('project',array('id'=>$id));
        $del2 = $this->host_model->delete('project_host',array('projectId'=>$id));
        $del3 = $this->host_model->delete('user_project',array('projectId'=>$id));
        if($del1 && $del2 && $del3){
            $this->success(lang('project_del_success'));
        }else{
            $this->error(lang('project_del_error'));
        }
    }

    function edit($id=0,$act=''){
        if($act == 'save'){
            $data = $this->_verifyInputProjectInfo($id);
            $res1 = $this->project_model->getOne('project',array('where'=>array('name'=>$data['name']),'select'=>'id'));
            $res2 = $this->project_model->getOne('project',array('where'=>array('deployPath'=>$data['deployPath']),'select'=>'id'));
            $res3 = $this->project_model->getOne('project',array('where'=>array('prodPath'=>$data['prodPath']),'select'=>'id'));
            if (isset($res1['id']) && $res1['id'] != $id) {
                $this->error(lang('project_name_already_exists'));
            }
            if (isset($res2['id']) && $res2['id'] != $id) {
                $this->error(lang('project_deploy_path_already_exists'));
            }
            if (isset($res3['id']) && $res3['id'] != $id) {
                $this->error(lang('project_line_path_already_exists'));
            }
            $res = $this->project_model->update('project',$data,array('id'=>$id));
            $res ? $this->success(lang('project_edit_success')) : $this->error(lang('project_edit_error'));
        }
        $this->_G['tplVars']['projectInfo'] = $this->_getProjectInfo($id);
        $this->load->view('project/project',$this->_G);
    }

    function bindHost($id=0,$act=''){
        ($id = intval($id)) or $this->error(lang('project_id_error'));
        if($act == 'save'){
            $newBindHostIds = $this->input->post('newBindHosts');
            empty($newBindHostIds) and $this->error(lang('user_host_no'));
            $oldBindHostIds = $this->project_model->getList('project_host',array('select'=>'hostId','where'=>array('projectId'=>$id),'callback'=>function($arr){return $arr['hostId'];}));
            //$oldBindHostIds = array_reduce($oldBindHostIds,function($a,$b){static $i=0;$a[$i] = $b['hostId'];$i++;return $a;});
            //求交集去一条条增加或者删除绑定信息
            $toAddHostIds = array_diff($newBindHostIds, $oldBindHostIds);
            $toDelHostIds = array_diff($oldBindHostIds, $newBindHostIds);
            if (count($toAddHostIds) > 0) {
                foreach($toAddHostIds as $val){
                    $this->project_model->insertKeyUp('project_host',array('projectId'=>$id,'hostId'=>$val));
                }
            }
            if (count($toDelHostIds) > 0) {
                foreach($toDelHostIds as $val){
                    $this->project_model->delete('project_host',array('projectId'=>$id,'hostId'=>$val));
                }
            }
            $this->success(lang('project_bind_success'));
        }

        $tplVars['projectInfo'] = $this->project_model->getOne('project',array('where'=>array('id'=>$id)));
        if(empty($tplVars['projectInfo'])){
            $this->error(lang('project_info_no'));
        }
        //获取已经绑定的HOST列表
        $tplVars['haveBindHosts'] = $this->project_model->getBindHosts($id);
        //获取未绑定的HOST列表
        $where = empty($tplVars['haveBindHosts'])
            ? ''
            : 'id NOT IN('. trim(
                array_reduce(
                    $tplVars['haveBindHosts'],
                    function($a,$b){
                        $a .= ','.$b['id'];
                        return $a;
                    }),
                ',') .')';
        $tplVars['notBindHosts'] = $this->project_model->getList('host',array('where'=>$where));
        $this->_G['tplVars'] = $tplVars;
        $this->load->view('project/project',$this->_G);
    }

    //检验提交上来的项目数据
    private function _verifyInputProjectInfo(&$id=null){
        $data = array();
        if($id !== null){
            ($id = intval($id)) or $this->error(lang('project_id_error'));
            $data['id'] = $id;
        }
        foreach(array(
                    'cname' => array(1),
                    'name' => array(1),
                    'deployPath' => array(1),
                    'prodPath' => array(1),
                    'svnUrl' => array(1),
                    'rsyncUser' => array(1),
                    'beforeExec' => array(0),
                    'afterExec' => array(0),
                ) as $key=>$val){
            $data[$key] = trim($this->input->post($key));
            if(strlen($data[$key]) == 0 && $val[0] == 1){
                $this->error(lang($key).lang('field_empty'));
            }
            if($key == 'deployPath' || $key == 'prodPath'){
                $data[$key] = rtrim($data[$key],DIRECTORY_SEPARATOR);
            }
        }
        return $data;
    }
    //获取项目的详细信息
    private function _getProjectInfo(&$id,$getBindHosts=true){
        ($id = intval($id)) or $this->error(lang('project_id_error'));
        $detail = $this->project_model->getOne('project',array('where'=>array('id'=>$id)));
        if(empty($detail)){
            $this->error(lang('project_info_no'));
        }
        $getBindHosts and $detail['bindHosts'] = $this->project_model->getBindHosts($id);
        return $detail;
    }
}