<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * @author wuxin
 * @date 2015 15/5/9 下午1:13
 * @copyright 7659.com
 */
class MY_Model extends CI_Model{

    public function __construct(){
        $this->load->database();
        parent::__construct();
    }
    //根据数组平接where串
    protected function getWhereStr($data,$t='and'){
        if(is_array($data) && !empty($data)){
            $str = $and = '';
            foreach($data as $k=>$v){
                $str .= $and.' `'.$k.'`="'.$v.'" ';
                $and = $t;
            }
            return $str;
        }
        return (string)$data;
    }

    public function insertKeyUp($tbname,$data,$update=array(),$increment=array()){
        if(empty($update)) {
            return $this->db->insert($tbname, $data) ? (($res = $this->db->insert_id()) ? $res : true) : 0;
        }else{
            foreach($update as $item){
                isset($increment[$item]) ? $this->db->set($item, $increment[$item],false) : $this->db->set($item, $data[$item]);
            }
            return $this->db->replace($tbname);
        }
    }

    public function update($tbname,$data,$where,$increment=array()){
        if(empty($increment)){
            return $this->db->update($tbname,$data,$where);
        }else{
            $this->db->where($where);
            $data = array_merge($data,$increment);
            foreach($data as $k=>$v){
                isset($increment[$k]) ? $this->db->set($k, $increment[$k],false) : $this->db->set($k, $data[$v]);
            }
            return $this->db->update($tbname);
        }
    }

    public function getTotal($tbname,$where=''){
        if(!empty($where)){
            $this->db->where($where);
        }
        $this->db->from($tbname);
        return $this->db->count_all_results();
    }

    public function getList($tbname,$param = array()){
        $param = array_merge(array('limit'=>array(),'select'=>'*','where'=>'','order'=>'','callback'=>null),$param);
        $select = is_array($param['select']) ? implode(',',$param['select']) : $param['select'];
        $this->db->select($select);
        empty($param['where']) or $this->db->where($param['where']);
        if(!empty($param['order'])){
            if(is_array($param['order'])){
               foreach($param['order'] as $k=>$v){
                   $this->db->order_by($k, $v);
               }
            }else{
                $this->db->order_by($param['order']);
            }
        }
        empty($param['limit']) or $this->db->limit($param['limit'][1],$param['limit'][0]);
        $data = $this->db->get($tbname)->result_array();
        if($param['callback'] !== null && !empty($data)){
            $data = array_map($param['callback'],$data);
        }
        return $data;
    }

    public function getOne($tbname,$param = array()){
        $param = array_merge(array('select'=>'*','where'=>'','order'=>''),$param);
        $select = is_array($param['select']) ? implode(',',$param['select']) : $param['select'];
        $this->db->select($select);
        empty($param['where']) or $this->db->where($param['where']);
        if(!empty($param['order'])){
            if(is_array($param['order'])){
                foreach($param['order'] as $k=>$v){
                    $this->db->order_by($k, $v);
                }
            }else{
                $this->db->order_by($param['order']);
            }
        }
        $this->db->limit(1);
        return $this->db->get($tbname)->row_array();
    }

    public function delete($tbname,$where){
        return $this->db->delete($tbname,$where);
    }

}