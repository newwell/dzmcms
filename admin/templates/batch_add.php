<?php if(!defined('IN_SITE')) exit('Access Denied'); ?>
<?php include template('header'); ?>
<div class="formnav">批量添加</div>
<form action="?action=batch_in&todo=saveadd" method="post" onsubmit="return CheckForm(this,true);">
<input type="hidden" value="<?php echo $formhash;?>" name="formhash">
<table align="center" class="formtable" cellpadding="0" cellspacing="1" width="97%">
	<tr>
	    <td align="right">链接地址:</td>
	    <td width="60px">
	    	<textarea rows="20" cols="80" name="urls" ></textarea>
	    </td>
	    <td valign="top">
	    *不能为空<br/>
	    	例:<br/>
	    	http://www.baidu.com<br/>
	    	http://www.dazan.cn<br/>
	    	http://www.taobao.com<br/>
	    	......<br/>
	    </td>
	</tr>
	<tr>
		<td colspan="3" align="center">
			<input type="reset"" class="formsubmit" value="清空" >
			<input type="submit" class="formsubmit" value="提交" >
		</td>
	</tr>
</table>
</form>
<?php include template('foot'); ?>