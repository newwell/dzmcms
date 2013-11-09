<?php if(!defined('IN_SITE')) exit('Access Denied'); ?>
<?php include template('header'); ?>
<div class="formnav"><?php echo $act['title'];?></div>
<table width="97%">
  <tr>
    <td>
		<table align="center" class="formtable" cellpadding="3" cellspacing="1" width="97%" style="line-height:18px;">
			<tr>
				<th scope="row" colspan="4" >积分排行版</th>
			</tr>
			<tr align="center" style="font-weight: bold;">
				<td>名次</td>
				<td>积分</td>
				<td>姓名/昵称</td>
				<td>卡号</td>
			</tr>
		
			
			<?php if(is_array($leaderboard_balance)) { foreach($leaderboard_balance as $key => $value) { ?>
			<tr align="center">
				<td><?php echo $key+1;?></td>
				<td><?php echo $value['balance'];?></td>
				<td><?php echo $value['name'];?>/<?php echo $value['nickname'];?></td>
				<td><?php echo $value['cardid'];?></td>
			</tr>
			<?php }}?>
		</table>
	</td>
    <td>
		<table align="center" class="formtable" cellpadding="3" cellspacing="1" width="97%" style="line-height:18px;">
			<tr>
				<th scope="row" colspan="4" >奖励积分排行版</th>
			</tr>
			<tr align="center" style="font-weight: bold;">
				<td>名次</td>
				<td>奖励积分</td>
				<td>姓名/昵称</td>
				<td>卡号</td>
			</tr>
			<?php if(is_array($leaderboard_jiangli_jifen)) { foreach($leaderboard_jiangli_jifen as $key => $value) { ?>
			<tr align="center">
				<td><?php echo $key+1;?></td>
				<td><?php echo $value['jiangli_jifen'];?></td>
				<td><?php echo $value['name'];?>/<?php echo $value['nickname'];?></td>
				<td><?php echo $value['cardid'];?></td>
			</tr>
			<?php }}?>
			
		</table>
	</td>
  </tr>
</table>
<?php include template('foot'); ?>