<?php if(!defined('IN_SITE')) exit('Access Denied'); ?>
<?php include template('header'); ?>
<link rel="stylesheet" type="text/css" href="script/aircity/css/jquery.suggest.css">

<script type="text/javascript" src="script/aircity/js/j.dimensions.js"></script>
<script type="text/javascript" src="script/aircity/js/aircity.js"></script>
<script type="text/javascript" src="script/aircity/js/j.suggest.js"></script>
<script type="text/javascript">
$(function(){
	$("#representative_city").suggest(citys,{hot_list:commoncitys,attachObject:"#suggest2"});
});
</script>
<div class="formnav">添加会员</div>
<form action="?action=shorturl_list&todo=saveadd" method="post" onsubmit="return CheckForm(this,true);">
<input type="hidden" value="<?php echo $formhash;?>" name="formhash">
<table align="center" class="formtable" cellpadding="0" cellspacing="1" width="97%">
	<tr>
	    <td width="80px" align="right">读卡:</td>
	    <td>
	        <input name="card" required="true" type="text" style="border:#336699 1px solid;"/>*不能为空</td>
	    <td width="80px" align="right">会员卡编号:</td>
	    <td>
	        <input name="cardid" required="true" type="text" style="border:#336699 1px solid;"/>*不能为空</td>
	</tr>
	<tr>
	    <td width="80px" align="right">持卡类型:</td>
	    <td>
	    	<select name="card_type">
	    	<?php foreach (GetConfig("card_type") as $key => $value) {?>
	    		<option value="<?php echo $key;?>"><?php echo $value;?></option>
	    	<?php }?>
	    	</select>
	    </td>
	    <td width="80px" align="right">押金:</td>
	    <td>
	        <input name="cash_pledge" type="text" style="border:#336699 1px solid;"/></td>
	</tr>
	<tr>
	    <td width="80px" align="right">姓名:</td>
	    <td>
	        <input name="name" required="true" type="text" style="border:#336699 1px solid;"/>*不能为空</td>
	    <td width="80px" align="right">昵称:</td>
	    <td>
	        <input name="nickname" type="text" style="border:#336699 1px solid;"/></td>
	</tr>
	<tr>
	    <td width="80px" align="right">手机号:</td>
	    <td>
	        <input name="phone" required="true" type="text" style="border:#336699 1px solid;"/>*不能为空</td>
	    <td width="80px" align="right">电子邮箱:</td>
	    <td>
	        <input name="email" type="mail" style="border:#336699 1px solid;"/></td>
	</tr>
	<tr>
	    <td width="80px" align="right">身份证:</td>
	    <td>
	        <input name="identity_card" type="text" style="border:#336699 1px solid;"/></td>
	    <td width="80px" align="right">性别:</td>
	    <td>
	    	<select name="sex">
	    		<option value="1">男</option>
	    		<option value="0">女</option>
	    	</select>
	    </td>
	</tr>
	<tr>
	    <td width="80px" align="right">会员等级:</td>
	    <td>
	    	<select name="grade">
	    	<?php foreach (GetConfig("grade") as $key => $value) {?>
	    		<option value="<?php echo $key;?>"><?php echo $value;?></option>
	    	<?php }?>
	    	</select>
	    </td>
	    <td width="80px" align="right">生日:</td>
	    <td>
	        <input name="birthday" type="text" class="Wdate" onClick="WdatePicker()" style="border:#336699 1px solid;"/></td>
	</tr>
	<tr>
	    <td width="80px" align="right">年费:</td>
	    <td>
	        <input name="annual_fee"type="text" style="border:#336699 1px solid;"/></td>
	    <td width="80px" align="right">年费到期时间:</td>
	    <td>
	        <input name="annual_fee_end_time" class="Wdate" onClick="WdatePicker()" type="text" style="border:#336699 1px solid;"/></td>
	</tr>
	<tr>
	    <td width="80px" align="right">充值金额:</td>
	    <td>
	        <input name="balance" type="text" style="border:#336699 1px solid;"/></td>
	    <td width="80px" align="right">客户经理:</td>
	    <td>
	        <input name="customer_manager" type="text" style="border:#336699 1px solid;"/></td>
	</tr>
</table>
<table><tr><td>&nbsp;</td></tr></table>
<table align="center" class="formtable" cellpadding="0" cellspacing="1" width="97%">	
	<tr>
	    <td width="80px" align="right">居住地址:</td>
	    <td>
	        <input name="address" type="text" style="border:#336699 1px solid;"/></td>
	    <td width="80px" align="right">QQ或MSN:</td>
	    <td>
	        <input name="qq" type="text" style="border:#336699 1px solid;"/></td>
	</tr>
	<tr>
	    <td width="80px" align="right">工作单位:</td>
	    <td>
	        <input name="work_unit" type="text" style="border:#336699 1px solid;"/></td>
	    <td width="80px" align="right">职业:</td>
	    <td>
	        <input name="occupation" type="text" style="border:#336699 1px solid;"/></td>
	</tr>
	<tr>
	    <td width="80px" align="right">是否大赛资格:</td>
	    <td>
	    <select name="eligibility">
	    	<option value="1">是</option>
	    	<option value="0">否</option>
	    </select>
	    <td width="80px" align="right">大赛次数:</td>
	    <td>
	        <input name="match_number" type="text" style="border:#336699 1px solid;"/></td>
	</tr>
	<tr>
	    <td width="80px" align="right">代表俱乐部:</td>
	    <td>
	        <input name="representative_club" type="text" style="border:#336699 1px solid;"/></td>
	    <td width="80px" align="right">代表城市:</td>
	    
	    <td>
	        <input name="representative_city" id="representative_city" type="text" style="border:#336699 1px solid;"/></td>
	        <div id='suggest2' class="ac_results">
	</tr>

	<tr>
		<td colspan="4" align="center">	<input type="button" class="formsubmit" value="提交" ></td>
	</tr>
</table>
</form>

<?php include template('foot'); ?>