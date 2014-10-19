<?php 
if (! defined('BASEPATH')) {
	exit('Access Denied');
}
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>后台导航添加</title>
<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>/static/css/admin/base.css">
<script src="<?php echo base_url();?>/static/js/jquery.js" language="javascript"></script>

<script src="<?php echo base_url();?>/static/js/validate/validator.js" language="javascript"></script>
<script type="text/javascript">
$(document).ready(function() {
//提交
$("#btnSave").click(function(){
    if($("#newform").Valid() == false || !$("#newform").Valid()) return false;
	var url="<?php echo site_url('admin/nav_manager/index/nav_add_page');?>";
	var postdata = {
		action:'add',
		nav_name:$("#nav_name").val(),
		url:$("#url").val(),
		p_id:$("#p_id").val(),
		nav_position:$("#nav_position").val(),
		nav_status:$("#nav_status").val(),
		nav_order:$("#nav_order").val()
	} ;

	$.ajax({
		   type: "POST",
		   url: url ,
		   data: postdata,
		   cache:false,
		   dataType:"json",
		 //  async:false,
		   success: function(msg){
			   
			   code = msg.resultcode ;
				if(code >=1){//成功
					result = msg.resultinfo.list.result ;
							
				}else if(code == 0){
					alert(msg.resultinfo.errmsg);
				}else{
					window.location.href="<?php echo site_url('admin/login/jump_permition_html') ;?>" ;
				}
			
		   },
		   beforeSend:function(){
			//  $("#ajax_wait").html('<font color="red">正在查询请稍后。。</font>');
		   },
		   error:function(){
			   alert('服务器繁忙请稍候');
		   }
		  
		});	
});

});
</script>
</head>
<body >


<!--  内容列表   -->

<form id="newform">
<table width="100%" border="0" cellpadding="2" cellspacing="1" bgcolor="#D1DDAA" align="center" style="">

<tr align="" bgcolor="#FAFAF1" height="32">
	<td >菜单名称:<input type="text" id="nav_name" class="form_input"  required="true" errmsg="请填写菜单名称" tip="请填写菜单名称"> </td>
</tr>
<tr align="" bgcolor="#FAFAF1" height="32">
	<td >位置:
		<select id="nav_position" required="true" style="width:200px">
			<option value="">请选择</option>
			<option value="1">左边功能</option>
			<option value="2">上边功能</option>
			<option value="3">右边功能</option>
		</select>
	</td>
</tr>
<tr align="" bgcolor="#FAFAF1" height="32">
	<td >菜单级别:
	<select id="nav_parent_id">
		<option value="0">顶层菜单</option>
		<?php 		
			if($list){
				foreach($list as $k=>$v){
					echo "<option value='{$v['nav_id']}'>{$v['nav_name']}</option>";
				}
			}
		?>
	</select>
	</td>
</tr>
<tr align="" bgcolor="#FAFAF1" height="32">
	<td >url地址:<input type="text" id="url" class="form_input" required="true" errmsg="请填写url地址" tip="请填写url地址">例如:admin/admin_tree/tree/tree_edit/</td>
</tr>


<tr align="" bgcolor="#FAFAF1" height="32">
	<td >状态:
		<select id="nav_status" >
			
			<option value="1">有效</option>
			<option value="0">无效</option>
		</select>
	</td>
</tr>
<tr align="" bgcolor="#FAFAF1" height="22">
	<td >排序:<input type="text" name="" class="form_input" value="0" id="nav_order" ></td>
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