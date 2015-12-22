<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * @author wuxin
 * @date 2015 15/12/14 下午2:55
 * @copyright 7659.com
 */
class User_model extends MY_Model{

    // 根据用户ID获取该用户绑定的项目列表
    function getUserProjects($userId) {
        $sql = 'SELECT project.*
        FROM user_project, project
        WHERE user_project.projectId = project.id AND user_project.userId = ' . $userId;
        $query = $this->db->query($sql);
        return $query->result_array();
    }
    // 获取某个用户的权限 信息

}