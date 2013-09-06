<?php if(!defined('IN_SITE')) exit('Access Denied'); ?>
<?php include template('header'); ?>
<div class="formnav"><?php echo $act['title'];?></div>

<table width="98%"  border="0" cellpadding="0" cellspacing="0" align="center">
	<tr >
    	<td colspan="1" >
            <input type="button" value="添加" class="button_input" onclick="$('#add_').show('fast');">
		</td>
   </tr>
  <tr>
    <td valign="top" align="center" width="100%">
    <table width="100%" cellpadding="1" cellspacing="1" align="center" class="listtable">
        <tr>
            <th>姓名</th>
			<th>激活</th>
			<th>操作</th>		
        </tr>
		<?php if(is_array($infoList)) { foreach($infoList as $key => $value) { ?>
        <tr <?php if (($key%2) == 0){echo 'bgcolor="#E4EDF9"';}else {echo 'bgcolor="#F1F3F5"';}?>>     
            <td class="list"><?php echo $value['name']?></td>  
            <td class="list" ><?php if ($value['activate']){echo '已激活'; }else {echo '未激活';}?></td>
            <td class="list"><?php if ($value['activate']){?>
            	<a href="?action=staff_deingcoholr_list&todo=deingcoholr_activate&activate=0&id=<?php echo $value['id'];?>">取消激活</a>
	           <?php  }else {?>
	           	<a href="?action=staff_deingcoholr_list&todo=deingcoholr_activate&activate=1&id=<?php echo $value['id'];?>">激活</a>
	           <?php }?>&nbsp;|&nbsp;
	           	<a onclick="if(confirm('确定删除?')){location.href='?action=staff_deingcoholr_list&todo=deingcoholr_del&id=<?php echo $value['id'];?>'}" href="JavaScript:;">删除</a>
           </td>
        </tr>
		<?php } }?>
		<form method="post" action="?action=staff_deingcoholr_list&todo=deingcoholr_add">
		<input type="hidden" value="<?php echo $formhash;?>" name="formhash">
		<tr bgcolor="#E4EDF9" style="display: none;" id="add_">
			<td class="list">添加&nbsp;&nbsp;&nbsp;<input name="name"/></td>
			<td class="list"><select name="activate">
				<option value="1">是</option>
				<option value="0">否</option>
			</select></td>
			<td class="list"><input type="submit" value="提交" class="formsubmit"/></td> 
		</tr>
		</form>
		<tr bgcolor="#A6D0F6" align="center">
			<td colspan="7"><?php if (!empty($page_control)){echo $page_control;}?></td>
		</tr>
    </table>
</td>
  </tr>
</table>
<?php include template('foot'); ?>