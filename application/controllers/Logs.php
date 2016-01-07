<?php
/**
 * Created by PhpStorm.
 * @author wuxin
 * @date 2015 15/12/23 上午10:27
 * @copyright 7659.com
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Logs extends MY_Controller{

    function __construct(){
        parent::__construct();
        $this->load->model('logs_model');
    }

    //根据查询条件，分页取部署日志
    function index(){
        ($page = intval($this->input->get('page'))) or $page = 1;
        //处理用户id，项目id，开始日期，结束日志等查询条件
        $userId = intval($this->input->get('userId'));
        $projectId = intval($this->input->get('projectId'));
        //获取开始日期参数
        (($startDate = trim($this->input->get('startDate'))) and ($startDate = strtotime($startDate))) or $startDate = 0;
        //获取结束日期参数
        (($endDate = trim($this->input->get('endDate'))) and ($endDate = strtotime($endDate))) or $endDate = 0;
        if ($startDate == 0 && $endDate == 0) {
            $startDate = TIMESTAMP - 60*60*24*7;
            $endDate = TIMESTAMP;
        } elseif($startDate == 0 && $endDate != 0) {
            $startDate = $endDate - 60*60*24*7;
        } elseif ($startDate != 0 && $endDate == 0) {
            $endDate = TIMESTAMP;
        }
        if ($startDate > $endDate) {
            $this->error('你输入的开始日期大于结束日期或当前时间');
        }

        //拼查询条件SQL
        $where = ' 1 ';
        $where .= $userId > 1 ? ' and userId = '.$userId : '';
        $where .= $projectId > 1 ? ' and projectId = '.$projectId : '';
        $where .= ' and deployTime BETWEEN ' .$startDate. ' AND ' .$endDate. ' ';

        $count = $this->logs_model->getTotal('deploy_log_project',$where);
        $offset = ($page - 1) * self::$pageSize;
        $tplVars = array('list'=>array(),'count'=>$count,'totalPage'=>ceil($count/self::$pageSize));
        if($count>0 && $count>$offset){
                //获取当前请求页数据
                $tplVars['list'] = $this->logs_model->getLogsList($where, $offset, self::$pageSize);
        }

        //查询条件保持
        $tplVars['queryArr'] = array(
            //'page' => $page,
            'userId' => $userId,
            'projectId' => $projectId,
            'startDate' => $startDate == 0 ? '' : date('Y-m-d',$startDate),
            'endDate' => $endDate == 0 ? '' : date('Y-m-d',$endDate)
        );

        $this->load->helper('page_helper');
        $tplVars['pageBar'] = getPageBar($count,$page,self::$pageSize);
        //返回项目和用户下拉框数据
        $tplVars['projectIdNames'] = $this->logs_model->getList('project',array('select'=>'id,name'));
        $tplVars['userIdNames'] = $this->logs_model->getList('user',array('select'=>'id,username'));

        $this->_G['tplVars'] = $tplVars;
        $this->load->view('logs/index',$this->_G);
    }
    //根据日志id查看部署日志详情及部署的主机列表
    function show($id=0){
        ($id = intval($id)) or $this->error('日志id错误');
        //获取日志详情
        $tplVars['deployLogInfo'] = $this->logs_model->getLogInfo($id);
        if(empty($tplVars['deployLogInfo'])){
            $this->error('没有该log信息');
        }
        //获取该项目部署日志对应的多个主机部署状态
        $tplVars['deployHosts'] = $this->logs_model->getList(
            'deploy_log_host',
            array(
                'select' => 'id, logId, hostIp, status',
                'where' => array(
                    'logId' => $id
                )
            )
        );

        $this->_G['tplVars'] = $tplVars;
        $this->load->view('logs/show',$this->_G);
    }
    //查看rsync日志
    function rsyncLog($id){
        ($id = intval($id)) or $this->error('日志id错误');
        //获取日志详情
        $tplVars['logInfo'] = $this->logs_model->getOne('deploy_log_host',array('where'=>array('id'=>$id)));
        if(empty($tplVars['logInfo'])){
            $this->error('没有该log信息');
        }
        $this->_G['tplVars'] = $tplVars;
        $this->load->view('logs/rsyncLog',$this->_G);
    }
}