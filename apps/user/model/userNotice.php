<?php
namespace app\user\model;
use think\Model;
use traits\model\SoftDelete;
class userNotice extends Model{
	use SoftDelete;
	protected $deleteTime = 'delete_time';
	protected $auto = ['ips'];
	//默认写入
	protected function setIpsAttr(){
		return request()->ip();
	}
}