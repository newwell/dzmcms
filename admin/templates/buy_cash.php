<?php if(!defined('IN_SITE')) exit('Access Denied'); ?>
<?php include template('header'); ?>
<div class="formnav"><?php echo $act['title'];?></div>
<form action="?action=buy_cash&todo=cash " method="post" onsubmit="return CheckForm(this,true);">
<input type="hidden" value="<?php echo $formhash;?>" name="formhash">
<input type="hidden" value="<?php echo $fid;?>" name="fid">
<table align="center" class="formtable" cellpadding="0" cellspacing="1" width="97%">
	<tr>
	    <td width="80px" align="right">名称:</td>
	    <td>
	        <input name="name" required="true"/>*不能为空</td>
	</tr>
	<tr>
	    <td width="80px" align="right">所属分类:</td>
	    <td>&nbsp;&nbsp;<?php echo $info['name'];?></td>
	</tr>
	<tr>
	    <td width="80px" align="right">备注:</td>
	    <td>
	        <textarea rows="5" cols="80" name="remark" style="border:#336699 1px solid;"></textarea>
	   		*可以为空</td>
	</tr>
	<tr>
		<td colspan="2" align="center">	<input type="submit" class="formsubmit" value="提交" ></td>
	</tr>
</table>
</form>
<?php include template('foot'); ?>