<?php 
if (! defined('BASEPATH')) {
	exit('Access Denied');
}
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>团队职务添加</title>
<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>/static/css/admin/base.css">
<script src="<?php echo base_url();?>/static/js/jquery.js" language="javascript"></script>
<script src="<?php echo base_url();?>/static/js/validate/validator.js" language="javascript"></script>

<script type="text/javascript" src="<?php echo base_url();?>/static/js/admin/common.js"></script>
<script type="text/javascript">
$(document).ready(function() {
//提交
$("#btnSave").click(function(){
	
    if($("#newform").Valid() == false || !$("#newform").Valid()) return false;

	 var duty_name = $("#duty_name").val();
	 var status = $("#status").val();
	 var url="<?php echo site_url("admin/master/team/team_duty");?>?action=team_duty_add_do";	
	
		$.ajax({
			   type: "POST",
			   url: url ,
			   data: "duty_name="+duty_name+"&status="+status+"&time=<?php echo time();?>",
			   cache:false,
			   dataType:"json",
			   success: function(msg){
				var code = msg.resultcode ;
				var result = msg.resultinfo.errmsg ;//错误信息
				if(parseInt(code) >=1){
					parent.location.href="<?php echo site_url('admin/master/team/team_duty') ;?>";
					
					 
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
	<td width="6%">职务名称：
	</td>
	<td width="16%">
	<input type="text" value="" name="" size="40" required="true"  tip="请输入职务名称 " style="height:30px" id="duty_name" />例如：后台管理员</td>
</tr>
<tr align="" bgcolor="#FAFAF1" height="22">
	<td width="6%">是否有效：</td>
	<td width="16%">
		<select required="true" id="status">
			<option value="">请选择</option>
			<option value="1">有效</option>
			<option value="0">无效</option>
		</select>
	</td>
</tr>

</table>
<div style="margin-top:12px;width:98%;margin-left:10px">
<input id="btnSave" class="ImgBtn_Big" type="button" value=" 提 交 " name="btnSave">
<input id="btnClose" class="ImgBtn_Big" type="button" value=" 关 闭 " name="btnReset">
</div>
</form>

</body>
</html>