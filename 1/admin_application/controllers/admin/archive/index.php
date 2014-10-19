<?php
/*
 * 文章管理
 * author wangjian
 * time 2013-04-16
 *
 */

if (! defined('BASEPATH')) {
	exit('Access Denied');
}

class Index extends CI_Controller {
	function Index(){
		parent::__construct();
	}
	function category(){
		//$this->load->view('admin/archive/archive_list');
	}
	function show_archive_list(){
		echo  'this is the page ' ;
		//$this->load->view("admin/archive/archive");
	}
	
}
