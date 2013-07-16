<?php if(!defined('IN_SITE')) exit('Access Denied'); ?>
<?php include template('header'); ?>
<div class="formnav">批量导入</div>
<form action="?action=batch_in&todo=doin" method="post" method="post" enctype="multipart/form-data">
<input type="hidden" value="<?php echo $formhash;?>" name="formhash">
<table align="center" class="formtable" cellpadding="0" cellspacing="1" width="97%">
	<tr>
          <td width="40%" align="right">标准xls例子</td>
          <td width="60%"><a href="./data/upload/demo.xls">点这里现在标准XLS</a></td>
    </tr>
	<tr>
          <td width="40%" align="right">请选择要导入的文件</td>
          <td width="60%">
          <input type="file" name="xls[]" id="xls[]" />(仅支持EXCEL的XLS格式)</td>
    </tr>
	<tr>
		<td colspan="3" align="center">
			<input type="reset"" class="formsubmit" value="清空" >
			<input type="submit" class="formsubmit" value="提交" >
		</td>
	</tr>
</table>
</form>
<?php include template('foot');?>