<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * @author wuxin
 * @date 2015 15/12/14 下午2:55
 * @copyright 7659.com
 */
class Deploy_model extends MY_Model{
    //根据项目ID和用户ID，查看是否绑定
    function checkProjectUserBind($projectId, $userId) {
        $res = $this->getOne(
            'user_project',
            array(
                'where'=>array(
                    'userId' => $userId,
                    'projectId' => $projectId
                )
            )
        );
        return !empty($res);
    }
    // 根据项目ID获取预发布机主机列表
    function getPredeployHosts($prjId){
        $sql = 'SELECT id,idc,ip,hostname FROM host AS h INNER JOIN (SELECT * FROM project_host WHERE projectId=' . $prjId . ') AS p ON p.hostId=h.id WHERE h.status=1 AND h.predeploy=1';
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    // 根据项目ID获取发布机主机列表
    function getDeployHosts($prjId){
        $sql = 'SELECT id,idc,ip,hostname FROM host AS h INNER JOIN (SELECT * FROM project_host WHERE projectId=' . $prjId . ') AS p ON p.hostId=h.id WHERE h.status=1 AND h.predeploy=0';
        $query = $this->db->query($sql);
        return $query->result_array();
    }
    //清除用户部署锁
    function releaseDeployLock($projectId,$userId,$me=true){
        if ($me == True) {
            $where = ' projectId=' . $projectId . ' AND userId=' . $userId;
        } else {
            $where = ' projectId=' . $projectId . ' AND userId!=' . $userId;
        }
        return $this->delete('deploy_session',$where);
    }
    // 检查是否持有项目部署锁
    function checkDeployLock($projectId,$userId){
        $res = $this->getOne(
            'deploy_session',
            array(
                'where'=>array(
                    'projectId' => $projectId,
                    'userId' => $userId
                )
            )
        );
        return !empty($res);
    }
    //写项目部署日志
    function addDeployProjectLog($projectId,$userId, $oldRevision, $newRevision) {
        return $this->insertKeyUp(
            'deploy_log_project',
            array(
                'userId' => $userId,
                'projectId' => $projectId,
                'oldRevision' => $oldRevision,
                'newRevision' => $newRevision,
                'deployTime' => TIMESTAMP,
            )
        );
    }
    //写主机部署日志
    function addDeployHostLog($logId, $hostIp, $rsyncLog, $status = 1) {
        return $this->insertKeyUp(
            'deploy_log_host',
            array(
                'logId' => $logId,
                'hostIp' => $hostIp,
                'rsyncLog' => $rsyncLog,
                'status' => $status,
            )
        );
    }
    // 获取某个项目的最新部署
    function getLastDeploys($projectId, $limit=20){
        $sql = 'SELECT l.*,u.username,p.cname as projectName FROM (SELECT * FROM deploy_log_project WHERE projectId=' . $projectId . ' ORDER BY deployTime DESC LIMIT ' . $limit . ') l INNER JOIN project p ON l.projectId=p.id INNER JOIN user u ON l.userId=u.id ORDER BY deployTime DESC';
        $query = $this->db->query($sql);
        return $query->result_array();
    }
}