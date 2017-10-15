<?php
namespace app\user\model;
use think\Model;
use traits\model\SoftDelete;
class userMain extends Model{
	use SoftDelete;
	protected $deleteTime = 'delete_time';
	protected $auto = ['user','ips','keys','init','type','password','status'];
	//默认写入
	protected function setIpsAttr(){
		return request()->ip();
	}
	protected function setUserAttr($val){
		return $val?$val:'_AUTO_'.get_uid();
	}
	protected function setStatusAttr($val){
		return isset($val)?$val:1;
	}
	protected function setInitAttr($val){
		return isset($val)?$val:0;
	}
	protected function setTypeAttr($val){
		return isset($val)?$val:0;
	}
	//获取器
	public function getStatusTextAttr($val,$data){
		return get_status_txt($data['status']);
	}
	public function getPasswordTextAttr($val,$data){
		return str_decrypt($data['password'],$data['keys']);
	}
}