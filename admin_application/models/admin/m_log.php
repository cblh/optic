<?php
//日志log model


###########################@###########################
#备注说明：如果运用于此平台操作， 相对应的日志log会很多，##
#可以考虑把表 common_log 分月进行存储####################
#@author wangjian######################################
#@time 2013-05-18######################################
#@website 57sy.com#####################################
#######################################################
if (! defined('BASEPATH')) {
	exit('Access Denied');
}
class M_log extends CI_Model {
	private $db ;
	private $table_pre ; //数据表表的前缀
	function M_log(){
		
		date_default_timezone_set('Asia/Shanghai');
		parent::__construct();
		$this->db = $this->load->database('real_data',true);
		$this->table_pre = $this->db->table_pre ;
	}
	//插入数据库
	/*
	 *sql 是要插入数据库中的 log_sql的值 
	 *$action 动作
	 *$person 操作人
	 *$ip ip地址
	 *status 操作是否成功 1成功 0失败
	 *message 失败信息
	 */
	function insert($sql,$action = '' ,$person = '' ,$ip = '',$status = '1' ,$message = '' ){
		if(!config_item('is_write_log_to_database')){//是否记录日志文件到数据表中
			return false ;
		}
		$sql = $this->admin_common->do_addslashes($sql) ;
		$message = $this->admin_common->do_addslashes($message);
		$time = date("Y-m-d H:i:s",time());
		$sql_log = "INSERT INTO `{$this->table_pre}common_log`(`log_action`,`log_person`,`log_time`,`log_ip`,`log_sql`,`log_status`,`log_message`)VALUES('{$action}','{$person}','{$time}','{$ip}','$sql','{$status}','{$message}')" ;
		$this->db->query($sql_log);
	}

	
	//查询数据
	//查询list data 返回对象的形式
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
	//查询list data 返回数组的形式
	function querylist_array($sql){
	
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
	
	
	
}