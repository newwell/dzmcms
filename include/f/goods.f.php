<?php
/**
 * ------------------------------------------------------------------
 * 商品管理集中函数库
 * ------------------------------------------------------------------
 * @author newwell
 * ------------------------------------------------------------------
 * @copyright	武汉大赞网络科技 
 * ------------------------------------------------------------------
 * @link	http://www.dazan.cn
 * ------------------------------------------------------------------
 */
/**
 * 获取列表
 * @param int		$startlimit	开始行
 * @param int		$perpage	结束行
 * @param array()	$where		查找的条件
 */
function goods_list($startlimit,$endlimit,$where='') {
	global $db,$tablepre;
	$sql = "SELECT * FROM  `{$tablepre}goods` ";
	if (!empty($where)) {
		$sql .="WHERE ".$where;
	}
	$sql .= "ORDER BY add_date DESC LIMIT $startlimit , $endlimit";
	$result		= $db->query($sql);
	$resultArr	= array();
	while($arr	= $db->fetch_array($result)){
		$arr['add_date']= gmdate('Y.n.j',$arr['add_date']);
        $resultArr[]	= $arr;
	}
	return $resultArr;
}
/**
 * 该条件下的会员的总数
 */
function goods_total($where='') {
	global $db,$tablepre;
	$sql	= "SELECT COUNT(id) AS countnum FROM {$tablepre}goods ";
	if (!empty($where)) {
		$sql .="WHERE ".$where;
	}
	$result	= $db->fetch_one_array($sql);
	return $result['countnum'];
}
/**
 * 删除指定id的信息
 * @param array $idArr	id数组
 */
function goods_del($idArr=array()) {
	global $db,$tablepre;
	$ids = implode(',', $idArr);
	$sql = "DELETE FROM `{$tablepre}goods` WHERE `id`in($ids)";
	$result	= $db->query($sql);
	return $result;
}
/**
 * 该条件下的商品分类的总数
 */
function goods_class_total($where='') {
	global $db,$tablepre;
	$sql	= "SELECT COUNT(id) AS countnum FROM {$tablepre}goods_class ";
	if (!empty($where)) {
		$sql .="WHERE ".$where;
	}
	$result	= $db->fetch_one_array($sql);
	return $result['countnum'];
}
/**
 * 递归获取商品分类列表
 * @param int		$fid	父ID
 */
function goods_class_list($fid) {
	global $db,$tablepre;
	$sql = "SELECT * FROM  `{$tablepre}goods_class` ";
	$module_result = $db->query("SELECT * FROM {$tablepre}goods_class WHERE fid = $fid");
	$module_arr    = array();
	while($modules = $db->fetch_array($module_result))
	{
		//检查下级是否含有分类
		if(check_goods_class_list_ChildMoudule($modules['id']))
        	$modules['childmodules'] =  goods_class_list($modules['id']);
        else
        	$modules['childmodules'] = '';
        $module_arr[] = $modules;
	}
	return $module_arr;
}
/**
 * 通过id得到分类的信息
 * @param int $id
 */
function goods_class_info($id) {
	global $db,$tablepre;
	$sql = "SELECT * FROM  `{$tablepre}goods_class` WHERE id = ".$id;
	$result	= $db->fetch_one_array($sql);
	return $result;
}
/**
 * 检查模块是否含有子模块函数
 *
 * @access  public
 *
 * @param   int $fid 要检查下属模块情况的模块的id
 *
 * @return  bool
 */
function check_goods_class_list_ChildMoudule($fid)
{
	global $db,$tablepre;
	$child = $db->fetch_one_array("SELECT COUNT(id) as allnum FROM {$tablepre}goods_class WHERE fid = $fid");
	if($child['allnum']==0)
		return false;
	else
		return true;
}
/**
 * 添加商品分类
 * @param int		$id			ID
 * @param array()	$infoArr	条件   array('url'=>"http://www.dazan.cn")
 */
function goods_class_add($infoArr) {
	global $db,$tablepre;
	if (empty($infoArr)) return false;
	if (!is_array($infoArr))return false;
	$sql = "INSERT INTO  `{$tablepre}goods_class` (";
	foreach ($infoArr as $key => $value) {
		$sql.="`$key` ,";
	}
	$sql = substr($sql,0,strlen($sql)- 1);
	$sql.=")VALUES (";
	foreach ($infoArr as $key => $value) {
		$sql.= " '$value',";
	}
	//去掉最后一个多余的,
	$sql = substr($sql,0,strlen($sql)- 1);
	$sql.=");";
	return $db->query($sql);
}
/**
 * 删除指定id的产品分类
 * @param array $idArr	id数组
 */
function goods_class_del($idArr=array()) {
	global $db,$tablepre;
	$ids = implode(',', $idArr);
	$sql = "DELETE FROM `{$tablepre}goods_class` WHERE `id`in($ids)";
	$result	= $db->query($sql);
	return $result;
}
/**
 * 添加商品
 * @param array()	$infoArr	条件   array('url'=>"http://www.dazan.cn")
 */
function goods_add($infoArr) {
	global $db,$tablepre;
	if (empty($infoArr)) return false;
	if (!is_array($infoArr))return false;
	$sql = "INSERT INTO  `{$tablepre}goods` (";
	foreach ($infoArr as $key => $value) {
		$sql.="`$key` ,";
	}
	$sql = substr($sql,0,strlen($sql)- 1);
	$sql.=")VALUES (";
	foreach ($infoArr as $key => $value) {
		$sql.= " '$value',";
	}
	//去掉最后一个多余的,
	$sql = substr($sql,0,strlen($sql)- 1);
	$sql.=");";
	return $db->query($sql);
}
/**
 * 得到商品信息
 * @param array()	$infoArr	条件   array('url'=>"http://www.dazan.cn")
 */
function goods_get($idArr=array()) {
	global $db,$tablepre;
	$ids = implode(',', $idArr);
	$sql = "SELECT * FROM `{$tablepre}goods` WHERE `id`in($ids)";
	$result		= $db->query($sql);
	$resultArr	= array();
	while($arr	= $db->fetch_array($result)){
		$arr['add_date']= gmdate('Y.n.j',$arr['add_date']);
        $resultArr[]	= $arr;
	}
	return $resultArr;
}
/**
 * 修改指定ID的信息
 * @param int		$id			ID
 * @param array()	$infoArr	修改的条件   array('url'=>"http://www.dazan.cn")
 */
function goods_update($id,$infoArr) {
	global $db,$tablepre;
	if (empty($infoArr)) return false;
	$sql = "UPDATE  `{$tablepre}goods` SET ";
	foreach ($infoArr as $key => $value) {
		$sql.= "`$key` =  '$value',";
	}
	//去掉最后一个多余的,
	$sql = substr($sql,0,strlen($sql)- 1);
	$sql.=" WHERE `id` =$id;";
	return $db->query($sql);
}
/**
 * 检查指定字段的值是够存在
 * @param int		$field			字段
 * @param string	$value			值
 * @return Boole	true or false   返回几条
 */
function goods_check_field($field,$value,$where) {
	global $db,$tablepre;
	$sql = "SELECT COUNT(id) AS countnum FROM {$tablepre}goods WHERE `$field` = '$value' $where";
	$result	= $db->fetch_one_array($sql);
	if (intval($result['countnum'])>0) {
		return true;
	}
	return false;
}