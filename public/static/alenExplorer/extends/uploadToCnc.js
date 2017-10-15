//_alenExplorer_extends
(function(){
	var name='uploadToCnc';
	if(typeof(_alenExplorer_extends)=="undefined"){
		echoLog('请先加载alen.Explorer.js');
		return;
	}
	var _css='<style>#uploadToCnc{position:relative;width:100%;height:100%;padding:10px;overflow:hidden}#uploadToCnc .loadbox{position:absolute;left:0;top:0;width:100%;height:100%;background:rgba(255,255,255,.5);text-align:center;opacity:0;visibility:hidden;transition:all .5s}#uploadToCnc .loadbox span{background:rgba(0,0,0,.6);display:inline-block;border-radius:10px;overflow:hidden;padding:10px;box-shadow:0 0 10px #666;position:relative;margin-top:-100px;transition:all .5s}#uploadToCnc .loadbox.x{opacity:1;visibility:visible}#uploadToCnc .loadbox.x span{margin-top:20px}#uploadToCnc .loadbox span i{font-size:3em;line-height:1em;float:left;color:#FFF}#uploadToCnc .loadbox span a{font-size:1.5em;float:left;line-height:2em;margin-left:5px;color:#FFF}#uploadToCnc .bg{position:relative;width:100%;height:100%;padding-left:320px}#uploadToCnc ul{position:absolute;top:0;left:160px;width:150px;height:100%;border:1px solid #ccc;margin:0;padding:0;border-radius:5px;list-style:none;overflow-x:hidden;overflow-y:auto}#uploadToCnc ul.x{left:0}#uploadToCnc ul li{line-height:1.6em;display:block;padding:0 10px;cursor:pointer}#uploadToCnc ul li:hover{background:#e0e0e0}#uploadToCnc ul li.x{background:#08c;color:#FFF}#uploadToCnc .selected{padding-top:3.5em;padding-bottom:3em;position:relative;height:100%}#uploadToCnc .selected .files{overflow:hidden;position:absolute;top:0;left:0;width:100%;padding:0 8.1em 0 3em}#uploadToCnc .selected .files i{position:absolute;left:0;top:0;font-size:3em;line-height:1em}#uploadToCnc .selected .files a{position:absolute;top:.3em;right:0}#uploadToCnc .selected .files a.but_browser{right:4.1em}#uploadToCnc .selected .files b,#uploadToCnc .selected .files span{display:block;line-height:1.5em;width:100%}#uploadToCnc .selected .box{overflow-x:hidden;overflow-y:auto;width:100%;height:100%;border-bottom:1px solid #ccc;border-top:1px solid #ccc;padding-top:5px}#uploadToCnc .selected .box .label{float:left;margin:0 5px 5px 0;cursor:pointer}#uploadToCnc .selected .but{text-align:right;position:absolute;left:0;bottom:0;width:100%}#uploadToCnc .code_browser{position:absolute;left:0;top:100%;width:100%;height:100%;z-index:5;background:#FFF;padding:5px 5px 3.5em 5px;transition:all .5s;border-top:4px solid #666;border-radius:0 0 5px 5px;opacity:0;visibility:hidden}#uploadToCnc .code_browser.x{top:0;border-top-width:0;opacity:1;visibility:visible}#uploadToCnc .code_browser textarea{width:100%;height:100%;resize:none}#uploadToCnc .code_browser .browser_tools{position:absolute;left:0;bottom:0;width:100%;text-align:right;padding:0 5px 5px 0}.alen_explorer.phone #uploadToCnc .bg{padding-left:0;padding-top:205px}.alen_explorer.phone #uploadToCnc ul{left:auto;right:0;width:49%;height:200px}.alen_explorer.phone #uploadToCnc ul.x{left:0;right:auto}@media screen and (orientation:landscape){.alen_explorer.phone #uploadToCnc .bg{padding-left:50%;padding-top:0}.alen_explorer.phone #uploadToCnc ul{width:24%;left:25%;right:auto;height:100%}.alen_explorer.phone #uploadToCnc ul.x{left:0}}</style>';
	var _urlArr;
	_alenExplorer_extends[name]={
		'setUrl':function(arr){
			_urlArr=arr;
		},
		'data':{
			'ext':['nc'],
			'icon':'<i style="color:#08c;" class="fa fa-file-code-o"></i>',
			'txt':'上传到机床',
			'name':'uploadToCnc',
			'fun':function(path,box,software){
				var explorer=_alenExplorer_extends[name].explorerObj;
				var obj=$('<div id="uploadToCnc"></div>');
				software.obj.find('.software_content').html(obj);
				var ids=[];
				var txtStr='';
				//构建UI
				var setUI=function(){
					var html=_css+'<div class="bg"><ul class="x"></ul><ul></ul><div class="selected"><div class="files"><i class="fa fa-file-code-o text-primary"></i><b class="text-primary ui_txtof"></b><span class="text-muted ui_txtof"></span><a class="btn btn-success but_browser">查看</a><a class="btn btn-success but_download">下载</a></div><div class="box"></div><div class="but"><button type="button" class="btn btn-primary but_submit fr_mr5">上传到机床</button><button type="button" class="btn btn-default but_close">取消</button></div></div></div><div class="loadbox"><span><i class="fa fa-circle-o-notch fa-spin"></i><a></a></span></div><div class="code_browser"><textarea class="form-control"></textarea><div class="browser_tools"><button type="button" class="btn btn-primary but_save fr_mr5">保存</button><button type="button" class="btn btn-default but_back">返回</button></div></div>';
					obj.html(html);
				}
				//显示加载框
				var showLoad=function(txt){
					software.isClose=false;
					txt=txt?txt:'加载中...';
					obj.find('.loadbox').addClass('x');
					obj.find('.loadbox a').text(txt);
				}
				//隐藏加载框
				var hideLoad=function(){
					software.isClose=true;
					obj.find('.loadbox').removeClass('x');
				}
				//加载车间
				var getWorkshop=function(cfun){
					alenPost(_urlArr.getName,{
							'workshop':'all',
					},function(data){
						var html='';
						for(i in data){
							html+='<li data-id="'+data[i].id+'">'+data[i].name+'</li>';
						}
						obj.find('ul:first').html(html);
						if(cfun)cfun();
					},function(err){
						if(cfun){
							cfun(err);
						}else{
							alerts(err);
						}
					});
				}
				//加载机床
				var getName=function(wid,cfun){
					alenPost(_urlArr.getName,{
							'workshop':wid,
					},function(data){
						var html='';
						for(i in data){
							var lessCls='';
							if(in_array(data[i].id,ids))lessCls=' class="x"';
							html+='<li'+lessCls+' data-id="'+data[i].id+'">'+data[i].name+'</li>';
						}
						obj.find('ul:eq(1)').html(html);
						if(cfun)cfun();
					},function(err){
						if(cfun){
							cfun(err);
						}else{
							alerts(err);
						}
					});
				}
				//添加项目
				var addCol=function(id){
					if(!in_array(id,ids)){
						ids.push(id);
						var o=obj.find('ul:eq(1) li[data-id='+id+']');
						o.addClass('x');
						var bgBox=obj.find('.selected .box');
						bgBox.append('<span data-id="'+id+'" class="label label-primary">'+o.text()+'</span>');
					}
				}
				//删除项目
				var delCol=function(id){
					var isID=false;
					for(var i in ids){
						if(id==ids[i]){
							isID=true;
							break;
						}
					}
					if(isID){
						ids.splice(i,1);
						obj.find('ul:eq(1) li[data-id='+id+']').removeClass('x');
						obj.find('.selected .box .label[data-id='+id+']').remove();
					}
				}
				
				setUI();
				obj.find('ul.x').on('click','li',function(){
					var o=$(this);
					var id=o.attr('data-id');
					showLoad();
					getName(id,function(data){	
						hideLoad();
						o.siblings().removeClass('x');
						o.addClass('x');				
					});
					
					
				});
				obj.find('ul:eq(1)').on('click','li',function(){
					var o=$(this);
					var id=o.attr('data-id');
					
					if(in_array(id,ids)){
						delCol(id);
					}else{
						addCol(id);
					}
					
				});
				obj.find('.selected').on('click','.box .label',function(){
					var o=$(this);
					var id=o.attr('data-id');
					delCol(id);
				});
				obj.find('.selected').on('click','.but_download',function(){
					explorer.cmd('download',path);
				});
				obj.find('.selected').on('click','.but_browser',function(){
					setTimeout(function(){
						obj.find('.code_browser textarea').val(txtStr);
						obj.find('.code_browser').addClass('x');
					},10);
				});
				obj.find('.selected').on('click','.but_close',function(){
					software.hide();
				});
				obj.find('.selected').on('click','.but_submit',function(){
					if(ids.length<1){
						alerts('请选择机床');
						return;
					}
					var txt=obj.find('.code_browser textarea').val();
					var Is=0;
					var delID=new Array();
					var tmpFun=function(){
						var tmpEnd=function(){
							for(var i in delID){
								delCol(delID[i]);
							}
							hideLoad();
						}
						if(Is>=ids.length){
							tmpEnd();
							return;
						}
						showLoad('上传中...'+(Is+1)+'/'+ids.length);
						uploadCode(ids[Is],txt,function(err){
							if(err){
								var alenObj=alerts(err,'',{
									'confirm':'继续上传',
									'cancel':'取消上传',
									'fun':function(val){
										if(val){
											Is++;
											tmpFun();
										}else{
											tmpEnd();
										}
									}
								});
							}else{
								delID.push(ids[Is]);
								Is++;
								tmpFun();
							}
						});
					}
					tmpFun();
				});
				obj.find('.code_browser').on('click','.but_back',function(){
					txtStr=obj.find('.code_browser textarea').val();
					obj.find('.code_browser textarea').val('');
					obj.find('.code_browser').removeClass('x');
				});
				obj.find('.code_browser').on('click','.but_save',function(){
					var txt=obj.find('.code_browser textarea').val();
					explorer.cmd('savetxt',path,txt,function(data){
						echoLog(data);
					});
				});
				var uploadCode=function(id,content,cfun){
					alenPost(_urlArr.saveCode,{
							'id':id,
							'content':content,
					},function(data){
						if(cfun)cfun();
					},function(err){
						if(cfun){
							cfun(err);
						}else{
							alerts(err);
						}
					});
					
				}
				
				
				showLoad();
				explorer.cmd('getinfo',path,'file',function(data){
					
					obj.find('.files b').text(data.name);
					obj.find('.files span').text(getFileSize(data.size));
					
					explorer.cmd('gettxt',path,function(txtData){
						txtStr=txtData.content;
						getWorkshop(function(){
							obj.find('ul.x li:first').click();
						});
					});
					
				});
			},
			'before':function(path,box,software){
				software.minWidth=650;
				software.minHeight=350;
			},
			'close':function(box,software){
			},
			'size':function(box,software){
				
			},
		},
	};
	
})();