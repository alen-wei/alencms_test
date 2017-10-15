<?php
namespace app\CncMonitor\model;
use think\Model;
class cncmonitorIrisLog extends Model{
	protected $deleteTime = 'delete_time';
	protected $auto = ['ips'];
	//默认写入
	protected function setIpsAttr(){
		return request()->ip();
	}
}