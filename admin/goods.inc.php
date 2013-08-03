<?php
if(!defined('IN_SITE')) exit('Access Denied');
CheckAccess();
global $act,$todo,$tablepre,$db;
admin_priv($act['action']);
require_once 'include/f/goods.f.php';
switch ($todo) {
	case 'class':
		echo 'class';
		$page   = intval( isset($_GET['page']) ? $_GET['page'] : 1 );
		$perpage = intval( isset($_GET['perpage']) ? $_GET['perpage'] : 20 );
		if($page > 0){
			$startlimit = ($page - 1) * $perpage;
		}else{
			$startlimit = 0;
		}
		$page_array = array();
		$total		= goods_class_total();
		$page_control = multipage($total,$perpage,$page);
		$infoArr	= goods_class_list($startlimit, $perpage);	
		include template('goods_class_list');
		break;
	case 'add':
		include template('goods_add');
		break;
	case 'search':
		$d_option	= htmlspecialchars( isset($_REQUEST['d_option']) ? $_REQUEST['d_option'] : '' );
		$keywork	= htmlspecialchars( isset($_REQUEST['keywork']) ? $_REQUEST['keywork'] : '' );

		$page   = intval( isset($_GET['page']) ? $_GET['page'] : 1 );
		$perpage = intval( isset($_GET['perpage']) ? $_GET['perpage'] : 20 );
		
		if($page > 0){
			$startlimit = ($page - 1) * $perpage;
		}else{
			$startlimit = 0;
		}
		$where = "";
		if (!empty($keywork)){
			switch ($d_option) {
				case 'name':
					$where .= "name LIKE '%$keywork%' ";
				break;
				case 'suk':
					$where .= "suk LIKE '%$keywork%' ";
				break;
				default:
					e('错误的参数');
				break;
			}
		}
		$total		= goods_total($where);
		$goodslArr =goods_list($startlimit, $perpage,$where);
		$page_control = multipage($total,$perpage,$page);
		include template('goods_list');
		break;
	case 'del':
		$ids   = isset($_POST['ids']) ? $_POST['ids'] : '';
		if (empty($_POST['ids'])) {
			e('请自少选择一项');
		}
		if (goods_del($ids)) {
			s('删除成功','?action=goods_list&todo=list');
		}
		break;
	case 'list':
		$page   = intval( isset($_GET['page']) ? $_GET['page'] : 1 );
		$perpage = intval( isset($_GET['perpage']) ? $_GET['perpage'] : 20 );
		if($page > 0){
			$startlimit = ($page - 1) * $perpage;
		}else{
			$startlimit = 0;
		}
		$page_array = array();
		$total		= goods_total();
		$page_control = multipage($total,$perpage,$page);
		$goodslArr	= goods_list($startlimit, $perpage);	
		include template('goods_list');
	break;
	
	default:
		e('参数不正确');
	break;
}