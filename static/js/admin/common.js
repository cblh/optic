//common.js file
/*
 *author wangjian
 *
 * time 2013-04-17
 */
//刷新网页
function refresh(url){
	window.location.href=url ;
}


//删除数据
/* @params 此功能基于jquery
 * @id 要删除的数据id
 * @require_url 请求的url地址
 * @成功之后要进行跳转的地址
 * no_permition_url 没权限地址
 */
function ajax_delete(id,require_url,jump_url,no_permition_url){
	if(confirm('是否确定删除，此操作不可恢复，请谨慎')){
		$.ajax({
			   type: "POST",
			   url: require_url+"?inajax=1" ,
			   data: "id="+id+"&time=<?php echo time();?>",
			   cache:false,
			   dataType:"json",
			 //  async:false,
			   success: function(msg){
				//console.dir(msg);
				var resultcode = msg.resultcode ;
				var message = msg.resultinfo.errmsg ;
				if(resultcode >=1){//成功信息 
					window.location.href=jump_url ;
				}else{
					window.location.href=no_permition_url ;
					//alert(no_permition_url);
					//alert(message);
				}
				
			   },
			   error:function(){
				 alert('服务器繁忙请稍后');  
			   }
			  
			});	
		}else{
			return false ;
		}
}
//通过js获取参数
function getQueryStringValue(name) {
    var str_url, str_pos, str_para;
    var arr_param = new Array();
    str_url = window.location.href;
    str_pos = str_url.indexOf("?");
    str_para = str_url.substring(str_pos + 1);
    if (str_pos > 0) {
        //if contain # ----------------------begin 
        str_para = str_para.split("#")[0];
        //-----------------------------------end
        arr_param = str_para.split("&");
        for (var i = 0; i < arr_param.length; i++) {
            var temp_str = new Array()
            temp_str = arr_param[i].split("=")
            if (temp_str[0].toLowerCase() == name.toLowerCase()) {
                return temp_str[1];
            }
        }
    }
    return "";
}