//加密
function easy_encrypt(str){
	var maxs=65535;
	var mmStr='';
	var strArr=new Array();
	var key=0;
	for(var i=0;i<str.length;i++){
		var ascii=str.charCodeAt(i);
		key+=ascii
		strArr.push(ascii);
	}
	key=parseInt(key/str.length);
	for(i in strArr){
		strArr[i]+=key;
		if(strArr[i]>maxs)strArr[i]-=maxs;
	}
	strArr.unshift(key*2);
	mmStr=strArr.join('A');
	return mmStr;
}
//解密
function easy_decrypt(str){
	var maxs=65535;
	var mmStr='';
	var strArr=str.split('A');
	var key=strArr.shift()/2;
	for(i in strArr){
		strArr[i]=strArr[i]-key;
		if(strArr[i]<0)strArr[i]+=maxs;
		mmStr+=String.fromCharCode(strArr[i]);
	}
	return mmStr;
}
//添加微信函数
var addWeixinFun=function(tfun){
	if(top!=window){
		top.addWeixinFun(tfun);
		return false;
	}
	if(window._WeixinFun){
		if(window._WeixinFun=="end"){
			tfun();
		}else{
			window._WeixinFun[window._WeixinFun.length]=tfun;
		}
	}else{
		window._WeixinFun=[tfun];
	}
}
//选择文件
var selectFile;
if(_sys.view=='weixin' && _sys.os!='ios'){
	if(top==window){
		selectFile=function(tmp_op,tmp_tfun){
			alert_hint('加载模块','spinner',-1);
			addWeixinFun(function(){
				selectFile=function(op,tfun){
					var multiple=op.multiple?9:1;
					wx.chooseImage({
						count: multiple,
						sizeType: ['original', 'compressed'], // 可以指定是原图还是压缩图，默认二者都有
						sourceType: ['album', 'camera'], // 可以指定来源是相册还是相机，默认二者都有
						success: function (ares) {
							alert_hint('加载图片','spinner',-1);
							var tmplocalIds = ares.localIds; // 返回选定照片的本地ID列表，localId可以作为img标签的src属性显示图片
							var zs=tmplocalIds.length;
							var wc=0;
							var backlist=[];
							var lesslist=[];
							var errstr='';
							var endfun=function(){
								if(wc<zs)return false;
								$('.ui_hint').remove();
								if(errstr){
									alerts(errstr+'个文件加载出错！',false,{'fun':function(){
										if(backlist.length)tfun(backlist,true,lesslist);
									}});
								}else{
									tfun(backlist,true,lesslist);
								}
							}
							for(var i in tmplocalIds){
								wx.uploadImage({
									localId: tmplocalIds[i], // 需要上传的图片的本地ID，由chooseImage接口获得
									isShowProgressTips: 0, // 默认为1，显示进度提示
									success: function (res) {
										var serverId = res.serverId; // 返回图片的服务器端ID
										alenPost(_sys.upload_wximg_url,{'mid':serverId,'type':'base64'},function(data){
											wc++;
											backlist[backlist.length]=data;
											lesslist[lesslist.length]=serverId;
											endfun();
										},function(err){
											wc++;
											errstr=parseInt(errstr)+1;
											endfun();
										});
									}
								});
							}
						}
					});
				}
				$('.ui_hint').remove();
				selectFile(tmp_op,tmp_tfun);
			});
		}
	}else{
		selectFile=function(op,tfun){
			top.selectFile(op,tfun);
		}
	}
	/*

	*/
}else{
	selectFile=function(op,tfun){
		var multiple=op.multiple?true:false;
		var exts=op.exts?op.exts:'';
		var accept='*.*';
		if(exts){
			var tmp=Array();
			for(var i in exts){tmp[i]='.'+exts[i].toLowerCase();}
			accept=tmp.join(',');
		}
		if(window._selectFileObj){
			window._selectFileObj.unbind();
			window._selectFileObj.val('');
		}else{
			window._selectFileObj=$('<input type="file" />');
		}
		window._selectFileObj.attr('accept',accept)
		window._selectFileObj.attr('multiple',multiple?multiple:this.multiple);
		window._selectFileObj.on("change", function(){
			if (!this.files.length) return false;
			var files = this.files;
			//if(tfun)tfun(files,false,Array('v72uX5urp4QWZRv-jX3QNO7vEby2h0Jg_OCAmAGegPA7zlKyuG9ofccNK-kHc1XL'));
			if(tfun)tfun(files);
		});
		window._selectFileObj.click();
	}
}

//浏览图片
var imgZoom;
if(top==window){
	if(_sys.view=='weixin'){
		imgZoom=function(o,tmpOP){
			alert_hint('加载图片','spinner',-1);
			addWeixinFun(function(){
				imgZoom=function(o,tmpOP){
					o=$(o);
					var pic =o.attr('data-img')
					var piclist=[];
					$('[data-img]').map(function(){
						var to=$(this);
						piclist[piclist.length]=to.attr('data-img');
					});
					wx.previewImage({
						current: pic, // 当前显示图片的http链接
						urls: piclist // 需要预览的图片http链接列表
					});
					return false;
				}
				$('.ui_hint').remove();
				imgZoom(o,tmpOP);
			});
		}
	}else{
		imgZoom=function(o,tmpOP){
			alert_hint('加载图片','spinner',-1);
			var piclist=[];
			var thisi,boxObj;
			var zs=0;
			var html='<div class="pswp" tabindex="-1" role="dialog" aria-hidden="true"><div class="pswp__bg"></div><div class="pswp__scroll-wrap"><div class="pswp__container"><div class="pswp__item"></div><div class="pswp__item"></div><div class="pswp__item"></div></div><div class="pswp__ui pswp__ui--hidden"><div class="pswp__top-bar"><div class="pswp__counter"></div><button class="pswp__button pswp__button--close" title="关闭"></button><button class="pswp__button pswp__button--fs" title="全屏"></button><button class="pswp__button pswp__button--zoom" title="缩放"></button><div class="pswp__preloader"><div class="pswp__preloader__icn"><div class="pswp__preloader__cut"><div class="pswp__preloader__donut"></div></div></div></div></div><div class="pswp__share-modal pswp__share-modal--hidden pswp__single-tap"><div class="pswp__share-tooltip"></div></div><button class="pswp__button pswp__button--arrow--left" title="Previous (arrow left)"></button><button class="pswp__button pswp__button--arrow--right" title="Next (arrow right)"></button><div class="pswp__caption"><div class="pswp__caption__center"></div></div></div></div></div>';
			var getdata=function(){
				$('.ui_hint').remove();
				o=$(o);
				var tmpObj=o;
				tmpObj.add(boxObj?$(boxObj).find('[data-img]'):'[data-img]');		
				//var tmpObj=boxObj?$(boxObj).find('[data-img]'):$('[data-img]');
				//alert(tmpObj.length);
				if(tmpObj.length<1)return;
				tmpObj.map(function(){
					zs++;
					var to=$(this);
					var imgurl=to.attr('data-img');
					piclist[piclist.length]=imgurl;
					if(imgurl==o.attr('data-img')){
						thisi=zs-1;
					}
				});
			}
			var tmpFun=function(){
				var op = {'index':0};
				var endFun=false;
				if(isNull(tmpOP))tmpOP={};
				if('_close' in tmpOP){
					endFun=tmpOP._close;
					delete tmpOP._close;
				}
				if('_box' in tmpOP){
					boxObj=tmpOP._box;
					delete tmpOP._box;
				}
				if(!('history' in tmpOP))tmpOP.history=false;
				
				for(var tmpI in tmpOP){op[tmpI]=tmpOP[tmpI];}
				getdata();
				if(piclist.length<1)return;
				var items = [];
				var loaddata={'html':'<div class="iu_page_load1"><i class="icon-spinner icon-spin"></i><b>加载中</b></div>',}
				for(var i=0;i<piclist.length;i++){items[i]=loaddata;}
				op.index=thisi;
				var pswpElement = document.querySelectorAll('.pswp')[0];
				var gallery = new PhotoSwipe( pswpElement, PhotoSwipeUI_Default, items,op);
				if(endFun)gallery.listen('close',endFun);
				gallery.listen('beforeChange', function(index){
					if(index && index>0){
						thisi++;
						thisi=thisi>zs-1?0:thisi;
					}else if(index && index<0){
						thisi--;
						thisi=thisi<0?zs-1:thisi;
					}
					if(!gallery.items[thisi].src){
						var gi=thisi+1;
						gi--;
						setTimeout(function(){
							loadImg(piclist[gi],function(img){
								gallery.items[gi]={
									src: img.src,
									w: img.width,
									h: img.height,
								};
								gallery.invalidateCurrItems();
								gallery.updateSize(true);
							});
						},0);
					}
				});
				gallery.init();
				return false;
			}
			if(typeof(PhotoSwipe)=="undefined"){
				include(_sys.static_url+'PhotoSwipe/photoswipe.css');
				include(_sys.static_url+'PhotoSwipe/default-skin/default-skin.css');
				include(_sys.static_url+'PhotoSwipe/photoswipe.min.js','js',function(){
					include(_sys.static_url+'PhotoSwipe/photoswipe-ui-default.min.js','js',function(){
						$('body').append(html);
						tmpFun();
					});
				});
			}else{
				tmpFun();
			}
		}
	}
}else{
	imgZoom=function(o,tmpOP){
		top.imgZoom(o,tmpOP);
	}
}

//构建视频
var setVideo=function(obj,plugin){
	plugin=plugin?'videojs':false;
	var tmpFun=	function(){
		$(obj).map(function(){
			var o=$(this);
			o.on('contextmenu',function(){return false;});
			var box=o.parent();
			var videoObj,videven,settime;
			var readonly=o.attr('readonly')?true:false;
			if(plugin=='videojs'){
				videojs.options.flash.swf = _sys.static_url+'Videojs/video-js.swf';
				o.addClass('video-js');
				o.addClass('vjs-big-play-centered');
				videoObj=videojs(o[0],{
					//width:o.width(),
					//height:o.height(),
					language:'zh-CN',
					//techOrder: ["html5","flash"],
				});
				videven=function(type,fun){
					videoObj.on(type,fun);
				}
				box=o.parent();
				settime=function(tt){
					if(!isNaN(tt)){
						videoObj.currentTime(tt);
					}else{
						return videoObj.currentTime();
					}
				}	
				if(top!=window)box.find('.vjs-fullscreen-control').hide();
				var controls=box.hasClass('vjs-controls-disabled')?false:true;
				if(!controls){
					box.removeClass('vjs-controls-disabled');
					o.click(function(){
						box.attr('data-click',true);
						setTimeout(function(){
							if(box.attr('data-click')){
								box.removeAttr('data-click');
								if(!videoObj.ended() && !videoObj.paused()){
									videoObj.pause();
								}else{
									videoObj.play();
								}
							}
						},300);
						return false;
					});
				}
				o.dblclick(function(){
					box.removeAttr('data-click');
					box.find('.vjs-fullscreen-control').click();
				});
				if(readonly)box.find('.vjs-progress-control').hide();
			}else{
				videoObj=o[0];
				videven=function(type,fun){
					videoObj.addEventListener(type,fun);
				}
				settime=function(tt){
					if(!isNaN(tt)){
						videoObj.currentTime=tt;
					}else{
						return videoObj.currentTime;
					}
				}
				var controls=o.attr('controls')?true:false;
				
				var playBut=$('<i class="vidBut icon-play"></i>');
				box.append(playBut);
				playBut.click(function(){
					videoObj.play();
				});
				playBut.hide();
				var clicktimes;
				$(videoObj).click(function(){
					if($(videoObj).attr('data-click')){ //双击
						$(videoObj).removeAttr('data-click');
						clearTimeout(clicktimes);
						if(getFullscreen()){
							exitFullscreen();
						}else{
							setFullscreen(videoObj);
						}
					}else{  //单击
						$(videoObj).attr('data-click',true);
						clicktimes=setTimeout(function(){
							$(videoObj).removeAttr('data-click');
							if(!videoObj.ended && !videoObj.paused){
								videoObj.pause();
							}else{
								videoObj.play();
							}
						},300);
					}
				});
				var loadBut=$('<i class="vidBut icon-spinner icon-spin"></i>');
				box.append(loadBut);
			}
			var shangTime=0;
			var readonlyFun={
				'playing':function(){
					if(shangTime==0){
						if(settime()>0){
							shangTime=0;
							settime(shangTime);
						}
					}
				},
				'timeupdate':function(){
					tmpTime=settime();
					if(tmpTime-shangTime>1 || tmpTime-shangTime<-1){
						settime(shangTime);
					}else{
						shangTime=tmpTime;
					}
				},
			};
			videven('canplay',function(){
				if(!plugin){
					loadBut.hide();
					playBut.show();
				}
			});
			videven('play', function(){
				if(!plugin){
					loadBut.show();
					playBut.hide();
				}else{
					box.find('.vjs-big-play-button').removeAttr('style');
				}
			});
			videven('pause', function(){
				if(!plugin){
					playBut.show();
				}else{
					box.find('.vjs-big-play-button').show();
				}
			});
			videven('playing',function(){
				if(readonly)readonlyFun.playing();
				if(!plugin){
					loadBut.hide();
					playBut.hide();
				}else{
					if(!controls)box.addClass('vjs-controls-disabled');
				}
			});
			videven('timeupdate',function(){
				if(readonly){
					readonlyFun.timeupdate();
				}else{
					shangTime=settime();
				}
			});
			videven('ended',function(){
				shangTime=0;
				if(getFullscreen())exitFullscreen();
			});
			videven('progress',function(){
				if(!plugin){
					if(shangTime==0)return;
					tmpTime=settime();
					if(tmpTime==shangTime){
						if(!videoObj.paused)loadBut.show();
					}else{
						loadBut.hide();
					}
				}
			});
		});
	}
	if(typeof(videojs)=="undefined" && plugin=='videojs'){
		alert_hint('加载播放器','spinner',-1);
		include(_sys.static_url+'Videojs/video-js.css');
		include(_sys.static_url+'Videojs/video.js','js',function(){
			include(_sys.static_url+'Videojs/lang/zh-CN.js','js',function(){
				$('.ui_hint').remove();
				tmpFun();
			});
		});
	}else{
		tmpFun();
	}
}