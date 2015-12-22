<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * @author wuxin
 * @date 2015 15/12/14 下午2:55
 * @copyright 7659.com
 */
class Project_model extends MY_Model{

    function getUserProjectTotal($userId){
        $sql = 'SELECT count(*) as num FROM user_project, project WHERE user_project.projectId = project.id AND user_project.userId = ' . $userId;
        $query = $this->db->query($sql);
        $res = $query->row_array();
        return (int)$res['num'];
    }

    function getUserProjectList($userId,$offset,$number=10){
        $sql = 'SELECT project.* FROM user_project, project WHERE user_project.projectId = project.id AND user_project.userId = ' . $userId. ' limit ' .$offset. ', '.$number;
        $query = $this->db->query($sql);
        return $query->result_array();
    }
    //获取绑定项目的主机列表
    function getBindHosts($prj_id) {
        $sql = 'SELECT `host`.* FROM `project_host`, `host` where project_host.hostId = `host`.id AND project_host.projectId = ' . $prj_id . ' order by `host`.ip ';
        $query = $this->db->query($sql);
        return $query->result_array();
    }
}