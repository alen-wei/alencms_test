<?php
namespace app\File\controller;
use think\Validate;
use base\Ajax;
class Explorer extends Ajax{
	protected $module='File';
	
	protected function _addDir($request){
		$event=controller('user/Common', 'event');
		$user=$event->verifyLogin();
		if(!$user)return set_err_back('00020004',false);
		
		$name=$request->param('name');
		$fid=$request->param('fid');
		$userID=$user['id'];
		
		$event=controller('Explorer', 'event');
		$back=$event->addDir($name,$fid,$userID);
		if(!$back)$back=set_err_back($event->errCode,$this->module);
		return $back;
	}
	
	protected function _getInfo($request){
		$event=controller('user/Common', 'event');
		$user=$event->verifyLogin();
		if(!$user)return set_err_back('00020004',false);
		
		$path=$request->param('path');
		$type=$request->param('type');
		
		$event=controller('Explorer', 'event');
		$back=$event->getInfo($path,$type);
		if(!$back)$back=set_err_back($event->errCode,$this->module);
		return $back;
	}
	
	protected function _getList($request){
		$event=controller('user/Common', 'event');
		$user=$event->verifyLogin();
		if(!$user)return set_err_back('00020004',false);
		
		$path=$request->param('path');
		
		$event=controller('Explorer', 'event');
		$back=$event->getList($path);
		if($back===0)$back=set_err_back($event->errCode,$this->module);
		return $back?$back:true;
	}
	
	protected function _addFile($request){
		$event=controller('user/Common', 'event');
		$user=$event->verifyLogin();
		if(!$user)return set_err_back('00020004',false);
		
		$path=$request->param('path');
		$data=$request->param('data');
		$data=json_decode($data,true);
		$userID=$user['id'];
		
		$event=controller('Explorer', 'event');
		$back=$event->addFile($data,$path,$userID);
		if($back===0)$back=set_err_back($event->errCode,$this->module);
		return $back?$back:true;
	}
	
	protected function _del($request){
		$event=controller('user/Common', 'event');
		$user=$event->verifyLogin();
		if(!$user)return set_err_back('00020004',false);
		
		$ids=$request->param('ids');
		
		$event=controller('Explorer', 'event');
		$back=$event->del($ids,$user['id']);
		if($back===0)$back=set_err_back($event->errCode,$this->module);
		return $back?$back:true;
	}
	
	protected function _rename($request){
		$event=controller('user/Common', 'event');
		$user=$event->verifyLogin();
		if(!$user)return set_err_back('00020004',false);
		
		$id=$request->param('id');
		$type=$request->param('type');
		$name=$request->param('name');
		
		$event=controller('Explorer', 'event');
		$back=$event->rename($id,$type,$name);
		if($back===0)$back=set_err_back($event->errCode,$this->module);
		return $back;
		
	}
	
	protected function _move($request){
		$event=controller('user/Common', 'event');
		$user=$event->verifyLogin();
		if(!$user)return set_err_back('00020004',false);
		
		$ids=$request->param('ids');
		$path=$request->param('path');
		
		$event=controller('Explorer', 'event');
		$back=$event->move($ids,$path,$user['id']);
		if($back===0)$back=set_err_back($event->errCode,$this->module);
		return $back?$back:true;
	}
	
	protected function _copy($request){
		$event=controller('user/Common', 'event');
		$user=$event->verifyLogin();
		if(!$user)return set_err_back('00020004',false);
		
		$ids=$request->param('ids');
		$path=$request->param('path');
		
		$event=controller('Explorer', 'event');
		$back=$event->copy($ids,$path,$user['id']);
		if($back===0)$back=set_err_back($event->errCode,$this->module);
		return $back?$back:true;
	}
	
	
	protected function _getTxt($request){
		$event=controller('user/Common', 'event');
		$user=$event->verifyLogin();
		if(!$user)return set_err_back('00020004',false);
		
		$id=$request->param('path');
		
		$event=controller('Explorer', 'event');
		$back=$event->getTxt($id);
		if($back===0)$back=set_err_back($event->errCode,$this->module);
		return $back?$back:true;
		
	}
	
	protected function _saveTxt($request){
		$event=controller('user/Common', 'event');
		$user=$event->verifyLogin();
		if(!$user)return set_err_back('00020004',false);
		
		$id=$request->param('path');
		$content=$request->param('content');
		
		$event=controller('Explorer', 'event');
		$back=$event->saveTxt($id,$content,$user['id']);
		if($back===0)$back=set_err_back($event->errCode,$this->module);
		return $back?$back:true;
		
	}
	
	protected function _getUrl($request){
		$event=controller('user/Common', 'event');
		$user=$event->verifyLogin();
		if(!$user)return set_err_back('00020004',false);
		
		$id=$request->param('id');
		
		$event=controller('Explorer', 'event');
		$back=$event->getUrl($id,$user['id']);
		if($back===0)$back=set_err_back($event->errCode,$this->module);
		return $back?$back:true;
	}
	
	protected function _download($request){
		$event=controller('user/Common', 'event');
		$user=$event->verifyLogin();
		if(!$user)return set_err_back('00020004',false);
		
		$id=$request->param('path');
		
		$event=controller('Explorer', 'event');
		$back=$event->getFile($id,true);
		
		$event=controller($this->module.'/Common', 'event');
		$back=$event->downloadFile($back);
		if($back){
			exit();
		}else{
			return set_err_back($event->errCode,$this->module);
		}
	}
}