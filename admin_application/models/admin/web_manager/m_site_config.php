<?php
if (! defined('BASEPATH')) {
	exit('Access Denied');
}
class M_site_config extends CI_Model {
	private $db ;
	private $table_pre ; //数据表表的前缀
	
	function M_site_config(){
		parent::__construct();		
		$this->db = $this->load->database('real_data',true);
		$this->table_pre = $this->db->table_pre ;
		
	}
	
	//插入一条数据
	function insert_one($sql){
		 //return $this->db->insert($this->table_pre."common_sysconfig",$data);
		 $query = $this->db->query($sql);
		// return $this->db->affected_rows(); //返回影响的行数
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
	
	
}