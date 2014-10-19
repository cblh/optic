<?php 
if (! defined('BASEPATH')) {
	exit('Access Denied');
}

?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>修改模块树</title>
<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>/static/css/admin/base.css">
<script src="<?php echo base_url();?>/static/js/jquery.js" language="javascript"></script>
<script src="<?php echo base_url();?>/static/js/validate/validator.js" language="javascript"></script>

<script type="text/javascript" src="<?php echo base_url();?>/static/js/admin/common.js"></script>

<script type="text/javascript">
$(document).ready(function() {
//提交
var id = getQueryStringValue("id");
var name = getQueryStringValue("name");
var tree_type = getQueryStringValue("tree_type");

var editurl = "<?php echo site_url('admin/admin_tree/tree/tree_edit')?>?inajax=1&action=tree_edit_do" ;
$("#info").html("当前正在修改模块树名称："+decodeURIComponent(name));
$("#btnSave").click(function(){

    if(!$("#newform").Valid()||$("#newform").Valid() == "false") return false;
	$.ajax({
		   type: "POST",
		   url: editurl ,
		   data: "tree_id="+$("#tree_id").val()+"&tree_name="+encodeURIComponent($("#tree_name").val())+"&tree_orderby="+parseInt($("#tree_orderby").val()),
		   cache:false,
		   dataType:"json",
		   success: function(msg){
			var code_num = msg.resultcode ;
			if(code_num>=1){
				
				parent.location.href="<?php echo site_url('admin/admin_tree/tree/tree_list');?>?tree_type="+tree_type;
			}else if(code_num == 0){
				alert(msg.resultinfo.errmsg);
				parent.JqueryDialog.Close("close");
			}else{
					window.location.href="<?php echo site_url('admin/login/jump_permition_html');?>" ;
			}
			 
		   }
				
});	
	  
});
//关闭
  $("#btnClose").click(function(){
	 	 parent.$("#windownbg").remove();
		 parent.$("#windown-box").fadeOut("slow",function(){$(this).remove();});
  });


});

</script>
</head>
<body>
<div>

<div id="info"></div>


</div>
<div style="clear: both	"></div>
<!--  快速转换位置按钮  -->
<form name="form2" id="newform">

<table width="98%" border="0" cellpadding="2" cellspacing="1" bgcolor="#D1DDAA" align="center" style="margin-top:8px">
<tr bgcolor="#E7E7E7">
	<td height="24" colspan="" >&nbsp;模块信息修改&nbsp;</td>
</tr>
<tr align="" bgcolor="#FAFAF1" height="22">
	<td >自增ID:<input type="text" name="tree_parent_id" class="form_input" id="tree_id" readonly value="<?php echo $one['tree_id'] ;?>">不可以进行填写，只读 </td>
</tr>


<tr align="" bgcolor="#FAFAF1" height="22">
	<td >修改的模块名称:<input type="text" name="tree_name" class="form_input" id="tree_name" required="true" value="<?php echo $one['tree_name'];?>"><font color="red">*</font>必填</td>
</tr>
<tr align="" bgcolor="#FAFAF1" height="22">
	<td >模块排序:<input type="text" name="tree_orderby" class="form_input" id="tree_orderby" valtype="int" maxlength="128" required="true" value="<?php echo $one['tree_orderby'];?>"><font color="red">*</font>必填，数字越大越在前</td>
</tr>
<tr bgcolor="#FAFAF1">
<td height="28" colspan="">
	&nbsp;

	<input type="button" value="提&nbsp;&nbsp;交" class="coolbg" id='btnSave'>
</td>
</tr>

</table>

</form>

</body>
</html>