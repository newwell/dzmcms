<?php if(!defined('IN_SITE')) exit('Access Denied'); ?>
<?php include template('header'); ?>

<script type="text/javascript">
$(document).ready(function(){
	$("#switchPoint").click(function(){
		$("#left").slideToggle("fast");
	});
	$("body").toggleClass('hidden_body_overflow_y');
});	
</script>
<table width="100%" border="0" cellpadding="0" cellspacing="0" class="wui_header">
  <tr>
    <td class="wui_logo" ><a style="color: white;font-size: 100%;" href="?action=show&todo=main">&nbsp;&nbsp;<?php echo $setting_sitename;?></a></td>
    <td class="wui_dynacomm" align="right"">德州扑克运营管理系统&nbsp;&nbsp;<img title="退出登陆" onclick="javascript:adminlogout();" style="cursor: pointer;height:22px" src="<?php echo $_TEMPLATESDIR?>/img/exit.png"/>&nbsp;&nbsp;</td>
  </tr>
</table>
<table border="0" cellpadding="0" cellspacing="0" height="100%" width="100%" style="table-layout: fixed;">
  <tr>
    <td width="205" id="left">
      <iframe frameborder="0" id="menu" name="menu" src="?action=show&todo=left&do=system"  style="height: 100%;width:100%; z-index: 1;overflow: auto;"></iframe>
    </td>
    <td style="WIDTH:7px" bgcolor="#F3F3F3">
      <table height="100%" cellspacing="0" cellpadding="0" border="0">
        <tbody>
          <tr>
            <td style="HEIGHT: 100%;background-color: #D4D4D4;"><span  title="关闭/打开"><img id="switchPoint" src="<?php echo $_TEMPLATESDIR?>/image/icon_close.png" border="0"></span></td>
          </tr>
        </tbody>
      </table>
    </td>
    <td >
      <table width="100%" height="100%" border="0" cellpadding="0" cellspacing="0">
        <tr>
          <td>
            <iframe frameborder="0" id="main" name="mainFrame" src="?action=show&todo=index" scrolling="yes" style="height: 100%; width: 100%; z-index: 1;overflow: auto; "></iframe>
          </td>
        </tr>
      </table>
    </td>
  </tr>
</table>
</body>
</html>