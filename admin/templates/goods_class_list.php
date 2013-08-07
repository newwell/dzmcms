<?php if(!defined('IN_SITE')) exit('Access Denied'); ?>
<?php include template('header'); ?>
<div class="formnav"><?php echo $act['title'];?></div> 
<style>.xiankuan{max-width: 220px;overflow: hidden;text-overflow: ellipsis;width: 220px;white-space:nowrap;}</style>
<table width="98%"  border="0" cellpadding="0" cellspacing="0" align="center">
  <tr>
    <td valign="top" align="center" width="100%">
    <table width="100%" cellpadding="1" cellspacing="1" align="center" class="listtable">
    	<tr >
         <td colspan="1" >
            <input type="button" value="添加一级分类" class="button_input" onclick="jumpto('?action=goods_class&todo=clasadd&fid=0')">
         </td>
        </tr>
        <tr>
            <th align="left" width="12%">&nbsp;&nbsp;<img src="<?php echo $_TEMPLATESDIR?>/image/icon_component.gif" /></th>
			<th>名称</th>
			<th>备注</th>
			<th>添加子分类</th>	
			<th>操作</th>
        </tr>
		<?php if(is_array($infoArr)) { foreach($infoArr as $key => $value) { ?>
        <tr style="background-color:#FAFAFA;">      
            <td class="list" style="border-bottom:1px #CCC solid;" align="left"><img src="<?php echo $_TEMPLATESDIR;?>image/folder_new.gif" border="0"/></td>
            <td class="list" style="border-bottom:1px #CCC solid;"><?php echo $value['name']?></td>
            <td class="list xiankuan" style="border-bottom:1px #CCC solid;"><?php echo $value['remark']?></td>
            <td class="list" style="border-bottom:1px #CCC solid;"><input type="button" class="button_input" value="添加子分类" onclick="jumpto('?action=goods_class&todo=clasadd&fid=<?php echo $value['id']?>')"/></td>
            
			<td class="list" style="border-bottom:1px #CCC solid;">
			<!-- <a href="?action=goods_class&todo=class_update&id=<?php echo $value['id']?>" title="修改"><img src="<?php echo $_TEMPLATESDIR?>image/edit_g.gif" border="0" alt="修改"/></a> -->
			<a href="?action=goods_class&todo=class_del&id=<?php echo $value['id']?>" title="删除"><img src="<?php echo $_TEMPLATESDIR?>image/delete_g.gif" border="0" alt="删除"/></a>
			</td>
        </tr>
        <?php if (is_array($value['childmodules'])){foreach ($value['childmodules'] as $childmodules_value) {?>
        <tr>
        	<td class="list" style="border-bottom:1px #CCC dotted;">&nbsp;&nbsp;&nbsp;<img src="<?php echo $_TEMPLATESDIR;?>image/join.gif" border="0"/><img src="<?php echo $_TEMPLATESDIR;?>image/page_text.gif" border="0"/></td>
        	<td class="list" style="border-bottom:1px #CCC dotted;"><?php echo $childmodules_value['name']?></td>
        	<td class="list xiankuan" style="border-bottom:1px #CCC dotted;"><?php echo $childmodules_value['remark']?></td>
        	<td class="list" style="border-bottom:1px #CCC dotted;">&nbsp;</td>
        	<td class="list" style="border-bottom:1px #CCC dotted;">
        	<!-- <a href="?action=goods_class&todo=class_update&id=<?php echo $childmodules_value['id']?>" title="修改"><img src="<?php echo $_TEMPLATESDIR?>image/edit_g.gif" border="0" alt="修改"/></a> -->
			<a href="?action=goods_class&todo=class_del&id=<?php echo $childmodules_value['id']?>" title="删除"><img src="<?php echo $_TEMPLATESDIR?>image/delete_g.gif" border="0" alt="删除"/></a></td>
        </tr>
        <?php }}?>
		<?php } }?>
		<tr bgcolor="#A6D0F6" align="center">
			<td colspan="5"></td>
		</tr>
    </table>
</td>
  </tr>
</table>
<?php include template('foot'); ?>