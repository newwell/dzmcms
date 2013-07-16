<?php if(!defined('IN_SITE')) exit('Access Denied'); ?>
<?php include template('header'); ?>
<div class="formnav">系统参数设置</div>
<form action="?action=<?php echo $act['action']?>&todo=saveset" method="post" >
<input type="hidden" name="formhash" value="<?php echo $formhash?>">
<table width="650"  border="0" cellpadding="1" cellspacing="1" align="center" class="formtable" >
        <tr>  
          <td width="25%" align="right" class="listtable" valign="top">网站名称:</td>
          <td align="left"><input type="text" name="setting_sitename" fun="required" required="true" value="<?php echo $setting_sitename?>"  size="35" style="border:#336699 1px solid;" onmouseover="fEvent('mouseover',this)" onfocus="fEvent('focus',this)" onblur="fEvent('blur',this)" onmouseout="fEvent('mouseout',this)"/>
          *网站名称，将显示在前台首页标题中</td>  
        </tr>
        <tr>
            <td align="right" class="listtable" valign="top">网站电话:</td>
            <td align="left"><input type="text" name="setting_sitephone" value="<?php echo $setting_sitephone?>" fun="required" required="true" size="35" style="border:#336699 1px solid;" onmouseover="fEvent('mouseover',this)" onfocus="fEvent('focus',this)" onblur="fEvent('blur',this)" onmouseout="fEvent('mouseout',this)"/>
            *网站电话，将显示在页面首页的联系方式处</td>
        </tr>
        <tr>  
            <td align="right" class="listtable" valign="top">网站邮件:</td>
            <td align="left"><input type="text" name="setting_siteemail" value="<?php echo $setting_siteemail?>"  fun="required" required="true"  size="35" style="border:#336699 1px solid;" onmouseover="fEvent('mouseover',this)" onfocus="fEvent('focus',this)" onblur="fEvent('blur',this)" onmouseout="fEvent('mouseout',this)"/> 
            *网站邮件，将显示在页面首页的网站邮件处</td>
        </tr>
        
        <tr>  
            <td align="right" class="listtable" valign="top">网站地址:</td>
            <td align="left"><input type="text" name="setting_siteurl" value="<?php echo $setting_siteurl?>" size="35" style="border:#336699 1px solid;" onmouseover="fEvent('mouseover',this)" onfocus="fEvent('focus',this)" onblur="fEvent('blur',this)" onmouseout="fEvent('mouseout',this)"/> 
            </td>
        </tr>
        
        <tr>
            <td align="right" class="listtable" valign="top">网站关闭:</td>
            <td align="left"><input type="radio" name="setting_sitestatus" value="1" <?php echo $check1?>>开启<input type="radio" name="setting_sitestatus" value="0" <?php echo $check2?>>关闭 *暂时将网站关闭，其他人无法访问，但不影响管理员访问</td>
        </tr>
        <tr>  
            <td align="right" class="listtable" valign="top">网站关闭原因:</td>
            <td align="left"><textarea style="border:#336699 1px solid;" rows="5" cols="40" onmouseover="fEvent('mouseover',this)" onfocus="fEvent('focus',this)" onblur="fEvent('blur',this)" onmouseout="fEvent('mouseout',this)" name="setting_siteclosereason"><?php echo $setting_siteclosereason?></textarea> *网站关闭时出现的提示信息</td>
        </tr>
        <tr>  
          <td width="25%" align="right" class="listtable" valign="top">跳转方式:</td>
          <td align="left">
          	<select name="setting_sitejump">
          		<option value="php" <?php if ($setting_sitejump=='php'){echo "selected";}?>>PHP跳转</option>
          		<option value="php301" <?php if ($setting_sitejump=='php301'){echo "selected";}?>>PHP带301跳转</option>
          		<option value="http301" <?php if ($setting_sitejump=='http301'){echo "selected";}?>>http带301跳转</option>
          		<option value="js" <?php if ($setting_sitejump=='js'){echo "selected";}?>>js跳转</option>
          	</select>
          </td>
        </tr>
    	<tr> 
        	<td colspan="2" align="center">
            <input type="submit" class="formsubmit" value="提交">            </td>
        </tr>
    </table>
</form>
<?php include template('foot'); ?>
