<?php if(!defined('IN_SITE')) exit('Access Denied'); ?>
<?php include template('header'); ?>
<div class="formnav"><?php echo $act['title'];?></div>
<form  method="post" id="data" action="?action=shorturl_list">
<input type="hidden" value="<?php echo $formhash;?>" name="formhash">

</form>
<?php include template('foot'); ?>