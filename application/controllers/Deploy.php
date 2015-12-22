<?php
/**
 * Created by PhpStorm.
 * @author wuxin
 * @date 2015 15/12/18 下午11:15
 * @copyright 7659.com
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Deploy extends MY_Controller{

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
        if (!is_dir($deployPath)) {
            // 创建发布目录
            if (!@mkdir($deployPath, DIR_WRITE_MODE, True)) {
                $this->error('创建发布目录"' . $deployPath . '"失败,请检查权限。');
            }
        }
        if (!is_dir($deployPath . DIRECTORY_SEPARATOR . '.svn')) {
            // 检出SVN代码到发布目录
            $res = Shell::svnCheckOut($svnUrl,$deployPath);
            if($res === false){
                $this->error('检出上线SVN"' . $svnUrl . '"失败,请检查权限。');
            }
        }
        $this->success('项目初始化成功');
    }
    //对项目进行diff
    function diff($id=0){
        $projectInfo = self::_getProjectInfo($id);
        // 是否强制释放别人的锁
        $this->_deployCheck($projectInfo,(bool)$this->input->get('clearlock'));

        $deployPath  = $projectInfo['deployPath'];

        // 判断预发布机和发布机的绑定信息
        $hasPredeploy = count($projectInfo['bindHosts']['predeployHosts']) > 0 ? true : false;
        $hasDeploy    = count($projectInfo['bindHosts']['deployHosts']) > 0 ? true : false;


        $toRevision = ($_revision = trim($this->input->get('revision')))
            ? (is_numeric($_revision) ? intval($_revision) : $_revision)
            : 'HEAD';
        // 获取部署机上工作版本SVN的信息
        $baseSvnInfo = Shell::getSvnInfo($deployPath,'BASE');
        if (!is_array($baseSvnInfo) || !isset($baseSvnInfo['revision'])) {
            $this->error($baseSvnInfo);
        }

        $prevRevision = $baseSvnInfo['revision'];
        $this->setPreviousRevision($id, $prevRevision);

        //获取部署机上最新版本的SVN信息
        $headSvnInfo = Shell::getSvnInfo($deployPath,$toRevision);
        if (!is_array($headSvnInfo) || !isset($headSvnInfo['revision'])) {
            $this->error($headSvnInfo);
        }
        $toRevision = $headSvnInfo['revision'];
        $this->setCurrentRevision($id, $toRevision);
        //获取diff结果
        $diffInfo = array();
        if ($baseSvnInfo['revision'] != $headSvnInfo['revision']) {
            $diffInfo = Shell::getSvnDiffInfo($deployPath, $prevRevision, $toRevision);
        }

        // 获取部署流程锁
        $res = $this->_getDeploytLock($id, $prevRevision, $toRevision);
        if(is_string($res)){
            $this->error($res);
        }

        $tplVars['baseSvnInfo'] = $baseSvnInfo;
        $tplVars['headSvnInfo'] = $headSvnInfo;
        $tplVars['diffInfo'] = $diffInfo;

        $tplVars['projectInfo'] = $projectInfo;
        $tplVars['hasPredeploy'] = $hasPredeploy;
        $tplVars['hasDeploy'] = $hasDeploy;
        $this->_G['tplVars'] = $tplVars;
        $this->load->view('deploy/deploy',$this->_G);
    }

    function commit($id=0){
        $projectInfo = self::_getProjectInfo($id);
        $this->_deployCheck($projectInfo);

        //$predeployHosts = $projectInfo['bindHosts']['predeployHosts'];
        //$deployHosts = $projectInfo['bindHosts']['deployHosts'];

        ($referer = $this->input->get('referer')) or $referer = 'home';
        ($currentStep = $this->input->get('step')) or $currentStep = 'predeploy';
        ($delete = $this->input->get('delete')) or $delete = 'false';
        $deployHosts = $currentStep == 'deploy' ? $projectInfo['bindHosts']['deployHosts'] : $projectInfo['bindHosts']['predeployHosts'];

        $prevRevision = $this->getPreviousRevision($id);
        $toRevision = $this->getCurrentRevision($id);

        // 判断预发布机和发布机的绑定信息
        //$hasPredeploy = count($predeployHosts) > 0 ? True : False;
        //$hasDeploy    = count($deployHosts) > 0 ? True : false;

        if ($deployHosts == false) {
            $this->error('该项目尚未绑定任何发布机器');
        }
        if ($toRevision === false || $prevRevision === false) {
            $this->error('非法的上下文环境,版本号信息缺失');
        }
        // 获取部署锁,防止冲突
        if (!$this->deploy_model->checkDeployLock($id,$this->_G['userInfo']['id'])) {
            $this->error('您尚未持有部署锁或者已经失效');
        }

        $updateInfo = Shell::svnUpdate($projectInfo['deployPath'], $toRevision);
        $tplVars['doUpdate'] = true;
        $tplVars['updateInfo'] = $updateInfo;
        // Rsync同步到生产机
        $failNum = 0;
        $successNum = 0;
        $pushInfo = Shell::pushCodeToHosts($projectInfo, $deployHosts, $delete);

        $tplVars['pushInfo'] = $pushInfo;
        if (isset($pushInfo['rsync']) && sizeof($pushInfo['rsync'])>0) {
            $successNum = sizeof($pushInfo['rsync']);
        }

        // 写入部署日志,方便日后回滚
        $logId = $this->deploy_model->addDeployProjectLog($id,$this->_G['userInfo']['id'], $prevRevision, $toRevision);
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
        $this->deploy_model->releaseDeployLock($id,$this->_G['userInfo']['id'],true);


        $tplVars['projectInfo'] = $projectInfo;
        $tplVars['predeployHosts'] = $deployHosts;
        $tplVars['deployHosts'] = $deployHosts;
        $tplVars['hasPredeploy'] = $currentStep == 'predeploy';
        $tplVars['hasDeploy'] = $currentStep == 'predeploy';
        $tplVars['prevRevision'] = $prevRevision;
        $tplVars['toRevision'] = $toRevision;
        $tplVars['projectId'] = $id;
        $this->_G['tplVars'] = $tplVars;
        $this->load->view('deploy/deploy',$this->_G);
    }
    /*
    // 进入代码发布流程
    function index($id=0){
        ($id = intval($id)) or $this->error(lang('project_id_error'));
        $projectInfo = $this->deploy_model->getOne('project',array('where'=>array('id'=>$id)));
        if(empty($projectInfo)){
            $this->error('没有该项目相关信息');
        }
        if (!$this->deploy_model->checkProjectUserBind($id,$this->_G['userInfo']['id'])) {
            $this->error('你没有该项目的部署权限');
        }
        ($referer = $this->input->get('referer')) or $referer = 'home';
        ($currentStep = $this->input->get('step')) or $currentStep = 'diff';
        ($delete = $this->input->get('delete')) or $delete = 'false';

        $deployPath  = $projectInfo['deployPath'];
        $svnUrl = $projectInfo['svnUrl'];
        if (!is_dir($deployPath)) {
            $this->error('项目尚未初始化,发布目录"' . $deployPath . '"不存在');
        }
        if (!is_dir($deployPath . DIRECTORY_SEPARATOR . '.svn')) {
            renderError('项目尚未初始化,代码仓库"' . $svnUrl . '"未检出到发布目录');
        }

        $predeployHosts = $this->deploy_model->getPredeployHosts($id);
        $deployHosts = $this->deploy_model->getDeployHosts($id);
        $prevRevision = $this->getPreviousRevision($id);
        $toRevision = $this->getCurrentRevision($id);

        // 判断预发布机和发布机的绑定信息
        $hasPredeploy = count($predeployHosts) > 0 ? True : False;
        $hasDeploy    = count($deployHosts) > 0 ? True : false;

        if($currentStep == 'diff'){
            $tplVars['template'] = 'diff';
            // 强制清理其他部署会话       // 强制释放本人持有的锁或别人的锁
            $this->input->get('clearlock') and $this->deploy_model->releaseDeployLock($id,$this->_G['userInfo']['id'],false);

            $toRevision = ($_revision = trim($this->input->get('revision')))
                    ? (is_numeric($_revision) ? intval($_revision) : $_revision)
                    : 'HEAD';

            // 获取部署机上工作版本SVN的信息
            $baseSvnInfo = Shell::getSvnInfo($deployPath,'BASE');
            if (!is_array($baseSvnInfo) || !isset($baseSvnInfo['revision'])) {
                $this->error($baseSvnInfo);
            }

            $prevRevision = $baseSvnInfo['revision'];
            $this->setPreviousRevision($id, $prevRevision);

            //获取部署机上最新版本的SVN信息
            $headSvnInfo = Shell::getSvnInfo($deployPath,$toRevision);
            if (!is_array($headSvnInfo) || !isset($headSvnInfo['revision'])) {
                $this->error($headSvnInfo);
            }
            $toRevision = $headSvnInfo['revision'];
            $this->setCurrentRevision($id, $toRevision);

            $diffInfo = array();
            if ($baseSvnInfo['revision'] != $headSvnInfo['revision']) {
                $diffInfo = Shell::getSvnDiffInfo($deployPath, $prevRevision, $toRevision);
            }

            // 获取部署流程锁
            $res = $this->_getDeploytLock($id, $prevRevision, $toRevision);
            if(is_string($res)){
                $this->error($res);
            }

            $tplVars['baseSvnInfo'] = $baseSvnInfo;
            $tplVars['headSvnInfo'] = $headSvnInfo;
            $tplVars['diffInfo'] = $diffInfo;
        }
        elseif($currentStep == 'predeploy'){
            if ($hasPredeploy == false) {
                $this->error('该项目尚未绑定任何预发布机');
            }
            if ($toRevision === false || $prevRevision === false) {
                $this->error('非法的上下文环境,版本号信息缺失');
            }
            // 获取部署锁,防止冲突
            if (!$this->deploy_model->checkDeployLock($id,$this->_G['userInfo']['id'])) {
                $this->error('您尚未持有部署锁或者已经失效');
            }
            // 更新工作拷贝到Diff的版本
            $updateInfo = Shell::svnUpdate($deployPath, $toRevision);

            // Rsync同步到预发布机
            $pushInfo = Shell::pushCodeToHosts($projectInfo, $predeployHosts, $delete);
            $tplVars['doUpdate'] = True;
            $tplVars['updateInfo'] = $updateInfo;
            $tplVars['pushInfo'] = $pushInfo;

            // 释放锁
            if ($hasDeploy == false) {
                $this->deploy_model->releaseDeployLock($id,$this->_G['userInfo']['id'],true);
            }
        }
        elseif($currentStep == 'deploy'){
            if ($hasDeploy == false) {
                $this->error('该项目尚未绑定任何生产机');
            }
            if ($toRevision === false || $prevRevision === false) {
                $this->error('非法的上下文环境,版本号信息缺失');
            }
            // 获取部署锁,防止冲突
            if (!$this->deploy_model->checkDeployLock($id,$this->_G['userInfo']['id'])) {
                $this->error('您尚未持有部署锁或者已经失效');
            }
            // 判定是否已经更新过目标版本
            if ($referer == 'diff') {
                $updateInfo = Shell::svnUpdate($deployPath, $toRevision);
                $tplVars['doUpdate'] = true;
                $tplVars['updateInfo'] = $updateInfo;
            } else {
                $tplVars['doUpdate'] = false;
            }
            // Rsync同步到生产机
            $failNum = 0;
            $successNum = 0;
            $pushInfo = Shell::pushCodeToHosts($projectInfo, $deployHosts, $delete);
            $tplVars['pushInfo'] = $pushInfo;
            if (isset($pushInfo['rsync']) && sizeof($pushInfo['rsync'])>0) {
                $successNum = sizeof($pushInfo['rsync']);
            }
            // 写入部署日志,方便日后回滚
            $logId = $this->deploy_model->addDeployProjectLog($id,$this->_G['userInfo']['id'], $prevRevision, $toRevision);
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
            if ($hasDeploy == True) {
                $this->deploy_model->releaseDeployLock($id,$this->_G['userInfo']['id'],true);
            }
        }
        $_REQUEST['step'] = $currentStep;
        $tplVars['projectInfo'] = $projectInfo;
        $tplVars['predeployHosts'] = $predeployHosts;
        $tplVars['deployHosts'] = $deployHosts;
        $tplVars['hasPredeploy'] = $hasPredeploy;
        $tplVars['hasDeploy'] = $hasDeploy;
        $tplVars['prevRevision'] = $prevRevision;
        $tplVars['toRevision'] = $toRevision;
        $tplVars['projectId'] = $id;
        $this->_G['tplVars'] = $tplVars;
        $this->load->view('deploy/deploy',$this->_G);
    }
    */
    //将代码发送到线测机或线上（生产）机前，进行要发送的主机列表确认
    function confirm($id=0) {
        $projectInfo = self::_getProjectInfo($id);
        $this->_deployCheck($projectInfo);

        $predeployHosts = $projectInfo['bindHosts']['predeployHosts'];
        $deployHosts = $projectInfo['bindHosts']['deployHosts'];
        //获取当前step,默认为predeploy
        (($currentStep = $this->input->get('step')) and in_array($currentStep,array('predeploy','deploy'))) or $currentStep='predeploy';

        //根据当前是预部署还是正式部署，获取要部署的host列表，并将要部署的主机列表 赋页面变量
        $tplVars['deployHosts'] = $currentStep == 'predeploy' ? $predeployHosts : $deployHosts;

        //将传来的参数原样传回去 赋值页面变量
        ($referer = $this->input->get('referer')) or $referer = 'home';
        ($delete = $this->input->get('delete')) or $delete = 'false';

        $tplVars['projectId'] = $id;
        $tplVars['step'] = $currentStep;
        $tplVars['referer'] = $referer;
        $tplVars['delete'] = $delete;
        $this->_G['tplVars'] = $tplVars;
        $this->load->view('deploy/deploy',$this->_G);
    }
    // 进入代码回滚流程
    function rollBack($id=0){
        // 获取项目信息和绑定的主机信息
        $projectInfo = self::_getProjectInfo($id);
        //该项目的部署权限
        $this->_deployCheck($projectInfo);

        $lastDeploys = $this->deploy_model->getLastDeploys($id);

        // $toRevision = isset($_REQUEST['revision']) ? intval($_REQUEST['revision']) : 'PREV';

        $tplVars['projectInfo'] = $projectInfo;
        $tplVars['lastDeploys']= $lastDeploys;
        $this->_G['tplVars'] = $tplVars;
        $this->load->view('deploy/deploy',$this->_G);
    }

    // 获取当前用户正在部署的版本
    function getPreviousRevision($projectId){
        $cookieName = 'previous:'.$projectId;
        if (isset($_COOKIE[$cookieName]) && is_numeric($_COOKIE[$cookieName])) {
            return $_COOKIE[$cookieName];
        }
        return false;
    }
    // 获取当前用户正在部署的版本
    function getCurrentRevision($projectId){
        $cookieName = 'revision:'.$projectId;
        if (isset($_COOKIE[$cookieName]) && is_numeric($_COOKIE[$cookieName])) {
            return $_COOKIE[$cookieName];
        }
        return false;
    }
    // 设置部署前的版本
    function setPreviousRevision($projectId, $revision){
        $cookieName = 'previous:'.$projectId;
        if (is_numeric($projectId) && is_numeric($revision)) {
            setcookie($cookieName, $revision, time()+3600*24*365,'/deploy/');
        }
    }// 设置当前用户正在部署的版本
    function setCurrentRevision($projectId, $revision){
        $cookieName = 'revision:'.$projectId;
        if (is_numeric($projectId) && is_numeric($revision)) {
            setcookie($cookieName, $revision, time()+3600*24*365,'/deploy/');
        }
    }

    // 对某个部署项目进行加锁,防止多人部署
    private function _getDeploytLock($projectId, $oldRevision, $newRevision){
        $userInfo = $this->_G['userInfo'];
        $sessions = $this->deploy_model->getList('deploy_session',array('where'=>'projectId=' . $projectId . ' AND userId !=' . $userInfo['id']));
        if (sizeof($sessions)>0) {
            $this->error(
                '探测到有多个同学进入该项目的部署流程,请进行口头沟通选择本次部署人员',
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
                    'createTime' => TIMESTAMP,
                ),
                array(
                    'projectId',
                    'userId',
                    'username',
                    'oldRevision',
                    'newRevision',
                    'createTime',
                )
            );
        }
    }

    //部署前检查
    private function _deployCheck($projectInfo,$clearlock=true){
        if (!is_dir($projectInfo['deployPath'])) {
            $this->error('项目尚未初始化,发布目录"' . $projectInfo['deployPath'] . '"不存在');
        }
        if (!is_dir($projectInfo['deployPath'] . DIRECTORY_SEPARATOR . '.svn')) {
            renderError('项目尚未初始化,代码仓库"' . $projectInfo['svnUrl'] . '"未检出到发布目录');
        }
        // 强制清理其他部署会话
        // 强制释放本人持有的锁或别人的锁
        $clearlock and $this->deploy_model->releaseDeployLock($projectInfo['id'],$this->_G['userInfo']['id'],false);

    }

    //获取项目的详细信息
    private function _getProjectInfo(&$id,$getBindHosts=true){
        ($id = intval($id)) or $this->error(lang('project_id_error'));
        if (!$this->deploy_model->checkProjectUserBind($id,$this->_G['userInfo']['id'])) {
            $this->error('你没有该项目的部署权限');
        }
        $this->load->model('project_model');
        $detail = $this->project_model->getOne('project',array('where'=>array('id'=>$id)));
        if(empty($detail)){
            $this->error('没有该项目信息');
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