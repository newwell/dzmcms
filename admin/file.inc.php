<?php
if(!defined('IN_SITE')) exit('Access Denied');
$_TODOLIST = array('show','del');

if($todo=="show") #显示文件列表界面以及文件上传界面
{
	$dir         = $_GET['tmp']; #获取文件目录
	
	#获取父路径
	$farther_dir = split("/",substr($dir,0,-1));
	if(count($farther_dir) <= 3)
	{
		$goback = "";
	}
	else
	{
		$goback = "";
		for($i=0;$i<count($farther_dir)-1;$i++)
		{
			$goback .= $farther_dir[$i]."/";
		}
	}
	
	if(!preg_match('/^(coursefile\/)/',$dir))
	{
		die('Access Denied');
	}
	
	$filearr = array();
	$file = opendir($dir); #整理文件夹以及文件
	while( ($files  = readdir($file)) !== false)
	{
		if($files !="." && $files!=".." && $files!="")
		{
			if(is_dir($dir."/".$files)) #文件夹处理
			{
				$filearr[] = array('type'=>'folder','name'=>gb2utf8($files),'url'=>$dir.gb2utf8($files));
			}
			elseif(is_file($dir."/".$files)) #文件处理
			{
				$filesize  = filesize($dir."/".$files);
				$filearr[] = array('type'=>'file','name'=>gb2utf8($files),'url'=>$dir.gb2utf8($files));
			}
		}
	}
	closedir($file);
	if($do=="")
	{
		include template("article_file_list");
	}
	else
	{
		include template("article_file_up");
	}
}
elseif($todo=="del")
{
	$dir = $_GET['tmp']; #获取文件目录
	
	#获取返回路径
	$farther_dir = split("/",$dir);
	$goback = "";
	for($i=0;$i<count($farther_dir)-1;$i++)
	{
		$goback .= $farther_dir[$i]."/";
	}
	//echo $goback;
	
	//unlink($dir);
	s("user_login_success","?action=file&todo=show&tmp=".$goback);
}
?>