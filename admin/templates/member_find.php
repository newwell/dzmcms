<?php if(!defined('IN_SITE')) exit('Access Denied'); ?>
<?php include template('header'); ?>
<div class="formnav"><?php echo $act['title'];?></div> 
<script type="text/javascript"> 
document.body.onload = function(){
    document.getElementById("card").focus();
};
</script>
<form action="?action=member_find&todo=dofind" method="post" onsubmit="return CheckForm(this,true);">
<input type="hidden" value="<?php echo $formhash;?>" name="formhash">
<table align="center" class="formtable" cellpadding="0" cellspacing="1" width="97%">
	<tr>
	    <td width="80px" align="right">读卡:</td>
	    <td>
	        <input name="card" id="card" type="text" style="border:#336699 1px solid;"/></td>
	    <td width="80px" align="right">会员卡编号:</td>
	    <td>
	        <input name="cardid" type="text" style="border:#336699 1px solid;"/></td>
	</tr>
	<tr>
		<td colspan="4" align="center">	<input type="submit" class="formsubmit" value="查询"></td>
	</tr>
</table>
</form>
<form action="?action=member_find&todo=so" method="post" onsubmit="return CheckForm(this,true);">
<input type="hidden" value="<?php echo $formhash;?>" name="formhash">
<table align="center" class="formtable" cellpadding="0" cellspacing="1" width="97%">
	<tr>
	    <td width="80px" align="center">按:<select name="option">
	    	<option value="name"  <?php if ($option=='name')echo 'selected';?>>姓名</option>
	    	<option value="phone" <?php if ($option=='phone')echo 'selected';?>>手机号</option>
	    </select>模糊搜索<input name="keywork" value="<?php if (isset($keywork))echo $keywork;?>"/></td>
	</tr>
	<tr>
		<td colspan="4" align="center">	<input type="submit" class="formsubmit" value="搜索"></td>
	</tr>
</table>
</form>
<table width="98%"  border="0" cellpadding="0" cellspacing="0" align="center">
  <tr>
    <td valign="top" align="center" width="100%">
    <table width="100%" cellpadding="1" cellspacing="1" align="center" class="listtable">
        <tr>
            <th>姓名</th>
			<th>昵称</th>
			<th>读卡</th>
			<th>会员号</th>
			<th>手机号</th>
			<th>积分</th>	
			<th>奖励积分</th>
			<th>积分合计</th>
			<th>操作</th>		
        </tr>
		<?php if(is_array($infoList)) { foreach($infoList as $key => $value) { ?>
        <tr <?php if (($key%2) == 0){echo 'bgcolor="#E4EDF9"';}else {echo 'bgcolor="#F1F3F5"';}?>>      
            <td class="list"><a href="?action=member_find&todo=dofind&card=<?php echo $value['card'];?>"><?php echo $value['name']?></a></td>  
            <td class="list"><?php echo $value['nickname']?></td>
            <td class="list"><?php echo $value['card']?></td>
            <td class="list"><?php echo $value['cardid']?></td>
            <td class="list"><?php echo $value['phone']?></td>
            <td class="list" ><?php echo $value['balance']?></td>
            <td class="list"><?php echo $value['jiangli_jifen']?></td>
            <td class="list" style="color: red;"><?php echo $value['balance']+$value['jiangli_jifen']?></td>
			<td class="list">
			<a href="JavaScript:;" onclick="if(confirm('删除不可恢复,确认删除?')){location.href='?action=member_find&todo=del&id=<?php echo $value['card']?>'}" title="删除"><img src="<?php echo $_TEMPLATESDIR?>/image/delete_g.gif" border="0" alt="删除"/></a>
			</td>
        </tr>
		<?php } }?>
		<tr bgcolor="#A6D0F6" align="center">
			<td colspan="9"><?php if (!empty($page_control)){echo $page_control;}?></td>
		</tr>
    </table>
</td>
  </tr>
</table>
<?php include template('foot'); ?>