<?php 
if (! defined('BASEPATH')) {
	exit('Access Denied');
}
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>后台管理团队</title>
<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>/static/css/admin/base.css">
<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>/static/css/admin/window.css" />
<script src="<?php echo base_url();?>/static/js/jquery.js" language="javascript"></script>

<script type="text/javascript" src="<?php echo base_url();?>/static/js/admin/common.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>/static/js/admin/tipswindown.js"></script>
<script language="javascript">

function duty_add(){
	tipsWindown('团队职务添加',"iframe:<?php echo site_url("admin/master/team/team_duty");?>?action=team_duty_add_page",500,300,"true","","true","");
}
function duty_edit(id,duty_name){
	tipsWindown('团队职务编辑',"iframe:<?php echo site_url("admin/master/team/team_duty_edit");?>?id="+id+"&duty_name="+duty_name,800,480,"true","","true","");

}
</script>
</head>
<body leftmargin="8" topmargin="8">
<div class="nav_des">
<span class="des">后台管理团队</span>
<a href="<?php echo site_url('admin/master/team/team_list');?>"  >团队成员</a>
<a href="<?php echo site_url('admin/master/team/team_duty');?>" class="nav_des_hover">团队职务</a>
<span style="float:right">
<img src="<?php echo base_url();?>/static/image/admin/refresh.png" onclick="refresh('<?php echo site_url("admin/master/team/team_duty");?>')" alt="点击刷新" title="点击刷新" style="cursor: pointer;padding:10px; border:none">
</span>
</div>
<div style="clear: both"></div>
  
<!--  内容列表   -->
<form name="form2">

<table width="100%" border="0" cellpadding="2" cellspacing="1" bgcolor="#D1DDAA" align="center" style="margin-top:8px">
<tr bgcolor="#E7E7E7">
	<td height="24" colspan="10" >&nbsp;职务列表&nbsp;</td>
</tr>
<tr align="center" bgcolor="#FAFAF1" height="22">
	<td width="6%">ID</td>
	<td width="4%">选择</td>
	<td width="10%">职务名称</td>
	<td width="13%">创建日期</td>
	<td width="10%">创建人</td>
	<td width="13%">最后修改日期</td>
	<td width="10%">修改人</td>
	<td width="10%">是否开启</td>
	<td>操作</td>
</tr>
<?php 
	if($result){
		foreach($result as $k=>$v){
?>
<tr align='center' bgcolor="#FFFFFF" onMouseMove="javascript:this.bgColor='#FCFDEE';" onMouseOut="javascript:this.bgColor='#FFFFFF';" height="22" >
	<td><?php echo $v['duty_id'];?></td>
	<td><input name="id" type="checkbox" id="id" value="<?php echo $v['duty_id'];?>" class="np"></td>
	<td align="left"><a href='javascript:void(0)' onclick="duty_edit('<?php echo $v['duty_id'];?>','<?php echo $v['duty_name'];?>')"><u><?php echo $v['duty_name'];?></u></a></td>
	<td><?php echo $v['duty_createtime'];?></td>
	<td><?php echo $v['duty_creator'];?></td>
	<td><?php echo $v['duty_updatetime'];?></td>
	<td><?php echo $v['duty_last_person'];?></td>
	<td width="10%"><?php echo $v['duty_status'];?></td>
	<td><a href="javascript:void(0)" onclick="duty_edit('<?php echo $v['duty_id'];?>','<?php echo $v['duty_name'];?>')"><img src="<?php echo base_url();?>/static/image/admin/edit.png" title="编辑"></a> | <a href="javascript:void(0)" onclick="ajax_delete(<?php echo $v['duty_id'] ;?>,'<?php $visit_url = 'admin/master/team/team_duty_del_do/id/'.$v['duty_id'] ;echo site_url($visit_url);?>','<?php echo site_url('admin/master/team/team_duty');?>','<?php echo site_url('admin/login/jump_permition_html') ;?>');"><img src="<?php echo base_url();?>/static/image/admin/gtk-del.png" title="删除"></a> </td>
</tr>
<?php 
	}
}
?>
<tr bgcolor="#FAFAF1">
<td height="28" colspan="10">
	&nbsp;
	
	<a href="javascript:void(0)" class="coolbg" onclick="duty_add()">&nbsp;职务添加&nbsp;</a>

</td>
</tr>

</table>

</form>


</body>
</html>