<?php
if (! defined('BASEPATH')) {
	exit('Access Denied');
}

/*
 *check admin is login 
 *author wangjian
 @验证用户是否登录了
 */

class check_authority{
	private $CI;  
	public function __construct() { 
		$this->CI = &get_instance(); //初始化 为了用方法
		
	}
	function auth(){
		//session_start();
		$this->CI->load->library('admin_common');//加载admin的公用方法
		/*###############为了判断是否是post传递过来的session-----------*/
		if(!empty($_POST['session']) && isset($_POST['session'])){
			session_id($_POST['session']);	
		}
		/*###############为了判断是否是post传递过来的session-----------*/
		session_start();
		
		$username =  $this->CI->admin_common->login_name();	
		if(preg_match("/admin(.*)/iUs", uri_string())){
			//如果url地址里面包含了admin
			
			if(!preg_match("/admin\/login\/(.*)/iUs", uri_string())){
				
				if(empty($username) || $username == ""){
					
					showmessage("密码已经过期",'admin/login/show_login',3,0);
				}
			}
		}
		
	
	}
	
	
}