<?php
if (! defined('BASEPATH')) {
	exit('Access Denied');
}
class M_tree extends CI_Model {
	private $db ;
	private $table_pre ; //数据表表的前缀
	function M_tree(){
		parent::__construct();		
		$this->db = $this->load->database('real_data',true);
		$this->table_pre = $this->db->table_pre ;
		
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
	
//查询一级父节点列表   
function queryparenttree($treetype) {   
		$sql = "select * from `{$this->table_pre}common_tree` where  tree_parent_id=0  AND tree_type = '{$treetype}' order by tree_orderby desc "; 
		$result = array();
		$result = $this->querylist($sql);
		$result = $this->admin_common->object_to_array($result);
		
		for($i=0;$i<count($result);$i++)
		{
         $moduleid = $result[$i]['tree_id'];
		 $result[$i]['Child'] = $this->query_child_module_list($moduleid,$treetype);
		}
        return $result;   
    }   
	  //查询二级Module
     function query_child_module_list($moduleid,$treetype) { 
		$sql = "select * from `{$this->table_pre}common_tree` where  tree_parent_id={$moduleid} AND tree_type = '{$treetype}' order by tree_orderby desc"; 
		$result = array();
		$result = $this->querylist($sql);
		$result = $this->admin_common->object_to_array($result);
        for($i=0;$i<count($result);$i++)
		{
         $moduleid = $result[$i]['tree_id'];
		 if($this->getchildcount($moduleid,$treetype)>0)
		   $result[$i]['Child'] = $this->query_child_module_list($moduleid,$treetype);
		}
        return $result;   
    }
    //获取子节点个数
	 function getchildcount($moduleid,$treetype) { 
        $sql = "select count(*)  as num from `{$this->table_pre}common_tree` where  tree_parent_id={$moduleid} AND tree_type = '{$treetype}' "; 
		$result = array();
		$result = $this->querylist($sql);
		$result = $this->admin_common->object_to_array($result);
		return $result[0]['num'];
	}
	
	//插入一条数据
	function insert_one($data){

		return array($this->db->insert("{$this->table_pre}common_tree",$data),$this->db->last_query());
	}
	//修改数据
	function update_data($sql){

		$query = $this->db->query($sql);
		return $this->db->affected_rows(); //返回影响的行数
	}
	
	//删除数据
	function del_data($sql){
		
		$query = $this->db->query($sql);
		return $this->db->affected_rows(); //返回影响的行数
	
	}
}