// // 嵌入网址
// function chg()
// {
// 	if (localStorage.page)//localstorage只存字符
// 	{
// 		document.getElementById("apDiv1").innerHTML='<iframe id="obj1" src="'+localStorage.page+'" ></iframe>';
// 		document.getElementById("apDiv1").onmouseover=disp();
// 		//document.getElementById("apDiv1").innerHTML='<object id="obj1" type="text/html" data="'+localStorage.page+'" ></object>';
// 	}
// 	else
// 	{
// 		document.getElementById("apDiv1").innerHTML='<iframe id="obj1" src="default/default.php" ></iframe>';
// 	}
// }
// 检查网址
function IsOk(url)
{
	var flag=getweb("http://optic.sinaapp.com/index.php/welcome/ISok/?url="+url);
	
	if(flag)return Number(flag); //返回字符
	return 0;
}
// 更改网址
function chs()
{
	var flag=0;
	while(!flag)
	{
		web=prompt("请输入正确的默认网址","http://www.baidu.com");/*换样式*/
		var re = new RegExp("(http|ftp|https):\/\/", "g"); //检查http://头部
		var flag1 = web.match(re);
		if(flag1==null)web="http://"+web;
		flag=IsOk(web);
	}
	if(localStorage.page!=web){localStorage.page=web;chg();}
}

//定时按钮消失
function disp()
{
	var t=setTimeout("document.getElementById('set').style.visibility='hidden' ",10000);
}
//检测通知
function checknt(){
	var  flag=getweb("http://optic.sinaapp.com/index.php/welcome/notic/");
	if(flag)
	{
		var obj = eval ("(" + flag + ")");
		for ( x in obj.item)
		{
			generate(obj.item[x].content,obj.item[x].ntid,obj.username);
		}
	}
	else
	{
		setTimeout("checknt()",10000);
	}
}
//通知显示
var t;//时间定时器
function generate(text,ntid,username) {
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
	onShow: function() {clearTimeout(t);},
	afterShow: function() {},
	onClose: function() {
		getweb("http://optic.sinaapp.com/index.php/welcome/insert_record/?ntid="+ntid+"&username="+username);
	},
	afterClose: function() {t=setTimeout("checknt()",10000);}
	}
	});
	//console.log('html: '+n.options.id);
}
// 测试
function ts(){
		alert("期待更强大的功能~");}
//发起同步get
function getweb(url)
{
	var xmlhttp;
	if (window.XMLHttpRequest)
	{// code for IE7+, Firefox, Chrome, Opera, Safari
	  xmlhttp=new XMLHttpRequest();
	}
	else
	{// code for IE6, IE5
	  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	}
	xmlhttp.open("GET",url,false);
	xmlhttp.send();
	if (xmlhttp.readyState==4 && xmlhttp.status==200)
	{
		return xmlhttp.responseText ;
	}
	return 0;
}





