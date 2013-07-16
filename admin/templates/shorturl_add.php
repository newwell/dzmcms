<?php if(!defined('IN_SITE')) exit('Access Denied'); ?>
<?php include template('header'); ?>
<div class="formnav">添加短网址</div>
<form action="?action=shorturl_list&todo=saveadd" method="post" onsubmit="return CheckForm(this,true);">
<input type="hidden" value="<?php echo $formhash;?>" name="formhash">
<table align="center" class="formtable" cellpadding="0" cellspacing="1" width="97%">
	<tr>
	    <td width="80px" align="right">链接地址:</td>
	    <td>
	        <input name="url" required="true" type="url" style="border:#336699 1px solid;width: 45%;height: 40px;"/>
	   		*不能为空</td>
	</tr>
	<tr>
	    <td width="80px" align="right">别名:</td>
	    <td>
	        <input name="alias" style="border:#336699 1px solid;width: 45%;height: 40px;"/>
	   		*自定义的短网址,可以不填写</td>
	</tr>
	<tr>
	    <td width="80px" align="right">备注:</td>
	    <td>
	        <textarea rows="5" cols="80" name="annotation" style="border:#336699 1px solid;"></textarea>
	   		*可以为空</td>
	</tr>
	<tr>
		<td colspan="2" align="center">	<input type="submit" class="formsubmit" value="提交" ></td>
	</tr>
</table>
</form>
<?php include template('foot'); ?>