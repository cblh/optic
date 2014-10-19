<?php
/*
 * tuandui
 * author wangjian
 * time 2013-04-21 
 *
 */

if (! defined('BASEPATH')) {
	exit('Access Denied');
}

class Team extends MY_Controller {
	private $username ;//当前登录的用户
	private $ip ; //登录的ip地址
	private $table_ ; //表的前缀
	function Team(){
		parent::__construct();
		$this->load->library('admin_common');//加载admin的公用方法
		date_default_timezone_set('Asia/Shanghai');
		$this->load->model('admin/team/M_team');
		$this->load->model('admin/team/M_duty_perm');
		$this->load->model('admin/members/M_members');
		$this->load->model('admin/M_log');
		$this->username = $this->get_login_name(); //得到登录的用户名
		$this->ip = $this->admin_common->get_client_ip();
		$this->table_ = $this->admin_common->table_pre('real_data');
	}
	function team_list(){
		$data_ = $_REQUEST ;
		$action = 'team_list' ;
		$action_array = array(
			'team_list','add_team_users'
		) ;
		$action = (isset($data_['action']) && in_array($data_['action'], $action_array))?$data_['action']:'team_list';
		if($action == 'add_team_users'){
			$this->team_list_add();
			die();
		}

		$sql_admin = "SELECT a.`uid`,a.`username`,b.`duty_name` FROM `{$this->table_}common_member` as a,`{$this->table_}common_duty` as b WHERE a.`allowadmin` = 1 AND a.`status` = 1 AND a.groupid>0 AND a.groupid=b.duty_id" ;
	//	echo $sql_admin ;die();
		$sql_duty = "SELECT `duty_id`,`duty_name` FROM `{$this->table_}common_duty` WHERE `duty_status` = 1 ORDER BY duty_id DESC";
		$res = $this->M_members->querylist($sql_admin);
		$res_duty = $this->M_team->querylist($sql_duty);//查询职务（后台）
		if($res){
			$res = $this->admin_common->object_to_array($res) ;
		}	
		if($res_duty){
			$res_duty = $this->admin_common->object_to_array($res_duty) ;
		}		
		/* echo '<pre>';
		print_r($res_duty);	 */
		$admin = $this->config->item('web_admin_master');//创始人
		$data['admin'] = $admin ;
		$data['list'] = $res_duty ;
		$data['admin_data'] = $res ;
		$this->load->view('admin/master/views_team_list',$data);
	}
	//团队成员新增
	private function team_list_add(){
		$admin = $this->config->item('web_admin_master');//创始人
		$post_data = $this->input->post(NULL,true) ;
		if(isset($post_data['duty_name']) && intval($post_data['duty_name']) >0){
			$duty_id =  intval($post_data['duty_name']);
		}else{
			showmessage('请先去添加职务','admin/master/team/team_duty',3,0);
		}
		
		$username =  html_escape($this->admin_common->do_addslashes(trim($post_data['username'])));
		$one_result = $this->M_members->query_one("SELECT `username` FROM `{$this->table_}common_member` WHERE `username`='{$username}' AND `username`!='{$admin}'");
		
		if(empty($one_result) || empty($username)){
			//用户不存在
			showmessage('你要添加的用户不存在','admin/master/team/team_list',3,0);
		}else if($duty_id <=0){
			showmessage('请添加团队职务','admin/master/team/team_duty',3,0);
		}else{
			$sql_update = "UPDATE `{$this->table_}common_member` SET `groupid` = '{$duty_id}',`allowadmin`='1',`status` ='1' WHERE `username`='{$username}'" ;
			
			$num = $this->M_members->update_data($sql_update,'log_update_add_admin',$this->username,$this->ip);
			if($num>=1){
				$this->M_log->insert($sql_update,'log_update_add_admin',$this->username,$this->ip,1,"修改用户{$username}为后台管理员成功"); //插入日志记录
				showmessage("修改用户{$username}为后台管理员成功",'admin/master/team/team_list',3,1);
			}else{
				$this->M_log->insert($sql_update,'log_update_add_admin',$this->username,$this->ip,0,"修改用户{$username}为后台管理员，你可能没有修改数据"); //插入日志记录
				showmessage("修改用户{$username}为后台管理员，你可能没有修改数据",'admin/master/team/team_list',3,0);
			}
		}
	}
	
	//团队成员删除
	function team_list_del(){
		$get_data = $this->uri->uri_to_assoc(5) ;//fen分5段 为了求出id
	
		$ret = 0 ; 
		$res =  '' ;
		$message = '' ;
		
		if(isset($get_data['id']) && $get_data['id'] > 0){
			$num = $this->M_members->update_data("UPDATE `{$this->table_}common_member` SET `groupid`=0 , `allowadmin`=0 where `uid`='{$get_data['id']}'");
			if($num>=1){
					$ret = 1 ;
					$message = '操作成功' ;
					$this->M_log->insert("UPDATE `{$this->table_}common_member` SET `groupid`=0 , `allowadmin`=0 where `uid`='{$get_data['id']}'",'log_update_del_admin',$this->username,$this->ip,1,"删除管理员的uid是{$get_data['id']}的信息成功"); //插入日志记录
			}else{
					$this->M_log->insert("UPDATE `{$this->table_}common_member` SET `groupid`=0 , `allowadmin`=0 where `uid`='{$get_data['id']}'",'log_update_del_admin',$this->username,$this->ip,0,"删除管理员数据可能不存在"); //插入日志记录
					$ret = 0 ;
					$message = '你要删除的数据可能不存在' ;
			}
			
		}else{
			$this->M_log->insert("UPDATE `{$this->table_}common_member` SET `groupid`=0 , `allowadmin`=0 where `uid`='{$get_data['id']}'",'log_update_del_admin',$this->username,$this->ip,0,"非法访问删除管理员信息"); //插入日志记录
			$ret = 0 ;
			$message = '非法访问，请检查你的url地址' ;
			
		}
		echo $this->admin_common->result_to_towf_new($res,$ret,$message,null);
	}
	//团队职位
	function team_duty(){
		$data_ = $_REQUEST ;
		$action = 'team_duty' ;
		$action_array = array(
			'team_duty','team_duty_add_page','team_duty_add_do'
		) ;
		$action = (isset($data_['action']) && in_array($data_['action'], $action_array))?$data_['action']:'team_duty';
		if($action == 'team_duty_add_page'){
			$this->team_duty_add() ;
		
		}elseif($action == 'team_duty_add_do'){
			$this->team_duty_add_do();
		}elseif($action == 'team_duty' ){
			$sql="SELECT * FROM `{$this->table_}common_duty` ORDER BY duty_id DESC " ;
			$res = $this->M_team->querylist($sql);
			/* echo '<pre>';
			print_r($res); */
			if($res){
				$res = $this->admin_common->object_to_array($res);
				foreach($res as $key=>$value){
					$res[$key]['duty_status'] = $this->admin_common->get_duty_status($value['duty_status'],true); //格式化
				}
			}
			$data['result'] = $res ;
			$this->load->view('admin/master/views_team_duty',$data);
		}
		
	}
	//添加职务页面
	private function team_duty_add(){
		$this->load->view('admin/master/views_duty_add');
	
	}
	//处理添加职务页面
	private function team_duty_add_do(){
		$post_data = $this->input->post(NULL,true) ;
		
		$duty_name =  html_escape($this->admin_common->do_addslashes(trim($post_data['duty_name'])));
		$status = (int)$post_data['status'];
		$data = array(
			'duty_name'=>$duty_name ,//职务名称
			'duty_status'=>$status ,//是否有效
			'duty_createtime'=>date("Y-m-d H:i:s",time()),//创建日期
			'duty_updatetime'=>date("Y-m-d H:i:s",time()),//修改日期
			'duty_creator'=>$this->username,//创建人
			'duty_last_person'=>$this->username,//修改人
		);
		$cur_time = date("Y-m-d H:i:s",time()) ;
		$sql_exists = "SELECT duty_id from {$this->table_}common_duty WHERE duty_name='{$duty_name}'" ;
		$one_res = $this->M_team->query_one($sql_exists);
		$code = 0 ;//错误  $code>=1 的时候正常
		$error_message = '' ;//错误信息
		$sql_insert_duty = "insert into `{$this->table_}common_duty`(duty_name,duty_status,duty_createtime,duty_updatetime,duty_creator,duty_last_person)values('{$duty_name}','$status','{$cur_time}','{$cur_time}','{$this->username}','{$this->username}')" ;
		if($one_res){
			$code = 0 ;//错误
			$error_message='你要添加的职务名称已经存在';
			$this->M_log->insert($sql_insert_duty,'log_add_duty',$this->username,$this->ip,0,"你要添加的职务名称已经存在"); //插入日志记录
		}else{
			$code = $this->M_team->insert_one($data)  ;
			if(!$code){
				$this->M_log->insert($sql_insert_duty,'log_add_duty',$this->username,$this->ip,0,"服务器繁忙请稍后"); //插入日志记录
				$error_message = '服务器繁忙请稍后' ;
			}else{
				//成功 插入到日志记录表里面
				$this->M_log->insert($sql_insert_duty,'log_add_duty',$this->username,$this->ip,1,"成功创建职务{$duty_name}"); //插入日志记录
			}
		}
		
		echo $res =  $this->admin_common->result_to_towf_new('',$code,$error_message,null);
	
		return 0 ;
	}
	//删除职务
	function team_duty_del_do(){
		$get_data = $this->uri->uri_to_assoc(5) ;//fen分5段 为了求出id
		$ret = 0 ; 
		$res =  '' ;
		$message = '' ;
		
		if(isset($get_data['id']) && $get_data['id'] > 0){
			$res = $this->M_team->query_one("SELECT `duty_name` FROM `{$this->table_}common_duty` WHERE duty_id='{$get_data['id']}'");
			if($res){
				$sql="DELETE FROM `{$this->table_}common_duty` WHERE duty_id='{$get_data['id']}'";
				$num =   $this->M_team->del_data($sql);
				if($num >= 1){
					$ret = 1 ;
					$message = '操作成功' ;
					$this->M_log->insert($sql,'log_del_duty',$this->username,$this->ip,1,"成功删除职务id是{$get_data['id']}的记录"); //插入日志记录
				}else{
					$ret = 0 ;
					$message = '服务器繁忙请稍后' ;
					$this->M_log->insert($sql,'log_del_duty',$this->username,$this->ip,0,"删除职务id是{$get_data['id']}的记录，服务器繁忙请稍后"); //插入日志记录
				}
			}else{
					$ret = 0 ;
					$message = '你要删除的数据不存在' ;
					$this->M_log->insert('no_sql','log_del_duty',$this->username,$this->ip,0,"删除职务id是{$get_data['id']}的记录不存在"); //插入日志记录
			}
			
		}else{
			$ret = 0 ;
			$message = '非法访问，请检查你的url地址' ;
			$this->M_log->insert('no_sql','log_del_duty',$this->username,$this->ip,0,"删除职务，非法访问，请检查你的url地址"); //插入日志记录
		}
		echo $this->admin_common->result_to_towf_new($res,$ret,$message,null);
		
	}
	//修改职务页面
	function team_duty_edit(){
		
		$data_ = $_REQUEST ;
		$action = 'team_duty_edit' ;
		$action_array = array(
			'team_duty_edit','team_duty_edit_do'
		) ;
		$action = (isset($data_['action']) && in_array($data_['action'], $action_array))?$data_['action']:'team_duty_edit';
			if($action == 'team_duty_edit' ){
			if(isset($_GET['id'])){
				$id = intval($_GET['id']);
			}else{
				$id = 0 ;
			}
			$sql="SELECT `duty_perm` FROM `{$this->table_}common_duty_perm` WHERE duty_id='{$id}'";
			$exists_data = $this->M_duty_perm->querylist($sql);
			if($exists_data){
				$exists_data = $this->admin_common->arrayChange($this->admin_common->object_to_array($exists_data));
			}
			//查询是否开启职务
			$data_array = $this->M_team->query_one("SELECT `duty_status`,`duty_name` FROM {$this->table_}common_duty WHERE duty_id='{$id}'");
			
			if(isset($data_array['duty_status'])){
				$isopen = $data_array['duty_status'];
			}else{
				$isopen = '' ;
			}
			
			$duty_name = '' ;
			$duty_name = ($data_array['duty_name'])?$data_array['duty_name']:'';
			$permition_array =$this->admin_common->return_nav_array();
			/* echo '<pre>';
			print_r($permition_array); */
			$data['result'] = $permition_array ;
			$data['exists_data']=$exists_data ;
			$data['isopen'] =$isopen ;
			$data['duty_name'] = $duty_name ;
			$this->load->view('admin/master/views_duty_edit',$data);
		}elseif($action == 'team_duty_edit_do'){
			$this->team_duty_edit_do();
		}
	
	}
	//处理修改团队职务的页面
	private function team_duty_edit_do(){ 
		$post_data = $this->input->post(NULL,true) ;
		$code =  0 ;
		$error_message = '';
		$status = intval($post_data['isopen']) ;
		$id = intval($post_data['id']) ;
		$duty_name = $this->admin_common->do_addslashes($post_data['duty_name']);
		if(empty($post_data['perms'])){
			$code = 0 ;
			$error_message = '请选择权限';
		}else{
			//先删除权限
			$sql_delete = "DELETE FROM `{$this->table_}common_duty_perm` WHERE duty_id='{$id}'";
			
			$this->M_duty_perm->del_data($sql_delete);
			
			$perms_data = explode(',', $post_data['perms']);
		
			$perms_data = array_unique($perms_data);
			
			foreach($perms_data as $k=>$v){
				 $this->M_duty_perm->insert_one(array('duty_id'=>$id,'duty_perm'=>$v))  ;
				 
			}
			$sql_duty_update="UPDATE `{$this->table_}common_duty` SET `duty_status` = '{$status}',`duty_name` = '{$duty_name}' WHERE duty_id='{$id}'";
			
			$this->M_team->update_data($sql_duty_update)  ;
			$code = 1 ;
			$error_message = '更新权限成功';
			$this->M_log->insert('no_update_perm_sql','log_update_permition',$this->username,$this->ip,1,"更新职务id是{$id}的权限成功"); //插入日志记录
		}
		$array =array();
		
		echo $res =  $this->admin_common->result_to_towf_new('',$code,$error_message,null);
		//print_r($post_data);
	}

	
}
