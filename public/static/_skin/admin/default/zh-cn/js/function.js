var pageMsg=function(winObj,_OP){
	var tmpThis=this;
	var token=null;
	var client={};
	var ctrlList={};
	var runList={};
	var diyFun=null;
	var _encryptFun=easy_encrypt;	//加密函数
	var _decryptFun=easy_decrypt;	//解密函数
	var _getUID=getUID;				//生成UID
	var _setTimemap=function(){return Date.parse(new Date());};		//获取时间戳
	
	var backFun={
		'suc':{},
		'err':{}
	};
	
	this.targetWin=null;		//目标页面
	this.targetUrl=null;		//目标网址
	this.acceptData=null;		//接受到的信息
	
	this.closeIng=false;		//是否正在关闭中
	
	this.onAccept=null;			//事件，收到信息时
	this.onHandclasp=null;		//事件，握手成功时
	this.onClose=null;			//事件，关闭连接时
	
	//关闭
	this.close=function(){
		var postData={
			'_type':'close'
		};
		if(tmpThis.targetWin){
			tmpThis.send(postData,null,closeFun);
		}else{
			for(var k in client){
				tmpThis.send(postData,k,closeFun);
			}
		}
	};
	//发送信息
	this.send=function(postData,pageID,sucFun,errFun){
		if(tmpThis.closeIng)return;
		if(postData._type=='close')tmpThis.closeIng=true;
		var url;
		var obj;
		if(tmpThis.targetWin){
			if(token){
				if(postData._type=='handclasp')return;
				url=tmpThis.targetUrl;
				postData._token=token;
			}else{
				if(postData._type!='handclasp')return;
				url='*';
			}
			obj=tmpThis.targetWin;
		}else{
			if(!(pageID in client))return;
			obj=client[pageID].obj;
			url=client[pageID].url;
			postData._token=pageID;
		}
		if(!obj || !url)return;
		if(postData._type!='result'){
			postData['_ctrlid']=_getUID();
			ctrlList[postData['_ctrlid']]=-1;
			backFun.suc[postData['_ctrlid']]=function(data){
				ctrlList[data._ctrlid]=1;
				if(sucFun)sucFun(data);
				delete backFun.suc[data._ctrlid];
			};
			backFun.err[postData['_ctrlid']]=function(data){
				ctrlList[data._ctrlid]=0;
				if(errFun)errFun(data);
				delete backFun.suc[data._ctrlid];
			};
		}
		var postFun=function(tmpFun,opObj,numI){
			numI=numI?numI:0;
			if(numI>10)return;
			var back=tmpFun(opObj);
			if(back){
				setTimeout(function(){
					numI++;
					postFun(tmpFun,opObj,numI);
				},500);
			}
		};
		postFun(function(tmpObj){
			var back=true;
			if(tmpObj.data._type!='result'){
				if(ctrlList[tmpObj.data._ctrlid]!=-1)return false;
			}else{
				back=false;
			}
			tmpObj.obj.postMessage(setPostData(tmpObj.data),tmpObj.url);
			return back;
		},{
			'url':url,
			'obj':obj,
			'data':postData
		});
	};
	//初始化
	this.init=function(){
		//注册页面关闭时事件
		window.addEventListener("beforeunload",function(){
			tmpThis.close();
		});
		//注册接受信息事件
		window.addEventListener("message",function(event){
			tmpThis.acceptData=getPostData(event.data);
			
			echoLog({
				'type':tmpThis.targetWin?'客户端':'服务端',
				'data':tmpThis.acceptData
			});
			
			if(!('_type' in tmpThis.acceptData))return;
			if(!('_ctrlid' in tmpThis.acceptData))return;
			if(!('_token' in tmpThis.acceptData) && tmpThis.targetWin)return;
			
			var type=tmpThis.acceptData._type;
			var ctrlID=tmpThis.acceptData._ctrlid;
			var pageID=tmpThis.acceptData._token;
			var backData=tmpThis.acceptData;
			backData._pageObj=event.source;
			//处理结果信息
			var resultFun=function(){
				if(ctrlID in ctrlList){
					if(ctrlList[ctrlID]!=-1)return;
				}else{
					return;
				}
				if(backData.status==true){
					backFun.suc[ctrlID](backData);
				}else{
					backFun.err[ctrlID](backData);
				}
			};
			if(tmpThis.targetWin){
				if(!token){
					if(type=='result')resultFun();
					return;
				}
				
			}else{
				//如果没有握手的话，处理握手
				if(type=='handclasp'){
					handclasp(backData);
					return;
				}
			}
			
			if(type=='result'){
				resultFun();
				return;
			}
			
			if(ctrlID in runList)return;
			runList[ctrlID]=pageID;
			switch(type){
				case 'close':
					sendResult(true,ctrlID);
					closeFun(backData);
				break;
				case 'run':
					eval(backData.code);
					sendResult(true,ctrlID);
				break;
				default:
					if(!(type in diyFun))break;
					sendResult(diyFun[type](backData),ctrlID);
			}
			if(tmpThis.onAccept)tmpThis.onAccept(backData);
			return;
		});
		if(winObj){
			handclasp();
		}
	};
	
	//关闭函数
	var closeFun=function(data){
		if(tmpThis.targetWin){
			tmpThis.targetWin=null;
			tmpThis.targetUrl=null;
			tmpThis.acceptData=null;
			client={};
			ctrlList={};
			runList={};
			token=null;
			backFun={
				'suc':{},
				'err':{}
			};
			tmpThis.closeIng=false;
			if(tmpThis.onClose)tmpThis.onClose(data);
		}else{
			var pageID=data._token;
			if(!(pageID in client))return;
			delete client[pageID];
			if(objCount(client)<1){
				tmpThis.closeIng=false;
				if(tmpThis.onClose)tmpThis.onClose(data);
			}
		}
	};
	//获取页面通讯URL
	var getPageUrl=function(){
		return window.location.href;
	};
	//处理发送信息
	var setPostData=function(data){
		return _encryptFun(JSON.stringify(data));
	};
	//处理接受的信息
	var getPostData=function(data){
		return JSON.parse(_decryptFun(data));
	};
	//发送结果信息
	var sendResult=function(type,cid,lessData){
		if(!(cid in runList))return;
		var data=lessData?lessData:{};
		data._type='result';
		data._ctrlid=cid;
		data.status=type?true:false;
		tmpThis.send(data,runList[cid]);
	};
	//握手
	var handclasp=function(data){
		if(data){		//处理握手信息
			var ctrlid=data._ctrlid;
			if(ctrlid in runList)return;
			
			var timemap=data.time;
			var tmpStr=_decryptFun(data.str);
			var tmpStr=tmpStr.split(';');
			
			if(timemap!=tmpStr[0])return;
			tmpStr.shift();
			var tmpToken=_getUID();
			var tmpClient={
				'url':tmpStr.join(';'),
				'obj':data._pageObj,
				'list':[]
			};
			client[tmpToken]=tmpClient;
			runList[ctrlid]=tmpToken;
			sendResult(true,data._ctrlid,{'url':getPageUrl()});
			data.url=tmpClient.url;
			if(tmpThis.onHandclasp)tmpThis.onHandclasp(data,tmpToken);
		}else{			//发送握手信息
			var timemap=_setTimemap();
			var postData={
				'_type':'handclasp',
				'time':timemap,
				'str':_encryptFun(timemap+';'+getPageUrl())
			};
			tmpThis.send(postData,null,function(backData){
				token=backData._token;
				tmpThis.targetUrl=backData.url;
				if(tmpThis.onHandclasp)tmpThis.onHandclasp(backData,token);
			});
		}
	};
	//默认函数
	var _autoFun=function(){
		if(winObj)tmpThis.targetWin=winObj;
		diyFun=_OP;
	};
	_autoFun();
	return this;
};