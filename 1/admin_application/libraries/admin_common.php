<?php
/*
@admin common function
@time 2013-04-19
@author wangjian
*/

if (! defined('BASEPATH')) {
	exit('Access Denied');
}
function sae_set_display_errors($str){
	return true ;
}

class Admin_common {
	function Admin_common(){
	}
/*
@在插入数据库的时候在'号进行加上\,过滤一些特殊的字符
@author wangjian
@time 2013-04-20
*/	
function do_addslashes($str){
	$str = str_replace("\\", "", $str); // 把\进行过滤掉
	$str = str_replace("%", "\%", $str); // 把 '%'前面加上\
	$str = str_replace("'", "\'", $str); // 把 '%'过滤掉
	$str = str_replace(" ","",$str);
	return $str ;
}
//admin visit
/*function return_visit_array(){
	return array(
			'admin/index/frame/',//整体框架
			'admin/index/top/',//上面的
			'admin/index/menu/',//左边的菜单
			'admin/index/main/',//右边的主题
			'admin/index/edit_passwd/',//修改密码页面
			'admin/login/jump_permition_html/', //无权限页面
			
			'admin/archive/index/show_archive_list/',
			'admin/archive/index/category/',
			
			
			'admin/master/team/team_list/',//团队成员 
			'admin/master/team/team_list_add/',//团队成员添加处理
			'admin/master/team/team_list_del/',//团队成员删除处理
			
			'admin/master/team/team_duty/', //team职务列表
			'admin/master/team/team_duty_add/',//team 职务add
			'admin/master/team/team_duty_add_do/', //team处理添加
			'admin/master/team/team_duty_del_do/',//team删除
			'admin/master/team/team_duty_edit/',//team 职务edit
			'admin/master/team/team_duty_edit_do/',//team 职务处理编辑
			
			
			'admin/members/user/user_add/',//用户添加
			'admin/members/user/do_member_add/',//处理用户添加
			'admin/members/user/manager_users/',//用户管理
			'admin/members/user/search/',//用户搜素
			
			//日志
			'admin/logs/log/log_list/',//日志列表
			'admin/logs/log/search/',//日志log搜索
			'admin/logs/log/export/' ,//日志导出功能
			//模块树
			'admin/admin_tree/tree/tree_list/',//模块树列表	
			'admin/admin_tree/tree/tree_list_add_do/' ,//模块树添加处理
			'admin/admin_tree/tree/tree_edit/',//模块树修改页面
			'admin/admin_tree/tree/tree_edit_do/',//处理模块树修改
			'admin/admin_tree/tree/tree_del_do/',//处理模块树删除
			'admin/admin_tree/tree/make_xml/',//生成模块树xml文件
			//后台发公告
			'admin/admin_message/message/message_publish/',
			'admin/admin_message/message/do_message_add/',
			//文件管理功能
			'admin/file_manager/file/file_list/', //文件列表
			'admin/file_manager/file/upload_file/', //文件批量上传
			'admin/file_manager/file/del_file/',//删除文件
			'admin/file_manager/file/mkdir_dir/',//创建目录
			
		);
}

*/




/*
 *不需要进行验证的url地址 
 * 
 */
function no_permition_url_array(){
	return array(
			'admin/index/frame/', //整体框架
			'admin/index/top/',//top
			'admin/index/menu/',//左边的菜单
			'admin/index/main/',//右边显示的那个页面
			'admin/login/show_login/',//登录页面
			'admin/login/do_login/',//登录处理
			'admin/members/user/login_out/',//退出登录
			'admin/members/user/edit_passwd/',//修改密码页面
			'admin/members/user/edit_passwd_do/',//处理修改密码 页面
			'admin/login/jump_permition_html/',//跳转到无权限页面
			);
}


/*
 *导航数组 
 *author 王建
 *time 2013-09-07
 */
function return_nav_array(){
	return array(
		'left'=>array(
			'系统设置'=>array(
					'系统基本参数'=>array(
							'url'=>'admin/web_manager/index/site_config/',
							'参数设置'=>'admin/web_manager/index/site_config/',
							'生成数据文件'=>'admin/web_manager/index/make_data/',
						
					),
				
					
			),	
			'站长'=>array(//站长
					'后台管理团队'=>array(//后台管理团队
							'url'=>'admin/master/team/team_list/',
							'团队成员页面'=>'admin/master/team/team_list/',
							
							'团队成员删除处理'=>'admin/master/team/team_list_del/',
			
							'团队职务列表'=>'admin/master/team/team_duty/',
						
							'团队职务删除'=>'admin/master/team/team_duty_del_do/',
			
							'团队职务编辑'=>'admin/master/team/team_duty_edit/',
											
					),
				
			),
			'用户'=>array(//用户
					'用户列表'=>array(//添加用户
							'url'=>'admin/members/user/manager_users/',
							'用户列表'=>'admin/members/user/manager_users/',	
							'用户添加'=>'admin/members/user/user_add/',
							
													
					),
				
		
			),
			'后台操作log'=>array(
					'日志记录'=>array(
						'url'=>'admin/logs/log/log_list/',
						'日志记录列表'=>'admin/logs/log/log_list/',	
						'日志导出'=>'admin/logs/log/export/',
					),
				
					
					),
			'后台模块树'=>array(
					'模块树'=>array(
						'url'=>'admin/admin_tree/tree/tree_list/',
						'模块树列表'=>'admin/admin_tree/tree/tree_list/',
						
						'模块树修改'=>'admin/admin_tree/tree/tree_edit/',
					
						'模块树删除'=>'admin/admin_tree/tree/tree_del_do/',
						'生成数据文件(模块树)'=>'admin/admin_tree/tree/make_xml/',
					),
					),
			'后台发布公告'=>array(
					'发布公告'=>array(
						'url'=>'admin/admin_message/message/message_publish/',
						'发布公告'=>'admin/admin_message/message/message_publish/',
						
						),
					),
			'后台文件管理'=>array(
					'文件管理'=>array(
							'url'=>'admin/file_manager/file/file_list/',
							'文件列表'=>'admin/file_manager/file/file_list/',
							'文件上传'=>'admin/file_manager/file/upload_file/',
							'文件删除'=>'admin/file_manager/file/del_file/',
							'创建目录'=>'admin/file_manager/file/mkdir_dir/',
						),
					),
		),
	);	
}




/**
 * PHP判断字符串纯汉字 OR 纯英文 OR 汉英混合
 * return 1: 英文
 * return 2：纯汉字
 * return 3：汉字和英文
 */

function utf8_str($str){
    $mb = mb_strlen($str,'utf-8');
    $st = strlen($str);
    if($st==$mb)
        return 1;
    if($st%$mb==0 && $st%3==0)
        return 2;
    return 3;
}	

/**
 +----------------------------------------------------------
 * 字符串截取，支持中文和其他编码
 +----------------------------------------------------------
 * @static
 * @access public
 +----------------------------------------------------------
 * @param string $str 需要转换的字符串
 * @param string $start 开始位置
 * @param string $length 截取长度
 * @param string $charset 编码格式
 * @param string $suffix 截断显示字符
 * @param string $strength 字符串的长度
 +----------------------------------------------------------
 * @return string
 +----------------------------------------------------------
 */
function msubstr($str, $start=0, $length, $strength,$charset="utf-8", $suffix=true)
{
    if(function_exists("mb_substr")){
    	if($suffix){
    		if($length <$strength ){
    			return mb_substr($str, $start, $length, $charset)."....";
    		}else{
    			return mb_substr($str, $start, $length, $charset);
    		}   		
    	}else{
    		return mb_substr($str, $start, $length, $charset);
    	}

    	
    }elseif(function_exists('iconv_substr')) {
    	if($suffix){//是否加上......符号
    		if($length < $strength){
    			return iconv_substr($str,$start,$length,$charset)."....";
    		}else{
    			return iconv_substr($str,$start,$length,$charset) ;
    		}  		
    	}else{
    		return iconv_substr($str,$start,$length,$charset) ;
    	}

       
    }
    $re['utf-8']   = "/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|[\xe0-\xef][\x80-\xbf]{2}|[\xf0-\xff][\x80-\xbf]{3}/";
    $re['gb2312'] = "/[\x01-\x7f]|[\xb0-\xf7][\xa0-\xfe]/";
    $re['gbk']    = "/[\x01-\x7f]|[\x81-\xfe][\x40-\xfe]/";
    $re['big5']   = "/[\x01-\x7f]|[\x81-\xfe]([\x40-\x7e]|\xa1-\xfe])/";
    preg_match_all($re[$charset], $str, $match);
    $slice = join("",array_slice($match[0], $start, $length));
    if($suffix){
    	return $slice."…";
    } else{
    	return $slice;
    }
   
}


/**
 +----------------------------------------------------------
 * 字符串截取，支持中文和其他编码
 +----------------------------------------------------------
 * @static
 * @access public
 +----------------------------------------------------------
 * @param string $str 需要计算的字符串
 * @param string $charset 字符编码
 +----------------------------------------------------------
 * @return length int
 +----------------------------------------------------------
 */

function abslength($str,$charset= 'utf-8'){
    if(empty($str)){
        return 0;
    }
    if(function_exists('mb_strlen')){
        return mb_strlen($str,'utf-8');
    }
    else {
        @preg_match_all("/./u", $str, $ar);
        return count($ar[0]);
    }
}


 //
/**
 * 对象转化数组的形式
 * author wangjian
 * time 2013-04-21
 */ 
function object_to_array($object){
	$data = array();
	if($object){
		 foreach($object as $k=>$v){
			if(is_object($v)){
				$data[] = get_object_vars($v);
			}
		 }
	}

	return $data ;
}
//数组转换为一维数组
function arrayChange($str){
	static $arr2;
	foreach($str as $v){
		if(is_array($v)){
			$this->arrayChange($v);
		}else{
			$arr2[]=$v;
		}
	}
	return $arr2;
}
/**
 * 会员级别数组
 * author wangjian
 * time 2013-04-21
 * @is_format是否加上特殊的标签
 */ 
function member_level_array($is_format=true){
	if($is_format){
		return array(
				1=>'后台管理员<img src="'.base_url().'/static/image/admin/admin.gif">',
				0=>'普通会员<img src="'.base_url().'/static/image/admin/star_level1.gif">',
		);		
	}else{
		return array(
				1=>'后台管理员',
				0=>'普通会员',
		);
	}

}
/**
 * 会员状态
 * author wangjian
 * time 2013-04-21
 * @is_format是否加上特殊的标签
 */ 
function member_status_array($is_format=true){
	if($is_format){
		return array(
				1=>'<font color="green">有效</font>',
				0=>'<font color="red" >过期</font>',
				2=>'<font color="red" >删除</font>',
		);
	}else{
		return array(
				1=>'有效',
				0=>'过期',
				2=>'删除',
		);
	}

}
/**
 * 得到会员状态
 * author wangjian
 * time 2013-04-21
 */ 
function get_member_status($status){
	$array =  $this->member_status_array();
	return $array[$status];
}
/**
 * 得到会员级别
 * author wangjian
 * time 2013-04-21
 * @format 是否得到格式化的值
 */ 
function get_member_level($level,$format=false){
	$array =  $this->member_level_array($format);
	return $array[$level];
}

/**
 * 团队职务状态数组
 * author wangjian
 * time 2013-05-09
 * @is_format是否加上特殊的标签
 */ 
function duty_array($is_format=true){
	if($is_format){
		return array(
				1=>'<font color="green">开启</font>',
				0=>'<font color="red" >关闭</font>',
				
		);
	}else{
		return array(
				1=>'开启',
				0=>'关闭',
				
		);
	}
}
/**
 * 得到会员级别
 * author wangjian
 * time 2013-04-21
 * @status 状态
 * @format 是否得到格式化的值
 */
function get_duty_status($status,$format=false){
	$array =  $this->duty_array($format);
	return $array[$status];
}
//重写json_encode方法
 function result_to_towf_new($vDataResult, $ret,$errmsg,$sigInfo){
	$result_arr = array();
	$result_arr["resultcode"] = (string)$ret;
	$tmp_arr["errmsg"] = $errmsg;
	$tmp_arr["obj"] = $sigInfo;
	$vResult = array();
	$tmp_arr["list"] = $vDataResult;
	$result_arr["resultinfo"] =  $tmp_arr;

	return json_encode($result_arr);
}
//获取客户端的IP地址
function get_client_ip(){
	if (getenv("HTTP_CLIENT_IP") && strcasecmp(getenv("HTTP_CLIENT_IP"), "unknown")){
		$ip = getenv("HTTP_CLIENT_IP");
	}else if (getenv("HTTP_X_FORWARDED_FOR") && strcasecmp(getenv("HTTP_X_FORWARDED_FOR"), "unknown")){
		$ip = getenv("HTTP_X_FORWARDED_FOR");
	}else if (getenv("REMOTE_ADDR") && strcasecmp(getenv("REMOTE_ADDR"), "unknown"))
		$ip = getenv("REMOTE_ADDR");
	else if (isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], "unknown"))
		$ip = $_SERVER['REMOTE_ADDR'];
	else
		$ip = "unknown";
	return($ip);
}
//获取登录的用户名
function login_name(){
	@session_start();
	@ob_clean() ;
	if(isset($_SESSION['username'])){
		return $_SESSION['username'];
	}else{
		return '' ;
	}
	
}
//获取登录的用户所在的群组
function group_name(){
	@ob_clean() ;
	@session_start() ;
	if(isset($_SESSION['group_name'])){
		return $_SESSION['group_name'];
	}else{
		return '' ;
	}
	
}

//定义操作动作对应的中文
function return_action_array(){
	return array(
			'log_update_add_admin'=>'添加管理员',
			'log_update_del_admin'=>'删除管理员',
			'log_add_duty'=>'添加职务',
			'log_del_duty'=>'删除职务',
			'log_update_permition'=>'修改职务对应的权限',
			'log_add_user'=>'添加用户',
			'log_add_tree'=>'添加模块树',
			'log_update_tree'=>'修改模块树',
			'log_delete_tree'=>'删除模块树',
			'log_publish_message'=>'投递公告',
			'log_mkdir_dir'=>'创建目录',
			'log_delete_file'=>'删除文件/目录',
			'log_upload_file'=>'上传文件',
	
			'system_setting'=>'系统基本参数设置',
			'system_setting_make_data'=>'系统参数生成数据文件',
	);
}

//根据操作动作返回对应的字符串
function get_log_action_str($action){
	$array = $this->return_action_array() ;
	return $array[$action];
}

//function 
####################################
#返回模块树对应的类型
####################################
#备注此处的key值必须是数字，因为数据库中type对应的字段类型是int型的
function return_treetype_list(){
	return array(
		1=>'图书',
		2=>'商品',
		3=>'家电',
		4=>'食品',	
		5=>'视频',
		6=>'图片',
		7=>'图标',	
	);
}

//得到模块类别对应的中文名称
/*
 * $type 类别
 */
function get_tree_type($type){
	$type_array = $this->return_treetype_list();
	return $type_array[$type];
}

/*
 *return table 前缀 
 * 
 */
function table_pre($group){
	$table_pre = '' ;
	if($group){
		$ci = &get_instance(); //初始化 为了用方法
		$d = $ci->load->database('real_data',true);
		$table_pre =  $d->table_pre;
	}
	return $table_pre ;
	
}





}