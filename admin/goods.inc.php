<?php
if(!defined('IN_SITE')) exit('Access Denied');
CheckAccess();
global $act,$todo,$tablepre,$db;
admin_priv($act['action']);
require_once 'include/f/goods.f.php';
switch ($todo) {
	case 'saveadd'://保存商品信息
		$name	= htmlspecialchars( isset($_POST['name']) ? $_POST['name'] : '' );
		$suk	= htmlspecialchars( isset($_POST['suk']) ? $_POST['suk'] : '' );
		$unit	= htmlspecialchars( isset($_POST['unit']) ? $_POST['unit'] : '' );
		$categories_id	= intval( isset($_POST['categories_id']) ? $_POST['categories_id'] : 0);
		$jinjia	= htmlspecialchars( isset($_POST['jinjia']) ? $_POST['jinjia'] : '' );
		$price	= intval( isset($_POST['price']) ? $_POST['price'] : 0 );
		$jiangli_jifen	= intval( isset($_POST['jiangli_jifen']) ? $_POST['jiangli_jifen'] : 0 );
		$diyong_jifen	= intval( isset($_POST['diyong_jifen']) ? $_POST['diyong_jifen'] : 0 );
		$inventory	= intval( isset($_POST['inventory']) ? $_POST['inventory'] : 0 );
		$remark	= htmlspecialchars( isset($_POST['remark']) ? $_POST['remark'] : '' );
		
		if (empty($name)){e('名称不能为空');};
		if (empty($unit)){e('单位不能为空');};
		if (empty($categories_id)){e('产品分类异常');};
		if (empty($price)){e('零售及不能为空');};
		if (empty($inventory)){e('库存不能为空');};
		
		$result = goods_add(array(
			"name"=>$name,
			"suk"=>$suk,
			"unit"=>$unit,
			"categories_id"=>$categories_id,
			"jinjia"=>$jinjia,
			"price"=>$price,
			"jiangli_jifen"=>$jiangli_jifen,
			"diyong_jifen"=>$diyong_jifen,
			"inventory"=>$inventory,
			"remark"=>$remark,
		
			"add_date"=>$localtime
		));
		s('添加成功','?action=goods_list&todo=list');
		break;
	case 'class_del':
		$id = isset($_GET['id']) ? $_GET['id'] : '';
		if (empty($id)){
			e('得不到ID');
		}
		if (check_goods_class_list_ChildMoudule($id)){
			e('有子分类不能删除');
		}
		goods_class_del(array($id));
		s('删除成功','?action=goods_class&todo=class');
		break;
	case 'save_clasadd':
		$fid  = isset($_POST['fid']) ? $_POST['fid'] : 0;
		$name	= htmlspecialchars( isset($_POST['name']) ? $_POST['name'] : '' );
		$remark	= htmlspecialchars( isset($_POST['remark']) ? $_POST['remark'] : '' );
		
		if (empty($name)){
			e('名称不能为空');
		}
		$result = goods_class_add(array(
			'fid'=>$fid,
			'name'=>$name,
			'remark'=>$remark,
		));
		s('添加成功','?action=goods_class&todo=class');
		break;
	case 'clasadd':
		$fid  = isset($_GET['fid']) ? $_GET['fid'] : 0;
		if (empty($fid)){
			$info['name'] = "一级分类";
		}else{
			$info = goods_class_info($fid);
		}
		include template('goods_class_add');
		break;
	case 'class':
		$infoArr	= goods_class_list(0);
		include template('goods_class_list');
		break;
	case 'add':
		$goodsClassInfoArr	= goods_class_list(0);
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