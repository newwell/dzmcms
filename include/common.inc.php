<?php
define("IN_SITE", "TRUE");
define("DZMC_VERSIONS", "1.7.9");
define("DZMC_SOFTDOG_KEY", "136d26b5b22b0dfe0ed781dac2d521f8");
header("Cache-control: private");
header(base64_decode('WC1Qb3dlcmVkLUJ5OiBkYVphbiBOZXR3b3JrIFRlY2gvd3d3LmRhemFuLmNu'));
header("Content-Type: text/html; charset=UTF-8");  
//关闭PHP.ini的错误提示
//ini_set("display_errors",0);
$start_time = array_sum(explode(' ',microtime()));

if (!get_magic_quotes_gpc())
{
    function addslashes_deep($value)
    {
        $value = is_array($value) ? array_map('addslashes_deep', $value) : addslashes($value);
        return $value;
    }
    $_POST      = array_map('addslashes_deep', $_POST);
    $_GET       = array_map('addslashes_deep', $_GET);
    $_COOKIE    = array_map('addslashes_deep', $_COOKIE);
    $_REQUEST   = array_map('addslashes_deep', $_REQUEST);
}

if (__FILE__ == '')
{
    die('Fatal error code: 0');
}

$_HOSTNAME      = $_SERVER['SERVER_NAME'];
$_self_path     = ($_SERVER['PHP_SELF'] == "" ) ? $_SERVER['REQUEST_URI'] : $_SERVER['PHP_SELF'];
$_path_array    = explode("/",$_self_path);
$_path_count    = count($_path_array);
$ROOT_PATH      = '';
for ($i=0;$i<$_path_count-2;$i++)
{
    $ROOT_PATH = '../'.$ROOT_PATH;
}
define('ROOT',$ROOT_PATH);

define('DZMC_ROOT_PATH', dirname(realpath(dirname(__FILE__).'/../index.php')));
unset($_self_path,$_path_array,$_path_count,$ROOT_PATH);

define('FILE_ROOT',str_replace('include/common.inc.php', '', str_replace('\\', '/', __FILE__)));

require_once('config.inc.php');
require_once(DZMC_ROOT_PATH.'/config/config.php');
require_once('db.class.php');
require_once('template.inc.php');
require_once('page.class.php');
require_once('fun.inc.php');

require_once(DZMC_ROOT_PATH.'/include/softdog.php');
//----加密狗验证
//开发版 不做验证
//  if (!softdog_check()){stop('未找到加密狗设备');}
//  $softdog_key = md5(softdog_getID().'dazan'.md5(softdog_readString(10)));
//  if ($softdog_key!=DZMC_SOFTDOG_KEY){stop('加密狗密钥不正确');}
//----end加密狗验证

if(!$enable_debugmode)
{
	error_reporting(0);
}

$db = new db($db_host,$db_user,$db_pass,$db_name,$enable_debugmode);

unset($db_host,$db_user,$db_pass,$db_name);

$localtime = time() + 8 * 3600;

$settings_result = $db->query("SELECT * FROM {$tablepre}settings");
while($settings = $db->fetch_array($settings_result))
{
	$settings['variable']  = 'setting_' . $settings['variable'];
	$$settings['variable'] = $settings['value'];
}
unset($settings_result);

ob_start();
?>