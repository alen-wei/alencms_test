<?php
namespace app\Admin\model;
use think\Model;
use traits\model\SoftDelete;
class adminUser extends Model{
	use SoftDelete;
	protected $deleteTime = 'delete_time';
	protected $auto = ['status'];
	//默认写入
	protected function setStatusAttr($val){
		return isset($val)?$val:1;
	}
        
    public function getStatusTextAttr($val,$data){
		//$statusArr = [-1=>lang('del'),0=>lang('disable'),1=>lang('normal'),2=>lang('to be verified')];
		return get_status_txt($data['status']);
	}
}