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
<form action="?action=statistics_paylog&todo=paylog" method="get" onsubmit="return thisCheckForm();">
<input type="hidden" value="statistics_paylog" name="action">
<input type="hidden" value="paylog" name="todo">
<input type="hidden" value="<?php echo $formhash;?>" name="formhash">
<table align="center" class="formtable" cellpadding="0" cellspacing="1" width="97%">
	<tr>
	    <td align="right">读卡:</td>
	    <td><input name="card" id="card" type="text" style="border:#336699 1px solid;"/></td>
	</tr>
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
	   	<td><input name="starttime" id="starttime" value="<?php echo $starttime;?>" class="Wdate" autocomplete="off" onfocus="WdatePicker({startDate:'%y-%M-01 00:00:00',dateFmt:'yyyy-MM-dd HH:mm:ss'})" />
	   	至<input name="endtime" id="endtime" value="<?php echo $endtime;?>" class="Wdate" autocomplete="off" onfocus="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss',startDate:'%y-%M-%d 23:59:59'});" />
	   	
	   	</td>
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
        <tr>
            <th>姓名/昵称</th>
            <th>会员卡</th>
            <th>支付方式</th>
            <th>充值的积分</th>
			<th >说明</th>
			<th >操作员</th>
			<th>产生日期</th>		
        </tr>
		<?php if(is_array($infoList)) { foreach($infoList as $key => $value) { ?>
        <tr <?php if (($key%2) == 0){echo 'bgcolor="#E4EDF9"';}else {echo 'bgcolor="#F1F3F5"';}?>>     
            <td class="list"><?php echo $value['member_info']['name'];?>/<?php echo $value['member_info']['nickname'];?></td>
            <td class="list"><?php echo $value['member_info']['cardid'];?></td>
            <td class="list"><?php echo $value['type_explain']?></td>
            <td class="list"><?php echo $value['money']?></td>
            
            <td class="list xiankuan" ><a href="JavaScript:;" onclick="JavaScript:hiBox('#showbox_<?php echo $value['id']?>', '详细说明',400,'','','.a_close');"><?php echo $value['explains']?><a/></td>
            <td class="list"><?php echo $value['system_user']?></td>
            <td class="list"><?php echo $value['add_date']?></td>
        </tr>
        <div id="showbox_<?php echo $value['id']?>" style="display:none">
		   <p><?php echo $value['explains']?></p>
		</div>
		<?php } }?>
		<tr bgcolor="#A6D0F6" align="center">
			<td colspan="7"><?php if (!empty($page_control)){echo $page_control;}?></td>
		</tr>
    </table>
</td>
  </tr>
</table>
<script type="text/javascript">$("#searchResult").textSearch("扣除");</script>
<script type="text/javascript">$("#searchResult").textSearch("减少");</script>
<script type="text/javascript">$("#searchResult").textSearch("增加",options={divFlag: true,
		divStr: " ",
		markClass: "",
		markColor: "#94AA3E",
		nullReport: false,
		callback: function(){
			return false;	
		}});</script>
<?php include template('foot'); ?>