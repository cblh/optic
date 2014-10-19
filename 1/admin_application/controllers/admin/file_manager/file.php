<?php
/*
 * 文件管理
* author wangjian
* time 2013-06-28
*
*/
if (! defined('BASEPATH')) {
	exit('Access Denied');
}

class File extends MY_Controller{
	private $username ;//当前登录的用户
	private $ip ; //登录的ip地址
	private $table_ ; //表的前缀
	private $dir ;

	function File(){
		parent::__construct();
		$this->load->library('admin_common');//加载admin的公用方法
		date_default_timezone_set('Asia/Shanghai');
		$this->load->model('admin/M_log');//加载日志模型
		$this->username = $this->get_login_name(); //得到登录的用户名
		$this->ip = $this->admin_common->get_client_ip();
		$this->dir = $this->get_dir_path() ; //得到根目录
		
	}
	
	function file_list(){
		$post_data = $_REQUEST ;		
		$action_array =array('show_list','view_list','get_dir_size','search_file','preview');
		
		$action = '' ;
		$folder_num = 0 ;
		$file_num = 0 ;
		$folder_array = array();//文件夹的详细信息
		$data = array();
		if(isset($post_data['action']) && in_array($post_data['action'],$action_array)){
			$action = $post_data['action'];
		}else{
			$action = 'view_list' ;
		}
		if($action == 'get_dir_size'){
			$this->query_dir_size() ; //查询目录的大小
			die();
		}
		if($action == 'preview'){
			
			$this->show_preview_file();
			
			
		}
			
		if(isset($post_data['dir']) && $post_data['dir'] != ""){
			$folder_array = $this->getFolderInfo($post_data['dir']) ;
			
		}else{
			$folder_array = $this->getFolderInfo() ;
		}
		$data = $folder_array['files'];
		
		$json_data = array(
				'result'=>$data,
				
		);
		if($action == 'view_list'){
			
			$this->load->view('admin/file_manager/views_file_list',$json_data);
		}elseif($action == 'show_list'){
			echo $this->admin_common->result_to_towf_new($json_data,1,'',array('folder_num'=>$folder_array['folder_num'],'file_num'=>$folder_array['file_num']));
		}
		
		
	}
	
	//查询目录的大小函数
	private function query_dir_size(){
		$post_data = $this->input->post(NULL,true) ;
		$size = 0 ;
		$code = 0 ;
		$message = '';
		$data  =array();
		if(isset($post_data['path']) && is_dir($this->dir.$post_data['path'])){
			
			$size = $this->getRealSize($this->getDirSize($this->dir.$post_data['path']));
			$code = 1 ;
			$message = '查询成功' ;
		}else{
			$code = 0 ;
			$message = '参数传递错误，请传递正确的目录' ;
		}
		$data['size'] = $size;
		echo $this->admin_common->result_to_towf_new($data,$code,$message,null);
	}
	
	//得到目录详细信息
	/*
	 *@dir 目录名称
	 *@folder_num 文件夹的数量
	 *@file_num 文件的数量
	 */
	private function getFolderInfo($dir = ''){
		$folder_num = 0 ;
		$file_num = 0 ;
		if(strripos(substr($dir, -1), "/") === false){
			$dir = $dir."/" ;
		}
		
		$dir =$this->dir.$dir;
		
		$fp = opendir($dir);
		$files = array();
	
		
		$vsize = '' ;
		$site_url = base_url()."/static/image/admin/" ;
		while(false!=$file=readdir($fp)){
				
			if(!in_array($file, array('.','..'))){
		
				$perm =  $this->GetFilePerms($dir.$file );  //文件的权限
				$isdir = is_dir($dir.$file); //判断是目录或者是文件
				$vtime =filemtime($dir.$file);

				($isdir)?($isdir=1):($isdir=0) ;
				
			    if(!$isdir){
			    	//echo $dir.$file ;
			    //	echo '<hr>';
			    	$vsize = $this->getRealSize(sprintf("%u",filesize($dir.$file)));//文件的大小 ,手册上这种写法避免文件过大获取错误
			    	$image = 'txt.gif' ;
			    	$array_file_type = array();
			    	$array_file_type = explode(".",$file);
			    	if(isset($array_file_type[1]) && strtoupper($array_file_type[1] == 'php') ){
			    		
			    		$image = 'php.gif';
			    	}
			    	$file_num++ ;//文件数量自增
			    }else{
			    	$vsize = '' ;
			    	$image = "file_dir.gif" ;
			    	$folder_num++; //文件夹数量自增
			    }
				
				$files[]=array(
						'filename'=>$file,
						'size'=>$vsize,
						'date'=>date("Y-m-d H:i:s",$vtime),
						'perms'=>$perm,
						'fileimage'=>$image,
						'isdir'=>$isdir
							
				);
				
		
			}
			
			
		}
		return array('files'=>$files,'folder_num'=>$folder_num,'file_num'=>$file_num); ;
	}
	//得到当前要遍历的文件夹目录
	private function get_dir_path(){
		$path = str_replace("\\", "/", dirname(__FILE__) );
		list($dir,$last) = @explode(APPPATH, $path);		
		if( substr($dir, -1) != '/'){
			$dir.='/';
		}
		return $dir ;
	}
	//得到目录的大小（递归进行调用）
	private function getDirSize($dir){ 
		
		$sizeResult =  0 ;
        $handle = opendir($dir);
        while (false!==($FolderOrFile = readdir($handle)))
        { 
            if($FolderOrFile != "." && $FolderOrFile != "..")
            { 
                if(is_dir("$dir/$FolderOrFile"))
                { 
                    $sizeResult += $this->getDirSize("$dir/$FolderOrFile"); 
                }
                else
                { 
                	
                    $sizeResult += sprintf("%u",filesize("$dir/$FolderOrFile")); 
                    
                }
            }    
        }
        closedir($handle);
        return $sizeResult;
    }
 
    // 单位自动转换函数
   private function getRealSize($size)
    {
    	$kb = 1024;         // Kilobyte
    	$mb = 1024 * $kb;   // Megabyte
    	$gb = 1024 * $mb;   // Gigabyte
    	$tb = 1024 * $gb;   // Terabyte
    
    	if($size < $kb)
    	{
    		return $size." B";
    	}
    	else if($size < $mb)
    	{
    		return round($size/$kb,2)." KB";
    	}
    	else if($size < $gb)
    	{
    		return round($size/$mb,2)." MB";
    	}
    	else if($size < $tb)
    	{
    		return round($size/$gb,2)." GB";
    	}
    	else
    	{
    		return round($size/$tb,2)." TB";
    	}
    }
    
	//读取文件权限
	private function GetFilePerms($fpath) {
	
		$perms = fileperms($fpath);
	
		if (($perms & 0xC000) == 0xC000) {
			$info = 's';
		} elseif (($perms & 0xA000) == 0xA000) {
			$info = 'l';
		} elseif (($perms & 0x8000) == 0x8000) {
			$info = '-';
		} elseif (($perms & 0x6000) == 0x6000) {
			$info = 'b';
		} elseif (($perms & 0x4000) == 0x4000) {
			$info = 'd';
		} elseif (($perms & 0x2000) == 0x2000) {
			$info = 'c';
		} elseif (($perms & 0x1000) == 0x1000) {
			$info = 'p';
		} else {
			$info = 'u'; //Unknown
		}
		// Owner
		$info .= (($perms & 0x0100) ? 'r' : '-');
		$info .= (($perms & 0x0080) ? 'w' : '-');
		$info .= (($perms & 0x0040) ?
				(($perms & 0x0800) ? 's' : 'x' ) :
				(($perms & 0x0800) ? 'S' : '-'));
	
		// Group
		$info .= (($perms & 0x0020) ? 'r' : '-');
		$info .= (($perms & 0x0010) ? 'w' : '-');
		$info .= (($perms & 0x0008) ?
				(($perms & 0x0400) ? 's' : 'x' ) :
				(($perms & 0x0400) ? 'S' : '-'));
		// World
		$info .= (($perms & 0x0004) ? 'r' : '-');
		$info .= (($perms & 0x0002) ? 'w' : '-');
		$info .= (($perms & 0x0001) ?
				(($perms & 0x0200) ? 't' : 'x' ) :
				(($perms & 0x0200) ? 'T' : '-'));
		return  $info;
	}
	//上传文件
	function upload_file(){
		//	
			
		$data = '' ;
		$MAX_SIZE = 2000000;
		$post_data = $this->input->post(NULL,true) ;
		$post_dir = '' ;
		if(isset($post_data['SubPath']) && $post_data['SubPath'] != ""){
			$post_dir = $post_data['SubPath'] ;
			if(strripos(substr($post_dir, -1), "/") === false){
				$post_dir = $post_dir."/" ;
			}
			if(strripos(substr($post_dir, 0,1),"/") !== false ){
				$post_dir = substr($post_dir, 1);
			}
		}
		$dir  = $this->dir.$post_dir ;
		
		$file_path = $dir . $_FILES['Filedata']['name'];
		
		
		if(!is_dir($dir)){
			$this->M_log->insert("no_upload_sql",'log_upload_file',$this->username,$this->ip,0,"上传的文件夹{$dir}不存在"); //插入日志记录
			echo $this->admin_common->result_to_towf_new($data,0,'上传的文件夹不存在',null);
			die();
		}
		if(!is_really_writable($dir)){
			$this->M_log->insert("no_upload_sql",'log_upload_file',$this->username,$this->ip,0,"文件夹{$dir}权限不足"); //插入日志记录
			echo $this->admin_common->result_to_towf_new($data,0,'文件夹权限不足',null);
			die();
		}
		if($_FILES['Filedata']['size']>$MAX_SIZE){
			$this->M_log->insert("no_upload_sql",'log_upload_file',$this->username,$this->ip,0,"上传的文件{$_FILES['Filedata']['name']}大小超过了规定大小$MAX_SIZE"); //插入日志记录
			echo $this->admin_common->result_to_towf_new($data,0,"上传的文件{$_FILES['Filedata']['name']}大小超过了规定大小$MAX_SIZE",null);
			exit;
		}
		if($_FILES['Filedata']['size'] == 0){
			$this->M_log->insert("no_upload_sql",'log_upload_file',$this->username,$this->ip,0,"请选择上传的文件"); //插入日志记录
			echo $this->admin_common->result_to_towf_new($data,0,'请选择上传的文件',null);
			exit;
		}
		if(!move_uploaded_file($_FILES['Filedata']['tmp_name'], $file_path))
		{
			$this->M_log->insert("no_upload_sql",'log_upload_file',$this->username,$this->ip,0,"上传文件{$file_path}失败请重新上传"); //插入日志记录
			echo $this->admin_common->result_to_towf_new($data,0,'上传文件失败请重新上传',null);
			exit;
		}
		
		$result = array('filepath'=>$post_dir);
		$this->M_log->insert("no_upload_sql",'log_upload_file',$this->username,$this->ip,1,"上传{$file_path}成功"); //插入日志记录
		echo $this->admin_common->result_to_towf_new($result,1,'上传成功',null);
		die();
	}
	

	//删除文件
	public function del_file(){
		$type_array = array('file','dir');
		$result = array();
		$post_data = $this->input->post(NULL,true) ;
		$p_path = '' ;
		$type = '' ;
		if(isset($post_data['path']) && $post_data['path'] != ""){
			$p_path = $post_data['path'];
		}
		if(isset($post_data['type']) && $post_data['type'] != ""){
			$type = $post_data['type'];
		}
		if(!in_array($type, $type_array)){
			$this->M_log->insert("no_delete_sql",'log_delete_file',$this->username,$this->ip,0,"目录和文件类型参数传递错误"); //插入日志记录
			echo $this->admin_common->result_to_towf_new($result,0,'目录和文件类型参数传递错误',null);
			die();
		}
		if($type == 'dir'){
			//目录
			if(!is_dir($this->dir.$p_path)){
				$this->M_log->insert("no_delete_sql",'log_delete_file',$this->username,$this->ip,0,"你要删除的目录不存在,或者不是目录"); //插入日志记录
				echo $this->admin_common->result_to_towf_new($result,0,'你要删除的目录不存在,或者不是目录',null);
				die();
			}
			$d_status = @rmdir($this->dir.$p_path);
			if($d_status){
				$this->M_log->insert("no_delete_sql",'log_delete_file',$this->username,$this->ip,1,"删除目录{$p_path}成功"); //插入日志记录
				echo $this->admin_common->result_to_towf_new($result,1,'删除目录成功',null);
				die();
			}else{
				$this->M_log->insert("no_delete_sql",'log_delete_file',$this->username,$this->ip,0,"删除目录{$p_path}失败，有可能文件夹不是空，或者你没权限"); //插入日志记录
				echo $this->admin_common->result_to_towf_new($result,0,'删除目录失败，有可能文件夹不是空，或者你没权限',null);
				die();
			}
			
		}else if($type == 'file'){//文件删除
			if(!file_exists($this->dir.$p_path)){
				$this->M_log->insert("no_delete_sql",'log_delete_file',$this->username,$this->ip,0,"你要删除的文件{$p_path}不存在"); //插入日志记录
				echo $this->admin_common->result_to_towf_new($result,0,'你要删除的文件不存在',null);
				die();
			}
			$status = @unlink($this->dir.$p_path);
			if($status){
				$this->M_log->insert("no_delete_sql",'log_delete_file',$this->username,$this->ip,1,"删除{$p_path}成功"); //插入日志记录
				echo $this->admin_common->result_to_towf_new($result,1,'删除成功',null);
				die();
			}else{
				$this->M_log->insert("no_delete_sql",'log_delete_file',$this->username,$this->ip,0,"删除{$p_path}失败，请检查文件的权限"); //插入日志记录
				echo $this->admin_common->result_to_towf_new($result,0,'删除失败，请检查文件的权限',null);
				die();
			}
		}
		
	}
	//创建目录
	public function mkdir_dir(){
		$post_data = $this->input->post(NULL,true) ;
		$action = '' ;
		$action_array = array('do_create','dir_page');
		if(isset($post_data['action']) && $post_data['action'] != ""){
			$action = $post_data['action'] ;
		}
		if(!in_array($action, $action_array)){
			$action = 'dir_page' ;
		}
		if($action == 'dir_page'){
			$this->load->view('admin/file_manager/views_mkdir_dir');
		}elseif($action == 'do_create'){
			$this->do_create_dir();
		}
		
	}
	//处理创建目录
	private function do_create_dir(){
		$post_data = $this->input->post(NULL,true) ;
		$result = array() ;
		$dir_name = '' ;
		if(!isset($post_data['dirname']) && $post_data['dirname'] == ""){
			$this->M_log->insert("no_create_dir",'log_mkdir_dir',$this->username,$this->ip,0,"目录名称不可以为空"); //插入日志记录
			echo $this->admin_common->result_to_towf_new($result,0,'目录名称不可以为空',null);
			die();
		}else{
			$dir_name = $post_data['dirname'] ;
		}
		$new_p = '' ;
		if(isset($post_data['path']) && $post_data['path'] != ""){
			$new_p = $post_data['path'] ;
		}
		if(is_dir($this->dir.$new_p)){
			if(is_really_writable($this->dir.$new_p)){
				if(@mkdir($this->dir.$new_p."/".$dir_name, 0700)){
					$result = array('filepath'=>$new_p);
					$this->M_log->insert("no_create_dir",'log_mkdir_dir',$this->username,$this->ip,1,"添加目录{$new_p}成功"); //插入日志记录
					echo $this->admin_common->result_to_towf_new($result,1,'创建目录'+$dir_name."成功",null);
					die();
				}else{
					$this->M_log->insert("no_create_dir",'log_mkdir_dir',$this->username,$this->ip,0,"添加目录{$new_p}失败,可能目录没有权限"); //插入日志记录
					echo $this->admin_common->result_to_towf_new($result,0,'创建目录失败，可能目录没有权限',null);
					die();
				}
		
			}else{
				$this->M_log->insert("no_create_dir",'log_mkdir_dir',$this->username,$this->ip,0,"添加目录{$new_p}失败,可能目录没有权限"); //插入日志记录
				echo $this->admin_common->result_to_towf_new($result,0,'创建目录失败，可能目录没有权限',null);
				die();
			}
		}
		
	}
	//预览文件
	private function show_preview_file(){
		$data_ = $_REQUEST ;
		$file_data = '' ;
		if(file_exists($this->dir.$data_['path'])){
			
			if(filesize($this->dir.$data_['path']) > 2*1024*1024){
				$file_data = '你不可查看大于2M的文件' ;
			}else{
				$file_data =  @file_get_contents($this->dir.$data_['path']);
			}
		}
		$view_data['file'] = $file_data ;
		$this->load->view('admin/file_manager/views_preview_file',$view_data);
	
	}
}