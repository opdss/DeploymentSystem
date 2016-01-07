<?php
/**
 * Created by PhpStorm.
 * @author wuxin
 * @date 2015 15/12/2 下午7:19
 * @copyright 7659.com
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Host extends MY_Controller{

    function __construct(){
        parent::__construct();
        $this->load->model('host_model');
    }

    function index(){
        $page = (int)$this->input->get('page');
        $page or $page=1;
        $keyword = $this->input->get('keyword');
        $idc = $this->input->get('idc');


        $sql_filter = ' 1=1 ';
        empty($keyword) or $sql_filter .= ' and (hostname like "%'.$keyword.'%" or ip like "%'.$keyword.'%")';
        empty($idc) or $sql_filter .= ' and idc = "'.$idc.'" ';

        $count = $this->host_model->getTotal('host',$sql_filter);
        $offset = ($page-1)*self::$pageSize;

        $tplVars = array('list'=>array(),'count'=>$count,'totalPage'=>ceil($count/self::$pageSize));

        if($count>0 && $offset<$count){
            $tplVars['list'] = $this->host_model->getList(
                'host',
                array(
                    'where' => $sql_filter,
                    'order' => array('id'=>'desc'),
                    'limit' => array($offset,self::$pageSize),
                    'callback' => function($arr){$arr['createTime'] = date('Y-m-d',$arr['createTime']);return $arr;}
                )
            );
        }

        $tplVars['pageSize'] = self::$pageSize;
        $tplVars['page'] = $page;
        $tplVars['searchArr'] = array('keyword'=>$keyword,'idc'=>$idc);
        $tplVars['idcs'] = self::getIdcs();
        $this->load->helper('page_helper');
        $tplVars['pageBar'] = getPageBar($count,$page,self::$pageSize);
        $this->_G['tplVars'] = $tplVars;

        $this->load->view('host/host',$this->_G);
    }

    function add($act=''){
        //增加主机记录
        if($act == 'save'){
            //增加主机记录,增加时会检验主机IP是否已存在
            $data = $this->_verifyInputHostInfo();
            $count = $this->host_model->getTotal('host',array('ip'=>$data['ip']));
            if($count>0){
                $this->error(lang('host_ip_already_exists'));
            }
            $this->load->library('shell');
            if(!Shell::checkPing($data['ip'])){
                $this->error(lang('host_ping_ip_error'));
            }
            $data['createTime'] = $data['updateTime']= TIMESTAMP;
            $hostId = $this->host_model->insertKeyUp('host',$data);
            $this->success(lang('host_add_success').',id = '.$hostId);
        }
        $tplVars['idcs'] = self::getIdcs();
        $this->_G['tplVars'] = $tplVars;
        $this->load->view('host/host',$this->_G);
    }

    function show($id=0){
        $detail = $this->_getHostInfo($id);
        $this->_G['tplVars']['recordDetail'] = $detail;
        $this->load->view('host/host',$this->_G);
    }

    //主机删除被OPS收回，删除主机记录并删除与项目的绑定关系
    function del($id=0){
        ($id = intval($id)) or $this->error(lang('host_id_error'));
        $del1 = $this->host_model->delete('host',array('id'=>$id));
        $del2 = $this->host_model->delete('project_host',array('hostId'=>$id));
        if($del1 && $del2){
            $this->success(lang('host_del_success'));
        }else{
            if(!$del1){
                $log = 'Delete host record failed  && delete bindInfo in project_host success where host_id = '. $id;
            }else{
                $log = 'Delete host record success  && delete bindInfo in project_host failed where host_id = '. $id;
            }
            log_message('error',$log);
            $this->error(lang('host_del_error'));
        }
    }

    function edit($id=0,$act=''){
        if($act == 'save'){
            $data = $this->_verifyInputHostInfo($id);
            $res = $this->host_model->getOne('host',array('where'=>array('ip'=>$data['ip'])));
            if(!empty($res)){
                $this->load->library('shell');
                if(!Shell::checkPing($data['ip'])){
                    $this->error(lang('host_ping_ip_error'));
                }
            }else{
                $res['id'] != $id and $this->error(lang('host_ip_already_exists'));
            }
            $data['updateTime']= TIMESTAMP;
            $this->host_model->update('host',$data,array('id'=>$id));
            $this->success(lang('host_edit_success').',hostid = '.$id);
        }
        $detail = $this->_getHostInfo($id);
        $this->_G['tplVars']['recordDetail'] = $detail;
        $this->_G['tplVars']['idcs'] = self::getIdcs();
        $this->load->view('host/host',$this->_G);
    }
    //下线主机，将主机status置0，部署时要判断主机的状态
    function offline($id=0){
        ($id = intval($id)) or $this->error(lang('host_id_error'));
        $res = $this->host_model->update('host',array('status'=>0,'updateTime'=>TIMESTAMP),array('id'=>$id));
        $res ? $this->success(lang('host_offline_success')) : $this->error(lang('host_offline_error'));
    }
    // 获取主机的机房列表
    private static function getIdcs(){
        return array(
            'cu',
            'cu22',
            'ct',
            'ct22',
            'cm',
            'cm22',
            'local',
            'local22',
            '本地虚拟机',
            '本地主机',
            '阿里云机器',
        );
    }
    //获取某个主机相关信息
    private function _getHostInfo(&$id,$getBindProjects=true){
        ($id = intval($id)) or $this->error(lang('host_id_error'));
        $detail = $this->host_model->getOne('host',array('where'=>array('id'=>$id)));
        if(empty($detail)){
            $this->error('没有该主机信息');
        }
        $detail['createTime'] = date('Y-m-d', $detail['createTime']);
        //获取主机记录详细信息，并获取绑定在该主机的项目信息
        $getBindProjects and $detail['bindProjectArr'] = $this->host_model->getBindProjects($id);
        return $detail;
    }
    //检验提交上来的主机数据
    private function _verifyInputHostInfo(&$id=null){
        $data = array();
        if($id !== null){
            ($id = intval($id)) or $this->error(lang('host_id_error'));
            $data['id'] = $id;
        }
        foreach(array(
            'hostname',
            'idc',
            'ip',
            'status',
            'predeploy',
                ) as $val){
            $data[$val] = $this->input->post($val);
            if(strlen($data[$val]) == 0){
                $this->error(lang($val).lang('field_empty'));
            }
        }

        if (!in_array($data['idc'],$this->getIdcs())) {
            $this->error(lang('host_idc_error'));
        }
        $this->load->library('verify');
        if (!verify::pregIP($data['ip'])) {
            $this->error(lang('host_ip_error'));
        }
        if ($data['status'] != '0' && $data['status'] != '1') {
            $this->error(lang('host_status_error'));
        }
        if ($data['predeploy'] != '0' && $data['predeploy'] != '1') {
            $this->error(lang('host_predeploy_error'));
        }
        return $data;
    }
}