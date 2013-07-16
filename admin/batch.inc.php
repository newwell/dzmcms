<?php
if (! defined ( 'IN_SITE' ))exit ( 'Access Denied' );
CheckAccess ();
global $act, $todo, $tablepre, $db;
admin_priv ( $act ['action'] );
require_once 'include/func/shorturl.func.php';
switch ($todo) {
	case 'doin' :
		if (isset ( $_FILES ['xls'] )) {
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
						//print_r($return);exit();
						$row = count ( $return ['Sheet1'] );
						
						for($i = 1; $i < $row; $i ++) {
							$sql = "insert into {$tablepre}urls
							(`url`,`annotation`,`times`,`add_date`)values
							('" . recode ( $return ['Sheet1'] [$i] [0] ) . "',
							'" . htmlspecialchars( recode ( $return ['Sheet1'] [$i] [1] ) ) . "',
							'" . intval(recode ( $return ['Sheet1'] [$i] [2] )) . "',
							$localtime);";
							$db->query ( $sql );
						}						
					}
					
					@unlink ( $url );
					s ( '导入数据成功', '?action=batch_in&todo=list' );
				}
			}
		} else {
			e ( '请选择一个XLS文件' );
		}
		break;
	case 'in' :
		include template ( 'batch_in' );
		break;
	case 'doout' :
		$start = strtotime ( isset ( $_POST ['start'] ) ? $_POST ['start'] : '' );
		$end = strtotime ( isset ( $_POST ['end'] ) ? $_POST ['end'] : '' );
		
		if (empty ( $start ) || empty ( $end ))
			e ( '选择一个时间' );
		
		$title = array ("短链接", "原链接", "添加时间", "备注", "被浏览次数" );
		$Data = array ();
		$Data [] = array_values ( $title );
		require_once 'include/excel_class.php';
		
		$sql = "SELECT * FROM  `{$tablepre}urls` WHERE add_date<$end AND add_date>$start";
		$query = $db->query ( $sql );
		while ( $rArr = $db->fetch_array ( $query ) ) {
			$rArr ['add_date'] = gmdate ( 'Y.n.j', $rArr ['add_date'] );
			if (! empty ( $rArr ['alias'] )) {
				$rArr ['surl'] = $setting_siteurl . $rArr ['alias'];
			} else {
				$rArr ['surl'] = $setting_siteurl . base_convert ( $rArr ['id'], 10, 36 );
			}
			;
			$Data [] = array ($rArr ['surl'], $rArr ['url'], $rArr ['add_date'], $rArr ['annotation'], $rArr ['times'] );
		}
		$start = gmdate ( 'Y_n_j', $start );
		$end = gmdate ( 'Y_n_j', $end );
		Create_Excel_File ( iconv ( "UTF-8", "gb2312", $start . "-" . $end . "所有短链接.xls" ), $Data );
		unset ( $start, $end, $Data, $sql, $rArr );
		break;
	case 'out' :
		include template ( 'batch_out' );
		break;
	case 'saveadd' :
		$urls = isset ( $_POST ['urls'] ) ? $_POST ['urls'] : '';
		$urls = str_replace ( "\n", '<---dazan-->', $urls );
		$urls = explode ( '<---dazan-->', $urls );
		$urls = array_filter ( $urls );
		$log = '值为为非链接的有:<br/>';
		$i = 0;
		foreach ( $urls as $key => $value ) {
			$value = trim ( $value );
			if (! filter_var ( $value, FILTER_VALIDATE_URL )) {
				$pai = $key + 1;
				$log .= "第" . $pai . "排<br/>";
			} else {
				shorturl_add ( array ('url' => $value, 'add_date' => $localtime ) );
				$i ++;
			}
		}
		echo '成功添加' . $i . '条!<br/>';
		echo $log;
		break;
	case 'add' :
		include template ( 'batch_add' );
		break;
	case 'list' :
	default :
		$page = intval ( isset ( $_GET ['page'] ) ? $_GET ['page'] : 1 );
		$perpage = intval ( isset ( $_GET ['perpage'] ) ? $_GET ['perpage'] : 20 );
		if ($page > 0) {
			$startlimit = ($page - 1) * $perpage;
		} else {
			$startlimit = 0;
		}
		$page_array = array ();
		$total = shorturl_total ();
		$page_control = multipage ( $total, $perpage, $page );
		$durlArr = shorturl_list ( $startlimit, $perpage );
		include template ( 'shorturl_list' );
		break;
}