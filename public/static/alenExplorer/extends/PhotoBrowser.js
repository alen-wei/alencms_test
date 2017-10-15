//_alenExplorer_extends
(function(){
	var name='PhotoBrowser';
	if(typeof(_alenExplorer_extends)=="undefined"){
		echoLog('请先加载alen.Explorer.js');
		return;
	}
	var _urlArr;
	var delayed=500;
	var loadTime;
	var loadPic=function(){
		var ids=[];
		var objList={};
		var eObj=_alenExplorer_extends[name].explorerObj;
		$('[data-imgid]').map(function(){
			var tmp=$(this).attr('data-imgid');
			ids.push(tmp);
			objList[tmp]=$(this);
        });
		if(ids.length<1)return;
		eObj.cmd('getUrl',ids.join(','),function(data){
			var thumbImg,pathImg;
			for(var i in data){
				thumbImg='';
				pathImg='';
				if('thumb' in data[i]){
					thumbImg=_sys.static_url+data[i].thumb[320];
					pathImg=_sys.static_url+data[i].path;
				}
				objList[i].parent('.img').html('<img data-img="'+pathImg+'" src="'+thumbImg+'" />');
			}
		});
	}
	_alenExplorer_extends[name]={
		'setUrl':function(arr){
			_urlArr=arr;
		},
		'data':{
			'ext':['jpg','jpeg','png','gif'],
			'icon':function(less,id){
				if(loadTime)clearTimeout(loadTime);
				loadTime=setTimeout(loadPic,delayed);
				return '<i data-imgid="'+id+'" style="color:#5cb85c;" class="fa fa-picture-o"></i><small style="color:#5cb85c;" class="fa fa-refresh fa-spin"></small>';
				
			},
			'txt':'图片浏览器',
			'name':name,
			'ui':false,
			'fun':function(path,box,software){
				var o=box.find('.alen_file[data-id='+path+']');
				o=o.find('[data-img]');
				imgZoom(o,{
					'history':false,
					'_box':box.find('.alen_filebox'),
					'_close':function(){
						software.hide();
				}});
			},
		},
	};
	
})();