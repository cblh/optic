(function() {
  $(".flyout-btn").click(function() {/*增删类以实现CSS旋转动画*/
    $(".flyout-btn").toggleClass("btn-rotate");
    $(".flyout").find("a").removeClass(); //避免重复clicked类
	//实现展开缩回菜单
    return $(".flyout").removeClass("flyout-init fade").toggleClass("expand");
  });

   $(".flyout").find("a").click(function() {
     $(".flyout-btn").toggleClass("btn-rotate");//旋转回菜单
     $(".flyout").removeClass("expand").addClass("fade");//消失
     return $(this).addClass("clicked");
   });

   $("#setpath").click(function(){//显示按钮
	$("#set").removeClass();
   })
}).call(this);