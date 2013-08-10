<?php if(!defined('IN_SITE')) exit('Access Denied'); ?>
<?php include template('header'); ?>
<script src="script/jquery.js" type="text/javascript"></script>
<script src="script/jquery.PrintArea.js" type="text/javascript"></script>

<input type="button" id="btnPrint" value="打印"/>
<div id="printContent">
<h2><?php echo $setting_sitename;?></h2>
------------------------------------------------<br/>
消费时间:<?php echo gmdate('Y.n.j',$localtime);?>===打印时间:<?php echo gmdate('Y.n.j',$localtime);?><br/>
------------------------------------------------<br/>
&nbsp;&nbsp;&nbsp;&nbsp;名称&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;个数&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;金额

<div>

<script type="text/javascript">
$(function(){
        $("#btnPrint").click(function(){ $("#printContent").printArea(); });
});
//$("#printContent").printArea();//直接打印
</script>
