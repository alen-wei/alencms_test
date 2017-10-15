<?php
namespace app\CncMonitor\controller;
use base\Ajax;

use app\System\model\publicTreeview;

class Api extends Ajax{
	protected $module='CncMonitor';
	//获取名称
	protected function _getName($request){
		$ids=$request->param('ids');
		$workshop=$request->param('workshop');
		$event=controller('Getdata', 'event');
		$data=$event->getName($ids,$workshop);
		if(!$data)$data=set_err_back($event->errCode,$this->module);
		return $data;
	}
	//获取数据
	protected function _getData($request){
		$ids=$request->param('ids');
		$time=$request->param('time');
		$endtime=$request->param('endtime');
		$fields=$request->param('fields');
		$workshop=$request->param('workshop');
		if($workshop){
			$ids=$workshop;
			$workshop=true;
		}else{
			$workshop=false;
		}
		$event=controller('Getdata', 'event');
		$data=$event->getData($ids,$time,$endtime,$fields,$workshop);
		if(!$data)$data=set_err_back($event->errCode,$this->module);
		return $data;
	}
	//获取程序
	protected function _getCode($request){
		$event=controller('user/Common', 'event');
		if(!$event->verifyLogin())return set_err_back('00020004',false);
		
		$id=$request->param('id');
		$codeid=$request->param('codeid');
		$event=controller('Getdata', 'event');
		$back=$event->getCode($id,$codeid);
		if(!$back)$back=set_err_back($event->errCode,$this->module);
		return $back;
	}
	//删除程序
	protected function _delCode($request){
		$event=controller('user/Common', 'event');
		if(!$event->verifyLogin())return set_err_back('00020004',false);
		
		$id=$request->param('id');
		$codeid=$request->param('codeid');
		$event=controller('Getdata', 'event');
		$back=$event->delCode($id,$codeid);
		if(!$back)$back=set_err_back($event->errCode,$this->module);
		return $back;
	}
	//保存程序
	protected function _saveCode($request){
		$event=controller('user/Common', 'event');
		if(!$event->verifyLogin())return set_err_back('00020004',false);
		
		$id=$request->param('id');
		$content=$request->param('content');
		$event=controller('Getdata', 'event');
		$back=$event->saveCode($id,$content);
		if(!$back)$back=set_err_back($event->errCode,$this->module);
		return $back;
	}
	//下载程序
	protected function _downCode($request){
		$event=controller('user/Common', 'event');
		if(!$event->verifyLogin())return set_err_back('00020004',false);
		
		$id=$request->param('id');
		$codeid=$request->param('codeid');
		$event=controller('Getdata', 'event');
		$content=$event->getCode($id,$codeid);
		header('Content-type: text/plain');
		header('Content-Disposition: attachment; filename="'.$codeid.'.nc"');
		echo $content;
		exit();
	}
	//锁定机床
	protected function _setAuthCnc($request){
		
		$groups=$request->param('groups');
		$userID=$request->param('userid');
		$id=$request->param('id');
		$sn=$request->param('sn');
		$times=$request->param('times');
		
		$event=controller('Getdata', 'event');
		$back=$event->setAuthCnc($id,$sn,$userID,$groups,$times);
		
		if(!$back)return set_err_back($event->errCode,$this->module);
		return $back;
	}
	
	protected function _test($request){
		
		$event=controller('file/Explorer', 'event');
		$back=$event->addDir('alend*ir');
		if(!$back)return set_err_back($event->errCode,'file');
		return $back;
	}
}