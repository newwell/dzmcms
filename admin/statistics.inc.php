<?php
if(!defined('IN_SITE')) exit('Access Denied');
CheckAccess();
global $act,$todo,$tablepre,$db;
admin_priv($act['action']);
require_once 'include/f/balance.f.php';
require_once 'include/f/member.f.php';
switch ($todo) {
	case 'PresentExp':
		$card		= dzmc_revise_card(( isset($_REQUEST['card']) ? $_REQUEST['card'] : '' ));
		if (!empty($card)){
			$member_info = member_get(array($card),'card');
			$sql = "SELECT * FROM  `{$tablepre}balance_log` WHERE  `card` ='".$member_info['card']."' AND `type`='积分赠送' ORDER BY  `id` DESC ";
			$result		= $db->query($sql);
			while($arr	= $db->fetch_array($result)){
				if (empty($arr['type'])){
					$arr['add_date']= gmdate('Y-n-j H:m:s',$arr['add_date']);
				}else{
					$arr['add_date']= date('Y-n-j H:m:s',$arr['add_date']);
				}
		
				$arr['member_info'] = member_get(array($arr['card']),'card');
				//$arr['sport'] = sport_get(array($arr['sport_id']),"id");
				$infoList[]	= $arr;
			}
			//$infoList = entry_list(0, 100," `card` = $card AND status = '已入赛' ");
			/*echo '<<pre>';
			 print_r($infoList);exit();*/
		}else {
			$member_info= '';
			$sql = "SELECT * FROM  `{$tablepre}balance_log` WHERE `type`='积分赠送' ORDER BY  `id` DESC ";
			$result		= $db->query($sql);
			while($arr	= $db->fetch_array($result)){
				if (empty($arr['type'])){
					$arr['add_date']= gmdate('Y-n-j H:m:s',$arr['add_date']);
				}else{
					$arr['add_date']= date('Y-n-j H:m:s',$arr['add_date']);
				}
				$arr['member_info'] = member_get(array($arr['card']),'card');
				$infoList[]	= $arr;
			}
		}
		include template('statistics_jifenlog_list');
		break;
	case 'jifenlog':
		$card		= dzmc_revise_card(( isset($_REQUEST['card']) ? $_REQUEST['card'] : '' ));
		if (!empty($card)){
			$member_info = member_get(array($card),'card');
			$sql = "SELECT * FROM  `{$tablepre}balance_log` WHERE  `card` ='".$member_info['card']."' AND `type`='积分变动' ORDER BY  `id` DESC ";
			$result		= $db->query($sql);
			while($arr	= $db->fetch_array($result)){
				if (empty($arr['type'])){
					$arr['add_date']= gmdate('Y-n-j H:m:s',$arr['add_date']);
				}else{
					$arr['add_date']= date('Y-n-j H:m:s',$arr['add_date']);
				}
				
				$arr['member_info'] = member_get(array($arr['card']),'card');
				//$arr['sport'] = sport_get(array($arr['sport_id']),"id");
		        $infoList[]	= $arr;
			}
			//$infoList = entry_list(0, 100," `card` = $card AND status = '已入赛' ");
			/*echo '<<pre>';
			print_r($infoList);exit();*/
		}else {
			$member_info= '';
			$sql = "SELECT * FROM  `{$tablepre}balance_log` WHERE `type`='积分变动' ORDER BY  `id` DESC ";
			$result		= $db->query($sql);
			while($arr	= $db->fetch_array($result)){
				if (empty($arr['type'])){
					$arr['add_date']= gmdate('Y-n-j H:m:s',$arr['add_date']);
				}else{
					$arr['add_date']= date('Y-n-j H:m:s',$arr['add_date']);
				}
				$arr['member_info'] = member_get(array($arr['card']),'card');
		        $infoList[]	= $arr;
			}
		}
		include template('statistics_jifenlog_list');
		break;
	case 'balance_change':
		$card		= dzmc_revise_card(( isset($_REQUEST['card']) ? $_REQUEST['card'] : '' ));
		if (!empty($card)){
			$member_info = member_get(array($card),'card');
			$sql = "SELECT * FROM  `{$tablepre}balance_log` WHERE  `card` ='".$member_info['card']."' ORDER BY  `add_date` DESC ";
			//exit($sql);
			$result		= $db->query($sql);
			while($arr	= $db->fetch_array($result)){if (empty($arr['type'])){
					$arr['add_date']= gmdate('Y-n-j H:m:s',$arr['add_date']);
				}else{
					$arr['add_date']= date('Y-n-j H:m:s',$arr['add_date']);
				}
				$arr['member_info'] = member_get(array($arr['card']),'card');
		        $infoList[]	= $arr;
			}
			//$infoList = entry_list(0, 100," `card` = $card AND status = '已入赛' ");
			/*echo '<<pre>';
			print_r($infoList);exit();*/
		}else {
			$member_info = '';
			/*$page   = intval( isset($_GET['page']) ? $_GET['page'] : 1 );
			$perpage = intval( isset($_GET['perpage']) ? $_GET['perpage'] : 20 );
			if($page > 0){
				$startlimit = ($page - 1) * $perpage;
			}else{
				$startlimit = 0;
			}
			$page_array = array();
			$total		= balance_log_total();
			$page_control = multipage($total,$perpage,$page);
			$sql = "SELECT * FROM  `{$tablepre}entry` ORDER BY add_date DESC LIMIT $startlimit , $perpage";
			$result		= $db->query($sql);
			while($arr	= $db->fetch_array($result)){
				$arr['add_date']= gmdate('Y-n-j H:m:s',$arr['add_date']);
				//$arr['sport'] = sport_get(array($arr['sport_id']),"id");
		        $infoList[]	= $arr;
			}*/
			//$infoList	= entry_list($startlimit, $perpage);	
		}
		/*$page   = intval( isset($_GET['page']) ? $_GET['page'] : 1 );
		$perpage = intval( isset($_GET['perpage']) ? $_GET['perpage'] : 20 );
		if($page > 0){
			$startlimit = ($page - 1) * $perpage;
		}else{
			$startlimit = 0;
		}
		$page_array = array();
		$total		= balance_log_total();
		$page_control = multipage($total,$perpage,$page);
		$infoList	= balance_log_list($startlimit, $perpage);	*/
		include template('statistics_balance_change_list');
	break;
	
	default:
		e('参数不正确');
	break;
}