<?php
/*
 *@des系统基本信息设置 
 *@author wangjian
 *@time 2013-10-02
 */
if (! defined('BASEPATH')) {
	exit('Access Denied');
}

class Index extends MY_Controller {
	private $varname = array() ;
	private $username ;//当前登录的用户
	private $ip ; //登录的ip地址
	private $table_ ; //表的前缀
	private $path ="data/sysconfig/sysconfig.txt"; //生成的系统配置信息文件
	function Index(){
		parent::__construct();
		$this->load->model('admin/web_manager/M_site_config');
		$this->load->library('admin_common');//加载admin的公用方法
		$this->username = $this->get_login_name(); //得到登录的用户名
		$this->load->model('admin/M_log'); //加载日志model
		$this->ip = $this->admin_common->get_client_ip();
		$this->table_ = $this->admin_common->table_pre('real_data');
		/*如果要增加页面的form表单，增加下面的数组即可*/
		$this->varname = array(
			/*
				@value 是页面中显示的文字 
				@desc  输入框的说明
				@width 文本框的宽度
				@type 类别，目前只支持3种类别
			*/
			'cfg_basehost'=>array('value'=>'站点网址','desc'=>'请填写站点网址','width'=>"250px",'type'=>'text'),
			'cfg_webname'=>array('value'=>'网站名称','desc'=>'请填写网站名称','width'=>"250px",'type'=>'text'),
			'cfg_medias_dir'=>array('value'=>'上传文件的默认路径','desc'=>'请填写上传文件的默认路径','width'=>"250px",'type'=>'text'),
			'cfg_powerby'=>array('value'=>'网站版权信息','desc'=>'版权信息','width'=>"250px",'type'=>'text'),
			'cfg_keywords'=>array('value'=>'站点默认关键词','desc'=>'填写网站关键词,用,号隔开','width'=>"250px",'type'=>'text'),	
			'cfg_beian'=>array('value'=>'网站备案号','desc'=>'请输入网站备案号','width'=>"250px",'type'=>'text'),
		    'cfg_description'=>array('value'=>'站点描述','desc'=>'输入站点描述信息','type'=>'textarea'),
			'cfg_isopen'=>array('value'=>'是否关闭网站','desc'=>'是否关闭网站','type'=>'radio'),
			'cfg_close_info'=>array('value'=>'关闭原因','desc'=>'输入网站关闭原因','type'=>'textarea'),
		);
		/*如果要增加页面的form表单，增加下面的数组即可*/
	}
	//站点配置页面
	function site_config(){
		$data_ = $_REQUEST ;
		$action_array = array(
			'config','add_config'
		);
		$action = (isset($data_['action']) && in_array($data_['action'],$action_array))?$data_['action']:'config';
		$config_array = $this->varname ;
		if($action == 'config'){
			$sql_search = "SELECT `varname`,`value` FROM `{$this->table_}common_sysconfig` " ;
			$list_data = $this->M_site_config->querylist($sql_search);
			$temp_array = array() ;
			if($list_data){
				foreach($list_data as $key=>$value){
					$temp_array[$value['varname']] = $value['value'] ;
				}
			}
			
			foreach ($config_array as $config_key=>$config_val){			
				$config_val['data_info'] = isset($temp_array[$config_key])?$temp_array[$config_key]:'' ;
				$config_array[$config_key] = $config_val ;
			}
			
		
			$data['config'] = $config_array ;
			/* echo '<pre>';
			print_r($config_array); */
			$this->load->view("admin/web_manager/views_site_config",$data);
		}elseif($action == 'add_config'){
			$this->add_config();
		}

	}
	
	//处理添加系统设置信息
	private function add_config(){
		$data_ = $post_data = $this->input->post(NULL,true) ;
		$str = '' ;
		$last_array = array();	
		if($data_){			
			foreach($data_ as $k=>$v){
				$value = html_escape($this->admin_common->do_addslashes(trim($v)));
				$data_[$k] = $value ;	
			}	
			$last_array = array_intersect_key($data_,$this->varname);		
		}
		if($last_array){
			foreach($last_array as $last_key=>$last_val){
				$sql_ = "INSERT INTO `{$this->table_}common_sysconfig`(`varname`,`value`)VALUES('{$last_key}','{$last_val}') on DUPLICATE key update `varname` = '{$last_key}' ,`value` = '{$last_val}'" ;
				$this->M_site_config->insert_one($sql_);
				$this->M_log->insert("{$sql_}",'system_setting',$this->username,$this->ip,1,"修改参数{$last_key}为'{$last_val}'成功"); //插入日志记录
			}
		}else{
			$this->M_log->insert("no_system_insert_sql",'system_setting',$this->username,$this->ip,0,"修改信息失败,可能参数出错"); //插入日志记录
		}	
		showmessage('修改成功','admin/web_manager/index/site_config',3,1) ;
		
	}
	
	//生成数据文件
	function make_data(){
			$sql_search = "SELECT `varname`,`value` FROM `{$this->table_}common_sysconfig` " ;
			$list_data = $this->M_site_config->querylist($sql_search);
			$keys_array = array_keys($this->varname) ;
			
			$data = array(); 
			$last_data = array();
			if($list_data){
				foreach($list_data as $data_k=>$data_v){
					if(in_array($data_v['varname'], $keys_array)){
						$data[] = $data_v ;
					}
				}
			}
			$path =str_replace("\\", "/", FCPATH);
		
			if(!file_exists(dirname($path.$this->path))){
				mkdir(dirname($path.$this->path));
			}
			if($data){
				foreach ($data as $data_key=>$data_val){
					$last_data[$data_val['varname']] = $data_val['value'];
				}
			}
			$last_data = serialize($last_data);
			@file_put_contents($path.$this->path, $last_data);
			$this->M_log->insert("no_system_make_data_sql",'system_setting_make_data',$this->username,$this->ip,1,"生成系统基本参数缓存文件成功"); //插入日志记录
			$json_data = array() ;
			echo $this->admin_common->result_to_towf_new($json_data,1,'生成成功,生成的文件是'.$path.$this->path.",生成的文件格式是序列化之后的数据",'');
			
			
	}
	
}
