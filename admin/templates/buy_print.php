<?php if(!defined('IN_SITE')) exit('Access Denied'); ?>
<?php //include template('header'); ?>
<script src="script/jquery.js" type="text/javascript"></script>
<script src="script/jquery.PrintArea.js" type="text/javascript"></script>

<input type="button" id="btnPrint" value="打印"/>
<div id="printContent" style="font-family: 宋体;font-size: 8px;">
<h2><?php echo $setting_sitename;?></h2><br/>
<h3>商品购买小票</h3>
-----------------------------<br/>
消费时间:<?php echo gmdate('Y.n.j H:i:s',$localtime);?><br>
打印时间:<?php echo gmdate('Y.n.j H:i:s',$localtime);?><br/>
-----------------------------<br/>
<?php echo $remark;?>
-----------------------------<br/>
支付方式:<?php echo $method_payment;?><br>
支付金额:<?php echo $payment_amount;?><br>
抵用积分:<?php echo $diyong_jifen;?><br>
奖励积分:<?php echo $jiangli_jifen;?><br>
-----------------------------<br/>
会员名称:<?php echo $member_info['name'];?><br/>
会员昵称:<?php echo $member_info['nickname'];?><br/>
会员卡号:<?php echo $member_info['cardid'];?><br/>
剩余积分:<?php echo $member_info['balance'];?><br/>
剩余奖励积分:<?php echo $member_info['jiangli_jifen'];?><br/>
</div>

<script type="text/javascript">
$(function(){
        $("#btnPrint").click(function(){ $("#printContent").printArea(); });
});
$("#printContent").printArea();//直接打印
</script>
<?php //include template('foot'); ?>