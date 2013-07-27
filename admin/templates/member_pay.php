<?php if(!defined('IN_SITE')) exit('Access Denied'); ?>
<?php include template('header'); ?>
<div class="formnav">账户充值</div>
<form action="?action=member_pay&todo=pay" method="post" onsubmit="return CheckForm(this,true);">
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
<form action="?action=member_pay&todo=dopay" method="post" onsubmit="return CheckForm(this,true);">
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
	<tr>
		<td align="right">充值金额:</td>
		<td><input name="dopay" /><input type="hidden" name="card" value="<?php echo $card;?>"/></td>
		<td align="right">当前兑换比率:</td>
		<td><span id="rmb">1</span>元人民币&nbsp;&nbsp;=&nbsp;&nbsp;<span id="jifenzhi"><?php echo $setting_rate * 1;?></span>积分</td>
	</tr>
	<tr align="center"><td colspan="4" >
		<input type="submit" class="formsubmit" value="充值"/>
	<td></tr>
</table>
</form>
<?php }?>
<?php include template('foot'); ?>