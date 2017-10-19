<?php
namespace base;
use think\queue\Job;
class JobTask{
	public function fire(Job $job,$data){
		echo 'ss';
		abort(500,get_err_data('00010002'));
		return;
		$event=new $data['class'];
		call_user_func_array([$event,$data['fun']],$data['param']);
		$job->delete();
	}
	public function failed($data){
		echo print_r($data,true);
	}
}