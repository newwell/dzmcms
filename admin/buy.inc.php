<?php
/**
 * ------------------------------------------------------------------
 * 商品销售逻辑处理块
 * ------------------------------------------------------------------
 * @author		newwell
 * ------------------------------------------------------------------
 * @copyright	武汉大赞网络科技
 * ------------------------------------------------------------------
 * @link		http://www.dazan.cn
 * ------------------------------------------------------------------
 */
if(!defined('IN_SITE')) exit('Access Denied');
CheckAccess();
global $act,$todo,$tablepre,$db;
admin_priv($act['action']);
require_once 'include/f/buy.f.php';
require_once 'include/f/member.f.php';
require_once 'include/f/balance.f.php';
require_once 'include/f/goods.f.php';

//今天
$kaishi = gmdate("Y-n-j 00:00:00",$localtime);
$jieshu = gmdate("Y-n-j 23:59:59",$localtime);
$jintian = "'".$kaishi."','".$jieshu."'";
//本周
$date = gmdate("Y-m-d",$localtime);
$w = gmdate("w", strtotime($date));  //获取当前周的第几天 周日是 0 周一 到周六是 1 -6
$d = $w ? $w - 1 : 6;  //如果是周日 -6天
$kaishi = gmdate("Y-m-d 00:00:00", strtotime("$date -".$d." days")); //本周开始时间
$jieshu = gmdate("Y-m-d 23:59:59", strtotime("$kaishi +6 days"));  //本周结束时间
$benzhou = "'".$kaishi."','".$jieshu."'";
//本月
$kaishi = gmdate("Y",$localtime).'-'.gmdate("m",$localtime).'-1  00:00:00';
$jieshu = gmdate("Y",$localtime).'-'.gmdate("m",$localtime).'-'.gmdate("t",$localtime).'  23:59:59';
$benyue = "'".$kaishi."','".$jieshu."'";
//近30天
$jieshu = gmdate("Y-n-j 23:59:59",$localtime);
$kaishi = gmdate("Y-m-d 00:00:00", strtotime("$jieshu -30 days"));
$jin30tian =  "'".$kaishi."','".$jieshu."'";
//近3月
$jieshu = gmdate("Y",$localtime).'-'.gmdate("m",$localtime).'-1  00:00:00';
$kaishi = gmdate("Y-m-1 00:00:00", strtotime("$benyu1hao -2 month"));
$jin3yue =  "'".$kaishi."','".$jieshu."'";

switch ($todo) {
	case 'log'://销售记录
		$card		= dzmc_revise_card(( isset($_REQUEST['card']) ? $_REQUEST['card'] : ''));
		$starttime	= isset($_REQUEST['starttime']) ? $_REQUEST['starttime'] : '';
		$endtime	= isset($_REQUEST['endtime']) ? $_REQUEST['endtime'] : '';
		
		$time_where = "";
		$moneywhere=" `card` >0 ";
		if (!empty($starttime)&&!empty($endtime)){
			$time_where = " AND add_date>='".strtotime($starttime)."' AND add_date<='".strtotime($endtime)."'";
		}
		$infoList	= array();
		if ((!empty($card))||(!empty($time_where))){
			$member_info = member_get(array($card),'card');
			$card = isset($member_info['card'])?$member_info['card']:0;
			$sql = "SELECT * FROM  `{$tablepre}order` WHERE `card` >0";
			if (!empty($card)){
				$member_info = member_get(array($card),'card');
				$sql.=" AND `card` ='".$member_info['card']."'";
				$moneywhere.=" AND `card` ='".$member_info['card']."'";
			}
			if (!empty($time_where)) {
				$sql.=$time_where;
				$moneywhere.=$time_where;
			}
			$result		= $db->query($sql." ORDER BY  `add_date` DESC ");
			while($arr	= $db->fetch_array($result)){
				$arr['add_date']= gmdate('Y-n-j H:i:s',$arr['add_date']);
				$arr['member_info'] = member_get(array($arr['card']),"card");
		        $infoList[]	= $arr;
			}
		}else {
			$member_info="";
		}
		//统计合计费用
		$money_sun			= abs(buy_order_money($moneywhere));
		include template('buy_log');
		break;
	case 'buy'://执行购买
		$card			= dzmc_revise_card( isset($_POST['card']) ? $_POST['card'] : '');
		$shuliang		= isset($_POST['shuliang']) ? $_POST['shuliang'] : '';
		$ids			= isset($_POST['ids']) ? $_POST['ids'] : '';
		$method_payment	= htmlspecialchars( isset($_POST['method_payment']) ? $_POST['method_payment'] : '' );
		
		if (empty($card))e("未获取到读卡");
		if (empty($shuliang))e("未购买商品");
		$member_info = member_get(array($card),'card');
		if (empty($member_info))e("用户不存在");
		if (empty($shuliang))e("未得到购买商品数量");
		$goodArr = array();
		foreach ($shuliang as $key => $value) {
			$temp = goods_get(array($ids[$key]));
			$goodArr[$ids[$key]] = $temp[0];
			$goodArr[$ids[$key]]["shuliang"] = $value;
		}
		/* echo "<pre>";
		print_r($goodArr); */
		$remark =$payment_amount=$diyong_jifen=$jiangli_jifen="";
		foreach ($goodArr as $value) {
			$remark.= $value['shuliang'].$value['unit']."\t[".$value['name']."]\t使用".$value['shuliang']*$value['price']."积分,".$value['shuliang']*$value['diyong_jifen']."奖励积分<br/>";
			$payment_amount = intval($payment_amount)+(intval($value['shuliang'])*intval($value['price']));
			$diyong_jifen = intval($diyong_jifen)+(intval($value['shuliang'])*intval($value['diyong_jifen']));
			$jiangli_jifen = intval($jiangli_jifen)+(intval($value['shuliang'])*intval($value['jiangli_jifen']));
		}
		$card = $member_info['card'];
		$r = buy_add(array(
			"card"=>$card,
			"type"=>"商品",
			"method_payment"=>$method_payment,
			"payment_amount"=>$payment_amount,
			"diyong_jifen"=>$diyong_jifen,
			"jiangli_jifen"=>$jiangli_jifen,
			"remark"=>$remark,
			'add_date'=>$localtime
		));
		if (!$r)e("购买失败");
		/*--减少相应的库存*/
		foreach ($goodArr as $value) {
			goods_update($value['id'], array(
			/*---库存减数量=现在的库存-*/
				"inventory"=>$value['inventory']-$value['shuliang']
			));
		}
		$money = intval("-".$payment_amount)-$diyong_jifen+$jiangli_jifen;//计算收入
		balance_reduce($card, $payment_amount);
		$text =  "商品交易,扣除".$payment_amount."积分";
		if (empty($diyong_jifen)){
			balance_reduce($card, $diyong_jifen,"jiangli_jifen");
			$text.=",扣除".$diyong_jifen."奖励积分";
		}
		if (empty($jiangli_jifen)){
			balance_add($card, $jiangli_jifen,"jiangli_jifen");
			$text.=",赠送".$jiangli_jifen."奖励积分";
		}
		balance_log($card,$text."<br/>备注:".$remark,$localtime,$money,"销售","商品");
		$member_info = member_get(array($card),'card');
		include template('buy_print');
		break;
	case 'docash'://非商品购买
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
		$card = $member_info['card'];
		$r = buy_add(array(
			"card"=>$card,
			"type"=>"非商品",
			"method_payment"=>$method_payment,
			"payment_amount"=>$payment_amount,
			"diyong_jifen"=>$diyong_jifen,
			"jiangli_jifen"=>$jiangli_jifen,
			"remark"=>$remark,
			'add_date'=>$localtime
		));
		if (!$r)e("购买失败");
		$money = intval("-".$payment_amount)-$diyong_jifen+$jiangli_jifen;//计算收入
		balance_reduce($card, $payment_amount);
		$text =  "非商品交易,扣除".$payment_amount."积分";
		if (empty($diyong_jifen)){
			balance_reduce($card, $diyong_jifen,"jiangli_jifen");
			$text.=",扣除".$diyong_jifen."奖励积分";
		}
		if (empty($jiangli_jifen)){
			balance_add($card, $jiangli_jifen,"jiangli_jifen");
			$text.=",赠送".$jiangli_jifen."奖励积分";
		}
		balance_log($card,$text,$localtime,$money,"销售","非商品");
		include template('buy_cash_print');
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