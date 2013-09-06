<?php
if(!defined('IN_SITE')) exit('Access Denied');
CheckAccess();
global $act,$todo,$tablepre,$db;
admin_priv($act['action']);
require_once 'include/f/staff.f.php';
switch ($todo) {
	case 'deingcoholr_activate':
		$id		= intval( isset($_GET['id']) ? $_GET['id'] : 0 );
		$activate		= intval( isset($_GET['activate']) ? $_GET['activate'] : 1 );
		staff_update($id, array(
			'activate'=>$activate
		));
		s('操作成功','?action=staff_deingcoholr_list&todo=deingcoholr_list');
		break;
	case 'deingcoholr_del':
		$id		= intval( isset($_GET['id']) ? $_GET['id'] : 0 );
		staff_del(array($id));
		s('删除成功','?action=staff_deingcoholr_list&todo=deingcoholr_list');
		break;
	case 'deingcoholr_add'://发牌员添加
		$name	= trim( isset($_POST['name']) ? $_POST['name'] : '' );
		$activate= intval( isset($_POST['activate']) ? $_POST['activate'] : 1 );
		if (empty($name))e('姓名不能为空');
		staff_add(array(
			'name'=>$name,
			'activate'=>$activate,
			'type'=>'发牌员'
		));
		s('添加成功','?action=staff_deingcoholr_list&todo=deingcoholr_list');
		//include template('staff_deingcoholr_add');
		break;
	case 'deingcoholr_list'://发牌员列表
		$page   = intval( isset($_GET['page']) ? $_GET['page'] : 1 );
		$perpage = intval( isset($_GET['perpage']) ? $_GET['perpage'] : 20 );
		if($page > 0){
			$startlimit = ($page - 1) * $perpage;
		}else{
			$startlimit = 0;
		}
		$page_array = array();
		$where = "`type`='发牌员'";
		$total		= staff_total($where);
		$page_control = multipage($total,$perpage,$page);
		$infoList	= staff_list($startlimit, $perpage, $where);
		include template('staff_deingcoholr_list');
	break;
	
	default:
		e('参数不正确');
	break;
}