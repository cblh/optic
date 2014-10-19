<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
| -------------------------------------------------------------------------
| Hooks
| -------------------------------------------------------------------------
| This file lets you define "hooks" to extend CI without hacking the core
| files.  Please see the user guide for info:
|
|	http://codeigniter.com/user_guide/general/hooks.html
|
*/

//定义在控制器完全运行之后调用此类,,判断是否登录了(判断session)
/* $hook['post_controller_constructor'][] = array(   
	 'class'=> 'check_authority',
	 'function' => 'auth',   
	 'filename' => 'check_authority.php',   
	 'filepath' => 'filter'
 ); */
//定义一个钩子，在调用控制器的时候进行触发,控制器运行之后进行调用

/*  $hook['post_controller_constructor'][] = array(   
	 'class'=> 'admincp_vister',
	 'function' => 'visit',   
	 'filename' => 'admincp_vister.php',   
	 'filepath' => 'filter'
 );  */
 
//控制各种后台用户访问 权限控制
/*  $hook['post_controller_constructor'][] = array(
 	 'class'=> 'admincp_permition',
 	 'function' => 'permition',
 	 'filename' => 'admincp_permition.php',
 	 'filepath' => 'filter'
 ); */
 

/* End of file hooks.php */
/* Location: ./application/config/hooks.php */