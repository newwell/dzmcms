<?php
include 'include/common.inc.php';
require_once 'include/func/shorturl.func.php';
//网站关闭功能
if($setting_sitestatus == 0){
	stop($setting_siteclosereason);
}

$code	= isset($_GET['c']) ? $_GET['c'] : '';
$result = shorturl_restore($code);

if ($result){
	shorturl_times_statistic($result['id']);
	switch ($setting_sitejump) {
		case 'js':
			$t = '<script type="text/javascript">location.href="';
			$t .=$result['url'];
			$t .='";</script>';
			exit($t);
			break;
		case 'http301':
			exit('<html><meta http-equiv="refresh" content="0;url='.$result['url'].'"></html>');
			break;
		case 'php301':
			send_http_status(301);
			header("location: ".$result['url']);
			break;
		case 'php':
		default:
			header("location: ".$result['url']);
		break;
	}
}else{
	send_http_status(404);
	echo '404';
}
