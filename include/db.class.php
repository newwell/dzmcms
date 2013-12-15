<?php
/**
 * ------------------------------------------------------------------
 * 数据库操作类
 * ------------------------------------------------------------------
 * @author newwell
 * ------------------------------------------------------------------
 * @copyright	武汉大赞网络科技 
 * ------------------------------------------------------------------
 * @link	http://www.dazan.cn
 * ------------------------------------------------------------------
 */
if(!defined('IN_SITE')) exit('Access Denied');


class db {

	#公有属性
    var  $link;
	var  $data;
	var  $fields;
	var  $row;
	var  $row_num;
	var  $insertid;
	var  $version;
	var  $affected_rows;
	var  $query_num = 0;
	var  $debug;
	#私有属性
	var  $user;
	var  $pass;
	var  $host;
	var  $db;
	
	function db($host,$user,$pass,$db,$debugmode=false) {
		$this->__construct($host,$user,$pass,$db,$debugmode);
	}
	
	function __construct($host,$user,$pass,$db,$debugmode=false) {
        $this->debug =  $debugmode;
		$this->Connect($host,$user,$pass,$db);
	}

	#公有方法
	
	/*
		公有静态方法,链接数据库初始化数据库访问对象
		$host	服务器地址
		$user	用户名
		$pass	密码
		$db		数据库名称
		
		无返回值
	*/
	function Connect($host,$user,$pass,$db) {
		$this->link = @ mysql_connect($host,$user,$pass) or $this->msg('连接数据库失败!可能是mysql数据库用户名或密码不正确! ');
		$this->_selectdb($db);
		if( $this->_version() >'4.1' ) {
			mysql_query("SET NAMES 'utf8'");
		}
		if( $this->_version() > '5.0.1' ) {
			mysql_query("SET sql_mode=''");
		}
		
	}
	
	function query($sql,$type='') {
		//$query = @ mysql_query($sql,$this->link) or $this->msg("SQL语法错误:".htmlspecialchars($sql));
		$func = $type == 'UNBUFFERED' && @function_exists('mysql_unbuffered_query') ?
			'mysql_unbuffered_query' : 'mysql_query';
		if(!($query = $func($sql)) && $type != 'SILENT') {
			if($this->debug) {
				$this->msg(htmlspecialchars($sql));
			}
		}
		$this->_querycount();
		return $query;
	}
		
	function fetch_array($query) {
		$data = @mysql_fetch_array($query);
		return $data;
	}
	
	function num_fields($query) {
		$fields = @mysql_num_fields($query);
		return $fields;
	}
	
	function fetch_row($query) {
		$row = @mysql_fetch_row($query);
		return $row;
	}

	function num_rows($query) {
		$row_num = @mysql_num_rows($query);
		return $row_num;
	}
			
	function insert_id() {
		$insertid = mysql_insert_id();
		return $insertid;
	}
	
	function affected_rows() {
		$affected_rows = mysql_affected_rows($this->link);
		return $affected_rows;
	}
	
	
	function fetch_one_array($sql){
		$query = $this->query($sql);
		$data  = $this->fetch_array($query);
		return $data;
	}
	
	function close() {
		return mysql_close($this->link);
	}
	
	

	
	#私有方法

	function _querycount() {
		$this->query_num++;
	}
	
	function _selectdb($db) {
		mysql_select_db($db,$this->link) or $this->msg('未找到指定数据库!');
	}
	
	function _version() {
		$this->version = mysql_get_server_info();
		return $this->version;
	}
	
	function _geterror() {
		return mysql_error();
	}
	
	function _geterrno() {
		return intval(mysql_errno());
	}
	  
	function msg($info) {
		echo "<html><head>\n";
		echo "<meta http-equiv=\"Content-Type\" content=\"text/html ; charset=utf-8\">\n";
		echo "<title>Sorry!We have an error during the process.</title></head>\n<body>\n";
		echo "<table width=\"65%\"  align=\"center\"  cellpadding=\"2\" cellspacing=\"2\" style=\"border:1px solid #ccc\">";
		echo "<tr><td style=\"line-height:22px;font-size:12px;font-family:tahoma;color:#666;\">\tWaring!MYSQL DataBase Query Error:<br /> $info <br />";
		echo "MYSQL error information:<br />".$this->_geterror()."<br />";
		echo "MYSQL error number:<br />".$this->_geterrno()."<br />\n";
		echo "Time: <br />".gmdate("Y-n-j g:ia", time() + (8 * 3600))."<br />\n";
		echo "File: <br />".$_SERVER['PHP_SELF']."<br /></td></tr>\n";
		echo "</table>\n</body>\n</html>\n";
		exit;
	}
	
}   
?>