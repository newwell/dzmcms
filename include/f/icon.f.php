<?php
/**
 * ------------------------------------------------------------------
 * 自定义快捷图标集中函数库
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
function icon_list($startlimit,$endlimit,$where='') {
	global $db,$tablepre;
	$sql = "SELECT * FROM  `{$tablepre}icon` ";
	if (!empty($where)) {
		$sql .="WHERE ".$where;
	}
	$sql .= " ORDER BY listnum ASC LIMIT $startlimit , $endlimit";
	$result		= $db->query($sql);
	$resultArr	= array();
	while($arr	= $db->fetch_array($result)){
		$arr['action'] = icon_action_get($arr['action_id']);
        $resultArr[]	= $arr;
	}
	//print_r($resultArr);exit;
	return $resultArr;
}
/**
 * 该条件下的总数
 */
function icon_total($where='') {
	global $db,$tablepre;
	$sql	= "SELECT COUNT(id) AS countnum FROM {$tablepre}icon ";
	if (!empty($where)) {
		$sql .="WHERE ".$where;
	}
	$result	= $db->fetch_one_array($sql);
	return $result['countnum'];
}
/**
 * 添加信息
 * @param int		$id			ID
 * @param array()	$infoArr	修改的条件   array('url'=>"http://www.dazan.cn")
 */
function icon_add($infoArr) {
	global $db,$tablepre;
	if (empty($infoArr)) return false;
	if (!is_array($infoArr))return false;
	$sql = "INSERT INTO  `{$tablepre}icon` (";
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
function icon_action_get($id) {
	global $db,$tablepre;
	$result = $db->fetch_one_array("SELECT * FROM {$tablepre}systemaction WHERE id = " .$id );
	return $result;
}
/**
 * 删除指定id的信息
 * @param array $idArr	id数组
 */
function icon_del($idArr=array()) {
	global $db,$tablepre;
	$ids = implode(',', $idArr);
	$sql = "DELETE FROM `{$tablepre}icon` WHERE `id`in($ids)";
	$result	= $db->query($sql);
	return $result;
}
/**
 * 更新指定信息
 * @param int		$id		读卡
 * @param array()	$infoArr	修改的条件   array('url'=>"http://www.dazan.cn")
 */
function icon_update($id,$infoArr) {
	global $db,$tablepre;
	if (empty($infoArr)) return false;
	$sql = "UPDATE  `{$tablepre}icon` SET ";
	foreach ($infoArr as $key => $value) {
		$sql.= "`$key` =  '$value',";
	}
	//去掉最后一个多余的,
	$sql = substr($sql,0,strlen($sql)- 1);
	$sql.=" WHERE `id` =$id;";
	return $db->query($sql);
}