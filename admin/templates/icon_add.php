<?php if(!defined('IN_SITE')) exit('Access Denied'); ?>
<?php include template('header'); ?>
<script src="script/jquery.customselect-0.2.js" type="text/javascript"></script>
<div class="formnav"><?php echo $act['title'];?>>添加</div>
<form action="?action=icon_list&todo=saveadd" method="post" onsubmit="return CheckForm(this,true);">
<input type="hidden" value="<?php echo $formhash;?>" name="formhash">
<input type="hidden" value="<?php echo $fid;?>" name="fid">
 <style type="text/css">
.iconselect {
	background-color:#FFF;
	height: 25px;
	width: 232px;
	padding-left: 15px;
	padding-top: 4px;
}
.iconselect img{
	height: 16px;
}
.selectitems {
	background-color: #F3F3F3;
width: 230px;
height: 25px;
border-bottom: dashed 1px #fff;
padding-left: 16px;
padding-top: 2px;
}
.selectitems span {
	margin-left: 5px;
}
.selectitems img {
	height: 16px;
}
.iconselectholder {
	width: 250px;
	overflow: auto;
	display:none;
	position:absolute;
}
.hoverclass{curson:hand;background-color: #E7E7E7;}
 </style>

<script type="text/javascript">
 $(document).ready(function() {
 $('#select_icon').customSelect();
 });</script>

<table align="center" class="formtable" cellpadding="0" cellspacing="1" width="97%">
	<tr>
	    <td align="right">选择图标:</td>
	    <td>
		    <select name="select_icon" class="customselect" title="选择图标" id="select_icon">
			  <?php for ($i = 1; $i < 20; $i++) {?>
			  	<option value="<?php echo $i;?>" id="<?php echo $i;?>" title="script/icons/<?php echo $i;?>.png"></option>
			  <?php }?>
			</select>
		</td>
	</tr>
	<tr>
		<th width="100%" colspan="2" align="center">选择对应操作</th>
	</tr>
	<?php if (is_array($cates)){foreach ($cates as $cates_value) {?>
	<tr>
		<td align="right"><?php echo $cates_value['title'];?></td>
		<td><?php if (is_array($cates_value['childs'])) {foreach ($cates_value['childs'] as $value) {?>
			<input name="action_id" type="radio" value="<?php echo $value['id'];?>"><?php echo $value['title'];?>
		<?php }}?></td>
	</tr>
	<?php }}?>


	<tr>
		<td colspan="4" align="center">	<input type="submit" class="formsubmit" value="提交" ></td>
	</tr>
</table>
</form>
<?php include template('foot'); ?>