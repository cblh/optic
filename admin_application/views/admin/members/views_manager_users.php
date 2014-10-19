<?php 
if (! defined('BASEPATH')) {
	exit('Access Denied');
}

?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>会员管理---数据处理平台</title>
<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>/static/css/admin/base.css">
<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>/static/css/admin/page.css">
<script src="<?php echo base_url();?>/static/js/jquery.js" language="javascript"></script>

<script src="<?php echo base_url();?>/static/js/validate/validator.js" language="javascript"></script>
<script language="javascript">




function selAll()
{
	for(i=0;i<document.form2.id.length;i++)
	{
		if(!document.form2.id[i].checked)
		{
			document.form2.id[i].checked=true;
		}
	}
}
function noSelAll()
{
	for(i=0;i<document.form2.id.length;i++)
	{
		if(document.form2.id[i].checked)
		{
			document.form2.id[i].checked=false;
		}
	}
}


</script>
 
<script>
$(function(){
	ajax_data(1);
	$("#search").click(function(){
		var allowadmin = $("#allowadmin").val();
		var username = $("#username").val();
		var orderby = $("#orderby").val();
		var desc_asc =$("#desc_asc").val();		
		ajax_data(1);
	});
});
function common_request(page){
	var allowadmin = $("#allowadmin").val();
	var username = $("#username").val();
	var orderby = $("#orderby").val();
	var desc_asc =$("#desc_asc").val();		
	var page = page ;
	var url="<?php echo site_url("admin/members/user/manager_users");?>?inajax=1";
	
	$.ajax({
		   type: "POST",
		   url: url ,
		   data: "allowadmin="+allowadmin+"&username="+username+"&orderby="+orderby+"&desc_asc="+desc_asc+"&page="+page+"&is_search=search&time=<?php echo time();?>",
		   cache:false,
		   dataType:"json",
		 //  async:false,
		   success: function(msg){
			var code = msg.resultcode ;
			if(code <0){
			//	window.location.href="<?php echo site_url('admin/login/jump_permition_html');?>" ;
				return false ;
			}  
			
			result = msg.resultinfo.list.result ;
			
			var shtml = '';
			
			for(var i in result){
				
				shtml+='<tr height="22" bgcolor="#FFFFFF" align="center" onmouseout="javascript:this.bgColor=\'#FFFFFF\';" onmousemove="javascript:this.bgColor=\'#FCFDEE\';">';
				shtml+='<td>'+result[i].uid+'</td>';
				shtml+='<td><input id="id" class="np" type="checkbox" value="'+result[i].uid+'" name="id"></td>';
				shtml+='<td><a href=""><u>'+result[i].username+'</u></a></td>';
				shtml+='<td>'+result[i].allowadmin+'</td>';
				shtml+='<td><font color="green">'+result[i].status+'</font></td>';
				shtml+='<td>'+result[i].regdate+'</td>';
				shtml+='<td><a href="101">编辑</a></td>';					
			}
			
			
			$("#result_p").html(shtml);
			//alert();
			$("#mynew_page").html(" ");
			$("#mynew_page").html(msg.resultinfo.list.page_string);
			$("#ajax_wait").html(' ');
		   },
		   beforeSend:function(){
			  $("#ajax_wait").html('<font color="red">正在查询请稍后。。</font>');
		   },
		   error:function(){
			   
		   }
		  
		});		
	

}
function ajax_data(page){
	if(!page){
		page = 1 ;
	}else{
		common_request(page);
	}
}


</script>
 

</head>
<body leftmargin="0" topmargin="0">
<div class="nav_des">
	<div style="float: left">
	<span class="des">用户>></span>
	<a href="<?php echo site_url("admin/members/user/user_add");?>">用户添加</a>
	<a href="<?php echo site_url("admin/members/user/manager_users");?>" class="nav_des_hover">用户管理</a>
	</div>
	<div id="ajax_wait" style="float:right"></div>
</div>

<!--  搜索表单  --> 	
<form name='form3' onsubmit="return false ">
<table width='100%'  border='0' cellpadding='1' cellspacing='1' bgcolor='#CBD8AC' align="center" style="margin-top:8px">
  <tr bgcolor='#EEF4EA'>
    <td background='<?php echo base_url();?>/static/image/admin/wbg.gif' align=''>
      <table border='0' cellpadding='0' cellspacing='0'>
        <tr>
          <td width='90' align='center'>会员级别：</td>
          <td width='100'>
          <select name='allowadmin' style='width:100' id="allowadmin">
         	<option value='all'>全部</option>
         	<?php 
         		if($level){
         			foreach ($level as $key=>$val){
         				echo "<option value='{$key}'>{$val}</option>";
         			}
         		}
         	?>
         
          </select>
        </td>
        <td width='70'>
           	用户名：
        </td>
        <td width='160'>
          	<input type='text' name='username' value='' style='width:150px' id="username" />
        </td>
        <td width='110'>
    		<select name='orderby' style='width:80px'  id="orderby">
            <option value='1'>uid</option>
            <option value='2'>注册时间</option>
      		</select>
        </td>
        <td width='70'>
    		<select name='desc_asc' style='width:80px' id="desc_asc">
            <option value='desc'>降序</option>
            <option value='asc'>升序</option>
      		</select>
        </td>        
        <td>
          <input name="imageField" type="image" src="<?php echo base_url();?>/static/image/admin/search.gif" width="45" height="20" border="0" class="np" id="search"/>
        </td>
       </tr>
      </table>
    </td>
  </tr>
</table>
</form>


<!--  内容列表   -->
<form name="form2">

<table width="100%" border="0" cellpadding="2" cellspacing="1" bgcolor="#D1DDAA" align="center" style="margin-top:8px">
<tr bgcolor="#E7E7E7">
	<td height="24" colspan="10" >&nbsp;会员管理 &nbsp;</td>
</tr>
<tr align="center" bgcolor="#FAFAF1" height="22">
	<td width="6%">ID</td>
	<td width="4%">选择</td>
	<td width="5%">用户名</td>
	<td width="10%">会员级别</td>
	<td width="10%">状态</td>
	<td width="8%">注册时间</td>

	<td width="10%">操作</td>
</tr>
<tbody id="result_p">

</tbody>
<tr bgcolor="#FAFAF1">
<td height="28" colspan="7">
	&nbsp;
	<a href="javascript:selAll()" class="coolbg">全选</a>
	<a href="javascript:noSelAll()" class="coolbg">取消</a>
</td>
</tr>
<tr align="right" bgcolor="#EEF4EA">

	<td height="36" colspan="7" align="right" id="mynew_page">
	<?php 
		if(isset($page_string)){
			echo $page_string ;
		}
	?>
	<!--翻页代码 --></td>
</tr>
</table>

</form>

</body>
</html>