<?php
if(!defined('IN_SITE')) exit('Access Denied');

//权限检查
CheckAccess();
admin_priv('system_set');

$_COMMONURL = array();
$_COMMONURL['default'] = array('?action='.$act['action'].'&todo=show','system_title');


//程序参数安全处理
$_TODOLIST = array('show','saveset','ajax','skin','saveskin');
check_todo($todo,$_TODOLIST);


if($todo=="show")
{
    if($setting_sitestatus == '1')
    {
        $check1 = "checked";
        $check2 = "";
    }
    elseif($setting_sitestatus == '0')
    {
    	$check1 = "";
        $check2 = "checked";
    }
	
    include template('system');
}elseif($todo=="saveset")
{
	$settings_result = $db->query("SELECT * FROM {$tablepre}settings");
	while($settings = $db->fetch_array($settings_result) ) 
	{
		if(isset($_POST['setting_'.$settings['variable']])) 
		{
			$db->query("UPDATE {$tablepre}settings SET value = '".$_POST['setting_'.$settings['variable']]."' WHERE variable = '".$settings['variable']."' ");
		}
	}
	//ajaxs('system_setconfig_success');
    s('system_setconfig_success','?action='.$act['action'].'&todo=show');
}
function getTemplateArr($dir)
{
	$available_templates = array();
	$this_Dirs           = array();
	$template_dir        = opendir($dir);
	while ($file = readdir($template_dir))
	{
		//过滤目录
		if(preg_match('/^\.$/',$file)) continue;
		if(preg_match('/^\.\.$/',$file)) continue;
		//过滤.开头文件夹
		if(preg_match('/^\..+$/',$file)) continue;
		$available_templates[] = $file;
	}
	closedir($template_dir);
    return  $available_templates;
}