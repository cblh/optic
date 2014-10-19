<?php 
if (! defined('BASEPATH')) {
	exit('Access Denied');
}

?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>后台模块树---数据处理平台</title>
<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>/static/css/admin/base.css">
<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>/static/css/admin/window.css" />
<script src="<?php echo base_url();?>/static/js/jquery.js" language="javascript"></script>
<script src="<?php echo base_url();?>/static/js/validate/validator.js" language="javascript"></script>

<script src="<?php echo base_url();?>/static/js/admin/common.js" language="javascript"></script>
<script type="text/javascript" src="<?php echo base_url();?>/static/js/admin/tipswindown.js"></script>
<style type="text/css">
.mymain{width:100%}
.mymain .mymain_left{
	width:30%;
	float:left;
	border:solid 1px #D1DDAA
}
.mymain .mymain_left .mymain_left_title{
	background:#E7E7E7;
	
	line-height:30px;
	font-size:14px ;
	font-weight:bold;
	padding-left:7px
}


.mymain .mymain_right{
	width:69%;
	float:right;
	border:solid 1px #D1DDAA;
}

.edit_del{
	display:none;
}
</style>
</head>
<body>

<div style="" class="mymain">
<div class="mymain_left">
	<div class="mymain_left_title">模块树
	<span style="float:right">
	<img style="cursor: pointer;padding:10px; border:none" title="点击刷新" alt="点击刷新" onclick="reflesh_data()" src="<?php echo base_url() ;?>/static/image/admin/refresh.png">
	</span>
	</div>
	<div style="padding: 8px">
		模块树类别选择：<select id="tree_type" onchange="type_ajax()" style="width:100px">
		<?php 
			if($type_array){
				foreach($type_array as $type_key=>$type_val){
		?>
		<option value="<?php echo $type_key ;?>"><?php echo $type_val ;?></option>
		<?php 
				}
			}
		?>
		</select><img id="introduce" title="说明" alt="说明" src="<?php echo base_url();?>/static/image/admin/info.gif" name="introduce"  style="cursor:pointer">
	</div>
	<div id="tree_list" style="padding:10px">
	
	
	</div>
	<div><input type="button" id="make_xml" onclick="make_xml()" value="生成数据文件" style="cursor:pointer"></div>
</div>
<div class="mymain_right">

<form name="form2" id="newform">

<table width="98%" border="0" cellpadding="2" cellspacing="1" bgcolor="#D1DDAA" align="center" style="margin-top:8px">
<tr bgcolor="#E7E7E7">
	<td height="24" colspan="" >&nbsp;模块信息添加&nbsp;</td>
</tr>
<tr align="" bgcolor="#FAFAF1" height="22">
	<td >模块的父ID:<input type="text" name="tree_parent_id" class="form_input" id="tree_parent_id" readonly>不可以进行填写，只读 </td>
</tr>
<tr align="" bgcolor="#FAFAF1" height="22">
	<td >父模块名称:<input type="text" name="p_name" class="form_input" id="p_name" readonly required="true">自动创建</td>
</tr>
<tr align="" bgcolor="#FAFAF1" height="22">
	<td >当前添加的模块类别ID:<input type="text" name="new_tree_type" class="form_input" id="new_tree_type" required="true" readonly>自动创建</td>
</tr>
<tr align="" bgcolor="#FAFAF1" height="22">
	<td >添加的模块名称:<input type="text" name="tree_name" class="form_input" id="tree_name" required="true"><font color="red">*</font>必填</td>
</tr>
<tr align="" bgcolor="#FAFAF1" height="22">
	<td >模块排序:<input type="text" name="tree_orderby" class="form_input" id="tree_orderby" valtype="int" maxlength="128" value="0"><font color="red">*</font>必填，数字越大越在前</td>
</tr>
<tr bgcolor="#FAFAF1">
<td height="28" colspan="">
	&nbsp;

	<input type="button" value="提&nbsp;&nbsp;交" class="coolbg" id='btnSave'>
</td>
</tr>

</table>

</form>


</div>
</div>

<script type="text/javascript">
$(document).ready(function() {
	ajax_tree_list('');//页面加载的时候进行调用

	$("#btnSave").click(function(){
		var tree_parent_id = $("#tree_parent_id").val();
		var addurl = "<?php echo site_url('admin/admin_tree/tree/tree_list') ;?>?inajax=1&action=tree_list_add_do" ;
		 if($("#newform").Valid() == false || !$("#newform").Valid()) return false;
		if(tree_parent_id != ""){
			$.ajax({
				   type: "POST",
				   url: addurl ,
				   data: "tree_name="+($("#tree_name").val())+"&id="+$("#tree_parent_id").val()+"&tree_type="+parseInt($("#new_tree_type").val())+"&tree_orderby="+parseInt($("#tree_orderby").val()),
				   cache:false,
				   dataType:"json",
				   success: function(msg){
					   var code = msg.resultcode ;
					   var message = msg.resultinfo.errmsg ;
					   if(code>=1){
							//插入数据成功
						   ajax_tree_list($("#tree_type").val());
						}else if(code<0){
							window.location.href="<?php echo site_url('admin/login/jump_permition_html') ;?>"; //无权限页面
						}else{
							
							alert(message);
						}
					  
					
				   }
						
	});				
		}else{
			alert("请选择左边的模块树进行添加");
			return false ;
		}
		
		
	});

	 $("#introduce").bind("mouseover",function(){
	       $("#introduceMessage").show();
	  }).bind("mousemove",function(event){
	     var pageX = event.pageX;
	      var pageY = event.pageY;
	      if(pageX == 'undefined'){
		    pageX = event.clientX+(document.body.scrollLeft || document.documentElement.scrollLeft);
	      }
		  if(pageY =='undefined'){
		    pageY = event.clientY+(document.body.scrollTop || document.documentElement.scrollTop);
		  }
		 $("#introduceMessage").css({left:pageX+'px',top:(pageY+8)+'px'});
	  }).bind("mouseout",function(){
	       $("#introduceMessage").hide();
	  });

	  
});

//
function ajax_tree_list(type){
	var tree_type ;
	if(type>=1){
		tree_type = parseInt($("#tree_type").val());
	}else if(parseInt(getQueryStringValue("tree_type")) >=1){
		tree_type = getQueryStringValue("tree_type") ;
	}else{
		tree_type = $("#tree_type").val() ;
	}
	var o = "#tree_type option[value="+"'"+tree_type+"']" ;
	$(o).attr("selected", true);   
	
	//var tree_type = parseInt($("#tree_type").val()) ;
	url = "<?php echo site_url('admin/admin_tree/tree/tree_list') ;?>?s=1&inajax=1" ;
	$.ajax({
		   type: "POST",
		   url: url ,
		   data: "tree_type="+tree_type,
		   cache:false,
		   dataType:"json",
		   success: function(msg){
			 
			 var data_list = msg.resultinfo.list ; //列表数据
			 var shtml = getChild(data_list,1);
			 
			 if(shtml == ""){
				//如果没有的话
				  $("#tree_parent_id").val(0);
				  $("#p_name").val('顶层模块');
				
				  $("#new_tree_type").val($("#tree_type").val());
				  $("#tree_list").html("你正在创建大的分类");
			}else{
				var div ="<div style='width:100px;border:solid 0px;margin-left:3px;cursor:pointer;margin-bottom:4px' id=''><a href='javascript:void(0)' onclick='create_parent()'>点击创建大类</a></div>";
				
				$("#tree_list").html(div+shtml);
			}
			 
		   }
				
});	

}

function reflesh_data(){
	ajax_tree_list($("#tree_type").val());
}
//下拉框改变的时候触发事件
function type_ajax(){
	ajax_tree_list($("#tree_type").val());
}

function getChild(module_tree,memuid){
	if(module_tree.length <=0){
		return '' ;
	}
    var shtml = '<table cellpadding="0" cellspacing="0" border="0">\n';
	for(var i=0; i<module_tree.length;i++)
	{
	   var supmemuid = memuid*10 + i;
	   var ModuleId = module_tree[i]['tree_id'];
	   var ModuleName = module_tree[i]['tree_name'];
	  
	   var Child = module_tree[i]['Child'];
	   newModuleName = "'"+ModuleName+"'" ;
	   var SltClkStr = 'DoSelect('+ModuleId+','+newModuleName+')';
	   
	//   if(ModuleTypeId == 0) SltClkStr = 'DoSelectRoot('+ModuleId+')';
	   if(Child != undefined && Child.length>0)
		{
		  shtml += '<tr onclick="'+SltClkStr+'" id="my_do_tr_'+supmemuid+'">\n';
          shtml += '<td width="20"><img id="img'+supmemuid+'" border="0" src="<?php echo base_url();?>/static/image/admin/minus.png"  onclick="ShowMenu('+supmemuid+');" style="font-weight:bold;cursor:pointer"></td>\n';
		  shtml += '<td style="font-weight:bold;cursor:pointer;width:100px"><div id="select'+ModuleId+'" class="Menu" > '+ModuleName+' </div></td><td width="30" class="edit_del" id="p1_'+ModuleId+'" style="padding-left:5px"><img src="<?php echo base_url() ;?>/static/image/admin/edit.png" onclick="edit_module('+ModuleId+',\''+ModuleName+'\')"></td><td class="edit_del" id="p2_'+ModuleId+'"><img src="<?php echo base_url() ;?>/static/image/admin/gtk-del.png" onclick="del_module('+ModuleId+','+supmemuid+')"></td>\n';
		  shtml += '</tr>\n'; 
		  var inChild = Child;
		 
           shtml += '<tr id="Menu'+supmemuid+'" style="display:\'\'" >\n';
		  shtml += '<td width="20"> </td>'; 
          shtml += '<td>'; 
		  shtml += getChild(inChild,supmemuid); //递归函数获取整树
		  shtml += '</td></tr>'; 
		}
		else
		{
          shtml += '<tr onclick="'+SltClkStr+'" id="my_do_tr_'+supmemuid+'">\n';
		  shtml += '<td width="27"><img id="img'+supmemuid+'" border="0" src="<?php echo base_url();?>/static/image/admin/closed_.png"></td>\n';
		  shtml += '<td style="cursor:pointer;"><div id="select'+ModuleId+'" class="Menu" style="width:100px;display:inline-block"> '+ModuleName+' </div></td><td width="30" class="edit_del" id="p1_'+ModuleId+'" style="padding-left:5px"><img src="<?php echo base_url() ;?>/static/image/admin/edit.png" onclick="edit_module('+ModuleId+',\''+ModuleName+'\')"></td><td class="edit_del" id="p2_'+ModuleId+'"><img src="<?php echo base_url() ;?>/static/image/admin/gtk-del.png" onclick="del_module('+ModuleId+','+supmemuid+')"></td>\n';
		  shtml += '</tr>'; 
		}
	}
     shtml += '</table>';
	 return shtml;
}


function ShowMenu(ID) 
{ 
	var MenuID = "Menu"+ID;
	var ImgID = "img" + ID;
	if(document.getElementById(MenuID).style.display=="none") 
	{ 
	 document.getElementById(MenuID).style.display=""; 
	 
	 document.getElementById(ImgID).src="<?php echo base_url();?>/static/image/admin/minus.png"; 
	}else 
	{ 
	  document.getElementById(MenuID).style.display="none"; 
	  document.getElementById(ImgID).src="<?php echo base_url();?>/static/image/admin/add.png"; 
	} 
}
function DoSelect(ID,ModuleName) 
{ 
	
  var oEle = $("div .Menu")
  for(var i=0;i<oEle.length;i++)
      oEle[i].style.cssText="";

  var SeleID = "select" + ID;
  document.getElementById(SeleID).style.cssText="border:1px Solid #AAAA11;color:Red;width:130px";//"border:1px solid #F00000;";
  $("#tree_parent_id").val(ID);
  $("#p_name").val(ModuleName);
  $("#new_tree_type").val($("#tree_type").val());
  $("#tree_name").val('') ;
  $("#tree_orderby").val('0');
  $(".edit_del").hide();
  $("#p1_"+ID).show();
  $("#p2_"+ID).show();
} 

//创建大的分类
function create_parent(){

	  $("#tree_parent_id").val(0);
	  $("#p_name").val('顶层分类');
	  $("#new_tree_type").val($("#tree_type").val());
	  $("#tree_name").val('') ;
}


//编辑
function edit_module(id,name){
	tipsWindown('修改模块树',"iframe:<?php echo site_url("admin/admin_tree/tree/tree_edit");?>?id="+id+"&name="+encodeURIComponent(name)+"&tree_type="+$("#tree_type").val(),800,450,"true","","true","");
	
}

//删除分类
function del_module(id,supmemuid){
	require_url = "<?php echo site_url('admin/admin_tree/tree/tree_del_do') ;?>" ;
	if(confirm('是否确定删除，此操作不可恢复，请谨慎')){
		$.ajax({
			   type: "POST",
			   url: require_url+"?inajax=1" ,
			   data: "id="+id+"&time=<?php echo time();?>",
			   cache:false,
			   dataType:"json",
			 //  async:false,
			   success: function(msg){
				
				var resultcode = msg.resultcode ;
				var message = msg.resultinfo.errmsg ;
				if(resultcode >=1){//成功信息 
					$("#my_do_tr_"+id).remove();
					var o = "tr[id^="+"'my_do_tr_"+supmemuid+"']";
					$(o).remove();
					$('#tree_name').val('');
					$('#p_name').val('');
					
				}else if(resultcode == 0){
					alert(message);
					return false ;
				}else{//无权限的时候
					window.location.href="<?php echo site_url('admin/login/jump_permition_html') ;?>";
				}
				
			   },
			   error:function(){
				 alert('服务器繁忙请稍后');  
				 return false ;
			   }
			  
			});	
		}else{
			return false ;
		}
}

function make_xml(){

	var tree_type = $("#tree_type").val();
	if(tree_type <1){
		alert('暂无模块树数据');
		return false ;
	}
	var require_url = "<?php echo site_url('admin/admin_tree/tree/make_xml');?>" ;
	$.ajax({
		   type: "POST",
		   url: require_url+"?inajax=1" ,
		   data: "tree_type="+tree_type+"&time=<?php echo time();?>",
		   cache:false,
		   dataType:"json",
		 //  async:false,
		   success: function(msg){
			
			var resultcode = msg.resultcode ;
			var message = msg.resultinfo.errmsg ;
			var dir = msg.resultinfo.list.dir ;
			if(resultcode >=1){//成功信息 
				//console.dir(msg);
				 alert('生成的文件是在"'+dir+"\"目录下面，请查看");
				 $("#make_xml").val('生成数据文件');
				 $("#make_xml").attr("disabled","");
				
			}else if(resultcode == 0){
				alert(message);
				$("#make_xml").val('生成数据文件');
				$("#make_xml").attr("disabled","");
				return false ;
			}else{
				window.location.href="<?php echo site_url('admin/login/jump_permition_html') ;?>";
			}
			
		   },
		   beforeSend:function(){
				  $("#make_xml").val('后台正在生成数据，请稍后');
				  $("#make_xml").attr("disabled","disabled");
		},
		   error:function(){
			 alert('服务器繁忙请稍后');  
			 return false ;
		   }
		  
		});	
	
}

</script>
<div id='introduceMessage' style="display:none;">
  <h1>数据说明</h1>
   <ul>
      <li>此处的下拉框的值 可用到配置文件libraries\admin_common.php 里面 方法<font color="red">return_treetype_list进行配置</font></li>
      <li>
      	<pre>
		      	//function 
		####################################
		#返回模块树对应的类型
		####################################
		#备注此处的key值必须是数字，因为数据库中type对应的字段类型是int型的
		function return_treetype_list(){
			return array(
				1=>'图书',
				2=>'商品',
				3=>'家电',
				4=>'食品',	
				5=>'视频',
				6=>'图片',
				7=>'图标',	
			);
		}
		请注意：次数数组的下标必须是数字的形式 
      	</pre>
      </li>
   </ul>
</div>
</html>
<style type="text/css">
#introduceMessage { display:none;height:auto;border:solid #CED9E71px;background-color:#CED9E7;z-index:10000;position:absolute;}
#introduceMessage h1 {color:#04468C; font-size:14px; padding-left:10px;padding-top:10px;}
#introduceMessage ul{padding:3px;margin-left:10px}
#introduceMessage li{padding:3px;list-style:none}
</style>

</body>
</html>
