<?php
/**
 * ------------------------------------------------------------------
 * 消费集中函数库
 * ------------------------------------------------------------------
 * @author newwell
 * ------------------------------------------------------------------
 * @copyright	武汉大赞网络科技
 * ------------------------------------------------------------------
 * @link	http://www.dazan.cn
 * ------------------------------------------------------------------
 */
/**
 * 添加账单信息
 * @param array()	$infoArr	修改的条件   array('url'=>"http://www.dazan.cn")
 */
function buy_add($infoArr) {
	global $db,$tablepre;
	if (empty($infoArr)) return false;
	if (!is_array($infoArr))return false;
	$sql = "INSERT INTO  `{$tablepre}order` (";
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
 * 该条件下的参赛的总数
 */
function buy_order_total($where='') {
	global $db,$tablepre;
	$sql	= "SELECT COUNT(id) AS countnum FROM {$tablepre}order ";
	if (!empty($where)) {
		$sql .="WHERE ".$where;
	}
	$result	= $db->fetch_one_array($sql);
	return $result['countnum'];
}
/**
 * 计算该条件下的支付的金额合计
 */
function buy_order_money($where='') {
	global $db,$tablepre;
	$sql	= "SELECT SUM(payment_amount) AS moneysum FROM {$tablepre}order ";
	if (!empty($where)) {
		$sql .="WHERE ".$where;
	}
	$result	= $db->fetch_one_array($sql);
	return $result['moneysum'];
}