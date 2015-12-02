<?php
/**
 * Created by PhpStorm.
 * @author wuxin
 * @date 2015 15/6/22 下午6:53
 * @copyright 7659.com
 */
class Game_model extends MY_Model{

    function getIdx(){
        $sql = "select idx from fl_game_info where status=1 GROUP BY idx ORDER BY idx asc";
        $query = $this->db->query($sql);
        $data = array();
        if ($query->num_rows() > 0) {
            foreach ($query->result_array() as $row) {
                $data[] = $row['idx'];
            }
        }
        return $data;
    }
}