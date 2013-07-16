<?php if(!defined('IN_SITE')) exit('Access Denied'); ?>
<?php include template('header'); ?>
<div class="formnav">还原短网址</div>
<script src="script/jquery-1.8.0.min.js" type="text/javascript"></script>
<form id="restoreform" action="?action=shorturl_list&todo=dorestore" method="post" onsubmit="return CheckForm(this,true);">
<input type="hidden" value="<?php echo $formhash;?>" name="formhash">
<table align="center" class="formtable" cellpadding="0" cellspacing="1" width="97%">
	<tr>
	    <td width="20%" align="right">输入短码或别名:</td>
	    <td>
	        <input name="code" required="true" style="border:#336699 1px solid;width: 45%;height: 40px;"/>
	   		*不能为空</td>
	</tr>
	
	<tr>
		<td colspan="2" align="center">	<input type="button" onclick="dorestore();" class="formsubmit" value="提交" ></td>
	</tr>
</table>
</form>
<table align="center" class="formtable" cellpadding="0" cellspacing="1" width="97%" style="display: none" id="restore_table">
	<tr>
	    <td width="80px" align="right">原网址是:</td>
	    <td><textarea rows="5" cols="80" id="restore_url"></textarea></td>
	</tr>
</table>
<script type="text/javascript">
function dorestore() {
	$.post("?action=shorturl_list&todo=dorestore", 
			$("#restoreform").serialize(),
			function(data){
				$("#restore_table").show("fast");
				if(data.url){
					$("#restore_url").val(data.url);
				}else{
					$("#restore_url").val('没有这个短码或别名!');
					};
			}, "json");
}
</script>
<?php include template('foot'); ?>