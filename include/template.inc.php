<?php

// +----------------------------------------------------------------------+
// | 网站地址 http://www.dazan.cn                                          |
// +----------------------------------------------------------------------+
// | 这是一个商业软件,您只能在获得本公司授权后才可以对程序进行修改                               |
// +----------------------------------------------------------------------+
// | 作者: 刘维 <hubei_java@163.com>                                       |
// +----------------------------------------------------------------------+
// | 页面:template.inc.php 网站模板函数库                                                                                           |
// +----------------------------------------------------------------------+

//模板编译
function template($file){
	global $_TEMPLATESDIR,$_CACHEDIR;
	if (!GetConfig('template_cache')){
		return $_TEMPLATESDIR . "/$file.php";
	};
	$objfile = $_TEMPLATESDIR . "$file.php";	
	$templateCacheFile = template_cache($objfile);	
	return $templateCacheFile;
}
//模板缓存
function template_cache($objfile) {
	global $_TEMPLATESDIR,$_CACHEDIR;
	$php_path = dirname(__FILE__) . '/';
	$cache_path = $php_path.'../'.$_CACHEDIR;
	// 根据模版文件名定位缓存文件
    $tmplCacheFile = $cache_path.md5($objfile).'.cache.dazan.cn.php';
    //如果缓存文件存在 直接返回  不用编译
    if (is_file($tmplCacheFile)) {
		return $tmplCacheFile;
	} 
	//检查目录是否存在，不存在则创建
	if(!is_dir($cache_path)){
		mkdir($cache_path,'0777');
	}            
	$tmplContent = file_get_contents($php_path.'../'.$objfile);

	//编译模板内容
	$tmplContent = template_compiler($tmplContent);
	//重写Cache文件
	touch($tmplCacheFile);
    if( false === file_put_contents($tmplCacheFile,trim($tmplContent))){
    	exit('缓存不能写->'.$tmplContent);
    }
    return $tmplCacheFile;
}
//模板编译和优化
function template_compiler($tmplContent) {
	// 添加安全代码   不用加  本身每个页面就有的
	/*$tmplContent  =  "<?php if(!defined('IN_SITE')) exit('Access Denied');?>".$tmplContent;*/
	//去空格和换行
	$find     = array("~>\s+<~","~>(\s+\n|\r)~");
    $replace  = array('><','>');
    $tmplContent = preg_replace($find, $replace, $tmplContent);
    return $tmplContent;
}
