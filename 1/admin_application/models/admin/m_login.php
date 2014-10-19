<?php
if (! defined('BASEPATH')) {
	exit('Access Denied');
}
class M_login extends CI_Model {
	private $db ;
	private $table_pre ; //数据表表的前缀	
	function M_login(){
		parent::__construct();		
		$this->db = $this->load->database('real_data',true);
		$this->table_pre = $this->db->table_pre ;	
	}
	//查询1条数据，返回结果
	function query_one($sql){
		
		return $this->db->query($sql)->row_array();
		
	}

	
}