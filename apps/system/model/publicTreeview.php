<?php
namespace app\System\model;
use think\Model;
use traits\model\SoftDelete;
class publicTreeview extends Model{
	use SoftDelete;
	protected $deleteTime = 'delete_time';
	protected $updateTime = false;
}