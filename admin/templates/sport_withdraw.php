<?php if(!defined('IN_SITE')) exit('Access Denied'); ?>
<?php include template('header'); ?>
<div class="formnav"><?php echo $act['title'];?></div>
<script type="text/javascript"> 
document.body.onload = function(){
    document.getElementById("card").focus();
};
</script>
<form action="?action=sport_withdraw&todo=withdraw" method="post" onsubmit="return CheckForm(this,true);">
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
<input type="hidden" value="<?php echo $formhash;?>" name="formhash">
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
</table><br>

<table width="97%" cellpadding="1" cellspacing="1" align="center" class="listtable">
        <tr>
            <th>赛事名称</th>
            <th>消耗积分</th>
			<th>服务费</th>
			<th>状态</th>
			<th>类型</th>
			<th>报名时间</th>
			<th>开赛时间</th>
			<th>操作</th>		
        </tr>
        <?php if(is_array($infoList)) { foreach($infoList as $key => $value) { ?>
        <tr <?php if (($key%2) == 0){echo 'bgcolor="#E4EDF9"';}else {echo 'bgcolor="#F1F3F5"';}?>>
            <td class="list"><?php echo $value['sport']['name'];?></td>      
            <td class="list"><?php echo $value['sport']['deduction'];?></td>  
            <td class="list"><?php if ($value['sport']['type']=="time_trial"){echo "每".$value['sport']['service_charge_time']."分钟,".$value['sport']['service_charge']."积分";}else {echo $value['sport']['service_charge'].'积分';}?></td>

            <td class="list"><?php echo $value['sport']['status']?></td>
            <td class="list"><?php if ($value['sport']['type']=="time_trial"){echo '计时赛';}else echo "非计时赛";?></td>
            <td class="list"><?php echo $value['add_date'];?></td>
            <td class="list"><?php echo gmdate("Y-n-j H:m:s",$value['sport']['add_date']) ?></td>
			<td class="list">
				<a href="?action=sport_withdraw&todo=dowithdraw&entry_id=<?php echo $value['id']?>&card=<?php echo $value['card'];?>&sport_id=<?php echo $value['sport']['id'];?>" title="退赛">退赛</a>
			
			</td>
        </tr>
		<?php }}?>

</table>

<?php }?>

<?php include template('foot'); ?>