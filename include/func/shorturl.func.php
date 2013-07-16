<?php
/**
 * ------------------------------------------------------------------
 * 短网址集中函数库
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
function shorturl_list($startlimit,$endlimit,$where='') {
	global $db,$tablepre;
	$sql = "SELECT * FROM  `{$tablepre}urls` ";
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
 * 获取短URL的总数
 */
function shorturl_total($where='') {
	global $db,$tablepre;
	$sql	= "SELECT COUNT(id) AS countnum FROM {$tablepre}urls ";
	if (!empty($where)) {
		$sql .="WHERE ".$where;
	}
	$result	= $db->fetch_one_array($sql);
	return $result['countnum'];
}
/**
 * 删除指定id的短url
 * @param array $idArr	id数组
 */
function shorturl_del($idArr=array()) {
	global $db,$tablepre;
	$ids = implode(',', $idArr);
	$sql = "DELETE FROM `{$tablepre}urls` WHERE `id`in($ids)";
	$result	= $db->query($sql);
	return $result;
}
/**
 * 得到指定ID的短URL信息
 * @param array		$idArr	id数组
 * @param string	$fields	要查询的字段
 */
function shorturl_get($idArr=array(),$fields='*') {
	global $db,$tablepre;
	$ids = implode(',', $idArr);
	$sql = "SELECT $fields FROM `{$tablepre}urls` WHERE `id`in($ids)";
	$result	= $db->fetch_one_array($sql);
	return $result;
}
/**
 * 更新指定ID的url信息
 * @param int		$id			ID
 * @param array()	$infoArr	修改的条件   array('url'=>"http://www.dazan.cn")
 */
function shorturl_update($id,$infoArr) {
	global $db,$tablepre;
	if (empty($infoArr)) return false;
	$sql = "UPDATE  `{$tablepre}urls` SET ";
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
 * @param int		$id				字段
 * @param string	$alias				值
 * @return int		$result['countnum']	返回几条
 */
function shorturl_alias_check($id,$alias) {
	global $db,$tablepre;
	if (empty($id)) {
		$sql = "SELECT COUNT(id) AS countnum FROM {$tablepre}urls WHERE `alias` = '$alias'";
	}else {
		$sql = "SELECT COUNT(id) AS countnum FROM {$tablepre}urls WHERE `alias` = '$alias' AND `id` !=$id";
	}
	$result	= $db->fetch_one_array($sql);
	if ($result['countnum']>0) {
		return true;
	}
	return false;
}
/**
 * 更新指定ID的url信息
 * @param int		$id			ID
 * @param array()	$infoArr	修改的条件   array('url'=>"http://www.dazan.cn")
 */
function shorturl_add($infoArr) {
	global $db,$tablepre;
	if (empty($infoArr)) return false;
	$sql = "INSERT INTO  `{$tablepre}urls` (";
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
 * 通过短码或者别名还原网站
 * @param string $code	短码或别名
 */
function shorturl_restore($code) {
	global $db,$tablepre;
	if(empty($code))return false;
	$id = base_convert($code,36,10);
	$sql = "SELECT * FROM  `{$tablepre}urls`WHERE id='$id' OR alias='$code' ORDER BY alias DESC LIMIT 1";
	$result	= $db->fetch_one_array($sql);
	return $result;
}
/**
 * 通过ID增加一个浏览次数
 * @param int $id	短url的ID
 */
function shorturl_times_statistic($id) {
	global $db,$tablepre;
	if(empty($id))return false;
	$sql = "UPDATE  `{$tablepre}urls` SET  `times` = times +1 WHERE id =".$id;
	$result	= $db->fetch_one_array($sql);
	return $result;
}