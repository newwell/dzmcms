<?php if(!defined('IN_SITE')) exit('Access Denied'); ?>
<?php /// include template('header'); ?>
<!-- <div class="formnav">rebuy-小票打印</div> -->
<script src="script/jquery.js" type="text/javascript"></script>
<script src="script/jquery.PrintArea.js" type="text/javascript"></script>

<input type="button" id="btnPrint" value="打印" class="formsubmit"/>
<div id="printContent" style="font-family: 宋体;font-size: 8px;">
<h2><?php echo $setting_sitename;?></h2><h3>充值小票</h3>
-----------------------------<br/>
充值时间:<?php echo gmdate('Y.n.j H:i:s',$localtime);?><br>
-----------------------------<br/>
充值金额:<?php echo $dopay;?><br>
对应充值积分:<?php echo $value;?><br>
支付方式:<?php echo $method_payment;?><br/>
-----------------------------<br/>
会员名:<?php echo $member_info['name'];?><br/>
会员昵称:<?php echo $member_info['nickname'];?><br/>
会员卡号:<?php echo $member_info['cardid'];?><br/>
剩余积分:<?php echo $member_info['balance'];?><br/>
剩余奖励积分:<?php echo $member_info['jiangli_jifen'];?><br/>
积分合计:<?php echo $member_info['balance']+$member_info['jiangli_jifen'];?>

</div>

<script type="text/javascript">
$(function(){
        $("#btnPrint").click(function(){ $("#printContent").printArea(); });
});
$("#printContent").printArea();//直接打印
</script>
<?php //include template('foot'); ?>