<?php if(!defined('IN_SITE')) exit('Access Denied'); ?>
<?php include template('header'); ?>
<div class="formnav">数据库优化</div>
<form  method="post" id="data" action="?action=database_optimize&todo=optimize">
<input type="hidden" value="<?=$formhash?>" name="formhash"> 
<table width="98%"  border="0" cellpadding="0" cellspacing="0" align="center">
  <tr>
    <td valign="top" align="center" width="100%">
    
    <table width="100%" cellpadding="1" cellspacing="1" align="center" class="listtable">
        <tr >
            <th width="20%" >数据表</td>
            <th width="10%" >数据行数</td>
            <th width="20%" >数据库长度</td>
<th width="20%" >索引</td>
            <th width="20%" >碎片</td>
            <th width="10%" >全选<input type="checkbox" name="chkall" onclick="checkall(this.form)"></td>
        </tr>
     
<?php if(is_array($tablearray)) { foreach($tablearray as $key => $table) { ?>
        <tr 
<?php if(($key%2) == 0 ) { ?>
 bgcolor="#E4EDF9" 
<?php } else { ?>
 bgcolor="#F1F3F5" 
<? } ?>
 >  
            <td  class="list"><?=$table['Name']?> </td>
            <td  class="list"><?=$table['Rows']?> </td>
            <td  class="list"><?=$table['Data_length']?></td>
<td  class="list"><?=$table['Index_length']?></td>
            <td  class="list"><?=$table['Data_free']?></td>
            <td  class="list"><input type="checkbox" name="tables[]" value="<?=$table['Name']?>"></td>
            
        </tr>
     
<?php } } ?>
     <tr class="tablenav">
         <td colspan="6" align="right"><input type="submit" value="优化选中的数据表"></td>
     </tr>
    </table>
</td>
  </tr>
</table>
</form>
<?php include template('foot'); ?>