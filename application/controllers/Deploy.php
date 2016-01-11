<?php
/**
 * Created by PhpStorm.
 * @author wuxin
 * @date 2015 15/12/18 下午11:15
 * @copyright 7659.com
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Deploy extends MY_Controller{

    const
        PRE_DEPLOY_TYPE = 0,
        PRO_DEPLOY_TYPE = 1,
        PRE_DIR = 'pre_dir',    //预发布目录
        PRO_DIR= 'pro_dir';     //生产目录

    function __construct(){
        parent::__construct();
        $this->load->model('deploy_model');
        $this->load->library('shell');
    }

    // 初始化项目
    function init($id=0){
        $projectInfo = self::_getProjectInfo($id,false);
        $deployPath = $projectInfo['deployPath'];
        $svnUrl = $projectInfo['svnUrl'];
        $preInitDir = $deployPath.DIRECTORY_SEPARATOR.self::PRE_DIR;
        $proInitDir = $deployPath.DIRECTORY_SEPARATOR.self::PRO_DIR;
        if (!is_dir($preInitDir)) {
            // 创建预发布目录
            if (!@mkdir($preInitDir, DIR_WRITE_MODE, True)) {
                $this->error(str_replace('{$deployPath}',$preInitDir,lang('deploy_mkdir_deploypath_error')));
            }
        }
        if (!is_dir($proInitDir)) {
            // 创建正式发布目录
            if (!@mkdir($proInitDir, DIR_WRITE_MODE, True)) {
                $this->error(str_replace('{$deployPath}',$proInitDir,lang('deploy_mkdir_deploypath_error')));
            }
        }

        if (!is_dir($preInitDir . DIRECTORY_SEPARATOR . '.svn')) {
            // 检出SVN代码到预发布目录
            $res = Shell::svnCheckOut($svnUrl,$preInitDir);
            if($res === false){
                $this->error(str_replace('{$svnUrl}',$svnUrl,lang('deploy_svn_checkout_error')));
            }
        }
        if (!is_dir($proInitDir . DIRECTORY_SEPARATOR . '.svn')) {
            // 检出SVN代码到正式发布目录
            $res = Shell::svnCheckOut($svnUrl,$proInitDir);
            if($res === false){
                $this->error(str_replace('{$svnUrl}',$svnUrl,lang('deploy_svn_checkout_error')));
            }
        }
        $this->success(lang('deploy_init_success'));
    }
    function preDiff($id=0){
        $this->_diff($id,self::PRE_DEPLOY_TYPE);
    }

    function proDiff($id=0){
        $this->_diff($id,self::PRO_DEPLOY_TYPE);
    }

    function preConfirm($id=0){
        $this->_confirm($id,self::PRE_DEPLOY_TYPE);
    }

    function proConfirm($id=0){
        $this->_confirm($id,self::PRO_DEPLOY_TYPE);
    }

    function preCommit($id=0){
        $this->_commit($id,self::PRE_DEPLOY_TYPE);
    }

    function proCommit($id=0){
        $this->_commit($id,self::PRO_DEPLOY_TYPE);
    }

    function preRollback($id=0){
        $this->_rollBack($id,self::PRE_DEPLOY_TYPE);
    }

    function proRollback($id=0){
        $this->_rollBack($id,self::PRO_DEPLOY_TYPE);
    }

    //对项目进行diff
    private function _diff($id=0, $deployType){
        $projectInfo = self::_getProjectInfo($id);
        // 是否强制释放别人的锁
        $this->_deployCheck($projectInfo,(bool)$this->input->get('clearlock'),$deployType);

        $deployPath  = $projectInfo['deployPath'] . DIRECTORY_SEPARATOR . ($deployType ? self::PRO_DIR : self::PRE_DIR);

        //检查发布机器
        if($deployType){
            if(empty($projectInfo['bindHosts']['deployHosts'])) {
                $this->error('没有绑定正式发布机器');
            }
        }else{
            if(empty($projectInfo['bindHosts']['predeployHosts'])) {
                $this->error('没有绑定预发布机器');
            }
        }

        $toRevision = ($_revision = trim($this->input->get('revision')))
            ? (is_numeric($_revision) ? intval($_revision) : $_revision)
            : 'HEAD';
        // 获取部署机上工作版本SVN的信息
        $baseSvnInfo = Shell::getSvnInfo($deployPath,'BASE');
        if (!is_array($baseSvnInfo) || !isset($baseSvnInfo['revision'])) {
            $this->error($baseSvnInfo);
        }

        $prevRevision = $baseSvnInfo['revision'];
        $this->setPreviousRevision($id, $prevRevision, $deployType);

        //获取部署机上最新版本的SVN信息
        $headSvnInfo = Shell::getSvnInfo($deployPath,$toRevision);
        if (!is_array($headSvnInfo) || !isset($headSvnInfo['revision'])) {
            $this->error($headSvnInfo);
        }
        $toRevision = $headSvnInfo['revision'];
        $this->setCurrentRevision($id, $toRevision, $deployType);
        //获取diff结果
        $diffInfo = array();
        if ($baseSvnInfo['revision'] != $headSvnInfo['revision']) {
            $diffInfo = Shell::getSvnDiffInfo($deployPath, $prevRevision, $toRevision);
        }

        // 获取部署流程锁
        $res = $this->_getDeploytLock($id, $prevRevision, $toRevision, $deployType);
        if(is_string($res)){
            $this->error($res);
        }

        $tplVars['baseSvnInfo'] = $baseSvnInfo;
        $tplVars['headSvnInfo'] = $headSvnInfo;
        $tplVars['diffInfo'] = $diffInfo;

        $tplVars['projectInfo'] = $projectInfo;
        $tplVars['deployType'] = $deployType;

        $tplVars['template'] = 'diff';
        $this->_G['tplVars'] = $tplVars;
        $this->load->view('deploy/deploy',$this->_G);
    }
    //将代码发送到线测机或线上（生产）机前，进行要发送的主机列表确认
    private function _confirm($id=0, $deployType) {
        $projectInfo = self::_getProjectInfo($id);
        $this->_deployCheck($projectInfo, true, $deployType);

        // 判断发布机器的绑定信息
        if(empty($projectInfo['bindHosts'])){
            $this->error(lang('deploy_bind_host_no'));
        }
        //检查发布机器
        $tplVars['deployHosts'] = $deployType ? $projectInfo['bindHosts']['deployHosts'] : $projectInfo['bindHosts']['predeployHosts'];
        if(empty($tplVars['deployHosts'])){
            $this->error(lang('deploy_bind_host_no'));
        }

        //将传来的参数原样传回去 赋值页面变量
        ($referer = $this->input->get('referer')) or $referer = 'home';
        ($delete = $this->input->get('delete')) or $delete = 'false';

        $tplVars['projectId'] = $id;
        $tplVars['referer'] = $referer;
        $tplVars['delete'] = $delete;
        $tplVars['deployType'] = $deployType;
        $tplVars['template'] = 'confirm';
        $this->_G['tplVars'] = $tplVars;
        $this->load->view('deploy/deploy',$this->_G);
    }

    private function _commit($id=0, $deployType){
        $projectInfo = self::_getProjectInfo($id);
        $this->_deployCheck($projectInfo, true, $deployType);

        $deployPath  = $projectInfo['deployPath'] . DIRECTORY_SEPARATOR . ($deployType ? self::PRO_DIR : self::PRE_DIR);

        ($referer = $this->input->get('referer')) or $referer = 'home';
        ($delete = $this->input->get('delete')) or $delete = 'false';

        //检查发布机器
        $deployHosts = $deployType ? $projectInfo['bindHosts']['deployHosts'] : $projectInfo['bindHosts']['predeployHosts'];
        if(empty($deployHosts)){
            $this->error(lang('deploy_bind_host_no'));
        }

        $prevRevision = $this->getPreviousRevision($id, $deployType);
        $toRevision = $this->getCurrentRevision($id, $deployType);

        if ($toRevision === false || $prevRevision === false) {
            $this->error(lang('deploy_unlawful'));
        }
        // 获取部署锁,防止冲突
        if (!$this->deploy_model->checkDeployLock($id, $this->_G['userInfo']['id'], $deployType)) {
            $this->error(lang('deploy_lock_error'));
        }

        $updateInfo = Shell::svnUpdate($deployPath, $toRevision);
        $tplVars['doUpdate'] = true;
        $tplVars['updateInfo'] = $updateInfo;
        // Rsync同步到生产机
        $failNum = 0;
        $successNum = 0;

        //更新对应的部署代码路径
        $projectInfo['deployPath'] =  $deployPath;
        $pushInfo = Shell::pushCodeToHosts($projectInfo, $deployHosts, $delete);

        $tplVars['pushInfo'] = $pushInfo;
        if (isset($pushInfo['rsync']) && sizeof($pushInfo['rsync'])>0) {
            $successNum = sizeof($pushInfo['rsync']);
        }

        // 写入部署日志,方便日后回滚
        $logId = $this->deploy_model->addDeployProjectLog($id,$this->_G['userInfo']['id'], $prevRevision, $toRevision, $deployType);
        if ($logId > 0){
            foreach ($pushInfo['rsync'] as $k => $rsyncLog) {
                $hostIp = $deployHosts[$k]['ip'];
                $status = 0;
                if (strpos($rsyncLog,'building file list ... done') !== false && strpos($rsyncLog, 'total size is') !== false) {
                    $status = 1;
                } else {
                    $failNum++;
                    $successNum--;
                }
                $this->deploy_model->addDeployHostLog($logId, $hostIp, $rsyncLog, $status);
            }
        }
        $tplVars['failNum'] = $failNum;
        $tplVars['successNum'] = $successNum;
        // 释放锁
        $this->deploy_model->releaseDeployLock($id, $this->_G['userInfo']['id'], true, $deployType);


        $tplVars['projectInfo'] = $projectInfo;
        $tplVars['deployHosts'] = $deployHosts;

        $tplVars['prevRevision'] = $prevRevision;
        $tplVars['toRevision'] = $toRevision;
        $tplVars['projectId'] = $id;

        $tplVars['template'] = 'commit';
        $this->_G['tplVars'] = $tplVars;
        $this->load->view('deploy/deploy',$this->_G);
    }
    // 进入代码回滚流程
    private function _rollBack($id=0, $deployType){
        // 获取项目信息和绑定的主机信息
        $projectInfo = self::_getProjectInfo($id);
        //该项目的部署权限
        $this->_deployCheck($projectInfo, True, $deployType);

        $lastDeploys = $this->deploy_model->getLastDeploys($id, $deployType, 20);

        // $toRevision = isset($_REQUEST['revision']) ? intval($_REQUEST['revision']) : 'PREV';

        $tplVars['projectInfo'] = $projectInfo;
        $tplVars['lastDeploys']= $lastDeploys;
        $tplVars['deployType'] = $deployType;
        $tplVars['template'] = 'rollback';
        $this->_G['tplVars'] = $tplVars;
        $this->load->view('deploy/deploy',$this->_G);
    }

    // 获取当前用户正在部署的版本
    private function getPreviousRevision($projectId, $deployType){
        $cookieName = 'previous_'.$deployType.':'.$projectId;
        if (isset($_COOKIE[$cookieName]) && is_numeric($_COOKIE[$cookieName])) {
            return $_COOKIE[$cookieName];
        }
        return false;
    }
    // 获取当前用户正在部署的版本
    private function getCurrentRevision($projectId, $deployType){
        $cookieName = 'revision_'.$deployType.':'.$projectId;
        if (isset($_COOKIE[$cookieName]) && is_numeric($_COOKIE[$cookieName])) {
            return $_COOKIE[$cookieName];
        }
        return false;
    }
    // 设置部署前的版本
    private function setPreviousRevision($projectId, $revision, $deployType){
        $cookieName = 'previous_'.$deployType.':'.$projectId;
        if (is_numeric($projectId) && is_numeric($revision)) {
            setcookie($cookieName, $revision, time()+3600*24*365,'/deploy/');
        }
    }// 设置当前用户正在部署的版本
    private function setCurrentRevision($projectId, $revision, $deployType){
        $cookieName = 'revision_'.$deployType.':'.$projectId;
        if (is_numeric($projectId) && is_numeric($revision)) {
            setcookie($cookieName, $revision, time()+3600*24*365,'/deploy/');
        }
    }

    // 对某个部署项目进行加锁,防止多人部署
    private function _getDeploytLock($projectId, $oldRevision, $newRevision, $deployType){
        $userInfo = $this->_G['userInfo'];
        $sessions = $this->deploy_model->getList('deploy_session',array('where'=>'projectId=' . $projectId . ' AND userId !=' . $userInfo['id'] .' AND deployType='.$deployType));
        if (sizeof($sessions)>0) {
            $this->error(
                lang('deploy_project_user_already_exists'),
                0,
                'deploy/session',
                array('sessions'=>$sessions,'projectId'=>$projectId)
            );
        } else {
            return $this->deploy_model->insertKeyUp(
                'deploy_session',
                array(
                    'projectId' => $projectId,
                    'userId' => $userInfo['id'],
                    'username' => $userInfo['username'],
                    'oldRevision' => $oldRevision,
                    'newRevision' => $newRevision,
                    'deployType' => $deployType,
                    'createTime' => TIMESTAMP,
                ),
                array(
                    'projectId',
                    'userId',
                    'username',
                    'oldRevision',
                    'newRevision',
                    'deployType',
                    'createTime',
                )
            );
        }
    }

    //部署前检查
    private function _deployCheck($projectInfo, $clearlock=true, $deployType=self::PRE_DEPLOY_TYPE){
        $deployPath = $projectInfo['deployPath'].DIRECTORY_SEPARATOR.($deployType ? self::PRO_DIR : self::PRE_DIR);
        if (!is_dir($deployPath)) {
            $this->error(str_replace('{$deployPath}',$deployPath,lang('deploy_init_no')));
        }
        if (!is_dir($deployPath . DIRECTORY_SEPARATOR . '.svn')) {
            $this->error(str_replace('{$svnUrl}',$projectInfo['svnUrl'],lang('deploy_svn_init_no')));
        }
        // 强制清理其他部署会话
        // 强制释放本人持有的锁或别人的锁
        $clearlock and $this->deploy_model->releaseDeployLock($projectInfo['id'], $this->_G['userInfo']['id'], false, $deployType);

    }

    //获取项目的详细信息
    private function _getProjectInfo(&$id,$getBindHosts=true){
        ($id = intval($id)) or $this->error(lang('project_id_error'));
        if (!$this->deploy_model->checkProjectUserBind($id,$this->_G['userInfo']['id'])) {
            $this->error(lang('deploy_auth_no'));
        }
        $this->load->model('project_model');
        $detail = $this->project_model->getOne('project',array('where'=>array('id'=>$id)));
        if(empty($detail)){
            $this->error(lang('project_info_no'));
        }
        if($getBindHosts and $detail['bindHosts'] = $this->project_model->getBindHosts($id)){
            $detail['bindHosts'] = array_reduce(
                $detail['bindHosts'],
                function($a,$b){
                    $key = $b['predeploy'] == 1 ? 'predeployHosts' : 'deployHosts';
                    $a[$key][] = $b;
                    return $a;
                },
                array('predeployHosts'=>array(),'deployHosts'=>array())
            );
        }
        return $detail;
    }
}