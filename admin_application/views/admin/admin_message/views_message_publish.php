<?php 
if (! defined('BASEPATH')) {
	exit('Access Denied');
}
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>公告发布</title>
<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>/static/css/admin/base.css">

<script src="<?php echo base_url();?>/static/js/jquery.js" language="javascript"></script> 
<script src="<?php echo base_url();?>/static/js/validate/validator.js" language="javascript"></script>


<!--datepicker-->
<script type="text/javascript" language="javascript" src="http://lib.sinaapp.com/js/prototype/1.7.0.0/prototype.js"></script>
<script type="text/javascript" language="javascript" src="<?php echo base_url();?>/static/js/DatePicker2/images/prototype-date-extensions.js"></script>
<script type="text/javascript" language="javascript" src="<?php echo base_url();?>/static/js/DatePicker2/images/behaviour.js"></script> 
<script type="text/javascript" language="javascript" src="<?php echo base_url();?>/static/js/DatePicker2/images/datepicker.js"></script> 
<script type="text/javascript" language="javascript" src="<?php echo base_url();?>/static/js/DatePicker2/images/behaviors.js"></script> 
<link rel="stylesheet" href="<?php echo base_url();?>/static/js/DatePicker2/images/datepicker.css">





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
<span class="des">公告>></span>
<a href="<?php echo site_url("admin/admin_message/message/message_publish");?>" class="nav_des_hover">公告添加</a>
<a href="<?php echo site_url("admin/index/main");?>">查看公告</a>
</div>
<!--  bootstrp 时间选择器   -->
<!--     <div class="form-group">
        <div class="input-group date form_datetime col-md-5" data-date-format='yyyy-mm-dd hh:ii:ss' data-link-field="dtp_input1">
            <input class="form-control" size="16" type="text" value="" readonly>
            <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
			<span class="input-group-addon"><span class="glyphicon glyphicon-th"></span></span>
        </div>
		<input type="hidden" id="dtp_input1" value=""/><br/>
    </div> -->
<!--  内容列表   -->
<form name="form2" method="post" name="" action="<?php echo site_url("admin/admin_message/message/message_publish");?>" id="newform">
<input type="hidden" value="do_message_add" name="action">
<table width="98%" border="0" cellpadding="2" cellspacing="1" bgcolor="#D1DDAA" align="center" style="margin-top:8px">
<tr bgcolor="#E7E7E7">
	<td height="24" colspan="" >&nbsp;公告发布&nbsp;</td>
</tr>
<tr align="" bgcolor="#FAFAF1" height="22">
	<td >接收班级:<select name="class">
	<?php 
		if($list){
			foreach ($list as $k=>$v){
		
	?>
	<option value="<?php echo $v['class'] ;?>"><?php echo  $v['class'] ;?></option>
	<?php 
		 }
		}
	?>
	</select></td>
</tr>
<tr align="" bgcolor="#FAFAF1" height="22">
	<td >公告标题:<input type="text" name="title" class="form_input" required="true" errmsg="请输入2-20个字符" tip="请输入3-20个字符" id="message_title"></td>
</tr>
<tr align="" bgcolor="#FAFAF1" height="22">
	<td >截止时间:<input name="deadtime" required="true" errmsg="请输入截止日期" tip="请输入截止日期" id="message_deadtime" class="datetimepicker"/></td>
</tr>
<tr align="" bgcolor="#FAFAF1" height="22">
	<td >内容:<textarea style="width: 300px; height:100px" required="true" name="content"></textarea></td>
</tr>
<tr bgcolor="#FAFAF1">
<td height="28" colspan="">
	&nbsp;

	<input type="submit" value="发&nbsp;&nbsp;布" class="coolbg" id='btnSave'>
</td>
</tr>

</table>

</form>

</body>
</html>