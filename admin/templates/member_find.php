<?php if(!defined('IN_SITE')) exit('Access Denied'); ?>
<?php include template('header'); ?>
<div class="formnav">会员查询</div>
<form action="?action=member_find&todo=dofind" method="post" onsubmit="return CheckForm(this,true);">
<input type="hidden" value="<?php echo $formhash;?>" name="formhash">
<table align="center" class="formtable" cellpadding="0" cellspacing="1" width="97%">
	<tr>
	    <td width="80px" align="right">读卡:</td>
	    <td>
	        <input name="card" type="text" style="border:#336699 1px solid;"/></td>
	    <td width="80px" align="right">会员卡编号:</td>
	    <td>
	        <input name="cardid" type="text" style="border:#336699 1px solid;"/></td>
	</tr>
	<tr>
		<td colspan="4" align="center">	<input type="submit" class="formsubmit" value="查询"></td>
	</tr>
</table>
</form>
<?php include template('foot'); ?>