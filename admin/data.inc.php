<?php
if(!defined('IN_SITE')) exit('Access Denied');

//权限检查
CheckAccess();
admin_priv($act['action']);


//程序参数安全处理
$_TODOLIST = array('list','optimize','backup','dobackup','query','execute','del','import','importzip','replace','doreplace');
check_todo($todo,$_TODOLIST);


//表信息列表
if($todo=="list") 
{
    $query = $db->query("SHOW TABLE STATUS LIKE '$tablepre%'");
    $tablearray = array();
    $i = 1;
    while($table = $db->fetch_array($query)) 
    {
        if($table['Data_free'] && $table['Type'] == 'MyISAM') 
        {
            $checked = $table['Type'] == 'MyISAM' || $table['Engine'] == 'MyISAM' ? 'checked' : 'disabled';
            $totalsize += $table['Data_length'] + $table['Index_length'];
        }
        $i++;  
        $tablearray[] = $table; 
    }
    include template('data_list');
}
elseif($todo=="optimize") //数据表优化操作
{
    $tables = $_POST['tables'];
    if(empty($tables))
    {
        e('database_none_table');
    }
    $query = $db->query("SHOW TABLE STATUS LIKE '$tablepre%'");
    while($table = $db->fetch_array($query)) 
    {
        if(is_array($tables) && in_array($table['Name'], $tables)) 
        {
            $db->query("OPTIMIZE TABLE $table[Name]");
        } 
    }
    
    s('database_optimize_success','?action='.$act["action"].'&todo=list');
}
elseif($todo=="backup")
{
	//初始化随机文件名
	$filename  = gmdate('ymdHi',$localtime).'_'.random(8);
    $filearr   = getFile('data/backup/',array('sql','zip'));
    include template('data_backup');
    
}
elseif($todo=="dobackup") //数据表备份操作
{
	//文件名称
	$filename  = $_REQUEST['filename'];
	//将数据中的中文16进制化
	$usehex = 1;
	//分卷编号
	$volume    = $_REQUEST['volume'];
	//压缩打包选项
	$ziped     = $_REQUEST['ziped'];
	//文件大小
	$sizelimit = $_REQUEST['sizelimit'];
	//位置
	$startfrom = $_REQUEST['startfrom'];
	//mysql兼容模式
	$sqlcompat = $_REQUEST['sqlcompat'];
	//表id
	$tableid   = intval($_REQUEST['tableid']);
	//设定数据库编码
	$sqlcharset = $dumpcharset = 'utf8';
	//数据是否扩展插入
	$extendins  = 0;
	//系统表数组
	$tables   = array('settings',
						'systemaction',
						'systemuser',
						'urls');
	//初始化SQL语句
	$sqldump = '';
	//表数组索引号
	$tableid = $tableid ? $tableid - 1 : 0;
	//初始行数
	$startfrom = intval($startfrom);
	
	//遍历表数组导出表结构和数据
	for($i = $tableid; $i < count($tables) && strlen($sqldump) < $sizelimit * 1000; $i++) 
	{
		$sqldump .= sqldumptable($tablepre .$tables[$i], $startfrom, strlen($sqldump));
		$startfrom = 0;
	}
	
	//获得当前正在到处的表的索引号
	$tableid = $i;
	//初始化数据库备份文件文件夹路径
	
	$backup_file_dir = dirname(__FILE__).DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'data'.DIRECTORY_SEPARATOR.'backup'.DIRECTORY_SEPARATOR;
//	$dumpfile = 'data/backup'.DIRECTORY_SEPARATOR;
	$dumpfile = $backup_file_dir;
	//生成分卷文件名称
	$dumpfile .= $filename .'_#num#.sql';
	//分卷编号
	$volume = intval($volume) + 1;
	//初始化zip对象
	$zip = '';
	
	//文件压缩设定
	if($ziped)
	{
		//如需需要打包则导入打包类
		require_once 'include/phpzip.class.php';
		//初始化zip类
		$zip = new zipfile();
	}
	
	
	
	//如有有数据导出
	if(trim($sqldump)) 
	{
		//加密的备份文件信息 时间/文件名/文件编号
		$codestring = '# code: '.base64_encode("$localtime,$filename,$volume")."\n";
		//增加备份文件顶部版权信息
		$sqldump = $codestring.
			"# <?exit();?>\n".
			"# 大赞科技(dazan.cn)数据分卷备份数据文件编号.$volume\n".
			"# 备份时间: $time\n".
			"# 数据库表前缀: $tablepre\n".
			"#\n".
			"# --------------------------------------------------------\n\n\n".
			"$setnames".
			 $sqldump;
		//生成文件名
		$dumpfile = str_replace('#num#',$volume,$dumpfile);
		
		//设定SQL单卷压缩打包
		if($ziped==1)
		{
			//往压缩包中添加同名SQL文件
			$zip->addFile($sqldump,str_replace('#num#',$volume,$filename.'_#num#.sql'));
			//更换文件后缀
			$dumpfile = str_replace('.sql','.zip',$dumpfile);
			
			//获得打包后的数据内容
			$sqldump = $zip->file();
			unset($zip);
		}
		
		//写入文件
		@$fp = fopen($dumpfile, 'wb');
		@flock($fp, 2);
		if(@!fwrite($fp, $sqldump)) 
		{
			@fclose($fp);
			e('database_backup_error');
		} 

		//开始循环跳转备份
		s('database_backup_success_next', "?action=database_backup&todo=dobackup&filename=".rawurlencode($filename)."&sizelimit=".rawurlencode($sizelimit)."&volume=".rawurlencode($volume)."&tableid=".rawurlencode($tableid)."&startfrom=".rawurlencode($startrow)."&extendins=".rawurlencode($extendins)."&sqlcharset=".rawurlencode($sqlcharset)."&sqlcompat=".rawurlencode($sqlcompat)."&usehex=$usehex&ziped=$ziped");
	}
	else
	{
		//如果设定全部SQL文件打包
		if($ziped==2)
		{
			$sqlfiles = glob($backup_file_dir.$filename."_*.sql");
			foreach($sqlfiles as $key => $sql)
			{
				$sqldump = file_get_contents($sql);
				$zip->addFile($sqldump,$filename.'_'.($key+1).'.sql');
			}
			
			$sqldump = $zip->file();
			unset($zip);
			$dumpfile = $backup_file_dir.$filename.".zip";
			@$fp = fopen($dumpfile, 'wb');
			@flock($fp, 2);
			if(@!fwrite($fp, $sqldump)) 
			{
				@fclose($fp);
				e('database_backup_error');
			}
			
			//压缩成功后再删除先前的SQL文件
			foreach($sqlfiles as $key => $sql)
			{
				unlink($sql);
			}
			
		}
		//备份完毕
		s('database_backup_success','?action=database&todo=backup');
	}
}
elseif($todo=="importzip") //导入压缩数据文件操作
{
	$backupfile = $_GET['datafile'];
	
	//文件身份及文件名确认
	if(is_file($backupfile) && preg_match("/^data\/backup\/[\w]+\.zip$/i", $backupfile)) 
	{ 
		//载入压缩包处理类
		require_once 'include/phpzip.class.php';
		
		//生成解压缩类
		$unzip = new SimpleUnzip();
		//读取压缩文件内容
		$unzip->ReadFile($backupfile);
		//检查压缩包内文件是否合法
		if($unzip->Count() == 0 || $unzip->GetError(0) != 0 || !preg_match("/\.sql$/i", $importfile = $unzip->GetName(0))) 
		{
			e('database_import_file_illegal');
		}
		//初始化解压缩的SQL文件数目
		$sqlfilecount = 0;
		//遍历压缩包中所有文件
		foreach($unzip->Entries as $entry) 
		{
			//验证为sql文件并写入到服务器文件夹中
			if(preg_match("/\.sql$/i", $entry->Name)) 
			{
				$fp = fopen('databackup/'.$entry->Name, 'w');
				fwrite($fp, $entry->Data);
				fclose($fp);
				$sqlfilecount++;
			}
		}
		
		//如果没有sql文件被解压缩那么该文件不合法
		!$sqlfilecount && e('database_import_file_illegal');
		
		//读出加密码中的文件信息
		$identify = explode(',', base64_decode(preg_replace("/^# code:\s*(\w+).*/s", "\\1", substr($unzip->GetData(0), 0, 256))));
		s('database_backup_upzip_success','?action=database&todo=import&delzip=yes&datafile='.$identify[1].'_'.$identify[2]);
	}
	else
	{
		e('database_import_file_illegal');
	}
}
elseif($todo=='import') //导入数据
{
	//文件参数
	$datafile   = $_GET['datafile'];
	//自动导入
	$autoimport = $_GET['autoimport'];
	var_dump($autoimport);exit();
	//文件夹自动识别
	if(!preg_match('/^data\/backup\//i',$datafile))
	{
		$datafile = 'data/backup/' . $datafile ;
	}
	//文件名自动识别
	if(!preg_match('/\.sql$/i',$datafile))
	{
		$datafile = $datafile .'.sql';
	}

	//文件安全检查
	if(!preg_match('/\.sql$/',$datafile) && !is_file($datafile))
	{
		
		if($autoimport=='yes')
		{
			//自动导入,已经没有下个导入文件,提示导入完成
			s('database_restore_success','?action=database&todo=backup');
		}
		else
		{
			//非自动导入则显示非法文件
			e('database_import_file_illegal');
		}
	}
	
	//读取文件内容
	if(@$fp = fopen($datafile, 'rb')) 
	{
		//读取SQL文件头部几行信息
		$sqldump  = fgets($fp, 256);
		//对先前写入的信息进行解码
		$identify = explode(',', base64_decode(preg_replace("/^# code:\s*(\w+).*/s", "\\1", $sqldump)));
		//获得该文件的备份序号
		$dumpinfo = array('volume' => intval($identify[2]));
		//读取文件
		$sqldump .= fread($fp, filesize($datafile));
		fclose($fp);
	}
	else 
	{
		if($autoimport=='yes')
		{
			//自动导入,已经没有下个导入文件,提示导入完成
			s('database_restore_success','?action=database&todo=backup');
		}
		else
		{
			e('database_import_file_illegal');
		}
	}
	
	//SQL数据处理
	$sqlquery = splitsql($sqldump);
	unset($sqldump);
	
	foreach($sqlquery as $sql) 
	{
		//数据库版本和字符集处理
		$sql = syntablestruct(trim($sql), $db->version > '4.1', 'utf8');
		//运行SQL语句
		if($sql != '') 
		{
			$db->query($sql, 'SILENT');
		}
	}
	
	//生成要导入的下个文件名
	$datafile_next = preg_replace("/_($dumpinfo[volume])(\..+)$/", "_".($dumpinfo['volume'] + 1)."\\2", $datafile);
	//如果是序列中第一个文件
	if($dumpinfo['volume'] == 1) 
	{
		s('database_restore_begin',"?action=database&todo=import&datafile=$datafile_next&autoimport=yes");
	} 
	elseif($autoimport=='yes') 
	{
		s('database_restore_next', "?action=database&todo=import&datafile=$datafile_next&autoimport=yes");
	} 
	else 
	{
		s('database_restore_success','?action=database&todo=backup');
	}
}
elseif($todo=="query") //显示查询页面
{
    include template('data_query');
} 
elseif($todo=="execute") //运行查询语句
{
    $sql = $_POST['sql'];
    if(empty($sql))
    {
        e('database_data_execute_empty');
    }
    //解除转义符
    $sql = str_replace("\\","",$sql);
    $db->query($sql);
    s("database_data_execute_success","?action=database&todo=query");
}
elseif($todo=="del")
{
    $backupfile = $_GET['file'];
    if(is_file($backupfile) && preg_match("/\.(sql|zip)$/i", $backupfile)) 
    {
        if(unlink($backupfile))
        {
            s('database_backupfile_delsuccess','?action=database&todo=backup');
        }
        else
        {
            e('database_backupfile_delfail');
        }
    }
    else
    {
        e('database_not_backupfile');
    }        
}
elseif($todo=='replace')
{
	include template('data_replace');
}
elseif($todo=='doreplace') 
{
	$search  = $_POST['search'];
	$replace = $_POST['replace'];
	if(!empty($search))
	{
		$db->query("UPDATE {$tablepre}sitearticle SET sortcontent = REPLACE(sortcontent,'$search','$replace') , content = REPLACE(content,'$search','$replace')");
		$db->query("UPDATE {$tablepre}classdeclarecontent SET content = REPLACE(content,'$search','$replace')");
	}
	s('database_replace_success','?action=database&todo=replace');
}          



function syntablestruct($sql, $version, $dbcharset) {

	if(strpos(trim(substr($sql, 0, 18)), 'CREATE TABLE') === FALSE) {
		return $sql;
	}

	$sqlversion = strpos($sql, 'ENGINE=') === FALSE ? FALSE : TRUE;

	if($sqlversion === $version) {

		return $sqlversion && $dbcharset ? preg_replace(array('/ character set \w+/i', '/ collate \w+/i', "/DEFAULT CHARSET=\w+/is"), array('', '', "DEFAULT CHARSET=$dbcharset"), $sql) : $sql;
	}

	if($version) {
		return preg_replace(array('/TYPE=HEAP/i', '/TYPE=(\w+)/is'), array("ENGINE=MEMORY DEFAULT CHARSET=$dbcharset", "ENGINE=\\1 DEFAULT CHARSET=$dbcharset"), $sql);

	} else {
		return preg_replace(array('/character set \w+/i', '/collate \w+/i', '/ENGINE=MEMORY/i', '/\s*DEFAULT CHARSET=\w+/is', '/\s*COLLATE=\w+/is', '/ENGINE=(\w+)(.*)/is'), array('', '', 'ENGINE=HEAP', '', '', 'TYPE=\\1\\2'), $sql);
	}
}



/**
 * 读取文件夹所有文件函数
 *
 * @param  string dir 文件夹路径
 * @param  array  ext 允许的文件后缀
 * 
 * @return array
 * @access public
 */

function sqldumptable($table, $startfrom = 0, $currsize = 0) {
	global $db, $sizelimit, $startrow, $extendins, $sqlcompat, $sqlcharset, $dumpcharset, $usehex;

	$offset = 300;
	$tabledump = '';

	if(!$startfrom) {

		$tabledump = "DROP TABLE IF EXISTS $table;\n";

		$createtable = $db->query("SHOW CREATE TABLE $table");
		$create = $db->fetch_row($createtable);

		$tabledump .= $create[1];

		if($sqlcompat == 'MYSQL41' && $db->version < '4.1') {
			$tabledump = preg_replace("/TYPE\=(.+)/", "ENGINE=\\1 DEFAULT CHARSET=".$dumpcharset, $tabledump);
		}
		if($db->version > '4.1' && $sqlcharset) {
			$tabledump = preg_replace("/(DEFAULT)*\s*CHARSET=.+/", "DEFAULT CHARSET=".$sqlcharset, $tabledump);
		}

		$query = $db->query("SHOW TABLE STATUS LIKE '$table'");
		$tablestatus = $db->fetch_array($query);
		$tabledump .= ($tablestatus['Auto_increment'] ? " AUTO_INCREMENT=$tablestatus[Auto_increment]" : '').";\n\n";
		if($sqlcompat == 'MYSQL40' && $db->version >= '4.1') {
			if($tablestatus['Auto_increment'] <> '') {
				$temppos = strpos($tabledump, ',');
				$tabledump = substr($tabledump, 0, $temppos).' auto_increment'.substr($tabledump, $temppos);
			}
			if($tablestatus['Engine'] == 'MEMORY') {
				$tabledump = str_replace('TYPE=MEMORY', 'TYPE=HEAP', $tabledump);
			}
		}
	}

	$tabledumped = 0;
	$numrows = $offset;
	if($extendins =='0') {
		while($currsize + strlen($tabledump) < $sizelimit * 1000 && $numrows == $offset) {
			$tabledumped = 1;
			$rows = $db->query("SELECT * FROM $table LIMIT $startfrom, $offset");
			$numfields = $db->num_fields($rows);
			if($usehex) {
				$fields=$db->query("SHOW FIELDS FROM $table");
				while ($fieldrows = $db->fetch_row($fields)) {
					$stringfield[] = preg_match('/char|text/i', $fieldrows[1]);
				}
			}
			$numrows = $db->num_rows($rows);
			while($row = $db->fetch_row($rows)) {
				$comma = '';
				$tabledump .= "INSERT INTO $table VALUES (";
				for($i = 0; $i < $numfields; $i++) {
					$tabledump .= $comma.($usehex ? ($row[$i] != '' && $stringfield[$i] ? '0x'.bin2hex($row[$i]) : '\''.$row[$i].'\'') : '\''.mysql_escape_string($row[$i]).'\'');
					$comma = ',';
				}
				$tabledump .= ");\n";
			}
			$startfrom += $offset;
		}
	} else {
		while($currsize + strlen($tabledump) < $sizelimit * 1000 && $numrows == $offset) {
			$tabledumped = 1;
			$rows = $db->query("SELECT * FROM $table LIMIT $startfrom, $offset");
			$numfields = $db->num_fields($rows);
			if($usehex) {
				$fields=$db->query("SHOW FIELDS FROM $table");
				while ($fieldrows = $db->fetch_row($fields)) {
					$stringfield[] = preg_match('/char|text/i', $fieldrows[1]);
				}
			}
			if($numrows = $db->num_rows($rows)) {
				$tabledump .= "INSERT INTO $table VALUES";
				$commas = '';
				while($row = $db->fetch_row($rows)) {
					$comma = '';
					$tabledump .= $commas." (";
					for($i = 0; $i < $numfields; $i++) {
						$tabledump .= $comma.($usehex ? ($row[$i] != '' && $stringfield[$i] ? '0x'.bin2hex($row[$i]) : '\''.$row[$i].'\'') : '\''.mysql_escape_string($row[$i]).'\'');
						$comma = ',';
					}
					$tabledump .= ')';
					$commas = ',';
				}
				$tabledump .= ";\n";
			}
			$startfrom += $offset;
		}
	}

	$startrow = $startfrom;
	$tabledump .= "\n";
	return $tabledump;
}
?>               
