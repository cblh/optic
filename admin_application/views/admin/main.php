<?php 
if (! defined('BASEPATH')) {
	exit('Access Denied');
}

?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>main</title>
<base target="_self">
<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>/static/css/admin/base.css" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>/static/css/admin/main.css" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>/static/css/admin/window.css" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>/static/css/admin/page.css" />
<style type="text/css">
#windown-content{
	color:green;
	letter-spacing:3px
}
</style>
<script src="<?php echo base_url();?>/static/js/jquery.js" language="javascript"></script>
<script type="text/javascript" src="<?php echo base_url();?>/static/js/admin/tipswindown.js"></script>
<script>

function popTips(myid){
	showTipsWindown($("#title_"+myid).val(), 'light', 600, 355,myid);
	
}
/*
*弹出本页指定ID的内容于窗口
*id 指定的元素的id
*title:	window弹出窗的标题
*width:	窗口的宽,height:窗口的高
*/
function showTipsWindown(title,id,width,height,myid){
	tipsWindown(title,"id:"+id,width,height,"true","","true",id);
	$("#windown-content").text($("#content_"+myid).val());
	update_is_read(myid);

}

$(function(){
	common_request(1);
	
});

function ajax_data(page){
	if(!page){
		page = 1 ;
	}else{
		common_request(page);
	}
}

function common_request(page){
	$.ajax({
		type: "POST",
		   url: "<?php echo site_url('admin/index/main');?>" ,
		   data: "action=one&time=<?php echo time();?>&page="+page,
		   cache:false,
		   dataType:"json",
		 //  async:false,
		   success: function(msg){ 
			  shtml = return_table();  
			  var data_list = msg.resultinfo.list.list ;
			  var datapage = msg.resultinfo.list.page ;
			  var page = '<tr bgcolor="#EEF4EA"><td colspan="6" align="right">'+datapage+'</td></tr>' ;
			  if(data_list){
				 for(var i in data_list ){
					var onclick = "popTips("+data_list[i].ntid+");" ;
					var hidden_content = '<textarea id=content_'+data_list[i].ntid+' style="display:none">'+data_list[i].content+'</textarea>' ;
					var hidden_title = '<input type="hidden" id=title_'+data_list[i].ntid+' value='+data_list[i].title+'>' ;
					shtml+='<tr onMouseMove="javascript:this.bgColor=\'#FCFDEE\';" onMouseOut="javascript:this.bgColor=\'#FFFFFF\';">';
					shtml+='<td style="cursor:pointer" onclick='+onclick+'>'+data_list[i].title+'</td>';
					shtml+='<td style="cursor:pointer" onclick='+onclick+'>'+data_list[i].content+'</td>';
					shtml+=hidden_content;
					shtml+=hidden_title ;
					shtml+='<td>'+data_list[i].publish_time+'</td>';
					shtml+='<td>'+data_list[i].deadtime+'</td>';
					shtml+='<td>'+data_list[i].class+'</td>';
					shtml+='<td>'+data_list[i].publish_person+'</td>';
					shtml+='<td id='+data_list[i].ntid+'>'+data_list[i].status_delete+'|<button type="button" onclick="status_delete('+data_list[i].ntid+');">删除</button></td>';
					shtml+='<td>'+data_list[i].notice_num+'</td>';
					shtml+'</tr>' ;


				 }
			  }

			
			
			if(datapage){
				shtml+=page;
			}
			shtml+='</table>';
			$("#myresult").html(shtml);
		   },
		   beforeSend:function(){
			 
		   },
		   error:function(){
			   alert('服务器繁忙请稍后');
		   }


	});
	

	

}

function status_delete(ntid){
	// alert('<?php echo base_url();?>admin.php/admin/index/status_delete');
	$.get("<?php echo base_url();?>index.php/status_delete/index", { ntid: ntid } ,function(id){
    alert(id);});
    document.getElementById(ntid).innerHTML="已删除";
}

function return_table(){
	var shtml = '' ;
	shtml+= '<table width="98%" align="center" border="0" cellpadding="2" cellspacing="1" style="margin-bottom:8px;margin-top:8px;"><tr><td  bgcolor="#EEF4EA" class="title" colspan="6"><span>公告</span></td></tr>';
	shtml+='<tr bgcolor="#E7E7E7"><td >标题</td><td>内容</td><td>发布日期</td><td>截止日期</td><td>接收班级</td><td>发布人</td><td>是否删除</td><td>已查看人数</td><td>忽略人数</td></tr>';
	return shtml ;
//	return '';
}
//修改已读属性
function update_is_read1(id){
	$.ajax({
		type: "POST",
		   url: "<?php echo site_url('admin/index/main');?>?inajax=1" ,
		   data: "action=is_read&time=<?php echo time();?>&id="+id,
		   cache:false,
		   dataType:"json",
		 //  async:false,
		   success: function(msg){ 
			 
			   var code = msg.resultcode ;
			   if(code>=1){
					$("#mystatus_"+id).html("<font color='green'>已读</font>");
			    }else if(code<0){
					//无权操作
			    	window.location.href="<?php echo site_url('admin/login/jump_permition_html') ;?>" ;
				  }else{
						//已经修改过了
				  }
		   }
		


	});
}
//修改已删除属性
function update_is_read(id){
	$.ajax({
		type: "POST",
		   url: "<?php echo site_url('admin/index/main');?>?inajax=1" ,
		   data: "action=is_read&time=<?php echo time();?>&id="+id,
		   cache:false,
		   dataType:"json",
		 //  async:false,
		   success: function(msg){ 
			 
			   var code = msg.resultcode ;
			   if(code>=1){
					$("#mystatus_"+id).html("<font color='green'>已读</font>");
			    }else if(code<0){
					//无权操作
			    	window.location.href="<?php echo site_url('admin/login/jump_permition_html') ;?>" ;
				  }else{
						//已经修改过了
				  }
		   }
		


	});
}
</script>
<style></style>
</head>
<body leftmargin="8" topmargin='8'>
<table width="98%" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td><div style='float:left'> <img height="14" src="<?php echo base_url();?>/static/image/admin/book1.gif" width="20" />&nbsp;欢迎使用公告管理系统。 </div>
     </td>
  </tr>
  
</table>
<div id="myresult">

</div>



<table width="98%" align="center" border="0" cellpadding="4" cellspacing="1" bgcolor="#CBD8AC" style="margin-bottom:8px">

  <tr bgcolor="#FFFFFF">
    <td width="25%" bgcolor="#FFFFFF">您的级别：<?php echo $group_name ;?></td>
    <td width="75%" bgcolor="#FFFFFF">当前所在的用户组是：<font color="red"><?php echo $group_name ;?></font></td>
  </tr><!--
  <tr bgcolor="#FFFFFF">
    <td>软件版本信息：</td>
    <td><?php echo $version ;?></td>
  </tr>
   <tr bgcolor="#FFFFFF">
    <td>网站默认超级管理员用户：</td>
    <td><?php echo $web_admin_master ;?>    如果修改此选项，对应数据库也要进行修改</td>
  </tr>
  <tr bgcolor="#FFFFFF">
    <td>网站创始人Email：</td>
    <td><?php echo $web_admin_email ;?></td>
  </tr>
  <tr bgcolor="#FFFFFF">
    <td>后台是否需要密码进行登录：</td>
    <td><?php echo $is_need_passwd ;?></td>
  </tr>
  <tr bgcolor="#FFFFFF">
    <td>是否开启后台记录日志到数据库：</td>
    <td><?php echo $is_write_log_to_database ;?></td>
  </tr>
   <tr bgcolor="#FFFFFF">
    <td>是否开启验证码进行登录后台：</td>
    <td><?php echo $is_need_yzm ;?></td>
  </tr> 
  
--></table>
<table width="98%" align="center" border="0" cellpadding="4" cellspacing="1" bgcolor="#CBD8AC">
  <tr bgcolor="#EEF4EA">
    <td colspan="2" background="<?php echo base_url();?>/static/image/admin/wbg.gif" class='title'><span>使用建议:</span></td>
  </tr>
  <tr bgcolor="#FFFFFF">
    <td height="32">个人博客：</td>
<!--    <td><a href="http://57sy.com" target="_blank"><u>http://57sy.com</u></a></td>-->
    
  </tr>
 
</table>

      <div id="light" class="white_content" style="display:none"></div>
</body>
</html>