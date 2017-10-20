<?php
namespace app\User\event;
use app\User\model\userNotice;
class Notice{
	protected $module='User';
	public $errCode='';
	
	public function add($data,$autotxt=true,$autoimg=true,$download=false,$userID=false){
		if(!isset($data['name']) or !$data['name']){
			$this->errCode='00010009';
			return false;
		}
		if(!isset($data['content']) or !$data['content']){
			$this->errCode='00010008';
			return false;
		}
		$content=$data['content'];
		unset($data['content']);
		if($autotxt){
			$data['txts']=get_html_txts($content,500);
		}else{
			$data['txts']=isset($data['txts'])?$data['txts']:'';
		}
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
		if(!$data['publish_time'])$data['publish_time']=get_now_time();
		if(!$data['expire_time'])$data['expire_time']=0;
		$data['user_id']=$userID;
		
		$notice=userNotice::create($data);
		//$notice=['id'=>1];
		if(!$notice){
			$this->errCode='00010002';
			return false;
		}
		$event=controller('File/Content','event');
		$back=$event->save($content,$this->module,'notice',$notice['id'],$download);
		if(!$back){
			$notice->delete();
			$this->errCode=set_err_back($event->errCode,'File');
			return false;
		}
		$notice->content_id=$back;
		$notice->save();
		return $notice;
	}
	
	public function getLists($wh=null,$tmp_field=true,$order=null,$page=null,$del=false){
		
	}
	
}