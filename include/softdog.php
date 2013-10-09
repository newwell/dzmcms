<?php
/**
 * ------------------------------------------------------------------
 * 加密狗集中处理函数库
 * ------------------------------------------------------------------
 * @author newwell
 * ------------------------------------------------------------------
 * @copyright	武汉大赞网络科技
 * ------------------------------------------------------------------
 * @link	http://www.dazan.cn
 * ------------------------------------------------------------------
 */
/**
 * 这个用于判断系统中是否存在着加密锁。不需要是指定的加密锁
 */
function softdog_check() {
	$s_simnew1=new COM("Syunew3A.s_simnew3");
    $DevicePath = $s_simnew1->FindPort(0);
    if( $s_simnew1->LastError != 0 ) {
    	 return FALSE;
    }
    return TRUE;
}
/**
 * 得到加密狗设备ID
 */
function softdog_getID() {
	$s_simnew1=new COM("Syunew3A.s_simnew3");
    $DevicePath = $s_simnew1->FindPort(0);
   	$ID1 = $s_simnew1->GetID_1($DevicePath);
    If ($s_simnew1->LastError != 0 ){
    	$ID1="";
    };
    $ID2 = $s_simnew1->GetID_2($DevicePath);
    If ($s_simnew1->LastError != 0 ){
    	$ID2="";
    };
    return Hex($ID1)."--";Hex($ID2);
}
/**
 * 得到加密狗设备版本
 */
function softdog_getVersion() {
	$s_simnew1=new COM("Syunew3A.s_simnew3");
    $DevicePath = $s_simnew1->FindPort(0);
   	$version = $s_simnew1->GetVersion($DevicePath);
   	if ($s_simnew1->LastError != 0){
   		$version = false;
   	}
    return $version;
}
/**
 * 读取字符串
 */
function softdog_readString($mylen) {
	$s_simnew1=new COM("Syunew3A.s_simnew3");
    $DevicePath = $s_simnew1->FindPort(0);
    //$mylen = 9;//读取长度
   	$outstring = $s_simnew1->YReadString(0, $mylen, "ffffffff", "ffffffff", $DevicePath);
    If ($s_simnew1->LastError != 0 ) {
    	return false;
    }else {
    	return $outstring;
    }
}
/**
 * 写字符串
 */
function softdog_writeString($String) {
	$s_simnew1=new COM("Syunew3A.s_simnew3");
    $DevicePath = $s_simnew1->FindPort(0);
    $nlen = $s_simnew1->YWriteString($String, 0, "ffffffff", "ffffffff", $DevicePath);
     if ($nlen < 1 ){
     	return false;
     }else {
     	return $nlen;
     }
}


function Hex($indata)
{
	$lX8 = $indata & 0x80000000;
	if($lX8)
	{
		$indata=$indata & 0x7fffffff;
	}
	while ($indata>16)
	{
		$temp_1=$indata % 16;
		$indata=$indata /16 ;
		if($temp_1<10)
		   $temp_1=$temp_1+0x30;
		else
		   $temp_1=$temp_1+0x41-10; 
		
		$outstring= chr($temp_1) . $outstring ; 
		   
	}
	$temp_1=$indata;
	if($lX8)$temp_1=$temp_1+8;
	if($temp_1<10)
	   $temp_1=$temp_1+0x30;
	else
	   $temp_1=$temp_1+0x41-10; 
	
	$outstring= chr($temp_1) . $outstring ; 
	
	return $outstring;
		 
}