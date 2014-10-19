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
<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>/static/css/admin/page.css">
<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>/static/css/admin/window.css" />
<script src="<?php echo base_url();?>/static/js/jquery.js" language="javascript"></script>

<script src="<?php echo base_url();?>/static/js/admin/common.js" language="javascript"></script>
<script type="text/javascript" src="<?php echo base_url();?>/static/js/admin/tipswindown.js"></script>
<script language="javascript">
$(function(){
	common_request(1);
});

function common_request(page){
	
	var url="<?php echo site_url("admin/nav_manager/index/show_nav");?>";
	var postdata = {
			action:'show_list'	,
			page:page	
	};
	$.ajax({
		   type: "POST",
		   url: url ,
		   data: postdata,
		   cache:false,
		   dataType:"json",
		   //async:false,  //是否异步
		   success: function(msg){
			var shtml = '' ;
			var code = msg.resultcode ;
			if(code <0){
				window.location.href="<?php echo site_url('admin/login/jump_permition_html');?>" ;
				return false ;
			}  
			
			var result = msg.resultinfo.list.list_ ;
			var page_ = msg.resultinfo.list.page_string ;
		
			//console.dir(result);
			if(result){
				
				for(var j in result){
					
					shtml+= '<tr align=\'center\' bgcolor="#FFFFFF" onMouseMove="javascript:this.bgColor=\'#FCFDEE\';" onMouseOut="javascript:this.bgColor=\'#FFFFFF\';" height="22" >' ;
					shtml+='<td>'+result[j].nav_id+'</td>';	
					shtml+='<td>'+result[j].nav_name+'</td>';	
					shtml+='<td>'+result[j].nav_url+'</td>';	
					shtml+='<td>'+result[j].nav_parent_id+'</td>';	
					shtml+='<td>'+result[j].nav_position+'</td>';	
					shtml+='<td>'+result[j].nav_status+'</td>';	
					shtml+='<td>'+result[j].nav_order+'</td>';	
					shtml+='</tr>';
				}
			}
			$("#result_p").html(shtml);
			$("#mynew_page").html(" ");
			$("#mynew_page").html(msg.resultinfo.list.page_string);
		   },
		   beforeSend:function(){
			 // $("#ajax_wait").html('<font color="red">正在查询请稍后。。</font>');
		   },
		   error:function(){
			   alert('加载失败');
		   }
		  
		});		
	

}
//栏目管理
function add_nav(){

	tipsWindown("添加导航","iframe:<?php echo site_url('admin/nav_manager/index/nav_add_page')?>",650,400,"true","","true","nav_");
}
</script>
</head>
<body leftmargin="8" topmargin="8">
<div class="nav_des">
<span class="des">后台导航管理</span>
<a href="<?php echo site_url('admin/nav_manager/index/show_nav') ;?>"  class="nav_des_hover">导航管理</a>

</div>

  
<!--  内容列表   -->


<table width="98%" border="0" cellpadding="2" cellspacing="1" bgcolor="#D1DDAA" align="center" style="margin-top:8px">
<tr bgcolor="#E7E7E7">
	<td height="24" colspan="10" >&nbsp;菜单列表&nbsp;</td>
</tr>
<tr align="center" bgcolor="#FAFAF1" height="22">
	<td width="6%">ID</td>
	
	<td width="10%">导航名称</td>
	<td width="20%">导航url地址</td>
	<td width="10%">父级名称</td>
	<td width="10%">位置</td>
	<td width="">状态</td>
	<td width="10%">排序</td>
</tr>
<tbody id="result_p">

</tbody>

<tr align="right" bgcolor="#EEF4EA">

	<td height="36" colspan="7" align="right" id="mynew_page">
	
	<!--翻页代码 --></td>
</tr>


<tr bgcolor="#FAFAF1">
<td height="28" colspan="10">
	&nbsp;
	<input type="button" value="添加导航" class="coolbg" onclick="add_nav()">
	
</td>
</tr>

</table>



</body>
</html>