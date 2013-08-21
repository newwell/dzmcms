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
<table align="center" class="formtable" cellpadding="0" cellspacing="1" width="97%">
	<tr>
	    <td align="right">参赛名称:</td>
	    <td><?php echo $sportinfo['name'];?></td>
	    <td align="right">支付方式:</td>
	    <td>
	    	<input type="radio" name="payment_type" value="balance"/>积分
	    	<input type="radio" name="payment_type" value="jiangli_jifen" checked="checked"/>奖励积分
	    </td>
	</tr>

	<tr>
		<td colspan="4" align="center">	<input type="submit" class="formsubmit" value="提交" ></td>
	</tr>
</table>
</form>
<?php }?>
<?php include template('foot'); ?>