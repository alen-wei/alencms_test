<?php
namespace app\User\event;
use app\User\model\userNotice;
class Notice extends \base\Event{
	protected $module='User';
	public $model='\\app\\User\\model\\userNotice';
	public $errCode='';
	
	public function add($data,$autotxt=true,$autoimg=true,$download=false,$userID=false){
		if(isset($data['id']))unset($data['id']);
		$this->save($data,$autotxt,$autoimg,$download,$userID);
	}
	
	public function save($data,$autotxt=true,$autoimg=true,$download=false,$userID=false){
		if(!isset($data['name']) or !$data['name']){
			$this->errCode='00010009';
			return false;
		}
		if(isset($data['content'])){
			$content=trim($data['content']);
			if($autotxt){
				$data['txts']=get_html_txts($content,500);
			}else{
				$data['txts']=isset($data['txts'])?$data['txts']:'';
			}
		}else{
			unset($data['txts']);
		}
		unset($data['content']);
		
		
		if(!isset($data['img']) or !$data['img']){
			if($autoimg){
				$imgArr=get_html_img($content);
				if($imgArr){
					$data['img']=$imgArr[0][0];
				}
			}else{
				$data['img']='';
			}
		}
		if(!isset($data['id'])){
			if(!$data['publish_time'])$data['publish_time']=get_now_time();
			if(!$data['expire_time'])$data['expire_time']=0;
		}
		if($userID)$data['user_id']=$userID;
		if(isset($data['id'])){
			$notice=$this->model::get($data['id']);
		}else{
			$notice=$this->model::create($data);
		}
		if(!$notice){
			$this->errCode='00010002';
			return false;
		}
		if(isset($content)){
			$event=controller('File/Content','event');
			$back=$event->save($content,$this->module,'notice',$notice['id'],$download);
			if(!$back){
				if(!isset($data['id']))$notice->delete();
				$this->errCode=set_err_back($event->errCode,'File');
				return false;
			}
		}else{
			$back=0;
		}
		$dbData=isset($data['id'])?$data:['id'=>$notice['id']];
		$dbData['content_id']=$back;
		//$notice->content_id=$back;
		$notice->save($dbData);
		return $notice;
	}
}