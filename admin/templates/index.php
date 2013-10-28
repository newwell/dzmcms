<?php if(!defined('IN_SITE')) exit('Access Denied'); ?>
<?php include template('header'); ?>
<div class="formnav">管理平台首页</div>
<?php if (is_array($iconArr)){?>
<table align="center" class="formtable" cellpadding="3" cellspacing="1" width="97%" style="line-height:18px;">
	<tr>
		<th scope="row" colspan="2" >快捷图标</th>
	</tr>
	<tr>
		<td>
		<?php foreach ($iconArr as $value) {?>
		<div class="index_icon_div" onmouseover="javascript:$(this).addClass('index_icon_div_onmousemove');" onmouseout="javascript:$(this).removeClass('index_icon_div_onmousemove');">
			<a title="<?php echo $value['action']['title'];?>" href="?action=<?php echo $value['action']['action'];?>&todo=<?php echo $value['action']['todo'];?>&do=<?php echo $value['action']['do'];?>">
				<img height="88px" border="0" src="script/icons/<?php echo $value['iconid'];?>.png"/>
			</a><br/>
			<div style="text-align: center;"><?php echo $value['action']['title'];?></div>
		</div>
		<?php }?>
		</td>
	</tr>
</table>
<?php }?>
<br/>
<table align="center" class="formtable" cellpadding="3" cellspacing="1" width="97%" style="line-height:18px;">
	<tr>
		<th scope="row" colspan="2" >系统版权信息</th>
	</tr>
	<tr>
		<td width="20%" align="right" valign="middle">版权所有：</td>
		<td width="80%" align="left" valign="middle"><a href="http://www.pk365club.com/" target="_blank">PK365俱乐部</a></td>
	</tr>

	<tr>
		<td  align="right" valign="middle">公司网站：</td>
		<td  align="left" valign="middle"><a href="http://www.pk365club.com/" target="_blank">PK365俱乐部</a></td>
	</tr><!--
	<tr>
		<td  align="right" valign="top">联系方式：</td>
		<td  align="left" valign="middle">
		电话:027-86926978<br>
		邮箱:s@dazan.cn <br>
		Q Q:753057793<br>
		</td>
	</tr>
--></table>
<?php include template('foot'); ?>