<?php if(!defined('IN_SITE')) exit('Access Denied'); ?>
<?php include template('header'); ?>
<div class="formnav"><?php echo $act['title'];?></div>
<form action="?action=member_changePassword&todo=changePassword&do=" method="post" onsubmit="return CheckForm(this,true);">
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
<form action="?action=member_changePassword&todo=dochangePassword" method="post" onsubmit="return CheckForm(this,true);">
<input type="hidden" value="<?php echo $formhash;?>" name="formhash">
<input type="hidden" name="card" value="<?php echo $card;?>"/>
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
</table>
<br/>
<table align="center" class="formtable" cellpadding="0" cellspacing="1" width="97%">
	<tr>
		<td align="right">原密码:</td>
		<td><input type="password" name="odl_pwd"/></td>
	</tr>
	<tr>
		<td align="right">新密码:</td>
		<td><input type="password" name="new_pwd"/></td>
	</tr>
	<tr>
		<td align="right">再次输入:</td>
		<td><input type="password" name="new2_pwd"/></td>
	</tr>
	<tr>
		<td align="center" colspan="2"><input type="submit" class="formsubmit" value="提交"/></td>
	</tr>
</table>
</form>
<?php }?>
<?php include template('foot'); ?>