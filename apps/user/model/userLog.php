<?php
namespace app\user\model;
use think\Model;
use traits\model\SoftDelete;
class userLog extends Model{
	protected $connection = 'nosql';	//使用nosql
	use SoftDelete;
	protected $deleteTime = 'delete_time';
	protected $auto = ['ips'];
	
	//默认写入
	protected function setIpsAttr(){
		return request()->ip();
	}
}