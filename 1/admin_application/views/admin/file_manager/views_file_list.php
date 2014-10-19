<?php 
if (! defined('BASEPATH')) {
	exit('Access Denied');
}

?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>文件管理---数据处理平台</title>
<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>/static/css/admin/base.css">
<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>/static/css/admin/page.css">
<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>/static/css/admin/window.css" />
<style type="text/css">

</style>
<script src="<?php echo base_url();?>/static/js/jquery.js" language="javascript"></script>
<script type="text/javascript" src="<?php echo base_url();?>/static/js/admin/tipswindown.js"></script>
<script language="javascript">
function selAll()
{
	for(i=0;i<document.form2.id.length;i++)
	{
		if(!document.form2.id[i].checked)
		{
			document.form2.id[i].checked=true;
		}
	}
}
function noSelAll()
{
	for(i=0;i<document.form2.id.length;i++)
	{
		if(document.form2.id[i].checked)
		{
			document.form2.id[i].checked=false;
		}
	}
}


</script>
 
<script language="javascript">
var path = '' ;
var s_re = '' ;

$(function(){
	common_request('');
	$("#file_name").keyup(function(){	
		Search_file(); //调用查找文件
	});
	

});

function Search_file(){
	var input = $.trim($("#file_name").val()) ;   //输入的文件名字
	var temp_search = {} ;
	var _data_str = '[' ;
	for(var i in s_re){
		if(isFileMatch(s_re[i].filename,input)){
			//alert(s_re[i].filename);
			_data_str+='{';
			_data_str += '"filename":' + '"'+s_re[i].filename+'",';
			_data_str += '"date":' + '"'+s_re[i].date+'",';
			_data_str += '"fileimage":' +'"'+ s_re[i].fileimage+'",';
			_data_str += '"isdir":' + s_re[i].isdir+',';
			_data_str += '"perms":' +'"'+ s_re[i].perms+'",';
			_data_str += '"size":' +'"'+ s_re[i].size+'",'
			_data_str = _data_str.substring(0,(_data_str.length-1));
			_data_str+='},';
		}
		//_data_str = _data_str.substring(0,(_data_str.length-1));
	} 
	
	_data_str+=']';
	temp_search = eval(_data_str);
	Success_callback_(temp_search);
	
}

//从后台查询信息
function common_request(dir){	
	var url="<?php echo site_url("admin/file_manager/file/file_list");?>?inajax=1";
	
	$.ajax({
		   type: "POST",
		   url: url ,
		   data: "action=show_list&time=<?php echo time();?>&dir="+dir,
		   cache:false,
		   dataType:"json",
		 //  async:false,
		   success: function(msg){
			
			var code = msg.resultcode ;
			if(code <0){
				window.location.href="<?php echo site_url('admin/login/jump_permition_html');?>" ;
				return false ;
			}  
			
			result = msg.resultinfo.list.result ;
			s_re = clone(result);
			$("#folder_num").html(msg.resultinfo.obj.folder_num);
			$("#file_num").html(msg.resultinfo.obj.file_num);
			Success_callback_(result);
			
		   },
		   beforeSend:function(){
			  $("#ajax_wait").html('<font color="red">正在查询请稍后。。</font>');
		   },
		   error:function(){
			   alert('服务器繁忙,请稍候....');
		   }
		  
		});		
	

}
/*
 * 
 */
function Success_callback_(result){
	var shtml = '';
	var site_url = "<?php echo base_url() ;?>/static/image/admin/" ;
	if(result){
			
			for(var i in result){
				
				var image = site_url+result[i].fileimage ;
				var onclick_get_size = '' ;
				var onclick_open_dir = '';
				var onclick_del_file = '' ;
				if(result[i].isdir){
					//如果是目录
					 onclick_get_size = "onclick=get_dir_size(\""+result[i].filename+"\","+i+")" ;
					 result[i].size = '<font color="red" style="cursor:pointer" id=get_'+i+'>获取大小</font><span id=span_'+i+'></span>';
					 onclick_open_dir = "style='cursor:pointer' onclick=get_folderr_info(\""+result[i].filename+"\","+i+")" ; 
					 onclick_del_file ="onclick=del_file(\""+result[i].filename+"\","+i+",'dir')" ; 
				}else{
						//不是目录
					onclick_del_file ="onclick=del_file(\""+result[i].filename+"\","+i+",'file')" ; 
					
					onclick_open_dir = "style='cursor:pointer' onclick=preview_file(\""+result[i].filename+"\")" ; 
				}
							
				shtml+='<tr height="22" bgcolor="#FFFFFF" align="center" onmouseout="javascript:this.bgColor=\'#FFFFFF\';" onmousemove="javascript:this.bgColor=\'#FCFDEE\';">';
				shtml+='<td>'+i+'</td>';
				shtml+='<td><input id="id" class="np" type="checkbox" value="'+i+'" name="id"></td>';
				shtml+='<td '+onclick_open_dir+'><img src='+image+'><a href="javascript:void(0)" >'+result[i].filename+'</a></td>';
				shtml+='<td '+onclick_get_size+'>'+result[i].size+'</td>';
				shtml+='<td><font color="green">'+result[i].date+'</font></td>';
				shtml+='<td>'+result[i].perms+'</td>';
				shtml+='<td><a href="101"></a><a href="javascript:void(0)" '+onclick_del_file+' id="del_'+i+'" ><img src="<?php echo base_url() ;?>/static/image/admin/gtk-del.png"></a></td>';					
			}
	}
	
	$("#result_p").html(shtml);
	$("#ajax_wait").html(' ');
	
}

//获取文件夹的大小
function get_dir_size(file,i){
	var url = "<?php echo site_url('admin/file_manager/file/file_list');?>?inajax=1" ;
	var p = path+"/"+file ;

	$.ajax({
		   type: "POST",
		   url: url ,
		   data: "time=<?php echo time();?>&action=get_dir_size&path="+p,
		   cache:false,
		   dataType:"json",
		   async:true,//异步请求
		   success: function(msg){
			   var code = msg.resultcode ;
			   var message = msg.resultinfo.errmsg ;
			   if(code <0){
					window.location.href="<?php echo site_url('admin/login/jump_permition_html');?>" ;
					return false ;
				}else if(code == 0){
					$("#span_"+i).html(message);
					$("#get_"+i).hide();
				}else{
					$("#span_"+i).html("<font color='green'><b>"+msg.resultinfo.list.size+"</b></font>");
					$("#get_"+i).hide();
				}  

				
		   },
		   beforeSend:function(){
			   $("#span_"+i).html("<font color='red'><b>正在查询请稍候....</b></font>");
		   },
		   error:function(){
			   alert('服务器繁忙');
		   }
		  
		});		
	
}

//获取目录下面的信息
function get_folderr_info(file){
	if(path == '/'){
		path = path+file ;
	}else{
		path = path+"/"+file ;
	}
	
	$("#cur_path").html(path);
	common_request(path);
}
//删除文件
function del_file(file,i,type){
	if(!confirm('是否确定删除文件'+file)){
			return false ;
	}
	
	var newp = '' ;
	if(path == '/'){
		newp = path+file ;
	}else{
		newp = path+"/"+file ;
	}
	
	var url="<?php echo site_url('admin/file_manager/file/del_file') ;?>";
	$.ajax({
		   type: "POST",
		   url: url ,
		   data: "time=<?php echo time();?>&path="+newp+"&type="+type,
		   cache:false,
		   dataType:"json",
		 //  async:false,
		   success: function(msg){
			   var code = msg.resultcode ;
			   var message = msg.resultinfo.errmsg ;
			  if(code < 0){
					//没有权限进行此操作
				  window.location.href="<?php echo site_url('admin/login/jump_permition_html');?>" ;
					return false ;
				}else if(code >= 1){//成功
					common_request(path);
					return false ;
				}else{
					alert(message);
					$("#del_"+i).show();
					return false ;
				}
				
		   },
		   beforeSend:function(){
			   $("#ajax_wait").html('<font color="red">正在删除请稍候。。</font>');
			   $("#del_"+i).hide();
		   },
		   error:function(){
			   alert('服务器繁忙');
		   }
		  
		});		
	//common_request(path);
//	alert(newp);
}
//得到上一级目录名称
function get_cur_path(){
	var newpath = '' ;
	if(path){
		var path_array =  new Array;
		path_array = path.split("/");
		for(var i =0 ;i<path_array.length ;i++){
		
			if(i == (path_array.length-1) ){
				continue ;
			}
			newpath=newpath+path_array[i]+"/" ;
		}
		newpath = newpath.substring(0,(newpath.length-1));
		if(newpath == ''){
			newpath = '/' ;
		}
	}

	return newpath ;
}

//跳转到上一级目录
function jump_to_dir(){
	var cur_p = get_cur_path();
	
	$("#cur_path").html(cur_p);
	path = cur_p ;
	common_request(cur_p);
}
//克隆对象
function clone(myObj){
	  if(typeof(myObj) != 'object') return myObj;
	  if(myObj == null) return myObj;
	  
	  var myNewObj = new Object();
	  
	  for(var i in myObj)
	    myNewObj[i] = clone(myObj[i]);
	  
	  return myNewObj;
}

/*
 * 查找字符串
 * filename 文件
 * input 包含的字符
 */

function isFileMatch(filename,input)
{
	var pattern = eval("\/"+input+"\/");
	var chartText = filename;
	var count_pat=chartText.match(pattern);
	if(count_pat==null)
	 return 0;
	else
	 return 1;
}

//创建目录
function create_dir(){
	tipsWindown("创建目录","iframe:<?php echo site_url('admin/file_manager/file/mkdir_dir')?>?path="+path,550,200,"true","","true","create_dir__");
}
//预览文件
function preview_file(file){
	var newp = '' ;
	if(path == '/'){
		newp = path+file ;
	}else{
		newp = path+"/"+file ;
	}

	tipsWindown("文件预览","iframe:<?php echo site_url('admin/file_manager/file/file_list')?>?action=preview&path="+newp,650,400,"true","","true","preview_file_");
}


</script>
<script type="text/javascript" src="<?php echo base_url();?>/static/js/swfupload/swfupload/swfupload.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>/static/js/swfupload/js/swfupload.queue.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>/static/js/swfupload/js/fileprogress.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>/static/js/swfupload/js/handlers.js"></script>
<script type="text/javascript">
		var swfu;
		var settings = {} ;
		
		window.onload = function() {
			 settings = {
				flash_url : "<?php echo base_url() ;?>/static/js/swfupload/swfupload/swfupload.swf",
				upload_url: "<?php echo site_url('admin/file_manager/file/upload_file') ;?>?inajax=1",
				post_params: {"session":"<?php echo session_id() ;?>"},
				file_size_limit : "1000 MB",
				file_types : "*.*",
				file_types_description : "All Files",
				file_upload_limit : 100,
				file_queue_limit : 0,
				custom_settings : {
				progressTarget : "fsUploadProgress",
					cancelButtonId : "btnCancel"
				},
				debug: false,

				// Button settings
				//button_image_url: "/js/swfupload/images/TestImageNoText_65x29.png",
				button_image_url:'<?php echo base_url() ;?>/static/js/swfupload/images/bg.png',
				button_width: "63",
				button_height: "20",
				button_placeholder_id: "spanButtonPlaceHolder",
				button_text: '<a class="theFont">批量上传</a>',
				button_text_style: ".theFont{ font-size: 12; color:black ; cursor:pointer;text-align:center}",
				button_text_left_padding: 0,
				button_text_top_padding: 0,
				
				// The event handler functions are defined in handlers.js
				file_queued_handler : fileQueued,
				file_queue_error_handler : fileQueueError,
				file_dialog_complete_handler : fileDialogComplete,
				upload_start_handler : uploadStart,
				upload_progress_handler : uploadProgress,
				upload_error_handler : uploadError,
				upload_success_handler : success_data,
				upload_complete_handler : uploadComplete,
				queue_complete_handler : queueComplete	// Queue plugin event
			};

			swfu = new SWFUpload(settings);
	     };
		 
		 function uploadComplete(){
			//alert(3);
		 }
		 function uploadStart(){
			
			 swfu.addPostParam("SubPath",path);
			 
		 }
		 function success_data(file, serverData) {
			   var obj = $.parseJSON(serverData);
				var code = obj.resultcode ;
				var p = obj.resultinfo.list.filepath ;
				var message =  obj.resultinfo.errmsg ;
				if(code < 0){
					//没有权限进行此操作
					alert("对不起你没有权限进行此操作");
					return false ;
				}else if(code >= 1){//成功
					//get_folderr_info(p);
					
					common_request(p);
					
					return false ;
				}else{
					alert(message);
					return false ;
				}
				
		}
		
		
</script>

</head>
<body leftmargin="0" topmargin="0">
<div class="nav_des">
	<div style="float: left">
	<span class="des">文件>></span>
	
	<a href="<?php echo site_url("admin/file_manager/file/file_list");?>" class="nav_des_hover">文件列表</a>
	</div>
	<div id="ajax_wait" style="float:right"></div>
</div>

<!--  搜索表单  --> 	
<form name='form3' onsubmit="return false ">
<table width='100%'  border='0' cellpadding='1' cellspacing='1' bgcolor='#CBD8AC' align="center" style="margin-top:8px">
  <tr bgcolor='#EEF4EA'>
    <td background='<?php echo base_url();?>/static/image/admin/wbg.gif' align=''>
      <table border='0' cellpadding='0' cellspacing='0'>
        <tr>
          <td width='90' align='center'>搜索文件：</td>
          <td width='100'>
       		<input type="text" name="file_name" id="file_name">
        </td>
   	   <td width='50'>
       		&nbsp;
        </td> 
       <td width='200'>
       		<a style="cursor:pointer"  id="spanButtonPlaceHolder"></a>
        </td>   
      <td width='100'>
       		<a href="javascript:void(0)" onclick="create_dir()">创建目录</a>
        </td>   
       </tr>
      </table>
    </td>
  </tr>
</table>
</form>
<input type="hidden" name="path" value="" id="path">

<!--  内容列表   -->
<form name="form2">

<table width="100%" border="0" cellpadding="2" cellspacing="1" bgcolor="#D1DDAA" align="center" style="margin-top:8px">
<tr bgcolor="#E7E7E7">
	<td height="24" colspan="10" >&nbsp;文件管理 &nbsp;&nbsp;<img src="<?php echo base_url();?>/static/image/admin/up_dir.gif" ><a href="javascript:void(0)" onclick="jump_to_dir()">上级目录</a>
	&nbsp;<img src="<?php echo base_url();?>/static/image/admin/file_dir.gif" >(<span id="folder_num"></span>)&nbsp;<img src="<?php echo base_url();?>/static/image/admin/txt.gif">(<span id="file_num"></span>)
	&nbsp;当前目录:<span id="cur_path">/</span>&nbsp;
	</td>
</tr>
<tr align="center" bgcolor="#FAFAF1" height="22">
	<td width="6%">ID</td>
	<td width="4%">选择</td>
	<td width="8%">文件名</td>
	<td width="10%">文件大小</td>
	<td width="10%">最后修改日期</td>
	<td width="8%">文件权限</td>

	<td width="10%">操作</td>
</tr>
<tbody id="result_p">

</tbody>
<tr bgcolor="#FAFAF1">
<td height="28" colspan="7">
	&nbsp;
	<a href="javascript:selAll()" class="coolbg">全选</a>
	<a href="javascript:noSelAll()" class="coolbg">取消</a>
</td>
</tr>
<!-- 
<tr align="right" bgcolor="#EEF4EA">

	<td height="36" colspan="7" align="right" id="mynew_page">
	
	</td>
</tr> -->
</table>

</form>
<div style="display:none">			
		<div id="divStatus">0 Files Uploaded</div>
			<div>
				
				<input id="btnCancel" type="button" value="Cancel All Uploads" onclick="swfu.cancelQueue();" disabled="disabled" style="margin-left: 2px; font-size: 8pt; height: 29px;" />
			</div>
</div>

<!-- 创建目录 -->
<div id="create_dir" style="display:none">
	<div style="margin:4px; padding:2px">目录名称:<input type="text" name="" id="dir_name_">备注:请输入字母数字和下划线 暂不支持中文字符</div>
	<div style="margin:5px"><input type="button" value="创建" id="create_dir_" style="width:80px; text-align:center" onclick="do_create_dir()"></div>
</div>
<!-- 创建目录 -->

</body>
</html>