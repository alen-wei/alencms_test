<?php
namespace base;
trait TraitJob{
	//验证
	protected function call_job($funName,$param,$slow=false){
		$isJob=false;
		if(!method_exists($this,$funName)){
			$this->errCode='00010004';
			return false;
		}
		if($isJob){
			if($slow===true)$slow='slow';
			$queueName=$slow?$slow:'default';
			$data=[
				'class'=>__CLASS__,
				'fun'=>$funName,
				'param'=>$param
			];
			$isPushed=\think\Queue::push('\\base\\JobTask',$data,$queueName);
			if(!$isPushed){
				$this->errCode='00010010';
				return false;
			}
			return true;
		}else{
			return call_user_func_array([$this,$funName],$param);
		}
	}
}