<?php
if (! defined('BASEPATH')) {
	exit('Access Denied');
}
class M_messages extends CI_Model {
	private $db ;
	private $table_pre ; //数据表表的前缀	
	function M_messages(){
		parent::__construct();
		$this->db = $this->load->database('real_data',true);
		$this->table_pre = $this->db->table_pre ;
	}
	//插入一条数据
	function insert_one($data){
	
		return array($this->db->insert("{$this->table_pre}common_message",$data),$this->db->last_query());
	}
	//插入一条公告
	function insert_event($data){
	
		return array($this->db->insert("nt_event",$data),$this->db->last_query());
	}


	//查询list data
	function querylist($sql){
	
		$result =array();
		$query = $this->db->query($sql);
		if($query){
			foreach($query->result_array() as $row){
				$result[] = $row ;
			}
		}
	
		return $result ;
	}
	//查询返回的结果
	function query_count($sql){

		$query = $this->db->query($sql);
		return $query->num_rows();
	}
	
	//修改数据
	function update_data($sql){
	
		$query = $this->db->query($sql);
		return $this->db->affected_rows(); //返回影响的行数
	}
}