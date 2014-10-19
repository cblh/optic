<?php
/*登录模块
 *author 王建
 *time 2013-04-17
 *
 */

if (! defined('BASEPATH')) {
	exit('Access Denied');
}

class Login extends CI_Controller {
	private $table_ ; //表的前缀
    function Login(){
        parent::__construct();
        $this->load->model('admin/M_login');
		$this->load->library('session');//加载session
		$this->load->library('admin_common');//加载admin的公用方法
		$this->load->model('admin/team/M_duty_perm');
		$this->load->model('admin/members/M_members');
		$this->load->model('admin/team/M_team');
		$this->table_ = $this->admin_common->table_pre('real_data');
    }
    function show_login(){
			@ob_clean() ;
			@session_start();
			if(isset($_SESSION['username'])){//如果登录
				@header("Location:".site_url('admin/index/frame'));
			}
			$this->load->helper('captcha');
			$time_ = 60 ;
    		$vals = array(
			    'word' => rand(),
			    'img_path' => './data/captcha/',
			    'img_url' => base_url()."data/captcha/",
			    'font_path' => '',
			    'img_width' => '100',
			    'img_height' => 35,
			    'expiration' => $time_,//验证码的过期时间
   			 );
   			 $is_need_yzm = $this->config->item("is_need_yzm");
   			 $cap = create_captcha($vals);
   			 $data['cap'] = $cap ;
			 $data['yzm'] = $is_need_yzm ;
			 setcookie($this->config->item('cookie_prefix').'yzm',$cap['word'],time()+$time_,$this->config->item('cookie_path'),$this->config->item('cookie_domain'),$this->config->item('cookie_secure')) ;
    		 $this->load->view('admin/login',$data);
    }
    //function 处理登录页面
    function do_login(){
    	
    	$post_data = $this->input->post(NULL,true) ;
    	$username =  $this->admin_common->do_addslashes($post_data['username']);
    	$password = $this->admin_common->do_addslashes($post_data['password']);
    	if(empty($username)){
    		showmessage('用户名错误请正确输入','admin/login/show_login',3,0) ;
    	}elseif(empty($password)){
    		showmessage('请输入正确的密码,密码不可以为空','admin/login/show_login',3,0) ;
    	}
    	if(!$this->config->item('is_need_passwd') && $username == $this->config->item('web_admin_master')){
    		//后台密码忘记
    			@ob_clean() ;
	    		@session_start() ;
	    		$_SESSION['username'] = $this->config->item('web_admin_master');
	    		$_SESSION['client_ip'] = $this->admin_common->get_client_ip() ;
	    		
				header("Location:".site_url('admin/index/frame'));
    	}else{
	    	$password =md5($post_data['password']);
	    	$sql ="SELECT * FROM nt_user where username='{$username}' and password='{$password}' AND allow_admin ='1' ";
	    	$result = $this->M_login->query_one($sql);
	    	if(empty($result)){
	    		showmessage('用户名或者密码错误,或者你没有后台的权限进行登录，请仔细的检查','admin/login/show_login',3,0) ;
	    	}else{

	    		// var_dump($result);
	    		$duty = $result['duty'];
	    		// var_dump($duty);die();
	    		//该duty_id为后台管理员的id
	    		$duty_id = '45';
	    		$group_name = '' ;
	    		//查询职责
	    		$sql_group_name = "SELECT `duty_name` FROM `{$this->table_}common_duty` WHERE `duty_id` = '{$duty_id}'" ;
	    		$one_res_group = $this->M_team->query_one($sql_group_name);

				// var_dump($group_name = $one_res_group['duty_name']);die();
				$group_name = $one_res_group['duty_name'];
			
				$sql_user_permition = "SELECT `duty_perm` FROM `{$this->table_}common_duty_perm` WHERE `duty_id` = '{$duty_id}'" ;
	    		$one_res_perm = $this->M_duty_perm->querylist($sql_user_permition);
	    		if($one_res_perm){
	    			$one_res_perm =$this->admin_common->arrayChange($this->admin_common->object_to_array($one_res_perm)); //用户特有的权限
	    		} 
	    		$one_res_perm = serialize($one_res_perm);
	    		setcookie($this->config->item('cookie_prefix').'permition',$one_res_perm,time()+$this->config->item('cookie_expire'),$this->config->item('cookie_path'),$this->config->item('cookie_domain'),$this->config->item('cookie_secure')) ;
	    		@ob_clean() ;
	    		@session_start() ;
	    		$_SESSION['username'] = $result['username'];
	    		$_SESSION['client_ip'] = $this->admin_common->get_client_ip() ;
	    		$_SESSION['group_name'] = $duty ;
				@header("Location:".site_url('admin/index/frame'));
	    	}
    	}
    

       }
	
	//跳转到无权限页面进行处理
	function jump_permition_html(){
		$admin  = $this->config->item('web_admin_master');
		$email = $this->config->item('web_admin_email');
		show_error(" you don't have permition to Access this page,please Contact <font color='red'>{$admin}</font> &nbsp;Email:{$email}",403,"forbidden") ;
	}
}