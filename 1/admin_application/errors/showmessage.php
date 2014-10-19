<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Transitional//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd'>
<html>
<head>
<title>页面温馨提示</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta http-equiv='Refresh' content='<?php echo  $timeout ;?>;URL=<?php echo site_url($url);?>'>
<style>
html, body{margin:0; padding:0; border:0 none;font:14px Tahoma,Verdana;line-height:160%;background:white}
a{text-decoration:none; color:#174B73; border-bottom:1px dashed gray}
a:hover{color:#F60; border-bottom:1px dashed gray}
div.message{margin:10% auto 0px auto;clear:both;padding:5px;border:1px solid silver; text-align:center; width:45%}
span.wait{color:blue;font-weight:bold}
span.error{color:red;font-weight:bold}
span.success{color:blue;font-weight:bold}
div.msg{margin:0px 0px}
</style>
</head>
<body>
<div class="message">
	<div class="msg">

	<?php 
		if($iserror){
	?>
	<span class="success"><?php echo $message ;?></span>
	<?php }else{?>
	
	<span class="error"><?php echo $message ;?></span>
	<?php }?>

	</div>
	<div class="tip">
	<?php if(!$iserror){?>
	<present name="closeWin" >
		页面将在 <span class="wait"><?php echo  $timeout ;?></span> 秒后自动关闭，如果不想等待请点击 <a href="<?php echo site_url($url);?>">这里</a> 关闭
	<?php }else{?>
	
		页面将在 <span class="wait"><?php echo  $timeout ;?></span> 秒后自动跳转，如果不想等待请点击 <a href="<?php echo site_url($url);?>">这里</a> 跳转
		<?php }?>
	</present>
	</div>
</div>
</body>
</html>