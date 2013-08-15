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
function balance_log($card,$explain) {
	global $db,$tablepre,$localtime;
	$sql = "INSERT INTO `{$tablepre}balance_log` (`card`, `explain`, `add_date`) VALUES ('$card', '$explain', $localtime);";
	$result	= $db->fetch_one_array($sql);
	return $result;
}