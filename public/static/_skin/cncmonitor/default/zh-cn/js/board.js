var cncBoard=function(type,workshop,_box,postUrl){
	_box=$(_box);
	var _this=this;
	var _err='';
	var _ajaxObj;
	var _upS=3000;
	var _errGd=3;
	var _res=10;
	var _winType;
	var _errObjArr=[];
	var _upTime;
	var _mobile=false;
	var _init=false;
	var _stop=false;
	var _sortArr=[
		function(a,b){
			//return a.id-b.id;
				return b.bn.warning-a.bn.warning;
				//return (b.bn.task+b.bn.race)-(a.bn.task+a.bn.race);
		},
		function(a,b){
			return a.bn-b.bn;
				//return b.bn-a.bn;
				//return b.mission_status-a.mission_status;
		}
	];
	var _fieldsArr=new Array(
		'id,power,run,standby,task,warning,status,error',
		'id,mission_status,finished,mission,workpiece,error,warning'
	);
	//设置UI
	var _setUI=function(){
		var html='<div class="tvBox"></div><div class="listBox"><ul><li class="x">';
		html+=type==1?'<div class="name">编号</div><div class="bar">状态比例</div>':'<div class="name x">编号</div><div class="workpiece">工件</div><div class="mission">任务</div><div class="bar x">状态比例</div>';
		html+='<div class="clearfix"></div></li></ul></div><div class="clearfix"></div><div class="lessList"><ul></ul><ul class="x"></ul><div class="noerr ui_txtof"><i class="fa fa-check-circle fr_mr5"></i><b>暂无报错信息</b></div></div>';
		_box.html(html);
	}
	//设置事件
	var _setEvt=function(){
		$(window).resize(function(){
			var bw=_box.width();
			var typeArr={
				'phone':768,
				'pad':992,
				'pc':1200,
				'mpc':1920,
				'max':0,
			};
			var tmp=0;
			var cls;
			for(var k in typeArr){
				if(typeArr[k]){			
					if(bw>tmp && bw<=typeArr[k]){
						tmp=typeArr[k];
						cls=k;
					}
				}else{
					if(!cls)cls=k;
					break;
				}
			}
			_winType=cls;
			var reBox=false;
			if(_winType=='phone' || _winType=='pad' || _winType=='pc'){
				if(!_mobile)reBox=true;
				_mobile=true;
				_box.find('.lessList,.tvBox,.listBox').addClass('mobile');
			}else{
				if(_mobile)reBox=true;
				_mobile=false;
				_box.find('.lessList,.tvBox,.listBox').removeClass('mobile');
			}
			var tmpFun=function(){
				var th=_box.find('.tvBox').outerHeight();
				_box.find('.listBox').height(_box.find('.tvBox').outerHeight());
				_box.find('.lessList').height(_box.height()-th-10);
				_setMasonry();
			}
			if(reBox && _init && !_stop){
				if(_ajaxObj){_ajaxObj.abort();_ajaxObj=false;}
				if(_upTime){
					clearTimeout(_upTime);
					_upTime=false;
				}
				_errObjArr=[];
				_box.find('.lessList ul').html('');
				_box.find('.lessList ul').removeAttr('style');
				_upTime=setTimeout(function(){
					tmpFun();
					_upFun();
				},1000);
				return;
				//_upFun();
			}
			tmpFun();
			
		});
		_box.on('mouseenter','.objCol .obj',function(){
			var o=$(this);
			if(!o.attr('data-tip')){
				o.attr('data-tip',true);
				o.tooltip();
				o.tooltip('show');
			}
		});
	}
	//设置错误区
	var _setMasonry=function(){
		if(_mobile){
			var masonryBox=_box.find('.lessList ul.x');
			var t_li=masonryBox.find('li');
			if(t_li.length<1){
				_box.find('.lessList ul:first').removeAttr('style');
				_box.find('.lessList').addClass('x');
				return false;
			}
			_box.find('.lessList').removeClass('x');
			var prev,left,t;
			t_li.map(function(){
				var o=$(this);
				if(!left)left=0;
				if(!t)t=0;
				if(prev){
					t=parseInt(prev.attr('data-top'))+prev.outerHeight(true);
					if(t+o.outerHeight(true)>masonryBox.height()){
						left+=prev.outerWidth(true);
						t=0;
					}
				}
				o.attr('data-top',t);
				o.css({'position':'absolute','left':left+'px','top':t+'px'});
				prev=o;
			});
			_box.find('.lessList ul:first').width(left+t_li.last().outerWidth(true));
		}else{
			
			var masonryBox=_box.find('.lessList ul:first');
			if(_errObjArr.length<1){
				masonryBox.find('li').remove();
				_box.find('.lessList').addClass('x');
				return false;
			}
			_box.find('.lessList').removeClass('x');
			var x_box=_box.find('.lessList ul.x');
			var prev,left,o,t;
			for(var i in _errObjArr){
				o=_box.find('.'+_errObjArr[i].id+'_copy');
				if(o.length<1){
					o=_box.find('.'+_errObjArr[i].id).clone();
					o.addClass(_errObjArr[i].id+'_copy');
				}else{
					o.find('p').remove();
					_box.find('.'+_errObjArr[i].id+' p').map(function(){
						o.append($(this).clone());
					});
				}
				o.addClass('js_temp');
				if(!left)left=0;
				if(!t)t=0;
				masonryBox.append(o);
				if(prev){
					t=parseInt(prev.attr('data-top'))+prev.outerHeight(true);
					if(t+o.outerHeight(true)>masonryBox.height()){
						if(left>0){
							
							o.remove();
							
							break;
						}
						left+=prev.outerWidth(true);
						t=0;
					}
				}
				o.attr('data-top',t);
				o.css({'position':'absolute','left':left+'px','top':t+'px'});
				prev=o;
			}
			masonryBox.find('li').not('.js_temp').remove();
			masonryBox.find('li').removeClass('js_temp');
			var x_obj=_box.find('.lessList ul.x li.x');
			if(x_obj.length>0)x_box.css('margin-top',0-x_obj.position().top+'px');
		}
	}
	//设置图标区
	var _setIcon=function(){
		var cjName='s_'+workshop;
		var gs=0;
		var html='';
		var data,tooltipData;
		for(i in cjDtat[cjName]){
			data=cjDtat[cjName][i];
			if(data.length>gs)gs=data.length;
			if(data.length>1){
				html+='<div class="objCol">';
				for(n in data){
					tooltipData='';
					if(data[n]<=0){
						html+='<div class="obj x"><div class="boxBg"></div></div>';
					}else{
						tooltipData=' data-toggle="tooltip" data-placement="bottom" title="#'+data[n]+'"';
						if(type==1){
							html+='<div'+tooltipData+' class="obj round id_'+data[n]+'" data-id="'+data[n]+'"><span>'+data[n]+'</span><div class="boxBg"><i class="iconfont icon-jichuang"></i></div><div class="boxBg1"></div></div>';
						}else{
							html+='<div'+tooltipData+' class="obj id_'+data[n]+'" data-id="'+data[n]+'"><span>'+data[n]+'</span><div class="boxBg"><i class="iconfont icon-renwu"></i></div></div>';
						}
					}
				}
				
				html+='<div class="clearfix"></div></div>';
			}else{
				html+='<div class="objCol x progress-bar-striped"></div>'
			}
		}
		_box.find('.tvBox').html(html);
		_box.find('.tvBox .obj').css('width',(parseInt(100/gs*100)/100)+'%');
	}
	//获取信息主函数
	var _getData=function(times,bFun){
		var postData={'fields':_fieldsArr[type-1],'workshop':workshop};
		if(times)postData.time=times;
		_ajaxObj=alenPost(postUrl,postData,function(data){
			_ajaxObj=false;
			
			var html,tmpObj,bn;
			for(var first in data)break;
			data=data[first];
			var cor;
			if(type==1){
				cor=[
					'#999',
					opArr.run.color,
					opArr.standby.color,
					opArr.warning.color,
					'#1B7C1B',
				];
				var gss=24*3600;
				for(i in data){
					data[i].bn={
						'task':parseInt(data[i].task/gss*10000)/100,
						'standby':parseInt(data[i].standby/gss*10000)/100,
						'warning':parseInt(data[i].warning/gss*10000)/100,
					};
					data[i].bn.race =parseInt((data[i].run-data[i].task)/gss*10000)/100;
				}
				data.sort(_sortArr[type-1]);
				//主面板
				for(i in data){
					_box.find('.id_'+data[i].id).find('.boxBg').css('color',cor[data[i].status]);
					tmpObj=_box.find('.listBox .listId_'+data[i].id);
					bn=data[i].bn
					if(tmpObj.length){
						tmpObj.find('.progress-bar:first').css('width',bn.task+'%');
						tmpObj.find('.progress-bar:eq(1)').css('width',bn.race+'%');
						tmpObj.find('.progress-bar:eq(2)').css('width',bn.standby+'%');
						tmpObj.find('.progress-bar:eq(3)').css('width',bn.warning+'%');
					}else{
						html='<li class="listId_'+data[i].id+'"><div class="name">#'+data[i].id+'</div><div class="bar"><div class="progress fr_mb0"><div class="progress-bar progress-bar-striped x" style="width: '+bn.task+'%"></div><div class="progress-bar progress-bar-success progress-bar-striped" style="width: '+bn.race+'%"></div><div class="progress-bar progress-bar-warning progress-bar-striped" style="width: '+bn.standby+'%"></div><div class="progress-bar progress-bar-danger progress-bar-striped" style="width: '+bn.warning+'%"></div></div></div><div class="clearfix"></div></li>';
						_box.find('.listBox ul').append(html);
					}
				}
			}else{
				cor=[
					opArr.standby.color,
					opArr.run.color,
					opArr.warning.color,
				];
				for(var i in data){
					data[i].bn=parseInt(data[i].mission)?parseInt(data[i].finished/data[i].mission*100):100;
				}
				data.sort(_sortArr[type-1]);
				//主面板
				var o_p;
				for(i in data){
					//$('#id_'+data[i].id).find('.boxBg').css('background',cor[data[i].mission_status]);
					_box.find('.id_'+data[i].id).find('.boxBg').css('color',cor[data[i].mission_status]);
					tmpObj=_box.find('.listBox .listId_'+data[i].id);
					bn=data[i].bn
					if(tmpObj.length){
						tmpObj.find('.mission b').text(data[i].finished);
						tmpObj.find('.mission small').text(data[i].mission);
						tmpObj.find('.progress-bar').css('width',bn+'%');
						if(o_p){
							tmpObj.insertAfter(o_p);
						}else{
							tmpObj.insertBefore(tmpObj.siblings(':first'));
						}
						o_p=tmpObj;
					}else{
						html='<li class="listId_'+data[i].id+'"><div class="name x small">#'+data[i].id+'</div><div class="workpiece small ui_txtof">'+data[i].workpiece+'</div><div class="mission"><b>'+data[i].finished+'</b>/<small>'+data[i].mission+'</small></div><div class="bar x"><div class="progress fr_mb0"><div class="progress-bar progress-bar-striped" style="width: '+bn+'%"></div></div></div><div class="clearfix"></div></li>';
						_box.find('.listBox ul').append(html);	
					}			
				}
			}
			
			
			//错误面板
			var errObj;
			_errObjArr=[];
			for(i in data){
				errObj=_box.find('.err_'+data[i].id);
				if(objCount(data[i].error)<1){
					errObj.remove();
					continue;
				}
				if(errObj.length>0){
					html='';
					errObj.find('p').remove();
					for(var n in data[i].error){html+='<p class="text-danger">'+n+':'+data[i].error[n]+'</p>';}
					errObj.append(html);
				}else{
					html='<li class="err_'+data[i].id+'"><i class="fa fa-warning text-danger"></i><b>#'+data[i].id+'</b><small class="text-muted fr_ml10">'+jsTime(data[i].warning)+'</small>';
					for(var n in data[i].error){
						html+='<p class="text-danger">'+n+':'+data[i].error[n]+'</p>';
					}
					html+='</li>';
					_box.find('.lessList ul.x').append(html);
				}
				if(!_mobile)_errObjArr.push({'id':'err_'+data[i].id,'val':data[i].warning});
			}
			if(!_mobile)_errObjArr.sort(function(a,b){return b.val-a.val;});
			_setMasonry();
			
			if(bFun)bFun();
		},
		function(err){
			_ajaxObj=false;
			if(bFun){
				bFun(err);
			}else{
				alerts(err);
			}
		});
	}
	//定时更新函数
	var _upFun=function(){
		if(_ajaxObj){_ajaxObj.abort();_ajaxObj=false;}
		if(_upTime){
			clearTimeout(_upTime);
			_upTime=false;
		}
		_getData(null,function(err){
			if(err){
				var o=alerts(err,'',{
					'cancel':'重试 - <span class="respan">'+_res+'</span>秒',
					'fun':function(){
						clearInterval(reTime);
						reTime=null;
						_upFun();
					}
				});
				var reTime=setInterval(function(){
					var io=$(o).find('.respan');
					i=parseInt(io.text());
					i--;
					if(i<=0){
						clearInterval(reTime);
						reTime=null;
						o.modal('hide')
						return false;
					}
					io.text(i);
				},1000);
			}else{
				if(!_mobile){
					//pc端最新错误提示
					var gdBox=_box.find('.lessList ul.x');
					if(gdBox.find('li').length>0){
						var s=gdBox.attr('data-s');
						if(s){
							s++;
							if(s>=_errGd){
								s=0;
								var m_id,m_stop;
								gdBox.find('li').map(function(){
									if(m_stop)return;
									var o=$(this);
									if(_box.height()-o.offset().top-o.outerHeight(true)<0){
										m_stop=true;
										return;
									}
									m_id=o;
								});
								if(m_id.next().length<1)m_id=gdBox.find('li:first');
								gdBox.find('li').removeClass('x');
								m_id.addClass('x');
								setTimeout(function(){
									gdBox.animate({'margin-top':0-m_id.position().top+'px'},1000);
								},1000);
							}
						}else{
							s=0;
						}
						gdBox.attr('data-s',s);
					}
				}
				_upTime=setTimeout(_upFun,_upS);
			}
		});
	}
	/********/
	_this.stop=function(){
		if(_ajaxObj){_ajaxObj.abort();_ajaxObj=false;}
		if(_upTime){
			clearTimeout(_upTime);
			_upTime=false;
		}
		_errObjArr=[];
		_box.find('.lessList ul').html('');
		_box.find('.lessList ul').removeAttr('style');
		_stop=true;
	}
	_this.init=function(){
		if(!type)_err='请指定数据类型';
		if(!workshop)_err='请指定车间';
		if(_err){
			alerts(_err);
			return;
		}
		_setUI();
		_setEvt();
		_setIcon();
		$(window).resize();
		_init=true;
	}
	_this.show=function(){
		if(_err){
			alerts(_err);
			return;
		}
		_stop=false;
		_upFun();
	}
};