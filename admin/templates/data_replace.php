<?php if(!defined('IN_SITE')) exit('Access Denied'); ?>
<?php include template('header'); ?>
<div class="formnav">运行SQL语句</div>
<form action="?action=database&todo=doreplace" method="post">
<input type="hidden" value="<?=$formhash?>" name="formhash">
<table width="98%"  border="0" cellpadding="0" cellspacing="0" align="center">
  <tr>
    <td valign="top" align="center" width="100%">
    
    <table width="100%" cellpadding="1" cellspacing="1" align="center" class="listtable">
        <tr class="listtitle">
            <th width="98%">网站数据内容替换</th>
        </tr>
        <tr bgcolor="#FFFFFF" > 
        <td align="center">                             
           寻找的关键字:<input type="text" size="40" name="search" style="border:#336699 1px solid;" onMouseOver="fEvent('mouseover',this)" onFocus="fEvent('focus',this)" onBlur="fEvent('blur',this)" onMouseOut="fEvent('mouseout',this)"/><br>
   替换的关键字:<input type="text" size="40" name="replace" style="border:#336699 1px solid;" onMouseOver="fEvent('mouseover',this)" onFocus="fEvent('focus',this)" onBlur="fEvent('blur',this)" onMouseOut="fEvent('mouseout',this)"/>
           </td>
        </tr>
     <tr class="tablenav">
         <td colspan="6" align="center"><input type="submit" value="运行SQL语句"></td>
     </tr>
    </table>
</td>
  </tr>
</table>
</form>
<?php include template('foot'); ?>
