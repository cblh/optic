<?php 
if (! defined('BASEPATH')) {
	exit('Access Denied');
}
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>会员添加</title>
<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>/static/css/admin/base.css">
<script src="<?php echo base_url();?>/static/js/jquery.js" language="javascript"></script>

<script src="<?php echo base_url();?>/static/js/validate/validator.js" language="javascript"></script>
<script type="text/javascript">
$(document).ready(function() {
//提交
$("#btnSave").click(function(){
    if($("#newform").Valid() == false || !$("#newform").Valid()) return false;
});

});
</script>
</head>
<body leftmargin="8" topmargin="8">
<div class="nav_des">
<span class="des">用户>></span>
<a href="<?php echo site_url("admin/members/user/user_add");?>" class="nav_des_hover">用户添加</a>
<a href="<?php echo site_url("admin/members/user/manager_users");?>">用户管理</a>
</div>

<!--  内容列表   -->
<form name="form2" method="post" name="" action="<?php echo site_url("admin/members/user/user_add");?>" id="newform">
<input type="hidden" value="do_member_add" name="action">
<table width="98%" border="0" cellpadding="2" cellspacing="1" bgcolor="#D1DDAA" align="center" style="margin-top:8px">
<tr bgcolor="#E7E7E7">
	<td height="24" colspan="" >&nbsp;会员添加&nbsp;</td>
</tr>
<tr align="" bgcolor="#FAFAF1" height="22">
	<td >用户名:<input type="text" name="username" class="form_input"  required="true" errmsg="请输入3-16个字符" tip="请输入3-16个字符"> </td>
</tr>
<tr align="" bgcolor="#FAFAF1" height="22">
	<td >密&nbsp;&nbsp;码:<input type="text" name="passwd" class="form_input" required="true" errmsg="请输入3-16个字符" tip="请输入3-16个字符"></td>
</tr>

<tr bgcolor="#FAFAF1">
<td height="28" colspan="">
	&nbsp;

	<input type="submit" value="提&nbsp;&nbsp;交" class="coolbg" id='btnSave'>
</td>
</tr>

</table>

</form>

</body>
</html>