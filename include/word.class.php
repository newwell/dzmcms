<?php
include 'include/MhtFileMaker.php';
/**
 * 生成伪word的文件
 * by:newwell
 * 从$word->start();到$word->save("data.doc");之间的不显示，并保存到DOC中
 */
class word {
	function start() {
		ob_start ();
		print 'From: =?utf-8?B?08kgV2luZG93cyBJbnRlcm5ldCBFeHBsb3JlciA4ILGjtOY=?=
Subject:
Date: Wed, 16 Oct 1991 09:00:35 +0800
MIME-Version: 1.0
Content-Type: text/html;
	charset="utf-8"
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<HTML><HEAD><META content="IE=5.0000" http-equiv="X-UA-Compatible">

<META content="text/html; charset=utf-8" http-equiv=Content-Type>
<META name=GENERATOR content="MSHTML 8.00.7600.16821"></HEAD>';

	}

	function save($path) {
		$content = ob_get_contents ();
		ob_end_clean ();
		$content = preg_replace ( '/src\s*?=\s*?([\/a-z0-9]+\.[a-z]{3})/i', 'src="\\1"', $content );
		$a = $_SERVER['HTTP_REFERER'];
		$absolutePath = substr($a,0,strrpos($a,'/')).'/';
		//echo $url ;exit;	
		$fileContent = getWordDocument ($content,$absolutePath);
		$fp = fopen ( $path, 'wb' );
		fwrite ( $fp, $fileContent );
		fclose ( $fp );
	}

}

/**
 * 根据HTML代码获取word文档内容
 * 创建一个本质为mht的文档，该函数会分析文件内容并从远程下载页面中的图片资源
 * 该函数依赖于类MhtFileMaker
 * 该函数会分析img标签，提取src的属性值。但是，src的属性值必须被引号包围，否则不能提取
 * 
 * @param string	$content		HTML内容
 * @param string	$absolutePath	网页的绝对路径。如果HTML内容里的图片路径为相对路径，那么就需要填写这个参数，来让该函数自动填补成绝对路径。这个参数最后需要以/结束
 * @param bool		$isEraseLink	是否去掉HTML内容中的链接
 */
function getWordDocument( $content,$absolutePath = "",$isEraseLink = true )
{
    $mht = new MhtFileMaker();
    if ($isEraseLink)
        $content = preg_replace('/<a\s*.*?\s*>(\s*.*?\s*)<\/a>/i' , '$1' , $content);   //去掉链接

    $images = array();
    $files = array();
    $matches = array();
    //这个算法要求src后的属性值必须使用引号括起来
    if ( preg_match_all('/<img.*?src\s*?=\s*?[\"\'](.*?)[\"\'](.*?)>/i',$content ,$matches ) )
    {
        $arrPath = $matches[1];
        for ( $i=0;$i<count($arrPath);$i++)
        {
            $path = $arrPath[$i];
            $imgPath = trim( $path );
            if ( $imgPath != "" )
            {
                $files[] = $imgPath;
                if( substr($imgPath,0,7) == 'http://')
                {
                    //绝对链接，不加前缀
                }
                else
                {
                    $imgPath = $absolutePath.$imgPath;
                }
                $images[] = $imgPath;
            }
        }
    }
    $mht->AddContents("tmp.html",$mht->GetMimeType("tmp.html"),$content);
    
    for ( $i=0;$i<count($images);$i++)
    {
        $image = $images[$i];
        if ( @fopen($image , 'r') )
        {
            $imgcontent = @file_get_contents( $image );
            if ( $content )
                $mht->AddContents($files[$i],$mht->GetMimeType($image),$imgcontent);
        }
        else
        {
            echo "文件:".$image." 没有找到,这将可能导致word不能读！<br />";
        }
    }
    
    return $mht->GetFile();
}
?>