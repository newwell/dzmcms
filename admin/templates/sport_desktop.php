<?php if(!defined('IN_SITE')) exit('Access Denied'); ?>
<?php include template('header'); ?>

<style> 
.wrap {
position:relative;
} 
.wrap ul { 
background: #eee; 
border: 1px solid #999; 
width: 150px; 
position: absolute; 
display: none; 
-moz-box-shadow: 3px 3px 9px #999; 
-webkit-box-shadow: 3px 3px 9px #999; 
-o-box-shadow: 3px 3px 9px #999; 
box-shadow: 3px 3px 9px #999; 
} 
.wrap ul li { 
border-bottom: 1px solid #ddd; 
line-height: 24px; 
} 
.wrap li.no {border-bottom: none;} 
.wrap ul li a { 
display: block; 
padding-left: .5em; 
} 
.wrap ul li a:hover { 
background-color: #FFBD00; 
}
</style>
<div class="formnav"><?php echo $act['title'];?></div>
<table width="98%"  border="0" cellpadding="0" cellspacing="0" align="center">
<tr><td align="left"><input type="button" class="button_input" value="添加赛事" onclick="JavaScript:location.href='?action=sport_add&todo=add&do='"/></td></tr>
  <tr><td valign="top" align="center" width="100%">
  <div class="desktop">
  	<?php if(is_array($listArr)) { foreach($listArr as $key => $value) { ?>
  	<div class="desktop_itme wrap" id="wrap<?php echo $value['id'];?>">
  	<ul id="menu<?php echo $value['id'];?>">
  	<?php if ($value['status']=="未开赛"){?>
			<li><a href="?action=sport_list&todo=kaisai&id=<?php echo $value['id']?>" title="开赛">开赛</a></li>
			<?php }elseif ($value['status']=="竞赛中"){?>
			<li><a href="?action=sport_list&todo=jiesai&id=<?php echo $value['id']?>" title="结束比赛">结束比赛</a></li>
			<?php }?>
			<li><a href="?action=sport_list&todo=prize&id=<?php echo $value['id']?>" title="颁奖">颁奖</a></li>
			<li><a href="?action=sport_list&todo=doentry&id=<?php echo $value['id']?>" title="报名">报名该赛事</a></li>
			<li><a href="JavaScript:;" onclick="if(confirm('删除不可恢复,同时删除该赛事下的参赛,颁奖记录,确认删除?')){location.href='?action=sport_list&todo=del&id=<?php echo $value['id']?>'}" title="删除">删除</a></li>
		</ul>
  		<div class="desktop_name"><?php echo $value['name'];?></div>
  		<div class="desktop_num">比赛人次/剩余人次<br/><?php echo $value['cansai_renci'];?>/<?php echo $value['people_number'];?></div>
  		<div class="desktop_time">开赛时间:<?php echo gmdate("Y-n-j H:i:s",$value['add_date']) ?></div>
  	</div>
  	<script type="text/javascript">
var EventUnit = { 
	addHandler: function(element, type, handler) {//添加事件处理程序 
		if(element.addEventListener) { 
			element.addEventListener(type, handler, false); 
		} else if(element.attachEvent) { 
			element.attachEvent('on' + type, handler); 
		} else { 
		element['on' + type] = handler; 
	}; 
	}, 
	getEvent: function(event) { 
		return event ? event : window.event; 
	}, 
	preventDefault: function(event) {//取消事件默认动作 
		if(event.preventDefault) { 
			event.preventDefault(); 
		} else { 
			event.returnValue = false; 
		}; 
	}
};
EventUnit.addHandler(window, 'load', function() { 
var wrap = document.getElementById('wrap<?php echo $value['id'];?>'); 
var menu = document.getElementById('menu<?php echo $value['id'];?>');
var menuStyle = menu.style.display; 
var x = wrap.offsetLeft + wrap.clientWidth, y = wrap.offsetTop + wrap.clientHeight; 
var w = 0, h = 0; 
var left = 0, top = 0; 

EventUnit.addHandler(wrap, 'contextmenu', function(event) { 
event = EventUnit.getEvent(event); 
EventUnit.preventDefault(event); 

	menu.style.display = 'block'; 
	w = menu.clientWidth; 
	h = menu.clientHeight; 
	left = (x - event.clientX >= w) ? event.clientX - wrap.offsetLeft : event.clientX - wrap.offsetLeft - w; 
	top = (event.clientY + h <= y) ? event.clientY - wrap.offsetTop : event.clientY - wrap.offsetTop - h; 
	menu.style.left = left + 'px'; 
	menu.style.top = top + 'px';
	}); 

	EventUnit.addHandler(document, 'click', function() { 
		menu.style.display = menuStyle; 
	}); 
});
</script>
  	<?php } }?>
  </div>
  </td></tr>
</table>


<?php include template('foot'); ?>