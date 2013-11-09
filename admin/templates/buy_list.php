<?php if(!defined('IN_SITE')) exit('Access Denied'); ?>
<?php include template('header'); ?>
<div class="formnav"><?php echo $act['title'];?></div>
<link type="text/css" rel="stylesheet" href="script/hiAlert/css/alert.css">
<script src="script/hiAlert/jquery.alert.js" type="text/javascript"></script>
<script src="script/textSearch.jquery.js" type="text/javascript"></script>

<script type="text/javascript"> 
document.body.onload = function(){
    document.getElementById("card").focus();
};
</script>

<table align="center" class="formtable" cellpadding="0" cellspacing="1" width="97%">
	<tr>
	    <td align="right">读卡:</td>
	    <td>
	    	<input name="buy_card" id="buy_card" required="true" type="text" onkeydown="if(event.keyCode==13){get_user_info();}"/><input onclick="get_user_info();" class="formsubmit" type="button" value="读卡"/>
	    </td>
	</tr>
	<tr>
	    <td align="right" >会员名:</td>
	    <td colspan="3"><span id="username">未获取</span></td>
	</tr>
	<tr>
		
	</tr>
</table>
<br/>

<form action="?action=sport_add&todo=saveadd" method="post">
<input type="hidden" value="<?php echo $formhash;?>" name="formhash" id="formhash">
<input type="hidden" id="card" value="" name="card">
<table width="97%" cellpadding="1" cellspacing="1" align="center" class="listtable" id="rspan">
	<tr>
		<td><input type="button" value="选择商品" class="button_input" onclick="JavaScript:hiBox('#showbox_chanpin', '选择商品',700,'','','.a_close');"></td>
	</tr>
	<tr>
		<th>名称</th>
		<th>简码</th>
		<th>单位</th>
		<th>所需积分</th>
		<th>所需奖励积分</th>
	</tr>
</table>
</form>

<div id="showbox_chanpin" style="display: none;">
<iframe name="good_list" src="?action=goods_list&todo=list&do=buy_list" style="height: 500px; width: 97%; z-index: 1;overflow: auto;border: 0; "></iframe>
</div>

<script type="text/javascript">
function add_buy_cart(ids) {
	$("#popup_overlay").hide();
	$("#popup_container").hide();
	$.get("?action=goods_list&todo=js_good_info&ids="+ids,function(data,status){
		$("#rspan").html($("#rspan").html()+data);
	  });
}
//得到用户信息
function get_user_info() {
	$("#username").html("获取失败");
	$("#card").val($("#buy_card").val());
	$.get("?action=member_find&todo=js_user_info&card="+$("#buy_card").val(),function(data,status){
		$("#username").html(data.name);
	  }, "json");
}
</script>
<?php include template('foot'); ?>