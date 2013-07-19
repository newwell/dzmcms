<?php if(!defined('IN_SITE')) exit('Access Denied'); ?>
<?php include template('header'); ?>

<script src="<?php echo $_TEMPLATESDIR;?>js/wui.js" type="text/javascript"></script>

	<div class="wui_contentLeft">
	<?php if(is_array($cates)) { foreach($cates as $key => $cate) {
		if (!empty($cate['childs'])){?>
			<div class="wui_contentLeftTitle"><span></span><?php echo$cate['title'];?></div>
			<div class="wui_contentLeftItem">
			<ul>
			<?php foreach($cate['childs'] as $num => $child) {?>
				<li class="wui_contentLeftItemList"><a target="mainFrame" href="?action=<?php echo $child['action'];?>&todo=<?php echo $child['todo'];?>&do=<?php echo $child['do'];?>"><?php echo $child['title'];?></a></li>
			<?php }?>
			</ul>
		</div>
	<?php 	}?>
	<?php }}?>
	<span >&nbsp;<br/>&nbsp;<br/>&nbsp;<br/></span>
	</div>
	
</body></html>