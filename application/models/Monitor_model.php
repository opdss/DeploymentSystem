<?php
/**
 * Created by PhpStorm.
 * @author wuxin
 * @date 2015 15/12/28 下午5:07
 * @copyright 7659.com
 */

class Monitor_model extends MY_Model{

    function getMonitorList($userId){
        $sql = 'SELECT * FROM user_app_monitor, app_monitor_item WHERE user_app_monitor.appMonitorId = app_monitor_item.id AND user_app_monitor.userId=' . $userId;
        $query = $this->db->query($sql);
        return $query->result_array();
    }
}