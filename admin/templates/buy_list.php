<?php if(!defined('IN_SITE')) exit('Access Denied'); ?>
<?php include template('header'); ?>
<div class="formnav"><?php echo $act['title'];?></div>
<script type="text/javascript"> 
document.body.onload = function(){
    document.getElementById("buy_card").focus();
};
</script>
<form action="?action=sport_add&todo=saveadd" method="post" onsubmit="return CheckForm(this,true);">
<input type="hidden" value="<?php echo $formhash;?>" name="formhash">
<table align="center" class="formtable" cellpadding="0" cellspacing="1" width="97%">
	<tr>
	    <td align="right">读卡:</td>
	    <td>
	    	<input name="buy_card" id="buy_card" required="true" type="text" onkeydown="if(event.keyCode==13){get_user_info();}"/><input onclick="get_user_info();" class="formsubmit" type="button" value="读卡"/>
	    	<span id="username"></span>
	    </td>
	    <td align="right">比赛开始时间:</td>
	    <td><input name="start_time" autocomplete="off" class="Wdate" onclick="WdatePicker({startDate:'%y-%M-01 00:00:00',dateFmt:'yyyy-MM-dd HH:mm:ss',alwaysUseStartDate:true})"/></td>
	</tr>
	<tr>
	    <td align="right">类型:</td>
	    <td><select name="type" onchange="show_sport_type(this.value);">
	    		<option value="time_trial">计时赛</option>
	    		<option value="no_time_trial" selected="selected">非计时赛</option>
	    </select></td>
	    <td align="right">积分消耗:</td>
	    <td>
	    	<input name="deduction" required="true"/>*不能为空
	    </td>
	</tr>
	<tr>
	    <td align="right">服务费:</td>
	    <td><span id="service_charge_time" style="display: none;">每<input name="service_charge_time" style="width: 35px;"/>分钟,</span>扣除<input name="service_charge" required="true"/>积分</td>
	    <td align="right">人数上限:</td>
	    <td><input name="people_number" required="true" value="10" />*不能为空</td>
	</tr>
	<tr>
	    <td align="right">支持可以再次买入:</td>
	    <td><select name="rebuy">
	    		<option value="1">是</option>
	    		<option value="0">否</option>
	    </select></td>
	    <td align="right">参赛次数:</td>
	    <td><input name="entry_number"/></td>
	</tr>
	<tr>
		<td align="right">涨盲时间:</td>
		<td><input name="zhangmang_time"/>分钟</td>
	    <td align="right">截至买入时间:</td>
	    <td><input name="stop_entry_time" autocomplete="off"  required="true" class="Wdate" onclick="WdatePicker({startDate:'%y-%M-01 00:00:00',dateFmt:'yyyy-MM-dd HH:mm:ss',alwaysUseStartDate:true})" />*不能为空</td>
	</tr>
	<tr>
	    <td align="right">休息时间:</td>
	    <td><input name="rest_time" />分钟</td>
	    <td align="right">记分牌:</td>
	    <td><input name="scoreboard"/></td>
	</tr>
	<tr>
	    <td align="right">桌数:</td>
	    <td><input name="MaxBLNum" /></td>
	    <td align="right">座位数:</td>
	    <td><input name="seating"/></td>
	</tr>
	<tr>
	    <td align="right" >发牌员:</td>
	    <td colspan="3"><select name="deingcoholr_id">
	    	<option value="0">-不指定-</option>
	    	<?php if (is_array($deingcoholrList)){foreach ($deingcoholrList as $value) {?>
	    	<option value="<?php echo $value['id'];?>"><?php echo $value['name'];?></option>	
	    	<?php }}?>
	    </select></td>
	</tr>
	<tr >
	    <td width="80px" align="right">备注:</td>
	    <td colspan="3">
	        <textarea rows="5" name="remark" style="width:100%;border:#336699 1px solid;"></textarea>
	   		*可以为空</td>
	</tr>
	<tr>
		<td colspan="4" align="center">	<input type="button" class="formsubmit" value="提交" ></td>
	</tr>
</table>
</form>
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
	    <td><input name="buy_card" id="buy_card" required="true" type="text" onkeydown="if(event.keyCode==13){get_user_info();}"/><input onclick="get_user_info();" class="formsubmit" type="button" value="读卡"/></td>
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
	$.get("?action=member_find&todo=js_user_info&card="+$("#buy_card").val(),function(data,status){
		$("#username").html(data.name);
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
}
</script>
<?php include template('foot'); ?>