<?php 
if (! defined('BASEPATH')) {
	exit('Access Denied');
}
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>文件预览</title>
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


<textarea style="width:640px; height:400px"><?php echo $file ;?></textarea>

</body>
</html>