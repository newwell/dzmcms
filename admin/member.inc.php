<?php
if(!defined('IN_SITE')) exit('Access Denied');
CheckAccess();
global $act,$todo,$tablepre,$db;
admin_priv($act['action']);
require_once 'include/f/member.f.php';
switch ($todo) {
	case 'docredits':
		$docredits= isset($_POST['docredits']) ? $_POST['docredits'] : "" ;
		$card		= ( isset($_REQUEST['card']) ? $_REQUEST['card'] : '' );
		if (empty($docredits))e("提现积分为0");
		if (empty($card))e('无法获取读卡');
		$member_info = member_get(array($card),'card');
		$value = $docredits/$setting_rate;
		$r = member_docredits($card, $value);
		s('成功充值[ '.$value.' ]积分',"?action=member_pay&todo=pay&card=".$card);
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