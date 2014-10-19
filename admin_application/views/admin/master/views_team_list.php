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
<script src="<?php echo base_url();?>/static/js/jquery.js" language="javascript"></script>

<script src="<?php echo base_url();?>/static/js/admin/common.js" language="javascript"></script>

</head>
<body leftmargin="8" topmargin="8">
<div class="nav_des">
<span class="des">后台管理团队</span>
<a href="<?php echo site_url('admin/master/team/team_list') ;?>"  class="nav_des_hover">团队成员</a>
<a href="<?php echo site_url('admin/master/team/team_duty');?>">团队职务</a>
</div>

  

<form name="form2" action="<?php echo site_url('admin/master/team/team_list') ;?>" method="post">
<input type="hidden" value="add_team_users" name="action">
<table width="98%" border="0" cellpadding="2" cellspacing="1" bgcolor="#D1DDAA" align="center" style="margin-top:8px">
<tr bgcolor="#E7E7E7">
	<td height="24" colspan="10" >&nbsp;成员列表&nbsp;</td>
</tr>
<tr align="center" bgcolor="#FAFAF1" height="22">
	<td width="6%">ID</td>
	<td width="10%">成员用户</td>
	<td width="10%">职务</td>
	<td width="">操作</td>
</tr>

<tr align='center' bgcolor="#FFFFFF" onMouseMove="javascript:this.bgColor='#FCFDEE';" onMouseOut="javascript:this.bgColor='#FFFFFF';" height="22" >
	<td>1</td>

	<td align="center"><?php echo $admin ;?></td>
	<td><h3>创始人</h3></td>
	<td width=""><h3>创始人</h3></td>
</tr>
<?php 
	if($admin_data){
		foreach($admin_data as $admin_key=>$admin_val){
			
	
?>
<tr align='center' bgcolor="#FFFFFF" onMouseMove="javascript:this.bgColor='#FCFDEE';" onMouseOut="javascript:this.bgColor='#FFFFFF';" height="22" >
	<td><?php echo $admin_val['uid'] ;?></td>

	<td align="center"><u><?php echo $admin_val['username'] ;?></u></td>
	<td><?php echo $admin_val['duty_name'] ;?></td>
	<td width="">
	<a onclick="ajax_delete('<?php echo $admin_val['uid'] ;?>','<?php $visit_url = 'admin/master/team/team_list_del/id/'.$admin_val['uid'] ;echo site_url($visit_url);?>','<?php echo site_url('admin/master/team/team_list');?>','<?php echo site_url('admin/login/jump_permition_html') ;?>');" href="javascript:void(0)">
		<img title="删除" src="<?php echo base_url() ;?>/static/image/admin/gtk-del.png">
	</a>
	</td>
</tr>
<?php 
		}
	}
?>
<tr align='center' bgcolor="#FFFFFF" onMouseMove="javascript:this.bgColor='#FCFDEE';" onMouseOut="javascript:this.bgColor='#FFFFFF';" height="22" >

	<td>新增</td>
	<td align="left"><input type="text" name="username" id=""></td>
	<td><select id="duty" name="duty_name">
	<?php 

		if($list){
			foreach($list as $k=>$v){
			
	?>
	<option value="<?php echo $v['duty_id'];?>"><?php echo $v['duty_name'];?></option>
	<?php 
			}
		}
	?>
	</select></td>
	<td width="">
	<a onclick="duty_edit('34','44')" href="javascript:void(0)">

	无操作
	</a>
	</td>
</tr>


<tr bgcolor="#FAFAF1">
<td height="28" colspan="10">
	&nbsp;
	<input type="submit" value="添加团队成员" class="coolbg">
	
</td>
</tr>

</table>

</form>

</body>
</html>