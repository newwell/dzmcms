<?php if(!defined('IN_SITE')) exit('Access Denied'); ?>
<?php //include template('header'); ?>
<!-- <div class="formnav"><?php echo $act['title'];?></div> -->
<script src="script/jquery.js" type="text/javascript"></script>
<script src="script/jquery.PrintArea.js" type="text/javascript"></script>
<input type="button" id="btnPrint" value="打印"/>
<div id="printContent" style="font-family: 宋体;font-size: 8px;">
<h2><?php echo $setting_sitename;?></h2>
<?php echo $act['title'];?>小票-编号:<?php echo $num;?><br/>
-----------------------------<br/>
打印时间:<?php echo gmdate('Y.n.j H:i:s',$localtime);?><br/>
-----------------------------<br/>
赠送分值:<?php echo $change_value;?><br>
-----------------------------<br/>
会员名称:<?php echo $member_info['name'];?><br/>
会员昵称:<?php echo $member_info['nickname'];?><br/>
会员卡号:<?php echo $member_info['cardid'];?><br/>
变动后积分:<?php echo $member_info['balance'];?><br/>
变动后奖励积分:<?php echo $member_info['jiangli_jifen'];?><br/>
剩余积分合计:<b><?php echo $member_info['balance']+$member_info['jiangli_jifen'];?></b>
</div>

<script type="text/javascript">
$(function(){
        $("#btnPrint").click(function(){ $("#printContent").printArea(); });
});
$("#printContent").printArea();//直接打印
</script>
<?php //include template('foot'); ?>