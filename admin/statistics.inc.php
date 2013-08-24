<?php
if(!defined('IN_SITE')) exit('Access Denied');
CheckAccess();
global $act,$todo,$tablepre,$db;
admin_priv($act['action']);
require_once 'include/f/balance.f.php';
switch ($todo) {
	case 'balance_change':
		$page   = intval( isset($_GET['page']) ? $_GET['page'] : 1 );
		$perpage = intval( isset($_GET['perpage']) ? $_GET['perpage'] : 20 );
		if($page > 0){
			$startlimit = ($page - 1) * $perpage;
		}else{
			$startlimit = 0;
		}
		$page_array = array();
		$total		= balance_log_total();
		$page_control = multipage($total,$perpage,$page);
		$infoList	= balance_log_list($startlimit, $perpage);	
		include template('statistics_balance_change_list');
	break;
	
	default:
		e('参数不正确');
	break;
}