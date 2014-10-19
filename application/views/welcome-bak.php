<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>首页</title>
<?php 
// echo base_url().'static/image/favicon.ico';?>
<link rel="shortcut icon" href="<?php echo base_url().'static/image/favicon.ico';?>" type="image/x-icon" /> 
<style type="text/css">
#apDiv1 {
  position:absolute;
  left:0px;
  top:0px;
  width:100%;
  height:100%;
  z-index:-1;
  background-color: #FFFFFF;
}
#u {
  z-index:100;
}
object{border:0px solid silver;width:100%;height:100%;background-color: #FFFFFF;}
</style>
  <script type="text/javascript" src="<?php echo base_url();?>static/js/artDialog/artDialog.js?skin=default"></script>
  <script type="text/javascript" src="<?php echo base_url();?>static/js/artDialog/artDialog.source.js?skin=default"></script>
  <script src="<?php echo base_url();?>static/js/artDialog/plugins/iframeTools.js"></script>
<!--[if lt IE 10]><script src="./static/js/html5.js"></script><![endif]-->
<script language="javascript">
  function chg()
  {
    if (localStorage.page)//localstorage只存字符
      {
        document.getElementById("apDiv1").innerHTML='<object id="obj1" type="text/html" data="'+localStorage.page+'"></object>';//ie无法工作
      }
    else
      {
        localStorage.page=prompt("请输入默认网址（带http://）","http://www.baidu.com");//缺正则检查
        chg();
      }
  }
  function key()
  {
    if(event.keyCode==9)
    {
      localStorage.page=0;chg();
    }
  } //bug 焦点在object失效
  //退出登录
  function login_out(){
    if(confirm("是否确定退出系统")){
      parent.window.location="<?php echo site_url("welcome/login_out");?>";
    }
  }
  

</script>
  <!--jquery min-->
  <script type="text/javascript" src="<?php echo base_url();?>static/js/noty/jquery.min.js"></script>

  <!-- noty -->
  <script type="text/javascript" src="<?php echo base_url();?>static/js/noty/jquery.noty.js"></script>

  <!-- layouts -->
  <script type="text/javascript" src="<?php echo base_url();?>static/js/noty/layouts/topCenter.js"></script><!--
  <script type="text/javascript" src="<?php echo base_url();?>static/js/noty/layouts/bottom.js"></script>
  <script type="text/javascript" src="<?php echo base_url();?>static/js/noty/layouts/bottomCenter.js"></script>
  <script type="text/javascript" src="<?php echo base_url();?>static/js/noty/layouts/bottomLeft.js"></script>
  <script type="text/javascript" src="<?php echo base_url();?>static/js/noty/layouts/bottomRight.js"></script>
  <script type="text/javascript" src="<?php echo base_url();?>static/js/noty/layouts/center.js"></script>
  <script type="text/javascript" src="<?php echo base_url();?>static/js/noty/layouts/centerLeft.js"></script>
  <script type="text/javascript" src="<?php echo base_url();?>static/js/noty/layouts/centerRight.js"></script>
  <script type="text/javascript" src="<?php echo base_url();?>static/js/noty/layouts/inline.js"></script>
  <script type="text/javascript" src="<?php echo base_url();?>static/js/noty/layouts/top.js"></script>
  <script type="text/javascript" src="<?php echo base_url();?>static/js/noty/layouts/topLeft.js"></script>
  <script type="text/javascript" src="<?php echo base_url();?>static/js/noty/layouts/topRight.js"></script>

  -->
  <!-- themes -->
  <script type="text/javascript" src="<?php echo base_url();?>static/js/noty/themes/default.js"></script>

  <!-- 显示前端用户的通知内容-->
  <script type="text/javascript">
  function generate(text,username,ntid) {
    var n = noty({
      text: text,
      type: 'information',
      dismissQueue: true,
      modal: false,
      maxVisible: 1,
      timeout: 0,
      layout: 'topCenter',
      theme: 'defaultTheme',
      callback: {
        onShow: function() {},
        afterShow: function() {},
        onClose: function() {
            $.get("<?php echo base_url();?>index.php/welcome/insert_record", { username: username, ntid: ntid } );
            },
        afterClose: function() {}
    }
    });
    console.log('html: '+n.options.id);
//    setTimeout("generate",1000);
  }
    <?php if ($event_num>0)
    {
      foreach ($event as $item): ?>
      $(document).ready(function() {
      generate('<?php echo $item->content;?>'<?php echo ',';?>'<?php echo $username;?>'<?php echo ',';?>'<?php echo $item->ntid;?>');
      
      });
  <?php endforeach;} ?> 
  </script>




</head>

  <!-- <body onload="chg()"  onkeydown="key()"> -->
<body   onkeydown="key()">

  <button onclick="art.dialog.open('<?php echo base_url();?>index.php/welcome/login_iframe', {title: '登录'});">登录</button>
<!--   |
  <button onclick="art.dialog.open('<?php echo base_url();?>index.php/welcome/login_iframe', {title: '注册'});">注册</button> -->
  |
  <button onclick="
  ">退出</button>

  <div id="apDiv1" >
  </div>

</body>

</html>

