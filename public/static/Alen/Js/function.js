var debug=true;
var $ifmobile=ifmobile();
var echoLog=function(txt){
	if(debug)console.log(txt);
}
var returnFalse=function(){return false;}
var winToUrl=function(url){
	if(!url){
		alerts('网址不可为空');
		return false;
	}
	if(top==window){
		window.location.href=url;
	}else{
		top.window.location.href=url;
	}
}
//获取全屏元素
var getFullscreen=function(){
	return document.fullscreenElement || document.webkitFullscreenElement || document.mozFullscreenElement;
}
//设置全屏元素
var setFullscreen=function(o){
	var de = $(o)[0];
	if(de.requestFullscreen){
		de.requestFullscreen();
	}else if(de.mozRequestFullScreen){
		de.mozRequestFullScreen();
	}else if(de.webkitRequestFullScreen){
		de.webkitRequestFullScreen();
	}
}
//退出全屏
var exitFullscreen=function(){
	var de = document;
	if(de.exitFullscreen){
		de.exitFullscreen();
	}else if(de.mozCancelFullScreen){
		de.mozCancelFullScreen();
	}else if(de.webkitCancelFullScreen){
		de.webkitCancelFullScreen();
	}
}
//阻止默认事件
function stopDefault(e) { 
    if ( e && e.preventDefault ){
        e.preventDefault(); //阻止默认浏览器动作(W3C) 
    }else {
        window.event.returnValue = false; //IE中阻止函数器默认动作的方式 
    }
    return false; 
}
function trim(str){ //删除左右两端的空格
	return str.replace(/(^\s*)|(\s*$)/g, "");
}
function ltrim(str){ //删除左边的空格
	return str.replace(/(^\s*)/g,"");
}
function rtrim(str){ //删除右边的空格
	return str.replace(/(\s*$)/g,"");
}
function isNull(data){ 
    return (data == undefined || data == null) ? true : false; 
}
function isNumber(obj) {  
    return obj === +obj  
} 

function getUID(){
	var d = new Date().getTime();
	var uuid = 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function(c) {
		var r = (d + Math.random()*16)%16 | 0;
		d = Math.floor(d/16);
		return (c=='x' ? r : (r&0x3|0x8)).toString(16);
	});
	return uuid;
}
//格式化时间
Date.prototype.format = function(format) {
       var date = {
              "M+": this.getMonth() + 1,
              "d+": this.getDate(),
              "h+": this.getHours(),
              "m+": this.getMinutes(),
              "s+": this.getSeconds(),
              "q+": Math.floor((this.getMonth() + 3) / 3),
              "S+": this.getMilliseconds()
       };
       if (/(y+)/i.test(format)) {
              format = format.replace(RegExp.$1, (this.getFullYear() + '').substr(4 - RegExp.$1.length));
       }
       for (var k in date) {
              if (new RegExp("(" + k + ")").test(format)) {
                     format = format.replace(RegExp.$1, RegExp.$1.length == 1
                            ? date[k] : ("00" + date[k]).substr(("" + date[k]).length));
              }
       }
       return format;
}
//对象属性总个数
function objCount(obj){
	var cut=0;
	for(var i in obj)cut++;
	return cut;
}
//cookie
function addCookie(objName,objValue,objHours){//添加cookie
	var str = objName + "=" + escape(objValue);
	if(objHours > 0){//为0时不设定过期时间，浏览器关闭时cookie自动消失
		var date = new Date();
		var ms = objHours*3600*1000;
		date.setTime(date.getTime() + ms);
		str += "; path=/; domain="+_SYS.domain+"; expires=" + date.toGMTString();
	}
	document.cookie = str;
}

function getCookie(objName){//获取指定名称的cookie的值
	var arrStr = document.cookie.split("; ");
	for(var i = 0;i < arrStr.length;i ++){
		var temp = arrStr[i].split("=");
		if(temp[0] == objName) return unescape(temp[1]);
	} 
}

function delCookie(name){//为了删除指定名称的cookie，可以将其过期时间设定为一个过去的时间
	var date = new Date();
	date.setTime(date.getTime() - 10000);
	document.cookie = name + "=a; expires=" + date.toGMTString();
}

//确认删除
function confirm_hint(obj,txts,fun,buttxt){
	var o=obj?$(obj):$(this);
	alerts(txts,false,{
		confirm:buttxt?buttxt:'删除',
		cancel:'取消',
		fun:function(val){
			if(val){
				if(fun){
					fun();
				}else{
					window.location.href=o.attr('href');
				}
			}
		}
	});
	return false;
}
//加载文件
function include(file,type,cfun){
	if(!type){
		type=getFileExt(file);
	}
	if(type=='css'){
		var html='<link rel="stylesheet" type="text/css" href="'+file+'"/>';
		$('head').append(html);
	}else if(type=='js'){	
		$.getScript(file,cfun);
	}
}
//加载图片
function loadImg(url,callback,errfun){
	var img = new Image(); //创建一个Image对象，实现图片的预下载
	img.onload=function(){ //图片下载完毕时异步调用callback函数。
		if(!img.width){
			setTimeout(function(){
				img.onload();
			},50);
		}else{
			if(callback){callback(img);}
		}
	}
	$(img).error(function(){
		if(errfun){errfun(img);}
		$(this).unbind('error,load');
	});	
	img.src = url;
	if(window.ActiveXObject){
		if(img.readyState=='complete'){
			img.onload=null;
			callback(img);
			return; // 直接返回，不用再处理onload事件
		}
	}else{
		if(img.complete){	
			img.onload=null;
			callback(img);
			return; // 直接返回，不用再处理onload事件
		}
	}
}
//post
function httpStatus(status,d_txt){
	status=parseInt(status);
	var txt;
	switch(status){
		case 401:
			txt='未经授权访问受密码保护的页面';
		break;
		case 403:
			txt='服务器拒绝请求';
		break;
		case 404:
		case 410:
			txt='T.T 不小心把页面弄丢了...';
		break;
		case 414:
			txt='URI地址过长';
		break;
		default:
			if(status>=400 && status<500){
				txt='请求出错';
			}else if(status>=500 && status<600){
				txt='T.T 服务器表示想休息一会...';
			}else{
				txt=d_txt?d_txt:'';
			}
		break;
	}
	return txt;
}
function alenPost(url,postdata,sfun,efun){
     var errFun=function(jqXHR,textStatus,errorThrown){
        var errObj={'url':url,'data':postdata};
        if(jqXHR.statusText.toLowerCase()=='abort'){
            errObj.txt='请求被客户端中止';
        }else{
            var info=httpStatus(jqXHR.status,'连接出错');
            errObj.txt=info;
            errObj.obj=jqXHR;
            if(efun)efun(info);
        }
        echoLog(errObj);
    };
	var obj=$.ajax({
		'type':'POST',
		//'xhrFields':{'withCredentials':true,},
		//'crossDomain': true,
		'url':url,
		'data':postdata,
		//'dataType':'json',
        'error':errFun,
		'success':function(data){
            if(typeof(data)!='object'){
                if(efun)efun('返回数据格式错误');
                return;
            }
			var msg,info,status;
			if('code' in data){
				status=parseInt(data.code);
				msg=data.msg;
				info=data.data;
				//wait
			}else{
				status=parseInt(data.status);
				msg=data.data;
				info=data.info;
			}
			var url='url' in data?data.url:'';
			if(status){
				if(sfun)sfun(msg,url);
			}else{
				if(efun)efun(msg,info);
			}
			echoLog(data);
		},
	});
	return obj;
}

//获取时间
function getNowDate(format,times){
	var nowDate=new Date();
	var newDate=times?new Date(times*1000):nowDate;
	if(format){
		var key='day@';
		var farr=format.indexOf(' ')==-1?[]:format.split(' ');
		if(farr.length<1)farr.push(format);
		var fi=[];
		for(var i in farr){
			if(farr[i].indexOf(key)==0){
				farr[i]=farr[i].substr(key.length);
				fi.push(i);
			}
		}
		var txt='';
		if(fi.length>0){
			var gg=Math.abs(newDate.getTime()-nowDate.getTime())/1000;
			if(gg<3600){
				var dw=newDate.getTime()<nowDate.getTime()?'前':'后';
				gg=parseInt(gg/60);
				return gg>0?gg+'分钟'+dw:'刚刚';
			}
			var day=Date.parse((nowDate.getMonth()+1)+'/'+nowDate.getDate()+'/'+nowDate.getFullYear());
			day=new Date(day);
			var ts=(newDate.getTime()-day.getTime())/1000/86400;
			ts=ts<0?0-Math.ceil(Math.abs(ts)):parseInt(ts);
			switch(ts){
				case 0:
					txt='今天';
				break;
				case 1:
					txt='明天';
				break;
				case 2:
					txt='后天';
				break;
				case -1:
					txt='昨天';
				break;
				case -2:
					txt='前天';
				break;
			}
		}
		if(txt){
			for(var i in farr){
				if(in_array(i,fi)){
					farr[i]=txt;
				}else{
					farr[i]=newDate.format(farr[i]);
				}
			}
			return farr.join(' ');
		}
		format=farr.join(' ');
		return newDate.format(format);
	}else{
		return newDate;
	}
	//alert(getNowDate('yyyy-MM-dd h:m:s',1464080078));	
}

//查找数组
function in_array(str,array){
    for(var i in array){
        if(array[i]==str){
            return true;
        }
    }
    return false;
}
//获取文件大小
function getFileSize(size){
	size=size?size:0;
	var dwi=0;
	var dw=Array('B','K','M','G','T');
	var zh=size.toString().substr(size.length-1,1).toUpperCase();
	if(isNaN(zh)){
		size=parseFloat(size.substr(0,size.length-1));
		while(zh!=dw[dwi] && dwi<dw.length){
			size=size*1024;
			dwi++;
		}
		return dwi<dw.length?size:false;
	}else{
		size=parseInt(size);
		while(size>1024 && dwi<dw.length){
			size=size/1024;
			dwi++;
		}
		return size.toFixed(2)+dw[dwi];
	}
}
//获取文件后缀
function getFileExt(name){
	var tmp=name.toString().split('.');
	return tmp.length>1?tmp[tmp.length-1].toLowerCase():'';
}
//progress
function alen_progress(opObj){
	this.max=100;
	this.i=0;
	this.cancel='取消';
	var name='alen_progress';
	
	if(!opObj)opObj={};
	if('max' in opObj)this.max=opObj.max;
	if('cancel' in opObj)this.cancel=opObj.cancel;
	
	var boxObj=$('#'+name);
	if(boxObj.length<1){
		var html='<div class="progress fr_mb10"><div class="progress-bar progress-bar-striped active" style="min-width: 2em; width: 0%">0%</div></div><div class="text-center progresstxt"><span>处理中<span>...【<b>'+this.i+'</b>/<b>'+this.max+'</b>】</div>';
		$('body').append(getModalHtml(name,'',{
			'close':false,
			'cancel':this.cancel,
		}));
		boxObj=$('#'+name);
	}
	setModaBody(name,html);
	boxObj.on('hidden.bs.modal', function(e){
		if(tmpthis.onClose)tmpthis.onClose();
	});
	this.onComplete;
	this.onClose;
	this.settxt=function(txt){
		txt=txt?txt:'处理中';
		boxObj.find('.progresstxt span').text(txt);
	}
	this.set=function(i){
		tmpthis.i=i;
		boxObj.find('.progresstxt b:first').text(tmpthis.i);
		boxObj.find('.progresstxt b:eq(1)').text(tmpthis.max);
		var bw=tmpthis.i>0?parseInt(tmpthis.i/tmpthis.max*100):0;
		boxObj.find('.progress-bar').css('width',bw+'%');
		boxObj.find('.progress-bar').text(bw+'%');
		if(tmpthis.i>=tmpthis.max){
			if(tmpthis.onComplete)tmpthis.onComplete();
		}
	}
	this.close=function(){
		boxObj.modal('hide');
	}
	this.show=function(){
		this.set(0);
		boxObj.modal({'backdrop':'static'});
	}
	var tmpthis=this;
	return this;
}
//alert
function alerts(str,til,data){
	/*
	if(window.top){
		window.top.alerts(str,til,data);
		return false;
	}
	*/
	if(!data)data={};
	if(!('confirm' in data))data.confirm='';
	if(!('cancel' in data))data.cancel='好的';
	if(('fun' in data))var backfun=data.fun;delete data.fun;
	var html;
	if(('html' in data))html=data.html;delete data.html;
	var name='alen_alert';
	var boxObj=$('#'+name);
	if(boxObj.length<1){
		$('body').append(getModalHtml(name,til,data));
		boxObj=$('#'+name);
		var backval=0;
		boxObj.on('hidden.bs.modal', function(e){
			boxObj.remove();
			if(backfun)backfun(backval);
		});
		boxObj.on('shown.bs.modal', function(){
			var but=boxObj.find(data.confirm?'.confirmbut':'.cancelbut');
			but.focus();
			if(('shown' in data))data.shown();
		});
		boxObj.find('.confirmbut').click(function(){
			backval=1;
			boxObj.modal('hide');
			return false;
		});
		boxObj.find('.cancelbut').click(function(){
			backval=('lessbut' in data)?false:0;
			boxObj.modal('hide');
			return false;
		});
		if(('lessbut' in data)){
			boxObj.find('.lessbut').click(function(){
				backval=2;
				boxObj.modal('hide');
				return false;
			});
		}
	}
	html=html?str:'<h4>'+str+'</h4>';
	setModaBody(name,html);
	boxObj.modal();
	return boxObj;
}
//hint
function alert_hint_close(){
	$('.ui_hint').remove();
}
function alert_hint(str,type,sj){
	type=type?type:'check';
	sj=sj?sj:1000;
	if(type=='spinner')type='circle-o-notch fa-spin';
	var html='<div class="ui_hint"><span>';
	html+='<i class="fa fa-'+type+'"></i><b>'+str+'</b>';
	html+='</span></div>';
	var obj=$(html);
	$('body').append(obj);
	
	obj.fadeIn(200,function(){
		if(sj>0){
			setTimeout(function(){
				obj.fadeOut(200,function(){
					obj.remove();
				});
			},sj);
		}
	});
	
}
//设置模块
function getModalHtml(id,til,data){
	if(typeof data!="object"){
		data={};
	}
	if(!('close' in data))data.close='true';
	if(!('cancel' in data))data.cancel='取消';
	if(!('confirm' in data))data.confirm='';
	if(!('less' in data))data.less='';
	var html='<div id="'+id+'" class="modal fade"><div class="modal-dialog"><div class="modal-content">';
	if(til){
		html+='<div class="modal-header">';
		if(data.close)html+='<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>';
		html+='<h4 class="modal-title">'+til+'</h4></div>';
	}
	html+='<div class="modal-body"></div>';
	var footerHtml='';
	if(data.less)footerHtml+=data.less;
	if(data.lessbut)footerHtml+='<button type="button" class="lessbut btn btn-default pull-left">'+data.lessbut+'</button>';
	if(data.cancel)footerHtml+='<button type="button" class="btn btn-default cancelbut" data-dismiss="modal">'+data.cancel+'</button>';
	if(data.confirm)footerHtml+='<button type="button" class="confirmbut btn btn-primary">'+data.confirm+'</button>';
	if(footerHtml)footerHtml='<div class="modal-footer">'+footerHtml+'</div>';
	html+=footerHtml+'</div></div></div>';
	return html;
}
function setModaBody(id,html){
	var o=$('#'+id+' .modal-body');
	if(html){
		o.html(html);
	}else{
		html=o.html();
	}
	return html;
}
function jsTime(times){
	var arr=[0,60,60,24];
	var dw=['秒','分','小时','天'];
	//var dw=['s','m','h','d'];
	times=parseInt(times);
	var str='';
	var i=0;
	do{
		str=dw[i]+str;
		i++;
		if(dw[i] && times>=arr[i]){
			str=(times%arr[i])+str;
			times=parseInt(times/arr[i]);
		}else{
			str=times+str;
			times=0;
		}
	}while(dw[i] && times>0);
	return str;
}
//判断移动端
function ifmobile(){
	var mobileAgent = new Array("iphone", "ipod", "ipad", "android", "mobile", "blackberry", "webos", "incognito", "webmate", "bada", "nokia", "lg", "ucweb", "skyfire");
	var browser = navigator.userAgent.toLowerCase(); 
	var isMobile = false; 
	for (var i=0; i<mobileAgent.length; i++){
		if(browser.indexOf(mobileAgent[i])!=-1){
			isMobile = true; 
			break; 
		}
	}
	return isMobile;
}
//获取指定URL的参数值  
function getUrlParam(name,url){
	url=url?url:window.location.search;
	if(!name){
		if(!url)return null;
		url=url.substr(1);
		var back={};
		url=url.split('&');
		for(var i in url){
			url[i]=url[i].split('=');
			back[url[i][0]]=decodeURIComponent(url[i][1]);
		};
		return back;
	}
    var pattern = new RegExp("[?&]"+name+"\=([^&]+)", "g");  
    var matcher = pattern.exec(url);  
    var items = null;  
    if(null != matcher){  
		try{  
		   items = decodeURIComponent(decodeURIComponent(matcher[1]));  
		}catch(e){  
		   try{  
				   items = decodeURIComponent(matcher[1]);  
		   }catch(e){  
				   items = matcher[1];  
		   }  
		}  
     }  
     return items;
}  
//新窗口打开连接
function openUrlNew(t_url){
	var tmpbox=$('<a></a>');
	var tmptxt=$('<span></span>');
	tmpbox.attr('target','_blank');
	tmpbox.attr('href',t_url);
	tmptxt.text('');
	tmpbox.append(tmptxt);
	tmpbox.hide();
	$('body').append(tmpbox);
	tmptxt.click();
	tmpbox.remove();
}
$(document).ready(function(){
	(function(){ //日期输入插件
		if($('.input-date').length<1)return;
		var tmpFun=function(){
			$('.input-date').map(function(){
				var o=$(this);
				var inp=o.find('input[type=text]');
				var format=inp.attr('data-format');
				var minView=0;
				if(format.indexOf('i')>-1 || format.indexOf('s')>-1){
					minView=0;
				}else if(format.indexOf('h')>-1){
					minView=1;
				}else if(format.indexOf('d')>-1){
					minView=2;
				}else if(format.indexOf('m')>-1){
					minView=3;
				}else if(format.indexOf('y')>-1){
					minView=4;
				}
				inp.datetimepicker({
					'language':'zh-CN',
					'format':format,
					'minView':minView,
					'minuteStep':2,
					'autoclose':true,
					'todayBtn':true,
				});
				o.removeClass('load');
				o.find('.icon-trash').parent().click(function(){
					inp.val('');
				});
			});
		}
		if(typeof($.fn.datetimepicker)=="undefined"){
			include(_sys.static_url+'Datetimepicker/css/bootstrap-datetimepicker.css');
			include(_sys.static_url+'Datetimepicker/js/bootstrap-datetimepicker.js','js',function(){
				include(_sys.static_url+'Datetimepicker/js/locales/bootstrap-datetimepicker.zh-CN.js','js',function(){
					tmpFun();
				});
			});
		}else{
			tmpFun();
		}
	})();
});
function clone(obj){  
    var o;  
    switch(typeof obj){  
    case 'undefined': break;  
    case 'string'   : o = obj + '';break;  
    case 'number'   : o = obj - 0;break;  
    case 'boolean'  : o = obj;break;  
    case 'object'   :  
        if(obj === null){  
            o = null;  
        }else{
            if(obj instanceof Array){  
                o = [];  
                for(var i = 0, len = obj.length; i < len; i++){  
                    o.push(clone(obj[i]));  
                }  
            }else{  
                o = {};  
                for(var k in obj){  
                    o[k] = clone(obj[k]);  
                }  
            }
        }  
        break;  
    default:          
        o = obj;break;  
    }  
    return o;     
} 

//获取指定key的hash值
function getHash(key,url) {
	var hash;
	if (!!url){
		hash = url.replace(/^.*?[#](.+?)(?:\?.+)?$/, "$1");
		hash = (hash == url) ? "" : hash;
	}else{
		hash = self.location.hash;
	}
	hash = "" + hash;
	hash = hash.replace(/^[?#]/, '');
	if(!key)return hash;
	hash = "&" + hash;
	var val = hash.match(new RegExp("[\&]" + key + "=([^\&]+)", "i"));
	if (val == null || val.length < 1){
		return null;
	}else{
		return decodeURIComponent(val[1]);
	}
}
/*
//transitionend animationend 事件
1、addTranEvent(elem,fn,duration)：用于绑定transtionend事件，处理掉多次执行的问题
2、addAnimEvent(elem,fn),removeAnimEvent(elem,fn)：分别用于绑定和解绑animationend事件
3、setStyleAttribute(elem,val)：用于设置css3的属性
*/
(function(root, factory){
	if(typeof define === 'function'){
		define(factory);
	}else if (typeof exports === 'object') {
		module.exports = factory;
	} else {
		root.WN = factory();
	}
})(this,function(){
	var WN = {},
		body=document.body || document.documentElement,
		style=body.style, 
		transition="transition",
		transitionEnd,
		animationEnd,
		vendorPrefix; 
	transition=transition.charAt(0).toUpperCase() + transition.substr(1);
	vendorPrefix=(function(){
		var  i=0, vendor=["Moz", "Webkit", "Khtml", "O", "ms"];
		while (i < vendor.length) {
			if (typeof style[vendor[i] + transition] === "string") {
				return vendor[i];
			}
			i++;
		}
		return false;
	})();
	transitionEnd=(function(){
		var transEndEventNames = {
			WebkitTransition : 'webkitTransitionEnd',
			MozTransition    : 'transitionend',
			OTransition      : 'oTransitionEnd otransitionend',
			transition       : 'transitionend'
		}
		for(var name in transEndEventNames){
			if(typeof style[name] === "string"){
				return transEndEventNames[name]
			}
		}
	})();
	animationEnd=(function(){
		var animEndEventNames = {
		WebkitAnimation : 'webkitAnimationEnd',
		animation      : 'animationend'
	}
	for(var name in animEndEventNames){
		if(typeof style[name] === "string"){
			return animEndEventNames[name]
			}
		}
	})();
	WN.addTranEvent=function(elem,fn,duration){
		duration=duration?duration:1;
		var called=false;
		var fncallback = function(){
			if(!called){
				fn();
				called=true;
			}
		};
		function hand(){    
			elem.addEventListener(transitionEnd, function () {
				elem.removeEventListener(transitionEnd, arguments.callee, false);
				fncallback();
			}, false);
		}
		setTimeout(hand,duration);
	};
	WN.addAnimEvent=function(elem,fn){
		elem.addEventListener(animationEnd,fn,false)
	};
	WN.removeAnimEvent=function(elem,fn){
		elem.removeEventListener(animationEnd,fn,false)
	};
	WN.setStyleAttribute=function(elem,val){
		if(Object.prototype.toString.call(val)==="[object Object]"){
			for(var name in val){
				if(/^transition|animation|transform/.test(name)){
					var styleName=name.charAt(0).toUpperCase() + name.substr(1);
					elem.style[vendorPrefix+styleName]=val[name];
				}else{
					elem.style[name]=val[name];
				}
			}
		}
	};
	WN.transitionEnd=transitionEnd;
	WN.vendorPrefix=vendorPrefix;
	WN.animationEnd=animationEnd;
	return WN;
});


if(window.navigator.standalone){
	echoLog('webApp模式');
	$('body').on('click','a[href]',returnFalse);
	/*
	$('body').hammer().on('tap',function(){
		var o=$(event.target);
		echoLog(o.prop("outerHTML"));
		if(!o.attr('href'))o=$(event.target).parents('a[href]:first');
		if(o.length<1)return;
		echoLog(o.prop("outerHTML"));
		//if(o.find('iframe').length>0)return;
		if(o.attr('data-nolink'))return;
		var url=o.attr('href');
		var target=o.attr('target');
		var winObj;
		switch(target){
			case  '_blank':
				openUrlNew(url);
				return false;
			break;
			case  '_parent':
				if(parent)winObj=parent.window;
			break;
			case  '_top':
				winObj=top.window;
			break;
			default:
				winObj=window.frames[target];
			break;
		}
		if(!winObj)winObj=window;
		echoLog(url);
		winObj.location.href=url;
		return false; 
    });
	*/
}