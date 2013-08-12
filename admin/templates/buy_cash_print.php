<?php if(!defined('IN_SITE')) exit('Access Denied'); ?>
<?php // include template('header'); ?>
<script src="script/jquery.js" type="text/javascript"></script>
<script src="script/jquery.PrintArea.js" type="text/javascript"></script>

<input type="button" id="btnPrint" value="打印"/>
<div id="printContent" style="font-family: 宋体;font-size: 8px;">
<h2><?php echo $setting_sitename;?></h2>
-----------------------------<br/>
消费时间:<?php echo gmdate('Y.n.j H:i:s',$localtime);?><br>
打印时间:<?php echo gmdate('Y.n.j H:i:s',$localtime);?><br/>
-----------------------------<br/>
支付方式:<?php echo $method_payment_v;?><br>
支付金额:<?php echo $payment_amount;?><br>
抵用积分:<?php echo $diyong_jifen;?><br>
奖励积分:<?php echo $jiangli_jifen;?><br>
-----------------------------<br/>
目前积分:<?php echo $member_info['balance'];?><br/>
会员读卡:<?php echo $member_info['card'];?><br/>
会员卡号:<?php echo $member_info['cardid'];?><br/>

<div>

<script type="text/javascript">
$(function(){
        $("#btnPrint").click(function(){ $("#printContent").printArea(); });
});
$("#printContent").printArea();//直接打印
</script>
