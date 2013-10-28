<?php
if(!defined('IN_SITE')) exit('Access Denied');

//程序参数安全处理
$_TODOLIST = array('login','logout','list','adduser','saveadd','edituser','saveedit','deluser');
check_todo($todo,$_TODOLIST);

if($todo=="list")
{
	if($_SESSION['userlevel']==2)
	{
		$sql = "select * from {$tablepre}systemuser WHERE id=".intval($_SESSION['uid']);
	}else
	{
		$sql = "select * from {$tablepre}systemuser WHERE userlevel<3";
	}
	$adminquery = $db->query($sql);
	$adminarr = array();
	while($admin = $db->fetch_array($adminquery))
	{
		//转换时间(将秒数转化成当前年-月-日-时：分的格式)
		$admin['lastlogintime'] =  gmdate('Y-n-j  H:i',$admin['lastlogintime']);
		//转换管理员级别名称
		$admin["userlevel"] = $admin["userlevel"] == 1 ? $lang['user_level_supadmin'] : $lang['user_level_admin'];
		$adminarr[] = $admin; 	
	}
	unset($adminquery,$log,$admin);
	include template("user_list");
}
elseif($todo=="adduser") //显示添加用户表单页面
{
	//网站模块列表
	
	$cate_result = $db->query("SELECT * FROM {$tablepre}systemaction WHERE fid = 0");
	$cates = array();
	while($cate = $db->fetch_array($cate_result))
	{
		$cate['childs'] = array();
		$child_result = $db->query("SELECT * FROM {$tablepre}systemaction WHERE fid = " .$cate['id'] );
		while($child = $db->fetch_array($child_result))
		{
			$cate['childs'][] = $child;
		}
		$cates[] = $cate;
	}
    //$college_type = department(0,1);
	include template("user_add");
}
elseif($todo=="saveadd") //处理添加新用户
{
	$username  = $_POST['username'];
	$password  = $_POST['password'];
	$userlevel = intval($_POST['userlevel']);
	
	//数据合法性验证
	$hascheck = checkuser($username,$password);
	if($hascheck)
	{
		e($hascheck);
	}

	//密码加密处理
	$password  = md5($password);

	//正则验证级别字段数据
	$userlevel = preg_replace("[^1-2]","",$userlevel);
	$userlevel =  $userlevel != '' ? $userlevel : 1;

	//用户IP
	$userip    = $_SERVER['REMOTE_ADDR'];

	//检查用户名是否被使用了
	$sql = "select id from {$tablepre}systemuser where username = '$username'";
	$userarr = $db->fetch_one_array($sql);
	if($userarr) {
		e("user_username_alreadyexist");
	}
	
	$actions = '';
	//管理员级别操作,超级管理员不涉及权限
	if(2 == $userlevel)
	{
		if(is_array($_POST['action']))
		{
			$actions = @join(",", $_POST['action']);
		}
	}
	else
	{
		$actions = 'all';
	}
	$sql = "INSERT INTO {$tablepre}systemuser
				(username,password,lastlogintime,lastloginip,actions,userlevel)  
			VALUES 
				('$username','$password',$localtime,'$userip','$actions',$userlevel)";
	$db->query($sql);
	
	
	s('user_reg_sucess','?action='.$act['action'].'&todo=list');
}
elseif($todo=="edituser")
{
	$uid = intval($_GET['uid']);

	//得到用户信息
	$userarr = $db->fetch_one_array("select * from {$tablepre}systemuser where id = $uid ");
	if($userarr)
	{
		$username = $userarr['username'];
	}
	else
	{
		e("user_username_notexist");
	}

	//网站模块列表
	$cate_result = $db->query("SELECT * FROM {$tablepre}systemaction WHERE fid = 0");
	$cates = array();
	while($cate = $db->fetch_array($cate_result))
	{
		$cate['childs'] = array();
		$child_result = $db->query("SELECT * FROM {$tablepre}systemaction WHERE fid = " .$cate['id'] );
		while($child = $db->fetch_array($child_result))
		{
			//该管理员相应权限检查,如果有则增加一个索引标定该模块本管理员有访问权限
			$child['cando'] = (strpos($userarr['actions'], $child['action']) !== false || $userarr['actions'] == 'all') ? 1 : 0;
			$cate['childs'][] = $child;
		}
		$cates[] = $cate;
	}
	$supadmincheck = $userarr['userlevel']== 1 ? 'checked' : '';
	$admincheck    = $userarr['userlevel']== 2 ? 'checked' : '';
	
	//课程网站内容栏目列表
/*	$m="m";
	$lanmu_result = $db->query("SELECT * FROM {$tablepre}sitemodule WHERE fid = 0");
	$lanmu_arr = array();
	while($lanmu = $db->fetch_array($lanmu_result)){
		$lanmu['cando'] = (strpos($userarr['actions'], $m.$lanmu['id'].$m) !== false || $userarr['actions'] == 'all') ? 1 : 0;
		$lanmu_arr[] = $lanmu;
	}*/
	
	//申报网站内容栏目列表
/*	if ($enable_declare == true) {
		$d="d";
		$lanmu_result = $db->query("SELECT * FROM {$tablepre}declarecate WHERE fid = 0");
		$lanmu_d_arr = array();
		while($lanmu_d = $db->fetch_array($lanmu_result)){
			$lanmu_d['cando'] = (strpos($userarr['actions'], $d.$lanmu_d['id'].$d) !== false || $userarr['actions'] == 'all') ? 1 : 0;
			$lanmu_d_arr[] = $lanmu_d;
		}
	}*/	
	
	include template("user_edit");
}
elseif($todo=="saveedit")
{
	//接收用户ID
	$uid = intval($_POST['uid']);

	//管理员权限检测,只能修改修改自己的密码
	if($_SESSION['userlevel']==2)
	{
		if($_SESSION['uid']!=$uid)
		{
			e('user_access_dined');
		}
	}

	$username     = $_POST['username'];
	$password     = $_POST['password'];
	$old_password = $_POST['old_password'];
	$userlevel    = intval($_POST['userlevel']);

	$sql = "";
	//用户名正则表达式
	$reg = "/^([a-zA-Z0-9]|[._]){4,19}$/";

	//检查修改的用户是否还存在
	$userarr = $db->fetch_one_array("select * from {$tablepre}systemuser where id = $uid ");

	//如果用户存在
	if($userarr) 
	{
		//检查用户名合法性
		if( !preg_match($reg,$username) ) 
		{
			e("user_username_notmatch");
		}

		$sql = "UPDATE {$tablepre}systemuser SET ";

		//检查用户名是否与原用户名相同
		if($username != $userarr['username'])
		{
			//检查新用户名是否被使用
			$user = $db->fetch_one_array("select * from {$tablepre}systemuser where username = '$username' ");
			if($user)
			{
				e("user_username_exist");
			}
			//更新Session用户名
			if($_SESSION['uid'] == $uid)
			{
				$_SESSION['username'] = $username ;
			}
			//更新系统操作记录表中该用户名的用户名
			//$db->query("UPDATE {$tablepre}systemlog set username = '$username' where userid  = $uid"); 
		}

		$sql .= " username = '$username',";

		
		if($_SESSION['userlevel'] == 1)  //用户级别为超级管理员
		{
			if(!empty($password)) 
			{
				if(preg_match($reg,$password) ) 
				{
					$password = md5($password);
					$sql .= " password = '$password' ,";
				} 
				else 
				{
					e("user_userpassword_notmatch");
				}
			}
		}
		elseif($_SESSION['userlevel'] == 2)
		{
			//密码检查
			if(!empty($password) && !empty($old_password)) 
			{
				if( md5($old_password) == $userarr['password'] ) 
				{
					if( preg_match($reg,$password) ) 
					{
						$password = md5($password);
						$sql .= " password = '$password' ,";
					} 
					else
					{
						e("user_userpassword_notmatch");
					}
				}
			}
		}
		
		if($_SESSION['userlevel'] == 1)  //用户级别为超级管理员
		{
			//级别更新
			$sql .= " userlevel  = $userlevel , ";
			//如果原级别为普通管理员且将其升级为超级管理员
			if(1 == $userlevel && $userarr['userlevel'] == 2)
			{
				$sql .= "  actions = 'all' ";
			}
			else
			{
				//权限更新
				if(is_array($_POST['action']))
				{
					$actions = @join(",", $_POST['action']);
				}
				$sql .= "  actions = '$actions' ";
			}
		}
		// 去除条件中多余的','
		$sql  = preg_replace('/,$/','',$sql);	
		$sql .= " WHERE id = $uid";
		
		$db->query($sql);
		 
	}
	else
	{
		e("user_username_notexist");

	}

	s('user_update_sucess','?action='.$act['action'].'&todo=list');
}
elseif($todo=="deluser")//删除用户
{
	$uid = intval($_GET['uid']);
	if ($uid == $_SESSION['uid']) {
		e('不能删除自己!');
	}
	$db->query("DELETE FROM {$tablepre}systemuser WHERE id = $uid");
	s('user_del_sucess','?action='.$act['action'].'&todo=list');
}
?>