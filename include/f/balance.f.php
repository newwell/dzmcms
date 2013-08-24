<?php
/**
 * ------------------------------------------------------------------
 * 会员余额函数库
 * ------------------------------------------------------------------
 * @author newwell
 * ------------------------------------------------------------------
 * @copyright	武汉大赞网络科技 
 * ------------------------------------------------------------------
 * @link	http://www.dazan.cn
 * ------------------------------------------------------------------
 */
/**
 * 积分增加
 * @param int		$card	用户card
 * @param string	$value	增加的值
 */
function balance_add($card,$value,$type='balance') {
	global $db,$tablepre;
	$sql = "UPDATE  `{$tablepre}member` SET  `$type` =  `$type`+$value WHERE  card =$card;";
	$result	= $db->fetch_one_array($sql);
	return $result;
}
/**
 * 积分减少
 * @param int		$card	用户card
 * @param string	$value	增加的值
 */
function balance_reduce($card,$value,$type='balance') {
	global $db,$tablepre;
	$sql = "UPDATE  `{$tablepre}member` SET  `$type` =  `$type`-$value WHERE  card =$card;";
	$result	= $db->fetch_one_array($sql);
	return $result;
}
/**
 * 积分记录
 * @param int		$card	用户card
 * @param string	$explain	增加的值
 */
function balance_log($card,$explain,$time) {
	global $db,$tablepre;
	$sql = "INSERT INTO `{$tablepre}balance_log` (`card`, `explain`, `add_date`) VALUES ('$card', '$explain','$time' );";
	$result	= $db->fetch_one_array($sql);
	return $result;
}

/**
 * 获取列表
 * @param int		$startlimit	开始行
 * @param int		$perpage	结束行
 * @param array()	$where		查找的条件
 */
function balance_log_list($startlimit,$endlimit,$where='') {
	global $db,$tablepre;
	$sql = "SELECT * FROM  `{$tablepre}balance_log` ";
	if (!empty($where)) {
		$sql .="WHERE ".$where;
	}
	$sql .= "ORDER BY add_date DESC LIMIT $startlimit , $endlimit";
	$result		= $db->query($sql);
	$resultArr	= array();
	while($arr	= $db->fetch_array($result)){
		$resultArr[]	= $arr;
	}
	return $resultArr;
}
/**
 * 该条件下的积分记录的总数
 */
function balance_log_total($where='') {
	global $db,$tablepre;
	$sql	= "SELECT COUNT(card) AS countnum FROM {$tablepre}balance_log ";
	if (!empty($where)) {
		$sql .="WHERE ".$where;
	}
	$result	= $db->fetch_one_array($sql);
	return $result['countnum'];
}