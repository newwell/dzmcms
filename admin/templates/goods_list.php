<?php if(!defined('IN_SITE')) exit('Access Denied'); ?>
<?php include template('header'); ?>
<div class="formnav"><?php echo $act['title'];?></div> 
<form  method="post" id="data" action="?action=goods_list">
<input type="hidden" value="<?php echo $formhash;?>" name="formhash">
<style>.xiankuan{max-width: 220px;overflow: hidden;text-overflow: ellipsis;width: 220px;white-space:nowrap;}</style>
<table width="98%"  border="0" cellpadding="0" cellspacing="0" align="center">
  <tr>
    <td valign="top" align="center" width="100%">
    <table width="100%" cellpadding="1" cellspacing="1" align="center" class="listtable">
		<tr >
         <td colspan="1" >
            <input type="button" value="删除" class="button_input" onclick="JavaScript:if(confirm('删除操作不可恢复,确认吗?')){commendsubmitform('?action=goods_list&todo=del',this.form,'');}">
         </td>
         <td colspan="6" align="right">
         <input type="hidden" name="action" value="goods_list"/>
         <input type="hidden" name="todo" value="search"/>
         	按<select name="d_option">
         		<option value="name" <?php if(!empty($d_option)&&$d_option=='name'){echo 'selected';}?>>名称</option>
         		<option value="suk" <?php if(!empty($d_option)&&$d_option=='suk'){echo 'selected';}?>>简码</option>
         	</select>搜索<input name="keywork" value="<?php if (!empty($keywork))echo $keywork;?>"/>
         	<input type="button" value="搜索" class="button_input" onclick="JavaScript:searchsubmitform(this.form);">
         	<script type="text/javascript">
         	function searchsubmitform(form) {
         		form.method='get';
         		form.action='?action=goods_list&todo=search';
         		form.submit();
			}
         	</script>
         </td>
        </tr>
        <tr>
            <th><input type="checkbox" name="chkall" onclick="checkall(this.form)" title="全选">全选</th>
            <th>名称</th>
			<th>简码</th>
			<th>单位</th>
			<th>售价(积分)</th>
			<th>奖励积分</th>	
			<th>操作</th>
        </tr>
		<?php if(is_array($goodslArr)) { foreach($goodslArr as $key => $value) { ?>
        <tr <?php if (($key%2) == 0){echo 'bgcolor="#E4EDF9"';}else {echo 'bgcolor="#F1F3F5"';}?>>
            <td class="list"><input type="checkbox" name="ids[]" value="<?php echo $value['id']?>">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>      
            <td class="list"><?php echo $value['name']?></td>
            <td class="list"><?php echo $value['suk']?></td>
            <td class="list"><?php echo $value['unit']?></td>
            <td class="list"><font color="red"><?php echo $value['price']?></font></td>
            <td class="list"><?php echo $value['jiangli_jifen']?></td>
            
			<td class="list">
			<a href="?action=goods_list&todo=update&id=<?php echo $value['id']?>" title="修改"><img src="<?php echo $_TEMPLATESDIR?>/image/edit_g.gif" border="0" alt="修改"/></a>
			</td>
        </tr>
		<?php } }?>
		<tr bgcolor="#A6D0F6" align="center">
			<td colspan="7"><?php if (!empty($page_control)){echo $page_control;}?></td>
		</tr>
    </table>
</td>
  </tr>
</table>
</form>
<?php include template('foot'); ?>