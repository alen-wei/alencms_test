<?php
namespace app\File\model;
use think\Model;
use traits\model\SoftDelete;
class fileContent extends Model{
	use SoftDelete;
	protected $deleteTime = 'delete_time';
}