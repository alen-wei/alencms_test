<?php
namespace app\Admin\model;
use think\Model;
use traits\model\SoftDelete;
class adminGroup extends Model{
	use SoftDelete;
	protected $deleteTime = 'delete_time';
}