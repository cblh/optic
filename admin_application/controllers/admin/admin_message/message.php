<?php
/*
 *@后台发布公告 
 *author wangjian
 *time 2013-05-27
 */
if (! defined('BASEPATH')) {
	exit('Access Denied');
}
class Message extends MY_Controller{
	private $username ;//当前登录的用户
	private $ip ; //登录的ip地址
	private $table_ ; //表的前缀
	function Message(){
		parent::__construct();
		$this->load->library('admin_common');//加载admin的公用方法
		$this->username = $this->get_login_name(); //得到登录的用户名
		$this->load->model('admin/members/M_members');
		$this->load->model('admin/admin_message/M_messages');
		date_default_timezone_set('Asia/Shanghai');
		$this->load->model('admin/M_log'); //加载日志model
		$this->ip = $this->admin_common->get_client_ip();
		$this->table_ = $this->admin_common->table_pre('real_data');
	}
	function message_publish(){
		$data_ = $_REQUEST ;
		$action = 'message_publish' ;
		$action_array = array(
			'message_publish','do_message_add'
		) ;
		$action = (isset($data_['action']) && in_array($data_['action'], $action_array))?$data_['action']:'message_publish';
		if($action == 'message_publish'){
			$sql_admin = "SELECT * FROM `nt_class`  " ;
			$res = $this->M_members->querylist($sql_admin);
			if($res){
				$res = $this->admin_common->object_to_array($res);
			}
			$data =array(
				'list'=>$res
			);
			$this->load->view('admin/admin_message/views_message_publish',$data);
		}elseif($action == 'do_message_add'){
			$this->do_message_add() ;
		}
		
	}
/*	//处理添加公告(原版)
	private function do_message_add(){
		$post_data = $this->input->post(NULL,true) ;
		$message_title = html_escape($this->admin_common->do_addslashes(trim($post_data['message_title'])));
		$content = html_escape($this->admin_common->do_addslashes(trim($post_data['content'])));
		if(iconv_strlen($message_title,'utf-8') <2 || iconv_strlen($message_title,'utf-8')>20){
			showmessage('公告标题长度必须在2-20之间','admin/admin_message/message/message_publish',3,0);
		}
		if(iconv_strlen($content,'utf-8') <=0 || iconv_strlen($content,'utf-8')>10000){
			showmessage('内容长度必须在0-10000之间','admin/admin_message/message/message_publish',3,0);
		}
		$username = html_escape($this->admin_common->do_addslashes(trim($post_data['username'])));
		if(iconv_strlen($username,'utf-8') <3 || iconv_strlen($username,'utf-8')>16){
			showmessage('用户名的长度必须在3-16之间','admin/admin_message/message/message_publish',3,0);
		}
		$data = array(
			'message_to_person'=>$username,
			'message_content'=>$content	,
			'message_title'=>$message_title,
			'message_publish_person'=>$this->username,
			'message_publish_time'=>date('Y-m-d H:i:s',time()),
			'message_status'=>'0',
		);*/
		//处理添加公告
	private function do_message_add(){
		$post_data = $this->input->post(NULL,true) ;
		$title = html_escape($this->admin_common->do_addslashes(trim($post_data['title'])));
		$content = $post_data['content'];
		$deadtime = $post_data['deadtime'];
		$this->load->library('form_validation');
		$this->form_validation->set_rules('title', 'required|min_length[2]|max_length[20]');
/*		if(iconv_strlen($title,'utf-8') <0 || iconv_strlen($title,'utf-8')>20){
			showmessage('公告标题长度必须在2-20之间','admin/admin_message/message/message_publish',1,0);
		}*/
		if(iconv_strlen($content,'utf-8') <=0 || iconv_strlen($content,'utf-8')>10000){
			showmessage('内容长度必须在0-10000之间','admin/admin_message/message/message_publish',1,0);
		}
		$class = html_escape($this->admin_common->do_addslashes(trim($post_data['class'])));
		if(iconv_strlen($class,'utf-8') <3 || iconv_strlen($class,'utf-8')>16){
			showmessage('用户名的长度必须在3-16之间','admin/admin_message/message/message_publish',1,0);
		}
		$data = array(
			'class'=>$class,
			'content'=>$content	,
			'title'=>$title,
			'publish_person'=>$this->username,
			'publish_time'=>date('Y-m-d H:i',time()),
			'deadtime'=>$deadtime,
		);
		// var_dump($data);die();
		
		@$array = $this->M_messages->insert_event($data);
		if($array[0] >=1){
			$this->M_log->insert($array[1],'log_publish_message',$this->username,$this->ip,1,'发给'.$class."的公告成功"); //插入日志记录
            showmessage('发给'.$class."的公告成功",'admin/admin_message/message/message_publish',1,1);

		}else{
			$this->M_log->insert($array[1],'log_publish_message',$this->username,$this->ip,0,'发给'.$class."的公告失败"); //插入日志记录
            showmessage('发给'.$class."的公告失败",'admin/admin_message/message/message_publish',1,0);
		}
		
	}
	
}