<?php
//if(!defined('IN_SITE')) 
exit('Access Denied');
$att_type = array('txt','rar','zip','gif', 'jpg', 'jpeg', 'png','bmp');

if($todo=='')
{
	$siteid   = $_SESSION['siteid'];
	$siteinfo = $db->fetch_one_array("SELECT * FROM {$tablepre}siteclass WHERE id = $siteid ");
	if(empty($siteinfo) || count($siteinfo) == 0)
	{
		stop('teachercp_site_not_exist');
	} 
	$dep = getDep($siteinfo['DepartmentId']);
	
	include template('teacher_index');
}
elseif($todo=='sysset')
{
	$sysset = $db->fetch_one_array("SELECT * FROM {$tablepre}siteclass WHERE id = ".$_SESSION['siteid']);
	#检查教师的课程是否存在
	if(empty($sysset))
	{
		stop('teachercp_site_not_exist');
	}
	$TEMPLATESDIR = $sysset['ClassTemplates'];
	$tmp_info = get_template_info($TEMPLATESDIR,'');
	$index_array = array();
	$j = 0;
	for($i = 0 ; $i< $tmp_info['indexnum']; $i++)
	{	
		$j = $i + 1; 
		$index_array[] = $j;
	}
	$modules = getModulesBySiteId($_SESSION['siteid']);
	include template('teacher_system');
}
elseif($todo=='ajax')
{
	$TEMPLATESDIR = $_GET['dir'];
	$tmp_info = get_template_info($TEMPLATESDIR,'');
	$index_array = array();
	$j = 0;
	for($i = 0 ; $i< $tmp_info['indexnum']; $i++)
	{	
		$j = $i + 1; 
		$index_array[] = $j;
	}
	$modules = getModulesBySiteId($_SESSION['siteid']);
	
	$str  = 'var templates = {';
	$str .= ' "imgurl": "'.$tmp_info['screenshot2'].'",';
	$str .= ' "count": "' .$tmp_info['indexnum'].'",';
	foreach($index_array as $key => $value)
	{
		$str .= ' "modules'.$value.'": [ ';
		foreach($modules as $mkey => $module)
		{
			if($sysset['ext'.$value] == $module['id'])
			{
				$str .= ' {"value":"'.$module['id'].'","modulename":"'.$module['modulename'].'","selected":"true"},';
			}
			else
			{
				$str .= ' {"value":"'.$module['id'].'","modulename":"'.$module['modulename'].'","selected":"false"},';
			}
		}
		$str  = ereg_replace(",$",'',$str);
		$str .= '],';
	}
	$str  = ereg_replace(",$",'',$str);
	$str .= '};';
	echo $str;
}
elseif($todo=='save')
{
	$webname          = $_POST['webname'];
	$ClassTemplates   = $_POST['ClassTemplates'];
	//$BannerImg        = $_POST['BannerImg'];
    //$newBannerImg     = $_FILES['newBannerImg'];
	$ext1  = isset($_POST['ext1']) ?  intval($_POST['ext1']) : 0;
	$ext2  = isset($_POST['ext2']) ?  intval($_POST['ext2']) : 0;
	$ext3  = isset($_POST['ext3']) ?  intval($_POST['ext3']) : 0;
	$ext4  = isset($_POST['ext4']) ?  intval($_POST['ext4']) : 0;
	$ext5  = isset($_POST['ext5']) ?  intval($_POST['ext5']) : 0;
	$ext6  = isset($_POST['ext6']) ?  intval($_POST['ext6']) : 0;
	$ext7  = isset($_POST['ext7']) ?  intval($_POST['ext7']) : 0;
	$ext8  = isset($_POST['ext8']) ?  intval($_POST['ext8']) : 0;
	$ext9  = isset($_POST['ext9']) ?  intval($_POST['ext9']) : 0;
	$ext10 = isset($_POST['ext10']) ?  intval($_POST['ext10']) : 0;
    
    if(empty($webname))
    {
        e('teachercp_site_name_empty');
    }
    if(empty($ClassTemplates))
    {
        e('teachercp_site_class_empty');
    }
   
    
	$sql = "UPDATE {$tablepre}siteclass SET ";
	/*
	if(isset($_FILES['BannerImg']))
	{
		$attach = $_FILES['BannerImg'];
		for ($i = 0; $i < count($attach['name']); $i++)
		{
			if($attach['error'][$i]!=4)
			{
				$ades = addslashes(trim($des[$i]));
				$attachment=$attach['name'][$i];
				$tmp_attachment  = $attach['tmp_name'][$i];
				$attachment_size = $attach['size'][$i];
				$url = uploadfile($attachment,$tmp_attachment,$attachment_size,$att_type);
				$sql .= " BannerImg = $url , ";
			}
			
		}
	}
	*/
	$sql .= "ClassName = '$webname' , ClassTemplates = '$ClassTemplates',
		ext1 = $ext1, ext2 = $ext2, ext3 = $ext3, ext4 = $ext4, ext5 = $ext5, ext6 = $ext6, ext7 = $ext7, ext8 = $ext8, ext9 = $ext9, ext10 = $ext10 
		WHERE id =".$_SESSION['siteid'];
    $db->query($sql);
    s('teachercp_set_success','?action=index&todo=sysset') ;
}
?>
