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
		//$back=false;
		if($back){
			$this->echoStatus();
			$job->delete();
		}else{
			$errArr=set_err_back($event->errCode,$event->getJobModule());
			//$errArr=['_txt'=>'test'];
			$this->echoStatus($errArr['_txt'],true);
			$job->release();
		}
	}
	public function failed($data){
		$this->class=$data['class'];
		$this->fun=$data['fun'];
		$this->param=$data['param'];
		$this->echoStatus('',true);
	}
	public function echoStatus($str='',$err=false){
		$space='    ';
		if($err){
			if($str){
				$status='Error';
			}else{
				$status='Failed';
				$str='Too many retries';
				$space=' ';
			}
		}else{
			$status='Success';
		}
		$echoStr='==['.get_now_time('Y-m-d H:i:s').']================='."\r\n";
		$echoStr.=$this->class.'@'.$this->fun.'=>'.$status."\r\n";
		$echoStr.=$space.'Param：'.json_encode($this->param,JSON_UNESCAPED_UNICODE)."\r\n";
		if($this->job)$echoStr.=$space.'  Num：'.$this->job->attempts()."\r\n";
		if($str)$echoStr.=$space.'  Msg：'.$str."\r\n";
		echo mb_convert_encoding($echoStr,config('os_code'),"UTF-8");
	}
}