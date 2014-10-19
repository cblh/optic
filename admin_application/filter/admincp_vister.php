<?php
//后台访问控制，
if (! defined('BASEPATH')) {
	exit('Access Denied');
}

/*
@后台访问控制主要是根据控制器和方法进行判断
*/

class admincp_vister {
	public function __construct() { 
		$this->CI = &get_instance(); //初始化 为了用方法
		
	}
	function visit(){
		//echo uri_string();
		
		$this->CI->load->library("admin_common");//加载公用的方法
		
		$admincp_actions_normal = $this->CI->admin_common->return_visit_array();
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
	/* 	echo $new_url ;
		echo '<hr>' ; */
		
		if(preg_match("/admin(.*)/iUs", $new_url)){
			if(!preg_match("/admin\/login\/(.*)/iUs", $new_url)){
		
					if(!in_array($new_url,$admincp_actions_normal)){
						//权限不足
						
						show_error("You don't have permition to visit this page");
					}
			}
		}

	}
}
