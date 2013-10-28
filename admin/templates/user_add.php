<?php if(!defined('IN_SITE')) exit('Access Denied'); ?>
<?php include template('header'); ?>
<div class="formnav">添加管理员</div>
<form action="?action=<?=$act['action']?>&todo=saveadd" method="post" onsubmit="return CheckForm(this,true);">
<input type="hidden" value="<?=$formhash?>" name="formhash">
<table align="center" class="formtable" cellpadding="0" cellspacing="1" width="97%">
<tr>
    	<td width="20%" align="right">
        	用户名:
        </td>
      <td>
       <input name="username" id="username" fun="UserName" required="true" style="border:#336699 1px solid;" onMouseOver="fEvent('mouseover',this)" onFocus="fEvent('focus',this)" onBlur="fEvent('blur',this)" onMouseOut="fEvent('mouseout',this)"/>
       <br />*用户名必须为4-19个字母数字和下划线组成		</td>
   </tr>
<tr>
<td width="20%" align="right">
        密码:
        </td>
   	<td>
<input name="password" id="password"  type="password" fun="PassWord" required="true" style="border:#336699 1px solid;" onMouseOver="fEvent('mouseover',this)" onFocus="fEvent('focus',this)" onBlur="fEvent('blur',this)" onMouseOut="fEvent('mouseout',this)"/>
<br />*密码必须为6-19个字母数字和下划线组成   </td>
   </tr>

   <tr>
<td width="20%" align="right">
        管理员级别:
        </td>
   	<td>
<input type="radio" name="userlevel" value="1" checked="checked" onfocus="$('#rightpanel').hide();"/>超级管理员
<input type="radio" name="userlevel" value="2" onfocus="$('#rightpanel').show();" />管理员
    </td>
   </tr>

  <tbody id="rightpanel" style="display:none">
   <tr>
<td width="100%" colspan="2" align="center">管理员权限分配</td>
   </tr>
   
<?php if(is_array($cates)) { foreach($cates as $key => $cate) { ?>
<tr>
<td width="20%" align="right">
<?=$cate['title']?>:
</td>
<td>
<?php if(is_array($cate['childs'])) { foreach($cate['childs'] as $num => $child) { ?>
<input type="checkbox" name="action[]" value="<?=$child['action']?>" ><?=$child['title']?>
<?php if(($num % 3) == 0 && $num > 0) { ?>
<br>
<? } ?>
<?php } } ?>
</td>
   </tr>
<?php } } ?>
</tbody>
   <tr>
         <td colspan="2" align="center">	<input type="submit" class="formsubmit" value="提交" >
        </td>

    </tr>

</table>
</form>
<?php include template('foot'); ?>
