<?php
/*
 * 后台模块树
 * author wangjian
 * time 2013-05-18
 *
 */
if (! defined('BASEPATH')) {
	exit('Access Denied');
}

class Tree extends MY_Controller{
	private $username ;//当前登录的用户
	private $ip ; //登录的ip地址
	private $dir = './data/tree'; //生成的xml文件路径
	private $table_ ; //表的前缀
	
	function Tree(){
		parent::__construct();
		$this->load->library('admin_common');//加载admin的公用方法
		$this->load->model('admin/tree/M_tree');
		date_default_timezone_set('Asia/Shanghai');
		$this->load->model('admin/M_log');//加载日志模型
		$this->username = $this->get_login_name(); //得到登录的用户名
		$this->ip = $this->admin_common->get_client_ip();
		$this->table_ = $this->admin_common->table_pre('real_data');
	}
	
	function tree_list(){
		$data_ = $_REQUEST ;
		$action = 'tree_list' ;
		$action_array = array(
			'tree_list','tree_list_add_do'
		) ;
		$action = (isset($data_['action']) && in_array($data_['action'], $action_array))?$data_['action']:'tree_list';
		if($action == 'tree_list_add_do'){
			$this->tree_list_add_do() ;
		}elseif($action == 'tree_list'){
			$post_data = $this->input->post(NULL,true) ;		
			$tree_type_array =$this->admin_common->return_treetype_list();
			$tree_type = '' ;//模块树类别 。
			if(isset($post_data['tree_type'])){
				
				if(array_key_exists($post_data['tree_type'], $tree_type_array)){
					$tree_type = intval($post_data['tree_type']);
				}else{
					$tree_type = 1 ;
				}
				
			}else{
				$tree_type = 1 ;
			}
			$res = $this->M_tree->queryparenttree($tree_type);
			$s = '' ;
			
			if(isset($_GET['s']) && $_GET['s'] != ""){
				
				echo $this->admin_common->result_to_towf_new($res,1,'',null);
			}else{
				$data['type_array'] = $tree_type_array ;
				$this->load->view('admin/tree/views_tree_list',$data);
			}
		}	
	}
	
	//添加模块树进行处理
	private function tree_list_add_do(){
		
		$tree_type_array =$this->admin_common->return_treetype_list();
		$post_data = $this->input->post(NULL,true) ;
		
		$tree_name = '' ;
		$tree_name = html_escape($this->admin_common->do_addslashes(trim($post_data['tree_name'])));
		
		$id = intval($post_data['id']) ;
		$code = 1 ;
		$message = '' ;
		if(empty($tree_name)){
			$code = 0 ;
			$message = '模块名称不可以是空' ;
			echo $this->admin_common->result_to_towf_new('',$code,$message,null);
			return false ;
		}
		
		
		$new_tree_type = '';
		if(array_key_exists($post_data['tree_type'], $tree_type_array)){
			$new_tree_type =  $post_data['tree_type'] ;
		}else{
			$new_tree_type = 1 ;
		}
		$data_insert = array();
		$data_insert = array(
				'tree_parent_id'=>$id ,
				'tree_name'=>$tree_name,
				'tree_type'=>$new_tree_type,
				'tree_orderby'=>intval($post_data['tree_orderby']),
		);
	
		$result_array = $this->M_tree->insert_one($data_insert);
		if($result_array[0]>=1){
			$code = 1 ;
			$message = '插入成功' ;
			
			$this->M_log->insert($result_array[1],'log_add_tree',$this->username,$this->ip,1,"添加模块树{$tree_name}类别是{$new_tree_type}成功"); //插入日志记录
		}else{
			$code = '0' ;
			$message = '服务器繁忙请稍后';
			$this->M_log->insert($result_array[1],'log_add_tree',$this->username,$this->ip,0,"添加模块树{$tree_name}类别是{$new_tree_type}失败"); //插入日志记录
		}
		echo $this->admin_common->result_to_towf_new('',$code,$message,null);
		
		
	}
	
	//修改模块树页面
	function tree_edit(){
		$data_ = $_REQUEST ;
		$action = 'tree_edit' ;
		$action_array = array(
			'tree_edit','tree_edit_do'
		) ;
		$action = (isset($data_['action']) && in_array($data_['action'], $action_array))?$data_['action']:'tree_edit';
		if($action == 'tree_edit'){
			if(isset($_GET['id'])){
				$id = intval($_GET['id']);
			}else{
				$id = $_GET['id'];
			}
			$sql = "SELECT `tree_id`,`tree_parent_id`,`tree_name`,`tree_type`,`tree_orderby` FROM `{$this->table_}common_tree` WHERE `tree_id` = '{$id}'" ;
			
			$one = $this->M_tree->query_one($sql);
			$data=array(
					'one'=>$one,
					
			);
			
			$this->load->view('admin/tree/views_tree_edit',$data);
		}elseif($action == 'tree_edit_do'){
			$this->tree_edit_do() ;
		}
		
	}
	//修改模块树处理
	private function tree_edit_do(){
		$post_data = $this->input->post(NULL,true) ;
		$tree_id = intval($post_data['tree_id']);
		$tree_name = html_escape($this->admin_common->do_addslashes(trim($post_data['tree_name'])));
		$tree_orderby = intval($post_data['tree_orderby']);
		$code = 1 ;
		$message = '' ;
		if(empty($tree_name)){
			$code = 0 ;
			$message = '模块名称不可以是空' ;
			echo $this->admin_common->result_to_towf_new('',$code,$message,null);
			return false ;
		}
		$sql_update = "UPDATE `{$this->table_}common_tree` SET `tree_name` = '{$tree_name}',`tree_orderby` = '{$tree_orderby}' WHERE tree_id='{$tree_id}'" ;	
		
		$num = $this->M_tree->update_data($sql_update);	
		if($num>=1){
			$code = 1 ;
			$message = '修改成功' ;
			$this->M_log->insert($sql_update,'log_update_tree',$this->username,$this->ip,1,"修改模块树为{$tree_name}成功"); //插入日志记录
		}else{
			$code = '0' ;
			$message = '服务器繁忙请稍后';
			$this->M_log->insert($sql_update,'log_update_tree',$this->username,$this->ip,0,"修改模块树为{$tree_name}失败"); //插入日志记录
		}
		echo $this->admin_common->result_to_towf_new('',$code,$message,null);
		die();
	}
	
	//删除模块树处理
	function tree_del_do(){
		$post_data = $this->input->post(NULL,true) ;
		$id = intval($post_data['id']);
		$sql="DELETE FROM `{$this->table_}common_tree` WHERE (`tree_id` = '{$id}' or tree_parent_id='{$id}')";
		$num = $this->M_tree->del_data($sql);
		$code = 0 ;
		$message = '' ;
		if($num>=1){
			$code = 1 ;
			$message = '删除成功';
			$this->M_log->insert($sql,'log_delete_tree',$this->username,$this->ip,1,"删除模块树{$id}成功"); //插入日志记录
		}else{
			$code = 0 ;
			$message= '服务器繁忙，或者地址出错' ;
			$this->M_log->insert($sql,'log_delete_tree',$this->username,$this->ip,0,"删除模块树{$id}失败，可能服务器繁忙，或者地址出错"); //插入日志记录
		}
		echo $this->admin_common->result_to_towf_new('',$code,$message,null);
		return ;
	}
	//生成xml文件（模块树）
	function make_xml(){
		$post_data = $this->input->post(NULL,true) ;
		$tree_type = intval($post_data['tree_type']);
		$res = $this->M_tree->queryparenttree($tree_type);
		$code = 0 ; //默认是 0
		$message = '' ;//提示信息
	
		
		if(empty($res)){
			$code = 0 ;
			$message = '对不起没有模块树数据';
			echo $this->admin_common->result_to_towf_new('',$code,$message,null);
			return ;
		}else{
			
			
			$str = serialize($res);
			if(!is_really_writable("{$this->dir}")){
			
				$code = 0 ;
				$message = "对不起目录{$this->dir}不可写,请保证有可写的权限" ;
				echo $this->admin_common->result_to_towf_new('',$code,$message,null);
				return ;
			}
			file_put_contents("{$this->dir}/tree_{$tree_type}.txt", $str);
			$code = 1 ;
		}


		echo $this->admin_common->result_to_towf_new(array('dir'=>$this->dir),$code,$message,null);
		return '';
	}
	
	
	
}


















