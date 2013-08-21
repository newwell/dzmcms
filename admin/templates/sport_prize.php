<?php if(!defined('IN_SITE')) exit('Access Denied'); ?>
<?php include template('header'); ?>
<div class="formnav">赛事奖励</div>
<form  method="post" id="data" action="?action=sport_list&todo=doprize">
<input type="hidden" value="<?php echo $formhash;?>" name="formhash">
<input type="hidden" value="<?php echo $sport_id;?>" name="sport_id">
<style>.xiankuan{max-width: 220px;overflow: hidden;text-overflow: ellipsis;width: 220px;white-space:nowrap;}</style>
<table width="98%"  border="0" cellpadding="0" cellspacing="0" align="center">
  <tr>
    <td valign="top" align="center" width="100%">
    <table width="100%" cellpadding="1" cellspacing="1" align="center" class="listtable">
        <tr>
            <th>名次</th>
			<th>会员读卡</th>
			<th >会员名称</th>
			<th >奖励积分</th>
			<th>奖励时间</th>		
        </tr>
		<?php if(is_array($infoList)) { foreach($infoList as $key => $value) { ?>
        <tr <?php if (($key%2) == 0){echo 'bgcolor="#E4EDF9"';}else {echo 'bgcolor="#F1F3F5"';}?>>
                  
            <td class="list"><?php echo $value['ranking']?></td>  
            <td class="list"><?php echo $value['card']?></td>
            <td class="list"><?php echo $value['name']?></td>
            <td class="list"><?php echo $value['jiangli_jifen']?></td>
            <td class="list xiankuan"><?php echo $value['add_date'];?></td>
        </tr>
		<?php } }?>
		<tr bgcolor="#A6D0F6" align="center">
			<td class="list"><input name="ranking" value="第 <?php echo $key+2;?> 名"/></td>
			<td class="list"><input name="card" value="" onblur="get_user_name();" id="card"/></td>
			<td class="list"><input name="name" id="name"/></td>
			<td class="list"><input name="jiangli_jifen"/></td>
			<td class="list"><input type="submit" value="提交奖励"  class="formsubmit" /></td>
		</tr>
		<script type="text/javascript">
function get_user_name() {
	$.get("?action=member_find&todo=js_user_info&card="+$("#card").val(),function(data,status){
		$("#name").val(data.name);
	  }, "json");
}</script>
	<!-- <tr bgcolor="#E4EDF9" align="center">
			<td class="list">奖池剩余:<?php echo $sport_info['jackpot']?></td>
		</tr>  -->
    </table>
</td>
  </tr>
</table>
</form>
<?php include template('foot'); ?>