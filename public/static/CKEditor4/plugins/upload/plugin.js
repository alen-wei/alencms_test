(function(){
	var name='upload';
	var fun={
		exec: function(o){
			var tmpFun=function(){
				var upObj=new _alenUpload;
				upObj.uploadUrl=_sys.upload_img_url;
				upObj.fileSize='3M';
				upObj.exts=Array('jpg','jpeg','gif','png','bmp');
				upObj.onComplete=function(files){
					var html="";
					var type;
					for(var k in files){
						type=files[k].file.type.split('/')[0].toLowerCase();
						if(type=='image'){
							html+='<img width="100%" alt="'+files[k].file.name+'" src="'+files[k].back.path+files[k].back.url+'" />';
						}
					}
					o.insertHtml(html);
				};
				upObj.onError=function(err){alerts(err);};
				upObj.showFile(true);
			};
			if(typeof(_alenUpload)=='undefined'){
				alert_hint('模块加载中...','spinner',-1);
				include(_sys.static_url+'Alen/Js/alen.Upload.js','js',function(){
					alert_hint_close();
					var tmpObj=$('<div class="ui_allbg" style="display:table;"><div class="obj_tmpSelectFile" style="color:#FFF; text-align:center; display:table-cell; vertical-align:middle; height:100%;"><button type="button" class="btn btn-primary"><i class="icon-file-alt"></i> 请选择文件</button><div class="fr_pt5">由于系统的限制，您需要点击上面的按钮</div></div></div>');
					$('body').append(tmpObj);
					tmpObj.find('button').click(function(){
						tmpObj.remove();
						tmpFun();
					});
				});
			}else{
				tmpFun();
			}
		}
	};
    CKEDITOR.plugins.add(name,{
        init: function(o){
			o.addCommand(name,fun);
			o.ui.addButton(name,{
				label: "上传文件",
				command : name,
				icon: this.path + "iocn.png"//这个g.ico是你的插件图标，放在同目录下
			});
        }
    });
})();