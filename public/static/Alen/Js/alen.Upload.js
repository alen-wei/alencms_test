function _alenUpload(){
	var files;
	var closeing=false;
	var filesMD5=Array();
	var md5Size;
	var fileSize;
	
	var maxMD5=2;
	var maxUpload=2;
	var FileLength=0;
	var MD5List=Array(0,Array());
	var UPList=Array(0,Array());
	var maxWidth=1920;
	
	var boxObj;
	var boxName;
	
	var fileExtension={
		'image':Array('jpg','jpeg','gif','png','bmp'),
		'video':Array('mp4','avi','mov','3gp'),
	};
	var tmpcss="<style>#uploaddiv_file .fileList .pic .shade{display:block;position:absolute;left:0;bottom:0;z-index:1;width:100%;height:100%;background:rgba(0,0,0,0.5)}#uploaddiv_file .fileList .pic .txt{position:absolute;left:50%;top:50%;margin:-0.6em auto auto -1.7em;z-index:2;background:rgba(0,0,0,0.8);width:3.4em;line-height:1.5em;color:#fff;text-align:center;border-radius:3px;transition:.6s;opacity:1}#uploaddiv_file .fileList .pic.x .shade{display:none}#uploaddiv_file .fileList .pic.x .txt{top:-50%;opacity:0}</style>";
	/************设置***********/
	this.loadFile=true;  //是否加载本地文件
	this.loadMD5=true;	//是否计算文件MD5
	this.multiple=false;  //是否多选
	this.exts;  //允许的文件后缀
	this.uploadUrl=_sys.upload_url;  //上传网址
	this.md5Size="2M";  //计算MD5时文件分块大小
	this.fileSize="5M";  //上传文件大小
	/************事件***********/
	this.onSelect; //选择文件之后
	this.onClose; //关闭时
	this.onComplete //完成时
	this.onError //文件出错时
	this.onLoadFile; //加载本地文件后
	this.onloadMD5;  //读取文件MD5后
	this.onUpload;  //上传文件MD5后
	/************私有方法***********/
	var initialize=function(){  //初始化
		boxName='uploaddiv_file';
		boxObj=$('#'+boxName);
		if(boxObj.length<1){
			$('body').append(tmpcss+getModalHtml(boxName,'上传文件'));
			boxObj=$('#'+boxName);
		}
		//关闭事件
		boxObj.on('hidden.bs.modal', function(e){
			closeing=true;
			if(tmpthis.onClose)tmpthis.onClose();
		});
	};
	//主要函数
	var _dataUrl;
	var _lesslist;
	var imgDataURL=function(image,orientation){
		var orientation=orientation?parseInt(orientation):1;
		var canvas=document.createElement('canvas');
		var width,height,degree;
		var drawWidth=image.width;
		var drawHeight=image.height;
		if(orientation==6 || orientation==8){
			if (image.height > maxWidth){  
				drawHeight = maxWidth;  
				drawWidth = drawHeight * image.width / image.height;  
			} 
		}else{
			if(image.width > maxWidth){  
				drawWidth = maxWidth;  
				drawHeight = drawWidth * image.height / image.width;  
			}
		}
		canvas.width=width=drawWidth;
		canvas.height=height=drawHeight; 
		var context=canvas.getContext('2d');
		switch(orientation){
			//iphone横屏拍摄，此时home键在左侧
			case 3:
				degree=180;
				drawWidth=-width;
				drawHeight=-height;
			break;
			//iphone竖屏拍摄，此时home键在下方(正常拿手机的方向)
			case 6:
				canvas.width=height;
				canvas.height=width; 
				degree=90;
				drawWidth=width;
				drawHeight=-height;
			break;
			//iphone竖屏拍摄，此时home键在上方
			case 8:
				canvas.width=height;
				canvas.height=width; 
				degree=270;
				drawWidth=-width;
				drawHeight=height;
			break;
		}
		context.rotate(degree*Math.PI/180);
		context.drawImage(image,0,0,drawWidth,drawHeight);
		return canvas.toDataURL('image/jpeg', 0.8); 
	}
	var mainFun=function(backFile,baseurl,lesslist){
		if(baseurl){
			runFun(backFile,baseurl,lesslist);
		}else{
			var loadfun=function(){
				alert_hint('处理图片','spinner',-1);
				var zs=backFile.length;
				var cn=0;
				var newFile=[];
				var tmpEnd=function(){
					cn++;
					if(cn>=zs){
						$('.ui_hint').remove();
						runFun(newFile,baseurl,lesslist);
					}
				}
				var cnFun=function(ti){
					setTimeout(function(){
						var tmpFile=backFile[ti];
						//tmpFile.name=tmpFile.name?tmpFile.name:'tmp.jpg';
						var tmpExt=getFileExt(tmpFile.name);
						if(in_array(tmpExt,fileExtension.image)){
							var tmpName=tmpFile.name.split('.');
							var tmp='';
							for(var n=0;n<tmpName.length-1;n++){tmp+=tmpName[n]+'.';}
							tmpName=tmp;
							EXIF.getData(tmpFile,function(){
								var exifData=EXIF.getAllTags(this);
								if(_sys.os!='ios' && _sys.os!='mac'){
									exifData.Orientation=1;
								}
								if((!exifData.Orientation || exifData.Orientation==1) && (!exifData.PixelXDimension || exifData.PixelXDimension<=maxWidth)){
									newFile[ti]=backFile[ti];
									tmpEnd();
								}else{
									getLocalPic(this,0,function(data){
										loadImg(data,function(img){
											newFile[ti]=dataURLtoBlob(imgDataURL(img,exifData.Orientation));
											newFile[ti].name=tmpName+'jpg';
											tmpEnd();
										});
									});
								}
							});
						}else{
							newFile[ti]=backFile[ti];
							tmpEnd();
						}
					},0);
				}
				for(var i=0;i<zs;i++){
					cnFun(i);
				}
			}
			if(typeof(EXIF)=='undefined'){
				include(_sys.static_url+'Alen/Js/exif.js','js',loadfun);
			}else{
				loadfun();
			}
		}
	}
	var runFun=function(backFile,baseurl,lesslist){
		if(baseurl){
			_dataUrl=[];
			var tmp=[];
			for(i in backFile){
				_dataUrl[i]=backFile[i];
				var str=backFile[i].split(';')[0].split('/')[1];
				backFile[i]=dataURLtoBlob(backFile[i]);
				backFile[i].name='用户文件.'+str;
			}
			getLocalPic=function(fi,i,tfun){  //重写获取本地图片
				if(tfun)tfun(_dataUrl[i],fi,i);
			}
		}
		if(lesslist){
			_lesslist=[];
			for(i in lesslist){
				_lesslist[i]=lesslist[i];
			}
			getLocalMD5=function(fi,i,tfun){  //重写获取文件MD5
				var txtObj=boxObj.find('.col_'+i+' .small');
				txtObj.html('效验文件...<span>0K/'+getFileSize(fi.size)+'</span>');
				txtObj.text(txtObj.attr('data-txt'));
				setProgress(i,100,1);
				boxObj.find('.col_'+i+' .pic').addClass('x');
				if(tfun)tfun(_lesslist[i],fi,i);
			}
		}
		//过滤文件
		var  err='';
		if(backFile.length<1){
			err+='<br />您还没选择文件！';
			return false;
		}else{
			files=Array();
			FileLength=0;
			for(var n=0;n<backFile.length;n++){
				if(tmpthis.exts){
					if(!in_array(getFileExt(backFile[n].name),tmpthis.exts)){
						err+='<br />文件'+backFile[n].name+'后缀名不符合';
						continue;
					}
				}
				if(fileSize){
					if(backFile[n].size>fileSize){
						err+='<br />文件'+backFile[n].name+'大小不符合';
						continue;
					}
				}
				files[FileLength]=backFile[n];
				FileLength++;
			}
		}
		if(err){
			err='额，搞事情！？'+err;
			if(tmpthis.onError)tmpthis.onError(err);
			return false;
		}
		setModaBody(boxName,'<i class=" icon-spinner icon-spin"></i> 文件加载中...请稍候...');
		boxObj.modal({'backdrop':'static'});
		
		var loads=0;
		var fun_load=function(){
			if(closeing)return false;
			if(loads>=files.length)return false;
			var i=loads;
			var extension=getFileExt(files[i].name);
			var less=extension.toUpperCase()+' '+getFileSize(files[i].size);
			if(tmpthis.loadFile && in_array(extension,fileExtension.image)){   //加载图片
				getLocalPic(files[i],i,function(imgData,fi,i){
					addCol(i,imgData,fi.name,less);
					if(tmpthis.onLoadFile && !closeing)tmpthis.onLoadFile(imgData,fi,i,FileLength);
					MD5List[1].push(i);
					fun_md5();
					loads++;
					fun_load();
				});
			}else{   //图标
				addCol(i,'icon-file:'+extension,files[i].name,less);
				if(tmpthis.onLoadFile)tmpthis.onLoadFile(null,files[i],i,FileLength);
				MD5List[1].push(i);
				fun_md5();
				loads++;
				fun_load();
			}
		}
		var md5cs=0;
		var fun_md5=function(){
			if(closeing)return false;
			if(!tmpthis.loadMD5){
				i=MD5List[1].shift();
				boxObj.find('.col_'+i+' .pic').addClass('x');
				UPList[1].push(i);
				fun_upload();
				return false;
			}
			if(MD5List[0]>=maxMD5)return false;
			if(MD5List[1].length<1)return false;
			i=MD5List[1].shift();
			MD5List[0]++;
			getLocalMD5(files[i],i,function(md5,fl,i){
				md5cs++;
				filesMD5[i]=md5;
				MD5List[0]--;
				fun_md5();
				if(tmpthis.onloadMD5 && !closeing)tmpthis.onloadMD5(md5,files[i],md5cs,FileLength);
				UPList[1].push(i);
				fun_upload();
			});
			fun_md5();
		}
		var uploadcs=0;
		var backdata=Array();
		var fun_upload=function(){
			if(closeing)return false;
			if(UPList[0]>=maxUpload)return false;
			if(UPList[1].length<1)return false;
			i=UPList[1].shift();
			UPList[0]++;
			uploadFile(files[i],i,filesMD5[i],function(data,fl){
				uploadcs++;
				UPList[0]--;
				backdata[backdata.length]={'file':fl,'back':data};
				if(tmpthis.onUpload && !closeing)tmpthis.onUpload(data,fl,uploadcs,FileLength);
				if(uploadcs>=FileLength){
					if(tmpthis.onComplete && !closeing)tmpthis.onComplete(backdata);
					tmpthis.close();
					return false;
				}
				fun_upload();
			});
		}
		setTimeout(fun_load,0);
		if(tmpthis.onSelect)tmpthis.onSelect(files);
	}
	var setProgress=function(i,value,type,txt){  //设置进度条
		type=type?type:0;
		if(type){
			$('.col_'+i+' .pic .shade').css('height',(100-value)+'%');
			$('.col_'+i+' .pic .txt').text(value+'%');
		}else{
			var o=boxObj.find('.col_'+i+' .progress .progress-bar');
			o.css('width',value+'%');
			txt=txt?txt:value+'%';
			o.text(txt);
		}
	}
	var addCol=function(ti,imgdata,name,less){  //新建行
		var box=boxObj.find('.fileList');
		if(box.length<1){
			setModaBody(boxName,'<ul class="ui_PicList fileList"></ul>');
			box=boxObj.find('.fileList');
		}
		var html='<li class="col_'+ti+'"><div class="pic"><i class="media-middle">';
		if(imgdata.substr(0,5)=='icon-'){
			imgdata=imgdata.split(':');
			html+='<em class="text-muted '+imgdata[0]+'"><b>'+imgdata[1].toUpperCase()+'</b></em>';
		}else{
			html+='<img class="center-block" src="'+imgdata+'" />';
		}
		html+='</i><div class="shade"></div><div class="txt">列队中</div></div>';
		html+='<div class="info"><div class="ui_txtof">'+name+'</div><div data-txt="'+less+'" class="small text-muted ui_txtof">'+less+'</div>';
		html+='<div class="progress fr_mt5" style="margin-bottom:0;"><div class="progress-bar progress-bar-striped active" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="min-width:2em;">0%</div></div>';
		html+='</div></li>';
		box.append(html);
	}
	
	var uploadFile=function(fl,i,md5,tfun){			
		var txtObj=boxObj.find('.col_'+i+' .small');
		txtObj.html('上传文件...<span>0K/'+getFileSize(fl.size)+'</span>');
		var tmpfun=function(){
			var myFormData = new FormData();
			myFormData.append('files',fl);
			var xhr = new XMLHttpRequest();
			xhr.upload.addEventListener("progress", function(evt){  //上传中
				if(closeing)xhr.Abort();
				if (evt.lengthComputable){
					var bf=parseInt(evt.loaded/evt.total*100);
					bf=bf>1?bf-1:bf;
					setProgress(i,bf);
					txtObj.html('上传文件...<span>'+getFileSize(evt.loaded)+'/'+getFileSize(evt.total)+'</span>');
				}else{
					console.log('进度不可用');
				}
			},false);
			xhr.addEventListener("load", function(evt){  //上传成功
				var data = $.parseJSON(evt.target.responseText);
				if(parseInt(data.status)){
					for(var f in data.data){break;}
					if('err' in data.data[f]){
						setProgress(i,100,0,'上传失败');
						txtObj.text(txtObj.attr('data-txt')+' '+data.data[f].err);
						boxObj.find('.col_'+i+' .progress .progress-bar').addClass('progress-bar-danger');
					}else{
						setProgress(i,100,0,'上传成功');
						txtObj.text(txtObj.attr('data-txt'));
					}
					var tmpdata=data.data[f];
					if(tfun)tfun(tmpdata,fl);
				}else{
					setProgress(i,100,0,'上传失败');
					txtObj.text(txtObj.attr('data-txt')+' '+data.data);
					boxObj.find('.col_'+i+' .progress .progress-bar').addClass('progress-bar-danger');
					if(tfun)tfun(data,fl);
				}
				
			}, false);
			xhr.withCredentials = true;
			xhr.open("POST",tmpthis.uploadUrl,true);
			xhr.send(myFormData);
		}
		if(md5){
			var tmpurl=_lesslist?_sys.upload_img_url:tmpthis.uploadUrl;
			var tmpdata=_lesslist?{'verify':1,'mid':md5}:{'md5':md5,'ext':tmpthis.exts?tmpthis.exts.join(','):'*'};
			//alert(JSON.stringify(tmpdata));
			$.ajax({
				'type':'POST',
				'xhrFields':{'withCredentials':true,},
				'crossDomain': true,
				'url':tmpurl,
				'data':tmpdata,
				'success':function(backdata){
					if(parseInt(backdata.status)){
						//for(var f in backdata.data){break;}
						setProgress(i,100,0,'秒传成功');
						txtObj.text(txtObj.attr('data-txt'));
						//backdata.data=backdata.data;
						if(tfun)tfun(backdata.data,fl);
					}else{
						if(backdata.info=='10030003'){
							tmpfun();
						}else{
							setProgress(i,100,0,'上传失败');
							txtObj.text(txtObj.attr('data-txt')+' '+backdata.data);
							if(tfun)tfun({'err':backdata.data},fl);
						}
					}
				},
				'error':function(){
					//alert('err');
				},
			});
		}else{
			tmpfun();
		}
	}
	var getLocalMD5=function(fi,i,tfun){  //获取文件MD5
		var file = fi;
		var chunkSize = md5Size;
		var fileReader = new FileReader();
		var blobSlice = File.prototype.mozSlice || File.prototype.webkitSlice || File.prototype.slice;
		var chunks = Math.ceil(file.size / chunkSize);
		var currentChunk = 0;
		var spark = new SparkMD5();
		var txtObj=boxObj.find('.col_'+i+' .small');
		txtObj.html('效验文件...<span>0K/'+getFileSize(file.size)+'</span>');
		fileReader.onprogress=function(){
			if(closeing)fileReader.abort(fi);
		}
		fileReader.onload = function(e) {
			spark.appendBinary(e.target.result);
			currentChunk++;
			if (currentChunk < chunks) {
				setProgress(i,parseInt(currentChunk/chunks*100),1);
				txtObj.find('span').text(getFileSize(currentChunk*chunkSize)+'/'+getFileSize(file.size));
				loadNext();
			}else{
				txtObj.text(txtObj.attr('data-txt'));
				setProgress(i,100,1);
				boxObj.find('.col_'+i+' .pic').addClass('x');
				if(tfun)tfun(spark.end(),fi,i);
			}
		};
		var loadNext=function(){
			var start = currentChunk * chunkSize;
			var end = start + chunkSize >= file.size ? file.size : start + chunkSize;
			fileReader.readAsBinaryString(blobSlice.call(file, start, end));
		};
		loadNext();
	}
	
	var getLocalPic=function(fi,i,tfun){  //获取本地图片
		var reader = new FileReader();
		reader.onprogress=function(){
			if(closeing)reader.abort(fi);
		}
		reader.onload = function(event){
			if(tfun)tfun(event.target.result,fi,i);
		}
       reader.readAsDataURL(fi);
    }
	var dataURLtoBlob=function(dataurl) {
		var arr = dataurl.split(','), mime = arr[0].match(/:(.*?);/)[1],
			bstr = atob(arr[1]), n = bstr.length, u8arr = new Uint8Array(n);
		while(n--){
			u8arr[n] = bstr.charCodeAt(n);
		}
		return new Blob([u8arr], {type:mime});
	}
	/************方法***********/
	this.showFile=function(multiple){  //显示选择文件框
		closeing=false;
		file=false;
		filesMD5=Array();
		md5Size=getFileSize(tmpthis.md5Size);
		fileSize=getFileSize(tmpthis.fileSize);
		var accept='*.*';
		if(tmpthis.exts){
			var tmp=Array();
			for(i in tmpthis.exts){
				tmp[i]='.'+tmpthis.exts[i].toLowerCase();
			}
			accept=tmp.join(',');
		}
		//selectFile({'multiple':multiple},function(fileList,baseurl,lesslist){
		selectFile({'exts':tmpthis.exts,'multiple':multiple},function(fileList,baseurl,lesslist){
			if (!fileList.length)return false;
			mainFun(fileList,baseurl,lesslist);
		});
	}
	this.upFile=function(upFiles,baseurl,lesslist){  //通过flie对象直接上传
		closeing=false;
		file=false;
		filesMD5=Array();
		md5Size=getFileSize(tmpthis.md5Size);
		fileSize=getFileSize(tmpthis.fileSize);
		mainFun(upFiles,baseurl,lesslist);
	}
	this.close=function(){ //关闭
		boxObj.modal('hide');
	}
	/************/
	var tmpthis=this;
	initialize();
}

//spark-md5.js
(function(factory){if(typeof exports==="object"){module.exports=factory()}else{if(typeof define==="function"&&define.amd){define(factory)}else{var glob;try{glob=window}catch(e){glob=self}glob.SparkMD5=factory()}}}(function(undefined){var add32=function(a,b){return(a+b)&4294967295},hex_chr=["0","1","2","3","4","5","6","7","8","9","a","b","c","d","e","f"];function cmn(q,a,b,x,s,t){a=add32(add32(a,q),add32(x,t));return add32((a<<s)|(a>>>(32-s)),b)}function ff(a,b,c,d,x,s,t){return cmn((b&c)|((~b)&d),a,b,x,s,t)}function gg(a,b,c,d,x,s,t){return cmn((b&d)|(c&(~d)),a,b,x,s,t)}function hh(a,b,c,d,x,s,t){return cmn(b^c^d,a,b,x,s,t)}function ii(a,b,c,d,x,s,t){return cmn(c^(b|(~d)),a,b,x,s,t)}function md5cycle(x,k){var a=x[0],b=x[1],c=x[2],d=x[3];a=ff(a,b,c,d,k[0],7,-680876936);d=ff(d,a,b,c,k[1],12,-389564586);c=ff(c,d,a,b,k[2],17,606105819);b=ff(b,c,d,a,k[3],22,-1044525330);a=ff(a,b,c,d,k[4],7,-176418897);d=ff(d,a,b,c,k[5],12,1200080426);c=ff(c,d,a,b,k[6],17,-1473231341);b=ff(b,c,d,a,k[7],22,-45705983);a=ff(a,b,c,d,k[8],7,1770035416);d=ff(d,a,b,c,k[9],12,-1958414417);c=ff(c,d,a,b,k[10],17,-42063);b=ff(b,c,d,a,k[11],22,-1990404162);a=ff(a,b,c,d,k[12],7,1804603682);d=ff(d,a,b,c,k[13],12,-40341101);c=ff(c,d,a,b,k[14],17,-1502002290);b=ff(b,c,d,a,k[15],22,1236535329);a=gg(a,b,c,d,k[1],5,-165796510);d=gg(d,a,b,c,k[6],9,-1069501632);c=gg(c,d,a,b,k[11],14,643717713);b=gg(b,c,d,a,k[0],20,-373897302);a=gg(a,b,c,d,k[5],5,-701558691);d=gg(d,a,b,c,k[10],9,38016083);c=gg(c,d,a,b,k[15],14,-660478335);b=gg(b,c,d,a,k[4],20,-405537848);a=gg(a,b,c,d,k[9],5,568446438);d=gg(d,a,b,c,k[14],9,-1019803690);c=gg(c,d,a,b,k[3],14,-187363961);b=gg(b,c,d,a,k[8],20,1163531501);a=gg(a,b,c,d,k[13],5,-1444681467);d=gg(d,a,b,c,k[2],9,-51403784);c=gg(c,d,a,b,k[7],14,1735328473);b=gg(b,c,d,a,k[12],20,-1926607734);a=hh(a,b,c,d,k[5],4,-378558);d=hh(d,a,b,c,k[8],11,-2022574463);c=hh(c,d,a,b,k[11],16,1839030562);b=hh(b,c,d,a,k[14],23,-35309556);a=hh(a,b,c,d,k[1],4,-1530992060);d=hh(d,a,b,c,k[4],11,1272893353);c=hh(c,d,a,b,k[7],16,-155497632);b=hh(b,c,d,a,k[10],23,-1094730640);a=hh(a,b,c,d,k[13],4,681279174);d=hh(d,a,b,c,k[0],11,-358537222);c=hh(c,d,a,b,k[3],16,-722521979);b=hh(b,c,d,a,k[6],23,76029189);a=hh(a,b,c,d,k[9],4,-640364487);d=hh(d,a,b,c,k[12],11,-421815835);c=hh(c,d,a,b,k[15],16,530742520);b=hh(b,c,d,a,k[2],23,-995338651);a=ii(a,b,c,d,k[0],6,-198630844);d=ii(d,a,b,c,k[7],10,1126891415);c=ii(c,d,a,b,k[14],15,-1416354905);b=ii(b,c,d,a,k[5],21,-57434055);a=ii(a,b,c,d,k[12],6,1700485571);d=ii(d,a,b,c,k[3],10,-1894986606);c=ii(c,d,a,b,k[10],15,-1051523);b=ii(b,c,d,a,k[1],21,-2054922799);a=ii(a,b,c,d,k[8],6,1873313359);d=ii(d,a,b,c,k[15],10,-30611744);c=ii(c,d,a,b,k[6],15,-1560198380);b=ii(b,c,d,a,k[13],21,1309151649);a=ii(a,b,c,d,k[4],6,-145523070);d=ii(d,a,b,c,k[11],10,-1120210379);c=ii(c,d,a,b,k[2],15,718787259);b=ii(b,c,d,a,k[9],21,-343485551);x[0]=add32(a,x[0]);x[1]=add32(b,x[1]);x[2]=add32(c,x[2]);x[3]=add32(d,x[3])}function md5blk(s){var md5blks=[],i;for(i=0;i<64;i+=4){md5blks[i>>2]=s.charCodeAt(i)+(s.charCodeAt(i+1)<<8)+(s.charCodeAt(i+2)<<16)+(s.charCodeAt(i+3)<<24)}return md5blks}function md5blk_array(a){var md5blks=[],i;for(i=0;i<64;i+=4){md5blks[i>>2]=a[i]+(a[i+1]<<8)+(a[i+2]<<16)+(a[i+3]<<24)}return md5blks}function md51(s){var n=s.length,state=[1732584193,-271733879,-1732584194,271733878],i,length,tail,tmp,lo,hi;for(i=64;i<=n;i+=64){md5cycle(state,md5blk(s.substring(i-64,i)))}s=s.substring(i-64);length=s.length;tail=[0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0];for(i=0;i<length;i+=1){tail[i>>2]|=s.charCodeAt(i)<<((i%4)<<3)}tail[i>>2]|=128<<((i%4)<<3);if(i>55){md5cycle(state,tail);for(i=0;i<16;i+=1){tail[i]=0}}tmp=n*8;tmp=tmp.toString(16).match(/(.*?)(.{0,8})$/);lo=parseInt(tmp[2],16);hi=parseInt(tmp[1],16)||0;tail[14]=lo;tail[15]=hi;md5cycle(state,tail);return state}function md51_array(a){var n=a.length,state=[1732584193,-271733879,-1732584194,271733878],i,length,tail,tmp,lo,hi;for(i=64;i<=n;i+=64){md5cycle(state,md5blk_array(a.subarray(i-64,i)))}a=(i-64)<n?a.subarray(i-64):new Uint8Array(0);length=a.length;tail=[0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0];for(i=0;i<length;i+=1){tail[i>>2]|=a[i]<<((i%4)<<3)}tail[i>>2]|=128<<((i%4)<<3);if(i>55){md5cycle(state,tail);for(i=0;i<16;i+=1){tail[i]=0}}tmp=n*8;tmp=tmp.toString(16).match(/(.*?)(.{0,8})$/);lo=parseInt(tmp[2],16);hi=parseInt(tmp[1],16)||0;tail[14]=lo;tail[15]=hi;md5cycle(state,tail);return state}function rhex(n){var s="",j;for(j=0;j<4;j+=1){s+=hex_chr[(n>>(j*8+4))&15]+hex_chr[(n>>(j*8))&15]}return s}function hex(x){var i;for(i=0;i<x.length;i+=1){x[i]=rhex(x[i])}return x.join("")}if(hex(md51("hello"))!=="5d41402abc4b2a76b9719d911017c592"){add32=function(x,y){var lsw=(x&65535)+(y&65535),msw=(x>>16)+(y>>16)+(lsw>>16);return(msw<<16)|(lsw&65535)}}if(typeof ArrayBuffer!=="undefined"&&!ArrayBuffer.prototype.slice){(function(){function clamp(val,length){val=(val|0)||0;if(val<0){return Math.max(val+length,0)}return Math.min(val,length)}ArrayBuffer.prototype.slice=function(from,to){var length=this.byteLength,begin=clamp(from,length),end=length,num,target,targetArray,sourceArray;
if(to!==undefined){end=clamp(to,length)}if(begin>end){return new ArrayBuffer(0)}num=end-begin;target=new ArrayBuffer(num);targetArray=new Uint8Array(target);sourceArray=new Uint8Array(this,begin,num);targetArray.set(sourceArray);return target}})()}function toUtf8(str){if(/[\u0080-\uFFFF]/.test(str)){str=unescape(encodeURIComponent(str))}return str}function utf8Str2ArrayBuffer(str,returnUInt8Array){var length=str.length,buff=new ArrayBuffer(length),arr=new Uint8Array(buff),i;for(i=0;i<length;i+=1){arr[i]=str.charCodeAt(i)}return returnUInt8Array?arr:buff}function arrayBuffer2Utf8Str(buff){return String.fromCharCode.apply(null,new Uint8Array(buff))}function concatenateArrayBuffers(first,second,returnUInt8Array){var result=new Uint8Array(first.byteLength+second.byteLength);result.set(new Uint8Array(first));result.set(new Uint8Array(second),first.byteLength);return returnUInt8Array?result:result.buffer}function hexToBinaryString(hex){var bytes=[],length=hex.length,x;for(x=0;x<length-1;x+=2){bytes.push(parseInt(hex.substr(x,2),16))}return String.fromCharCode.apply(String,bytes)}function SparkMD5(){this.reset()}SparkMD5.prototype.append=function(str){this.appendBinary(toUtf8(str));return this};SparkMD5.prototype.appendBinary=function(contents){this._buff+=contents;this._length+=contents.length;var length=this._buff.length,i;for(i=64;i<=length;i+=64){md5cycle(this._hash,md5blk(this._buff.substring(i-64,i)))}this._buff=this._buff.substring(i-64);return this};SparkMD5.prototype.end=function(raw){var buff=this._buff,length=buff.length,i,tail=[0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0],ret;for(i=0;i<length;i+=1){tail[i>>2]|=buff.charCodeAt(i)<<((i%4)<<3)}this._finish(tail,length);ret=hex(this._hash);if(raw){ret=hexToBinaryString(ret)}this.reset();return ret};SparkMD5.prototype.reset=function(){this._buff="";this._length=0;this._hash=[1732584193,-271733879,-1732584194,271733878];return this};SparkMD5.prototype.getState=function(){return{buff:this._buff,length:this._length,hash:this._hash}};SparkMD5.prototype.setState=function(state){this._buff=state.buff;this._length=state.length;this._hash=state.hash;return this};SparkMD5.prototype.destroy=function(){delete this._hash;delete this._buff;delete this._length};SparkMD5.prototype._finish=function(tail,length){var i=length,tmp,lo,hi;tail[i>>2]|=128<<((i%4)<<3);if(i>55){md5cycle(this._hash,tail);for(i=0;i<16;i+=1){tail[i]=0}}tmp=this._length*8;tmp=tmp.toString(16).match(/(.*?)(.{0,8})$/);lo=parseInt(tmp[2],16);hi=parseInt(tmp[1],16)||0;tail[14]=lo;tail[15]=hi;md5cycle(this._hash,tail)};SparkMD5.hash=function(str,raw){return SparkMD5.hashBinary(toUtf8(str),raw)};SparkMD5.hashBinary=function(content,raw){var hash=md51(content),ret=hex(hash);return raw?hexToBinaryString(ret):ret};SparkMD5.ArrayBuffer=function(){this.reset()};SparkMD5.ArrayBuffer.prototype.append=function(arr){var buff=concatenateArrayBuffers(this._buff.buffer,arr,true),length=buff.length,i;this._length+=arr.byteLength;for(i=64;i<=length;i+=64){md5cycle(this._hash,md5blk_array(buff.subarray(i-64,i)))}this._buff=(i-64)<length?new Uint8Array(buff.buffer.slice(i-64)):new Uint8Array(0);return this};SparkMD5.ArrayBuffer.prototype.end=function(raw){var buff=this._buff,length=buff.length,tail=[0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0],i,ret;for(i=0;i<length;i+=1){tail[i>>2]|=buff[i]<<((i%4)<<3)}this._finish(tail,length);ret=hex(this._hash);if(raw){ret=hexToBinaryString(ret)}this.reset();return ret};SparkMD5.ArrayBuffer.prototype.reset=function(){this._buff=new Uint8Array(0);this._length=0;this._hash=[1732584193,-271733879,-1732584194,271733878];return this};SparkMD5.ArrayBuffer.prototype.getState=function(){var state=SparkMD5.prototype.getState.call(this);state.buff=arrayBuffer2Utf8Str(state.buff);return state};SparkMD5.ArrayBuffer.prototype.setState=function(state){state.buff=utf8Str2ArrayBuffer(state.buff,true);return SparkMD5.prototype.setState.call(this,state)};SparkMD5.ArrayBuffer.prototype.destroy=SparkMD5.prototype.destroy;SparkMD5.ArrayBuffer.prototype._finish=SparkMD5.prototype._finish;SparkMD5.ArrayBuffer.hash=function(arr,raw){var hash=md51_array(new Uint8Array(arr)),ret=hex(hash);return raw?hexToBinaryString(ret):ret};return SparkMD5}));