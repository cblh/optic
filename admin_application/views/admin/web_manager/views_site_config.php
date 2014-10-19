<?php 
if (! defined('BASEPATH')) {
	exit('Access Denied');
}
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>系统设置</title>
<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>/static/css/admin/base.css">
<script src="<?php echo base_url();?>/static/js/jquery.js" language="javascript"></script>


<script type="text/javascript">
$(function(){

	$("#make_data").click(function(){
		var url ="<?php echo site_url('admin/web_manager/index/make_data') ;?>";
		$.ajax({
			   type: "POST",
			   url: url ,
			   data: "",
			   cache:false,
			   dataType:"json",
			 //  async:false,
			   success: function(msg){
				var code = msg.resultcode ;
				if(code <0){
					$("#p_result").show().html('<font color="red">对不起权限不足。。。</font>');
					return false ;
				}  
				
			//	result = msg.resultinfo.list.result ;
				
				$("#p_result").show().html('<font color="green">'+msg.resultinfo.errmsg+'</font>');
				
			   },
			   beforeSend:function(){
				  $("#p_result").show().html('<font color="red">正在生成请稍候。。。</font>');
			   },
			   error:function(){
				   alert("服务器繁忙");
			   }
			  
			});	
	});
});
</script>
</head>
<body leftmargin="8" topmargin="8">
<div class="nav_des">
<span class="des">系统核心设置>></span>
<a href="<?php echo site_url("admin/web_manager/index/site_config");?>" class="nav_des_hover">系统设置</a>

</div>

<!--  内容列表   -->
<form name="form2" method="post" name="" action="<?php echo site_url("admin/web_manager/index/site_config");?>" id="newform">
<input type="hidden" name="action" value="add_config">
<table width="98%" border="0" cellpadding="2" cellspacing="1" bgcolor="#D1DDAA" align="center" style="margin-top:8px">

<tr bgcolor="#E7E7E7">
	<td height="24" colspan="" >&nbsp;系统参数设置&nbsp;</td>
</tr>
<?php 
	
	if($config){
		
		foreach($config as $key=>$value){
			
		
?>
<tr align="" bgcolor="#FAFAF1" height="22">
	<td ><?php echo (isset($value['value']))?$value['value']:'' ;?>:
	<?php if(isset($value['type']) && $value['type'] == 'textarea'){?>
	<textarea style="width:300px; height:100px" name="<?php echo $key ;?>" ><?php echo $value['data_info'];?></textarea><?php echo (isset($value['desc'])?$value['desc']:'') ;?>
	<?php }?>
	
	<?php 
		if(isset($value['type']) && $value['type'] == 'radio'){
	?>
	<?php if(isset($value['data_info']) && $value['data_info'] == '1'){?>
		是<input type="radio" name="<?php echo $key ;?>" value="1" checked/>
		否<input type="radio" name="<?php echo $key ;?>" value="0" />&nbsp;&nbsp;<?php echo (isset($value['desc'])?$value['desc']:'') ;?>
	<?php }else{?>
		是<input type="radio" name="<?php echo $key ;?>" value="1" />
		否<input type="radio" name="<?php echo $key ;?>" value="0" checked/>&nbsp;&nbsp;<?php echo (isset($value['desc'])?$value['desc']:'') ;?>
	<?php }?>
	<?php }?>
	
	
	<?php 
		if(isset($value['type']) && $value['type'] == 'text'){
	?>
	<input type="text" name="<?php echo $key ;?>" class="form_input"  value="<?php echo $value['data_info'];?>" style="width:<?php echo isset($value['width'])?$value['width']:'' ;?>"> <?php echo $value['desc'] ;?>
	<?php 
		}
	?>
	</td>
</tr>
<?php 
	
	
	}
	
	}
?>
<!-- 
<tr align="" bgcolor="#FAFAF1" height="22">
	<td ><?php echo $value ;?>:<input type="text" name="<?php echo $key ;?>" class="form_input"  required="true" errmsg="请输入3-16个字符" tip="请输入3-16个字符"> </td>
</tr>
-->
<tr bgcolor="#FAFAF1">
<td height="28" colspan="">
	&nbsp;

	<input type="submit" value="提&nbsp;&nbsp;交" class="coolbg" id='btnSave'>
</td>
</tr>

</table>

</form>
<div style="width:98% ; background:#E7E7E7 ;margin:auto ;margin-top:10px ; text-align:center ; height:30px ; line-height:20px">
	<input type="button" value="生成数据文件" style="height:24px ;margin-top:2px" id="make_data">
</div>
<div style="width:98% ; background:#E7E7E7 ;margin:auto ;margin-top:10px ; text-align:center ; height:30px ; line-height:30px ; display:none" id="p_result">
	
</div>
</body>
</html>
