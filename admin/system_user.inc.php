<?php
if(!defined('IN_SITE')) exit('Access Denied');

//程序参数安全处理
$_TODOLIST = array('export','doimport','importpage','login','logout','list','adduser','saveadd','edituser','saveedit','deluser');
check_todo($todo,$_TODOLIST);

if($todo=="list"){
	$bumen   = intval(isset ($_GET['bumen'] ) ? $_GET ['bumen'] : '');
	$page    = intval( isset($_GET['page']) ? $_GET['page'] : 1 );
	$perpage = intval( isset($_GET['perpage']) ? $_GET['perpage'] : 20 );
	if($page > 0){
		$startlimit = ($page - 1) * $perpage;
	}else{
		$startlimit = 0;
	}
	//分页类参数数组
	$page_array = array();
	$sql = "SELECT COUNT(id) AS countnum FROM {$tablepre}systemuser";
	if ($bumen!='') {
		$sql .=" WHERE bumen = $bumen";
	}
	$log = $db->fetch_one_array($sql);
	$total  = $log['countnum'];
	$sql = "select * from {$tablepre}systemuser ";
	if ($bumen!='') {
		$sql .=" WHERE bumen = $bumen";
	}
	$sql .=" ORDER BY id ASC LIMIT $startlimit,$perpage";
	$adminquery = $db->query($sql);
	$adminarr = array();
	while($admin = $db->fetch_array($adminquery))
	{
		//转换时间(将秒数转化成当前年-月-日-时：分的格式)
		$admin['lastlogintime'] =  gmdate('Y-n-j  H:i',$admin['lastlogintime']);
		$admin['QQ'] = r_qq($admin['QQ']);
		$adminarr[] = $admin; 	
	}
	$page_control = multipage($total,$perpage,$page);
	unset($adminquery,$admin);
	include template("user_list");
}elseif ($todo=="export"){
	//数据导出
	$title = array( "登录名称","真实姓名","组织","单位","职位","办公电话","手机","级别");
	$Data = array();
	$Data[] = array_values($title);
	require_once 'include/excel_class.php';
	$sql = "SELECT * FROM  {$tablepre}systemuser WHERE userlevel != 1";
	$query = $db->query($sql);
	while ($userinfoArr = $db->fetch_array($query)) {
		$userinfoArr['jiebie'] = getJiBie($userinfoArr['userlevel']);
		$Data[] = array($userinfoArr['username'],$userinfoArr['zname'],$userinfoArr['yuanxi'],$userinfoArr['bumen'],$userinfoArr['zhiwei'],$userinfoArr['tel'],$userinfoArr['phone'],$userinfoArr['jiebie']);
	}
	//print_r($Data);
	Create_Excel_File(iconv("UTF-8","gb2312","用户信息表.xls"),$Data);
	exit;
}
elseif ($todo=="importpage"){//导入页面
	include template("user_import");
}
elseif ($todo=="doimport"){//导入数据
		if(isset($_FILES['xls']))
		{
			require_once('./include/excel_class.php');
			$attach = $_FILES['xls'];
			for ($i = 0; $i < count($attach['name']); $i++)
			{
				if($attach['error'][$i] != 4)
				{
					$attachment=$attach['name'][$i];
					$tmp_attachment  = $attach['tmp_name'][$i];
					$attachment_size = $attach['size'][$i];
					$url = uploadfile($attachment,$tmp_attachment,$attachment_size,array('xls'));
					
					$xls = Read_Excel_File($url,$return);
					if($xls)
					{
						e($xls);
					}
					else
					{
						$row = count($return['Sheet1']);						
						for($i = 1;$i<$row;$i++)
						{
							$sql = "insert into {$tablepre}systemuser
							(`userlevel`,`actions`,`username`,`password`,`zname`,`yuanxi`,`bumen`,`zhiwei`,`tel`,`phone`)values
							('3','system_user,zhouzhi,announcement','".recode($return['Sheet1'][$i][0])."','".md5(recode($return['Sheet1'][$i][1]))."','".recode($return['Sheet1'][$i][2])."','".recode($return['Sheet1'][$i][3])."','".recode($return['Sheet1'][$i][4])."','".recode($return['Sheet1'][$i][5])."','".recode($return['Sheet1'][$i][6])."','".recode($return['Sheet1'][$i][7])."')";
							//echo $sql;
							$db->query($sql);
						}
					}
					@unlink($url);					
					s('导入数据成功','?action=system_user&todo=list');
				}
			}
		}
		else
		{
			e('请选择一个XLS文件');
		}
}
elseif($todo=="adduser") //显示添加用户表单页面
{
	$depaQuery = $db->query("SELECT * FROM  {$tablepre}department ORDER BY sunx ASC");
	while($depa = $db->fetch_array($depaQuery)){
		$depaArr[] = $depa; 	
	}
	//网站模块列表	
	/*$cate_result = $db->query("SELECT * FROM {$tablepre}systemaction WHERE fid = 0");
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
	}*/
	include template("user_add");
}
elseif($todo=="saveadd") //处理添加新用户
{
	$username  = $_POST['username'];
	$password  = $_POST['password'];
	$userlevel = intval($_POST['userlevel']);
	$zname	= $_POST['zname'];
	$zhiwei	= $_POST['zhiwei'];
	$QQ		= intval($_POST['QQ']);
	$phone	= $_POST['phone'];
	$bumen	= $_POST['bumen'];
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
		e("登陆名，已经被注册了。");
	}
	
	$actions = '';
	//管理员级别操作,超级管理员不涉及权限
	if(2 == $userlevel)
	{
		if(is_array($_POST['action']))
		{
			$actions = @join(",",$_POST['action']);
		}
	}
	else
	{
		$actions = 'system_user,announcement';
	}
	$sql = "INSERT INTO {$tablepre}systemuser
				(username,password,lastlogintime,lastloginip,actions,userlevel,zname,QQ,bumen,zhiwei,phone)  
			VALUES 
				('$username','$password',$localtime,'$userip','$actions',$userlevel,'$zname',$QQ,'$bumen','$zhiwei','$phone')";
	$db->query($sql);
	
	
	s('user_reg_sucess','?action='.$act['action'].'&todo=list');
}
//--编辑
elseif($todo=="edituser")
{
	//$uid = intval($_GET['uid']);
	$uid = intval($_SESSION['uid']);
	$userinfo = getUserInfo($uid);

	if($userinfo){
		$username = $userinfo['username'];
	}else{
		e("user_username_notexist");
	}
	$userarr = $userinfo;
	$supadmincheck = $userarr['userlevel']== 1 ? 'checked' : '';
	$admincheck    = $userarr['userlevel']== 2 ? 'checked' : '';
	include template("user_edit");
}
elseif($todo=="saveedit")
{
	//接收用户ID
	$uid = intval($_POST['uid']);

	$old_password	= isset ( $_POST ['old_password'] ) ? $_POST ['old_password'] : '';
	$password		= isset ( $_POST ['password'] ) ? $_POST ['password'] : '';
	$password2		= isset ( $_POST ['password2'] ) ? $_POST ['password2'] : '';
	
	$username		= isset ( $_POST ['username'] ) ? $_POST ['username'] : '';
	$zname		= isset ( $_POST ['zname'] ) ? $_POST ['zname'] : '';
	$userlevel	= 1;
	$email		= isset ( $_POST ['email'] ) ? $_POST ['email'] : '';
	
	//if (strlen($password)<6) e('密码长度不能小于六位');
	if ($password!=$password2) e('两次密码不相同');
	if (! filter_var ( $email,  FILTER_VALIDATE_EMAIL)) e('邮箱格式不正确');
	
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

		$sql .= " username = '$username'";

			if (empty($old_password) && !empty($password)) e("改密码需要原密码");
			
			//密码检查
			if(!empty($password) && !empty($old_password)) 
			{
				if( md5($old_password) == $userarr['password'] ) 
				{
					if( preg_match($reg,$password) ) 
					{
						$password = md5($password);
						$sql .= ",password = '$password'";
					} 
					else
					{
						e("user_userpassword_notmatch");
					}
				}else {
					e("原密码不正确");
				}
			}
		
		if($_SESSION['userlevel'] == 1)  //用户级别为超级管理员
		{
			//级别更新
			$sql .= ",userlevel  = $userlevel";
			if(1 == $userlevel )
			{
				$sql .= ",actions = 'all'";
			}
			else
			{
				//$sql .= ",actions = 'system_user,zhouzhi,announcement'"
				//权限更新
				if(is_array($_POST['action']))
				{
					$actions = @join(",", $_POST['action']);
				}
				$sql .= ", actions = '$actions' ";
			}
		}
		// 去除条件中多余的','
		$sql .= ",zname = '$zname',email = '$email',";
		$sql  = preg_replace('/,$/','',$sql);	
		$sql .= " WHERE id = $uid";
		$db->query($sql);
	}
	else
	{
		e("user_username_notexist");

	}
		s('user_update_sucess','?action='.$act['action'].'&todo=edituser');
	
}
elseif($todo=="deluser")//删除用户
{
	$uid = intval($_GET['uid']);
	if ($uid == $_SESSION['uid']) {
		e('不能删除自己！');
	}
	$db->query("DELETE FROM {$tablepre}systemuser WHERE id = $uid");
	
	s('user_del_sucess','?action='.$act['action'].'&todo=list');

	
}
/**
 * qq
 */
function r_qq($qq) {
	if (intval($qq)!=''&&intval($qq)!=0) {
		return "<a title='点击QQ聊天' href='tencent://message/?uin=".$qq."'><img src='http://wpa.qq.com/pa?p=1:".$qq.":44' border='0'/></a> ";
	}else {
		return '------';
	}
}
?>