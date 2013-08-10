<?php
if(!defined('IN_SITE')) exit('Access Denied');
CheckAccess();
global $act,$todo,$tablepre,$db;
admin_priv($act['action']);
require_once 'include/f/buy.f.php';
switch ($todo) {
	case 'cash'://非商品购买
		include template('buy_cash');
		break;
	case 'list':
		stop('这功能稍后写');
	break;
	
	default:
		e('参数不正确');
	break;
}