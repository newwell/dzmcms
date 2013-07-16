// JavaScript Document

//主函数
function CheckForm(oForm,doajax) {
	
	var err_ele = null;
	var ele = oForm.elements;
	var haserror = false;
	//遍历所有表元素
	for(var i=0;i<ele.length;i++) {
		//过滤元素类型
		if(ele.type != 'submit' && ele.type != 'hidden' && ele.type != 'button') {
			//是否需要验证标签
			var required = ele[i].getAttribute('required');
			if(required!="undefined" && (required==""||required=="true"||required=="yes") ) {
				if(!validate(ele[i])) {	
					if(err_ele==null)
						err_ele = ele[i];
					haserror = true;	
					
				}
			}
		}
	}
	//判断是否有没有通过验证的项目
	
	if(haserror) {
		err_ele.focus();
		return false;
	} else {
		//检查提交方式 ajax 或正常表单提交
		if(typeof(doajax) == 'undefined') {
			dosubmit(oForm);
			return false;
		} else {
			
			return true;
		}
	}
}


//生成<span>警告标签函数
function CreatSpan(ele,Msg) {
	var span = document.createElement("span");
	span.className = 'checkspan';
	//设定名称属性,防止与其他span标签重复
	span.setAttribute('name','formcheck');
	span.innerHTML = Msg;
	//如果有父节点
	if(ele.parentNode) {
		//去除多余的span标签
		RemoveSpan(ele);
		//加入新span标签
		ele.parentNode.appendChild(span);
	}
}

function RemoveSpan(ele) {
	//非第一次验证时,去除前一次验证显示的span标签
	//得到父节点下所有子节点
	var parent = ele.parentNode;
	var child  = parent.childNodes;
	for(i=0;i<child.length;i++) {
		//容错,子节点可能不含有tagName属性
		try {
			//得到name的属性,兼容IE和火狐
            var tagname    = child[i].tagName.toLowerCase();
			var name       = child[i].getAttribute('name');
			if(name != 'undefined' && tagname == 'span') {
				if(name == 'formcheck' ) {
					parent.removeChild(child[i]);
				}
			}
		}
		catch(e) { 
			continue;
		}
	}
}

//得到表单元素的值
function getvalue(ele) {
	var type = ele.getAttribute('type');
	switch(type) {
　　　　case 'text':
　　　　case 'hidden':
　　　　case 'password':
　　　　case 'file':
		case "select-one":
　　　　case 'textarea': 
			return ele.value;
　　　　case 'checkbox':
　　　　case 'radio': 
			return choosevalue(ele);
　　　　case "select-multiple": 
			return selectvalue(ele);
　　}
}



//取得radio,checkbox的选中数
function choosevalue(ele) {
	var value = 0;
	//取得第一个元素的name,搜索这个元素组
　　var tmpele = document.getElementsByName(ele.getAttribute('name'));
　　for(var i=0;i<tmpele.length;i++) {
		if(tmpele[i].checked) {
			value++;
　　　　}
　　}
	return value;
}

//取得select的选中数
function selectvalue(ele) {
	var value = 0;
	for(var i=0;i<ele.options.length;i++) {
		//单选下拉框提示选项设置为value=""
		if(ele.options[i].selected && ele.options[i].value!='') {
			value++;
		}
	}
	return value;
}



//检测函数
function validate(ele) {
	//得到设定的检测函数
	var fun = ele.getAttribute('fun');
	//自定义提示语句
	var custommsg = ele.getAttribute('msg');
	
	if(fun!=null) {
		var checkarr = executeFunc(fun,ele);
		if(checkarr[2] == 'regxp') { //正则表达式处理
			if(!regValidate(getvalue(ele),checkarr[0])) {
				//如果有自定义提示语句则使用自定义提示语句
				if(custommsg) {
					CreatSpan(ele,custommsg);
				} else {
					CreatSpan(ele,checkarr[1]);
				}
				return false;
			} else {
				RemoveSpan(ele);
			}
		} else if(checkarr[2] == 'custom') { //函数自行逻辑处理
			//如果验证通过
			if(checkarr[0]) {
				RemoveSpan(ele);
			} else {
				//如果有自定义提示语句则使用自定义提示语句
				if(custommsg) {
					CreatSpan(ele,custommsg);
				} else {
					CreatSpan(ele,checkarr[1]);
				}
				return false;
			}
		}
	}
	return true;
}

//运行函数
function executeFunc(name,element) {
	//匹配函数是否带有参数
	if(name.match(/^[\w]+\([\w,\'\"]+\);?$/)) {
		return eval(name.replace(/^([\w]+)\(([\w,\'\"]+)\);?$/,'$1($2,element)'));
	} else {
		return eval(name.match(/^[\w]+\(\)$/) ? name : name + '()');
	}
}

//
//判定某个值与表达式是否相符
function regValidate(value,sReg) {
	//字符串->正则表达式,不区分大小写
	var reg = new RegExp(sReg ,"i");
	if(reg.test(value)) {
		return true;
	} else {
		return false;
	}
}



//////////////////////////////////验证规则定义///////////////////////////////





function UserName() {
	var rt = new Array();
	rt[0] = '^([a-zA-Z0-9]|[._]){4,19}$';
	rt[1] = "用户名必须为4-19个字母和数字组成";
	rt[2] = 'regxp';
	return rt;
}

function PassWord() {
	var rt = new Array();
	rt[0] = '^([a-zA-Z0-9]|[._]){6,19}$';
	rt[1] = "密码必须为6-19个字母和数字组成";
	rt[2] = 'regxp';
	return rt;
}

function reg(sReg){
	var rt = new Array();
	rt[0] = sReg;
	rt[1] = '';
	rt[2] = 'regxp';
	return rt;
}

function notBlank(){
	var rt = new Array();
	rt[0] = "^\\S+$";
	rt[1] = "该项不能为空,且不能含有空格!";
	rt[2] = 'regxp';
	return rt;
}

function required(){
	var rt = new Array();
	rt[0] = "^\\S";
	rt[1] = "该项不能为空!";
	rt[2] = 'regxp';
	return rt;
}

function isDate(fmt){
	var regex = new Array("-",".","/");
	var regex0 = "";
	for (var i=0;i<regex.length ;i++ ){
		if(fmt.indexOf(regex[i])!=-1){
			regex0 = regex[i];
			break;
		}
	}
	var y = 0;
	var m = 0;
	var d = 0;
	var number = new Array(0,0,0);
	var ch = '';
	var index = 0;
	for (var i=0;i<fmt.length ;i++ ) {
		if(ch==''||fmt.charAt(i)==ch) {
			number[index] = number[index]+1;
		} else if(fmt.charAt(i)!=regex0) {
			index++;
			number[index] = number[index]+1;
		}
		ch = fmt.charAt(i);
		if(fmt.charAt(i)=='Y'||fmt.charAt(i)=='y') {
			y++;
		}
		if(fmt.charAt(i)=='M'||fmt.charAt(i)=='m') {
			m++;
		}
		if(fmt.charAt(i)=='D'||fmt.charAt(i)=='d') {
			d++;
		}
	}
	
	var rt = new Array();
	var sreg = "";
	for(i in number){
		if(number[i]!=0) {
			if(sreg!="") {
				sreg += regex0;	
			}
			sreg += "\\d{"+number[i]+"}"; 
		}
	}
	sreg = "^"+sreg+"$";
	rt[0] = sreg;
	rt[1] = "该项格式应为"+fmt;
	rt[2] = 'regxp';
	return rt;
}

/**
 * 字符串判定
 * 如min设为"'#'",表示字数不能大于max
 * 如max设为"'#'",表示字数不能小于min
 */
function isString(min,max) {
	var rt = new Array();
	if(min == null && max == null) {
		rt[0] = "";
		rt[1] = "";

		return rt;
	}
	if(max==null)
		max=min;
	if(min == "#" && max == "#") {
		//任意字符
		rt[0] = "[\\S|\\s]";
		rt[1] = "";
		return rt;
	}
	if(min=="#"){
		rt[0] = "^[\\S|\\s]{0,"+max+"}$"; 
		rt[1] = "该项字数不能大于"+max;
		return rt;
	}
	if(max=="#"){
		rt[0] = "^[\\S|\\s]{"+min+",}$";
		rt[1] = "该项字数不能小于"+min;
		return rt;
	}
	rt[0] = "^[\\S|\\s]{"+min+","+max+"}$"; 
	if(min==max){
		rt[1] = "该项字数应为"+min+"个！";
	}else{
		rt[1] = "该项字数介于"+min+"和"+max+"之间！";
	}
	rt[2] = 'regxp';
	return rt;
}

function strStartsWith(str){
	if(this.value.indexOf(str)!=0){
		alert(this.showName+"必须以字符‘"+str+"’开头！");
		return false;
	}
}

function isContains(str,ele) {
	var rt = new Array();
	var value = ele.value;
	if(value.indexOf(str)==-1){
		rt[0] = false;
	} else {
		rt[0] = true;
	}
	rt[1] = '必须包含字符‘'+str+'’！';
	rt[2] = 'coustom';
	return rt;
}

function strEndsWith(str) {
	var rt = new Array();
	rt[0] = str+'$';
	rt[1] = '必须以字符‘'+str+'’结束';
	rt[2] = 'regxp';
	return rt;
	
}

//判断email
function isEmail() {
	var rt = new Array();
	rt[0] = "\\w+([-+.]\\w+)*@\\w+([-.]\\w+)*\\.\\w+([-.]\\w+)*";
	rt[1] = "该项填写的EMAIL格式不正确！";
	rt[2] = 'regxp';
	return rt;
}

//只能输入中文
function onlyZh() {
	var rt = new Array();
	rt[0] = "^[\u0391-\uFFE5]+$";
	rt[1] = "该项只能输入中文！";
	rt[2] = 'regxp';
	return rt;
}

//只可输入英文
function onlyEn() {
	var rt = new Array();
	rt[0] = "^[A-Za-z]+$";
	rt[1] = "该项只能输入英文！";
	rt[2] = 'regxp';
	return rt;
}

function enOrNum() {
	var rt = new Array();
	rt[0] = "^[A-Za-z0-9]+$";
	rt[1] = "该项只能输入英文和数字,且不能有空格！";
	rt[2] = 'regxp';
	return rt;
}

/**
 * 整数的判定
 * @param type
 *		为空		任意整数
 *		'0+'	非负整数
 *		'+'		正整数
 *		'-0'	非正整数
 *		'-' 	负整数
 */
function isInt(type) {
	var rt = new Array();
	if(type=="0+"){
		rt[0] = "^\\d+$";
		rt[1] = "该项应输入非负整数!";
	}else if(type=="+"){
		rt[0] = "^\\d*[1-9]\\d*$";
		rt[1] = "该项应输入正整数!";
	}else if(type=="-0"){
		rt[0] = "^((-\\d+)|(0+))$";
		rt[1] = "该项应输入非正整数!";
	}else if(type=="-"){
		rt[0] = "^-\\d*[1-9]\\d*$";
		rt[1] = "该项应输入负整数!";
	}else{
		rt[0] = "^-?\\d+$";
		rt[1] = "该项应输入整数值！";
	}
	rt[2] = 'regxp';
	return rt;
}

/**
 * 浮点数的判定
 * @param type
 *		为空		任意浮点数
 *		'0+'	非负浮点数
 *		'+'		正浮点数
 *		'-0'	非正浮点数
 *		'-'		负浮点数
 */
function isFloat(type) {
	var rt = new Array();
	if(type=="0+"){
		rt[0] = "^\\d+(\.\\d+)?$";
		rt[1] = "该项应输入非负浮点数!";
	}else if(type=="+"){
		rt[0] = "^((\\d+\\.\\d*[1-9]\\d*)|(\\d*[1-9]\\d*\\.\\d+)|(\\d*[1-9]\\d*))$";
		rt[1] = "该项应输入正浮点数!";
	}else if(type=="-0"){
		rt[0] = "^((-\\d+(\.\\d+)?)|(0+(\\.0+)?))$";
		rt[1] = "该项应输入非正浮点数!";
	}else if(type=="-"){
		rt[0] = "^(-((\\d+\\.\\d*[1-9]\\d*)|(\\d*[1-9]\\d*\\.\\d+)|(\\d*[1-9]\\d*)))$";
		rt[1] = "该项应输入负浮点数!";
	}else{
		rt[0] = "^(-?\\d+)(\\.\\d+)?$";
		rt[1] = "该项应输入浮点数值！";
	}
	rt[2] = 'regxp';
	return rt;
}

/**
 * 数字大小判定
 * 如min设为"'#'",表示不能大于max
 * 如max设为"'#'",表示不能小于min
 */
function setNumber(min,max,ele) {
	var rt = new Array();
	//获得dom控件对象
	var value = ele.value;
	if(min == null && max == null) {
		//任意数字,不判定范围
		return isFloat();
	}

	if(max == null) {
		max = min;
	}

	//任意数字,不判定范围
	if(min == '#' && max == '#'){
		
		return isFloat();
	}

	rt[0] = true;
	rt[2] = 'custom';
	if(min == '#') {
		if(value > max) {
			rt[0] = false;
			rt[1] = value + "不能大于" + max;
		}
	}
	if(max == '#') {
		if(value < min) {
			rt[0] = false;
			rt[1] = value + "不能小于" + max;
		}
	}
	if( value < min || value > max) {
		rt[0] = false;
		if(min == max) {
			rt[1] = value + "的值应为" + min;
		} else {
			rt[1] = value + "应介于"+min+"和"+max+"之间！";
		}
	}
	return rt;
}

function isPhone() {
	var rt = new Array();
	rt[0] = "^((\\(\\d{2,3}\\))|(\\d{3}\\-))?(\\(0\\d{2,3}\\)|0\\d{2,3}-)?[1-9]\\d{6,7}(\\-\\d{1,4})?$";
	rt[1] = "应输入正确的电话号码格式！";
	rt[2] = 'regxp';
	return rt;
}

function isMobile() {
	var rt = new Array();
	rt[0] = "^((\\(\\d{2,3}\\))|(\\d{3}\\-))?13\\d{9}$";
	rt[1] = "应输入正确的手机号码格式！";
	rt[2] = 'regxp';
	return rt;
}

function isUrl() {
	var rt = new Array();
	rt[0] = "^http[s]?:\/\/[A-Za-z0-9]+\.[A-Za-z0-9]+[\/=\\?%\\-&_~`@[\\]\\':+!]*([^<>\"\"])*$";
	rt[1] = "应输入正确的URL（必须以http(s)://开头）！";
	rt[2] = 'regxp';
	return rt;
}

function isZip() {
	var rt = new Array();
	rt[0] = "^[1-9]\\d{5}$";
	rt[1] = "应输入正确的编码格式！";
	rt[2] = 'regxp';
	return rt;
}

//目标至少需要选择(通常用于select-multiple/checkbox)
function select(num) {
	var rt = new Array();
	rt[0] = "^0{"+num+",}$";
	rt[1] = "应至少选择"+num+"项！";
	rt[2] = 'regxp';
	return rt;
}