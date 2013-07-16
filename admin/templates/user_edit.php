<?php if(!defined('IN_SITE')) exit('Access Denied'); ?>
<?php  include template('header'); ?>
<div class="formnav">管理员信息修改</div>
<form name="creator" action="?action=<?php echo $act['action']?>&todo=saveedit" method="post" onsubmit="return CheckForm(this,true);">
<input type="hidden" value="<?php echo $formhash?>" name="formhash">
<table align="center" class="formtable" cellpadding="0" cellspacing="1" width="580">
    <tr>
		<td width="20%" align="right">原密码:</td>
   		<td><input name="old_password" id="old_password"  type="password" style="border:#336699 1px solid;" onMouseOver="fEvent('mouseover',this)" onFocus="fEvent('focus',this)" onBlur="fEvent('blur',this)" onMouseOut="fEvent('mouseout',this)" />*修改密码必须填写原密码</td>
   </tr>
<tr>
<td width="20%" align="right">新密码:</td>
   	<td>
<input name="password" id="password"  type="password"  style="border:#336699 1px solid;" onMouseOver="fEvent('mouseover',this)" onFocus="fEvent('focus',this)" onBlur="fEvent('blur',this)" onMouseOut="fEvent('mouseout',this)" /> 
   <font color="red">*密码长度不能小于六位数</font></td>
</tr>
<tr>
<td width="20%" align="right"><input type="hidden" name="uid" value="<?php echo $uid?>">确认新密码:</td>
   	<td>
<input name="password2" id="password"  type="password"  style="border:#336699 1px solid;" onMouseOver="fEvent('mouseover',this)" onFocus="fEvent('focus',this)" onBlur="fEvent('blur',this)" onMouseOut="fEvent('mouseout',this)" /> 
   <font color="red">*密码长度不能小于六位数</font></td>
</tr>
<tr>
	<td colspan="2" align="center">不修改密码则上面不做修改</td>
</tr>

<tr>
    <td width="20%" align="right">登陆名称:</td>
    <td><input name="username" id="username"  fun="UserName" required="true" style="border:#336699 1px solid;" onMouseOver="fEvent('mouseover',this)" onFocus="fEvent('focus',this)" onBlur="fEvent('blur',this)" onMouseOut="fEvent('mouseout',this)" value="<?php echo $username?>" /></td>
</tr>
<tr>
	<td width="20%" align="right">称呼:</td>
	<td>
		<input name="zname" id="zname" value="<?php echo $userinfo['zname']?>" style="border:#336699 1px solid;" />
	</td>
</tr>
<tr>
	<td width="20%" align="right">邮箱:</td>
   	<td>
		<input name="email" value="<?php echo $userinfo['email']?>" type="email" style="border:#336699 1px solid;" />
	</td>
</tr>
<!-- 咱不修改..有待调整 
<tr><td colspan="2">&nbsp;</td></tr>

<tr>
	<td colspan="2" align="center" valign="middle"><img height="30px" border="0" src="<?php //echo get_gravatar($userinfo['email']);?>"><a href="http://gravatar.com" target="_blank">点这里修改你的全球头像</a></td>
</tr>
<tr><td colspan="2">&nbsp;</td></tr>
 -->
<tr>
	<td colspan="2" align="center">
		<input type="submit" class="formsubmit" value="提交" >
	</td>
</tr>

</table>
</form>


<?php include template('foot'); ?>
