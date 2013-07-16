<?php if(!defined('IN_SITE')) exit('Access Denied'); ?>
<?php include template('header'); ?>
<div class="formnav">批量导出</div>
<form action="?action=batch_in&todo=doout" method="post" onsubmit="return CheckForm(this,true);">
<input type="hidden" value="<?php echo $formhash;?>" name="formhash">
<table align="center" class="formtable" cellpadding="0" cellspacing="1" width="97%">
	<tr>
	    <td align="center">
	    	<br/>导出时间:<input name="start"  onClick="WdatePicker({startDate:'%y-%M-01 00:00:00',dateFmt:'yyyy-MM-dd HH:mm:ss',alwaysUseStartDate:true})"/>至<input name="end"  onClick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss',alwaysUseStartDate:true})"/>的所有短网址<br/>
	    	<br/>
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