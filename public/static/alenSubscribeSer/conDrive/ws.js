(function(){
	if(typeof(subscribeSer)=="undefined"){
		echoLog('请先加载subscribeSer类');
		return;
	}
	window.subscribeSer_TmpConDrive=function(opUrl,opToken,opTokenTime){
		var tmpThis=this;
		var _url,_con,_token,_tokenTime,_verify;
		var _setSendData=function(data){
			return JSON.stringify(data);
		};
		var _getMsg=function(msg){
			return JSON.parse(msg);
		};
		var _evt={
			'onopen':function(e){
				echoLog('连接成功');
				if(tmpThis.onInit)tmpThis.onInit();
				tmpThis.send({
					'type':'verify',
					'token':_token,
					'time':_tokenTime
				});
			},
			'onmessage':function(e){
				var data=_getMsg(e.data);
				if(!'type' in data)return;
				if('verify'==data.type){
					if('err' in data){
						echoLog('验证失败:'+data.err);
						if(tmpThis.onErr)tmpThis.onErr(data.err);
					}else{
						echoLog('验证成功');
						_verify=true;
						if(tmpThis.onVerify)tmpThis.onVerify();
					}
					return;
				}
				if(!_verify)return;
				if(tmpThis.onMsg)tmpThis.onMsg(data);
			},
			'onclose':function(e){
				echoLog('关闭连接');
				if(tmpThis.onClose)tmpThis.onClose();
				_con=null;
			}
		};
		this.onInit=null;
		this.onVerify=null;
		this.onMsg=null;
		this.onClose=null;
		this.onErr=null;
		this.send=function(data){
			if(!_con)return false;
			var msg=_setSendData(data);
			_con.send(msg);
			return true;
		};
		var _init=function(){
			_verify=false;
			_url=opUrl;
			_token=opToken;
			_tokenTime=opTokenTime;
			_con = new WebSocket("ws://"+_url);
			for(var evtName in _evt){
				_con[evtName]=_evt[evtName];
			}
		};
		_init();
	};
})();