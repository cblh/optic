<?php 
if (! defined('BASEPATH')) {
	exit('Access Denied');
}

?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>后台操作日志---数据处理平台</title>
<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>/static/css/admin/base.css">
<script src="<?php echo base_url();?>/static/js/jquery.js" language="javascript"></script>
<script type="text/javascript" src="<?php echo base_url();?>/static/js/DatePicker/WdatePicker.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>/static/js/admin/common.js"></script>
<script><!--
$(function(){
	$("#search").click(function(){
		ajax_data(1);
	});

	$("#export").click(function(){
		var log_action = $("#log_action").val();
		var log_person = $("#log_person").val();
		var log_time1 = $("#log_time1").val();
		var log_time2 =$("#log_time2").val();	
		var log_status = $("#log_status").val();
		var param = '' ;
		window.location.href = "<?php echo site_url('admin/logs/log/export') ;?>?log_action="+log_action+'&log_person='+log_person+"&log_time1="+log_time1+"&log_time2="+log_time2+"&log_status="+log_status;
		
	});
});
function common_request(page){
	var log_action = $("#log_action").val();
	var log_person = $("#log_person").val();
	var log_time1 = $("#log_time1").val();
	var log_time2 =$("#log_time2").val();	
	var log_status = $("#log_status").val();
	var page = page ;
	var url="<?php echo site_url("admin/logs/log/log_list");?>?inajax=1&action=search";
	
	$.ajax({
		   type: "POST",
		   url: url ,
		   data: "log_action="+log_action+"&log_person="+log_person+"&log_time1="+log_time1+"&log_time2="+log_time2+"&log_status="+log_status+"&page="+page+"&time=<?php echo time();?>",
		   cache:false,
		   dataType:"json",
		 //  async:false,
		   success: function(msg){
			   
			//console.dir(msg);
			var code = '' ;
			code = msg.resultcode ;
			if(code >=1){//成功
				result = msg.resultinfo.list.result ;
				var shtml = '';
				for(var i in result){
						
						shtml+='<tr height="22" bgcolor="#FFFFFF" align="center" onmouseout="javascript:this.bgColor=\'#FFFFFF\';" onmousemove="javascript:this.bgColor=\'#FCFDEE\';">';
						shtml+='<td>'+result[i].log_id+'</td>';
						shtml+='<td>'+result[i].log_action+'</td>';
						shtml+='<td><a href=""><u>'+result[i].log_person+'</u></a></td>';
						shtml+='<td>'+result[i].log_time+'</td>';
						shtml+='<td><font color="green">'+result[i].log_ip+'</font></td>';
						shtml+='<td>'+result[i].log_status+'</td>';
						shtml+='<td>'+result[i].log_sql+'</td>';
						shtml+='<td>'+result[i].log_message+'</td>';					
					}
				
					$("#result_p").html(shtml);
					//alert();
					$("#mynew_page").html(" ");
					$("#mynew_page").html(msg.resultinfo.obj);
					$("#ajax_wait").html(' ');				
			}else{
				window.location.href="<?php echo site_url('admin/login/jump_permition_html') ;?>" ;
			}
			
			
		   },
		   beforeSend:function(){
			  $("#ajax_wait").html('<font color="red">正在查询请稍后。。</font>');
		   },
		   error:function(){
			   alert('服务器繁忙请稍候');
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


--></script>
 
<style>
.pg{
	float:right
}
.pg a {
    background-color: #FFFFFF;
    background-repeat: no-repeat;
    border: 1px solid #C2D5E3;
    color: #333333;
    display: inline;
    float: left;
    height: 20px;
    margin-left: 4px;
    overflow: hidden;
    padding: 3 8px;
    text-decoration: none;
}
.pg a.current{
	background-color: #E5EDF2;
}
tr td{
	word-break;break-all ;
	word-wrap:break-word
}
</style>
 
</head>
<body leftmargin="0" topmargin="0">

<div class="nav_des">
<span class="des">日志log</span>
<a href="<?php echo site_url('admin/logs/log/log_list');?>" class="nav_des_hover">日志列表</a>
<span style="margin:3px; padding:3px; cursor:pointer"><img src="<?php echo base_url() ;?>/static/image/admin/excel.png" alt="导出execl" title="导出数据" id="export"></span>
<span style="float:right">
<img src="<?php echo base_url();?>/static/image/admin/refresh.png" onclick="refresh('<?php echo site_url("admin/logs/log/log_list");?>')" alt="点击刷新" title="点击刷新" style="cursor: pointer;padding:10px; border:none">
</span>
<div id="ajax_wait" style="float:right"></div>
</div>
<div style="clear: both"></div>
<!--  搜索表单  --> 	
<form name='form3' onsubmit="return false ">
<table width='100%'  border='0' cellpadding='1' cellspacing='1' bgcolor='#CBD8AC' align="center" style="margin-top:8px">
  <tr bgcolor='#EEF4EA'>
    <td background='<?php echo base_url();?>/static/image/admin/wbg.gif' align=''>
      <table border='0' cellpadding='0' cellspacing='0'>
        <tr>
          <td width='90' align='center'>操作动作：</td>
          <td width='100'>
          <select name='log_action' style='width:100' id="log_action">
         	<option value='all'>全部</option>
         	<?php 
         		if($action){
         			foreach ($action as $key=>$val){
         				echo "<option value='{$key}'>{$val}</option>";
         			}
         		}
         	?>
         
          </select>
        </td>
        <td width='70'>
           	操作人：
        </td>
       
        <td width='160'>
          	<input type='text' name='log_person' value='' style='width:100px' id="log_person" />
        </td>
         <td width='70'>
           	是否成功：
        </td>
    	 <td width='70'>
           	<select id="log_status">
           		<option value="">全部</option>
           		<option value="1">成功</option>
           		<option value="0">失败</option>
           	</select>
        </td>     
         <td width='90' align='center'>操作时间：</td>
    	<td width='260'>
          	<input type="text" value="" name="log_time1" id="log_time1" size="18" valtype="date"  errmsg="" tip=" "  onclick="WdatePicker({dateFmt:'yyyy-MM-dd',maxDate:'%y-%M-#{%d+1}'})"/>
       		~<input type="text" value="" name="log_time"  id ="log_time2" size="18" valtype="date"  errmsg="" tip=" "  onclick="WdatePicker({dateFmt:'yyyy-MM-dd',maxDate:'%y-%M-#{%d+1}'})"/>
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

<table width="98%" border="0" cellpadding="2" cellspacing="1" bgcolor="#D1DDAA" align="center" style="margin-top:8px">
<tr bgcolor="#E7E7E7">
	<td height="24" colspan="10" >&nbsp;日志记录 &nbsp;</td>
</tr>
<tr align="center" bgcolor="#FAFAF1" height="22">
	<td width="3%">日志ID</td>
	<td width="8%">动作</td>
	<td width="5%">操作人</td>
	<td width="8%">操作时间</td>
	<td width="7%">操作者ip地址</td>
	<td width="6%">操作是否成功</td>
	<td width="24%">执行sql</td>
	<td width="12%">详细信息</td>
	
</tr>
<tbody id="result_p">
<?php 

	foreach($result as $k=>$v){

?>
<tr align='' bgcolor="#FFFFFF" onMouseMove="javascript:this.bgColor='#FCFDEE';" onMouseOut="javascript:this.bgColor='#FFFFFF';" height="22" >
	<td><?php echo $v['log_id'] ;?></td>
	<td><?php echo $v['log_action'];?></td>
	<td align="left"><?php echo $v['log_person'];?></td>
	<td><?php echo $v['log_time'];?></td>
	<td><?php echo $v['log_ip'];?></td>
	<td><?php echo $v['log_status'];?></td>
	<td><?php echo $v['log_sql'];?></td>
	<td><?php echo $v['log_message'];?></td>
</tr>
<?php }?>
</tbody>

<tr align="right" bgcolor="#EEF4EA">

	<td height="36" colspan="8" align="right" id="mynew_page">
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