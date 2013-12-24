<?php
/**
 * ------------------------------------------------------------------
 * 赛事管理集中函数库
 * ------------------------------------------------------------------
 * @author newwell
 * ------------------------------------------------------------------
 * @copyright	武汉大赞网络科技 
 * ------------------------------------------------------------------
 * @link	http://www.dazan.cn
 * ------------------------------------------------------------------
 */
/**
 * 该条件下的赛事的总数
 */
function sport_total($where='') {
	global $db,$tablepre;
	$sql	= "SELECT COUNT(id) AS countnum FROM {$tablepre}sport ";
	if (!empty($where)) {
		$sql .="WHERE ".$where;
	}
	$result	= $db->fetch_one_array($sql);
	return $result['countnum'];
}
/**
 * 获取列表
 * @param int		$startlimit	开始行
 * @param int		$perpage	结束行
 * @param array()	$where		查找的条件
 */
function sport_list($startlimit,$endlimit,$where='') {
	global $db,$tablepre;
	$sql = "SELECT * FROM  `{$tablepre}sport` ";
	if (!empty($where)) {
		$sql .="WHERE ".$where." ";
	}
	$sql .= "ORDER BY add_date DESC LIMIT $startlimit , $endlimit";
	$result		= $db->query($sql);
	$resultArr	= array();
	while($arr	= $db->fetch_array($result)){
		//$arr['add_date']= gmdate('Y.n.j',$arr['add_date']);
        $resultArr[]	= $arr;
	}
	return $resultArr;
}
/**
 * 添加赛事
 * @param int		$id			ID
 * @param array()	$infoArr	条件   array('url'=>"http://www.dazan.cn")
 */
function sport_add($infoArr) {
	global $db,$tablepre;
	if (empty($infoArr)) return false;
	if (!is_array($infoArr))return false;
	$sql = "INSERT INTO  `{$tablepre}sport` (";
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
 * 删除指定id的信息
 * @param array $idArr	id数组
 */
function sport_del($idArr=array()) {
	global $db,$tablepre;
	$ids = implode(',', $idArr);
	$sql = "DELETE FROM `{$tablepre}sport` WHERE `id`in($ids)";
	$result	= $db->query($sql);
	return $result;
}
/**
 * 更新指定ID的信息
 * @param int		$id			ID
 * @param array()	$infoArr	修改的条件   array('url'=>"http://www.dazan.cn")
 */
function sport_update($id,$infoArr) {
	global $db,$tablepre;
	if (empty($infoArr)) return false;
	$sql = "UPDATE  `{$tablepre}sport` SET ";
	foreach ($infoArr as $key => $value) {
		$sql.= "`$key` =  '$value',";
	}
	//去掉最后一个多余的,
	$sql = substr($sql,0,strlen($sql)- 1);
	$sql.=" WHERE `id` =$id;";
	return $db->query($sql);
}
/**
 * 得到指定ID的信息
 * @param array		$idArr	id数组
 * @param string	$fields	要查询的字段
 */
function sport_get($idArr=array(),$fields) {
	global $db,$tablepre;
	$ids = implode(',', $idArr);
	$sql = "SELECT * FROM `{$tablepre}sport` WHERE `$fields` in($ids)";
	$result	= $db->fetch_one_array($sql);
	return $result;
}
/**
 * 添加赛事
 * @param int		$id			ID
 * @param array()	$infoArr	条件   array('url'=>"http://www.dazan.cn")
 */
function entry_add($infoArr) {
	global $db,$tablepre;
	if (empty($infoArr)) return false;
	if (!is_array($infoArr))return false;
	$sql = "INSERT INTO  `{$tablepre}entry` (";
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
function entry_list($startlimit,$endlimit,$where='') {
	global $db,$tablepre;
	$sql = "SELECT * FROM  `{$tablepre}entry` ";
	if (!empty($where)) {
		$sql .="WHERE ".$where;
	}
	$sql .= "ORDER BY add_date DESC LIMIT $startlimit , $endlimit";
	$result		= $db->query($sql);
	$resultArr	= array();
	while($arr	= $db->fetch_array($result)){
		//$arr['add_date']= gmdate('Y.n.j',$arr['add_date']);
        $resultArr[]	= $arr;
	}
	return $resultArr;
}
/**
 * 该条件下的参赛的总数
 */
function entry_total($where='') {
	global $db,$tablepre;
	$sql	= "SELECT COUNT(id) AS countnum FROM {$tablepre}entry ";
	if (!empty($where)) {
		$sql .="WHERE ".$where;
	}
	$result	= $db->fetch_one_array($sql);
	return $result['countnum'];
}
/**
 * 奖池减少
 * @param int		$id		赛事编号
 * @param string	$value	值
 */
function jackpot_reduce($id,$value) {
	global $db,$tablepre;
	$sql = "UPDATE  `{$tablepre}sport` SET  `jackpot` =  `jackpot`-$value WHERE  id =$id;";
	$result	= $db->fetch_one_array($sql);
	return $result;
}
/**
 * 奖池增加
 * @param int		$id		赛事编号
 * @param string	$value	值
 */
function jackpot_add($id,$value) {
	global $db,$tablepre;
	//$sql = "UPDATE  `{$tablepre}sport` SET  `jackpot` =  `jackpot`+$value WHERE  id =$id;";
	$sql = "UPDATE  `{$tablepre}sport` SET  `jackpot` =`jackpot`+$value WHERE id =$id";
	//exit($sql);
	$result	= $db->fetch_one_array($sql);
	return $result;
}
/**
 * 得到指定ID的信息
 * @param array		$idArr	id数组
 * @param string	$fields	要查询的字段
 */
function entry_get($idArr=array(),$fields) {
	global $db,$tablepre;
	$ids = implode(',', $idArr);
	$sql = "SELECT * FROM `{$tablepre}entry` WHERE `$fields` in($ids)";
	$result	= $db->fetch_one_array($sql);
	return $result;
}
/**
 * 更新指定ID的信息
 * @param int		$id			ID
 * @param array()	$infoArr	修改的条件   array('url'=>"http://www.dazan.cn")
 */
function entry_update($id,$infoArr) {
	global $db,$tablepre;
	if (empty($infoArr)) return false;
	$sql = "UPDATE  `{$tablepre}entry` SET ";
	foreach ($infoArr as $key => $value) {
		$sql.= "`$key` =  '$value',";
	}
	//去掉最后一个多余的,
	$sql = substr($sql,0,strlen($sql)- 1);
	$sql.=" WHERE `id` =$id;";
	return $db->query($sql);
}
/**
 * 添加颁奖记录
 * @param int		$id			ID
 * @param array()	$infoArr	条件   array('url'=>"http://www.dazan.cn")
 */
function prize_add($infoArr) {
	global $db,$tablepre;
	if (empty($infoArr)) return false;
	if (!is_array($infoArr))return false;
	$sql = "INSERT INTO  `{$tablepre}prize` (";
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
 * 删除参赛信息
 * @param array $idArr	id数组
 */
function entry_del($idArr=array(),$fields='id') {
	global $db,$tablepre;
	if (empty($idArr))return false;
	$ids = implode(',', $idArr);
	$sql = "DELETE FROM `{$tablepre}entry` WHERE `$fields`in($ids)";
	$result	= $db->query($sql);
	return $result;
}
/**
 * 删除指定字段的颁奖信息
 * @param array $idArr	id数组
 */
function prize_del($idArr=array(),$fields='id') {
	global $db,$tablepre;
	if (empty($idArr))return false;
	$ids = implode(',', $idArr);
	$sql = "DELETE FROM `{$tablepre}prize` WHERE `$fields`in($ids)";
	$result	= $db->query($sql);
	return $result;
}