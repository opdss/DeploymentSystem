<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * @author wuxin
 * @date 2015 15/12/23 上午11:07
 * @copyright 7659.com
 */
class Logs_model extends MY_Model{

    function getLogsList($where,$offset,$pageSize){
        $sql = <<<EOF
            SELECT
                l.*,u.username,p.name AS projectEname,p.cname AS projectCname
            FROM
                (SELECT
                    id, userId, projectId, oldRevision, newRevision, deployTime
                FROM
                    deploy_log_project
                WHERE
                    $where
                ) l
            INNER JOIN
                user u
            ON
                l.userId=u.id
            INNER JOIN
                project p
            ON
                l.projectId=p.id
            ORDER BY
                deployTime DESC
            LIMIT
                $offset, $pageSize
EOF;
        $query = $this->db->query($sql);
        return $query->result_array();
    }
    //获取日志详情
    function getLogInfo($logId){
        //获取日志详情
        $sql = 'SELECT l.*,u.username,p.name AS projectEname,p.cname AS projectCname '
            .'FROM (SELECT id, userId, projectId, oldRevision , newRevision, deployTime FROM deploy_log_project '
            .'WHERE id = '.$logId.' ) l '
            .'INNER JOIN user u ON l.userId=u.id INNER JOIN project p ON l.projectId=p.id';
        $query = $this->db->query($sql);
        return $query->row_array();
    }
}