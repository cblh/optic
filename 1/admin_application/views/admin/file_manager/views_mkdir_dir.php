<?php 
if (! defined('BASEPATH')) {
	exit('Access Denied');
}
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>文件目录添加</title>
<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>/static/css/admin/base.css">
<script src="<?php echo base_url();?>/static/js/jquery.js" language="javascript"></script>
<script src="<?php echo base_url();?>/static/js/validate/validator.js" language="javascript"></script>

<script type="text/javascript" src="<?php echo base_url();?>/static/js/admin/common.js"></script>
<script type="text/javascript">
var path = '' ;
$(document).ready(function() {
//提交
path = getQueryStringValue("path");
$("#btnSave").click(function(){
	
    if($("#newform").Valid() == false || !$("#newform").Valid()) return false;

	 var dir_name = $("#dir_name").val();
	 var pattern =  /^[a-zA-Z0-9_]{1,}$/; 
	if(!dir_name.match(pattern)){
		alert('文件名字输入的不合法');
		return false ;
	}
	
	 var url="<?php echo site_url("admin/file_manager/file/mkdir_dir");?>?inajax=1";	
	
		$.ajax({
			   type: "POST",
			   url: url ,
			   data: "dirname="+dir_name+"&path="+path+"&time=<?php echo time();?>&action=do_create",
			   cache:false,
			   dataType:"json",
			   success: function(msg){
				var code = msg.resultcode ;
				var result = msg.resultinfo.errmsg ;//错误信息
				var p = msg.resultinfo.list.filepath ;
				if(parseInt(code) < 0){
					window.location.href="<?php echo site_url('admin/login/jump_permition_html');?>" ;
					return false ;
				}else if(parseInt(code) >=1){
					parent.$("#windownbg").remove();
					parent.$("#windown-box").fadeOut("slow",function(){$(this).remove();});
					parent.common_request(p);
					
				}else{
					//失败
					alert(result);
				}
			   }
					
		});	
//关闭


});
$("#btnClose").click(function(){
	parent.$("#windownbg").remove();
	parent.$("#windown-box").fadeOut("slow",function(){$(this).remove();});
   
 });
});
</script>
<style type="text/css">


</style>
</head>
<body>

<!--  快速转换位置按钮  -->
<form  id="newform">
<table width="98%" border="0" cellpadding="2" cellspacing="1" bgcolor="#D1DDAA" align="center" style="margin-top:8px">
<tr align="" bgcolor="#FAFAF1" height="22">
	<td width="6%">目录名称：
	</td>
	<td width="16%">
	<input type="text" value="" name="" size="40" required="true"  tip="请输入文件夹名称 " style="" id="dir_name" />备注:请输入字符数字下划线</td>
</tr>


</table>
<div style="margin-top:12px;width:98%;margin-left:10px">
<input id="btnSave" class="ImgBtn_Big" type="button" value=" 提 交 " name="btnSave">
<input id="btnClose" class="ImgBtn_Big" type="button" value=" 关 闭 " name="btnReset">
</div>
</form>

</body>
</html>