<?php if(!defined('IN_SITE')) exit('Access Denied'); ?>
<?php include template('header'); ?>
<div class="formnav">管理员列表</div>
<table width="98%"  border="0" cellpadding="0" cellspacing="0" align="center">
  <tr>
    <td valign="top" align="center" width="100%">
    
    <table width="100%" cellpadding="1" cellspacing="1" align="center" class="listtable">
        <tr>
            <th width="18%">用户名</th>
            <th width="24%">管理员级别</th>
            <th width="15%">上次登陆时间</th>
            <th width="15%">上次登陆IP</th>
            <th width="18%">操作</th>
        </tr>
     
<?php if(is_array($adminarr)) { foreach($adminarr as $key => $admin) { ?>
        <tr 
<?php if(($key%2) == 0 ) { ?>
 bgcolor="#E4EDF9" 
<?php } else { ?>
 bgcolor="#F1F3F5" 
<? } ?>
 >  
            <td  class="list"><?=$admin['username']?> </td>
            <td  class="list"><?=$admin['userlevel']?> </td>
            <td  class="list"><?=$admin['lastlogintime']?></td>
            <td  class="list"><?=$admin['lastloginip']?> </td>
            <td  class="list" align="center">
            
<?php if($_SESSION['userlevel'] == 1) { ?>
            <a href="?action=<?=$act['action']?>&todo=edituser&uid=<?=$admin['id']?>"><img src="<?=$_TEMPLATESDIR?>/image/edit_g.gif" border="0" alt="提交"/></a>  | <a href="javascript:delconfirm('?action=<?=$act['action']?>&todo=deluser&uid=<?=$admin['id']?>');"><img src="<?=$_TEMPLATESDIR?>/image/delete_g.gif" border="0" /></a>
<?php } elseif($_SESSION['userlevel'] == 2) { ?>
<?php if($admin['id'] == $_SESSION['uid']) { ?>
 <a href="?action=<?=$act['action']?>&todo=edituser&uid=<?=$admin['id']?>">
 <img src="<?=$_TEMPLATESDIR?>/image/edit_g.gif" border="0" alt="提交"/>
 </a>
<? } } ?>
            </td>
        </tr>
     
<?php } } ?>
<?php if($_SESSION['userlevel'] == 1) { ?>
      <tr class="tablenav">
         <td colspan="5" align="right"><input type="button" value="添加管理员" onclick="jumpto('?action=<?=$act['action']?>&todo=adduser')"></td>
     </tr>
    
<? } ?>
    </table> 
</td>
  </tr>
</table>
<?php include template('foot'); ?>
