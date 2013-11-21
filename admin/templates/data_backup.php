<?php if(!defined('IN_SITE')) exit('Access Denied'); ?>
<?php include template('header'); ?>
<div class="formnav">平台数据库备份</div>
<form action="?action=database_backup&todo=dobackup" method="post" onsubmit="return CheckForm(this,true);">
<input type="hidden" value="<?=$formhash?>" name="formhash">
<table align="center" class="formtable" cellpadding="0" cellspacing="1" width="98%">
   <tr>
        <td align="right">
        备份文件压缩设定:
        </td>
       <td>
		<!-- <input name="ziped" value="0" type="radio">不压缩 -->
        <input name="ziped" value="1" type="radio">分卷备份
        <input name="ziped" value="2" type="radio" checked="checked">备份为一个文件
        </td>
   </tr>
   <tr>
<td align="right">
        备份数据兼容格式:
        </td>
   		<td>
   	        <!-- <input class="radio" type="radio" name="sqlcompat" value="MYSQL40"> MySQL 3.23/4.0.x &nbsp; --> <input class="radio" type="radio" name="sqlcompat" value="MYSQL41" checked> DZBAK 1.x 和 DZBAK 2.x &nbsp;
   		</td>
   	</tr>
   	
   	<tr>
<td align="right">

备份的文件名:
        </td>
   	<td>
<input type="text" name="filename" value="<?=$filename?>" fun="required" required="true" size="30" style="border:#336699 1px solid;" onmouseover="fEvent('mouseover',this)" onfocus="fEvent('focus',this)" onblur="fEvent('blur',this)" onmouseout="fEvent('mouseout',this)"/>
   	</td>
   </tr>
   	<tr>
<td align="right">
分卷备份大小:
        </td>
   	<td>
<input type="text" name="sizelimit" value="2024" fun="isInt('0+')" required="true" style="border:#336699 1px solid;" onmouseover="fEvent('mouseover',this)" onfocus="fEvent('focus',this)" onblur="fEvent('blur',this)" onmouseout="fEvent('mouseout',this)"/>kb *分卷备份 - 每个分卷文件大小限制(kb) 
   </td>
   </tr>
   
   
   
   
   <tr>
<td colspan="2" align="center">	<input type="submit" class="formsubmit" value="开始备份" >
        </td>

        </tr>
</table>
</form>
<table align="center" cellpadding="0" cellspacing="1" width="98%" class="listtable">
   <!--<tr >
        <th align="center" colspan="4" >
        已经备份文件列表
       </th>
   </tr>-->
   <tr>
        <th align="center" width="50%">备份文件名称</th>
        <th width="20%" align="center">备份文件大小</th>
        <th width="20%" align="center">修改时间</th>
        <th width="10%" class="list" align="center">操作</th>
   </tr>
    
<?php if(is_array($filearr)) { foreach($filearr as $key => $file) { ?>
    
<?php if(($key%2) == 0 ) { ?>
   		<tr  bgcolor="#E4EDF9">
   
<?php } else { ?>
   		<tr  bgcolor="#F1F3F5" >
   	
<? } ?>
       <td align="center"><?=$file['name']?></td>
       <td align="center"><?=$file['size']?></td> 
       <td align="center"><?=$file['edittime']?></td>
       <td class="list" align="center">
       
<?php if(preg_match('/\.dzbak$/',$file['name'])) { ?>
       		<!-- <a title="还原数据库" href="?action=database&todo=importzip&datafile=<?=$file['name']?>"><img src="<?=$_TEMPLATESDIR?>/image/restore_g.gif" border="0" ></a> | --> 
       		<a title="下载备份数据" href="<?=$file['name']?>"><img src="<?=$_TEMPLATESDIR?>/image/restore_g.gif" border="0" ></a>&nbsp;&nbsp;
       		<a title="删除数据库备份文件" onclick="if(confirm('删除不可恢复,确认删除?')){location.href='?action=database_backup&todo=del&file=<?php echo $file['name'];?>';}" href="JavaScript:;"><img src="<?=$_TEMPLATESDIR?>/image/delete_g.gif" border="0"></a>
<?php }?>
       </td>
    
<?php } } ?>
     
   
   </tr>
</table>
<?php include template('foot'); ?>