//_alenExplorer_extends
(function(){
	var name='videoPlayer';
	if(typeof(_alenExplorer_extends)=="undefined"){
		echoLog('请先加载alen.Explorer.js');
		return;
	}
	var _css='<style>#videoPlayer,#videoPlayer .vidObj{position:relative;width:100%;height:100%;display:block;}#videoPlayer .loadbox{position:absolute;left:0;top:0;width:100%;height:100%;background:rgba(255,255,255,.5);text-align:center;opacity:0;visibility:hidden;transition:all .5s}#videoPlayer .loadbox span{background:rgba(0,0,0,.6);display:inline-block;border-radius:10px;overflow:hidden;padding:10px;box-shadow:0 0 10px #666;position:relative;margin-top:-100px;transition:all .5s}#videoPlayer .loadbox.x{opacity:1;visibility:visible}#videoPlayer .loadbox.x span{margin-top:20px}#videoPlayer .loadbox span i{font-size:3em;line-height:1em;float:left;color:#FFF}#videoPlayer .loadbox span a{font-size:1.5em;float:left;line-height:2em;margin-left:5px;color:#FFF}</style>';
	var _urlArr;
	_alenExplorer_extends[name]={
		'setUrl':function(arr){
			_urlArr=arr;
		},
		'data':{
			'ext':['mp4'],
			'icon':function(less,id){
				return '<i style="color:#5bc0de;" class="fa fa-film"></i><small style="color:#5bc0de;" class="fa fa-play"></small>';
				
			},
			'txt':'视频播放器',
			'name':name,
			'fun':function(path,box,software){
				var explorer=_alenExplorer_extends[name].explorerObj;
				var obj=$('<div id="'+name+'"></div>');
				software.obj.find('.software_content').html(obj);
				var ids=[];
				var txtStr='';
				//构建UI
				var setUI=function(){
					var html=_css+'<div class="loadbox"><span><i class="fa fa-circle-o-notch fa-spin"></i><a></a></span></div>';
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
				setUI();
				showLoad();
				explorer.cmd('getUrl',path,function(data){
					hideLoad();
					data=data[path];
					var vidUrl=_sys.static_url+data.path;
					var vo=$('<video class="vidObj" controls src="'+vidUrl+'"></video>');
					obj.append(vo);
					setVideo(vo,true);
				});
			},
		},
	};
	
})();