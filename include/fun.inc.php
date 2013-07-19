<?php
/**
 * 网站错误提示信息处理函数
 *
 * @access  private
 *
 * @param   string      $msg        提示语句,lang数组索引
 * @param   string      $gourl      提示跳转的地址
 * @param   int         $back       是否自动返回上个页面/默认不跳转
 *
 * @return  null
 */
function e($msg,$gourl='',$back=1)
{
	Message($msg,$back,$back);
}

/**
 * 网站成功提示信息处理函数
 *
 * @access  private
 *
 * @param   string      $msg        提示语句,lang数组索引
 * @param   string      $gourl      提示跳转的地址
 * @param   int         $back       是否自动返回上个页面/默认为自动跳转给出的url
 *
 * @return  null
 */
function s($msg,$gourl='',$back=0)
{
	Message($msg,$gourl,$back);
}

/**
 * 网站停止提示信息处理函数
 *
 * @access  private
 *
 * @param   string      $msg        提示语句,lang数组索引
 * @param   string      $gourl      提示跳转的地址
 * @param   int         $back       是否自动返回上个页面/默认为不显示跳转连接
 *
 * @return  null
 */
function stop($msg,$gourl='',$back=3)
{
	Message($msg,$gourl,$back);
}

/**
 * 网站提示信息处理函数
 *
 * @access  private
 *
 * @param   string      $msg        提示语句,lang数组索引
 * @param   string      $gourl      提示跳转的地址
 * @param   int         $back       是否自动返回上个页面
 *
 * @return  null
 */
function Message($msg,$gourl,$back)
{
	global $db;
	if(isset($GLOBALS['lang'][$msg])) {
		$msg = $GLOBALS['lang'][$msg];
	}
	$jumptime = 2000;
	$htmlhead = '<html><head><title>提示信息</title><meta http-equiv="content-Type" content="text/html charset=utf-8" /><META HTTP-EQUIV="pragma" CONTENT="no-cache"><META HTTP-EQUIV="Cache-Control" CONTENT="no-cache, must-revalidate">';
	$htmlhead .= '<base target="_self"/></head><body leftmargin="0" topmargin="0" bgcolor="#FFFFFF"><center><script>';
	$htmlfoot  = '</script></center></body></html>';
	if(1==$back){
		$jumptime = 2000;
		$gourl = "javascript:history.go(-1);";
	}

	$func = "var pgo=0;function JumpUrl(){if(pgo==0) location='$gourl';pgo = 1;}";
	$rmsg = $func;
	$rmsg .= 'document.write(\'<div style="border:1px solid #336699;width:550px;margin-top:25px;padding:1px;">\');';
	$rmsg .= 'document.write(\'<div style="height:20px;font-size:16px;font-weight:bold;BACKGROUND:#1693D9;BORDER-BOTTOM:#A8ECFF 2px solid;color:#FFFFFF;vertical-align:middle;padding:5px;">- 提示信息 -</div>\');';
	$rmsg .= 'document.write(\'<div style="height:100px;font-size:14px;"><br/>\');';

	$rmsg .= "document.write(\"$msg\");";
	if($back!=3)
	{
		$rmsg .= 'document.write(\'<br/><br/><a href="'.$gourl.'" style="font-size:12px;color:#336699;"><u>系统将在'.($jumptime/1000).'秒钟后自动跳转,如果你的浏览器没反应,请点击这里</u></a><br/><br/><div>\');';
	}
	$rmsg .= 'document.write("</div>");';
	if($back!=3)
	{
		$rmsg .= "setTimeout('JumpUrl()',$jumptime);";
	}
	$msg  = $htmlhead . $rmsg . $htmlfoot;
	echo $msg;
	if(is_object($db))
	{
		$db->close();
		unset($db);
	}
	ob_flush();
	exit();
}

/**
 * ajax 错误信息返回处理函数
 *
 * @access  public
 *
 * @param   string      $message    提示语句,lang数组索引
 * @param   array       $urls    	自定义跳转url连接数组
 * @param   string      $autojump   是否自动跳转
 *
 * @return  null
 */
function ajaxe($message,$urls='',$autojump='true')
{
	if(isset($GLOBALS['lang'][$message])) {
		$message = $GLOBALS['lang'][$message];
	}
	$arr = array();
	$arr['msgtype']  = 'error';
	$arr['autojump'] = $autojump;
	$arr['msg'] = $message;
	//跳转URL处理,自定义url
	if(is_array($urls) && !empty($urls))
	{
		foreach($urls as $key => $url)
		{
			$arr['url'][] = array($url[0],$url[1],$url[2]);
		}
	}
	else //默认Url
	{
			$arr['url'][] = array('javascript:closeMsgPanel();','返回重新填写');
	}
	ajaxmessage($arr);
}

/**
 * ajax 成功提示信息返回处理函数
 *
 * @access  public
 *
 * @param   string      $message    提示语句,lang数组索引
 * @param   array       $urls    	自定义跳转url连接数组
 * @param   string      $autojump   是否自动跳转
 *
 * @return  null
 */
function ajaxs($message,$urls='',$autojump='true')
{
	if(isset($GLOBALS['lang'][$message])) {
		$message = $GLOBALS['lang'][$message];
	}
	//初始化JSON数组
	$arr = array();
	//设定返回类型为成功
	$arr['msgtype']  = 'success';
	//设定是否自动跳转
	$arr['autojump'] = $autojump;
	//提示信息
	$arr['msg']   = $message;
	//跳转URL处理,自定义url
	if(is_array($urls) && !empty($urls))
	{
		foreach($urls as $key => $url)
		{
			$arr['url'][] = array($url[0],$url[1],$url[2]);
		}
	}
	else //默认Url
	{
		$arr['url'][] = array('javascript:closeMsgPanel();','完成','');
	}
	ajaxmessage($arr);
}

/**
 * ajax 提示信息输出函数
 *
 * @access  public
 *
 * @param   array      	$arr    	提示语句,lang数组索引
 *
 * @return  null
 */
function ajaxmessage($arr)
{
	global $db;
	//导入JSON生成类
	include 'include/json.class.php';
	//实例化JSON类
	$json = new Services_JSON;
	//将数组转换为JSON
	echo $json->encode($arr);
	unset($json);
	//注销数据库对象
	if(is_object($db))
	{
		$db->close();
		unset($db);
	}
	//输出到浏览器
	ob_flush();
	exit();
}

/**
 * 无序随机码生成函数
 *
 * @access  public
 *
 * @param   int      	$length    	随机码长度
 * @param   int         $numeric	是否只为数字
 *
 * @return  string
 */
function random($length, $numeric = 0) {
	PHP_VERSION < '4.2.0' && mt_srand((double)microtime() * 1000000);
	if($numeric) {
		$hash = sprintf('%0'.$length.'d', mt_rand(0, pow(10, $length) - 1));
	} else {
		$hash = '';
		$chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz';
		$max = strlen($chars) - 1;
		for($i = 0; $i < $length; $i++) {
			$hash .= $chars[mt_rand(0, $max)];
		}
	}
	return $hash;
}

/**
 * HTML表单hash码生成函数
 *
 * @access  public
 *
 * @return  string
 */
function formhash()
{
	global $localtime;
	if( isset($_SESSION['username']) && isset($_SESSION['uid']) ) {
		return substr(md5(substr($localtime, 0, -7).$_SESSION['username'].$_SESSION['uid'].$_SESSION['userpassword']), 8, 8);
	}
}

/**
 * HTML表单POST提交安全检查函数
 *
 * @access  public
 *
 * @return  null
 */
function submitcheck()
{
	//检查是否为POST提交
	if($_SERVER['REQUEST_METHOD'] == 'POST' && $_POST['formhash'] != formhash())
	{
		stop('Sorry Your Submit Seccode Invalid!!');
	}
}

/**
 * 刷新后台左侧页面函数
 *
 * @access  public
 *
 * @return  null
 */
function RefreshLeftMenu()
{
	echo "<script>parent.document.getElementById('menu').src='?action=classcategory&todo=left';</script>";
}

/**
 * PHPurl重写函数
 *
 * @access  public
 *
 * @return  null
 */
function  mod_rewrite()
{
	if (isset($_SERVER ['PATH_INFO']))
	{
		$url = substr ( $_SERVER['PATH_INFO'],1);
		$url = explode ( ' / ' , $url );
		foreach($url as $key => $value)
		{
			if($key % 2 != 1)
			{
				if($value != '') $_GET [ $value ] = $url [ $key + 1 ];
				$querystring [] = $value . ' = ' . $url [ $key + 1 ];
			}
		}
		$_SERVER['QUERY_STRING'] = implode("&",$querystring);
		$_SERVER['PHP_SELF'] = substr($_SERVER ['PHP_SELF' ],0,strpos($_SERVER ['PHP_SELF'],'.php') + 4);
		$_SERVER['REQUEST_URI'] = $_SERVER[ ' PHP_SELF ' ] . '?' . $_SERVER[' QUERY_STRING'];
	}
}

/**
 * 分页函数
 *
 * @access  public
 *
 * @param   int      	$num        总数
 * @param   int         $perpage    每页数目
 * @param   int         $curpage    当前页
 * @param   bool        $append     是否附加省略号
 *
 * @return  string
 */
function multipage($num, $perpage, $curpage, $maxpages = 0, $page = 10, $simple = 0, $onclick = '')
{
	$multipage = '';
	$onclick = $onclick ? ' onclick="'.$onclick.'(event)"' : '';
	//url处理
	$mpurl = $_SERVER['PHP_SELF'];
	if(isset($_SERVER['QUERY_STRING']))
	{
		$mpurl .= '?' . preg_replace('/&page=[0-9]*/','',$_SERVER['QUERY_STRING']) . '&';
	}
	if($num > $perpage) {
		$offset = 2;

		$realpages = @ceil($num / $perpage);
		$pages = $maxpages && $maxpages < $realpages ? $maxpages : $realpages;

		if($page > $pages) {
			$from = 1;
			$to = $pages;
		} else {
			$from = $curpage - $offset;
			$to = $from + $page - 1;
			if($from < 1) {
				$to = $curpage + 1 - $from;
				$from = 1;
				if($to - $from < $page) {
					$to = $page;
				}
			} elseif($to > $pages) {
				$from = $pages - $page + 1;
				$to = $pages;
			}
		}

		$multipage = ($curpage - $offset > 1 && $pages > $page ? '[<a href="'.$mpurl.'page=1" class="p_redirect"'.$onclick.'>&lsaquo;</a>]' : '').
			($curpage > 1 && !$simple ? '[<a href="'.$mpurl.'page='.($curpage - 1).'" class="p_redirect">上一页</a>]&nbsp;' : '');
		for($i = $from; $i <= $to; $i++) {
			$multipage .= $i == $curpage ? '[<a class="p_curpage">'.$i.'</a>]' :
				'[<a href="'.$mpurl.'page='.$i.'" class="p_num"'.$onclick.'>'.$i.'</a>]';
		}

		$multipage .= ($curpage < $pages && !$simple ? '&nbsp;[<a href="'.$mpurl.'page='.($curpage + 1).'" class="p_redirect"'.$onclick.'>下一页</a>]' : '').
			($to < $pages ? '[<a href="'.$mpurl.'page='.$pages.'" class="p_redirect"'.$onclick.'>&rsaquo;</a>]' : '').
			(!$simple && $pages > $page ? '<a class="p_pages" style="padding: 0px"><input class="p_input" type="text" name="custompage" onKeyDown="if(event.keyCode==13) {window.location=\''.$mpurl.'page=\'+this.value; return false;}"></a>' : '');

		$multipage = $multipage ? '<div class="p_bar">'.(!$simple ? '<a class="p_total">&nbsp;共'.$num.'条记录&nbsp;</a><a class="p_pages">&nbsp;'.$curpage.'/'.$realpages.'页&nbsp;</a>' : '').$multipage.'</div>' : '';
	}
	return $multipage;
}

/**
 * 截取UTF-8编码下字符串的函数
 *
 * @access  public
 *
 * @param   string      $str        被截取的字符串
 * @param   int         $start      截取的起始位置
 * @param   int         $length     截取的长度
 * @param   bool        $append     是否附加省略号
 *
 * @return  null
 */
function sub_str($str, $start=0, $length=0, $append=true)
{
    $str = trim($str);
    $reval = '';

    if (0 == $length)
    {
    	$length = strlen($str);
    }
    elseif (0 > $length)
    {
    	$length = strlen($str) + $length;
    }

    if (strlen($str) <= $length) return $str;

    for($i = 0; $i < $length; $i++)
    {
        if (!isset($str[$i])) break;

        if (196 <= ord($str[$i]))
        {
            $i += 2 ;
            $start += 2;
        }
    }
    if ($i >= $start) $reval = substr($str, 0, $i);
    if ($i < strlen($str) && $append) $reval .= "...";

	return $reval;
}


/**
* 过滤html元素，并保持原格式输出
*
* @access  public
*
* @param   string $content 输入内容
*
* @return  string
*/
function htmlencode($content)
{
  	$content = str_replace(' ', "&nbsp;", $content);
  	$content = str_replace('<', '＜',     $content);
  	$content = str_replace('>', '＞',     $content);
  	$content = str_replace('\n', "<br />",  $content);

    return $content;
}

/**
 * 获得用户的真实IP地址函数
 *
 * @access  public
 *
 * @return  string
 */
function real_ip()
{
	if (isset($_SERVER))
	{
		if (isset($_SERVER["HTTP_X_FORWARDED_FOR"]))
		{
			$arr = explode(',', $_SERVER["HTTP_X_FORWARDED_FOR"]);

            /* 取X-Forwarded-For中第一个非unknown的有效IP字符串 */
            foreach ($arr AS $ip)
            {
                $ip = trim($ip);

                if ($ip != 'unknown')
                {
                    $realip = $ip;
                    break;
                }
            }
		}
		elseif (isset($_SERVER["HTTP_CLIENT_IP"]))
		{
			$realip = $_SERVER["HTTP_CLIENT_IP"];
		}
		else
		{
			$realip = $_SERVER["REMOTE_ADDR"];
		}
	}
	else
	{
		if (getenv('HTTP_X_FORWARDED_FOR'))
		{
			$realip = getenv('HTTP_X_FORWARDED_FOR');
		}
		elseif (getenv('HTTP_CLIENT_IP'))
		{
			$realip = getenv('HTTP_CLIENT_IP');
		}
		else
		{
			$realip = getenv('REMOTE_ADDR');
		}
	}

	return $realip;
}

/**
 * 用户IP限制函数
 *
 * @access  public
 *
 * @param string $man 用户类型
 *
 * @return  null
 */
function banip($man)
{
    global $siteadminip,$siteuserip;
    if($man=='admin')
    {
        if(!ipaccess(real_ip(),$siteadminip))
        {
            stop('对不起,您的IP没有访问权限');
        }
    }
    elseif($man=='user')
    {
        if(!ipaccess(real_ip(),$siteuserip))
        {
            stop('对不起,您的IP没有访问权限');
        }
    }
}

/**
 * 检查管理员登录权限
 *
 * @access  public
 *
 * @return  null
 */
function CheckAccess()
{
	if( empty($_SESSION['uid']) || empty($_SESSION['username']) || empty($_SESSION['userlevel']) || empty($_SESSION['useraction']))
	{
		//判断http请求类型
		if((isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest') || (isset($_SERVER['HTTP_X_PROTOTYPE_VERSION']) && $_SERVER['HTTP_X_PROTOTYPE_VERSION'] == '1.5.0'))
		{
			//来自PROTOTYPE的Ajax HTTP请求使用JSON方式返回没有权限访问的信息
			ajaxe('对不起,您的登陆状态已经失效,请重新登陆!',array(array('javascript:adminlogout();','确定','')));
		}
		else
		{
			//常规无权限跳转
			echo "<script>\n";
			echo "try {\n ";
			echo "parent.window.location.href='?';\n ";
			echo "}\n ";
			echo "catch(e)\n ";
			echo "{\n ";
			echo "location.window.href='?';\n ";
			echo "}\n ";
			echo "</script>";
			exit();
		}
	}
}

/**
 * 管理员模块访问权限检测函数
 *
 * @access  public
 *
 * @param   string  $priv_str 权限代码字符串
 *
 * @return  bool
 */
function admin_priv($priv_str)
{
    if ($_SESSION['useraction'] == 'all')
    {
    	
        return true;
    }
    if (strpos(',' . $_SESSION['useraction'] . ',', ',' . $priv_str . ',') === false)
    {
        stop('user_access_dined');
		return false;
    }
    else
    {
        return true;
    }
}

/**
 * Ip访问权限检测函数
 *
 * @access  private
 *
 * @param   string  $ip 用户ip地址
 * @param   string  $accesslist 允许访问的地址列表
 *
 * @return  bool
 */
function ipaccess($ip, $accesslist) {
    return preg_match("/^(".str_replace(array("\r\n", ' '), array('|', ''), preg_quote($accesslist, '/')).")/", $ip);
}


/**
 * 汉字转拼音函数
 *
 * @access  public
 *
 * @param   string  $str 汉字
 * @param   int     $ishead 是否保留首字符 1为保留
 * @param	int		$isclose 是否保留全局拼音数组 1为保留
 *
 * @return  string
 */
function GetPinyin($str,$ishead=0,$isclose=1)
{
    global $pinyins;
    $restr = "";
    $str = trim($str);
    $slen = strlen($str);
    if($slen<2) return $str;
    if(count($pinyins)==0)
	{
        $fp = fopen("include/data/pinyin.db","r");
        while(!feof($fp))
		{
            $line = trim(fgets($fp));
            $pinyins[$line[0].$line[1]] = substr($line,3,strlen($line)-3);
        }
        fclose($fp);
    }
    for($i=0;$i<$slen;$i++)
	{
        if(ord($str[$i])>0x80)
        {
            $c = $str[$i].$str[$i+1];
            $i++;
            if(isset($pinyins[$c]))
			{
                if($ishead==0) $restr .= $pinyins[$c];
                else $restr .= $pinyins[$c][0];
            }
			else
			{
				$restr .= "_";
			}
        }
		else if( eregi("[a-z0-9]",$str[$i]) )
		{
		    $restr .= $str[$i];
		}
        else
		{
			$restr .= "_";
		}
    }
    if($isclose==0) unset($pinyins);
    return $restr;
}

/**
 * UTF-8字符编码转换GBK编码函数
 *
 * @access  public
 *
 * @param   string  $gb 需要转换的utf-8编码的字符串
 *
 * @return  string
 */
function utf82gb($gb)
{
  	if(function_exists("iconv"));
  		return iconv("UTF-8","GB2312",$text);

    $filename = ROOT . "include/data/gb-unicode.table.txt";
    $tmp = file($filename);
    $codetable = array();
    while(list($key,$value) = each($tmp) )
        $codetable[hexdec(substr($value,7,6))]=substr($value,0,6);;
    $out = "";
    $len = strlen($gb);
    $i = 0;
    while($i < $len) {
        $c = ord( substr( $gb, $i++, 1 ) );
        switch($c >> 4)
        {
            case 0: case 1: case 2: case 3: case 4: case 5: case 6: case 7:
                // 0xxxxxxx
                $out .= substr( $gb, $i-1, 1 );
            break;
            case 12: case 13:
                // 110x xxxx   10xx xxxx
                $char2 = ord( substr( $gb, $i++, 1 ) );
                $char3 = $codetable[(($c & 0x1F) << 6) | ($char2 & 0x3F)];
                $out .= _hex2bin( dechex(  $char3 + 0x8080 ) );
            break;
            case 14:
                // 1110 xxxx  10xx xxxx  10xx xxxx
                $char2 = ord( substr( $gb, $i++, 1 ) );
                $char3 = ord( substr( $gb, $i++, 1 ) );
                $char4 = $codetable[(($c & 0x0F) << 12) | (($char2 & 0x3F) << 6) | (($char3 & 0x3F) << 0)];
                 $out .= _hex2bin( dechex ( $char4 + 0x8080 ) );


            break;
        }
    }
    return $out;
}

/**
 * GBK字符编码转换UTF-8编码函数
 *
 * @access  public
 *
 * @param   string  $gbstr 需要转换的GBK编码的字符串
 *
 * @return  string
 */
function gb2utf8($gbstr)
{
	if(function_exists('iconv'))
		return iconv("GB2312","UTF-8",$gbstr);
 	if(trim($gbstr)=="") return $gbstr;
  	$filename = ROOT . "include/data/gb2312-utf8.table.txt";
  	$fp = fopen($filename,"r");
  	while ($l = fgets($fp,15) )
  	{
  		$CODETABLE[hexdec(substr($l, 0, 6))] = substr($l, 7, 6);
  	}
  	fclose($fp);
 	$ret = "";
 	$utf8 = "";
 	while ($gbstr)
 	{
  		if (ord(substr($gbstr, 0, 1)) > 127)
  		{
   			$thisW = substr($gbstr, 0, 2);
   			$gbstr = substr($gbstr, 2, strlen($gbstr));
   			$utf8 = "";
   			@$utf8 = u2utf8(hexdec($CODETABLE[hexdec(bin2hex($thisW)) - 0x8080]));
   			if($utf8!="")
   			{
    			for ($i = 0;$i < strlen($utf8);$i += 3)
    			{
     				$ret .= chr(substr($utf8, $i, 3));
    			}
   			}
  		}
  		else
  		{
   			$ret .= substr($gbstr, 0, 1);
   			$gbstr = substr($gbstr, 1, strlen($gbstr));
  		}
 	}
 	return $ret;
}

/**
 * hex字符编码转换bin编码函数
 *
 * @access  private
 *
 * @param   string  $gbstr 需要转换的hex编码的字符串
 *
 * @return  string
 */
function _hex2bin( $hexdata )
{
	for ( $i=0; $i<strlen($hexdata); $i+=2 )
		$bindata.=chr(hexdec(substr($hexdata,$i,2)));

	return $bindata;
}

/**
 * 将SQL文件转换为PHP数组函数
 *
 * @access  public
 *
 * @param   string  $sql 读取的SQL文件内容
 *
 * @return  string
 */
function splitsql($sql) {
	$sql = str_replace("\r", "\n", $sql);
	$ret = array();
	$num = 0;
	$queriesarray = explode(";\n", trim($sql));
	unset($sql);
	foreach($queriesarray as $query)
	{
		$queries = explode("\n", trim($query));
		foreach($queries as $query)
		{
			$ret[$num] .= $query[0] == "#" ? NULL : $query;
		}
		$num++;
	}
	return($ret);
}

/**
 * 读取文件夹所有文件函数
 *
 * @access public
 *
 * @param  string dir 文件夹路径
 * @param  array  ext 允许的文件后缀
 *
 * @return array
 */
function getFile($dir,$ext='')
{
    $fileArr = array();
    $dp = opendir($dir);
    while( ($file  = readdir($dp)) !== false)
    {
        if($file !="." && $file!=".." && $file!="")
        {

			//文件后缀名判断
			if($ext=='')
			{
	            //文件夹判断
				if(is_dir($dir."/".$file))
				{
					$fileArr = array_merge($fileArr,getFile($dir."/".$file));
				}
	            if(is_file($dir."/".$file))
	            {
	                $fileArr[] = array(
	                                'size'=>(filesize($dir."/".$file)/1024).'kb',
	                                'edittime'=>gmdate ("Y-n-j  H:i:s", filemtime($dir."/".$file) + 8 * 3600 ),
	                                'name'=>$dir."/".gb2utf8($file)
	                                 );
	            }
			}
			else
			{
				//多个文件后缀判断
				if(is_array($ext))
				{
					$ext = '('.implode('|',$ext).')';
				}
				if(preg_match('/\.'.$ext.'$/',$file))
				{
					$fileArr[] = array(
	                                'size'=>(filesize($dir."/".$file)/1024).'kb',
	                                'edittime'=>gmdate ("Y-n-j  H:i:s", filemtime($dir."/".$file) + 8 * 3600 ),
	                                'name'=>$dir.gb2utf8($file)
	                                 );
				}
			}
        }
    }
    closedir($dp);
    return $fileArr;
}

/**
 * 安全检查系统操作
 *
 * @access  public
 *
 * @return  null
 */

function check_todo($todo,$todoarr)
{
    if(!in_array($todo,$todoarr))
    {
        stop('common_unknow_action');
    }
}



/**
 * 检测用户名密码是否合法
 *
 * @access  public
 *
 * @param   string      $name      用户名
 * @param   string      $pass      密码
 *
 * @return  string
 */
function checkuser($name,$pass)
{
    if( empty($name) )
    {
        return "user_username_empty";
    }
    if( empty($pass) )
    {
        return "user_password_empty";
    }
    $reg = "/^([a-zA-Z0-9]|[._]){4,19}$/";

    if( !preg_match($reg,$name) )
    {
        return "user_username_notmatch";
    }
    if( !preg_match($reg,$pass) )
    {
        return "user_password_notmatch";
    }
    return 0;
}

/**
 * 删除一个文件夹的下面所有的文件夹和文件
 *
 * @access  public
 *
 * @param   string      $dir      文件夹路径
 *
 * @return  bool
 */
function doDelDir($dir)
{
    $dh = @opendir($dir);
    while ($file = @readdir($dh))
    {
        if($file!="." && $file!="..")
        {
            $fullpath=$dir."/".$file;
            if(!is_dir($fullpath))
            {
                @unlink($fullpath);
            }
            else
            {
                doDelDir($fullpath);
            }
        }
    }
    @closedir($dh);
    if (@rmdir($dir))
    {
        return true;
    }
    else
    {
        return false;
    }
}

/**
 * 通过UID得到用户详细信息
 */
function getUserInfo($uid) {
	global $db,$tablepre;
	$userinfo = $db->fetch_one_array("SELECT * FROM {$tablepre}systemuser WHERE id = $uid");
	return $userinfo;
}
/**
 * 解码  Excel导入有用到
 */
function recode($str)
{
	$str = preg_replace("|&#([0-9]{1,5})|", "\".u2utf82gb(\\1).\"", $str);
	$str = "\$str=\"$str\";";
	eval($str);
	return $str;
} 
function u2utf82gb($c)
{
	$str = "";
	if ($c < 0x80)
	{
		$str .= chr($c);
	}
	else if ($c < 0x800)
	{
		$str .= chr(0xC0 | $c >> 6);
		$str .= chr(0x80 | $c &0x3F);
	}
	else if ($c < 0x10000)
	{
		$str .= chr(0xE0 | $c >> 12);
		$str .= chr(0x80 | $c >> 6 &0x3F);
		$str .= chr(0x80 | $c &0x3F);
	}
	else if ($c < 0x200000)
	{
		$str .= chr(0xF0 | $c >> 18);
		$str .= chr(0x80 | $c >> 12 &0x3F);
		$str .= chr(0x80 | $c >> 6 &0x3F);
		$str .= chr(0x80 | $c &0x3F);
	} 
	return $str;
	//return iconv('UTF-8', 'GB2312', $str);
}
/**
 * 文件上传处理函数
 *
 * @access  public
 *
 * @param   string      $filename      文件名称
 * @param   string      $tmpfile       临时文件名
 * @param   int			$filesize      文件大小
 * @param   string      $attach_dir    存放文件夹位置
 * @param   string      $uploadroot    上传文件夹根目录位置
 * @param   string      $uploadpath    上传文件夹目录
 *
 * @return  string
 */
function uploadfile($filename,$tmpfile,$filesize,$type,$attach_dir='file',$uploadroot='',$uploadpath='')
{

    global $localtime;
    $extension  = strtolower(substr(strrchr($filename, "."),1));
	$filename   = $localtime.'.'.$extension;

    if (!in_array($extension, $type))
        e('common_file_type_error');
    //if($filesize>$attach_maxsize)
       // e('common_file_toolarge');
    //转移附件--按年月分类附件
	if($uploadroot=='')
		$uploadroot = 'data/upload/';
	if($uploadpath=='')
		$uploadpath = 'data/upload/'.date("Ym")."/";
    $attachpath = $uploadpath.$filename;

    if(!is_dir($uploadroot))
        @mkdir($uploadroot,0777) OR die("权限不足无法创建".$uploadroot."目录!请手动创建并设置权限为777");
    if(!is_dir($uploadpath))
        @mkdir($uploadpath,0777) OR die("创建".$uploadpath."目录失败");
    if(@is_uploaded_file($tmpfile)){
        if(!@move_uploaded_file($tmpfile ,$attachpath)){
                @unlink($tmpfile);//删除临时文件
                e('common_file_uploaderror');
        }
    }
    return $attachpath;
}
/**
 * 得到全球头像
 * @param string	$email
 * @param int		$size
 * @param url		$default	默认图标地址
 */
function get_gravatar( $email, $size = 80, $default = 'mm') {
    return $grav_url = "http://www.gravatar.com/avatar/" . md5( strtolower( trim( $email ) ) ) . "?d=" . urlencode( $default ) . "&s=" . $size;
}
/**
 * 发送HTTP状态信息
 * @param int $code 状态代码
 */
function send_http_status($code) {
	static $_status = array(
	// Informational 1xx
	100 => 'Continue',
	101 => 'Switching Protocols',
	// Success 2xx
	200 => 'OK',
	201 => 'Created',
	202 => 'Accepted',
	203 => 'Non-Authoritative Information',
	204 => 'No Content',
	205 => 'Reset Content',
	206 => 'Partial Content',
	// Redirection 3xx
	300 => 'Multiple Choices',
	301 => 'Moved Permanently',
	302 => 'Moved Temporarily ',  // 1.1
	303 => 'See Other',
	304 => 'Not Modified',
	305 => 'Use Proxy',
	// 306 is deprecated but reserved
	307 => 'Temporary Redirect',
	// Client Error 4xx
	400 => 'Bad Request',
	401 => 'Unauthorized',
	402 => 'Payment Required',
	403 => 'Forbidden',
	404 => 'Not Found',
	405 => 'Method Not Allowed',
	406 => 'Not Acceptable',
	407 => 'Proxy Authentication Required',
	408 => 'Request Timeout',
	409 => 'Conflict',
	410 => 'Gone',
	411 => 'Length Required',
	412 => 'Precondition Failed',
	413 => 'Request Entity Too Large',
	414 => 'Request-URI Too Long',
	415 => 'Unsupported Media Type',
	416 => 'Requested Range Not Satisfiable',
	417 => 'Expectation Failed',
	// Server Error 5xx
	500 => 'Internal Server Error',
	501 => 'Not Implemented',
	502 => 'Bad Gateway',
	503 => 'Service Unavailable',
	504 => 'Gateway Timeout',
	505 => 'HTTP Version Not Supported',
	509 => 'Bandwidth Limit Exceeded'
	);
	if(array_key_exists($code,$_status)) header('HTTP/1.1 '.$code.' '.$_status[$code]);
}
/**
 * 得到配置文件
 * @param string $config
 */
function GetConfig($config){
	if (array_key_exists($config, $GLOBALS['DZMC_CFG'])) {
			return $GLOBALS['DZMC_CFG'][$config];
	}
	return '';
}
