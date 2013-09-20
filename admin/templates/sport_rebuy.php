<?php if(!defined('IN_SITE')) exit('Access Denied'); ?>
<?php include template('header'); ?>
<div class="formnav">rebuy</div>
<script type="text/javascript"> 
document.body.onload = function(){
    document.getElementById("buy_card").focus();
};
</script>

<?php if (!empty($member_info)) {?>
<input type="hidden" value="<?php echo $formhash;?>" name="formhash">
<table align="center" class="formtable" cellpadding="0" cellspacing="1" width="97%">
	<tr>
		<th colspan="4">rebuy用户信息</th>
	</tr>
	<tr>
	    <td width="80px" align="right">姓名:</td>
	    <td><?php echo $member_info['name'];?></td>
	    <td width="80px" align="right">昵称:</td>
	    <td><?php echo $member_info['nickname'];?></td>
	</tr>
	<tr>
	    <td width="80px" align="right">积分:</td>
	    <td style="color: red;"><?php if (empty($member_info['balance'])){ echo "0.00";}else {echo $member_info['balance'];};?></td>
	    <td width="80px" align="right">奖励积分:</td>
	    <td><?php echo $member_info['jiangli_jifen'];?></td>
	</tr>
</table><br>
<form action="?action=sport_withdraw&todo=dorebuy" method="post" onsubmit="return CheckForm(this,true);">
<input type="hidden" value="<?php echo $formhash;?>" name="formhash">
<input type="hidden" value="<?php echo $sport_id;?>" name="sport_id">
<input type="hidden" value="<?php echo $entry_id;?>" name="entry_id">
<input type="hidden" value="<?php echo $card;?>" name="card">


<table align="center" class="formtable" cellpadding="0" cellspacing="1" width="97%">
	<tr>
		<th colspan="4">rebuy赛事信息</th>
	</tr>
	<tr>
	    <td align="right">参赛名称:</td>
	    <td><?php echo $sport_info['name'];?></td>
	    <td align="right">rebuy费用:</td>
	    <td><input value="<?php echo $sportcharge;?>" disabled="disabled" name="sportcharge" id="sportcharge"></td>
	</tr>
</table>
<br>
<table align="center" class="formtable" cellpadding="0" cellspacing="1" width="97%">
	<tr>
		<th colspan="4">支付用户信息</th>
	</tr>
	<tr>
	    <td align="right">读卡:</td>
	    <td><input name="buy_card" id="buy_card" required="true" type="text" onkeydown="if(event.keyCode==13){get_user_info();}"/></td>
	    <td align="right">会员号:</td>
	    <td><input value="未获取" disabled="cardid" name="cardid" id="cardid"></td>
	</tr>
	<tr>
	    <td align="right">用户名/昵称:</td>
	    <td><input value="未获取" disabled="disabled" name="user_name" id="user_name"></td>
	    <td align="right">积分:</td>
	    <td><input value="未获取" disabled="disabled" name="balance" id="balance"></td>
	</tr>
	<tr>
	    <td align="right">奖励积分:</td>
	    <td><input value="未获取" disabled="disabled" name="jiangli_jifen" id="jiangli_jifen"></td>
	    <!-- <td align="right">积分合计:</td>
	    <td><input value="未获取" disabled="disabled" id="jifen_heji"></td> -->
	    <td align="right">优先支付方式:</td>
	    <td>
		    <select name="payment_type" id="payment_type">
		    	<option value="balance">积分</option>
		    	<option value="jiangli_jifen" selected="selected">奖励积分</option>
		    </select>
	    </td>
	</tr>

	<tr>
		<td colspan="4" align="center">	<input type="button" class="formsubmit" value="提交" onclick="check_jifen_submit(this.form);"></td>
	</tr>
</table>
</form>
<script type="text/javascript">
function get_user_info() {
	alert('11111111111111');
	$.get("?action=member_find&todo=js_user_info&card="+$("#buy_card").val(),function(data,status){
		$("#user_name").val(data.name);
		$("#cardid").val(data.cardid);
		$("#balance").val(data.balance);
		$("#jiangli_jifen").val(data.jiangli_jifen);
		//$("#jifen_heji").val(parseInt(data.jiangli_jifen)+parseInt(data.balance));
	  }, "json");
}
function check_jifen_submit(form) {
	balance = parseInt($("#balance").val());
	jiangli_jifen = parseInt($("#jiangli_jifen").val());
	sportcharge = parseInt($("#sportcharge").val());
	payment_type = $("#payment_type").val();

	if(((balance)+(jiangli_jifen))<(sportcharge)){
		alert('积分不够,无法完成报名!');
		return false;
	}
	if(payment_type=='jiangli_jifen'){
		if(jiangli_jifen>sportcharge){
			form.submit();
			return true;
		}else{
			cha = sportcharge-jiangli_jifen;
			if(confirm('奖励积分不够,剩余'+cha+"分,从积分中扣除")){
				form.submit();
			}return false;
		}
	}else{
		if(balance>sportcharge){
			form.submit();
			return true;
		}else{
			cha = sportcharge-balance;
			if(confirm('积分不够,剩余'+cha+"分,从奖励积分中扣除")){
				form.submit();
			}return false;
		}
	}
	//alert(payment_type);
	//alert(jiangli_jifen);
	//alert(sportcharge);
	
	//form.submit();
}
</script>
<?php }?>
<?php include template('foot'); ?>