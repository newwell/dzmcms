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
<table width="97%" cellpadding="1" cellspacing="1" align="center" class="listtable">
        <tr>
            <th>名称/昵称</th>
            <th>会员号</th>
            <th>类型</th>
			<th>支付方式</th>
			<th>使用积分</th>
			<th>奖励积分抵用</th>
			<th>奖励</th>
			<th>备注</th>
			<th>消费时间</th><!--
			<th>操作</th>		
        --></tr>
        <?php if(is_array($infoList)) { foreach($infoList as $key => $value) { ?>
        <tr bgcolor="#F1F3F5" onmouseover="tr_add_color($(this))"onmouseout="tr_del_color($(this))">
            <td class="list"><?php echo $value['member_info']['name'];?>/<?php echo $value['member_info']['nickname'];?></td>
            <td class="list"><?php echo $value['member_info']['cardid'];?></td>
            <td class="list"><?php echo $value['type'];?></td>  
            <td class="list"><?php echo $value['method_payment'];?></td>
            <td class="list"><?php echo $value['payment_amount'];?></td>
            <td class="list"><?php echo $value['diyong_jifen'];?></td>
            <td class="list"><?php echo $value['jiangli_jifen'];?></td>
            <td class="list"><?php echo $value['remark'];?></td>
            <td class="list"><?php echo $value['add_date'];?></td>
			<!--<td class="list">
			</td>
        --></tr>
		<?php }}?>
		<tr bgcolor="#A6D0F6" align="center">
			<td colspan="10"><?php if (!empty($page_control)){echo $page_control;}?></td>
		</tr>
</table>
<?php include template('foot'); ?>