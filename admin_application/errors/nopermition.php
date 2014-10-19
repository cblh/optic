<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>无权限访问</title>
<style type="text/css">

.permition{
width:100%;
text-align:center;
margin:0 auto;
background:#EEF4EA;
height:100px;
font-size:14px;
font-weight:bold
}
.permition .left{float:left; border:solid 0px; margin-top:10%}
.permition .right {float:right}
.right div{border:solid 0px; margin-top:2%; padding-top:1%; text-indent:3px;}
</style>
</head>

<body>
<div class="permition" >
<div style="text-align:center; border:solid 0px; height:100px; width:23%; margin:0 auto">
	<div class="left">
		<img src="<?php echo base_url();?>/static/image/admin/forbidden.png" title="无权限访问" alt="无权限访问">
	</div>
	<div class="right">
		<div style="">你正在访问一个无权页面，请联系管理员开通</div>
		<div>
		管理员:<?php echo config_item('web_admin_master');?></div>
		<div>Email:<?php echo config_item('web_admin_email'); ?></div>
	</div>

</div>

</div>
</body>
</html>
