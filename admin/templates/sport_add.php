<?php if(!defined('IN_SITE')) exit('Access Denied'); ?>
<?php include template('header'); ?>
<div class="formnav"><?php echo $act['title'];?></div>
<form action="?action=sport_add&todo=saveadd" method="post" onsubmit="return CheckForm(this,true);">
<input type="hidden" value="<?php echo $formhash;?>" name="formhash">
<table align="center" class="formtable" cellpadding="0" cellspacing="1" width="97%">
	<tr>
	    <td align="right">赛事名称:</td>
	    <td><input name="name" required="true"/>*不能为空</td>
	    <td align="right">比赛开始时间:</td>
	    <td><input name="start_time" class="Wdate" onclick="WdatePicker({startDate:'%y-%M-01 00:00:00',dateFmt:'yyyy-MM-dd HH:mm:ss',alwaysUseStartDate:true})"/></td>
	</tr>
	<tr>
	    <td align="right">类型:</td>
	    <td><select name="type">
	    		<option value="time_trial">计时赛</option>
	    </select></td>
	    <td align="right">积分消耗:</td>
	    <td>
	    	<input name="deduction" required="true"/>*不能为空
	    </td>
	</tr>
	<tr>
	    <td align="right">服务费:</td>
	    <td>每<input name="service_charge_time" style="width: 35px;"/>分钟,扣除<input name="service_charge" required="true"/>积分</td>
	    <td align="right">人数上限:</td>
	    <td><input name="people_number" required="true"/>*不能为空</td>
	</tr>
	<tr>
	    <td align="right">支持可以再次买入:</td>
	    <td><select name="rebuy">
	    		<option value="1">是</option>
	    		<option value="0">否</option>
	    </select></td>
	    <td align="right">参赛次数:</td>
	    <td><input name="entry_number"/></td>
	</tr>
	<tr>
		<td align="right">涨盲时间:</td>
		<td><input name="zhangmang_time"/>分钟</td>
	    <td align="right">截至买入时间:</td>
	    <td><input name="stop_entry_time" required="true" class="Wdate" onclick="WdatePicker({startDate:'%y-%M-01 00:00:00',dateFmt:'yyyy-MM-dd HH:mm:ss',alwaysUseStartDate:true})" />*不能为空</td>
	</tr>
	<tr>
	    <td align="right">休息时间:</td>
	    <td><input name="rest_time" />分钟</td>
	    <td align="right">记分牌:</td>
	    <td><input name="scoreboard"/></td>
	</tr>
	<tr>
	    <td align="right">桌数:</td>
	    <td><input name="MaxBLNum" /></td>
	    <td align="right">座位数:</td>
	    <td><input name="seating"/></td>
	</tr>
	<tr >
	    <td width="80px" align="right">备注:</td>
	    <td colspan="3">
	        <textarea rows="5" name="remark" style="width:100%;border:#336699 1px solid;"></textarea>
	   		*可以为空</td>
	</tr>
	<tr>
		<td colspan="4" align="center">	<input type="submit" class="formsubmit" value="提交" ></td>
	</tr>
</table>
</form>
<?php include template('foot'); ?>