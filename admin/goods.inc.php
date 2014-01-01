<?php
if(!defined('IN_SITE')) exit('Access Denied');
CheckAccess();
global $act,$todo,$tablepre,$db;
admin_priv($act['action']);
require_once 'include/f/goods.f.php';
switch ($todo) {
	case 'saveupdate'://保存修改信息
		$id	= intval( isset($_POST['id']) ? $_POST['id'] : 0 );
		if (empty($id))e("未获取商品ID");
		
		$name	= htmlspecialchars( isset($_POST['name']) ? $_POST['name'] : '' );
		$suk	= htmlspecialchars( isset($_POST['suk']) ? $_POST['suk'] : '' );
		$unit	= htmlspecialchars( isset($_POST['unit']) ? $_POST['unit'] : '' );
		$categories_id	= intval( isset($_POST['categories_id']) ? $_POST['categories_id'] : 0);
		$jinjia	= htmlspecialchars( isset($_POST['jinjia']) ? $_POST['jinjia'] : '' );
		$price	= abs(intval( isset($_POST['price']) ? $_POST['price'] : 0 ));
		$jiangli_jifen	= abs(intval( isset($_POST['jiangli_jifen']) ? $_POST['jiangli_jifen'] : 0 ));
		$diyong_jifen	= abs(intval( isset($_POST['diyong_jifen']) ? $_POST['diyong_jifen'] : 0 ));
		$inventory	= abs(intval( isset($_POST['inventory']) ? $_POST['inventory'] : 0 ));
		$remark	= htmlspecialchars( isset($_POST['remark']) ? $_POST['remark'] : '' );
		
		if (empty($name)){e('名称不能为空');};
		if (empty($unit)){e('单位不能为空');};
		if (empty($categories_id)){e('产品分类异常');};
		if (empty($price)){e('零售价不能为空');};
		if (empty($inventory)){e('库存不能为空');};
		
		
		if (goods_check_field("name", $name,"AND `id`!= ".$id))e("商品名称已经被占用!");
		if (!empty($suk)) {
			if (goods_check_field("suk", $suk,"AND `id`!= ".$id)){
				e("商品简码已经被占用!");
			};
		}
		
		$result = goods_update($id,array(
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
		s('修改成功','?action=goods_list&todo=list');
		
		break;
	case 'js_good_info':
		$ids = $_GET['ids'];
		$ids = substr($ids,0,strlen($ids)- 1);
		$ids = explode(",", $ids);
		$goodsInfo = goods_get($ids);
		$html = "";
		foreach ($goodsInfo as $value) {
			$html.="<tr bgcolor='#F1F3F5'>";
			$html.='<td class="list">'.$value['name'].'</td>';
			$html.='<td class="list">'.$value['suk'].'</td>';
			$html.='<td class="list" id="inventory_'.$value['id'].'">'.$value['inventory'].'</td>';
			$html.='<td class="list"><input name="shuliang[]" id="shuliang_'.$value['id'].'" value="1" onblur="buy_cart_up_price('.$value['id'].');"/><input type="hidden" name="ids[]" value="'.$value['id'].'"/>'.$value['unit'].'</td>';
			$html.='<td class="list">';
			$html.='<input disabled name="price[]" id="price_'.$value['id'].'" value="'.$value['price'].'"/>';
			$html.='<input type="hidden" id="old_price_'.$value['id'].'" value="'.$value['price'].'"/>';
			$html.='<input type="hidden" id="old_diyong_jifen_'.$value['id'].'" value="'.$value['diyong_jifen'].'"/>';
			$html.='</td>';
			$html.='<td class="list"><input disabled name="diyong_jifen[]" id="diyong_jifen_'.$value['id'].'" value="'.$value['diyong_jifen'].'"/></td>';
			$html.="</tr>";
		}
		//echo "<pre>";
		//print_r($goodsInfo);
		echo $html;
		ob_flush();
		exit();
		break;
	case 'update':
		$id	= intval( isset($_GET['id']) ? $_GET['id'] : 0 );
		if (empty($id))e("未获取商品ID");
		//获取要修改的商品信息
		$good_info = goods_get(array($id));
		if (empty($good_info))e("无商品信息");
		$good_info = $good_info[0];
		//获取商品分类
		$goodsClassInfoArr	= goods_class_list(0);
		include template('goods_update');
		break;
	case 'saveadd'://保存商品信息
		$name	= htmlspecialchars( isset($_POST['name']) ? $_POST['name'] : '' );
		$suk	= htmlspecialchars( isset($_POST['suk']) ? $_POST['suk'] : '' );
		$unit	= htmlspecialchars( isset($_POST['unit']) ? $_POST['unit'] : '' );
		$categories_id	= intval( isset($_POST['categories_id']) ? $_POST['categories_id'] : 0);
		$jinjia	= htmlspecialchars( isset($_POST['jinjia']) ? $_POST['jinjia'] : '' );
		$price	= abs(intval( isset($_POST['price']) ? $_POST['price'] : 0 ));
		$jiangli_jifen	= abs(intval( isset($_POST['jiangli_jifen']) ? $_POST['jiangli_jifen'] : 0 ));
		$diyong_jifen	= abs(intval( isset($_POST['diyong_jifen']) ? $_POST['diyong_jifen'] : 0 ));
		$inventory	= abs(intval( isset($_POST['inventory']) ? $_POST['inventory'] : 0 ));
		$remark	= htmlspecialchars( isset($_POST['remark']) ? $_POST['remark'] : '' );
		
		if (empty($name)){e('名称不能为空');};
		if (empty($unit)){e('单位不能为空');};
		if (empty($categories_id)){e('产品分类异常');};
		if (empty($price)){e('零售价不能为空');};
		if (empty($inventory)){e('库存不能为空');};
		
		if (goods_check_field("name", $name))e("商品名称已经被占用!");
		if (!empty($suk)) {
			if (goods_check_field("suk", $suk))e("商品简码已经被占用!");
		}
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