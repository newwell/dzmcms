<?php if(!defined('IN_SITE')) exit('Access Denied'); ?>
<?php include template('header'); ?>
<div class="formnav">运行SQL语句</div>
<form action="?action=database&todo=execute" method="post">
<input type="hidden" value="<?=$formhash?>" name="formhash">
<table width="98%"  border="0" cellpadding="0" cellspacing="0" align="center">
  <tr>
    <td valign="top" align="center" width="100%">
    
    <table width="100%" cellpadding="1" cellspacing="1" align="center" class="listtable">
        <tr class="listtitle">
            <th width="98%">请在这里输入要运行的SQL语句</th>
        </tr>
        <tr bgcolor="#FFFFFF" > 
        <td align="center">                             
           <textarea name="sql" cols="50" rows="15" style="border:#336699 1px solid;" onMouseOver="fEvent('mouseover',this)" onFocus="fEvent('focus',this)" onBlur="fEvent('blur',this)" onMouseOut="fEvent('mouseout',this)"></textarea>　
          <br /> *输入要执行的SQL语句，看能否执行正确　　
           </td>
        </tr>
     <tr class="tablenav">
         <td colspan="6" align="center">
 	<input type="submit" value="运行SQL语句">
<a href="?action=database&todo=replace">网站数据内容替换</a>
</td>
     </tr>
    </table>
</td>
  </tr>
</table>
</form>
<?php include template('foot'); ?>
