var panFun=function(data){
	var e=data || event;
	panMianFun(function(ev,box){
		var panType=box.attr('data-pan');
		if(!panType)return;
		var range=20;
		var time=100;
		if(ev.deltaTime<time)return;
		if(panType!='y'){
			if(Math.abs(ev.deltaX)>Math.abs(ev.deltaY) && Math.abs(ev.deltaX)>=range){
				box.attr('data-pan','y');
				box.addClass('pan');
			}else{
				box.removeAttr('data-pan');
			}
			return;
		}
		var obj=box.find('.fr_left');
		var ift=box.hasClass('x')?(ev.deltaX>obj.width() || ev.deltaX < 0):(ev.deltaX<0-obj.width() || ev.deltaX > 0);
		if(ift)return;
		obj.css('margin-left',ev.deltaX+'px');
		ev.preventDefault();
	},data,'panFun');
	return false;
}
var panedFun=function(data){
	var e=data || event;
	panMianFun(function(ev,box){
		if(!box.attr('data-pan'))return;
		box.removeClass('pan');
		box.find('.fr_left').removeAttr('style');
		if(ev.deltaX>0){
			box.removeClass('x');
		}else if(ev.deltaX<0){
			box.addClass('x');
		}
		box.removeAttr('data-pan');
		ev.preventDefault();
	},data,'panedFun');
	return false;
}
var panstartFun=function(data){
	var e=data || event;
	panMianFun(function(ev,box){
		box.attr('data-pan',true);
		ev.preventDefault();
	},data,'panstartFun');
	return false;
}
var panMianFun=function(fun,data,fname){
	var box=$('.fr_frame');
	if(box.length>0){
		fun(data,box);
	}else{
		if(top[fname]){
			box=$(top).find('.fr_frame');
			top[fname](data,box);
		}
	}
}
if($ifmobile && (typeof(noHammer)=='undefined' || !noHammer)){
	$('body').hammer().on('pan',panFun);
	$('body').hammer().on('panstart',panstartFun);
	$('body').hammer().on('panend',panedFun);
	$('body').hammer().on('pancancel',panedFun);
}
