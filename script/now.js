

function initArray() {
	for(i=0;i<initArray.arguments.length;i++) {
		this[i] = initArray.arguments[i];
	}
}
 var isnMonths = new initArray("1月","2月","3月","4月","5月","6月","7月","8月","9月","10月","11月","12月");
 var isnDays   = new initArray("星期日","星期一","星期二","星期三","星期四","星期五","星期六","星期日");
 today = new Date();
 hrs = today.getHours();
 min = today.getMinutes();
 sec = today.getSeconds();
 clckh = "" + ((hrs>12) ? hrs-12 : hrs );
 clckm = ((min<10)?"0":"") + min; clcks = ((sec<10)?"0":"") + sec;
 clck  = (hrs>=12) ? "下午" : "上午" ;
 var stnr = "";
 var ns   = "0123456789";
 var a    = "";

function getFullYear(d) {
	yr = d.getYear();
	if(yr<1000) {
		yr += 1900;
	}
	return yr;
}
  
//下面各行分别是一种风格，把不需要的删掉即可
  document.write(getFullYear(today)+"年"+isnMonths[today.getMonth()]+""+today.getDate()+"日"+"&nbsp;"+isnDays[today.getDay()]+"&nbsp;"+"<span id='liveclock' style'=width: 109px; height: 15px'></span>");
var liveclock = document.getElementById('liveclock');
function butong_net() {
	var Digital = new Date();
	var hours   = Digital.getHours();
	var minutes = Digital.getMinutes();
	var seconds = Digital.getSeconds();
	if(minutes<=9)
		minutes = "0" + minutes;
	if(seconds<=9)
		seconds = "0" + seconds;
	myclock = "" + hours + ":" + minutes + ":" + seconds + "";
	liveclock.innerHTML = myclock;
	setTimeout("butong_net()",1000);
}
butong_net();