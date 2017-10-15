<?php
namespace app\File\model;
use think\Model;
use traits\model\SoftDelete;
class fileItem extends Model{
	use SoftDelete;
	protected $deleteTime = 'delete_time';
}