<?php
class M_duty_perm extends CI_Model {
	private $db ;
	private $table_pre ; //数据表表的前缀
	function M_duty_perm(){
		parent::__construct();
		$this->db = $this->load->database('real_data',true);		
		$this->table_pre = $this->db->table_pre ;
	}
	//插入一条数据
	function insert_one($data){
		
		 return $this->db->insert("{$this->table_pre}common_duty_perm",$data);
		
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
			foreach($query->result() as $row){
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
	//删除数据
	function del_data($sql){

		$query = $this->db->query($sql);
		return $this->db->affected_rows(); //返回影响的行数
		
	}


}