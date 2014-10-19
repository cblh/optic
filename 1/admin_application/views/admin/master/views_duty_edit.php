<?php 
if (! defined('BASEPATH')) {
	exit('Access Denied');
}
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>团队职务编辑</title>
<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>/static/css/admin/base.css">
<script src="<?php echo base_url();?>/static/js/jquery.js" language="javascript"></script>
<script src="<?php echo base_url();?>/static/js/validate/validator.js" language="javascript"></script>

<script type="text/javascript" src="<?php echo base_url();?>/static/js/admin/common.js"></script>
<script type="text/javascript">
$(document).ready(function() {
//提交
var id = getQueryStringValue("id") ;
var duty_name = decodeURIComponent(getQueryStringValue("duty_name"));

$("#myresult").html("当前正在编辑<font color='red' style='font-size:14px;font-weight:bold'>"+duty_name+"</font>的信息");
$("#btnSave").click(function(){
	if($("#newform").Valid() == false || !$("#newform").Valid()) return false;
	
	 var url="<?php echo site_url("admin/master/team/team_duty_edit");?>?action=team_duty_edit_do";	
	 var length = $("input:checked").length;
	 var perms = new Array() ;
	 $("input:checked").each(function(i){
		 perms[i] = $(this).val() ;
	});
		
	var isopen = $("#isopen").val();
	var duty_name = $("#duty_name").val();
		$.ajax({
			   type: "POST",
			   url: url ,
			   data: "id="+id+"&perms="+perms+"&isopen="+isopen+"&duty_name="+duty_name,
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
function selected_checked(id){
	
	if($("#parent_"+id).attr("checked")){
		$("#parent_"+id).attr("checked",false) ;
	}else{
		$("#parent_"+id).attr("checked",true) ;
	}
}
//点击进行所有的进行选择或者是取消
function all_checked(id){
	
	var checked = $("#"+id).attr("checked");
	var p = "input[id^='"+id+"']";	
	$(p).attr("checked",checked);
	
	
}

//显示和隐藏
function show_hide(index){
	if(document.getElementById("show_hide_"+index).style.display == 'none'){
		document.getElementById("show_hide_"+index).style.display = '';
		document.getElementById("image_"+index).src="<?php echo base_url() ;?>/static/image/admin/minus.png";
	}else{
		document.getElementById("show_hide_"+index).style.display = 'none';
		
		document.getElementById("image_"+index).src="<?php echo base_url() ;?>/static/image/admin/add.png";
	}
}
</script>
<style type="text/css">


</style>
</head>
<body>

<div style="clear: both	"></div>
<div >
<span id="myresult"></span>

</div>
<!--  快速转换位置按钮  -->
<form  id="newform">
<table width="98%" border="0" cellpadding="2" cellspacing="1" bgcolor="#D1DDAA" align="center" style="margin-top:8px">
<?php 
/*  echo '<pre>';
print_r($result['left'] );  */
$i = 0 ;
$j = 0 ;
$k_index = 0 ;

if($result['left']){
	foreach($result['left'] as $key=>$value){	
?>
<tr align="" bgcolor="#E7E7E7" height="26">
	<td>
	<div style="font-size:14px ; font-weight:bold ;"><img src="<?php echo base_url() ;?>/static/image/admin/minus.png" width="16px" height="16px" onclick="show_hide(<?php echo $i ;?>)" id="image_<?php echo $i ;?>">&nbsp;<?php echo $key;?><!-- <input type="checkbox" value='<?php echo $key ;?>' id="parent_<?php echo $i?>" onclick="all_checked('parent_<?php echo $i?>')"> --></div>
	</td>
	
</tr>
<tr align="" bgcolor="#FAFAF1" height="22" id="show_hide_<?php echo $i ;?>">
	<td>
	<?php 
		if($value){
			foreach($value as $k_=>$v_){
				$childindex = "parent_".$i."_".$j;
				$c_ = '' ;
				if(in_array($v_['url'],$exists_data)){
					$c_ = "checked" ;
				}
				echo "<div style='margin-top:4px;color:green;font-weight:bold;font-size:12px;clear:both;margin-left:9px'>{$k_}<input type='checkbox' value='{$v_['url']}' id=\"$childindex\" onclick=\"all_checked('$childindex');\"  $c_></div><div style='font-size:12px;color:black;margin-top:3px'>如果下面的小功能要有权限,上面的大类必须选择</div>" ;
				echo '<hr size=1 color="green" >';
			
				foreach($v_ as $k1=>$v1){
					$checked = '';
					if(in_array($v1, $exists_data)){
						$checked = "checked";
						if($k1 != 'url'){
							$k1="<font color='red'>".$k1."</font>";
						}
						
					}
					$index = $i."_".$j."_".$k_index;
					if($k1 != 'url'){
						echo "<div style=\"float:left;border:solid 0px;display:block;width:160px;margin-left:3px;cursor:pointer\"  ><input type='checkbox' name='' id=\"parent_$index\"  value='{$v1}' style='border:none;cursor:pointer' $checked><span onclick=\"selected_checked('$index')\">".$k1."</span></div>" ;
					}
					
					$k_index++ ;
				}
				$j++ ;
			}
		}
		
	?>
	</td>

</tr>
<?php 
	$i++ ;
	}
}
?>
<tr align="" bgcolor="#FAFAF1" height="30">
	<td >职务名称:
		<input type="text" name="duty_name" value="<?php echo $duty_name ;?>" required="true" id="duty_name">
	</td>
</tr>
<tr align="" bgcolor="#FAFAF1" height="30">
	<td >是否开启:<select id="isopen" required="true" class="input_validation-failed"><option value="">选择</option><option value="1" <?php if($isopen == 1){echo "selected='selected'";}?>>开启</option><option value="0" <?php if($isopen == 0){echo "selected='selected'";}?>>关闭</option></select></td>
</tr>
</table>
<div style="margin-top:12px;width:98%;margin-left:10px">
<input id="btnSave" class="ImgBtn_Big" type="button" value=" 提 交 " name="btnSave">
<input id="btnClose" class="ImgBtn_Big" type="button" value=" 关 闭 " name="btnReset">
</div>
</form>

</body>
</html>