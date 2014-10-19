<?php 
if (! defined('BASEPATH')) {
	exit('Access Denied');
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>top</title>
<link href="<?php echo base_url();?>/static/css/admin/base.css" rel="stylesheet" type="text/css">
<script src="<?php echo base_url();?>/static/js/jquery.js" language="javascript"></script>
<script src="<?php echo base_url();?>/static/js/admin/divfrm/divframe.js" language="javascript"></script>
<script language='javascript'>
var preFrameW = '206,*';
var FrameHide = 0;
var curStyle = 1;
var totalItem = 9;
function ChangeMenu(way){
	var addwidth = 10;
	var fcol = top.document.all.btFrame.cols;
	if(way==1) addwidth = 10;
	else if(way==-1) addwidth = -10;
	else if(way==0){
		if(FrameHide == 0){
			preFrameW = top.document.all.btFrame.cols;
			top.document.all.btFrame.cols = '0,*';
			FrameHide = 1;
			return;
		}else{
			top.document.all.btFrame.cols = preFrameW;
			FrameHide = 0;
			return;
		}
	}
	fcols = fcol.split(',');
	fcols[0] = parseInt(fcols[0]) + addwidth;
	top.document.all.btFrame.cols = fcols[0]+',*';
}


function mv(selobj,moveout,itemnum)
{
   if(itemnum==curStyle) return false;
   if(moveout=='m') selobj.className = 'itemsel';
   if(moveout=='o') selobj.className = 'item';
   return true;
}

function changeSel(itemnum)
{
  curStyle = itemnum;
  for(i=1;i<=totalItem;i++)
  {
     if(document.getElementById('item'+i)) document.getElementById('item'+i).className='item';
  }
  document.getElementById('item'+itemnum).className='itemsel';
}
function login_out(){
	if(confirm("是否确定退出系统")){
		parent.window.location="<?php echo site_url("admin/members/user/login_out");?>";
	}
}
function edit_passwd(){
	
	showModalTemplate("<?php echo site_url("admin/archive/index/category");?>",'网站栏目管理',800,450,'');
}

</script>
<style>
body { padding:0px; margin:0px; }
#tpa {
	color: #009933;
	margin:0px;
	padding:0px;
	float:right;
	padding-right:10px;
}

#tpa dd {
	margin:0px;
	padding:0px;
	float:left;
	margin-right:2px;
}

#tpa dd.ditem {
	margin-right:8px;
}

#tpa dd.img {
  padding-top:6px;
}

div.item
{
  text-align:center;
	
	width:82px;
	height:26px;
	line-height:28px;
}

.itemsel {
  width:80px;
  text-align:center;
  background:#226411;
	border-left:1px solid #c5f097;
	border-right:1px solid #c5f097;
	border-top:1px solid #c5f097;
	height:26px;
	line-height:28px;
}

*html .itemsel {
	height:26px;
	line-height:26px;
}

a:link,a:visited {
 text-decoration: underline;
}

.item a:link, .item a:visited {
	font-size: 12px;
	color: #ffffff;
	text-decoration: none;
	font-weight: bold;
}

.itemsel a:hover {
	color: #ffffff;
	font-weight: bold;
	border-bottom:2px solid #E9FC65;
}

.itemsel a:link, .itemsel a:visited {
	font-size: 12px;
	color: #ffffff;
	text-decoration: none;
	font-weight: bold;
}

.itemsel a:hover {
	color: #ffffff;
	border-bottom:2px solid #E9FC65;
}

.rmain {
  padding-left:10px;
</style>
</head>
<body bgColor='#ffffff'>
<table width="100%" border="0" cellpadding="0" cellspacing="0" background="<?php echo base_url();?>/static/image/admin/topbg.gif">
  <tr>
    <td width='20%' height="60"><img src="<?php echo base_url();?>/static/image/admin/logo.gif" /></td>
    <td width='80%' align="right" valign="bottom">
    	<table width="750" border="0" cellspacing="0" cellpadding="0">
      <tr>
      <td align="right" height="26" style="padding-right:10px;line-height:26px;">
        	您好：<span class="username"><?php echo $username;?></span>，登录ip:<font color="red"><?php echo $ip ;?></font>:所在的后台管理组：<font color="red"><?php echo $group_name ;?></font>
        	
        	[<a href="<?php echo site_url('admin/members/user/edit_passwd');?>" target="main">修改密码</a>]
        	[<a href="javascript:void(0)"  onclick="login_out()">注销退出</a>]&nbsp;
      </td>
      </tr>
      <tr>
        <td align="right" height="34" class="rmain">
		<dl id="tpa">
		
		
		<dd><div class='itemsel' id='item1' onMouseMove="mv(this,'move',1);" onMouseOut="mv(this,'o',1);"><a href="menu.asp" onclick="changeSel(1)" target="menu">公告栏管理</a></div></dd>
<!--		<dd><div class='item' id='item2' onMouseMove="mv(this,'m',2);" onMouseOut="mv(this,'o',2);"><a href="menu.html" onclick="changeSel(2)" target="menu">暂无</a></div></dd>-->
		</dl>
		</td>
      </tr>
    </table></td>
  </tr>
</table>
</body>
</html>