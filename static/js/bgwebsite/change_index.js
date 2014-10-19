/* Done by ERVINE */
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
	noty({
		text: "不喜欢默认首页？输入你喜欢的网站地址吧~<input id='input_web'  value='http://www.baidu.com'></input>",
		type: 'warning',
		dismissQueue: true,
		modal: false,
		maxVisible: 1,
		timeout: 0,
		layout: 'bottomCenter',
		theme: 'defaultTheme',
		afterClose: function() {},
		buttons: [
			{addClass: 'btn btn-info', text: '不修改了', onClick: function($noty) {
				$noty.close();
			  }
			},
			{addClass: 'btn btn-primary', text: '恢复默认', onClick: function($noty) {
				localStorage.removeItem("page");
				$noty.close();
				window.location.reload();
			  }
			},
			{addClass: 'btn btn-danger', text: '确认修改', onClick: function($noty) {
				web = document.getElementById("input_web").value;
				$noty.close();
				var re = new RegExp("(http|ftp|https):\/\/", "g"); //检查http://头部
				var flag1 = web.match(re);
				if(flag1==null)web="http://"+web;
				flag=IsOk(web);
				if(flag){
					if(localStorage.page!=web){localStorage.page=web;chg();}
				}else{
					chs();
				}
			  }
			}
		  ]
	});
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
		ctitle(1);
		for ( x in obj.item)
		{
			generate(obj.item[x].title,obj.item[x].content,obj.item[x].ntid,obj.username,obj.item.length-x);
		}
	}
	else
	{
		setTimeout("checknt()",10000);
	}
}
//通知显示
var t,t2;//时间定时器
function generate(title,content,ntid,username,flag) {
	var n = noty({
	text: title,
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
	},
	afterClose: function() {if(flag==1){setTimeout("checknt()",10000);}}
	},
	buttons: [
        {addClass: 'btn btn-primary', text: '查看详细通知', onClick: function($noty) {
            getweb("http://optic.sinaapp.com/index.php/welcome/insert_record/?ntid="+ntid+"&username="+username);
            $noty.close();
          	noty({dismissQueue: true, force: true, layout: 'topCenter', theme: 'defaultTheme', text: content , type: 'success'});
			ctitle(0);
          }
        },
        {addClass: 'btn btn-danger', text: '忽略该通知', onClick: function($noty) {
            getweb("http://optic.sinaapp.com/index.php/welcome/insert_record/?ntid="+ntid+"&username="+username+"&read="+"0");
            $noty.close();
          	ctitle(0);
          }
        }
      ]
	});
	//console.log('html: '+n.options.id);
}
// 测试
function ts(){
	noty({dismissQueue: true, force: true,timeout: 1500, layout: 'bottomCenter', theme: 'defaultTheme', text: "期待更强大的功能~" , type: 'success'});
}
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
//通知标题
var tts=1;
function ctitle(num)
{
	if(num){
		t2 = setTimeout("ctitle(1)",1000);
		switch(tts){
		case 1: document.title="首页 - 有新的消息~";tts=2;break;
		case 2: document.title="首页";tts=1;break;}}
	else {clearTimeout(t2);document.title="首页";}
}





