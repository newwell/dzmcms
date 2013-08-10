<?php if(!defined('IN_SITE')) exit('Access Denied'); ?>
<?php include template('header'); ?>
<div class="formnav"><?php echo $act['title'];?></div>
<form action="?action=buy_cash&todo=docash" method="post" onsubmit="return CheckForm(this,true);">
<input type="hidden" value="<?php echo $formhash;?>" name="formhash">
<input type="hidden" value="<?php echo $fid;?>" name="fid">
<table align="center" class="formtable" cellpadding="0" cellspacing="1" width="97%">
	<tr>
	    <td width="80px" align="right">读卡:</td>
	    <td>
	        <input name="card" required="true"/>*不能为空</td>
	</tr>
	<tr>
	    <td width="80px" align="right">支付方式:</td>
	    <td><select name="method_payment">
	    	<?php foreach (GetConfig("method_payment") as $key => $value) {?>
	    		<option value="<?php echo $key;?>"><?php echo $value;?></option>
	    	<?php }?>
	    	</select></td>
	</tr>
	<tr>
	    <td width="80px" align="right">支付金额:</td>
	    <td>
	        <input name="payment_amount" required="true"/>*不能为空</td>
	</tr>
	<tr>
	    <td width="80px" align="right">抵用积分:</td>
	    <td>
	        <input name="diyong_jifen"/></td>
	</tr>
	<tr>
	    <td width="80px" align="right">奖励积分:</td>
	    <td>
	        <input name="jiangli_jifen"/></td>
	</tr>
	<tr>
	    <td width="80px" align="right">备注:</td>
	    <td>
	        <textarea rows="5" cols="80" name="remark" style="border:#336699 1px solid;"></textarea>
	   		*可以为空</td>
	</tr>
	<tr>
		<td colspan="2" align="center">	<input type="button" class="formsubmit" value="提交" onclick="javascript:this.form.submit();"></td>
	</tr>
</table>
</form>
<?php include template('foot'); ?>