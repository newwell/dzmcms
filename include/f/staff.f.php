<?php
/**
 * ------------------------------------------------------------------
 * 员工管理集中函数库
 * ------------------------------------------------------------------
 * @author newwell
 * ------------------------------------------------------------------
 * @copyright	武汉大赞网络科技 
 * ------------------------------------------------------------------
 * @link	http://www.dazan.cn
 * ------------------------------------------------------------------
 */
/**
 * 添加会员信息
 * @param int		$id			ID
 * @param array()	$infoArr	修改的条件   array('url'=>"http://www.dazan.cn")
 */
function staff_add($infoArr) {
	global $db,$tablepre;
	if (empty($infoArr)) return false;
	if (!is_array($infoArr))return false;
	$sql = "INSERT INTO  `{$tablepre}staff` (";
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
 * 获取列表
 * @param int		$startlimit	开始行
 * @param int		$perpage	结束行
 * @param array()	$where		查找的条件
 */
function staff_list($startlimit,$endlimit,$where='') {
	global $db,$tablepre;
	$sql = "SELECT * FROM  `{$tablepre}staff` ";
	if (!empty($where)) {
		$sql .="WHERE ".$where;
	}
	$sql .= "ORDER BY id DESC LIMIT $startlimit , $endlimit";
	$result		= $db->query($sql);
	$resultArr	= array();
	while($arr	= $db->fetch_array($result)){
        $resultArr[]	= $arr;
	}
	return $resultArr;
}
/**
 * 该条件下的总数
 */
function staff_total($where='') {
	global $db,$tablepre;
	$sql	= "SELECT COUNT(id) AS countnum FROM {$tablepre}staff ";
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
function staff_del($idArr=array()) {
	global $db,$tablepre;
	$ids = implode(',', $idArr);
	$sql = "DELETE FROM `{$tablepre}staff` WHERE `id`in($ids)";
	$result	= $db->query($sql);
	return $result;
}
/**
 * 更新指定信息
 * @param int		$id		读卡
 * @param array()	$infoArr	修改的条件   array('url'=>"http://www.dazan.cn")
 */
function staff_update($id,$infoArr) {
	global $db,$tablepre;
	if (empty($infoArr)) return false;
	$sql = "UPDATE  `{$tablepre}staff` SET ";
	foreach ($infoArr as $key => $value) {
		$sql.= "`$key` =  '$value',";
	}
	//去掉最后一个多余的,
	$sql = substr($sql,0,strlen($sql)- 1);
	$sql.=" WHERE `id` =$id;";
	return $db->query($sql);
}