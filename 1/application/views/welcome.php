<?php  //判断已登录用户
			$this->load->library('session');
			$uid = $this->session->userdata('uid');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<?php  //显示default页面
		if (!$uid) {
			include base_url()."default/default.html";
		} 
		else{
			include "welcome_uid.html";
			}
?>