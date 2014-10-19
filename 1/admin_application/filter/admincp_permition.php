<?php
//后台访问控制，控制用户的访问
if (! defined('BASEPATH')) {
	exit('Access Denied');
}

class admincp_permition {
	public function __construct() {
		$this->CI = &get_instance(); //初始化 为了用方法
		$this->CI->load->library("admin_common");//加载公用的方法
	}
	function permition(){
		//echo 'permition';
		$last_permition = array();
		$permition =array();
		$admin = $this->CI->config->item('web_admin_master');
		$username = $this->CI->admin_common->login_name();//当前登录的用户
		if($admin != $username){
			$no_permition_array = $this->CI->admin_common->no_permition_url_array();
			$this->CI->load->model('admin/members/M_members');
			
			if(isset($_COOKIE[$this->CI->config->item('cookie_prefix').'permition'])){
				$permition = $_COOKIE[$this->CI->config->item('cookie_prefix').'permition'];
			}
			
			if($permition){
				$permition = unserialize($permition) ;
			}
			if($permition && $no_permition_array){
				$last_permition = array_merge($permition,$no_permition_array); ;
			}elseif(!$permition && $no_permition_array){
				$last_permition = $no_permition_array ;
			}elseif($permition && !$no_permition_array){
				$last_permition = $permition ;
			}
			
			$url_array = $this->CI->uri->segment_array() ;
			
			$new_url = '';
			if(isset($url_array[1])){
				$new_url.=$url_array[1]."/";
			}
			if(isset($url_array[2])){
				$new_url.=$url_array[2]."/";
			}
			if(isset($url_array[3])){
				$new_url.=$url_array[3]."/";
			}
			if(isset($url_array[4])){
				$new_url.=$url_array[4]."/";
			}
		 	
			//判断是普通的请求地址还是ajax请求
			if(!in_array($new_url, $last_permition)){
				
				if(isset($_GET['inajax'])){
					echo $this->CI->admin_common->result_to_towf_new('',$this->CI->config->item('no_permition'),"你没有权限进行此操作，请联系管理员",null);
				}else{
				//	shownopermition() ;
				show_error("you don't have permition to Access this page,please Contact <font color='red'>{$admin}</font> &nbsp;Email:{$this->CI->config->item('web_admin_email')}",403,'forbidden');
				
				}
				die();
			}			
		}

	}
}