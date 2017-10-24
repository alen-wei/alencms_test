var subscribeSer=function(opTokenUrl){
	var tmpThis=this;
	var _connect,_tokenUrl;
	var _conDrive={};
	var _driveFile=['ws'];
	var _getToken=function(backFun){
		alenPost(_tokenUrl,{},function(data){
			if(backFun)backFun(data);
		},function(err){
			echoLog(err);
			setTimeout(function(){
				_getToken(backFun);
			},5000);
		});
	};
	var _setConnect=function(data){
		var type=data.type;
		var runFun=function(){
			_connect=new _conDrive[type](data.url,data.token,data.time);
		};
		if(!(type in _conDrive)){
			if(in_array(type,_driveFile)){
				include(_sys.static_url+'alenSubscribeSer/conDrive/'+type+'.js','js',function(){
					tmpThis.addDrive(type,window.subscribeSer_TmpConDrive);
					window.subscribeSer_TmpConDrive=null;
					runFun();
				});
				return 0;
			}else{
				var err='找不到'+type+'驱动';
				echoLog(err);
				if(tmpThis.onErr)tmpThis.onErr(err);
				return false;
			}
		}
		runFun();
		return true;
	};
	this.onErr=null;
	this.addDrive=function(name,clsFun){
		_conDrive[name]=clsFun;
	};
	this.init=function(){
		_getToken(function(data){
			_setConnect(data);
		});
	};
	var _init=function(){
		_tokenUrl=opTokenUrl;
	};
	_init();
};

