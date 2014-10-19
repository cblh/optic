<?php
/*
 * 后台菜单配置和管理
 * author wangjian 
 */
if (! defined('BASEPATH')) {
	exit('Access Denied');
}

class Index extends MY_Controller {
	private $username ;//当前登录的用户
	private $ip ; //登录的ip地址
	private $table_ ; //表的前缀
	function Index(){
		parent::__construct();
		$this->load->library('admin_common');//加载admin的公用方法
		date_default_timezone_set('Asia/Shanghai');
		$this->load->model('admin/nav_manager/M_nav');
		$this->username = $this->get_login_name(); //得到登录的用户名
		$this->ip = $this->admin_common->get_client_ip();
		$this->table_ = $this->admin_common->table_pre('real_data');
	}
	
	function show_nav(){
		$this->load->library('pagination');//加载分页类
		$this->load->library('MY_Pagination');//加载分页类
		
		$request = $_REQUEST ;
		$action = '' ;
		if(!isset($request['page'])){
			$page  =1 ;
		}else{
			$page = intval($request['page']);
		}
		$sql_count = "SELECT count(*) as cnt FROM {$this->table_}common_admin_nav" ;
		$total =  $this->M_nav->query_count("{$this->table_}common_admin_nav") ;
		
		$per_page = 2 ;//每一页显示的数量
		$page_string = $this->page_string($total, $per_page, $page);
		$limit = ($page-1)*$per_page;
		
		$data_ = $this->M_nav->querylist("SELECT * FROM {$this->table_}common_admin_nav ORDER BY nav_id DESC LIMIT $limit,$per_page");
		if($data_){
			foreach($data_ as $k=>$v){
				$data_[$k]['nav_position'] = ($v['nav_position'] == 1)?'菜单左边':'菜单右边' ;
				$data_[$k]['nav_status'] = ($v['nav_status'] == 1)?'开启':'<font color="red">关闭</font>' ;
			}
		}
		$data = array(
			'list_'=>$data_	,
			'page_string'=>$page_string
		);
		if(isset($request['action']) && $request['action'] == 'show_list'){
			echo $this->admin_common->result_to_towf_new($data,'','',null);
			die();
		}
		//echo $this->admin_common->result_to_towf_new($data,'','',null);
		$this->load->view("admin/nav_manager/nav_list",$data);
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
		$page_string =  $this->pagination->create_links(true,'common_request');
		return $page_string ;
	}	
	function nav_add_page(){
		$action_array = array('add','page');
		$action = 'page' ;
		
		if(isset($_REQUEST['action']) && in_array($_REQUEST['action'], $action_array)){
			$action = $_REQUEST['action'] ;
		}
		if($action == 'page'){
			$data_ = $this->M_nav->querylist("SELECT nav_id,nav_name FROM {$this->table_}common_admin_nav WHERE nav_status = 1 AND nav_position = 1  ");
			$list_ = array(
				'list'=>$data_
			);
			
			$this->load->view("admin/nav_manager/nav_add_page",$list_);
		}elseif($action == "add"){
			$this->add_nav() ;
		}
		
	}
	//处理添加菜单
	private function add_nav(){
		$data = $_REQUEST;
		$nav_name = $this->_params('nav_name'); //菜单名称
		$url = $this->_params('url') ;//url地址
		$p_id = intval($this->_params('p_id'));//菜单级别
		$nav_position = $this->_params('nav_position') ;//菜单位置
		$nav_status = intval($this->_params('nav_status')) ; //菜单状态
		$nav_order = intval($this->_params('nav_order')) ;//菜单排序
		$params = array(
			'nav_name'=>$nav_name,
			'nav_url'=>$url,
			'nav_parent_id'=>$p_id,
			'nav_position'=>$nav_position,
			'nav_status'=>$nav_status,
			'nav_order'=>$nav_order	
		);
		//echo '<pre>';
		//print_r($params);
		$num =  $this->M_nav->insert_one($params);
		if($num >= 1){
			echo $res =  $this->admin_common->result_to_towf_new('',1,0,null);
		}else{
			echo $res =  $this->admin_common->result_to_towf_new('',0,'服务器繁忙',null);
		}
		
	}
	//判断参数
	private function _params($index){
		if(isset($_REQUEST[$index]) && !empty($_REQUEST[$index])){
			return html_escape($this->admin_common->do_addslashes($_REQUEST[$index]));
		}else{
			return '' ;
		}
	}
	
	
	
	
}
