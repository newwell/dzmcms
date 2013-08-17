<?php
if(!defined('IN_SITE')) exit('Access Denied');
CheckAccess();
global $act,$todo,$tablepre,$db;
admin_priv($act['action']);
require_once 'include/f/member.f.php';
switch ($todo) {
	case 'list':
	;
	break;
	
	default:
		e('参数不正确');
	break;
}