<?php if(!defined('IN_SITE')) exit('Access Denied'); ?>
<?php include template('header'); ?>
<div class="formnav"><?php echo $act['title'];?></div>
<form action="?action=goods_add&todo=saveadd" method="post" onsubmit="return CheckForm(this,true);">
<input type="hidden" value="<?php echo $formhash;?>" name="formhash">
<input type="hidden" value="<?php echo $fid;?>" name="fid">
<table align="center" class="formtable" cellpadding="0" cellspacing="1" width="97%">
	<tr>
	    <td align="right">名称:</td>
	    <td><input name="name" required="true"/>*不能为空</td>
	    <td align="right">商品简码:</td>
	    <td><input name="suk"/></td>
	</tr>
	<tr>
	    <td align="right">单位:</td>
	    <td><input name="unit" required="true"/>*不能为空</td>
	    <td align="right">所属分类:</td>
	    <td>
	    	<select name="categories_id">
	    	<?php if (is_array($goodsClassInfoArr)){foreach ($goodsClassInfoArr as $value) {?>
	    		<optgroup label="<?php echo $value['name'];?>">
	    		<?php if (is_array($value['childmodules'])){foreach ($value['childmodules'] as $childmodules_value) {?>
	    			<option value="<?php echo $childmodules_value['id'];?>"><?php echo $childmodules_value['name'];?></option>
	    		<?php }}?>
	    		</optgroup>
	    	<?php }}?>
	    	</select>
	    </td>
	</tr>
	<tr><!--
	    <td align="right">进价:</td>
	    <td><input name="jinjia"/></td>
	    --><td align="right">库存:</td>
	    <td ><input name="inventory" required="true"//>*不能为空</td>
	    <td align="right">零售价:</td>
	    <td><input name="price" required="true"/>*不能为空</td>
	</tr>
	<tr>
	    <td align="right">奖励积分:</td>
	    <td><input name="jiangli_jifen"/></td>
	    <td align="right">可抵用奖励积分:</td>
	    <td><input name="diyong_jifen"/></td>
	</tr>
	<tr >
	    <td width="80px" align="right">备注:</td>
	    <td colspan="3">
	        <textarea rows="5" name="remark" style="width:100%;border:#336699 1px solid;"></textarea>
	   		*可以为空</td>
	</tr>
	<tr>
		<td colspan="4" align="center">	<input type="submit" class="formsubmit" value="提交" ></td>
	</tr>
</table>
</form>
<?php include template('foot'); ?>