<?php
require_once('include/common.inc.php');
require_once('include/lang/lang.php');

session_start();

//后台语言全局数组
$GLOBALS['lang'] = $lang;

//安全hash值
$formhash = formhash();

//模块导向GET参数
$action = isset($_GET['action']) ? trim($_GET['action']) : '';
$todo   = isset($_GET['todo'])   ? trim($_GET['todo']) : '';
$do     = isset($_GET['do'])     ? trim($_GET['do']) : '';

//ip地址访问检测
banip('admin');

//模板路径设置
$_TEMPLATESDIR = 'admin/templates/';
$_CACHEDIR     = 'data'.DIRECTORY_SEPARATOR.'cache'.DIRECTORY_SEPARATOR;

//模块导航

switch($action) { 
	//登陆界面
	case '':
		include template('login');
	break;
	case 'savereg':		
		$username	= $_POST['username'];
		$password	= $_POST['password'];
		$password2	= $_POST['password2'];
		$zname		= $_POST['zname'];//真姓名
		$yuanxi		= $_POST['province'];//院系
		$bumen		= $_POST['city'];//部门
		$tel		= $_POST['tel'];
		$zhiwei		= $_POST['zhiwei'];
		$phone		= $_POST['phone'];//手机
		$userlevel  = $_POST['userlevel'];
		//数据合法性验证
		$hascheck = checkuser($username,$password);
		if($hascheck){
			e($hascheck);
		}
		/*if ($password!=$password2) {
			e('两次输入的密码不一致！');
		}*/
		$password = md5($password);
		//检查用户名是否被使用了
		$sql = "select id from {$tablepre}systemuser where username = '$username'";
		$userarr = $db->fetch_one_array($sql);
		if($userarr) {
			e("user_username_alreadyexist");
		}
		switch ($userlevel) {
			case '1':
				$actions = "all";
			break;
			case '2':
			case '3':
				$actions = "system_user,zhouzhi,announcement";
			break;
		}
		
		$sql = "INSERT INTO {$tablepre}systemuser (`actions`,`username`, `zname`, `password`, `userlevel`, `yuanxi`, `bumen`, `zhiwei`, `tel`, `phone`) 
		VALUES ('$actions','$username', '$zname', '$password', '$userlevel', '$yuanxi', '$bumen', '$zhiwei', '$tel', '$phone')";
		$db->query($sql);		
		s('用户添加成功','?action=system_user&todo=list');
		
		
	break;
	case 'checklogin' :

		//调用验证码类
		/*require_once('include/code.class.php');
		$photo = new captcha();*/
		$username     = trim($_POST['username']);
		$password     = trim($_POST['password']);
		//$checkcode    = $_POST['checkcode'];
		//$captcha_word = $_SESSION['captcha_word'];

		/*if(!isset($checkcode) || !isset($captcha_word) )
		{
			e('common_code_empty');
		}
		//使用验证码类check_word方法检查验证码是否正确
		if( !$photo->check_word($checkcode,$captcha_word) )
		{
			e('common_code_error');
		}
		unset($photo);*/
		$hascheck = checkuser($username,$password);

		if($hascheck)
		{
			e($hascheck);
		}
		$userarr = $db->fetch_one_array("SELECT * FROM {$tablepre}systemuser WHERE username = '$username'");
		if($userarr) {
			if( md5($password) == $userarr['password'] ) {
				$_SESSION['uid'] = $userarr['id'];
				$_SESSION['username']   = $username;
				$_SESSION['userlevel']  = $userarr['userlevel'];
				$_SESSION['useraction'] = $userarr['actions'];
				$_SESSION['userpassword']   = $userarr['password'];
				$userip    = $_SERVER['REMOTE_ADDR'];
				$db->query("UPDATE {$tablepre}systemuser SET lastloginip = '".$userip ."' , lastlogintime = ".$localtime." WHERE id = ".$userarr['id']);
			} else {
				e("user_access_denied");
			}
		} else {
			e("user_username_notexist");
		}		
			s("登陆成功","?action=show&todo=main");
		
	case 'logout' :
		//关闭SESSION
		session_destroy();
		s("common_exit_success","?");
	//显示操作
	case 'show' :
		CheckAccess();
		//左侧导航菜单读取
		if($todo=='left')
		{
			switch($do) {
				case 'system' :
					if($_SESSION['userlevel']==1)
					{
						$cate_result = $db->query("SELECT * FROM {$tablepre}systemaction WHERE fid = 0 ORDER BY listnum,id ASC");
						$cates = array();
						while($cate = $db->fetch_array($cate_result))
						{
							//读取下级菜单
							$cate['childs'] = array();
							$child_result = $db->query("SELECT * FROM {$tablepre}systemaction WHERE fid = " .$cate['id']." ORDER BY listnum,id ASC" );
							while($child = $db->fetch_array($child_result))
							{
								$cate['childs'][] = $child;
							}
							$cates[] = $cate;
						}
					}elseif($_SESSION['userlevel']==2 || $_SESSION['userlevel']==3)
					{
						$useractions = explode(",",$_SESSION['useraction']);					
						$cate_result = $db->query("SELECT * FROM {$tablepre}systemaction WHERE fid = 0 ORDER BY listnum,id ASC");
						$cates = array();
						while($cate = $db->fetch_array($cate_result))
						{
							//读取下级菜单
							$cate['childs'] = array();
							$child_result = $db->query("SELECT * FROM {$tablepre}systemaction WHERE fid = " .$cate['id']." AND action in ('".implode("','",explode(",",$_SESSION['useraction']))."') ORDER BY listnum,id ASC" );

							while($child = $db->fetch_array($child_result))
							{
								$cate['childs'][] = $child;
							}
							$cates[] = $cate;
						}
					}

				break;
				case 'class' :
					$title = '网站栏目';
					$todo  = 'left_class_nav';
					//JS菜单总数
					$h = 1;
					//JS菜单级别
					$k = 0;
					if($_SESSION['userlevel']==2)
					{
						//$useractions = preg_replace("/[a-zA-Z_]/","",$_SESSION['useraction']);
						//$useractions = preg_replace("/^,,*(\d+)/","\\1",$useractions);

						preg_match_all("/m(\d+)m/",$_SESSION['useraction'],$matches);
						$a = $matches[1];
						$k = array_keys($a);
						 $n = count($k);
						for ($i=0; $i<$n; $i++){
						   $v .= $a[$k[$i]].',';
						 }
						$va =substr ( $v, 0, -1 );
						$javascript = leftclassmenuV($va);

					}else
					{
						$javascript = leftclassmenu(0);
					}
				break;
			}

		}elseif ($todo == 'index'){
			switch ($_SESSION['userlevel']) {
				case '1':
					break;
				case '2':
					//$weipiyue = checkWeiPiYueZhouZhi();
				break;
				case '3':
					//$weitijiao = checkWeiTiJiaoZhouZhi($_SESSION['uid']);					
				break;
				default:
					s('登陆失效','./');
				break;
			}
		}
		include template($todo);
		break;
	//栏目模块导向操作
	default :
		$sql ="SELECT * FROM {$tablepre}systemaction WHERE action = '$action' AND fid != 0";
		$act     = $db->fetch_one_array($sql);
		if(!empty($act))
		{
			
			//提交安全检查
			submitcheck();
			$filename = 'admin/'.$act['page'];
			if (!is_file($filename)){
				stop($act['title'].'--功能稍后写');
			}
			//调用模块文件
			include $filename;
		}
		else
		{ //print_r($sql);exit;
			stop('common_unknow_action');
		}
}
$quernum = $db->query_num;
$db->close();
unset($db);

// 程序运行信息
$end_time = array_sum(explode(' ',microtime()));
$runtime  = number_format( $end_time - $start_time, 10);
echo "\n<!--  $quernum times query, processed in $runtime second(s)  daZan Network Tech/www.dazan.cn -->";
ob_flush();
?>