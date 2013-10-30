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
require_once 'include/f/member.f.php';
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
function balance_log($card,$explain,$time,$money,$type='',$type_explain='',$system_user='') {
	
	global $db,$tablepre;
	$member_info = member_get(array($card),'card');
	$explain .="<br>剩余积分:".$member_info['balance']."剩余奖励积分:".$member_info['jiangli_jifen'];
	if (empty($system_user)){
		if (!empty($_SESSION['username'])){
			$system_user =$_SESSION['username'];
		}
	}
	$sql = "INSERT INTO `{$tablepre}balance_log` (`card`, `explains`, `add_date`,`type`,`type_explain`,`system_user`,`money`) VALUES ('$card', '$explain','$time','$type','$type_explain','$system_user','$money');";
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
	$sql	= "SELECT COUNT(id) AS countnum FROM {$tablepre}balance_log ";
	if (!empty($where)) {
		$sql .="WHERE ".$where;
	}
	//echo $sql;exit;
	$result	= $db->fetch_one_array($sql);
	return $result['countnum'];
}
/**
 * 得到指定ID的信息
 * @param array		$idArr	id数组
 * @param string	$fields	要查询的字段
 */
function balance_log_get($idArr=array(),$fields) {
	global $db,$tablepre;
	$ids = implode(',', $idArr);
	$sql = "SELECT * FROM `{$tablepre}balance_log` WHERE `$fields` in($ids)";
	$result	= $db->fetch_one_array($sql);
	return $result;
}