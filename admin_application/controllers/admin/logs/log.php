<?php
######################
#后台操作日志查询
#
######################
if (! defined('BASEPATH')) {
	exit('Access Denied');
}
class Log extends  MY_Controller{
	private $page_num = 20 ;
	private $table_ ; //表的前缀
	function Log(){
		parent::__construct();
		$this->load->library('admin_common');//加载admin的公用方法
		$this->load->model('admin/M_log');
		$this->page_num = 10 ;
		$this->table_ = $this->admin_common->table_pre('real_data');
	}
	function log_list(){
		$data_ = $_REQUEST ;
		$action = 'log_list' ;
		$action_array = array(
			'log_list','search'
		) ;
		$action = (isset($data_['action']) && in_array($data_['action'], $action_array))?$data_['action']:'log_list';
		if($action == 'search'){
				$this->search();
				die();
		}else if($action == 'log_list'){
				$this->load->library('pagination');//加载分页类
				$this->load->library('MY_Pagination');//加载分页类
				$res =array();
				$get_data = $this->uri->uri_to_assoc(5) ;//fen分5段 为了求出page
				if(isset($get_data['page'])){
					$page = intval($get_data['page']) ;	
					if($page == 0){
						$page = 1 ;
					}	
				}else{
					$page = 1 ;
				}
		
				$sql_count = "SELECT * FROM `{$this->table_}common_log`";
				$total = $this->M_log->query_count($sql_count);
				$page_num = $this->page_num  ;
				$page_string = $this->page_string($total, $page_num, $page);
				
				$limit = ($page-1)*$page_num;
				$sql = "SELECT * FROM `{$this->table_}common_log` ORDER BY log_id DESC  LIMIT ".$limit.",".$page_num;
				
				
				$res = $this->M_log->querylist($sql);
				if($res){
					$res = $this->admin_common->object_to_array($res);
					foreach($res as $k=>$v){
						$res[$k]['log_action'] = $this->admin_common->get_log_action_str($v['log_action']);
						$res[$k]['log_status'] = ($v['log_status']==1)?'成功':'<font color="red">失败</font>' ;
					}
				}
				/*echo '<pre>';
				print_r($res);*/
				$data =array(
					'result'=>$res,
					'action'=>$this->admin_common->return_action_array(),
					'page_string'=>$page_string ,
				);
			
				$this->load->view('admin/logs/views_log_list',$data);		
		}
		
		

	}
	
	private function search(){
		$post_data = $this->input->post(NULL,true) ;
		$where = $this->search_condition('post') ;
		if(!isset($post_data['page'])){
			$page  =1 ;
		}else{
			$page = intval($post_data['page']);
		}		

		$sql_count = "SELECT * FROM `{$this->table_}common_log` ".$where ." ORDER BY log_id DESC ";
		
		$total = $this->M_log->query_count($sql_count);
 		$per_page =$this->page_num ;//每一页显示的数量
		$page_string = $this->page_string($total, $per_page, $page);

     	
		$limit = ($page-1)*$per_page;
		$sql = "SELECT * FROM `{$this->table_}common_log` $where  LIMIT ".$limit.",".$per_page;
		
		$res = $this->M_log->querylist($sql);
		if($res){
			$res = $this->admin_common->object_to_array($res);
			foreach($res as $k=>$v){
				$res[$k]['log_action'] = $this->admin_common->get_log_action_str($v['log_action']);
				$res[$k]['log_status'] = ($v['log_status']==1)?'成功':'<font color="red">失败</font>' ;
			}
		}
	
		$data['result'] = $res ;
		
		echo $this->admin_common->result_to_towf_new($data,1,'',$page_string);
		
	}

/*
 * 生成查询条件
 * 
 * @data_mode 数据方式 post get
 */	
private function search_condition($data_mode = 'get'){
	$data = '' ;
	$where = ' where 1=1';
	if($data_mode == 'get'){
		$data = $this->input->get(NULL,true) ;
	}else{
		$data = $this->input->post(NULL,true) ;
	}
	
	if(isset($data['log_action']) && array_key_exists($data['log_action'], $this->admin_common->return_action_array())){
		$where.=" AND `log_action`='{$data['log_action']}'";
	}
	
	if(isset($data['log_person']) && $data['log_person'] != ""){
		$where.=" AND `log_person` = '{$data['log_person']}'";
	}
	
	if(isset($data['log_status']) &&  in_array($data['log_status'], array("1","0"),true)){
		$where.=" AND `log_status` = '{$data['log_status']}'";
	}
	
	
	$log_time1 = $data['log_time1'];
	$log_time2 = $data['log_time2'];
	
	if($log_time1 != "" && $log_time2 != ""){
		$where.=" AND `log_time` >= '{$log_time1}' AND `log_time` <='{$log_time2}'";
	}elseif($log_time1 == "" && $log_time2 != ""){
		$where.=" AND `log_time` <='{$log_time2}'";
	}elseif($log_time1 != "" && $log_time2  == ""){
		$where.=" AND `log_time` >='{$log_time1}'";
	}
	return $where ;
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
//导出数据
function export(){
	$where = $this->search_condition('get') ;
	$sql_ = "SELECT * FROM `{$this->table_}common_log` ".$where ." ORDER BY log_id DESC ";
	$res = $this->M_log->querylist_array($sql_);
	if(count($res) >50000){
		echo 'max num is 50000';
		die();
	}
	
	
	$table = '<table border="1">' ;
	$table.='<tr><td>日志id</td><td>操作action</td><td>操作者</td><td>操作日期</td><td>操作者IP</td><td>触发sql</td><td>状态</td><td>系统返回信息</td></tr>';
	
	if($res){
		foreach($res as $k=>$v){
			$status = ($v['log_status']==1)?'成功':'<font color="red">失败</font>' ;
			$table.='<tr>';
			$table.='<td>'.$v['log_id'].'</td>';
			$table.="<td>{$this->admin_common->get_log_action_str($v['log_action'])}</td>";
			$table.="<td>{$v['log_person']}</td>";
			$table.="<td>{$v['log_time']}</td>";
			$table.="<td>{$v['log_ip']}</td>";
			$table.="<td>{$v['log_sql']}</td>";
			$table.="<td>{$status}</td>";
			$table.="<td>{$v['log_message']}</td>";
			$table.='</tr>';
		}
	}
	
	$table.='</table>';
	$name = date("Ymd",time())."_log";
	header('Content-Length: '.strlen($table));
	header("Content-type:application/vnd.ms-excel");
	header("Content-Disposition:filename={$name}.xls");
	echo $table;

}


}