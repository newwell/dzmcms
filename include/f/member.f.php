<?php
/**
 * ------------------------------------------------------------------
 * 会员管理集中函数库
 * ------------------------------------------------------------------
 * @author newwell
 * ------------------------------------------------------------------
 * @copyright	武汉大赞网络科技 
 * ------------------------------------------------------------------
 * @link	http://www.dazan.cn
 * ------------------------------------------------------------------
 */
/**
 * 获取短url列表
 * @param int		$startlimit	开始行
 * @param int		$perpage	结束行
 * @param array()	$where		查找的条件
 */
function member_list($startlimit,$endlimit,$where='') {
	global $db,$tablepre;
	$sql = "SELECT * FROM  `{$tablepre}member` ";
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
function member_total($where='') {
	global $db,$tablepre;
	$sql	= "SELECT COUNT(id) AS countnum FROM {$tablepre}member ";
	if (!empty($where)) {
		$sql .="WHERE ".$where;
	}
	$result	= $db->fetch_one_array($sql);
	return $result['countnum'];
}
/**
 * 检查指定字段的值是够存在
 * @param int		$field			字段
 * @param string	$value			值
 * @return Boole	true or false   返回几条
 */
function member_check_field($field,$value) {
	global $db,$tablepre;
	$sql = "SELECT COUNT(id) AS countnum FROM {$tablepre}member WHERE `$field` = '$value'";
	$result	= $db->fetch_one_array($sql);
	if ($result['countnum']>0) {
		return true;
	}
	return false;
}
/**
 * 添加会员信息
 * @param int		$id			ID
 * @param array()	$infoArr	修改的条件   array('url'=>"http://www.dazan.cn")
 */
function member_add($infoArr) {
	global $db,$tablepre;
	if (empty($infoArr)) return false;
	if (!is_array($infoArr))return false;
	$sql = "INSERT INTO  `{$tablepre}member` (";
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
 * 得到指定ID的信息
 * @param array		$idArr	id数组
 * @param string	$fields	要查询的字段
 */
function member_get($idArr=array(),$fields) {
	global $db,$tablepre;
	$ids = implode(',', $idArr);
	$sql = "SELECT * FROM `{$tablepre}member` WHERE `$fields` in($ids)";
	$result	= $db->fetch_one_array($sql);
	return $result;
}
