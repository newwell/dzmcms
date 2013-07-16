/*
	命名空间	: Utils
*/
var Utils = new Object();

/*
	命名空间	: Utils
	类		: Event
	描述		: 处理事件Event的相关静态方法
*/


Utils.Event = {
	
	//得到触发事件的对象
	'getEvTarget' : function(ev) {
		var target = ev.target || ev.srcElement;
		return target;
	},
	
	//得到触发事件的X轴和Y轴位置
	'getEvPosition' : function(ev) {
		if(ev.pageX || ev.pageY){
			return {x:ev.pageX, y:ev.pageY};
		}
		return {
			x:ev.clientX ,
			y:ev.clientY 
		};
	}
};

/*
	命名空间	: Dom
*/
var Dom = new Object();

/*
	命名空间	: Dom
	类		: Element
	描述		: 元素处理类
*/
Dom.Element = {
	//得到当前拖拽对象的currentStyle属性
	'currentStyle' : function(ele,index) {
		//ie获得方式
		if(ele.currentStyle){  
			var str = ele.currentStyle[index]; 
	    }
		else //firefox获得方式
		{  
			var str = document.defaultView.getComputedStyle(ele, null)[index];
	    }  
	    return str; 
	},
	
	
	'getPosition' : function(e) {
	
		var left = parseInt(Dom.Element.currentStyle(e,'left')) || 0;
		var top  = parseInt(Dom.Element.currentStyle(e,'top')) || 0;
		
		return {x:left, y:top};
	
	},
	/*
		返回一个元素的父节点
		@ele : 需要返回父节点的对象
		return : object
	*/
	'getFather' :function(ele) {
		return typeof(ele.parentNode) == 'undefined' ? ele : ele.parentNode;
	}
}

/*
	命名空间	: Dom
	类			: Drag
	描述		: 拖拽类
*/
Dom.Drag = {
	
	/*
		判断鼠标是否按下
		类型: staitc - bool
	*/
	'iMouseDown'  : false,
	/*
		拖动的对象
		类型: staitc - object
	*/
	'dragObject'  : null,
	/*
		拖动的对象的位置
		类型: staitc - object
	*/
	'elePos' : null,
	/*
		拖动的对象的长宽
		类型: staitc - object
	*/
	'eleWH'  : new Object(),
	
	/*
		拖动时对象是否透明
		类型: staitc - bool
	*/
	'eleTransparent'  : false,
	
	/*
		设定一个元素可以拖动
		类型: static
		返回: none
		@ele    - object : DOM元素对象
		@father - bool 	 : 是否拖动父对象
	*/
	'makeDraggable' : function(ele,father) {
		if(!ele) return;
		//如果是要拖动父对象
		if(father)
		{
			//设定要拖动父对象标识
			ele.setAttribute('MoveFather','true');
		}
		else
		{
			//设定可拖动标识
			ele.setAttribute('Draggable','true');
		}
	},
	
	/*
		鼠标按下触发事件
		类型: static
		返回: none
		@ev : event对象,firefox兼容
	*/
	'mouseDown' : function(ev) {
		ev = ev || window.event;
		//根据event对象获得触发事件的DOM元素对象
		var target = Utils.Event.getEvTarget(ev);
		//检查是否需要移动父节点
		if(target.getAttribute('MoveFather') == 'true')
		{
			//获得父节点
			Dom.Drag.dragObject = Dom.Element.getFather(target);
			if(Dom.Drag.dragObject)
			{
				Dom.Drag.dragObject.setAttribute('Draggable','true');
			}
		}
		else
		{
			Dom.Drag.dragObject = target;
		}
		
		//检查该对象是否有能拖动的标识
		if(Dom.Drag.dragObject.getAttribute('Draggable') != 'true')
		{
			return false;
		}
		
		//对象是否透明
		if(Dom.Drag.eleTransparent)
		{
			//多浏览器设定透明属性
			Dom.Drag.dragObject.style.filter = 'alpha(opacity=75)';
			Dom.Drag.dragObject.style.mozOpacity = '0.75'; 
			Dom.Drag.dragObject.style.opacity = '0.75';
		}
		
		var x = Dom.Element.currentStyle(Dom.Drag.dragObject,'left');
		var y = Dom.Element.currentStyle(Dom.Drag.dragObject,'top');
		
		//判断Left和Top的百分比属性,重新计算为像素
		if(x.match(/([0-9]+?)%$/))
		{
			x = parseFloat(x.replace(/([0-9]+)%$/,'0.$1'));
			x = parseFloat(document.body.clientWidth * x)
		}
		if(y.match(/([0-9]+?)%$/))
		{
			y = parseFloat(y.replace(/([0-9]+)%$/,'0.$1'));
			y = parseFloat(document.body.scrollHeight * y)
		}
		
		//获得触发事件元素的长宽,parseInt用于去除属性值后面的px
		Dom.Drag.eleWH.x  =  parseInt(x);
		Dom.Drag.eleWH.y  =  parseInt(y);
		//获得当前触发事件的位置
		Dom.Drag.elePos = Utils.Event.getEvPosition(ev); 
		//设定点击属性为true,标示鼠标已点击
		Dom.Drag.iMouseDown = true;
	},
	
	
	
	/*
		鼠标移动时触发事件
		@ev : event对象,firefox兼容
	*/
	'mouseMove' : function(ev) {
		//检查鼠标是否按下
		if(Dom.Drag.iMouseDown) 
		{
			ev = ev || window.event;
			//防止拖动层时选中其他文字
			if(ev.stopProgation)
			{ 
				ev.stopPropagation(); 
			} 
			else
			{ 
				ev.cancelBubble = true; 
			} 
			if(!Dom.Drag.dragObject)
			{
				//根据event对象获得触发事件的DOM元素对象
				var target = Utils.Event.getEvTarget(ev);
				//检查是否需要移动父节点
				if(target.getAttribute('MoveFather') == 'true')
				{
					Dom.Drag.dragObject = Dom.Element.getFather(target);
					if(Dom.Drag.dragObject)
					{
						Dom.Drag.dragObject.setAttribute('Draggable','true');
					}
				}
				else
				{
					Dom.Drag.dragObject = target;
				}
			}
			
			//获得当前触发事件的位置
			var mousePos    =  Utils.Event.getEvPosition(ev);
			//检查拖拽对象样式position是否设置为'absolute'
			if(Dom.Drag.dragObject.style.position != 'absolute')
			{
				//设定对象的属性,如果为非absolute定位对象则拖拽完毕后要还原这一设定
				Dom.Drag.dragObject.setAttribute('noabsolute',Dom.Drag.dragObject.style.position);
				Dom.Drag.dragObject.style.position = 'absolute';
			}
			//设定拖拽对象的位置 = 拖拽对象的横向位置x + 当前鼠标事件横向位置x- 点击时横向位置x
			try {
				Dom.Drag.dragObject.style.left     = Dom.Drag.eleWH.x + mousePos.x - Dom.Drag.elePos.x + "px" ;
				Dom.Drag.dragObject.style.top      = Dom.Drag.eleWH.y + mousePos.y - Dom.Drag.elePos.y + "px" ;
			} catch(e) {
				
			}
			
		}
		if(Dom.Drag.dragObject) return false;
	},
	
	/*
		鼠标松开触发事件
		@ev : event对象,firefox兼容
	*/
	'mouseUp'   : function(ev) {
		//设定鼠标按下属性为false
		Dom.Drag.iMouseDown = false;
		//还原先前的定位属性
		if(Dom.Drag.dragObject)
		{
			if(Dom.Drag.dragObject.getAttribute('noabsolute'))
			{
				Dom.Drag.dragObject.style.position = Dom.Drag.dragObject.getAttribute('noabsolute');
			}
		}
		//对象是否透明
		if(Dom.Drag.eleTransparent)
		{
			//多浏览器设定透明属性
			Dom.Drag.dragObject.style.filter = '';
			Dom.Drag.dragObject.style.mozOpacity = ''; 
			Dom.Drag.dragObject.style.opacity = '';
		}
	},
	
	/*
		设定当前页面可以拖拽
	*/
	
	'startdrag' : function() {
		document.onmousemove = Dom.Drag.mouseMove;
		document.onmouseup   = Dom.Drag.mouseUp;
		document.onmousedown = Dom.Drag.mouseDown;
	},
	
	/*
		设定当前页面不可以拖拽
	*/
	'stopdrag' : function() {
		document.onmousemove = null;
		document.onmousedown = null;
		document.onmouseup   = null;
	}
}