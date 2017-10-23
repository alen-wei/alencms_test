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
	public function getContentHtmlAttr(){
		$content=$this->content;
		return count($content)>0?get_content_html($content[0]->content):'';
	}
	public function content(){
    	return $this->morphMany('\\app\\File\\model\\fileContent',['module_id','by_id'],2)->where(['less'=>'notice'])->field('id,content,update_time,delete_time,lock')->order(['ver'=>'DESC'])->limit(1);
    }
	public function user(){
    	return $this->belongsTo('userMain','user_id')->field('id,user,img,name,status,create_time,delete_time');
    }
}