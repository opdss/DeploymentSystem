<?php
/**
 * Created by PhpStorm.
 * @author wuxin
 * @date 2015 15/12/2 下午7:19
 * @copyright 7659.com
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Monitor extends MY_Controller{

    function __construct(){
        parent::__construct();
        $this->load->model('monitor_model');
    }
    //项目监控主页面，显示有权限的项目监控列表
    function index(){
        $this->_G['tplVars']['recordsArray'] = $this->monitor_model->getMonitorList($this->_G['userInfo']['id']);
        $this->load->view('monitor/monitor',$this->_G);
    }

    function addItem($act=''){
        if($act == 'save'){
            $data = $this->_verifyInputmonitorItemInfo();
            $data['createTime'] = TIMESTAMP;
            $res = $this->monitor_model->insertKeyUp('app_monitor_item',$data);
            if(!$res){
                $this->error('插入数据库出错:app_monitor_item');
            }
            /*  redis 处理
            $redisLink = new Redis();
            $redisResult = '';
            if (!$redisLink->connect(__REDIS_IP__, __REDIS_PORT__)) {
                $redisResult .= 'Redis 连接失败';
            } else {
               //redis中的更新
            }
            */
            //新增user_app_monitor
            $res = $this->monitor_model->insertKeyUp(
                'user_app_monitor',
                array(
                    'userId' => $this->_G['userInfo']['id'],
                    'appMonitorId' => $res
                )
            );
            if($res){
                $this->success('增加成功');
            }else{
                $this->error('插入数据库出错:user_app_monitor');
            }
        }
        $this->load->view('monitor/monitor',$this->_G);
    }

    function editItem($id=0,$act=''){
        if($act == 'save'){
            $data = $this->_verifyInputMonitorItemInfo($id);
            $res = $this->monitor_model->update('app_monitor_item',$data,array('id'=>$id));
            $res ? $this->success('项目修改成功') : $this->error('项目修改失败');
        }
        ($id = intval($id)) or $this->error('item ID 错误');
        $this->_G['tplVars']['appMonitorInfo'] = $this->monitor_model->getOne('app_monitor_item',array('where'=>array('id'=>$id)));
        if(empty($this->_G['tplVars']['appMonitorInfo'])){
            $this->error('没有该项目记录');
        }
        $this->load->view('monitor/monitor',$this->_G);
    }

    function delItem($id=0){
        ($id = intval($id)) or $this->error('item ID 错误');
        //删除app_monitor_item
        $this->monitor_model->delete('app_monitor_item',array('id'=>$id));
        //删除user_app_monitor
        $this->monitor_model->delete('user_app_monitor',array('appMonitorId'=>$id));
        //删除app_monitor_target
        $this->monitor_model->delete('app_monitor_target',array('appMonitorItem_id'=>$id));
        $this->error('删除成功');
    }

    function manageTarget($itemId=0){
        ($itemId = intval($itemId)) or $this->error('item ID 错误');
        $tplVars['urlResult'] = $this->monitor_model->getList('app_monitor_target',array('where'=>array('appMonitorItemId'=>$itemId)));
        $tplVars['id'] = $itemId;
        $this->_G['tplVars'] = $tplVars;
        $this->load->view('monitor/monitor',$this->_G);
    }

    function addTarget($itemId=0, $act=''){
        if($act == 'save'){
            //新增app_monitor
            $data = $this->_verifyInputMonitorTargetInfo($itemId);
            $res = $this->monitor_model->insertKeyUp('app_monitor_target', $data);
            if($res){
                $this->success('增加成功');
            }else{
                $this->error('插入数据库出错');
            }
        }
        ($itemId = intval($itemId)) or $this->error('item ID 错误');
        $this->_G['tplVars']['appMonitorItemId'] = $itemId;
        $this->load->view('monitor/monitor',$this->_G);
    }

    function editTarget($itemId=0, $targetId=0, $act=''){
        if($act == 'save'){
            $data = $this->_verifyInputMonitorTargetInfo($itemId,$targetId);
            $res = $this->monitor_model->update('app_monitor_target',$data,array('id'=>$targetId));
            $res ? $this->success('项目修改成功') : $this->error('项目修改失败');
        }
        ($targetId = intval($targetId)) or $this->error('target ID 错误');
        $this->_G['tplVars']['appTargetInfo'] = $this->monitor_model->getOne('app_monitor_target',array('where'=>array('appMonitorItemId'=>$itemId,'id'=>$targetId)));
        if(empty($this->_G['tplVars']['appTargetInfo'])){
            $this->error('没有该项目记录');
        }
        $this->load->view('monitor/monitor',$this->_G);
    }

    function delTarget($itemId=0, $targetId=0){
        //删除app_monitor_target
        ($itemId = intval($itemId)) or $this->error('item ID 错误');
        ($targetId = intval($targetId)) or $this->error('target ID 错误');
        $this->monitor_model->delete('app_monitor_target',array('id'=>$targetId));
        $this->success('删除成功');
    }

    //检验提交上来的数据
    private function _verifyInputMonitorItemInfo(&$id=null){
        $data = array();
        if($id !== null){
            ($id = intval($id)) or $this->error('item ID 错误');
            $data['id'] = $id;
        }
        $data['name'] = trim($this->input->post('name')) or $this->error('名字不能为空');
        $data['checkinterval'] = intval($this->input->post('checkinterval')) or $this->error('时间不能为空');
        $data['maillist'] = trim($this->input->post('maillist'),',');
        $this->load->library('verify');
        array_map(
            function($email){
                if(!Verify::pregEmail($email)){
                    $this->error('邮箱:'.$email.'错误');
                }
            },
            explode(',',$data['maillist'])
        );
        return $data;
    }
    //检验提交上来的数据
    private function _verifyInputMonitorTargetInfo(&$itemId=0,&$targetId=null){
        $data = array();
        ($itemId = intval($itemId)) or $this->error('item ID 错误');
        $data['appMonitorItemId'] = $itemId;
        if($targetId !== null){
            ($targetId = intval($targetId)) or $this->error('target ID 错误');
            $data['id'] = $targetId;
        }
        $data['name'] = trim($this->input->post('name')) or $this->error('名字不能为空');
        $data['target'] = trim($this->input->post('target')) or $this->error('监控地址不能为空');
        return $data;
    }
}