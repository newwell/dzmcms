<?php
if(!defined('IN_SITE')) exit('Access Denied');
CheckAccess();
global $act,$todo,$tablepre,$db;
admin_priv($act['action']);
require_once 'include/f/buy.f.php';
require_once 'include/f/member.f.php';
require_once 'include/f/balance.f.php';
switch ($todo) {
	case 'docash':
		$card			= dzmc_revise_card(( isset($_POST['card']) ? $_POST['card'] : '' ));
		$method_payment	= htmlspecialchars( isset($_POST['method_payment']) ? $_POST['method_payment'] : '' );
		$payment_amount	= intval( isset($_POST['payment_amount']) ? $_POST['payment_amount'] : 0);
		$diyong_jifen	= intval( isset($_POST['diyong_jifen']) ? $_POST['diyong_jifen'] : 0 );
		$jiangli_jifen	= intval( isset($_POST['jiangli_jifen']) ? $_POST['jiangli_jifen'] : 0 );
		$remark			= htmlspecialchars( isset($_POST['remark']) ? $_POST['remark'] : '' );
		
		if (empty($card)){
			e('读卡不能为空');
		}
		$member_info = member_get(array($card),'card');
		if (!$member_info){
			e("读卡:".$card.";用户不存在");
		}
		if ($method_payment=="jifen"&&$member_info['balance']<$payment_amount){
			e("积分不够,请时使用其他付款方式");
		}
		$money = intval("-".$payment_amount);
		if (balance_reduce($card, $payment_amount)){
			balance_log($card, "非商品交易,扣除$payment_amount积分",$localtime,$money);
		}
		//echo "";
		$method_payment_v = GetConfig('method_payment');
		//print_r($method_payment_v);
		$method_payment_v = $method_payment_v[$method_payment];
		$member_info = member_get(array($card),'card');
		include template('buy_cash_print');
		//s("交易完成","?action=buy_cash&todo=cash");
		break;
	case 'cash'://非商品购买
		include template('buy_cash');
		break;
	case 'list':
		include template('buy_list');
	break;
	
	default:
		e('参数不正确');
	break;
}