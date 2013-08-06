<?php
if(!defined('IN_SITE')) exit('Access Denied');
CheckAccess();
global $act,$todo,$tablepre,$db;
admin_priv($act['action']);
require_once 'include/f/member.f.php';
switch ($todo) {
	case 'doexport':
		$start = strtotime ( isset ( $_POST ['start'] ) ? $_POST ['start'] : '' );
		$end = strtotime ( isset ( $_POST ['end'] ) ? $_POST ['end'] : '' );
		
		if (empty ( $start ) || empty ( $end ))e ( '选择一个时间' );
		
		$title = Array("读卡","会员号","持卡类型","押金","姓名","昵称","手机号","电子邮箱",
					"身份证号","性别","会员等级","生日","年费","年费到期时间","余额","客户经理","居住地址",
					"qq或者MSN","工作单位","职业","大赛资格","大赛次数","代表俱乐部","代表城市","奖励积分","注册时间");
		$Data = array ();
		$Data [] = array_values ( $title );
		require_once 'include/excel_class.php';
		
		$sql = "SELECT * FROM  `{$tablepre}member` WHERE add_date<$end AND add_date>$start";
		$query = $db->query ( $sql );
		while ( $rArr = $db->fetch_array ( $query ) ) {
			//$rArr ['add_date'] = gmdate ( 'Y-n-j', $rArr ['add_date'] );
			if ($rArr ['sex']==1){
				$rArr ['sex'] = "男";
			}else {
				$rArr ['sex'] = "女";
			}
			//$Data[] = $rArr;
			$Data [] = array ($rArr ['card'], $rArr ['cardid'], $rArr ['card_type'], $rArr ['cash_pledge'], $rArr ['name'], $rArr ['nickname'], $rArr ['phone'], $rArr ['email'],
			 $rArr ['identity_card'], $rArr ['sex'], $rArr ['grade'], gmdate ( 'Y-n-j', $rArr ['birthday'] ), $rArr ['annual_fee'],gmdate ( 'Y-n-j', $rArr ['annual_fee_end_time'] ), $rArr ['balance'], $rArr ['customer_manager'], $rArr ['address'],
			 $rArr ['qq'], $rArr ['work_unit'], $rArr ['occupation'], $rArr ['eligibility'], $rArr ['match_number'], $rArr ['representative_club'], $rArr ['representative_city'], $rArr ['jiangli_jifen'],gmdate ( 'Y-n-j', $rArr ['add_date'] ));
		}
		//echo '<pre>';print_r($Data);exit;
		$start = gmdate ( 'Y_n_j', $start );
		$end = gmdate ( 'Y_n_j', $end );
		Create_Excel_File ( iconv ( "UTF-8", "gb2312", $start . "-" . $end . "所有会员.xls" ), $Data );
		break;
	case 'export':
		include template ( 'member_export' );
		break;
	case 'doimport':
		if (isset ( $_FILES ['xls'])) {
			require_once ('include/excel_class.php');
			$attach = $_FILES ['xls'];
			for($i = 0; $i < count ( $attach ['name'] ); $i ++) {
				if ($attach ['error'] [$i] != 4) {
					$attachment = $attach ['name'] [$i];
					$tmp_attachment = $attach ['tmp_name'] [$i];
					$attachment_size = $attach ['size'] [$i];
					$url = uploadfile ( $attachment, $tmp_attachment, $attachment_size, array ('xls' ) );
					
					$xls = Read_Excel_File ( $url, $return );
					if ($xls) {
						e ( $xls );
					} else {
						//echo "<pre>";print_r($return);exit();
						$row = count ( $return ['Sheet1'] );
						
						for($i = 1; $i < $row; $i ++) {
							if ((recode ( $return ['Sheet1'] [$i] [9] ))=="男"){
								$sex = 1;
							}else {
								$sex = 0;
							}
								$result = member_add(array(
									'card'=>recode ( $return ['Sheet1'] [$i] [0] ),
									'cardid'=>recode ( $return ['Sheet1'] [$i] [1] ),
									'card_type'=>recode ( $return ['Sheet1'] [$i] [2] ),
									'cash_pledge'=>recode ( $return ['Sheet1'] [$i] [3] ),
									'name'=>recode ( $return ['Sheet1'] [$i] [4] ),
									'nickname'=>recode ( $return ['Sheet1'] [$i] [5] ),
									'phone'=>recode ( $return ['Sheet1'] [$i] [6] ),
									'email'=>recode ( $return ['Sheet1'] [$i] [7] ),
									'identity_card'=>recode ( $return ['Sheet1'] [$i] [8] ),
									'sex'=>$sex,
									'grade'=>recode ( $return ['Sheet1'] [$i] [10] ),
									'birthday'=>strtotime(recode ( $return ['Sheet1'] [$i] [11] )),
									'annual_fee'=>recode ( $return ['Sheet1'] [$i] [12] ),
									'annual_fee_end_time'=>strtotime(recode ( $return ['Sheet1'] [$i] [13] )),
									'balance'=>recode ( $return ['Sheet1'] [$i] [14] ),
									'customer_manager'=>recode ( $return ['Sheet1'] [$i] [15] ),
								
									'address'=>recode ( $return ['Sheet1'] [$i] [16] ),
									'qq'=>recode ( $return ['Sheet1'] [$i] [17] ),
									'work_unit'=>recode ( $return ['Sheet1'] [$i] [18] ),
									'occupation'=>recode ( $return ['Sheet1'] [$i] [19] ),
									'eligibility'=>recode ( $return ['Sheet1'] [$i] [20] ),
									'match_number'=>recode ( $return ['Sheet1'] [$i] [21] ),
									'representative_club'=>recode ( $return ['Sheet1'] [$i] [22] ),
									'representative_city'=>recode ( $return ['Sheet1'] [$i] [23] ),
									'jiangli_jifen'=>recode ( $return ['Sheet1'] [$i] [24] ),
								
									'add_date'=>strtotime(recode ( $return ['Sheet1'] [$i] [25] ))
								));
								/*if (!$result){
									$resultArr[] =recode ( $return ['Sheet1'] [$i] [0] );
								}*/
						}						
					}
					
					@unlink ( $url );
					s ( '导入数据成功', '?action=member_import&todo=import' );
				}
			}
		} else {
			e ( '请选择一个XLS文件' );
		}
		break;
	case 'import':
		include template ( 'member_import' );
		break;
	case 'dochangePassword':
		$card		= ( isset($_REQUEST['card']) ? $_REQUEST['card'] : '' );
		$odl_pwd	= isset($_POST['odl_pwd']) ? $_POST['odl_pwd'] : "" ;
		$new_pwd	= isset($_POST['new_pwd']) ? $_POST['new_pwd'] : "" ;
		$new2_pwd	= isset($_POST['new2_pwd']) ? $_POST['new2_pwd'] : "" ;
		
		if (empty($card))s('无法获取读卡',"?action=member_changePassword&todo=changePassword&card=".$card);
		$member_info = member_get(array($card),'card');
		if ($new_pwd!=$new2_pwd){
			s('两次输入的密码不同',"?action=member_changePassword&todo=changePassword&card=".$card);
		}
		$odl_pwd = md5($odl_pwd);
		if ($odl_pwd!=$member_info['pwd']){
			s('原密码不正确',"?action=member_changePassword&todo=changePassword&card=".$card);
		}
		$result = member_update($card, array(
			'pwd' =>md5($new_pwd)
		));
		if ($result) {
			s('密码修改成功',"?action=member_changePassword&todo=changePassword&card=".$card);
		}else {
			s('修改失败',"?action=member_changePassword&todo=changePassword&card=".$card);
		}
		break;
	case 'changePassword':
		$card		= ( isset($_REQUEST['card']) ? $_REQUEST['card'] : '' );
		if (!empty($card)){
			$member_info = member_get(array($card),'card');
		}else {
			$member_info = '';
		}
		include template('member_changePassword');
		break;
	case 'docredits':
		$docredits= isset($_POST['docredits']) ? $_POST['docredits'] : "" ;
		$card		= ( isset($_REQUEST['card']) ? $_REQUEST['card'] : '' );
		if (empty($docredits))e("提现金额为0");
		if (empty($card))e('无法获取读卡');
		$member_info = member_get(array($card),'card');
		$value = $docredits*$setting_rate;
		if ($member_info['balance']<$value){
			s('您的积分不够,无法兑换',"?action=member_credits&todo=credits&card=".$card);
		}
		$r = member_docredits($card, $value);
		s('成功,提现[ '.$docredits.' ]扣除[ '.$value.' ]积分',"?action=member_credits&todo=credits&card=".$card);
		break;
	case 'credits'://提现/积分兑换
		$card		= ( isset($_REQUEST['card']) ? $_REQUEST['card'] : '' );
		if (!empty($card)){
			$member_info = member_get(array($card),'card');
		}else {
			$member_info = '';
		}
		include template('member_credits');
		break;
	case 'dopay':
		$dopay	= isset($_POST['dopay']) ? $_POST['dopay'] : "" ;
		$card		= ( isset($_REQUEST['card']) ? $_REQUEST['card'] : '' );
		if (empty($dopay))e("充值金额为0");
		if (empty($card))e('无法获取读卡');
		$value = $dopay*$setting_rate;
		$r = member_dopay($card, $value);
		s('成功充值[ '.$value.' ]积分',"?action=member_pay&todo=pay&card=".$card);	
		break;
	case 'pay':
		$card		= ( isset($_REQUEST['card']) ? $_REQUEST['card'] : '' );
		if (!empty($card)){
			$member_info = member_get(array($card),'card');
		}else {
			$member_info = '';
		}
		include template('member_pay');
		break;
	case 'dofind':
		$card		= (int)( isset($_REQUEST['card']) ? $_REQUEST['card'] : '' );
		$cardid		= (int)( isset($_REQUEST['cardid']) ? $_REQUEST['cardid'] : '' );
		$member_info = '';
		if (!empty($card)){
			$member_info = member_get(array($card),'card');
		}elseif (!empty($cardid)){
			$member_info = member_get(array($cardid),'cardid');
		}else {
			e('至少填写一个信息');
		}
		if (empty($member_info))e('暂无该会员信息');
		include template('member_dofind');
		break;
	case 'find':
		include template('member_find');
		break;
	case 'saveadd':
		$card		= (int)( isset($_POST['card']) ? $_POST['card'] : 0 );
		$cardid		= (int)( isset($_POST['cardid']) ? $_POST['cardid'] : 0 );
		$card_type	= (int)( isset($_POST['card_type']) ? $_POST['card_type'] : 0 );		
		$cash_pledge= htmlspecialchars( isset($_POST['cash_pledge']) ? $_POST['cash_pledge'] : '' );
		$name		= htmlspecialchars( isset($_POST['name']) ? $_POST['name'] : '' );
		$nickname	= htmlspecialchars( isset($_POST['nickname']) ? $_POST['nickname'] : '' );
		$phone		= htmlspecialchars( isset($_POST['phone']) ? $_POST['phone'] : '' );
		$email		= htmlspecialchars( isset($_POST['email']) ? $_POST['email'] : '' );
		$identity_card	= htmlspecialchars( isset($_POST['identity_card']) ? $_POST['identity_card'] : '' );
		$sex		= htmlspecialchars( isset($_POST['sex']) ? $_POST['sex'] : 1 );
		$grade		= htmlspecialchars( isset($_POST['grade']) ? $_POST['grade'] : '' );
		$birthday	= htmlspecialchars( isset($_POST['birthday']) ? $_POST['birthday'] : 0 );
		$annual_fee	= htmlspecialchars( isset($_POST['annual_fee']) ? $_POST['annual_fee'] : '' );
		$annual_fee_end_time= htmlspecialchars( isset($_POST['annual_fee_end_time']) ? $_POST['annual_fee_end_time'] : '' );
		$balance	= htmlspecialchars( isset($_POST['balance']) ? $_POST['balance'] : "0.0" );
		$customer_manager	= htmlspecialchars( isset($_POST['customer_manager']) ? $_POST['customer_manager'] : '' );

		$address	= htmlspecialchars( isset($_POST['address']) ? $_POST['address'] : '' );
		$qq			= htmlspecialchars( isset($_POST['qq']) ? $_POST['qq'] : '' );
		$work_unit	= htmlspecialchars( isset($_POST['work_unit']) ? $_POST['work_unit'] : '' );
		$occupation	= htmlspecialchars( isset($_POST['occupation']) ? $_POST['occupation'] : '' );
		$eligibility	= htmlspecialchars( isset($_POST['eligibility']) ? $_POST['eligibility'] : 0 );
		$match_number	= htmlspecialchars( isset($_POST['match_number']) ? $_POST['match_number'] : 0 );
		$representative_club	= htmlspecialchars( isset($_POST['representative_club']) ? $_POST['representative_club'] : '' );
		$representative_city	= htmlspecialchars( isset($_POST['representative_city']) ? $_POST['representative_city'] : '' );
		
		if(empty($card))e('读卡不能为空');
		if(empty($cardid))e('会员卡编号未填写');
		if(empty($name))e('姓名不能为空');
		if(empty($phone))e('手机号不能为空');
		
		if (!empty($email)) {
			if (! filter_var ( $email, FILTER_VALIDATE_EMAIL )) e('邮箱格式不正确');
		}
		if (!empty($annual_fee)) {
			if (empty($annual_fee_end_time)) e('在有年费的情况下,年费到期时间不能不写');
		}
		
		if (member_check_field('card', $card))e('读卡已经别占用');
		if (member_check_field('cardid', $cardid))e('会员编号已经别占用');

		$result = member_add(array(
			'card'=>$card,
			'cardid'=>$cardid,
			'card_type'=>$card_type,
			'cash_pledge'=>$cash_pledge,
			'name'=>$name,
			'nickname'=>$nickname,
			'phone'=>$phone,
			'email'=>$email,
			'identity_card'=>$identity_card,
			'sex'=>$sex,
			'grade'=>$grade,
			'birthday'=>strtotime($birthday),
			'annual_fee'=>$annual_fee,
			'annual_fee_end_time'=>strtotime($annual_fee_end_time),
			'balance'=>$balance,
			'customer_manager'=>$customer_manager,
		
			'address'=>$address,
			'qq'=>$qq,
			'work_unit'=>$work_unit,
			'occupation'=>$occupation,
			'eligibility'=>$eligibility,
			'match_number'=>$match_number,
			'representative_club'=>$representative_club,
			'representative_city'=>$representative_city,
		
			'add_date'=>$localtime
		));
		if ($result) {
			s('添加成功','?action=member_add&todo=add');
		}else {
			e('添加失败');
		}
		break;
	case 'add':
		include template('member_add');
		break;
	case 'list':
		$page   = intval( isset($_GET['page']) ? $_GET['page'] : 1 );
		$perpage = intval( isset($_GET['perpage']) ? $_GET['perpage'] : 20 );
		if($page > 0){
			$startlimit = ($page - 1) * $perpage;
		}else{
			$startlimit = 0;
		}
		$page_array = array();
		$total		= member_total();
		$page_control = multipage($total,$perpage,$page);
		$durlArr	= member_list($startlimit, $perpage);	
		include template('member_list');
		break;
	default:
		e('参数不正确');
	break;
}