<?php if(!defined('IN_SITE')) exit('Access Denied'); ?>
<?php include template('header'); ?>
<div class="formnav"><?php echo $act['title'];?></div>
<style>.xiankuan{max-width: 430px;overflow: hidden;text-overflow: ellipsis;width: 330px;white-space:nowrap;}</style>
<link type="text/css" rel="stylesheet" href="script/hiAlert/css/alert.css">
<script src="script/hiAlert/jquery.alert.js" type="text/javascript"></script>
<script src="script/textSearch.jquery.js" type="text/javascript"></script>
<script type="text/javascript"> 
document.body.onload = function(){
    document.getElementById("card").focus();
};
function thisCheckForm() {
	if($("#starttime").val()!=''&&$("#endtime").val()!=''){
		return true;
	}
	if($("#starttime").val()==''&&$("#endtime").val()==''){
		return true;
	}
	alert('不能只填一边的时间!');
	return false;
}
</script>
<form action="?action=statistics_serviceCharge&todo=serviceCharge" method="post" onsubmit="return thisCheckForm(this,true);">
<input type="hidden" value="<?php echo $formhash;?>" name="formhash">
<table align="center" class="formtable" cellpadding="0" cellspacing="1" width="97%">
<!-- 	<tr>
	    <td align="right">读卡:</td>
	    <td><input name="card" id="card" type="text" style="border:#336699 1px solid;"/></td>
	</tr> -->
		<tr>
	    <td align="right">快捷选择:</td>
	    <td>
		    <input onclick="set_kuaixuan(<?php echo $jintian;?>)" type="button" class="button_input" value="今天"/>
		    <input onclick="set_kuaixuan(<?php echo $benzhou;?>)" type="button" class="button_input" value="本周"/>
		    <input onclick="set_kuaixuan(<?php echo $benyue;?>)" type="button" class="button_input" value="本月"/>
		    <input onclick="set_kuaixuan(<?php echo $jin30tian;?>)" type="button" class="button_input" value="最近30天"/>
		    <input onclick="set_kuaixuan(<?php echo $jin3yue;?>)" type="button" class="button_input" value="最近3个月"/>
		    <input onclick="set_kuaixuan('','')" type="button" class="button_input" value="自定义"/>
		</td>
	</tr>
	<tr>
	   	<td align="right">时间范围</td>
	   	<td><input name="starttime" id="starttime" value="<?php if (isset($starttime))echo $starttime;?>" class="Wdate" autocomplete="off" onfocus="WdatePicker({startDate:'%y-%M-01 00:00:00',dateFmt:'yyyy-MM-dd HH:mm:ss',alwaysUseStartDate:true})" />
	   	至<input name="endtime" id="endtime" value="<?php if (isset($endtime))echo $endtime;?>" class="Wdate" autocomplete="off" onfocus="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss',startDate:'%y-%M-%d 23:59:59',alwaysUseStartDate:true});" /></td>
	</tr>
	<tr align="center">
		<td colspan="2"><input type="submit" class="formsubmit" value="提交"></td>
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
	    <td width="80px" align="right">读卡:</td>
	    <td><?php echo $member_info['card'];?></td>
	    <td width="80px" align="right">会员号:</td>
	    <td><?php echo $member_info['cardid'];?></td>
	</tr>
	<tr>
	    <td width="80px" align="right">积分:</td>
	    <td style="color: red;"><?php if (empty($member_info['balance'])){ echo "0.00";}else {echo $member_info['balance'];};?></td>
	    <td width="80px" align="right">奖励积分:</td>
	    <td><?php echo $member_info['jiangli_jifen'];?></td>
	</tr>
</table><br>
<?php }?>
<table width="98%"  border="0" cellpadding="0" cellspacing="0" align="center" id="searchResult">
  <tr>
    <td valign="top" align="center" width="100%">
    <table width="100%" cellpadding="1" cellspacing="1" align="center" class="listtable">
    	<tr bgcolor="#fff" align="left">
			<td colspan="7" align="left">
				该条件下的服务费合计:<font color="red"><?php echo $money_sun;?></font>积分=<font color="red"><?php echo $money_sun/$setting_rate;?></font>人民币
				&nbsp;&nbsp;其中&nbsp;[计时赛:<font color="red"><?php echo $money_sun_jishi;?></font>积分=<font color="red"><?php echo $money_sun_jishi/$setting_rate;?></font>人民币
				&nbsp;&nbsp;非计时赛:<font color="red"><?php echo $money_sun_feijishi;?></font>积分=<font color="red"><?php echo $money_sun_feijishi/$setting_rate;?></font>人民币]
			</td>
		</tr>
        <tr>
            <th>赛事名称</th>
            <th>赛事类型</th>
            <th>状态</th>
			<th>获得服务费</th>	
        </tr>
		<?php if(is_array($infoList)) { foreach($infoList as $key => $value) { ?>
        <tr <?php if (($key%2) == 0){echo 'bgcolor="#E4EDF9"';}else {echo 'bgcolor="#F1F3F5"';}?>>     
            <td class="list"><?php echo $value['name'];?></td>
            <td class="list"><?php if ($value['type']=="time_trial"){echo '计时赛';}elseif ($value['type']=="no_time_trial"){ echo "非计时赛";}elseif ($value['type']=="pk_trial"){echo "PK赛";};?></td>
            <td class="list"><?php echo $value['status'];?></td>
            <td class="list"><?php echo abs($value['serve_money']);?></td>
        </tr>
		<?php } }?>
		<tr bgcolor="#A6D0F6" align="center">
			<td colspan="7"><?php if (!empty($page_control)){echo $page_control;}?></td>
		</tr>
    </table>
</td>
  </tr>
</table>
<?php include template('foot'); ?>