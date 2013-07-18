<?php if(!defined('IN_SITE')) exit('Access Denied'); ?>
<?php include template('header'); ?>
<div class="formnav">系统参数设置</div>
<form action="?action=<?php echo $act['action']?>&todo=saveset" method="post" >
<input type="hidden" name="formhash" value="<?php echo $formhash?>">
<table width="650"  border="0" cellpadding="1" cellspacing="1" align="center" class="formtable" >
        <tr>  
          <td width="25%" align="right" class="listtable" valign="top">俱乐部名称:</td>
          <td align="left"><input type="text" name="setting_sitename" fun="required" required="true" value="<?php echo $setting_sitename?>"  size="35" style="border:#336699 1px solid;" onmouseover="fEvent('mouseover',this)" onfocus="fEvent('focus',this)" onblur="fEvent('blur',this)" onmouseout="fEvent('mouseout',this)"/>
          *将显示在管理首页标题中</td>  
        </tr>
        <tr>
            <td align="right" class="listtable" valign="top">联系电话:</td>
            <td align="left"><input type="text" name="setting_sitephone" value="<?php echo $setting_sitephone?>" fun="required" required="true" size="35" style="border:#336699 1px solid;" onmouseover="fEvent('mouseover',this)" onfocus="fEvent('focus',this)" onblur="fEvent('blur',this)" onmouseout="fEvent('mouseout',this)"/>
            </td>
        </tr>
        <tr>  
            <td align="right" class="listtable" valign="top">联系邮箱:</td>
            <td align="left"><input type="text" name="setting_siteemail" value="<?php echo $setting_siteemail?>"  fun="required" required="true"  size="35" style="border:#336699 1px solid;" onmouseover="fEvent('mouseover',this)" onfocus="fEvent('focus',this)" onblur="fEvent('blur',this)" onmouseout="fEvent('mouseout',this)"/> 
            </td>
        </tr>
        
        <tr>  
            <td align="right" class="listtable" valign="top">url地址:</td>
            <td align="left"><input type="text" name="setting_siteurl" value="<?php echo $setting_siteurl?>" size="35" style="border:#336699 1px solid;" onmouseover="fEvent('mouseover',this)" onfocus="fEvent('focus',this)" onblur="fEvent('blur',this)" onmouseout="fEvent('mouseout',this)"/> 
            </td>
        </tr>
        
    	<tr> 
        	<td colspan="2" align="center">
            <input type="submit" class="formsubmit" value="提交">            </td>
        </tr>
    </table>
</form>
<?php include template('foot'); ?>
