<?php if(!defined('IN_SITE')) exit('Access Denied'); ?>
<?php include template('header'); ?>
<link type="text/css" rel="stylesheet" href="script/hiAlert/css/alert.css">
<script src="script/hiAlert/jquery.alert.js" type="text/javascript"></script>
<div class="formnav"><?php echo $act['title'];?></div>
<form action="?action=sport_list&todo=search<?php if ($do=='entry'){echo "&do=entry";}?>" method="post">
<input type="hidden" value="<?php echo $formhash;?>" name="formhash">
<style>.xiankuan{max-width: 220px;overflow: hidden;text-overflow: ellipsis;width: 220px;white-space:nowrap;}</style>
<table width="98%"  border="0" cellpadding="0" cellspacing="0" align="center">
  <tr>
    <td valign="top" align="center" width="100%">
    <table width="100%" cellpadding="1" cellspacing="1" align="center" class="listtable">
    <?php //if ($todo!='entry'){?>
		<tr >
         <td colspan="9" align="right">
         <input type="hidden" name="action" value="shorturl_list"/>
         <input type="hidden" name="todo" value="search"/>
         	名称<input name="keywork" value="<?php if (!empty($keywork))echo $keywork;?>"/>
         	<input type="submit" value="搜索" class="button_input">
         </td>
        </tr>
        <tr>

            <th>赛事名称</th>
            <th >消耗积分</th>
			<th >服务费</th>
			<th >比赛人数/上限</th>
			<th >支持可以再次买入</th>
			<th >参赛次数</th>
			<th >状态</th>
			<th >类型</th>
			<th >开赛时间</th>
			<th>操作</th>		
        </tr>
		<?php if(is_array($listArr)) { foreach($listArr as $key => $value) { ?>
        <tr <?php if (($key%2) == 0){echo 'bgcolor="#E4EDF9"';}else {echo 'bgcolor="#F1F3F5"';}?>>
            <td class="list"><?php echo $value['name'];?></td>      
            <td class="list"><?php echo $value['deduction'];?></td>  
            <td class="list"><?php if ($value['type']=="time_trial"){echo "每".$value['service_charge_time']."分钟,".$value['service_charge']."积分";}else {echo $value['service_charge'].'积分';}?></td>
            <td class="list">0/<?php echo $value['people_number'];?></td>
            <td class="list"><?php if ($value['rebuy']){echo "是";}else {echo '否';}?></td>
            <td class="list"><?php echo $value['entry_number']?></td>
            <td class="list"><?php echo $value['status']?></td>
            <td class="list"><?php if ($value['type']=="time_trial"){echo '计时赛';}else echo "非计时赛";?></td>
            <td class="list"><?php echo gmdate("Y-n-j H:m:s",$value['add_date']) ?></td>
			<td class="list">
			<?php if ($do!='entry'){?>
			<!-- <a href="?action=sport_list&todo=update&id=<?php echo $value['id']?>" title="修改"><img src="<?php echo $_TEMPLATESDIR?>/image/edit_g.gif" border="0" alt="修改"/></a>  -->
			<a href="JavaScript:;" onclick="JavaScript:hiBox('#showbox_<?php echo $value['id']?>', '<?php echo $value['name'];?>',400,'','','.a_close');" title="查看">查看</a>
			<?php if ($value['status']=="未开赛"){?>
				<a href="?action=sport_list&todo=kaisai&id=<?php echo $value['id']?>" title="开赛">开赛</a>
			<?php }elseif ($value['status']=="竞赛中"){?>
			<a href="?action=sport_list&todo=jiesai&id=<?php echo $value['id']?>" title="结束比赛">结束比赛</a>
			<?php }elseif ($value['status']=="已结束"){?>
			<a href="?action=sport_list&todo=prize&id=<?php echo $value['id']?>" title="颁奖">颁奖</a>
			<?php }elseif ($value['status']=="已颁奖"){?>
			<a href="?action=sport_list&todo=prizelog&id=<?php echo $value['id']?>" title="颁奖记录">颁奖记录</a>
			<a href="JavaScript:;" onclick="if(confirm('删除操作不可恢复,确认吗?')){location.href='?action=sport_list&todo=del&id=<?php echo $value['id']?>'}" title="删除">删除</a>
			<?php }?>
			<?php }else {?>
				<a href="?action=sport_list&todo=doentry&id=<?php echo $value['id']?>" title="报名">报名</a>
			<?php }?>
			</td>
        </tr>
<div id="showbox_<?php echo $value['id']?>" style="display:none">
<h3><?php echo $value['name'];?></h3>
   <p>比赛开始时间:<?php echo gmdate("Y-n-j H:m:s",$value['start_time'])?><br>
   报名截至时间:<?php echo gmdate("Y-n-j H:m:s",$value['stop_entry_time'])?><br>
   消耗积分:<?php echo $value['deduction'];?><br>
   服务费:<?php if ($value['type']=="time_trial"){echo "每".$value['service_charge_time']."分钟,".$value['service_charge']."积分";}else {echo $value['service_charge'].'积分';}?><br>
 类型:<?php if ($value['type']=="time_trial"){echo '计时赛';}else echo "非计时赛";?><br>
 人数上限:<?php echo $value['people_number'];?><br>
是否可以再次买入:<?php if ($value['rebuy']){echo "是";}else {echo '否';}?><br>
参赛次数:<?php echo $value['entry_number'];?><br>
涨盲时间: <?php echo $value['zhangmang_time'];?> 分钟<br>
休息时间: <?php echo $value['rest_time'];?> 分钟<br>
记分牌: <?php echo $value['scoreboard'];?><br>
桌数: <?php echo $value['MaxBLNum'];?><br>
座位数: <?php echo $value['seating'];?><br>
备注: <?php echo $value['remark'];?><br>
   </p>
   <p style="text-align:right"><a href="#" class="a_close">关闭</a></p>
</div>
        
		<?php } }?>
		<tr bgcolor="#A6D0F6" align="center">
			<td colspan="10"><?php if (!empty($page_control)){echo $page_control;}?></td>
		</tr>
    </table>
</td>
  </tr>
</table>
</form>
<?php include template('foot'); ?>