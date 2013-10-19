<?php if(!defined('IN_SITE')) exit('Access Denied'); ?>
<?php include template('header'); ?>
<div class="formnav"><?php echo $act['title'];?></div>
<form method="post">
<input type="hidden" value="<?php echo $formhash;?>" name="formhash">
<table width="98%"  border="0" cellpadding="0" cellspacing="0" align="center" >
  <tr>
    <td valign="top" align="center" width="100%">    
    <table width="100%" cellpadding="1" cellspacing="1" align="center" class="listtable">
        <tr>
            <th>图标</th>
            <th>名称</th>
            <th>序号</th>
            <th>操作<input type="checkbox" name="chkall" onClick="checkall(this.form)"></th>
        </tr>
        <?php 
        if (is_array($listArr)) {foreach ($listArr as $key => $value) {
        	if (($key%2)==0){
        		$bgcolor="#E4EDF9";
        	}else {
        		$bgcolor="#F1F3F5";
        	};?>
        	<tr bgcolor="<?php echo $bgcolor;?>">
        		<td class="list"><img height="24px" src="script/icons/<?php echo $value['iconid'];?>.png"></td>
        		<td class="list"><?php echo $value['action']["title"];?></td>
        		<td class="list">
        			<input name="listnum[<?php echo $value['id'];?>]" value="<?php echo $value['listnum'];?>" size="3"/>
        		</td>
        		<td class="list">
	            	<!-- <a href="?action=livechat&todo=edit&id=<?php echo $value['id'];?>"><img src="<?php echo $_TEMPLATESDIR;?>/image/edit_g.gif" border="0"></a> -->
	            	删除<input type="checkbox" name="id[]" value="<?php echo $value['id'];?>">
            	</td>  
        	</tr>
        <?php };}?>
     <tr class="tablenav">
         <td colspan="5" align="right">
         	<input type="button" value="添加图标" onclick="jumpto('?action=icon_list&todo=add')">
         	<input type="button" value="一键排序" onclick="commendsubmitform('?action=icon_list&todo=serialnumber',this.form,'')">
         	<input type="button" value="批量删除" onclick="commendsubmitform('?action=icon_list&todo=del',this.form,'del')">
         </td>
     </tr>
    </table>
	</td>
  </tr>
</table>
</form>
<?php include template('foot'); ?>