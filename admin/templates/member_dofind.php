<?php if(!defined('IN_SITE')) exit('Access Denied'); ?>
<?php include template('header'); ?>
<div class="formnav">会员基本信息</div>
<table align="center" class="formtable" cellpadding="0" cellspacing="1" width="97%">
	<tr>
	    <td width="80px" align="right">姓名:</td>
	    <td><?php echo $member_info['name'];?></td>
	    <td width="80px" align="right">昵称:</td>
	    <td><?php echo $member_info['nickname'];?></td>
	</tr>
	<tr>
	    <td width="80px" align="right">读卡:</td>
	    <td><?php echo $member_info['card'];?></td>
	    <td width="80px" align="right">会员卡编号:</td>
	    <td><?php echo $member_info['cardid'];?></td>
	</tr>
	<tr>
	    <td width="80px" align="right">持卡类型:</td>
	    <td><?php $card_type = GetConfig("card_type");
	    echo $card_type[$member_info['card_type']];?>
	    </td>
	    <td width="80px" align="right">押金:</td>
	    <td></td>
	</tr>
	
	<tr>
	    <td width="80px" align="right">手机号:</td>
	    <td><?php echo $member_info['phone'];?></td>
	    <td width="80px" align="right">电子邮箱:</td>
	    <td><?php echo $member_info['email'];?></td>
	</tr>
	<tr>
	    <td width="80px" align="right">身份证:</td>
	    <td><?php echo $member_info['identity_card'];?></td>
	    <td width="80px" align="right">性别:</td>
	    <td><?php if ($member_info['sex']==1){
	    	echo("男");
	    }else {
	    	echo("女");
	    }?></td>
	</tr>
	<tr>
	    <td width="80px" align="right">会员等级:</td>
	    <td><?php $grade = GetConfig("grade");
	    echo $grade[$member_info['grade']];?>
	    </td>
	    <td width="80px" align="right">生日:</td>
	    <td><?php
	    if (!empty($member_info['birthday'])){
	    	 echo gmdate('Y-n-j',$member_info['birthday']);
	    }else {
	    	echo '未填写';
	    }?></td>
	</tr>
	<tr>
	    <td width="80px" align="right">年费:</td>
	    <td><?php echo $member_info['annual_fee'];?></td>
	    <td width="80px" align="right">年费到期时间:</td>
	    <td><?php
	    if (!empty($member_info['annual_fee_end_time'])){
	    	 echo gmdate('Y-n-j',$member_info['annual_fee_end_time']);
	    }else {
	    	echo '未填写';
	    }?></td>
	</tr>
	<tr>
	    <td width="80px" align="right">积分:</td>
	    <td><font color="red"><?php if (empty($member_info['balance'])){echo '0.00';}else{ echo $member_info['balance'];}?></font></td>
	    <td width="80px" align="right">奖励积分:</td>
	    <td><?php if (empty($member_info['jiangli_jifen'])){echo '0.00';}else{ echo $member_info['jiangli_jifen'];}?></td>
	</tr>
	<tr>
	    <td width="80px" align="right">积分合计:</td>
	    <td><font color="red"><?php echo $member_info['balance']+$member_info['jiangli_jifen'];?></font></td>
	    <td width="80px" align="right">客户经理:</td>
	    <td><?php echo $member_info['customer_manager'];?></td>
	</tr>
</table>
<table><tr><td>&nbsp;</td></tr></table>
<table align="center" class="formtable" cellpadding="0" cellspacing="1" width="97%">	
	<tr>
	    <td width="80px" align="right">居住地址:</td>
	    <td><?php echo $member_info['address'];?></td>
	    <td width="80px" align="right">QQ或MSN:</td>
	    <td><?php echo $member_info['qq'];?></td>
	</tr>
	<tr>
	    <td width="80px" align="right">工作单位:</td>
	    <td><?php echo $member_info['work_unit'];?></td>
	    <td width="80px" align="right">职业:</td>
	    <td><?php echo $member_info['occupation'];?></td>
	</tr>
	<tr>
	    <td width="80px" align="right">是否大赛资格:</td>
	    <td><?php if ($member_info['eligibility']==1){
	    	echo("是");
	    }else {
	    	echo("否");
	    }?></td>
	    <td width="80px" align="right">大赛次数:</td>
	    <td><?php echo $member_info['match_number'];?></td>
	</tr>
	<tr>
	    <td width="80px" align="right">代表俱乐部:</td>
	    <td><?php echo $member_info['representative_club'];?></td>
	    <td width="80px" align="right">代表城市:</td>
	    
	    <td><?php echo $member_info['representative_city'];?></td>
	</tr>

</table>
</form>

<?php include template('foot'); ?>