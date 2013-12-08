<?php
/**
 * ------------------------------------------------------------------
 * 赛事逻辑处理块
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
global $act,$todo,$tablepre,$db,$do;
admin_priv($act['action']);
require_once 'include/f/sport.f.php';
require_once 'include/f/member.f.php';
require_once 'include/f/balance.f.php';
require_once 'include/f/staff.f.php';
switch ($todo) {
	case 'prize2service'://把剩余奖池放入服务费收益中
		$sport_id	= intval( isset($_GET['sport_id']) ? $_GET['sport_id'] : '' );
		if (empty($sport_id)) e("未获取到赛事编号");
		$sport_info		= sport_get(array($sport_id),'id');
		if ($sport_info['jackpot']<1) {
			s("没有奖池可以转入服务费了",'?action=sport_list&todo=prize&id='.$sport_id);
		}
		switch ($sport_info['type']) {
			case 'no_time_trial':
				$explain = "非计时赛";
			break;
			case 'time_trial':
				$explain = "计时赛";
			break;
			case 'pk_trial':
				$explain = "PK赛";
			break;
		}
		//剩余奖池扣完
		jackpot_reduce($sport_id, $sport_info['jackpot']);
		//记入服务费收益
		$money = intval('-'.$sport_info['jackpot']);
		balance_log("", "", $$localtime, $money,"服务费",$explain,$sport_id);
		s("转入成功",'?action=sport_list&todo=prize&id='.$sport_id);
	break;
	case 'desktop':
		$page   = intval( isset($_GET['page']) ? $_GET['page'] : 1 );
		$perpage = intval( isset($_GET['perpage']) ? $_GET['perpage'] : 20 );
		if($page > 0){
			$startlimit = ($page - 1) * $perpage;
		}else{
			$startlimit = 0;
		}
		$page_array = array();
		$total		= sport_total();
		$page_control = multipage($total,$perpage,$page);
		$listArr	= sport_list($startlimit, $perpage);
		include template('sport_desktop');
		break;
	case 'dorebuy':
		$r_card		= dzmc_revise_card(( isset($_POST['card']) ? $_POST['card'] : '' ));
		$buy_card	= dzmc_revise_card(( isset($_POST['buy_card']) ? $_POST['buy_card'] : '' ));
		$entry_id	= intval( isset($_POST['entry_id']) ? $_POST['entry_id'] : '' );
		$sport_id	= intval( isset($_POST['sport_id']) ? $_POST['sport_id'] : '' );
		$payment_type   = isset($_POST['payment_type']) ? $_POST['payment_type'] : '';
		
		$sportcharge	= intval( isset($_POST['sportcharge']) ? $_POST['sportcharge'] : '' );
		
		$tiaohui = "?action=sport_withdraw&todo=withdraw&card=$r_card";
		if (empty($buy_card)){s('没有得到读卡',$tiaohui);}
		if (empty($entry_id)){s('没有得到参赛编号',$tiaohui);}
		if (empty($sport_id)){s('没有得到赛事编号',$tiaohui);}
		
		//用户信息为 支付用户的
		$member_info	= member_get(array($buy_card),'card');
		$r_member_info	= member_get(array($r_card),'card');
		$entry_info		= entry_get(array($entry_id),'id');
		$sport_info		= sport_get(array($sport_id),'id');
		
		$card = $member_info['card'];
		//计算赛事费用
		if($sport_info['type']=='time_trial'){//计时赛
			$serviceCharge = $sport_info['deduction'];
		}elseif ($sport_info['type']=='no_time_trial') {//非计时赛
			$serviceCharge = $sport_info['deduction']+$sport_info['service_charge'];
		}elseif ($sport_info['type']=='pk_trial'){//PK赛
			$serviceCharge = $sportcharge;
			if (empty($serviceCharge)){
				s('PK赛rebuy费用不能为空',$tiaohui);
			}
		}else {
			s('无法获取赛事类型',$tiaohui);
		}
		
		if((($member_info['balance'])+($member_info['jiangli_jifen']))<($serviceCharge)){
			s('支付人所有积分不够,无法完成rebuy',"?action=sport_withdraw&todo=rebuy&entry_id=$entry_id&card=$r_card&sport_id=$sport_id");
		}
		if ($payment_type=='jiangli_jifen'){
			if ($member_info['jiangli_jifen']>$serviceCharge) {
				balance_reduce($card, $serviceCharge,$payment_type);//扣除积分;
				$money = intval("-".$serviceCharge);
				$text_ ="扣除rebuy费,奖励积分:".$serviceCharge."分";
				$explain = "为[".$r_member_info['name']."]rebuy赛事[ ".$sport_info['name']." ]:".$text_.",  ";
			}else {
				$cha = $serviceCharge-$member_info['jiangli_jifen'];
				balance_reduce($card, $member_info['jiangli_jifen'],'jiangli_jifen');
				balance_reduce($card, $cha,'balance');
				$money = intval("-".($member_info['jiangli_jifen']+$cha));
				$text_ = "扣除rebuy费,奖励积分:".$member_info['jiangli_jifen']."分,积分:".$cha."分";
				$explain = "为[".$r_member_info['name']."]rebuy赛事[ ".$sport_info['name']." ]:".$text_.",  ";
			}
		}elseif ($payment_type=="balance") {
			if ($member_info['balance']>$serviceCharge) {
				balance_reduce($card, $serviceCharge,$payment_type);//扣除积分;
				$money = intval("-".$serviceCharge);
				$text_ = "扣除rebuy费:,积分:".$serviceCharge."分";
				$explain = "为[".$r_member_info['name']."]rebuy赛事[ ".$sport_info['name']." ]:".$text_.",  ";
			}else {
				$cha = $serviceCharge-$member_info['balance'];
				balance_reduce($card, $member_info['balance'],'balance');
				balance_reduce($card, $cha,'jiangli_jifen');
				$money = intval("-".($member_info['balance']+$cha));
				$text_ = "扣除rebuy费,积分:".$member_info['balance']."分,奖励积分:".$cha."分";
				$explain = "为[".$r_member_info['name']."]rebuy赛事[ ".$sport_info['name']." ]:".$text_.",  ";
			}
		}
			
		$result = entry_update($entry_id, array(
				"number"=>$entry_info['number']+1
		));
		if ($result) {
			//$money = intval($value);
			balance_log($card, $explain, $localtime,$money);
			//增加奖池  和  记录服务费
			//++++++++++++++++++++++++++++++++
			if ($sport_info['type']=='pk_trial') {
				//PK赛买入的钱都直接进入奖池
				jackpot_add($sport_id,$serviceCharge);
				//PK赛没有rebuy服务费 只记录积分流水
				balance_log($card, $explain, $localtime,intval('-'.$sport_info['service_charge']));
				
			}elseif ($sport_info['type']=='time_trial') {//计时赛
				//不是pk赛的话奖池增加数直接等于比赛的积分消耗
				jackpot_add($sport_id,  $sport_info['deduction']);
				balance_log($card, $explain, $localtime,intval('-'.$sport_info['service_charge']));
			}elseif ($sport_info['type']=='no_time_trial'){//非计时赛
				//不是pk赛的话奖池增加数直接等于比赛的积分消耗
				jackpot_add($sport_id,  $sport_info['deduction']);
				balance_log($card, $explain, $localtime,intval('-'.$sport_info['service_charge']));
				balance_log("", $explain, $localtime,intval('-'.$sport_info['service_charge']),"服务费","非计时赛",$sport_id);
				
			}
			//++++++++++++++++++++++++++++++++
			//成功rebuy 比赛人次减少一个
			sport_update($sport_id, array(
			"people_number"=>$sport_info['people_number']-1
			));
			$buy_member_info = member_get(array($card),'card');
			$r_member_info = member_get(array($r_card),'card');
			include template('sport_rebuy_print');
		}else {
			s('失败','?action=sport_entry&todo=entry&do=entry');
		}
		break;
	case 'rebuy':
		$card		= dzmc_revise_card(( isset($_GET['card']) ? $_GET['card'] : '' ));
		$entry_id	= intval( isset($_GET['entry_id']) ? $_GET['entry_id'] : '' );
		$sport_id	= intval( isset($_GET['sport_id']) ? $_GET['sport_id'] : '' );
		
		
		
		if (empty($card)){s('没有得到读卡',$tiaohui);}
		if (empty($entry_id)){s('没有得到参赛编号',$tiaohui);}
		if (empty($sport_id)){s('没有得到赛事编号',$tiaohui);}
		
		$member_info	= member_get(array($card),'card');
		$entry_info		= entry_get(array($entry_id),'id');
		$sport_info		= sport_get(array($sport_id),'id');
		
		$card = $member_info['card'];
		//比赛人次小于一就不能再次报名了.等于说没有名额了嘛
		if ($sport_info['people_number']<1) s('参赛人次达到上限','?action=sport_withdraw&todo=withdraw');
		//计算赛事费用
		if($sport_info['type']=='time_trial'){//计时赛
			$sportcharge = $sport_info['deduction'];
		}else {//非计时赛
			$sportcharge = $sport_info['deduction']+$sport_info['service_charge'];
		}
		include template('sport_rebuy');
		/* if (!$sport_info['rebuy']){s('本赛事不允许再次买入',$tiaohui);}
		
		if ($sport_info['type']=='time_trial'){
			//计算//rebuy 服务费
			$serviceCharge = $sport_info['deduction'];
		}else {
			$serviceCharge = $sport_info['deduction']+$sport_info['service_charge'];
		}

		//相应积分是否够扣除
		if ($entry_info['payment_type']=='jiangli_jifen'){
			if ($member_info['jiangli_jifen']<$serviceCharge){
				s("需要[ $serviceCharge ]奖励积分,奖励积分不够,无法完成rebuy",$tiaohui);
				
			}
			balance_reduce($card, $serviceCharge,$entry_info['payment_type']);
			$type="奖励积分";
		}elseif ($entry_info['payment_type']=='balance') {
			if ($member_info['balance']<$serviceCharge){
				s("需要[ $serviceCharge ]积分,积分不够,无法完成rebuy",$tiaohui);
			}
			balance_reduce($card, $serviceCharge,$entry_info['payment_type']);
			$type="积分";
		}
		balance_log($card, "rebuy赛事[".$sport_info['name']."]:rebuy扣除:$type,".$serviceCharge."分", $localtime);
		$result = entry_update($entry_id, array(
			"number"=>$entry_info['number']+1
		));
		jackpot_add($sport_id,  $sport_info['deduction']);//增加奖池
		$member_info	= member_get(array($card),'card'); */
		//include template('sport_rebuy_print');
		break;
	case 'doprize':
		$ranking	= htmlspecialchars( isset($_POST['ranking']) ? $_POST['ranking'] : '' );
		$card		= dzmc_revise_card(( isset($_POST['card']) ? $_POST['card'] : '' ));
		$sport_id		= intval( isset($_POST['sport_id']) ? $_POST['sport_id'] : '' );
		$name		= htmlspecialchars( isset($_POST['name']) ? $_POST['name'] : '' );
		$jiangli_jifen	= intval( isset($_POST['jiangli_jifen']) ? $_POST['jiangli_jifen'] : '' );
		
		$sportinfo		= sport_get(array($sport_id),'id');
		if ($jiangli_jifen>$sportinfo['jackpot'])e('奖池不够颁奖');
		
		$member_info = member_get(array($card),'card');
		if (empty($member_info))e('无会员信息');
		$card = $member_info['card'];
		$result = prize_add(array(
			'card'=>$card,
			'sport_id'=>$sport_id,
			'ranking'=>$ranking,
			'name'=>$name,
			'jiangli_jifen'=>$jiangli_jifen,
			'add_date'=>$localtime
		));
		if ($result) {
			balance_add($card, $jiangli_jifen,'jiangli_jifen');//奖励积分
			$money = intval($jiangli_jifen);
			balance_log($card, "赛事奖励-增加奖励积分:".$jiangli_jifen."奖项:$ranking", $localtime,$money);
			jackpot_reduce($sport_id, $jiangli_jifen);//减少奖池
			$member_info = member_get(array($card),'card');
			include template('sport_prize_print');
		}else {
			s('添加失败','?action=sport_list&todo=prize&id='.$sport_id);
		}
		break;
	case 'prize':
		$sport_id	= intval( isset($_GET['id']) ? $_GET['id'] : '' );
		if (empty($sport_id)){e("没有获取到ID");}
		$sql = "SELECT * FROM  `{$tablepre}prize` WHERE  `sport_id` =$sport_id";
			$result		= $db->query($sql);
			$infoList	= array();
			while($arr	= $db->fetch_array($result)){
				$arr['add_date']= gmdate('Y-n-j H:i:s',$arr['add_date']);
				//$arr['sport'] = sport_get(array($arr['sport_id']),"id");
		        $infoList[]	= $arr;
			}
		$sport_info = sport_get(array($sport_id),"id");
		include template('sport_prize');
		break;
	case 'dowithdraw'://执行退赛
		
		$card		= dzmc_revise_card(( isset($_GET['card']) ? $_GET['card'] : '' ));
		$entry_id	= intval( isset($_GET['entry_id']) ? $_GET['entry_id'] : '' );
		$sport_id	= intval( isset($_GET['sport_id']) ? $_GET['sport_id'] : '' );
		if (empty($card)){s('没有得到读卡',"?action=sport_withdraw&todo=withdraw&card=$card");}
		if (empty($entry_id)){s('没有得到参赛编号',"?action=sport_withdraw&todo=withdraw&card=$card");}
		if (empty($sport_id)){s('没有得到赛事编号',"?action=sport_withdraw&todo=withdraw&card=$card");}
		
		$tiaohui = "?action=sport_withdraw&todo=withdraw&card=$card";
		
		$member_info	= member_get(array($card),'card');
		$entry_info		= entry_get(array($entry_id),'id');
		$sport_info		= sport_get(array($sport_id),'id');
				
		switch ($sport_info['type']) {
			case 'time_trial'://计时赛//计算记时服务费
				$serviceCharge = $localtime - $entry_info['add_date'];
				$serviceCharge = $serviceCharge/($sport_info['service_charge_time']*60);
				$serviceCharge = ceil($serviceCharge)*$sport_info['service_charge'];
			break;
			case "no_time_trial"://非计时赛
				//非计时赛的退赛是完全退回积分的.所以服务费为 积分消耗+服务费
				$serviceCharge = $sport_info['deduction']+$sport_info['service_charge'];
			break;
			case "pk_trial"://PK赛
				$serviceCharge = '';//PK赛退赛直接发奖,不用计算服务费
			break;
			
			default:
				s("找不到赛事类型",$tiaohui);
			break;
		}
		//计时赛才进行积分计算 扣除根据时间计算的服务费
		if ($sport_info['type']=='time_trial'){
			//相应积分是否够扣除
			if ($entry_info['payment_type']=='jiangli_jifen'){
				if ($member_info['jiangli_jifen']<$serviceCharge){
					s("需要[ $serviceCharge ]奖励积分,奖励积分不够,无法完成退赛",$tiaohui);
					
				}
				balance_reduce($card, $serviceCharge,$entry_info['payment_type']);
				$type="奖励积分";
			}elseif ($entry_info['payment_type']=='balance') {
				if ($member_info['balance']<$serviceCharge){
					s("需要[ $serviceCharge ]积分,积分不够,无法完成退赛",$tiaohui);
				}
				balance_reduce($card, $serviceCharge,$entry_info['payment_type']);
				$type="积分";
			}
		}
		 $result = entry_update($entry_id, array(
			"status"=>"已退赛",
			"exit_time"=>$localtime
		));
		if ($result){
			if ($sport_info['type']=='time_trial'){//计时赛
				$money = intval("-".$serviceCharge);
				balance_log($card, "退出赛事[".$sport_info['name']."]:扣除服务费:$type,".$serviceCharge."分", $localtime,$money,"服务费","计时赛",$sport_id);
				include template('sport_withdraw_print');
			}elseif ($sport_info['type']=='no_time_trial'){//非计时赛
				//参赛次数大于1表示rebuy过了,rebuy就是已经上场了,上场过的用户是不允许退赛的!
				if ($entry_info['number']>1) s("已经rebuy过了,无法完成退赛",$tiaohui);
				//如果比赛颁奖过了,奖池不够了,是不能退回积分的
				if ($sport_info['jackpot']<$sport_info['deduction']) s("奖池不够,无法完成退赛",$tiaohui);
				//扣掉奖池里的积分
				jackpot_reduce($sport_info['id'], $sport_info['deduction']);
				
				//把赛事退回的积分只能退回到奖励积分中
				balance_add($card, $serviceCharge,"jiangli_jifen");
				$money = intval("+".$sport_info['service_charge']);//赚到手的服务费给退回去了
				balance_log($card, "退出赛事[".$sport_info['name']."]:退还".$serviceCharge."积分到奖励积分账户", $localtime,$money,"服务费","非计时赛",$sport_id);
				include template('sport_withdraw_print');
				//echo "把积分退回去,稍等一下做,可以先用积分变动!";
			}elseif ($sport_info['type']=='pk_trial'){//PK赛
				//直接去颁奖
				echo '<script type="text/javascript">location.href="?action=sport_list&todo=prize&card='.$card.'&id='.$sport_id.'";</script>';
			}
		}else {
			s("退赛失败",$tiaohui);
		}
		
		break;
	case 'withdraw'://会员赛事记录页面
		$card		= dzmc_revise_card(( isset($_REQUEST['card']) ? $_REQUEST['card'] : ''));
		$infoList	= array();
		if (!empty($card)){
			$member_info = member_get(array($card),'card');
			$card = isset($member_info['card'])?$member_info['card']:0;
			$sql = "SELECT * FROM  `{$tablepre}entry` WHERE `card` =$card ORDER BY  `add_date` DESC ";
			$result		= $db->query($sql);
			while($arr	= $db->fetch_array($result)){
				$arr['add_date']= gmdate('Y-n-j H:i:s',$arr['add_date']);
				$arr['sport'] = sport_get(array($arr['sport_id']),"id");
				$arr['member_info'] = member_get(array($arr['card']),"card");
		        $infoList[]	= $arr;
			}
			//$infoList = entry_list(0, 100," `card` = $card AND status = '已入赛' ");
			/*echo '<<pre>';
			print_r($infoList);exit();*/
		}else {
			$member_info = '';
			$page   = intval( isset($_GET['page']) ? $_GET['page'] : 1 );
			$perpage = intval( isset($_GET['perpage']) ? $_GET['perpage'] : 20 );
			if($page > 0){
				$startlimit = ($page - 1) * $perpage;
			}else{
				$startlimit = 0;
			}
			$page_array = array();
			$total		= entry_total();
			$page_control = multipage($total,$perpage,$page);
			$sql = "SELECT * FROM  `{$tablepre}entry` ORDER BY add_date DESC LIMIT $startlimit , $perpage";
			$result		= $db->query($sql);
			while($arr	= $db->fetch_array($result)){
				$arr['add_date']= gmdate('Y-n-j H:i:s',$arr['add_date']);
				$arr['sport'] = sport_get(array($arr['sport_id']),"id");
				$arr['member_info'] = member_get(array($arr['card']),"card");
		        $infoList[]	= $arr;
			}	
		}
		include template('sport_withdraw');
		break;
	case 'save_entry'://保存报名信息
		$card		= dzmc_revise_card(( isset($_REQUEST['card']) ? $_REQUEST['card'] : '' ));
		$sport_id   = isset($_REQUEST['sport_id']) ? $_REQUEST['sport_id'] : '';
		$payment_type   = isset($_POST['payment_type']) ? $_POST['payment_type'] : '';
		$sportcharge   = isset($_POST['sportcharge']) ? $_POST['sportcharge'] : '';
		
		if (empty($card)||empty($sport_id))s('会员id未得到','?action=sport_entry&todo=entry&do=entry');
		$member_info = member_get(array($card),'card');
		$sportinfo = sport_get(array($sport_id),'id');
		$card = $member_info['card'];
		
		//计算当前正在比赛的人数
		//$shangxian = entry_total(" `sport_id`=".$sport_id." AND `status`='已入赛'");
		
		
		
		
		if($sportinfo['type']=='time_trial'){//计时赛
				$sportcharge = $sportinfo['deduction'];
		}elseif ($sportinfo['type']=='no_time_trial') {//非计时赛
				$sportcharge = $sportinfo['deduction']+$sportinfo['service_charge'];
		}elseif ($sportinfo['type']=='pk_trial') {//PK赛
			$sportcharge =intval($sportcharge);
		}
		if((($member_info['balance'])+($member_info['jiangli_jifen']))<($sportcharge)){
			s('所有积分不够,无法完成报名','?action=sport_entry&todo=doentry&do=entry&card='.$card."&id=".$sport_id);
		}
/*		if(payment_type=='jiangli_jifen'){
				if(jiangli_jifen>sportcharge){
					form.submit();
					return true;
				}else{
					cha = sportcharge-jiangli_jifen;
					if(confirm('奖励积分不够,剩余'+cha+"分,从积分中扣除")){
						form.submit();
					}return false;
				}
			}else{
				if(balance>sportcharge){
					form.submit();
					return true;
				}else{
					cha = sportcharge-balance;
					if(confirm('积分不够,剩余'+cha+"分,从奖励积分中扣除")){
						form.submit();
					}return false;
				}
			}*/
		if ($payment_type=='jiangli_jifen'){
			if ($member_info['jiangli_jifen']>$sportcharge) {
				balance_reduce($card, $sportcharge,$payment_type);//扣除积分;
				$money = intval("-".$sportcharge);
				$text_ ="扣除参赛费,奖励积分:".$sportcharge."分";
				$explain = "报名赛事[ ".$sportinfo['name']." ]:".$text_.",  ";
			}else {
				$cha = $sportcharge-$member_info['jiangli_jifen'];
				balance_reduce($card, $member_info['jiangli_jifen'],'jiangli_jifen');
				balance_reduce($card, $cha,'balance');
				$text_ = "扣除参赛费,奖励积分:".$member_info['jiangli_jifen']."分,积分:".$cha."分";
				$money = intval("-".($member_info['jiangli_jifen']+$cha));
				$explain = "报名赛事[ ".$sportinfo['name']." ]:".$text_.",  ";
			}
		}elseif ($payment_type=="balance") {
			if ($member_info['balance']>$sportcharge) {
				balance_reduce($card, $sportcharge,$payment_type);//扣除积分;
				$money = intval("-".$sportcharge);
				$text_ = "扣除参赛费:,积分:".$sportcharge."分";
				$explain = "报名赛事[ ".$sportinfo['name']." ]:".$text_.",  ";
			}else {
				$cha = $sportcharge-$member_info['balance'];
				balance_reduce($card, $member_info['balance'],'balance');
				balance_reduce($card, $cha,'jiangli_jifen');
				$money = intval("-".($member_info['balance']+$cha));
				$text_ = "扣除参赛费,积分:".$member_info['balance']."分,奖励积分:".$cha."分";
				$explain = "报名赛事[ ".$sportinfo['name']." ]:".$text_.",  ";
			}
		}
		//记录到报名列表
		$result = entry_add(array(
			'card'=>$card,
			'sport_id'=>$sport_id,
			'payment_type'=>$payment_type,
			'add_date'=>$localtime
		));
		if (!$result) {
			s('失败','?action=sport_entry&todo=entry&do=entry');
		}
		sport_update($sport_id, array(
			"entry_number"=>$sportinfo['entry_number']+1
		));
		if ($sportinfo['type']=='no_time_trial') {
			//非计时赛 记录服务费
			balance_log($card, $explain, $localtime,intval('-'.$sportinfo['service_charge']),"服务费","非计时赛",$sport_id);
			jackpot_add($sport_id,  $sportinfo['deduction']);//增加奖池
		}elseif ($sportinfo['type']=='time_trial'){
			balance_log($card, $explain, $localtime,$money,"服务费","计时赛",$sport_id);
			jackpot_add($sport_id,  $sportinfo['deduction']);//增加奖池
		}elseif ($sportinfo['type']=='pk_trial') {
			balance_log($card, $explain, $localtime,$money,"","PK赛");
			jackpot_add($sport_id,$sportcharge);//增加奖池
		}
		//成功报名比赛人次减少一个
		sport_update($sport_id, array(
		"people_number"=>$sportinfo['people_number']-1
		));
		$member_info = member_get(array($card),'card');
		include template('sport_save_entry_print');
		
		break;
	case 'doentry'://报名页面
		$card		=dzmc_revise_card(( isset($_REQUEST['card']) ? $_REQUEST['card'] : '' ));
		$id   = isset($_GET['id']) ? $_GET['id'] : '';
		if (!empty($card)){
			$member_info = member_get(array($card),'card');
		}else {
			$member_info = '';
		}
		if (!empty($id)){
			$sportinfo = sport_get(array($id),'id');
			//计算服务费
			if($sportinfo['type']=='time_trial'){//计时赛
				$sportcharge = $sportinfo['deduction'];
			}else {//非计时赛
				$sportcharge = $sportinfo['deduction']+$sportinfo['service_charge'];
			}
			
		}else {
			$sportinfo = '';
			$sportcharge = 0;
		}
		if ($sportinfo['status']=="已结束")e('比赛已结束,不能报名!');
		//比赛人次小于一就不能再次报名了.等于说没有名额了嘛
		if ($sportinfo['people_number']<1)s('参赛人次达到上限','?action=sport_entry&todo=entry&do=entry');
		
		//if ($localtime>$sportinfo['stop_entry_time'])e('超出报名时间,停止报名');
		
		/*$result = entry_add(array(
			'name'=>$name,
			'start_time'=>strtotime($start_time) + 8 * 3600,
			'type'=>$type,
			'deduction'=>$deduction,
			'service_charge_time'=>$service_charge_time,
			'service_charge'=>$service_charge,
			'people_number'=>$people_number,
			'rebuy'=>$rebuy,
			'entry_number'=>$entry_number,
			'zhangmang_time'=>$zhangmang_time,
			'stop_entry_time'=>strtotime($stop_entry_time) + 8 * 3600,
			'rest_time'=>$rest_time,
			'scoreboard'=>$scoreboard,
			'MaxBLNum'=>$MaxBLNum,
			'seating'=>$seating,
			'remark'=>$remark,
		
			'add_date'=>$localtime
		));*/
		//print_r($sportinfo);
		include template('sport_doentry');	
		break;
	case 'entry':
		$page   = intval( isset($_GET['page']) ? $_GET['page'] : 1 );
		$perpage = intval( isset($_GET['perpage']) ? $_GET['perpage'] : 20 );
		if($page > 0){
			$startlimit = ($page - 1) * $perpage;
		}else{
			$startlimit = 0;
		}
		$page_array = array();
		$total		= sport_total();
		$page_control = multipage($total,$perpage,$page);
		$listArr	= sport_list($startlimit, $perpage);	
		include template('sport_list');
		break;
	case 'jiesai':
		$id   = isset($_GET['id']) ? $_GET['id'] : '';
		if (empty($id)) {
			e('ID不存在!');
		}
		if (sport_update($id, array(
			'status'=>"已结束"
		))) {
			s('操作成功','?action=sport_list&todo=list');
		}
		break;
	case 'kaisai':
		$id   = isset($_GET['id']) ? $_GET['id'] : '';
		if (empty($id)) {
			e('ID不存在!');
		}
		if (sport_update($id, array(
			'status'=>"竞赛中"
		))) {
			s('开赛成功','?action=sport_list&todo=list');
		}
		break;
	case 'search':
		$keywork	= htmlspecialchars( isset($_REQUEST['keywork']) ? $_REQUEST['keywork'] : '' );
		
		$page   = intval( isset($_GET['page']) ? $_GET['page'] : 1 );
		$perpage = intval( isset($_GET['perpage']) ? $_GET['perpage'] : 20 );
		
		if($page > 0){
			$startlimit = ($page - 1) * $perpage;
		}else{
			$startlimit = 0;
		}
		$where = "name LIKE '%$keywork%' ";
		$total		= sport_total($where);
		$listArr =sport_list($startlimit, $perpage,$where);
		$page_control = multipage($total,$perpage,$page);
		include template('sport_list');		
		break;
	case 'del':
		$id   = isset($_GET['id']) ? $_GET['id'] : '';
		if (empty($id)) {
			e('ID不存在!');
		}
		//删除赛事信息
		if (sport_del(array($id))) {
			//删除参赛退赛记录
			entry_del(array($id),'sport_id');
			//删除颁奖记录
			prize_del(array($id),'sport_id');
			s('删除成功','?action=sport_list&todo=list');
		}
		break;
	case 'saveadd'://保存添加赛事
		$name		= htmlspecialchars( isset($_POST['name']) ? $_POST['name'] : '' );
		$start_time	= htmlspecialchars( isset($_POST['start_time']) ? $_POST['start_time'] : 0 );
		$type		= htmlspecialchars( isset($_POST['type']) ? $_POST['type'] : 0 );
		$deduction	= intval( isset($_POST['deduction']) ? $_POST['deduction'] : 0 );
		$service_charge_time	= intval( isset($_POST['service_charge_time']) ? $_POST['service_charge_time'] : 0 );
		$service_charge	= intval( isset($_POST['service_charge']) ? $_POST['service_charge'] : 0 );
		$people_number	= intval( isset($_POST['people_number']) ? $_POST['people_number'] : 0 );
		$rebuy	= intval( isset($_POST['rebuy']) ? $_POST['rebuy'] : 0 );
		//$entry_number	= intval( isset($_POST['entry_number']) ? $_POST['entry_number'] : '' );/*--去掉参赛次数--用新的 参赛人次逻辑代替 newwell 20131125*/
		$zhangmang_time	= ( isset($_POST['zhangmang_time']) ? $_POST['zhangmang_time'] : '' );
		$stop_entry_time= ( isset($_POST['stop_entry_time']) ? $_POST['stop_entry_time'] : 0 );
		$rest_time	= intval( isset($_POST['rest_time']) ? $_POST['rest_time'] : 0 );
		$scoreboard	= htmlspecialchars( isset($_POST['scoreboard']) ? $_POST['scoreboard'] : 0 );
		$MaxBLNum	= intval( isset($_POST['MaxBLNum']) ? $_POST['MaxBLNum'] : 0 );
		$seating	= intval( isset($_POST['seating']) ? $_POST['seating'] : 0 );
		$deingcoholr_id	= intval( isset($_POST['deingcoholr_id']) ? $_POST['deingcoholr_id'] : 0 );
		$remark	= htmlspecialchars( isset($_POST['remark']) ? $_POST['remark'] : '' );
		
		if ($type=="time_trial"){//计时赛必须填写服务费分钟
			if (empty($service_charge_time))e('计时赛必须填写服务费扣费分钟');
		};
		if (empty($name))e("赛事名称不能为空!");
		if (empty($start_time))e("比赛开始时间必填");
		if (empty($stop_entry_time))e("截至报名时间必填");
		if (strtotime($stop_entry_time)<=strtotime($start_time))e("截至报名不应比比赛开始开始时间小");
		
		$result = sport_add(array(
			'name'=>$name,
			'start_time'=>strtotime($start_time) + 8 * 3600,
			'type'=>$type,
			'deduction'=>$deduction,
			'service_charge_time'=>$service_charge_time,
			'service_charge'=>$service_charge,
			'people_number'=>$people_number,
			'rebuy'=>$rebuy,
			'entry_number'=>0,  /*--当前报名的人次  newwell 20131208*/
			'zhangmang_time'=>$zhangmang_time,
			'stop_entry_time'=>strtotime($stop_entry_time) + 8 * 3600,
			'rest_time'=>$rest_time,
			'scoreboard'=>$scoreboard,
			'MaxBLNum'=>$MaxBLNum,
			'seating'=>$seating,
			'deingcoholr_id'=>$deingcoholr_id,
			'remark'=>$remark,
		
			'add_date'=>$localtime
		));
		if ($result) {
			s('添加成功','?action=sport_add&todo=add');
		}else {
			e('添加失败');
		}
		break;
	case 'add':
		$where = "`type`='发牌员' AND `activate`=1";
		$deingcoholrList = staff_list(0, 1000,$where);
		include template('sport_add');
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
		$total		= sport_total();
		$page_control = multipage($total,$perpage,$page);
		$listArr	= sport_list($startlimit, $perpage);	
		include template('sport_list');
	break;
	
	default:
		e('参数不正确');
	break;
}