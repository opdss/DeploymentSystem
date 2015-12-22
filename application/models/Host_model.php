<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * @author wuxin
 * @date 2015 15/12/14 下午2:55
 * @copyright 7659.com
 */
class Host_model extends MY_Model{

    // 获取绑定该主机的项目列表
    function getBindProjects($hostId){
        $sql = 'SELECT prj.* FROM project_host AS ph, project AS prj WHERE ph.projectId = prj.id AND ph.hostId = '. $hostId;
        $query = $this->db->query($sql);
        return $query->result_array();
    }
}