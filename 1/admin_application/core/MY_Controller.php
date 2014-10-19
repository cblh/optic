<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
 * 让ci继承自己的类库 
 * ######################################
 * 这个类里面写权限代码
 *###################################
 */

class MY_Controller extends CI_Controller{
	public $login_username = '' ;//登录的用户名
	function MY_Controller(){
		parent::__construct() ;
		if(!empty($_POST['session']) && isset($_REQUEST['session'])){
			@session_id($_REQUEST['session']);  //此处因为有上传文件 ，解决swfupload上传文件的时候session失效问题
		}
		@ob_clean() ;
		@session_start();
		
		$this->load->library("admin_common");//加载公用的方法
		$this->check_is_login();
		$this->permition() ;
		
	}
	//检查是否登录了
	private function check_is_login(){
		if(isset($_SESSION['username'])){
			$this->login_username = $_SESSION['username'];
		}
	
		if(empty($this->login_username) || $this->login_username == ""){
				//如果没有登录
				if(isset($_GET['inajax'])){
					echo $this->admin_common->result_to_towf_new('',$this->config->item('no_permition'),"你的密码已经过期,重新登录",null);
					die();
				}
				showmessage("密码已经过期",'admin/login/show_login',3,0);
			}
		
		
		
	}
	//验证是否有访问的权限
	private function permition(){
		$last_permition = array();
		$permition =array();
		$admin = $this->config->item('web_admin_master');
		//echo $admin ;
		if($admin != $this->login_username){
			$no_permition_array = $this->admin_common->no_permition_url_array();  //不需要权限就可以进行访问的模块
			
			$this->load->model('admin/members/M_members');
			if(isset($_COOKIE[$this->config->item('cookie_prefix').'permition'])){
				$permition = $_COOKIE[$this->config->item('cookie_prefix').'permition'];
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
			$url_array = $this->uri->segment_array() ;
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
			
				if(isset($_GET['inajax']) || $this->is_ajax()){
					echo $this->admin_common->result_to_towf_new('',$this->config->item('no_permition'),"你没有权限进行此操作，请联系管理员",null);
				}else{
					//	shownopermition() ;
					show_error("you don't have permition to Access this page,please Contact <font color='red'>{$admin}</font> &nbsp;Email:{$this->config->item('web_admin_email')}",403,'forbidden');
			
				}
				die();
			}
			
		}
		
	}
	
	//当前登录的用户名
	public function get_login_name(){
		return $this->login_username ;
		
	}
	
	//注销session和cookie
	public function destory_session_cookie(){
		if($this->login_username){
			$_SESSION['username'] = '' ;
			unset($_SESSION['username']);
			$_SESSION['client_ip'] = '' ;
			unset($_SESSION['client_ip']);
			setcookie($this->config->item('cookie_prefix').'permition',"",time()-$this->config->item('cookie_expire'),$this->config->item('cookie_path'),$this->config->item('cookie_domain'),$this->config->item('cookie_secure')) ;
			unset($_COOKIE);
			showmessage('退出成功','admin/login/show_login',3,1) ;
		}
	}
	private function is_ajax(){
		if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'){
			return true ;
		}else{
			echo false ;
		}
	}
}