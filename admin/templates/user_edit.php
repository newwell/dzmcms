<?php if(!defined('IN_SITE')) exit('Access Denied'); ?>
<?php include template('header'); ?>
<div class="formnav"><?=$nav?> > 管理员信息修改</div>
<form action="?action=<?=$act['action']?>&todo=saveedit" method="post" onsubmit="return CheckForm(this,true);">
<input type="hidden" value="<?=$formhash?>" name="formhash">
<table align="center" class="formtable" cellpadding="0" cellspacing="1" width="97%">
<tr>
    	<td width="20%" align="right">
        	用户名:
        </td>
        <td>
       <input name="username" id="username"  fun="UserName" required="true" style="border:#336699 1px solid;" onMouseOver="fEvent('mouseover',this)" onFocus="fEvent('focus',this)" onBlur="fEvent('blur',this)" onMouseOut="fEvent('mouseout',this)" value="<?=$username?>" />
</td>
   </tr>
<tr>
<td width="20%" align="right"><input type="hidden" name="uid" value="<?=$uid?>">
        密码:
        </td>
   	<td>
<input name="password" id="password"  type="password"  style="border:#336699 1px solid;" onMouseOver="fEvent('mouseover',this)" onFocus="fEvent('focus',this)" onBlur="fEvent('blur',this)" onMouseOut="fEvent('mouseout',this)" /> 
   </td>
   </tr>

   
<?php if($_SESSION['userlevel'] == 2 ) { ?>
    <tr>
<td width="20%" align="right">
        原密码:
        </td>
   	<td>
<input name="old_password" id="old_password"  type="password" style="border:#336699 1px solid;" onMouseOver="fEvent('mouseover',this)" onFocus="fEvent('focus',this)" onBlur="fEvent('blur',this)" onMouseOut="fEvent('mouseout',this)" />*修改密码必须填写原密码</td>
   </tr>
<? } ?>

<?php if($_SESSION['userlevel'] == 1 ) { ?>
   <tr>
<td width="20%" align="right">
        管理员级别:
        </td>
   	<td>
<input type="radio" name="userlevel" value="1" <?=$supadmincheck?> onfocus="$('#rightpanel').hide();"/>超级管理员
<input type="radio" name="userlevel" value="2" <?=$admincheck?> onfocus="$('#rightpanel').show();"/>管理员
    </td>
   </tr>
    <tbody id="rightpanel" style="display:none;">

   <tr>
<td width="100%" colspan="2" align="center">管理员权限分配</td>
   </tr>
   
<?php 
//print_r($cates);

if(is_array($cates)) { foreach($cates as $key => $cate) { ?>
<tr>
<td width="20%" align="right">
<?=$cate['title']?>:
</td>
<td>
<?php if(is_array($cate['childs'])) { foreach($cate['childs'] as $num => $child) { ?>
<input type="checkbox" name="action[]" value="<?=$child['action']?>" 
<?php if($child['cando'] == 1) { ?>
checked
<? } ?>
><?=$child['title']?>
<?php if(($num % 3) == 0 && $num > 0) { ?>
<br>
<? } ?>
<?php } } ?>
</td>
   </tr>
<?php } } ?>

</tbody>
<? } ?>
   <tr>
<td colspan="2" align="center">	<input type="submit" class="formsubmit" value="提交" >
</td>

    </tr>

</table>
</form>
<?php if($userarr['userlevel']== 2) { ?>
<script>
$('#rightpanel').toggle();
//Element.('rightpanel');
</script>
<? } ?>
<?php include template('foot'); ?>
