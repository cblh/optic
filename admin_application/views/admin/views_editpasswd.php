<?php 
if (! defined('BASEPATH')) {
	exit('Access Denied');
}
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>修改密码</title>
<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>/static/css/admin/base.css">
<script src="<?php echo base_url();?>/static/js/jquery.js" language="javascript"></script>
<script src="<?php echo base_url();?>/static/js/validate/validator.js" language="javascript"></script>
<script type="text/javascript" src="<?php echo base_url();?>/static/js/admin/common.js"></script>
<script type="text/javascript">
$(document).ready(function() {
//提交
$("#btnSave").click(function(){

    if(!$(this).Valid()||$(this.form).Valid() == false) return false;
	  
  });
//关闭
  $("#btnClose").click(function(){
     parent.JqueryDialog.Close("close");
  });

});

</script>
</head>
<body>


<!--  快速转换位置按钮  -->
<div style="border:dashed 1px #D1DDAA; padding:15px; margin:5px">
<form name="" method="post"  action="<?php echo site_url('admin/members/user/edit_passwd_do') ;?>" id="myform">
用户名：<input type="text" value="<?php echo $username ;?>" name="" size="18" required="true"  disabled="true" /><br />

新密码：<input type= "password" value="" name="newpasswd" size="18" required="true"  errmsg="shuru riqi" tip="shuru riqi "  /><br />
第二次：<input type= "password" value="" name="newpasswd2" size="18" required="true"  errmsg="shuru riqi" tip="shuru riqi "  /><br />

<br />
<input id="btnSave" class="ImgBtn_Big" type="submit" value=" 提 交 " name="btnSave">

</form>
</div>
</body>
</html>