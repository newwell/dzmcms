<?php if(!defined('IN_SITE')) exit('Access Denied'); ?>
<?php include template('header'); ?>
<div class="formnav"><?php echo $act['title'];?></div>
<style>.xiankuan{max-width: 330px;overflow: hidden;text-overflow: ellipsis;width: 330px;white-space:nowrap;}</style>
<script type="text/javascript"> 
document.body.onload = function(){
    document.getElementById("card").focus();
};
</script>
<form action="?action=statistics_balance_change&todo=balance_change" method="post" onsubmit="return CheckForm(this,true);">
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
	    <td width="80px" align="right">积分:</td>
	    <td style="color: red;"><?php if (empty($member_info['balance'])){ echo "0.00";}else {echo $member_info['balance'];};?></td>
	    <td width="80px" align="right">奖励积分:</td>
	    <td><?php echo $member_info['jiangli_jifen'];?></td>
	</tr>
</table><br>
<?php }?>
<table width="98%"  border="0" cellpadding="0" cellspacing="0" align="center">
  <tr>
    <td valign="top" align="center" width="100%">
    <table width="100%" cellpadding="1" cellspacing="1" align="center" class="listtable">
        <tr>
            <!-- <th ><input type="checkbox" name="chkall" onclick="checkall(this.form)" title="全选">全选</th> -->
            <th>读卡</th>
			<th >说明</th>
			<th>产生日期</th>
			<th>操作</th>		
        </tr>
		<?php if(is_array($infoList)) { foreach($infoList as $key => $value) { ?>
        <tr <?php if (($key%2) == 0){echo 'bgcolor="#E4EDF9"';}else {echo 'bgcolor="#F1F3F5"';}?>>
            <!-- <td class="list"><input type="checkbox" name="ids[]" value="<?php echo $value['id']?>">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td> -->      
            <td class="list"><?php echo $value['card']?></td>  
            <td class="list xiankuan"><?php echo $value['explain']?></td>
            <td class="list"><?php echo $value['add_date']?></td>
			<td class="list">
			<a href="?action=shorturl_list&todo=update&id=<?php echo $value['id']?>" title="修改"><img src="<?php echo $_TEMPLATESDIR?>/image/edit_g.gif" border="0" alt="修改"/></a>
			</td>
        </tr>
		<?php } }?>
		<tr bgcolor="#A6D0F6" align="center">
			<td colspan="7"><?php if (!empty($page_control)){echo $page_control;}?></td>
		</tr>
    </table>
</td>
  </tr>
</table>
<?php include template('foot'); ?>