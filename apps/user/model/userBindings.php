<?php
namespace app\user\model;
use think\Model;
use traits\model\SoftDelete;
class userBindings extends Model{
	use SoftDelete;
	protected $deleteTime = 'delete_time';
	protected $auto = ['expire_time','ips','status'];
	//默认写入
	protected function setIpsAttr(){
		return request()->ip();
	}
	protected function setStatusAttr($val){
		return isset($val)?$val:1;
	}
	protected function setExpireTimeAttr($val){
		return isset($val)?$val:0;
	}
	//获取器
	public function getStatusTextAttr($val,$data){
		return get_status_txt($data['status']);
	}
	protected function getTypeTextAttr($val,$data){
		$typeArr = [1=>lang('phone'),2=>lang('email'),3=>lang('qq'),4=>lang('weixin')];
		return $typeArr[$data['type']];
	}
}