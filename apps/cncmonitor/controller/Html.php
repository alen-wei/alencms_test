<?php
namespace app\CncMonitor\controller;
use base\Tpl;
class Html extends Tpl{
	protected $module='CncMonitor';
	protected function _frame(){
		$this->page_title='监控系统';
		$event=controller('user/Common', 'event');
		$user=$event->verifyLogin();
		return ['user'=>$user];
    }
    protected function _monitor(){
		$this->page_title='实时监控';
		return ['test'=>'asdasd'];
    }
	protected function _chart(){
		$this->page_title='数据统计分析';
		return true;
    }
	
	protected function _board($request){
		
		$type=$request->param('type');
		$workshop=$request->param('workshop');
		if(!$type and !$workshop){
			$til='车间看板 - 全部车间';
		}else{
			$til='车间看板 - 车间#'.$workshop;
			if($type)$til.=' - '.($type==1?'实时':'任务');
		}
		$this->page_title=$til;
		return ['type'=>$type,'workshop'=>$workshop];
    }
	protected function _tv(){
		$this->page_title='车间看板 - 全部车间';
		return true;
    }
	protected function _tv_status(){
		$this->page_title='车间看板 - '.input('workshop').'号车间 状态';
		return true;
    }
	protected function _tv_mission(){
		$this->page_title='车间看板 - '.input('workshop').'号车间 任务';
		return true;
    }
	protected function _code(){
		$this->page_title='程序管理';
		$event=controller('user/Common', 'event');
		$user=$event->verifyLogin();
		if(!$user)$this->Tpl='disable';
		return true;
	}
	protected function _code_bank(){
		$this->page_title='程序库管理';
		$event=controller('user/Common', 'event');
		$user=$event->verifyLogin();
		if(!$user)$this->Tpl='disable';
		return true;
	}
}
