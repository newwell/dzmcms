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
 * 获取列表
 * @param int		$startlimit	开始行
 * @param int		$perpage	结束行
 * @param array()	$where		查找的条件
 */
function member_list($startlimit,$endlimit,$where='',$order='add_date') {
	global $db,$tablepre;
	$sql = "SELECT * FROM  `{$tablepre}member` ";
	if (!empty($where)) {
		$sql .="WHERE ".$where;
	}
	$sql .= "ORDER BY ".$order." DESC LIMIT $startlimit , $endlimit";
	//exit($sql);
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
	$sql	= "SELECT COUNT(card) AS countnum FROM {$tablepre}member ";
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
	$sql = "SELECT COUNT(card) AS countnum FROM {$tablepre}member WHERE `$field` = '$value'";
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
	if (count($idArr==1)and $fields=='card'){
		$sql = "SELECT * FROM `{$tablepre}member` WHERE `card` ='$ids' or cardid = '$ids'";
	}else {
		$sql = "SELECT * FROM `{$tablepre}member` WHERE `$fields` in($ids)";
	}
	$result	= $db->fetch_one_array($sql);
	return $result;
}
/**
 * 积分充值
 * @param array		$idArr	id数组
 * @param string	$fields	要查询的字段
 */
function member_dopay($card,$value) {
	global $db,$tablepre;
	$sql = "UPDATE  `{$tablepre}member` SET  `balance` =  `balance`+$value WHERE  card =$card;";
	$result	= $db->fetch_one_array($sql);
	return $result;
}
/**
 * 积分提现
 * @param array		$idArr	id数组
 * @param string	$fields	要查询的字段
 */
function member_docredits($card,$value) {
	global $db,$tablepre;
	$sql = "UPDATE  `{$tablepre}member` SET  `balance` =  `balance`-$value WHERE  card =$card;";
	$result	= $db->fetch_one_array($sql);
	return $result;
}
/**
 * 更新指定$card的会员信息信息
 * @param int		$card		读卡
 * @param array()	$infoArr	修改的条件   array('url'=>"http://www.dazan.cn")
 */
function member_update($card,$infoArr) {
	global $db,$tablepre;
	if (empty($infoArr)) return false;
	$sql = "UPDATE  `{$tablepre}member` SET ";
	foreach ($infoArr as $key => $value) {
		$sql.= "`$key` =  '$value',";
	}
	//去掉最后一个多余的,
	$sql = substr($sql,0,strlen($sql)- 1);
	$sql.=" WHERE `card` =$card;";
	return $db->query($sql);
}
/**
 * 删除指定id的信息
 * @param array $idArr	id数组
 */
function member_del($idArr=array()) {
	global $db,$tablepre;
	$ids = implode(',', $idArr);
	$sql = "DELETE FROM `{$tablepre}member` WHERE `card`in($ids)";
	$result	= $db->query($sql);
	return $result;
}