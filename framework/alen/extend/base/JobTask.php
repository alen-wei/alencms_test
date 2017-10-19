<?php
namespace base;
use think\queue\Job;
class JobTask{
	public $class;
	public $fun;
	public $param;
	public $job;
	
	public function fire(Job $job,$data){
		$this->class=$data['class'];
		$this->fun=$data['fun'];
		$this->param=$data['param'];
		$this->job=&$job;
		
		$event=new $this->class;
		$back=call_user_func_array([$event,$this->fun],$this->param);
		$back=false;
		$event->errCode='00010001';
		if($back){
			$this->echoStatus();
			$job->delete();
		}else{
			$errArr=set_err_back($event->errCode,$event->module);
			$this->echoStatus($errArr['_txt'],true);
			//abort(500,'error');
		}
	}
	public function failed($data){
		$this->class=$data['class'];
		$this->fun=$data['fun'];
		$this->param=$data['param'];
		$this->echoStatus('任务超过重试次数','failed');
	}
	public function echoStatus($str='',$err=false){
		if($err){
			$status=$err=='failed'?'失败':'错误';
		}else{
			$status='成功';
		}
		$echoStr=get_now_time('Y-m-d H:i:s').':'.$this->class.'@'.$this->fun.'['.$status."]\r\n";
		$echoStr.='参数：'.json_encode($this->param,JSON_UNESCAPED_UNICODE)."\r\n";
		if($this->job)$echoStr.='次数：'.$this->job->attempts()."\r\n";
		if($str)$echoStr.='消息：'.$str."\r\n";
		echo mb_convert_encoding($echoStr,config('os_code'),"UTF-8");
	}
}