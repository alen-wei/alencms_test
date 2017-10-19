<?php
namespace app\File\event;
use app\File\model\fileContent;
class Content{
	use \base\TraitJob;
	
	protected $module='file';
	public $errCode='';
	//验证文件/文件夹名称
	public function add($content,$module,$less,$byid,$download=0){
		$moduleID=config('app_id');
		if(!isset($moduleID[strtolower($module)])){
			$this->errCode='00010003';
			return false;
		}
		$moduleID=$moduleID[strtolower($module)];
		$data=[
			'module_id'=>$moduleID,
			'less'=>$less,
			'by_id'=>$byid,
			'content'=>$content,
		];
		//$id=fileContent::create($data);
		$id=1;
		$back=$this->call_job('downloadImg',[$id]);
		//if(!$back)return false;
		//$this->downloadImg(76);
		print_r($data);
		exit();
		return $data;
	}
	public function downloadImg($id){
		//$isJob=config('queue_switch');
		
		$data=fileContent::find($id);
		$content=$data['content'];
		$tmp=get_html_img($content,true);
		$static_url=config('static_url');
		$xyArr=['http','https'];
		$imgArr=[];
		foreach($tmp[0] as $v){
			$continue=false;
			foreach($xyArr as $vv){
				if($continue)break;
				if(0!=stripos($v,$vv))$continue=true;
			}
			if($continue)continue;
			if(0===stripos($v,$static_url))continue;
			$imgArr[]=$v;
		}
		$imgArr=array_unique($imgArr);
		$event=controller($this->module.'/Common', 'event');
		//$imgArr=[0=>$imgArr[0]];
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
		return true;
		
		
		
		
		/*
		if($back){
			print_r($back);
		}
		//
		if(!$ext){
			$finfo = finfo_open(FILEINFO_MIME_TYPE);
			$ext= explode('/', finfo_file($finfo,$tmpFile));
			$ext=$ext[1];
		}
		//echo get_url_file($imgArr[0]);
		//echo $tmpFile;
		print_r($ext);
		 * 
		 */
	}
	
}