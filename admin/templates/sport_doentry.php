<?php if(!defined('IN_SITE')) exit('Access Denied'); ?>
<?php include template('header'); ?>
<div class="formnav">会员参赛报名</div>
<script type="text/javascript"> 
document.body.onload = function(){
    document.getElementById("card").focus();
};
</script>
<form action="?action=sport_list&todo=doentry&id=<?php echo $id;?>" method="post" onsubmit="return CheckForm(this,true);">
<input type="hidden" value="<?php echo $formhash;?>" name="formhash">
<table align="center" class="formtable" cellpadding="0" cellspacing="1" width="97%">
	<tr>
	    <td width="80px" align="right">读卡:</td>
	    <td>
	        <input name="card" id="card" required="true" type="text" style="border:#336699 1px solid;"/>
	   		<input type="submit" class="formsubmit" value="提交">
	   	</td>
	</tr>
</table>
</form>
<?php if (!empty($member_info)) {?>
<input type="hidden" value="<?php echo $formhash;?>" name="formhash">
<table align="center" class="formtable" cellpadding="0" cellspacing="1" width="97%">
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
<form action="?action=sport_list&todo=save_entry&id=<?php echo $id;?>" method="post" onsubmit="return CheckForm(this,true);">
<input type="hidden" value="<?php echo $formhash;?>" name="formhash">
<input type="hidden" value="<?php echo $id;?>" name="sport_id">
<input type="hidden" value="<?php echo $card;?>" name="card">
<input type="hidden" value="<?php echo $member_info['balance'];?>" name="balance" id="balance">
<input type="hidden" value="<?php echo $member_info['jiangli_jifen'];?>" name="jiangli_jifen" id="jiangli_jifen">
<input type="hidden" value="<?php echo $sportinfo['type']?>" name="sporttype" id="sporttype">
<table align="center" class="formtable" cellpadding="0" cellspacing="1" width="97%">
	<tr>
	    <td align="right">参赛名称:</td>
	    <td><?php echo $sportinfo['name'];?></td>
	    <td align="right">优先支付方式:</td>
	    <td>
		    <select name="payment_type" id="payment_type">
		    	<option value="balance">积分</option>
		    	<option value="jiangli_jifen" selected="selected">奖励积分</option>
		    </select>
	    </td>
	</tr>
	<tr <?php if ($sportinfo['type']!="pk_trial"){
		echo 'style="display: none;"';
	}?>>
		<td align="right">PK赛买入金额:</td>
		<td colspan="3"><input type="text" value="<?php echo $sportcharge;?>" name="sportcharge" id="sportcharge"></td>
	</tr>
	<tr>
		<td colspan="4" align="center">	<input type="button" class="formsubmit" value="提交" onclick="check_jifen_submit(<?php echo $card;?>,<?php echo $id;?>,this.form);"></td>
	</tr>
</table>
</form>
<script type="text/javascript">
function check_jifen_submit(card,sid,form) {
	balance = parseInt($("#balance").val());
	jiangli_jifen = parseInt($("#jiangli_jifen").val());
	sportcharge = parseInt($("#sportcharge").val());
	payment_type = $("#payment_type").val();
	sporttype= $("#sporttype").val();
	if(sporttype=="pk_trial"){//PK赛的时候买入金额不能为0
		if (sportcharge=="0"||isNaN(sportcharge)) {
			alert('PK赛买入金额不能为零');return false;
		}
	}
	if((sportcharge)>((balance)+(jiangli_jifen))){
		alert('积分不够,无法完成报名!');
		return false;
	}
	if(payment_type=='jiangli_jifen'){
		if(jiangli_jifen>=sportcharge){
			form.submit();
			return true;
		}else{
			cha = sportcharge-jiangli_jifen;
			if(confirm('奖励积分不够,剩余'+cha+"分,从积分中扣除")){
				form.submit();
			}return false;
		}
	}else{
		if(balance>=sportcharge){
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