<?php
if(!defined('IN_SITE')) exit('Access Denied');
CheckAccess();
global $act,$todo,$tablepre,$db,$do;
admin_priv($act['action']);
require_once 'include/f/icon.f.php';
switch ($todo) {
	case 'serialnumber':
		$listnum =  isset($_POST['listnum']) ? $_POST['listnum'] : '';
		if (empty($listnum))e("无法获取序号");
		if (is_array($listnum)){
			foreach ($listnum as $key => $value) {
				icon_update($key, array(
					"listnum"=>$value
				));
			}
		}
		s("排序成功","?action=icon_list&todo=list");
	case 'del':
		$id	=  isset($_POST['id']) ? $_POST['id'] : '';
		if (empty($id))e("至少选择一个");
		icon_del($id);
		s("删除成功","?action=icon_list&todo=list");
		break;
	case 'saveadd':
		$select_icon	= intval( isset($_POST['select_icon']) ? $_POST['select_icon'] : '' );
		$action_id		= intval( isset($_POST['action_id']) ? $_POST['action_id'] : '' );
		
		if (empty($action_id)){e("请选择一个对应的操作项");}
		/*$result = $db->query("SELECT * FROM {$tablepre}systemaction WHERE id = " .$action_id );
		while($child = $db->fetch_array($result)){
			$actionInfo[] = $child;
		}
		print_r($actionInfo);exit;*/
		//$link = "?action=member_find&todo=find&do=";
		$result = icon_add(array(
			"iconid"=>$select_icon,
			"userid"=>$_SESSION['uid'],
			"action_id"=>$action_id
		));
		if ($result){
			s("添加成功","?action=icon_list&todo=list");
		}s("添加失败","?action=icon_list&todo=list");
		
		break;
	case 'add':
		//操作模块列表
		$cate_result = $db->query("SELECT * FROM {$tablepre}systemaction WHERE fid = 0");
		$cates = array();
		while($cate = $db->fetch_array($cate_result)){
			$cate['childs'] = array();
			$child_result = $db->query("SELECT * FROM {$tablepre}systemaction WHERE fid = " .$cate['id'] );
			while($child = $db->fetch_array($child_result)){
				$cate['childs'][] = $child;
			}
			$cates[] = $cate;
		}
		include template('icon_add');
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
		//只能获取自己的图标信息
		$where = "userid = ".$_SESSION["uid"];
		
		$total		= icon_total($where);
		$page_control = multipage($total,$perpage,$page);
		$listArr	= icon_list($startlimit, $perpage,$where);
/*		$sql = "SELECT * FROM  `{$tablepre}icon` ";
		if (!empty($where)) {
			$sql .="WHERE ".$where;
		}
		$sql .= " ORDER BY listnum ASC LIMIT $startlimit , $perpage";
		$result		= $db->query($sql);
		$listArr	= array();
		while($arr	= $db->fetch_array($result)){
			
	        $listArr[]	= $arr;
		}*/
		include template('icon_list');
		break;
	
	default:
		e('参数不正确');
	break;
}