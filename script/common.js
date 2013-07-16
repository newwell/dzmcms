
//隐藏页面中所有的下拉菜单
function hideselects()
{
	var selects = document.getElementsByTagName('select');
	for(i = 0; i < selects.length; i++) 
	{ 
		selects[i].style.visibility = 'hidden'; 
	}
}

//显示页面中所有的下拉菜单
function showselects()
{
	var selects = document.getElementsByTagName('select');
	for(i = 0; i < selects.length; i++) 
	{ 
		selects[i].style.visibility = 'visible'; 
	}
}

//隐藏页面中所有的框架
function hideiframes()
{
	var iframes = document.getElementsByTagName('iframe');
	for(i = 0; i < iframes.length; i++) 
	{ 
		iframes[i].style.visibility = 'hidden'; 
	}
}

//显示页面中所有的框架
function showiframes()
{
  var iframes = document.getElementsByTagName('iframe');
  for(i = 0; i < iframes.length; i++) 
  { 
     iframes[i].style.visibility = 'visible'; 
  }
}

//时间戳函数
function timestamp()
{
	var timestamp = Date.parse(new Date());
	return timestamp;
}

//获得当前页面文件名 注意在类似 http://www.qq.com/ 这样的地址时是无法识别的。
function getpagename()
{
	var url = document.location.href;
	//过滤域名和目录名称
	url = url.replace(/^(http:\/\/[\w\.\-]+\/([\w\.\-]*\/)*)/i,'');
	//过滤查询字符串
	url = url.replace(/(\?.*)/,'');
	return url;
}

//动态加载 JavaScript 或者 Css 文件
function loadscriptfile(filename, filetype)
{ 
	var head = document.getElementsByTagName('head').item(0);
	if(filetype == 'js')
	{ 
		var scripts = document.getElementsByTagName("script");
		for (var i=0;i<scripts.length;i++)
		{
			  if (scripts[i].src && scripts[i].src.toLowerCase() == filename ) 
				  return;
		}
		var fileref = document.createElement('script'); 
		fileref.setAttribute('type','text/javascript'); 
		fileref.setAttribute('src', filename); 
	} 
	if(filetype == 'css')
	{
		var css = document.getElementsByTagName("link");
		for(var i=0;i<css.length;i++)
		{
			  if (css[i].type == 'text/css' && css[i].href.toLowerCase() == filename ) 
				  return;
		}
		var fileref = document.createElement('link'); 
		fileref.setAttribute('rel','stylesheet'); 
		fileref.setAttribute('type','text/css');  
		fileref.setAttribute('href',filename);
	} 
	if(typeof fileref != 'undefined') 
		head.appendChild(fileref); 
}

//导入JS类库
function importclass()
{
	loadscriptfile('script/class.js','js');
}


