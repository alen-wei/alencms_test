<?php
namespace app\user\model;
use think\Model;
use traits\model\SoftDelete;
class userLogType extends Model{
	use SoftDelete;
	protected $deleteTime = 'delete_time';
}