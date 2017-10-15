var _alenExplorer_extends={};
function _alenExplorer(_op_box,_op_url){
	var box;
	var _this=this;
	//当前路径
	var _path;
	//选中对象
	var _selectboard=new Array();
	//剪贴板
	var _clipboard=new Array();
	//上传对象
	var _file=new _alenUpload();
	_file.fileSize='500M';
	//上一层命令是否生效
	_backspace=true;
	//是否为剪切
	_shear=false;
	//命令URL
	var _url;
	var _winType;
	//内置命令集
	var _cmd={
		//获取信息
		'getinfo':function(path,type,backFun){
			_showhint('加载中...','spinner',-1);
			alenPost(_url.getInfo,{'path':path,'type':type,},function(data){
				_hidehint();
				if(backFun)backFun(data);
			},function(err){
				_hidehint();				
				alerts(err);
			});
		},
		//打开
		'open':function(path,type,backFun){
			var dirFun=function(tmp){
				_path=tmp;
				_cmd.refurbish(backFun);
			}
			if(path=='\\'){
				dirFun(path);
				return;
			}
			_showhint('加载中...','spinner',-1);
			alenPost(_url.getInfo,{'path':path,'type':type,},function(data){
				_hidehint();
				if(data.type=='dir'){
					dirFun(data.path);
				}else if(data.type=='file'){
					if(!_path){
						var tmp=data.path.split('\\');
						tmp.splice(-1,1);
						tmp=tmp.join('\\')+'\\';
						dirFun(tmp)
					}
					var ext=data.less;
					if(ext in _runExt){
						if(_runExt[ext].run in _runFile){
							if('close' in _runFile[_runExt[ext].run])software.onClose=_runFile[_runExt[ext].run].close;
							if('size' in _runFile[_runExt[ext].run])software.onSize=_runFile[_runExt[ext].run].size;
							if('ui' in _runFile[_runExt[ext].run])software.ui=_runFile[_runExt[ext].run].ui;
							if('before' in _runFile[_runExt[ext].run])_runFile[_runExt[ext].run].before(data.id,box,software);
							software.init(_runExt[ext].txt);
							software.show();							
							_runFile[_runExt[ext].run].fun(data.id,box,software);
							if(_winType=='phone'){
								software.size();
							}
						}else{
							alerts('找不到对应的方法打开此文件');
						}
						return;
					}
					alerts('暂不支持打开此文件');
				}
			},function(err){
				_hidehint();				
				alerts(err);
			});
		},
		//重命名
		'rename':function(fileID,type,name,backFun){
			if(!name){
				alerts('名称不能为空');
				return;
			}
			_showhint('提交中...','spinner',-1);
			alenPost(_url.rename,{'id':fileID,'type':type,'name':name},function(data){
				_hidehint();
				if(backFun)backFun(data);
			},function(err){
				_hidehint();
				alerts(err);
			});
		},
		//刷新
		'refurbish':function(backFun){
			_showhint('加载中...','spinner',-1);
			alenPost(_url.getList,{'path':_path},function(data){
				window.location.href='#'+_path;
				_clear();
				box.find('.alen_toptools .pathTools input').val(_path);
				for(var i in data){
					if(data[i].type=='dir'){
						_addDir({'id':data[i].id,'name':data[i].name});
					}else if(data[i].type=='file'){
						_addFile({'id':data[i].id,'name':data[i].name,'less':data[i].less});
					}
				}
				_hidehint();
				if(backFun)backFun(data);
			},function(err){
				_hidehint();
				alerts(err);
			});
		},
		//移动
		'move':function(oldPath,newPath,backFun){
			_showhint('移动中...','spinner',-1);
			alenPost(_url.move,{'ids':oldPath,'path':newPath},function(data){
				_hidehint();
				if(backFun)backFun(data);
			},function(err){
				_hidehint();
				alerts(err);
			});
		},
		//复制
		'copy':function(oldPath,newPath,backFun){
			_showhint('复制中...','spinner',-1);
			alenPost(_url.copy,{'ids':oldPath,'path':newPath},function(data){
				_hidehint();
				if(backFun)backFun(data);
			},function(err){
				_hidehint();
				alerts(err);
			});
		},
		//删除
		'del':function(ids,backFun){
			_showhint('删除中...','spinner',-1);
			alenPost(_url.del,{'ids':ids},function(data){
				_hidehint();
				if(backFun)backFun(data);
			},function(err){
				_hidehint();
				alerts(err);
			});
		},
		//新建文件夹
		'adddir':function(name,path,backFun){
			if(!name){
				alerts('名称不能为空');
				return;
			}
			_showhint('新建中...','spinner',-1);
			alenPost(_url.addDir,{'name':name,'fid':path,},function(data){
				_hidehint();
				if(backFun)backFun(data);
			},function(err){
				_hidehint();
				alerts(err);
			});
		},
		//上传文件
		'upload':function(path,backFun){
			path=path?path:_path;
			if('upload' in _url)_file.uploadUrl=_url.upload;
			_file.onComplete=function(data){
				_showhint('处理中...','spinner',-1);
				var fileData=new Array();
				var errStr='出现错误<br />';
				for(var i in data){
					if('err' in data[i].back){
						errStr+='<br />'+data[i].file.name+':'+data[i].back.err;
					}else{
						fileData.push({'id':data[i].back.id,'name':name=data[i].file.name});
					}
				}
				if(fileData.length>0){
					var str=JSON.stringify(fileData);
					alenPost(_url.addFile,{'data':str,'path':path},function(data){
						_hidehint();
						if(backFun)backFun(data);
					},function(err){
						_hidehint();
						alerts(err);
					});
				}else{
					_hidehint();
					alerts(errStr);
				}
			}
			_file.onError=function(err){
				_backspace=true;
				alerts(err);
			}
			_file.onSelect=function(){
				_backspace=false;
			}
			_file.showFile(true);
		},
		//返回上一层
		'backup':function(backFun){
			if(_path=='\\')return;
			var pathArr=_path.split('\\');
			pathArr.splice(-2,2);
			_path=pathArr.join('\\')+'\\';
			_cmd.refurbish(backFun);
		
		},
		//下载
		'download':function(path,backFun){
			var url=_url.download+'?path='+path;
			openUrlNew(url);
			if(backFun)backFun(url);
		},
		//获取文件内容
		'gettxt':function(path,backFun){
			_showhint('读取中...','spinner',-1);
			alenPost(_url.getTxt,{'path':path},function(data){
				_hidehint();
				if(backFun)backFun(data);
			},function(err){
				_hidehint();
				alerts(err);
			});
		},
		//保存文本文件
		'savetxt':function(path,content,backFun){
			_showhint('保存中...','spinner',-1);
			alenPost(_url.saveTxt,{'path':path,'content':content},function(data){
				_hidehint();
				if(backFun)backFun(data);
			},function(err){
				_hidehint();
				alerts(err);
			});
		},
		//获取文件真实url
		'getUrl':function(ids,backFun){
			alenPost(_url.getUrl,{'id':ids},function(data){
				_hidehint();
				if(backFun)backFun(data);
			},function(err){
				_hidehint();
				alerts(err);
			});
		},
		
		
	};
	//右键菜单
	var _navCol={
		'rename':{
			'name':'重命名',
			'iocn':'pencil',
			'disable':['multi','none'],
			'less':function(){return (_sys.os=='mac' || _sys.os=='ios')?'Return':'F2';},
			'fun':function(){
				if(_selectboard.length!=1)return;
				var til='给';
				til+=_selectboard[0].type=='dir'?'文件夹':'文件';
				til+='【'+_selectboard[0].name+'】重命名：';
				var alenObj=alerts('<P>'+til+'</p><input class="form-control" value="'+_selectboard[0].name+'" placeholder="请输入名称" />','',{
					'html':true,
					'confirm':'提交',
					'cancel':'取消',
					'shown':function(){
						var inp=alenObj.find('input');
						inp.focus();
						inp.select();
					},
					'fun':function(val){
						if(val){
							var str=alenObj.find('input').val();
							if(str==_selectboard[0].name)return;
							_cmd.rename(_selectboard[0].id,_selectboard[0].type,str,function(data){
								box.find('.alen_filebox .alen_file.focus .name').text(data);
							});
						}
					}
				});
			}
		},
		'download':{
			'name':'下载文件',
			'iocn':'cloud-download',
			'disable':['dir','none'],
			'fun':function(){
				for(var i in _selectboard){
					_cmd.download(_selectboard[i].id)
				}
			}
		},
		'link1':false,
		'refurbish':{
			'name':'刷新',
			'iocn':'refresh',
			'disable':[],
			'less':'F5',
			'fun':_cmd.refurbish
		},
		'link2':false,
		'copy':{
			'name':'复制',
			'iocn':'files-o',
			'disable':['none'],
			'less':function(){return (_sys.os=='mac' || _sys.os=='ios')?'⌘ + C':'Ctrl + C';},
			'fun':function(){
				if(_selectboard.length<1)return;
				_shear=false;
				_clipboard=_selectboard;
				box.find('.alen_file').removeClass('shear');
				_showhint('复制成功','files-o',0,true);
			}
		},
		'sheared':{
			'name':'剪切',
			'iocn':'scissors',
			'disable':['none'],
			'less':function(){return (_sys.os=='mac' || _sys.os=='ios')?'⌘ + X':'Ctrl + X';},
			'fun':function(){
				if(_selectboard.length<1)return;
				_shear=true;
				_clipboard=_selectboard;
				box.find('.alen_file').removeClass('shear');
				for(var i in _clipboard){box.find('.alen_file[data-id='+_clipboard[i].id+']').addClass('shear');}
				_showhint('剪切成功','scissors',0,true);
			}
		},
		'paste':{
			'name':'粘贴',
			'iocn':'clipboard',
			'disable':['clipnone'],
			'less':function(){return (_sys.os=='mac' || _sys.os=='ios')?'⌘ + V':'Ctrl + V';},
			'fun':function(){
				if(_clipboard.length<1)return;
				var cmdName=_shear?'move':'copy';
				var ids=new Array();
				for(var i in _clipboard){
					ids.push(_clipboard[i].id+'|'+_clipboard[i].type);
				}
				ids=ids.join(',');
				_cmd[cmdName](ids,_path,function(data){
					_clipboard=new Array();
					_cmd.refurbish();
				});
			}
		},
		'del':{
			'name':'删除',
			'iocn':'trash',
			'disable':['none'],
			'less':'Delete',
			'fun':function(){
				if(_selectboard.length!=1)return;
				alerts('您确定要删除么？','',{
					'confirm':'删除',
					'cancel':'取消',
					'fun':function(val){
						if(1!=val)return;
						var ids=new Array();
						for(var i in _selectboard){
							ids.push(_selectboard[i].id+'|'+_selectboard[i].type);
						}
						ids=ids.join(',');
						_cmd.del(ids,function(data){
							_cmd.refurbish();
						});
					}
				});
			}
		},
		'link3':false,
		'adddir':{
			'name':'新建文件夹',
			'iocn':'folder',
			'disable':[],
			'fun':function(){
				var alenObj=alerts('<P>新建文件夹</p><input class="form-control" placeholder="请输入名称" />','',{
					'html':true,
					'confirm':'提交',
					'cancel':'取消',
					'shown':function(){
						var inp=alenObj.find('input');
						inp.focus();
						inp.select();
					},
					'fun':function(val){
						if(val){
							var str=alenObj.find('input').val();
							_cmd.adddir(str,_path,function(data){
								_addDir({'id':data,'name':str});
							});
						}
					}
				});
			}
		},
		'upload':{
			'name':'上传文件',
			'iocn':'upload',
			'disable':[],
			'fun':function(){
				_cmd.upload(_path,function(data){
					for(var i in data){
						_addFile({'id':data[i].id,'name':data[i].name,'less':data[i].less});
					}
				});
			}
		},
	};
	//拓展
	var _runExt={};
	var _runFile={};
	var softwareBox;
	var software={
		'onClose':false,
		'onSize':false,
		'isClose':true,
		'minWidth':0,
		'minHeight':0,
		'obj':null,
		'ui':true,
		'init':function(txt){
			txt=txt?txt:'';
			var ww=box.width()/2;
			if(ww<this.minWidth)ww=this.minWidth;
			var hh=box.height()/2;
			if(hh<this.minHeight)hh=this.minHeight;
			softwareBox.css({
				'width':ww,
				'height':hh,
			});
			softwareBox.find('.til span').text(txt);
			this.center();
		},
		'show':function(html){
			if(this.ui){
				softwareBox.find('.software_content').html(html);
				box.find('.software').removeClass('hide');
			}else{
				box.find('.software').addClass('hide');
			}
			box.find('.softwareBg').removeClass('hide');
			this.reMH();
		},
		'hide':function(){
			if(false==this.isClose)return;
			softwareBox.find('.software_content').html('');
			softwareBox.removeClass('s_max');
			box.find('.softwareBg').addClass('hide');
			if(this.onClose)this.onClose(box,this);
		},
		'size':function(){
			if(false==this.ui)return;
			if(softwareBox.hasClass('s_max')){
				if(_winType=='phone')return;
				softwareBox.removeClass('s_max');
				var tmp=softwareBox.attr('data-tmp').split(',');
				softwareBox.removeAttr('data-tmp');
				softwareBox.css({
					'left':tmp[0]+'px',
					'top':tmp[1]+'px',
					'width':tmp[2]+'px',
					'height':tmp[3]+'px',
				});
			}else{
				softwareBox.addClass('s_max');
				softwareBox.attr('data-tmp',parseInt(softwareBox.css('left'))+','+parseInt(softwareBox.css('top'))+','+softwareBox.width()+','+softwareBox.height());
				softwareBox.css({
					'top':0,
					'left':0,
					'width':'100%',
					'height':'100%',
				});
			}
			this.reMH();
		},
		'reMH':function(){
			if(false==this.ui)return;
			softwareBox.find('.software_content').css('height',softwareBox.height()-softwareBox.find('.til').outerHeight(true));
		},
		'center':function(){
			if(false==this.ui)return;
			softwareBox.css({
				'top':(box.height()-softwareBox.height())/2,
				'left':(box.width()-softwareBox.width())/2,
			});
		},
	};
	//清空文件容器
	var _clear=function(){
		_selectboard=new Array();
		box.find('.alen_bottomtools .info b').text('0');
		box.find('.alen_filebox').html('');
	};
	//隐藏提示
	var _hidehint=function(){
		_backspace=true;
		box.find('.alen_exp_loadBox').removeClass('x');
		box.find('.alen_exp_loadBoxBg').removeClass('x');
	};
	//显示提示
	var _showhint=function(txt,type,sj,nobg){
		_backspace=false;
		type=type?type:'check';
		sj=sj?sj:1000;
		box.find('.alen_exp_loadBox i').removeClass();
		box.find('.alen_exp_loadBox i').addClass('fa');
		if(type=='spinner'){
			type='circle-o-notch';
			box.find('.alen_exp_loadBox i').addClass('fa-spin');
		}
		type='fa-'+type;
		box.find('.alen_exp_loadBox i').addClass(type);
		box.find('.alen_exp_loadBox span').text(txt);
		if(sj>0){
			WN.addTranEvent(box.find('.alen_exp_loadBox')[0],function(){
				setTimeout(function(){
					_hidehint();
				},sj);
			});
		}
		box.find('.alen_exp_loadBox').addClass('x');
		if(!nobg)box.find('.alen_exp_loadBoxBg').addClass('x');
	};
	//右键菜单状态
	var _mousenavStatus=false;
	//设置事件
	var _setEvent=function(){
		$(window).hashchange(function(){
			var tmp=getHash();
			//echoLog(tmp);
			_runCmd(decodeURIComponent(tmp));
		});
		//窗口变化时改变容器大小
		$(window).resize(function(){
			var bw=box.width();
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
				box.removeClass(k);
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
			if(_winType=='phone')box.find('.alen_mousenav').removeAttr('style');
			box.addClass(_winType);
			var boxH=box.height()-box.find('.alen_toptools').outerHeight(true)-box.find('.alen_bottomtools').outerHeight(true)-10;
			box.find('.alen_filebox').height(boxH);
			software.reMH();
		});
		//软件
		softwareBox.find('.but_close').hammer().on('tap',function(){software.hide();});
		softwareBox.find('.but_max').hammer().on('tap',function(){software.size();});
		softwareBox.find('.til').hammer().on('pan',function(e){
			if(software.obj.hasClass('s_max'))return;
			e=e||event;
			var tmp=software.obj.attr('data-xy');
			if(!tmp)return;
			tmp=tmp.split(",");
			software.obj.css({
				'left':parseFloat(tmp[0])+e.deltaX+'px',
				'top':parseFloat(tmp[1])+e.deltaY+'px',
			});
		});
		softwareBox.find('.til').hammer().on('panstart',function(e){
			if(software.obj.hasClass('s_max'))return;
			software.obj.attr('data-xy',software.obj.position().left+','+software.obj.position().top);
		});
		softwareBox.find('.til').hammer().on('panend',function(e){
			var left=parseInt(software.obj.css('left'));
			var ww=software.obj.width();
			if(left<0){
				left=0;	
			}else if(left>box.width()-ww){
				left=box.width()-ww;
			}
			var top=parseInt(software.obj.css('top'));
			var hh=software.obj.find('.til').height();
			if(top<0){
				top=0;	
			}else if(top>box.height()-hh){
				top=box.height()-hh;
			}
			software.obj.css({
				'left':left,
				'top':top,
			});	
			software.obj.removeAttr('data-xy');
		});

		softwareBox.find('.move').hammer().on('pan',function(e){
			e=e||event;
			var tmp=software.obj.attr('data-wh');
			if(!tmp)return;
			tmp=tmp.split(",");
			tmp[0]=parseFloat(tmp[0])+e.deltaX;
			tmp[1]=parseFloat(tmp[1])+e.deltaY;
			if(tmp[0]<software.minWidth)tmp[0]=software.minWidth;
			if(tmp[1]<software.minHeight)tmp[1]=software.minHeight;
			software.obj.css({
				'width':tmp[0]+'px',
				'height':tmp[1]+'px',
			});
			software.reMH();
			if(software.onSize)software.onSize(box,software);
		});
		softwareBox.find('.move').hammer().on('panstart',function(e){
			software.obj.attr('data-wh',software.obj.width()+','+software.obj.height());
		});
		softwareBox.find('.move').hammer().on('panend',function(e){
			software.obj.removeAttr('data-wh');
		});
		softwareBox.find('.til').hammer().on('doubletap',function(e){
			software.size();
		});
		
		//点击其他地方隐藏右键菜单
		var bodyFun=function(){
			if(_showMousenav){
				var obj=$(event.target).parents('.alen_mousenav');
				if(obj.length>0)return false;
				_hideMousenav();
			}
		}
		if(!$ifmobile){
			$('body').mousedown(bodyFun);	
		}
		//按钮命令
		box.find('[data-cmd]').click(function(){
			var o=$(this);
			_cmd[o.attr('data-cmd')]();
		});
		box.find('[data-navcol]').click(function(){
			var o=$(this);
			_navCol[o.attr('data-navcol')].fun();
		});
		box.find('.pathTools input').keyup(function(event){
			var code=event.which || event.keyCode ;
			if(13==code){
				if($('#alen_alert').length>0)return;
				if(box.find('.softwareBg:visible').length>0)return;
				box.find('.pathTools button').click();
				return false;
			}
		});
		box.find('.pathTools button').click(function(){
			var str=trim(box.find('.pathTools input').val());
			if(!str){
				alerts('请输入路径');
				return;
			}
			_runCmd(str);
		});
		
		
		//禁用系统右键菜单
		box.bind("contextmenu",function(){return false;});
		//禁止选中文本
		box[0].onselectstart=function(){return false;};
		//快捷键
		var shiftKey=false;
		$('body').keydown(function(event){
			var code=event.which || event.keyCode ;
			var obj=$(event.target);
			var cmdKey=false;
			if(_sys.os=='mac' || _sys.os=='ios'){
				cmdKey=event.metaKey;
			}else{
				cmdKey=event.ctrlKey;
			}
			if(_mousenavStatus && code!=32)_hideMousenav();
			switch(code){
				case 16:	//shift
				case 17:	//ctrl
					shiftKey=true;
				break;
				case 37:     //向左
				case 39:     //向右
					if(_mousenavStatus)return;
					if('input'==obj[0].nodeName.toLowerCase())return;
					if($('#alen_alert').length>0)return;
					if(box.find('.softwareBg:visible').length>0)return;
					if(_selectboard.length<1)return;
					if(code==37){
						var tmpObj=box.find('.alen_file.focus:first').prev();
						if(tmpObj.length<1)tmpObj=box.find('.alen_file:last');
					}else{
						var tmpObj=box.find('.alen_file.focus:last').next();
						if(tmpObj.length<1)tmpObj=box.find('.alen_file:first');
					}
					_select();
					_select(tmpObj);
				break;
				case 38:     //向上
				case 40:     //向下
					if(cmdKey){
						if(_sys.os=='mac' || _sys.os=='ios'){
							if(_mousenavStatus)return;
							if('input'==obj[0].nodeName.toLowerCase())return;
							if($('#alen_alert').length>0)return;
							if(box.find('.softwareBg:visible').length>0)return;
							if(38==code){
								if(!_backspace)return;
								_cmd.backup();
							}else{
								box.find('.alen_mousenav .but_open').click();
							}
						}
					}else{
						var gs=parseInt(box.find('.alen_filebox').width()/box.find('.alen_file').outerWidth(true));
						var ii=box.find('.alen_file.focus:last').prevAll().length;
						var zs=box.find('.alen_file').length;
						var mb=ii;
						if(38==code){
							if(ii-gs<0){
								while(mb<zs){
									mb+=gs;
								}	
							}
							mb-=gs;
						}else{
							if(ii+gs>=zs){
								while(mb>=0){
									mb-=gs;
								}
							}
							mb+=gs;
						}
						if(mb<0 || mb>=zs)return;
						_select();
						_select(box.find('.alen_file:eq('+mb+')'));
					}
				break;
				case 65:	//a
					if(cmdKey){
						if('input'==obj[0].nodeName.toLowerCase())return;
						if($('#alen_alert').length>0)return;
						if(box.find('.softwareBg:visible').length>0)return;
						box.find('.alen_file').map(function(){_select($(this));});
						return false;
					}
				break;
				case 67:	//c
					if(cmdKey){
						if('input'==obj[0].nodeName.toLowerCase())return;
						if($('#alen_alert').length>0)return;
						if(box.find('.softwareBg:visible').length>0)return;
						_navCol.copy.fun();
						return false;
					}
				break;
				case 78:	//n
					if(cmdKey && event.shiftKey){
						if('input'==obj[0].nodeName.toLowerCase())return;
						if($('#alen_alert').length>0)return;
						if(box.find('.softwareBg:visible').length>0)return;
						echoLog('aa');
						return false;
					}
				break;
				case 86:	//v
					if(cmdKey){
						if('input'==obj[0].nodeName.toLowerCase())return;
						if($('#alen_alert').length>0)return;
						if(box.find('.softwareBg:visible').length>0)return;
						_navCol.paste.fun();
						return false;
					}
				break;
				case 88:	//x
					if(cmdKey){
						if('input'==obj[0].nodeName.toLowerCase())return;
						if($('#alen_alert').length>0)return;
						if(box.find('.softwareBg:visible').length>0)return;
						_navCol.sheared.fun();
						return false;
					}
				break;
				case 8:	  //backspace
					if('input'==obj[0].nodeName.toLowerCase())return;
					if($('#alen_alert').length>0)return;
					if(box.find('.softwareBg:visible').length>0)return;
					if(!_backspace)return;
				break;
				case 112: //F1
				case 113: //F2
				case 115: //F4
				case 116: //F5
				case 123: //F12
					return false;
				break;
			}
		});
		$('body').keyup(function(event){
			var code=event.which || event.keyCode ;
			var obj=$(event.target);
			var delFun=function(){
				if($('#alen_alert').length>0)return;
				if(box.find('.softwareBg:visible').length>0)return;
				_navCol.del.fun();
			}
			var rename=function(){
				if($('#alen_alert').length>0)return;
				if(box.find('.softwareBg:visible').length>0)return;
				_navCol.rename.fun();
			}
			switch(code){
				case 8:		//backspace
					if('input'==obj[0].nodeName.toLowerCase())return;
					if($('#alen_alert').length>0)return;
					if(box.find('.softwareBg:visible').length>0)return;
					if(_sys.os=='mac' || _sys.os=='ios'){
						delFun();
					}else{
						if(!_backspace)return;
						_cmd.backup();
					}
				break;
				case 13:	//enter
					if('input'==obj[0].nodeName.toLowerCase())return;
					if($('#alen_alert').length>0)return;
					if(box.find('.softwareBg:visible').length>0)return;
					if(_sys.os=='mac' || _sys.os=='ios'){
						rename();
					}else{
						box.find('.alen_mousenav .but_open').click();
					}
				break;
				case 16:	//shift
				case 17:	//ctrl
					shiftKey=false;
				break;
				case 32:    //空格
					if('input'==obj[0].nodeName.toLowerCase())return;
					if($('#alen_alert').length>0)return;
					if(box.find('.softwareBg:visible').length>0)return;
					if(_selectboard.length<1){
						_select(box.find('.alen_file:first'));
					}else{
						if(_mousenavStatus){
							_hideMousenav();
						}else{
							var tmpObj=box.find('.alen_file.focus:last');
							var ll=tmpObj.offset().left-box.offset().left+(tmpObj.width()/2);
							var tt=tmpObj.offset().top-box.offset().top+(tmpObj.height()/2);
							_showMousenav(ll,tt);
						}
					}
				break;
				case 46: //del
					if('input'==obj[0].nodeName.toLowerCase())return;
					if($('#alen_alert').length>0)return;
					if(box.find('.softwareBg:visible').length>0)return;
					delFun();
				break;
				case 113: //F2
					if('input'==obj[0].nodeName.toLowerCase())return;
					if($('#alen_alert').length>0)return;
					if(box.find('.softwareBg:visible').length>0)return;
					rename();
				break;
				case 115: //F4
					if('input'==obj[0].nodeName.toLowerCase())return;
					if($('#alen_alert').length>0)return;
					if(box.find('.softwareBg:visible').length>0)return;
					box.find('.alen_toptools .pathTools input').focus();
				break;
				case 116: //F5
					if('input'==obj[0].nodeName.toLowerCase())return;
					if($('#alen_alert').length>0)return;
					if(box.find('.softwareBg:visible').length>0)return;
					_cmd.refurbish();
				break;
			}
		});
		//点击选中
		var tapFun=function(e){
			e=e||event;
			var o=$(e.target).parents('.alen_file');
			if(o.length>0){
				if(shiftKey){
					_select(o,o.hasClass('focus')?true:false);
				}else{
					if(!o.hasClass('focus') || 3!=e.which)_select();
					_select(o);
				}
			}else{
				if(!shiftKey)_select();
			}
		}
		
		//右键&&长按菜单
		var pressFun=function(e){
			e=e||event;
			e.pageX=e.pageX||e.center.x;
			e.pageY=e.pageY||e.center.y;
			tapFun(e);
			_showMousenav(e.pageX-box.offset().left,e.pageY-box.offset().top);
			
		}
		if($ifmobile){
			box.find('.alen_filebox').hammer().on('press',pressFun);
			box.find('.alen_filebox').hammer().on('tap',function(e){
				tapFun(e);
				box.find('.alen_mousenav .but_open').click();
			});
		}else{
			box.find('.alen_filebox').hammer().on('tap',tapFun);
			box.find('.alen_filebox').mousedown(function(e){
				e=e||event;
				if(3==e.which){
					pressFun(e);
					return false;
				}
			});
			//拉选框
			var selectBoxFun=function(ev){
				var o=box.find('.alen_selectBox');
				var cssData={
					'left':(ev.deltaX>0?ev.center.x-ev.deltaX:ev.center.x)-box.offset().left,
					'top':(ev.deltaY>0?ev.center.y-ev.deltaY:ev.center.y)-box.offset().top,
					'width':Math.abs(ev.deltaX),
					'height':Math.abs(ev.deltaY),
				};
				o.css(cssData);
				var selectFun=function(obj){
					if(shiftKey && obj.attr('data-lock'))return;
					var to=obj.find('a:first');
					var ifx=[cssData.left,cssData.left+cssData.width];
					var ify=[cssData.top,cssData.top+cssData.height];
					var boxX=[to.position().left,to.position().left+to.outerWidth()];
					var boxY=[to.position().top,to.position().top+to.outerHeight()];
					var x=false;
					var y=false;
					for(var i in boxX){
						if(x)break;
						if(boxX[i]>=ifx[0] && boxX[i]<=ifx[1])x=true;
						if(ifx[i]>=boxX[0] && ifx[i]<=boxX[1])x=true;
					}
					for(i in boxY){
						if(y)break;
						if(boxY[i]>=ify[0] && boxY[i]<=ify[1])y=true;
						if(ify[i]>=boxY[0] && ify[i]<=boxY[1])y=true;
					}
					if(x && y){
						if(!obj.hasClass('focus'))_select(obj);
					}else{
						if(obj.hasClass('focus'))_select(obj,true);
					}
				}
				box.find('.alen_file').map(function(){selectFun($(this));});
			}
			//拖动文件
			var moveFun=function(e){
				e=e||event;
				var o=box.find('.alen_moveicon');
				var tw=_selectboard.length>1?o.find('span').outerWidth():o.outerWidth();
				var cssData={
					'top':(e.center.y-box.offset().top-(o.outerHeight()/2))+'px',
					'left':(e.center.x-box.offset().left-(tw/2))+'px',
				};
				o.css(cssData);
				var b=focusFun(e.center.x-box.offset().left,e.center.y-box.offset().top,'hover');
				if(!b)box.find('.alen_filebox .alen_file.hover').removeClass('hover');
			}
			var focusFun=function(x,y,cls){
				var obj;
				box.find('.alen_filebox .alen_file').map(function(){
					if(obj)return;
					var o=$(this);
					var ifx=false;
					var ify=false;
					if(x>=o.position().left && x<=o.position().left+o.width()){
						ifx=true
					}
					if(y>=o.position().top && y<=o.position().top+o.height()){
						ify=true;
					}
					if(ifx && ify)obj=o;
				});
				if(obj){
					if('focus'==cls){
						if(!shiftKey)_select();
						_select(obj);
					}else{
						if(!obj.hasClass(cls)){
							box.find('.alen_filebox .alen_file').removeClass(cls);
							obj.addClass(cls);
						}
					}
					return true;
				}
				return false;
			}
			var _ifmove=false;
			box.find('.alen_filebox').hammer().on('panstart',function(e){
				e=e||event;
				e.pageX=e.pageX||e.center.x;
				e.pageY=e.pageY||e.center.y;
				
				var obj=$(e.target).parents('.alen_file')
				if(obj.length){
					_ifmove=true;
					if(!obj.hasClass('focus'))focusFun(e.pageX-box.offset().left,e.pageY-box.offset().top,'focus');
					box.find('.alen_moveicon').addClass('show');
					if(_selectboard.length>1){
						box.find('.alen_moveicon a').removeAttr('style');
						box.find('.alen_moveicon a').html('<span>'+_selectboard.length+'</span>')
					}else{
						box.find('.alen_moveicon a').html(box.find('.alen_file.focus .img').html());
						box.find('.alen_moveicon a').css('color',box.find('.alen_file.focus .img i').css('color'));
					}
				}else{
					box.find('.alen_selectBox').addClass('show');
					box.find('.alen_file.focus').attr('data-lock',true);
				}
			});
			box.find('.alen_filebox').hammer().on('pan',function(e){
				e=e||event;
				if(_ifmove){
					moveFun(e);
				}else{
					selectBoxFun(e);
				}
			});
			box.find('.alen_filebox').hammer().on('panend',function(){
				if(_ifmove){
					_ifmove=false;
					box.find('.alen_moveicon').removeClass('show');
					box.find('.alen_moveicon').removeAttr('style');
					var obj=box.find('.alen_filebox .alen_file.hover:first');
					if('dir'==obj.attr('data-type')){
						var dirID=obj.attr('data-id');
						echoLog(dirID);
					}
					obj.removeClass('hover');
				}else{
					box.find('.alen_selectBox').removeClass('show');
					box.find('.alen_selectBox').removeAttr('style');
					box.find('.alen_file.focus').removeAttr('data-lock');
				}
			});
			//双击打开
			box.on('dblclick','.alen_file',function(){
				box.find('.alen_mousenav .but_open').click();
			});
		}
		//点击右键菜单
		box.find('.alen_mousenav').on('click','li',function(){
			var o=$(this);
			if(o.hasClass('disabled'))return false;
			var a=o.find('a');
			var cmd=a.attr('class').split('_')[1];
			if('open'==cmd){
				if(_selectboard.length==1)_cmd.open(_selectboard[0].id,_selectboard[0].type);
			}else{
				_navCol[cmd].fun();	
			}
			_hideMousenav();
			return false;
		});
		box.find('.alen_exp_loadBoxBg').on('click',function(){
			if($(this).hasClass('navBg'))_hideMousenav();
		});
		
		
	}
	//设置右键菜单
	var _setMousenav=function(){
		var openBut=false;
		var disable={'none':[],'clip':[],'clipnone':[],'multi':[],'one':[],'dir':[],'file':[]};
		for(var i in _navCol){
			if(_navCol[i]){
				for(var v in _navCol[i].disable){
					for(var k in disable){
						if(k==_navCol[i].disable[v]){
							disable[k].push(i);
							break;
						}
					}
				}
			}
		}
		var disableCol=[];
		if(_selectboard.length==0){
			disableCol.push('none');
		}else if(_selectboard.length==1){
			disableCol.push('one');
			openBut=true;
		}else{
			disableCol.push('multi');
		}
		disableCol.push(_clipboard.length<1?'clipnone':'clip');
		for(i in _selectboard){
			if(_selectboard[i].type=='dir'){
				disableCol.push('dir');
			}else if(_selectboard[i].type=='file'){
				disableCol.push('file');
			}
		}
		var ids=[];
		for(i in disableCol){
			for(v in disable[disableCol[i]]){
				ids.push(disable[disableCol[i]][v]);
			}
		}
		box.find('.alen_mousenav .lessNav').remove();
		var html,less;
		for(i in _navCol){
			if(_navCol[i]){
				less='';
				if('less' in _navCol[i] && _navCol[i].less){
					less=((typeof _navCol[i].less=='string')&&_navCol[i].less.constructor==String)?_navCol[i].less:_navCol[i].less();
					less='<small class="text-muted">'+less+'</small>';
				}
				html='<li class="lessNav"><a class="but_'+i+'"><i class="fa fa-'+_navCol[i].iocn+' fr_mr5"></i><span>'+_navCol[i].name+'</span>'+less+'</a></li>';
				html=$(html);
				if(in_array(i,ids)){
					html.addClass('disabled');
				}
			}else{
				html='<li class="lessNav divider"></li>';
			}
			box.find('.alen_mousenav').append(html);
		}
		var openObj=box.find('.alen_mousenav .but_open').parent();
		var tmpStr='打开';
		if(openBut){
			if(_selectboard[0].type=='file'){
				var ext=box.find('.alen_file.focus').attr('data-ext');
				if(ext in _runExt)tmpStr=_runExt[ext].txt;
			}
			openObj.removeClass('disabled');
		}else{	
			openObj.addClass('disabled');
		}
		openObj.find('span').text(tmpStr);
	}
	//显示右键菜单
	var _showMousenav=function(x,y){
		_setMousenav();
		var o=box.find('.alen_mousenav');
		o.addClass('show');
		if(_winType=='phone'){
			box.find('.alen_exp_loadBoxBg').addClass('x');
			box.find('.alen_exp_loadBoxBg').addClass('navBg');
		}else{
			if(x+o.outerWidth()>box.outerWidth())x=x-o.outerWidth();
			if(y+o.outerHeight()>box.outerHeight())y=y-o.outerHeight();
			o.css({'left':x+'px','top':y+'px'});
			if(_winType=='pad'){
				box.find('.alen_exp_loadBoxBg').addClass('x');
				box.find('.alen_exp_loadBoxBg').addClass('navBg');
			}
		}
		_mousenavStatus=true;
	}
	//隐藏右键菜单
	var _hideMousenav=function(){
		if(_winType=='phone' || _winType=='pad'){
			box.find('.alen_exp_loadBoxBg').removeClass('x');
			box.find('.alen_exp_loadBoxBg').removeClass('navBg');
		}
		box.find('.alen_mousenav').removeClass('show');
		box.find('.alen_mousenav li').removeClass('disabled');
		_mousenavStatus=false;
	}
	//选中项目
	var _select=function(Obj,del){
		del=del?true:false;
		var txtObj=box.find('.alen_bottomtools .info b:eq(2)');
		if(!Obj){
			_selectboard=new Array();
			box.find('.alen_file').removeClass('focus');
			txtObj.text(0);
			return;
		}
		if(del){
			if(!Obj.hasClass('focus'))return;
			var id=Obj.attr('data-id');
			for(var i in _selectboard){
				if(_selectboard[i].id==id){
					_selectboard.splice(i,1);
					break;
				}
			}
			Obj.removeClass('focus');
		}else{
			if(Obj.hasClass('focus'))return;
			_selectboard.push({
				'id':Obj.attr('data-id'),
				'name':Obj.find('.name').text(),
				'type':Obj.attr('data-type'),
			});
			Obj.addClass('focus');
		}
		txtObj.text(parseInt(txtObj.text())+(del?-1:1));
	}
	//添加文件夹项目
	var _addDir=function(data){
		var html='<div class="alen_file" data-type="dir" data-id="'+data.id+'"><a><span class="img"><i class="fa fa-folder-open"></i></span><span class="name ui_txtof">'+data.name+'</span></a></div>';
		box.find('.alen_filebox').append(html);
		var txtObj=box.find('.alen_bottomtools .info b:first');
		txtObj.text(parseInt(txtObj.text())+1);
	}
	//添加文件项目
	var _addFile=function(data){
		var icon;
		if(data.less in _runExt){
			icon=((typeof _runExt[data.less].icon=='string')&&_runExt[data.less].icon.constructor==String)?_runExt[data.less].icon:_runExt[data.less].icon(data.less,data.id);
		}else{
			icon='<i class="fa fa-file"></i>';
		}
		var html='<div class="alen_file file '+data.less+'" data-ext="'+data.less+'" data-type="file" data-id="'+data.id+'"><a><span class="img">'+icon+'</span><span title="'+data.name+'" class="name ui_txtof">'+data.name+'</span></a></div>';
		box.find('.alen_filebox').append(html);
		var txtObj=box.find('.alen_bottomtools .info b:eq(1)');
		txtObj.text(parseInt(txtObj.text())+1);
	}
	//运行命令
	var _runCmd=function(str){
		if(str==_path)return;
		//echoLog(str);
		//echoLog(_path);
		_cmd.open(str);
	}
	//初始化函数
	var _init=function(){
		//自动安装扩展
		if(objCount(_alenExplorer_extends)>0){
			for(var tmpI in _alenExplorer_extends){
				_alenExplorer_extends[tmpI].explorerObj=_this;
				_this.install(_alenExplorer_extends[tmpI].data);
			}
		}
		var tmp='<li><a class="but_open"><i class="fa fa-sign-in fr_mr5"></i><span>打开</span><small class="text-muted">'+(_sys.OS=='mac' || _sys.OS=='ios'?'⌘ + ↓':'Enter')+'</small></a></li>';
		box.find('.alen_mousenav').append(tmp);
		_setEvent();
		$(window).resize();
		var tmpCmd=getHash();
		if(tmpCmd){
			_runCmd(decodeURIComponent(tmpCmd));
		}else{
			_path='\\';
			_cmd.refurbish();
		}
	}
	/**********************/
	//设置命令URL
	this.setUrl=function(urlData){
		_url=urlData;
	}
	//安装扩展
	this.install=function(tmpExtend){
		var tmp={
			'txt':tmpExtend.txt,
			'icon':tmpExtend.icon,
			'run':tmpExtend.name,
		};
		for(var i in tmpExtend.ext){
			_runExt[tmpExtend.ext[i].toUpperCase()]=tmp;
		}
		_runFile[tmpExtend.name]={'fun':tmpExtend.fun};
		if('before' in tmpExtend)_runFile[tmpExtend.name].before=tmpExtend.before;
		if('close' in tmpExtend)_runFile[tmpExtend.name].close=tmpExtend.close;
		if('size' in tmpExtend)_runFile[tmpExtend.name].size=tmpExtend.size;
		_runFile[tmpExtend.name].ui=('ui' in tmpExtend)?tmpExtend.ui:true;
	}
	//cmd
	this.cmd=function(){
		for(var i=0;i<=4;i++){
			if(!arguments[i])arguments[i]=null;
		}
		_cmd[arguments[0]](arguments[1],arguments[2],arguments[3],arguments[4]);
	}
	//初始化
	this.init=function(){
		var htmlUrl;
		if((typeof _op_url=='string')&&_op_url.constructor==String){
			htmlUrl=_op_url;
		}else{
			htmlUrl=_op_url._htmlFile;
			delete _op_url._htmlFile;
			_url=_op_url;
		}
		var tmpBox=$(_op_box);
		tmpBox.load(htmlUrl,function(){
			box=tmpBox.find('.alen_explorer');
			softwareBox=box.find('.softwareBg .software');
			software.obj=softwareBox;
			_init();
		});
	}
}