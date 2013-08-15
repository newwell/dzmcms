<?php if(!defined('IN_SITE')) exit('Access Denied'); ?>
<?php include template('header'); ?>
<div class="formnav"><?php echo $act['title'];?></div> 
<form action="?action=member_jifenlog&todo=jifenlog" method="post" onsubmit="return CheckForm(this,true);">
<script type="text/javascript"> 
document.body.onload = function(){
    document.getElementById("card").focus();
};
</script>
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
<form action="?action=member_jifenlog&todo=dojifenlog" method="post" onsubmit="return CheckForm(this,true);">
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
	<tr>
		<td align="right">变动类型:</td>
		<td><select name="change_type">
				<option value="add">增加</option>
				<option value="del">减少</option>
			</select>
		</td>
		<td align="right">变动对象:</td>
		<td><select name="change_object">
				<option value="balance">积分</option>
				<option value="jiangli_jifen">奖励积分</option>
			</select></td>
	</tr>
	<tr>
		<td align="right">变动积分值:</td>
		<td colspan="3">
			<input name="change_value"/>
		</td>
	</tr>
	<tr>
		<td align="right">备注:</td>
	    <td colspan="3">
	        <textarea rows="5" name="remark" style="width:100%;border:#336699 1px solid;"></textarea>
	   		*可以为空</td>
	</tr>
	<tr>
		<td colspan="4" align="center">
			<input type="submit" class="formsubmit" value="提交"/>
		</td>
	</tr>
</table>
</form>
 
<?php }?>
<?php include template('foot'); ?>