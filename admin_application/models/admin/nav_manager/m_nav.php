<?php
class M_nav extends CI_Model {
	private $db ;
	private $table_pre ; //数据表表的前缀
	function M_nav(){
		parent::__construct();	
		$this->db = $this->load->database('real_data',true);
		$this->table_pre = $this->db->table_pre ;
		
	}
	//插入一条数据
	function insert_one($data){
		 return $this->db->insert($this->table_pre."common_admin_nav",$data);
	}
	//查询1条数据，返回结果
	function query_one($sql){
		
		return $this->db->query($sql)->row_array();
		
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
	function query_count($table){
	
		return $this->db->count_all_results($table);
		
	}
	//删除数据
	function del_data($sql){
	
		$query = $this->db->query($sql);
		return $this->db->affected_rows(); //返回影响的行数
		
	}
	//修改数据
	function update_data($sql){
	
		$query = $this->db->query($sql);
		return $this->db->affected_rows(); //返回影响的行数
	}
	
	

}