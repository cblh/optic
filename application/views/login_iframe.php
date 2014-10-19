<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>登录</title>
<?php 
$this->load->helper('url');
// echo uri_string();
// echo base_url();?>
<script src="<?php echo base_url();?>static/js/artDialog/artDialog.source.js?skin=default"></script>
<script src="<?php echo base_url();?>static/js/artDialog/plugins/iframeTools.source.js"></script>
<script src="<?php echo base_url();?>static/js/clienthint/clienthint.js"></script>
<style>
body, div, dl, dt, dd, ul, ol, li, h1, h2, h3, h4, h5, h6, pre, code, form, fieldset, legend, input, button, textarea, p, blockquote, th, td { margin: 0; padding: 0; }
fieldset, img { border: 0; }
img { display:inline-block; }
:focus { outline: 0; }
address, caption, cite, code, dfn, em, strong, th, var, optgroup { font-style: normal; font-weight: normal; }
h1, h2, h3, h4, h5, h6 { font-size: 100%; font-weight: normal; }
abbr, acronym { border: 0; font-variant: normal; }
input, button, textarea, select, optgroup, option { font-family: inherit; font-size: inherit; font-style: inherit; font-weight: inherit; }
code, kbd, samp, tt { font-size:100%; }
input, button, textarea, select { *font-size: 100%;
}
ol, ul { list-style: none outside none; }
table { border-collapse: collapse; border-spacingpacing: 0; }
caption, th { text-align: left; }
sup, sub { font-size: 100%; vertical-align: baseline; }
:link, :visited, ins { text-decoration: none; }
blockquote, q { quotes: none; }
blockquote:before, blockquote:after, q:before, q:after { content: ''; content: none; }
/**/
html, body { border:none 0; }
body { font-size:75%; color:#666; font-family:'Microsoft Yahei', Tahoma, Arial!important; font-family:'宋体', Tahoma, Arial; text-align:center; }
a { color:#214FA3; }
a:hover { text-decoration:underline; }
#login-form { padding-top:30px; }
#login-form p { padding:5px;  }
#login-form input { width:15em; padding:4px; border:1px solid #CCC; }
#login-form input:focus { border-color:#426DC9; }
#login-form .login-form-error { background:#FFFBFC; border-color:#F00 !important; }
</style>
</head>

<body>
<!-- <div style="background:#FAFBDD; padding:3px;"><a href="javascript:art.dialog.close();">iframe中关闭</a></div> -->
<!-- <form id="login-form" action="./login" method="post" target="_top"> -->
<form id="login-form" action="<?php base_url(); ?>login_validation" method="post" target="_top">
<?php
if(isset($error)){
	echo '<p><label>'.$error.'</label></p>';}
?>
<p><label>帐号：<input id="login-form-username" name="username" type="text" onkeyup="showHint(this.value)"></label></p>
<!-- <p>提示: <span id="txtHint"></span></p> -->
<p><label>密码：<input id="login-form-password" name="password" type="password"></label></p>
<?php
if(isset($error)){
	echo '<p><label>登录：<input type="submit" value="登录"></label></p>';}
?>
</form>
<script>
(function () {
var parent = art.dialog.parent,				// 父页面window对象
	api = art.dialog.open.api,	// 			art.dialog.open扩展方法
	$ = function (id) {return document.getElementById(id)},
	form = $('login-form'),
	username = $('login-form-username'),
	password = $('login-form-password');

if (!api) return;

parent.document.title = '系统登录';

// 操作对话框
api.title('公告系统登录')
	// 自定义按钮
	.button(
		{
			name: '登录',
			callback: function () {
				if (check(username) && check(password)) form.submit();
				return false;
			},
			focus: true
		},
		{
			name: '取消',
			callback: function () {
				parent.document.title = '首页';
			},
		}
	);

// 表单验证
var check = function (input) {
	if (input.value === '') {
		inputError(input);
		input.focus();
		return false;
	} else {
		return true;
	};
};

// 输入错误提示
var inputError = function (input) {
	clearTimeout(inputError.timer);
	var num = 0;
	var fn = function () {
		inputError.timer = setTimeout(function () {
			input.className = input.className === '' ? 'login-form-error' : '';
			if (num === 5) {
				input.className === '';
			} else {
				fn(num ++);
			};
		}, 150);
	};
	fn();
};

window.onload = function () {setTimeout(function () {username.focus()}, 240);};
})();
</script>
</body>
</html>
