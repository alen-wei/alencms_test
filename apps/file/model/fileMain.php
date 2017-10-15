<?php
namespace app\File\model;
use think\Model;
use traits\model\SoftDelete;
class fileMain extends Model{
	use SoftDelete;
	protected $deleteTime = 'delete_time';
	protected $auto = ['ips','status','apply','user_id'];
	//默认写入
	protected function setIpsAttr(){
		return request()->ip();
	}
	protected function setStatusAttr($val){
		return isset($val)?$val:1;
	}
	protected function setApplyAttr($val){
		return isset($val)?$val:0;
	}
	protected function setuserIdAttr($val){
		return isset($val)?$val:0;
	}
}