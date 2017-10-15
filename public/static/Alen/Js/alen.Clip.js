function _alenClip(){
	var closeing=false;
	var runf=true;
	var exts=Array('jpg','jpeg','gif','png','bmp');
	var fileName='';
	var autoks=false;
	this.clipFile=function(imgurl,width,height,backfun){
		autoks={
			'imgurl':imgurl,
			'width':width,
			'height':height,
			'backfun':backfun,
		}
		alert_hint('加载模块','spinner',-1);
		//alerts('加载中...请稍候再试');
	}
	var run=function(){
		tmpthis.clipFile=function(imgurl,width,height,backfun){
			closeing=false;
			runf=true;
			if(imgurl){
				tmpClip(imgurl,width,height,backfun);
			}else{
				selectFile({'exts':exts},function(fileList,baseurl,less){
					if (!fileList.length) return false;
					var files = fileList[0];
					if(baseurl){
						tmpClip(files,width,height,backfun);
					}else{
						fileName=files.name;
						var reader = new FileReader();
						reader.onprogress=function(){
							if(closeing){reader.abort(files);runf=false;}
						}
						reader.onload = function(event){
							tmpClip(event.target.result,width,height,backfun);
						}
					   reader.readAsDataURL(files);
					}
				});
			}
		}
		if(autoks){
			$('.ui_hint').remove();
			if(_sys.view=='weixin' && _sys.os!='ios'){
				tmpthis.clipFile(autoks.imgurl,autoks.width,autoks.height,autoks.backfun);
				autoks=false;
			}else{
				var tmpObj=$('<div class="ui_allbg" style="display:table;"><div class="obj_tmpSelectFile" style="color:#FFF; text-align:center; display:table-cell; vertical-align:middle; height:100%;"><button type="button" class="btn btn-primary"><i class="icon-file-alt"></i> 请选择文件</button><div class="fr_pt5">由于系统的限制，您需要点击上面的按钮</div></div></div>');
				$('body').append(tmpObj);
				tmpObj.find('button').click(function(){
					tmpObj.remove();
					tmpthis.clipFile(autoks.imgurl,autoks.width,autoks.height,autoks.backfun);
					autoks=false;
				});
			}
		}
	}
	
	var uploadDataurl=	function(dataurl,tfun,less){
		var upObj=new _alenUpload;
		upObj.uploadUrl=_sys.upload_img_url;
		upObj.fileSize='3M';
		upObj.exts=exts;
		upObj.onComplete=function(files){
			var data=files[0];
			//echoLog(data);
			//data.data.name=fileName;
			if(tfun)tfun(data);
		}
		upObj.onError=function(err){
			alerts(err);
		}
		var tmp=new Array();
		tmp[0]=dataurl;
		upObj.upFile(tmp,true,less);
	}
	
	
	var tmpClip=function(pic,tw,th,tfun){
		if(!runf)return false;
		runf=false;
		var html='<div class="ui_allbg" id="obj_clipArea"><style>.clipAreabox{height:100%;width:100%;color:#FFF;position:relative}.clipArea_info{background:rgba(0,0,0,.5);padding:10px;border-radius:5px;position:absolute;bottom:10px;right:10px}.clipArea_pic{position:absolute;bottom:104px;right:10px;border:2px solid #fff;box-shadow:0 0 10px #000;visibility:hidden;opacity:0;transition:all .2s;cursor:pointer}.clipArea_pic.x{visibility:visible;opacity:1}.clipArea_pic:before{content:"点击刷新";width:6em;line-height:2em;height:2em;background:rgba(0,0,0,.5);color:#fff;position:absolute;left:50%;top:50%;text-align:center;margin:-1em 0 0 -3em;border-radius:1em;transition:all .2s;visibility:hidden;opacity:0}.clipArea_pic:hover:before{visibility:visible;opacity:1}#clipArea{width:100%;height:100%}#clipArea_Btn{display:none}#clipArea_view{box-sizing:content-box;width:100%}.clipArea_info .showview:before{content:"打开"}.clipArea_info .showview.x:before{content:"关闭"}@media screen and (max-width:767px){.clipArea_info{width:100%;bottom:0;right:0;border-radius:0}.clipArea_pic{right:0;bottom:84px;max-width:100%}.clipArea_pic:before{content:"";display:none}}</style><div class="clipAreabox"><div id="clipArea"></div><div class="clipArea_pic"><div id="clipArea_view" class="pull-right"></div></div><div class="clipArea_info"><p>截取大小：<b></b></p><button class="btn btn-default pull-left endbut fr_mr20">取消</button><button class="btn btn-primary pull-left showview">浏览</button><button class="btn btn-success pull-left upbut fr_ml10">确认截取</button><div class="clearfix"></div><button id="clipArea_Btn" class="btn btn-default"></button></div></div></div>';
		$('body').append(html);
		var bw,bh;
		if(tw>th){
			bw=200;
			bh=bw/tw*th;
		}else{
			bh=200;
			bw=bh/th*tw;
		}
		var hsw=th/tw;
		var imgData,getImgFun;
		var ext='jpg';
		
		$('.clipArea_pic').width(tw);
		$('#clipArea_view').css('padding-top',(hsw*100)+'%');
		$('.clipArea_info p b').text(tw+'px * '+th+'px');
		var clipOP={
			size: [bw, bh], // 截取框的宽和高组成的数组。默认值为[260,260]
			outputSize: [tw, th], // 输出图像的宽和高组成的数组。默认值为[0,0]，表示输出图像原始大小
			outputType: ext, // 指定输出图片的类型，可选 "jpg" 和 "png" 两种种类型，默认为 "jpg"
			view: "#clipArea_view", // 显示截取后图像的容器的选择器或者DOM对象
			ok: "#clipArea_Btn", // 确认截图按钮的选择器或者DOM对象
			loadStart: function(file) {}, // 开始加载的回调函数。this指向 fileReader 对象，并将正在加载的 file 对象作为参数传入
			loadComplete: function(src) {}, // 加载完成的回调函数。this指向图片对象，并将图片地址作为参数传入
			loadError: function(event) {}, // 加载失败的回调函数。this指向 fileReader 对象，并将错误事件的 event 对象作为参数传入
			clipFinish: function(dataURL){
				//imgData=dataURL;
				if(getImgFun){getImgFun(dataURL);}
			}, // 裁剪完成的回调函数。this指向图片对象，会将裁剪出的图像数据DataURL作为参数传入
		};
		if(pic){
			clipOP.source=pic;  //需要裁剪图片的url地址。该参数表示当前立即开始裁剪的图片，不需要使用file控件获取。注意，该参数不支持跨域图片。
		}else{
			clipOP.file='#clipArea_flie';  //上传图片的<input type="file">控件的选择器或者DOM对象
		}
		//alert(JSON.stringify(clipOP));
		var clipArea = new bjj.PhotoClip("#clipArea",clipOP);
		$('.clipArea_pic').click(function(){
			getImgFun=false;
			$('#clipArea_Btn').click();
		});
		$('.clipArea_info .showview').click(function(){
			if(!$(this).hasClass('x')){
				getImgFun=false;
				$('#clipArea_Btn').click();
			}
			$(this).toggleClass('x');
			$('.clipArea_pic').toggleClass('x');
		});
		
		$('.clipArea_info .endbut').click(function(){
			clipArea.destroy();
			$('#obj_clipArea').remove();
			closeing=true;
		});
		$('.clipArea_info .upbut').click(function(){
			getImgFun=function(dataurl){
				uploadDataurl(dataurl,function(data){
					if(tfun)tfun(data);
					$('.clipArea_info .endbut').click();
				});
			}
			$('#clipArea_Btn').click();
			return false;
		});
	}
	var loadzs=5;
	var loadwc=0;
	var loadfun=function(){
		loadwc++;
		if(loadwc>=loadzs){
			run();
		}
	}
	if(typeof(Hammer)=='undefined'){
		include(_sys.static_url+'Hammer/hammer.js','js',loadfun);
	}else{
		loadfun();
	}
	if(typeof(IScroll)=='undefined'){
		include(_sys.static_url+'Alen/Js/iscroll-zoom.js','js',loadfun);
	}else{
		loadfun();
	}
	if(typeof(lrz)=='undefined'){
		include(_sys.static_url+'Alen/Js/lrz.all.bundle.js','js',loadfun);
	}else{
		loadfun();
	}
	if(typeof(bjj)=='undefined'){
		include(_sys.static_url+'Jq_plugins/jquery.photoClip.js','js',loadfun);
	}else{
		loadfun();
	}
	if(typeof(_alenUpload)=='undefined'){
		include(_sys.static_url+'Alen/Js/alen.Upload.js','js',loadfun);
	}else{
		loadfun();
	}
	var tmpthis=this;
	return this;
}
