	$(document).ready(function(){
		$(".wui_contentLeftTitle").click(function(){
			$(this).next("div").slideToggle("fast");
			$(this).toggleClass("wui_contentLeftTitleActive");
			$(this).siblings(".wui_contentLeftTitleActive").removeClass("wui_contentLeftTitleActive");
		});
		$(".wui_contentLeftItemList").click(function(){
			$(".wui_contentLeftItemList").removeClass("wui_contentLeftItemListClick");
			$(this).toggleClass("wui_contentLeftItemListClick");
		});
		$(".wui_contentLeftItemList").mouseover(function(){
			$(this).toggleClass("wui_contentLeftItemListMousemove");
		});
		$(".wui_contentLeftItemList").mouseout(function(){
			$(".wui_contentLeftItemList").removeClass("wui_contentLeftItemListMousemove");
		});
		$(".wui_contentMain").load(function(){
	    	var wui_content_width = $(".wui_content").width();//获取宽度
			var wui_contentLeft_width = $(".wui_contentLeft").width();
			wui_contentMain_width = wui_content_width - wui_contentLeft_width - 1;//计算出宽度
			$(".wui_contentMain").animate({width:wui_contentMain_width},"fast");//动画形式改变宽度
	    });
		$(window).resize(function() {
			var wui_content_width = $(".wui_content").width();
			var wui_contentLeft_width = $(".wui_contentLeft").width();
			wui_contentMain_width = wui_content_width - wui_contentLeft_width - 1;
			$(".wui_contentMain").animate({width:wui_contentMain_width},"fast");
		});
	});	