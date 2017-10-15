<?php
namespace app\File\event;

use app\File\model\fileMain;
use app\File\model\fileApply;

class Common {
	protected $module='file';
	public $errCode='';
	static $upPath='_upload';
	static $thumbPath='_thumb';
	static $typeExt=[
		'img'=>['jpg','jpeg','gif','png','bmp'],
		'video'=>['mp4','avi','mov','3gp'],
		'compressed'=>['zip','rar','7z','gz'],
		'code'=>['php','js','html','htm','css','nc'],
		'txt'=>['txt','text','log'],
		'file'=>['apk','pdf','doc','docx','ppt','pptx','xls','xlsx'],
	];
	
	//文件夹
	public function saveDir(){
		return get_now_time('Ymd');
	}
	//上传文件
	public function uploadFile($validate=false,$field='file'){
		$files = request()->file($field);
		if(!$files){
			$this->errCode='0001';
			return false;
		}
		if(!is_array($files))$files=[$files];
		if($validate===false){
			$validate=[];
		}else{
			if(!is_array($validate)){
				$this->errCode='0002';
				return false;
			}
		}
		if(!isset($validate['size']))$validate['size']=get_file_size('100M');
		//if(!isset($validate['ext']))$validate['ext']=['rar','zip','7z','txt','apk','pdf','doc','ppt','xls','docx','pptx','xlsx'];
		$savePath=self::$upPath;
		$dir=$this->saveDir();
		$path=config('static_path').$savePath.DS.$dir;
		$status=0;
		$data=[];
		foreach($files as $v){
			$id=0;
			$md5=$v->md5();
			$back=$this->getDbData(['md5'=>$md5]);
			if($back){
				$status=1;
				$id=$back->id;
				$filePath=$back->path;
			}else{
				$info=$v->rule('get_uid')->validate($validate)->move($path);
				if($info){
					$status=1;
					$filePath=$savePath.'/'.$dir.'/'.$info->getFilename();
					$dbData=[
						'path'=>$filePath,
						'ext'=>strtoupper($info->getExtension()),
						//'type'=>$info->getInfo('type'),
						'type'=>$info->getMime(),
						'size'=>$info->getInfo('size'),
						'md5'=>$md5,
					];
					$id=$this->setDbData($dbData);
				}
			}
			if($id){
				$data[$v->getInfo('name')]=[
					'url'=>$filePath,
					'path'=>config('static_url'),
					'id'=>$id,
				];
			}else{
				$data[$v->getInfo('name')]=[
					'err'=>$v->getError(),
				];
			}
		}
		return $data;
	}
	//获取本地文件
	public function localFile($oldFile,$ext){
		$filePath=config('static_path');
		$dir= self::$upPath.DS.$this->saveDir().DS;
		add_file_dir($filePath.$dir);
		$newName=get_uid().'.'.$ext;
		$dir.=$newName;
		rename($oldFile,$filePath.$dir);
		$md5=md5_file($filePath.$dir);
		$back=$this->getDbData(['md5'=>$md5]);
		if($back){
			unlink($filePath.$dir);
			return [
				$newName=>[
					'url'=>$back['path'],
					'path'=>config('static_url'),
					'id'=>$back['id'],
				]
			];
		}else{
			$finfo = finfo_open(FILEINFO_MIME_TYPE);
			$mime_type = finfo_file($finfo, $filePath.$dir);
			$dbData=[
				'path'=>str_replace(DS,'/',$dir),
				'ext'=>strtoupper($ext),
				'type'=>$mime_type,
				'size'=>filesize($filePath.$dir),
				'md5'=>$md5,
			];
			$id=$this->setDbData($dbData);
			return [
				$newName=>[
					'url'=>$dbData['path'],
					'path'=>config('static_url'),
					'id'=>$id,
				]
			];
		}
	}
	//写入数据库
	public function setDbData($data,$userID=false){
		if($userID===false){
			$event=controller('user/Common', 'event');
			$type=$event->verifyLogin();
			$data['user_id']=$type?$type['id']:0;
		}
		$back = fileMain::create($data);
		return intval($back->id);
	}
	
	//检证文件上传
	public function getDbData($wh){
		if($wh){
			if(!is_array($wh))$wh=['id'=>['=',$wh]];
		}else{
			return false;
		}
		$data=fileMain::where($wh)->find();
		if($data){
			$file=config('static_path').str_replace('/',DS,$data->path);
			if(file_exists($file)){
				return $data;
			}else{
				fileMain::destroy($data->id);
				return false;
			}
		}else{
			return false;
		}
	}
	//thumb
	public function createThumb($path){
		//$path=config('static_path').str_replace('/',DS,$v['url']);
		$name=pathinfo($path);
		$ext=$name['extension'];
		$dir=explode(DS,str_replace(config('static_path'),'',$name['dirname']));
		$dir_l= self::$thumbPath;
		$dir[0]='';
		$dir=implode(DS,$dir).DS;
		$name=$name['filename'];
		$thumb_wh=config('thumb_wh');
		$thumb=[];
		foreach($thumb_wh as $kk=>$vv){
			$thumbPath=config('static_path').$dir_l.DS.$kk.$dir;
			$thumbPath_file=$thumbPath.$name.'.'.$ext;
			if(!file_exists($thumbPath_file)){
				$vv=explode(',',$vv);
				$image = \think\Image::open($path);
				add_file_dir($thumbPath);
				$image->thumb($vv[0],$vv[1])->save($thumbPath_file);
			}
			$thumb[$kk]=str_replace(DS,'/',$dir_l.DS.$kk.$dir.$name.'.'.$ext);
		}
		return $thumb;
	}
	//
	public function delFile($ids,$dFile=false){
		$data=$this->getFile($ids);
		if(!$data){
			$this->errCode='0003';
			return false;
		}
		if($data->apply>0){
			$this->errCode='0009';
			return false;
		}
		$path=$data->path;
		$back=$data->delete();
		if(!$back){
			$this->errCode='00010002';
			return false;
		}
		if($dFile){
			$filePath=config('static_path').str_replace('/',DS,$path);
			if(!is_file($filePath)){ 
				$this->errCode='0003';
				return false;
			}
			if(!unlink($filePath)){
				$this->errCode='00010002';
				return false;
			}
		}
		return true;
	}
	//应用文件
	public function applyFile($file,$id,$module,$less=''){
		$lazyTime=5;  //延时更新时间
		$moduleID=config('app_id');
		$module=strtolower($module);
		if(!isset($moduleID[$module]))return false;
		$moduleID=$moduleID[$module];
		if($file){
			$fileArr=str_to_arr($file);
			$mainObj=fileMain::where(['path'=>['in',$fileArr]])->select();
			if(!$mainObj)return false;
		}else{
			$mainObj=null;
		}
		$wh=[
			'module_id'=>$moduleID,
			'by_id'=>$id,
			'less'=>$less,
		];
		$applyObj=fileApply::where($wh)->select();
		$minusID=[];
		if($mainObj){
			foreach($applyObj as $k=>$v){
				if(!isset($mainObj[$k]))break;
				if($mainObj[$k]->id==$v->file_id)continue;
				$minusID[]=$v->file_id;
				$v->file_id=$mainObj[$k]->id;
				$v->save();
				unset($applyObj[$k]);
				//$mainObj[$k]->apply++;
				//$mainObj[$k]->save();
				$mainObj[$k]->setInc('apply',1,$lazyTime);
				unset($mainObj[$k]);
			}
		}
		if($applyObj and count($applyObj)>0){
			foreach($applyObj as $k=>$v){
				$v->delete();
			}
		}
		if($mainObj and count($mainObj)>0){
			$tmpData=[];
			foreach($mainObj as $k=>$v){
				$tmpData[]=[
					'file_id'=>$v->id,
					'module_id'=>$moduleID,
					'by_id'=>$id,
					'less'=>$less,
				];
				$v->apply++;
				$v->save();
			}
			$tmpObj=new fileApply;
			$tmpObj->saveAll($tmpData);
		}
		if(count($minusID)>0){
			fileMain::where(['id'=>['in',$minusID]])->setDec('apply',1,$lazyTime);
		}
		return true;
	}
	public function getFile($wh=null,$isID=true,$status=1){
		$where=[];
		if($isID){
			$where['id']=$wh;
		}else{
			$where=$wh;
		}
		if($status)$where['status']=$status;
		$data=fileMain::field(true)->where($where)->find();
		return $data;
	}
	//文件列表
	public function getLists($wh=null,$tmp_field=true,$order=null,$page=null,$del=false){
		$field=$tmp_field?$tmp_field:true;
		$db=$del?fileMain::withTrashed()->field($field):fileMain::field($field);
		if($wh){
			$where=$wh;
			$db=$db->where($where);
		}
        if($page){
            if($page===true){
                return $db->count();
            }else{
				if($order){
					$order=order_to_arr($order);
					$db=$db->order($order);
				}
                $db=$db->page($page);
            }
        }
        $data=$db->select();
		return $data;
	}
	//下载文件
	public function downloadFile($wh=null,$isID=true,$status=1){
		$oneLoad=1024;  //每次读取文件大小
		
		if(!is_numeric($wh) and $isID==true){
			$path=$wh;
		}else{
			$data=$this->getFile($wh,$isID,$status);
			if(!$data){
				$this->errCode='0003';
				return false;
			}
			$path=$data->path;
		}
		$filePath=config('static_path').str_replace('/',DS,$path);
		if (!is_file($filePath)){ 
			$this->errCode='0003';
			return false;
		}
		
		set_time_limit(0);
		$size2=$data->size-1;
		
		header("Content-type: ".$data->type);
		header("Accept-Ranges: bytes");
		header("Accept-Length: ".$data->size);
		header("Content-Disposition: attachment; filename=".$data->md5.".".$data->ext);
		header("Content-Range: bytes 0-".$size2."/".$data->size);
		
		$file = fopen($filePath,"r");
		$buffer = $data->size>=$oneLoad?$oneLoad:$data->size;
		$file_count = 0;
		while(!feof($file) && $file_count < $data->size){
			$file_con = fread($file,$buffer);
			$file_count += $buffer;
			echo $file_con;
			ob_flush();  //输出缓冲  
			flush();
		}
		fclose($file);
		exit();
	}
}