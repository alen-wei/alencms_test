<?php
namespace app\CncMonitor\model;
use think\Model;
class cncmonitorStatus extends Model{
	protected $deleteTime = 'delete_time';
	protected $auto = ['status'];
	//默认写入
	protected function setStatusAttr($val){
		return $val?$val:0;
	}
}