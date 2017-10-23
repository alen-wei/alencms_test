<?php
namespace app\File\event;
use app\File\model\fileContent;
class Content{
	use \base\TraitJob;
	protected $module='file';
	public $errCode='';
	public $maxNum=2;
	
	public function save($content,$module,$less,$byid,$download=0){
		
		$moduleID=config('app_id');
		if(!isset($moduleID[strtolower($module)])){
			$this->errCode='00010003';
			return false;
		}
		$moduleID=$moduleID[strtolower($module)];
		$content=trim($content);
		if(!$content)return true;
		$imgArr=get_html_img($content);
		if($imgArr){
			foreach($imgArr[0] as $k=>$v){
				$content=str_replace($imgArr[1][$k],$v,$content);
			}
		}
		$where=[
			'module_id'=>$moduleID,
			'less'=>$less,
			'by_id'=>$byid,
		];
		$data=[
			'content'=>$content,
			'ver'=>1,
			'lock'=>$download?1:0,
		];
		$count=fileContent::where($where)->count();
		if($count<1){
			$back=fileContent::create(array_merge($where,$data));
		}else{
			$old=fileContent::field('id,content,ver')->where($where)->order(['ver'=>'DESC'])->find();
			if(md5($old->content)==md5($content)){
				return $old->id;
			}else{
				$data['ver']=$old->ver+1;
				if($count<$this->maxNum){
					$back=fileContent::create(array_merge($where,$data));
				}else{
					$tmp=fileContent::field('id,content,ver')->where($where)->order(['update_time'=>'ASC'])->find();
					foreach($data as $k=>$v){
						$tmp->$k=$v;
					}
					$back=$tmp->save();
					$id=$tmp->id;
				}
			}
		}
		if($back===false){
			$this->errCode='0010';
			return false;
		}
		if(!isset($id))$id=$back['id'];
		if($download)$back=$this->call_job('downloadImg',[$id],true);
		return $id;
	}
	public function downloadImg($id){
		//$isJob=config('queue_switch');
		$data=fileContent::find($id);
		$content=$data['content'];
		if(!$content)return true;
		$tmp=get_html_img($content,true);
		$static_url=config('static_url');
		$xyArr=['http','https'];
		$imgArr=[];
		foreach($tmp[0] as $v){
			$continue=true;
			foreach($xyArr as $vv){
				if(!$continue)break;
				if($vv==strtolower(substr($v,0,strlen($vv))))$continue=false;
			}
			if($continue)continue;
			if(0===stripos($v,$static_url))continue;
			$imgArr[]=$v;
		}
		if(count($imgArr)<1)return true;
		$imgArr=array_unique($imgArr);
		$event=controller($this->module.'/Common', 'event');
		$newArr=[];
		foreach($imgArr as $v){
			$tmpFile=set_temp_file(get_uid(true),get_url_file($v),true,true);
			$ext=strtolower(pathinfo($v, PATHINFO_EXTENSION));
			if(!$ext){
				$finfo = finfo_open(FILEINFO_MIME_TYPE);
				$ext= explode('/', finfo_file($finfo,$tmpFile));
				$ext=$ext[1];
			}
			$back=$event->localFile($tmpFile,$ext);
			if(!$back){
				$this->errCode=$event->errCode;
				return false;
			}
			$back=array_shift($back);
			$path=config('static_path').str_replace('/',DS,$back['url']);
			$event->createThumb($path);
			$newArr[]=$back['url'];
		}
		foreach ($imgArr as $k=>$v){
			$content=str_replace($v,$newArr[$k],$content);
		}
		$data->content=$content;
		$data->lock=0;
		$back=$data->save();
		if(!$back){
			$this->errCode='00010002';
			return false;
		}
		return true;
	}
	
}