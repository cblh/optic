<?php
/*
 * 会员模块
 * author wangjian
 * time 2013-04-16
 *
 */

if (! defined('BASEPATH')) {
	exit('Access Denied');
}
class User extends MY_Controller{
	private $ip ; //登录的ip地址
	private $username ;//当前登录的用户
	private $table_ ; //表的前缀
	function User(){
		parent::__construct();
		$this->load->library('admin_common');//加载admin的公用方法
		$this->load->model('admin/members/M_members');
		date_default_timezone_set('Asia/Shanghai');	
		$this->load->model('admin/M_log'); //加载日志model
		$this->username = $this->get_login_name(); //得到登录的用户名
		$this->ip = $this->admin_common->get_client_ip();
		$this->table_ = $this->admin_common->table_pre('real_data');
	}
	//会员添加
	function user_add(){
		$data_ = $_REQUEST ;
		$action = "user_add" ;
		$action_array = array("user_add","do_member_add");
		$action = (isset($data_['action']) && in_array($data_['action'],$action_array))?$data_['action']:"user_add";
		if($action == "user_add"){
			$this->load->view('admin/members/views_member_add');
		}elseif($action == "do_member_add"){
			$this->do_member_add();
		}
		
		
	}
	//处理会员添加
	private function do_member_add(){
		$post_data = $this->input->post(NULL,true) ;
		$username = html_escape($this->admin_common->do_addslashes(trim($post_data['username'])));
		$passwd = trim($post_data['passwd']);
		$username = str_replace(" ","",$username);
		$passwd = str_replace(" ","",$passwd);
		if(iconv_strlen($username,'utf-8') <3 || iconv_strlen($username,'utf-8')>16){
			showmessage('用户名的长度必须在3-16之间','admin/members/user/user_add',3,0);
		}
		if(iconv_strlen($passwd,'utf-8') <3 || iconv_strlen($passwd,'utf-8')>16){
			showmessage('密码的长度必须在3-16之间','admin/members/user/user_add',3,0);
		}
		if($this->admin_common->utf8_str($passwd) != 1){
			//如果不是英文
			showmessage('密码必须是纯英文','admin/members/user/user_add',3,0);
		}		
		$res = $this->M_members->query_one("SELECT `username` FROM `{$this->table_}common_member` WHERE username='{$username}'");
		if($res){
			showmessage('用户<font color="red">'.$username."</font>已经存在",'admin/members/user/user_add',3,0);
		}
		$data = array(
			'username'=>$username,
			'passwd'=>md5($passwd),
			'allowadmin'=>0,
			'regdate'=>date("Y-m-d H:i:s",time())
		);
		$num = $this->M_members->insert_one($data);
		if($num >=1){
			$this->M_log->insert("insert_user_sql ",'log_add_user',$this->username,$this->ip,1,'添加用户'.$username."成功"); //插入日志记录
			showmessage('添加用户'.$username."成功",'admin/members/user/user_add',3,1);
		}else{
			$this->M_log->insert("insert_user_sql ",'log_add_user',$this->username,$this->ip,0,'添加用户'.$username."失败"); //插入日志记录
			showmessage('添加用户'.$username."失败",'admin/members/user/user_add',3,0);
		}
		
	}
	/*
	 *@params 会员管理 
	 * 
	 */	
	function manager_users(){
		$this->load->library('pagination');//加载分页类
		$this->load->library('MY_Pagination');//加载分页类
		$post_data = $this->input->post(NULL,true) ; 
		$is_search = '' ;
		$level_array = $this->admin_common->member_level_array(false);
		$data['level'] = $level_array;
		if(isset($post_data['is_search']) && $post_data['is_search'] == 'search'){
			$this->search() ;
		}else{
			$this->load->view('admin/members/views_manager_users',$data);
		}
		
	}
	private function search(){
		$post_data = $this->input->post(NULL,true) ;
		$where = ' where 1=1';
		
		$allow_admin = $post_data['allowadmin'];
		if($allow_admin != 'all'){
			
			$where.=' AND `allowadmin`='.intval($allow_admin);
		}
		if(isset($post_data['username'])){
			$username = $this->admin_common->do_addslashes($post_data['username']);
		}	
				
		if(!empty($username)){
			$where.=" AND username='{$username}'";
		}
		$orderby = intval($post_data['orderby']);
		if(!in_array($orderby, array(1,2))){
			$orderby = 1 ;
		}
		$array_key = array(1=>'uid',2=>'regdate');
		$where.=" ORDER BY ".$array_key[$orderby];
		
		$desc_asc = $post_data['desc_asc']; //根据降序或者是升序
		if(!in_array($desc_asc,array('asc','desc'))){
			$desc_asc = "asc";
		}
		$where.=" ".$desc_asc;
		if(!isset($post_data['page'])){
			$page  =1 ;
		}else{
			$page = intval($post_data['page']);
		}		

		$sql_count = "SELECT `uid`,`username`,`passwd`,`allowadmin`,`status`,`regdate` FROM {$this->table_}common_member ".$where;
		
		$total = $this->M_members->query_count($sql_count);
 		$per_page = 15 ;//每一页显示的数量
		$page_string = $this->page_string($total, $per_page, $page);

     	
		$limit = ($page-1)*$per_page;
		$sql = "SELECT `uid`,`username`,`allowadmin`,`status`,`regdate` FROM {$this->table_}common_member $where  LIMIT ".$limit.",".$per_page;
		
		$res = $this->M_members->querylist($sql);
		if($res){
			$res = $this->admin_common->object_to_array($res);
		}
		if($res){
			foreach($res as $key=>$val){
				$res[$key]['status'] = $this->admin_common->get_member_status($val['status']);
				$res[$key]['allowadmin'] = $this->admin_common->get_member_level($val['allowadmin']);
			}		
		}	
		
		$data['result'] = $res ;
		$data['page_string'] = $page_string ;
		
		
		echo $this->admin_common->result_to_towf_new($data,'','',null);
	}
	
/*@function page_string
 *@分页返回数据
 *@total 总的数量
 *@page_num 每一页显示的数量
 *@page 当前是第几页数据
 */
private function page_string($total,$page_num,$page){
	$page_string = '' ;
	$this->load->library('pagination');//加载分页类
	$this->load->library('MY_Pagination');//加载分页类
	$config['total_rows'] = $total;
	$config['use_page_numbers'] =true; // 当前页结束样式
	$config['per_page'] = $page_num; // 每页显示数量，为了能有更好的显示效果，我将该数值设置得较小
	$config['full_tag_open'] = '<div class="pg">'; // 分页开始样式
	$config['full_tag_close'] = '</div>'; // 分页结束样式
	$config['first_link'] = '首页'; // 第一页显示
	$config['last_link'] = '末页'; // 最后一页显示
	$config['next_link'] = '下一页 >'; // 下一页显示
	$config['prev_link'] = '< 上一页'; // 上一页显示
	$config['cur_tag_open'] = ' <a class="current">'; // 当前页开始样式
	$config['cur_tag_close'] = '</a>'; // 当前页结束样式
	$config['uri_segment'] = 6;
	$config['anchor_class']='class="ajax_page" ';
	$this->pagination->cur_page = $page ;
	$this->pagination->initialize($config); // 配置分页
	$page_string =  $this->pagination->create_links(true,'ajax_data');
	return $page_string ;
}

//login out
function login_out(){
	$this->destory_session_cookie();
}
//
//functions 修改密码
function edit_passwd(){
	
	$data['username'] = $this->username;
	$this->load->view('admin/views_editpasswd',$data);
}
//处理修改密码
function edit_passwd_do(){
	$post_data = $this->input->post(NULL,true) ;

	$newpasswd = $this->admin_common->do_addslashes($post_data['newpasswd']);
	$newpasswd2 =$this->admin_common->do_addslashes($post_data['newpasswd2']);
	if(empty($newpasswd) || empty($newpasswd2)) {
		showmessage('密码必须进行填写','admin/login/edit_passwd',3,0);
	}
	if(iconv_strlen($newpasswd,'utf-8') <3 || iconv_strlen($newpasswd,'utf-8')>16){
		showmessage('密码的长度必须在3-16之间','admin/login/edit_passwd',3,0);
	}
	if($newpasswd != $newpasswd2){
		showmessage('2次密码必须相同','admin/login/edit_passwd',3,0);
	}
	
	if($this->admin_common->utf8_str($newpasswd) != 1){
		//如果不是英文
		showmessage('密码必须是纯英文','admin/login/edit_passwd',3,0);
	}
	$newpasswd = md5($newpasswd);
	$sql_update="UPDATE `{$this->table_}common_member` SET passwd = '{$newpasswd}'  WHERE username='{$this->username}'";
	$num = $this->M_members->update_data($sql_update);
	if($num >=1){

		$url = site_url("admin/members/user/login_out");
		echo "<script>parent.window.location=\"{$url}\";</script>";
	}else{
		showmessage('你可能没有修改任何东西','admin/members/user/edit_passwd',3,0);
	}
}


}