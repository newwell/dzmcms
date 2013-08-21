<?php
if(!defined('IN_SITE')) exit('Access Denied');
CheckAccess();
global $act,$todo,$tablepre,$db,$do,$localtime;
admin_priv($act['action']);
require_once 'include/f/sport.f.php';
require_once 'include/f/member.f.php';
require_once 'include/f/balance.f.php';
switch ($todo) {
	case 'doprize':
		$ranking	= htmlspecialchars( isset($_POST['ranking']) ? $_POST['ranking'] : '' );
		$card		= intval( isset($_POST['card']) ? $_POST['card'] : '' );
		$sport_id		= intval( isset($_POST['sport_id']) ? $_POST['sport_id'] : '' );
		$name		= htmlspecialchars( isset($_POST['name']) ? $_POST['name'] : '' );
		$jiangli_jifen	= intval( isset($_POST['jiangli_jifen']) ? $_POST['jiangli_jifen'] : '' );
		
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
			jackpot_reduce($sport_id, $jiangli_jifen);//增加奖池
			$member_info = member_get(array($card),'card');
			$sportinfo		= sport_get(array($sport_id),'id');
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
				$arr['add_date']= gmdate('Y-n-j H:m:s',$arr['add_date']);
				//$arr['sport'] = sport_get(array($arr['sport_id']),"id");
		        $infoList[]	= $arr;
			}
		$sport_info = sport_get(array($sport_id),"id");
		//print_r($sport_info);exit;
		include template('sport_prize');
		break;
	case 'dowithdraw':
		
		$card		= intval( isset($_GET['card']) ? $_GET['card'] : '' );
		$entry_id	= intval( isset($_GET['entry_id']) ? $_GET['entry_id'] : '' );
		$sport_id	= intval( isset($_GET['sport_id']) ? $_GET['sport_id'] : '' );
		if (empty($card)){s('没有得到读卡',"?action=sport_withdraw&todo=withdraw&card=$card");}
		if (empty($entry_id)){s('没有得到参赛编号',"?action=sport_withdraw&todo=withdraw&card=$card");}
		if (empty($sport_id)){s('没有得到赛事编号',"?action=sport_withdraw&todo=withdraw&card=$card");}
		
		$tiaohui = "?action=sport_withdraw&todo=withdraw&card=$card";
		
		$member_info	= member_get(array($card),'card');
		$entry_info		= entry_get(array($entry_id),'id');
		$sport_info		= sport_get(array($sport_id),'id');
		
		//计算服务费
		$serviceCharge = $localtime - $entry_info['add_date'];
		$serviceCharge = $serviceCharge/($sport_info['service_charge_time']*60);
		$serviceCharge = ceil($serviceCharge)*$sport_info['service_charge'];
		//相应积分是否够扣除
		if ($entry_info['payment_type']=='jiangli_jifen'){
			if ($member_info['jiangli_jifen']<$serviceCharge){
				s("需要[ $serviceCharge ]奖励积分,奖励积分不够,无法完成退赛",$tiaohui);
				
			}
			balance_reduce($card, $serviceCharge,$entry_info['payment_type']);
			$type="奖励积分";
		}elseif ($entry_info['payment_type']=='deduction') {
			if ($member_info['balance']<$serviceCharge){
				s("需要[ $serviceCharge ]积分,积分不够,无法完成退赛",$tiaohui);
			}
			balance_reduce($card, $serviceCharge,$entry_info['payment_type']);
			$type="积分";
		}
		$result = entry_update($entry_id, array(
			"status"=>"已退赛"
		));
		if ($result){
			s("退赛成功,扣除[ $serviceCharge ]$type",$tiaohui);
		}else {
			s("退赛失败",$tiaohui);
		}
		
		break;
	case 'withdraw':
		$card		= ( isset($_REQUEST['card']) ? $_REQUEST['card'] : '' );
		if (!empty($card)){
			$member_info = member_get(array($card),'card');
			$sql = "SELECT * FROM  `{$tablepre}entry` WHERE  `card` =$card AND STATUS =  '已入赛' ORDER BY  `add_date` DESC ";
			$result		= $db->query($sql);
			$infoList	= array();
			while($arr	= $db->fetch_array($result)){
				$arr['add_date']= gmdate('Y-n-j H:m:s',$arr['add_date']);
				$arr['sport'] = sport_get(array($arr['sport_id']),"id");
		        $infoList[]	= $arr;
			}
			//$infoList = entry_list(0, 100," `card` = $card AND status = '已入赛' ");
			/*echo '<<pre>';
			print_r($infoList);exit();*/
		}else {
			$member_info = '';
		}
		include template('sport_withdraw');
		break;
	case 'save_entry':
		$card		= ( isset($_REQUEST['card']) ? $_REQUEST['card'] : '' );
		$sport_id   = isset($_REQUEST['sport_id']) ? $_REQUEST['sport_id'] : '';
		$payment_type   = isset($_POST['payment_type']) ? $_POST['payment_type'] : '';
		
		if (empty($card)||empty($sport_id))s('会员id为得到','?action=sport_entry&todo=entry&do=entry');
		$member_info = member_get(array($card),'card');
		$sportinfo = sport_get(array($sport_id),'id');
		
		if ($payment_type=='jiangli_jifen'){
			if ($member_info['jiangli_jifen']<$sportinfo['deduction']){
				s('奖励积分不够,无法完成报名','?action=sport_entry&todo=doentry&do=entry&card='.$card."&id=".$sport_id);
			}
		}elseif ($payment_type=="balance") {
			if ($member_info['balance']<$sportinfo['deduction']){
				s('积分不够,无法完成报名','?action=sport_entry&todo=doentry&do=entry&card='.$card."&id=".$sport_id);
			}
		}
		
		$result = entry_add(array(
			'card'=>$card,
			'sport_id'=>$sport_id,
			'payment_type'=>$payment_type,
			'add_date'=>$localtime
		));
		if ($result) {
			balance_reduce($card, $sportinfo['deduction'],$payment_type);//扣除积分
			jackpot_add($sport_id,  $sportinfo['deduction']);//增加奖池
			$member_info = member_get(array($card),'card');
			include template('sport_save_entry_print');
		}else {
			s('添加失败','?action=sport_entry&todo=entry&do=entry');
		}
		break;
	case 'doentry':
		$card		= ( isset($_REQUEST['card']) ? $_REQUEST['card'] : '' );
		$id   = isset($_GET['id']) ? $_GET['id'] : '';
		if (!empty($card)){
			$member_info = member_get(array($card),'card');
		}else {
			$member_info = '';
		}
		if (!empty($id)){
			$sportinfo = sport_get(array($id),'id');
		}else {
			$sportinfo = '';
		}
		
		if ($sportinfo['status']=="已结束")e('比赛已结束,不能报名!');
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
		if (sport_del(array($id))) {
			s('删除成功','?action=sport_list&todo=list');
		}
		break;
	case 'saveadd':
		$name		= htmlspecialchars( isset($_POST['name']) ? $_POST['name'] : '' );
		$start_time	= htmlspecialchars( isset($_POST['start_time']) ? $_POST['start_time'] : 0 );
		$type		= htmlspecialchars( isset($_POST['type']) ? $_POST['type'] : 0 );
		$deduction	= intval( isset($_POST['deduction']) ? $_POST['deduction'] : 0 );
		$service_charge_time	= intval( isset($_POST['service_charge_time']) ? $_POST['service_charge_time'] : 0 );
		$service_charge	= intval( isset($_POST['service_charge']) ? $_POST['service_charge'] : 0 );
		$people_number	= intval( isset($_POST['people_number']) ? $_POST['people_number'] : 0 );
		$rebuy	= intval( isset($_POST['rebuy']) ? $_POST['rebuy'] : 0 );
		$entry_number	= intval( isset($_POST['entry_number']) ? $_POST['entry_number'] : '' );
		$zhangmang_time	= intval( isset($_POST['zhangmang_time']) ? $_POST['zhangmang_time'] : '' );
		$stop_entry_time= intval( isset($_POST['stop_entry_time']) ? $_POST['stop_entry_time'] : 0 );
		$rest_time	= intval( isset($_POST['rest_time']) ? $_POST['rest_time'] : 0 );
		$scoreboard	= htmlspecialchars( isset($_POST['scoreboard']) ? $_POST['scoreboard'] : 0 );
		$MaxBLNum	= intval( isset($_POST['MaxBLNum']) ? $_POST['MaxBLNum'] : 0 );
		$seating	= intval( isset($_POST['seating']) ? $_POST['seating'] : 0 );
		$remark	= htmlspecialchars( isset($_POST['remark']) ? $_POST['remark'] : '' );
		

		if (empty($name))e("赛事名称不能为空!");
		if (empty($start_time))e("比赛开始时间必填");
		if (empty($stop_entry_time))e("截至报名时间必填");
		if (($stop_entry_time)<=($start_time))e("截至报名不应比比赛开始开始时间小");
		
		$result = sport_add(array(
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
		));
		if ($result) {
			s('添加成功','?action=sport_add&todo=add');
		}else {
			e('添加失败');
		}
		break;
	case 'add':
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