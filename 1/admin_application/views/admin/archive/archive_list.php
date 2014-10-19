<?php 
if (! defined('BASEPATH')) {
	exit('Access Denied');
}
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>文档管理</title>
<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>/static/css/admin/base.css">
<script src="<?php echo base_url();?>/static/js/jquery.js" language="javascript"></script>
<script src="<?php echo base_url();?>/static/js/validate/validator.js" language="javascript"></script>
<script type="text/javascript" src="<?php echo base_url();?>/static/js/DatePicker/WdatePicker.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>/static/js/admin/common.js"></script>
<script type="text/javascript">
$(document).ready(function() {
//提交
$("#btnSave").click(function(){

    if(!$(this).Valid()||$(this.form).Valid() == "false") return false;
	  
  });
//关闭
  $("#btnClose").click(function(){
     parent.JqueryDialog.Close("close");
  });

});

</script>
</head>
<body>
<div style="float: right;cursor:pointer">
<img src="<?php echo base_url();?>/static/image/admin/refresh.png" onclick="refresh('<?php echo site_url("admin/archive/index/category");?>')" alt="点击刷新" title="点击刷新">
</div>
<div style="clear: both	"></div>
<!--  快速转换位置按钮  -->
<form>

栏目名称：<input type="text" value="" name="" size="18" required="true" valtype="int" />
日期：<input type="text" value="" name="" size="18" required="true" valtype="date"  errmsg="shuru riqi" tip="shuru riqi "  onclick="WdatePicker({dateFmt:'yyyy-MM-dd',maxDate:'%y-%M-#{%d-1}'})"/>
<br />
<input id="btnSave" class="ImgBtn_Big" type="button" value=" 提 交 " name="btnSave">
<input id="btnClose" class="ImgBtn_Big" type="button" value=" 关 闭 " name="btnReset">
</form>

</body>
</html>