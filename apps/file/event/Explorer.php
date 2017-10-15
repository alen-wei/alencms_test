<?php
namespace app\File\event;

use app\File\model\fileDir;
use app\File\model\fileItem;
use app\File\model\fileMain;

class Explorer{
	protected $module='file';
	public $errCode='';
	//验证文件/文件夹名称
	public function verifyName($name,$fid=0){
		$str='\\/|,*?';
		$str=str_split($str);
		foreach($str as $v){
			if(stripos($name,$v)!==false){
				$this->errCode='0007';
				return 0;
			}
		}
		$back=fileDir::get(['fid'=>$fid,'name'=>$name,]);
		if($back){
			$this->errCode='0005';
			return 0;
		}
		$back=fileItem::get(['dir_id'=>$fid,'name'=>$name,]);
		if($back){
			$this->errCode='0005';
			return 0;
		}
		return true;
	}
	//新建文件夹
	public function addDir($name,$fid=0,$userID=false){
		if($userID===false)$userID=0;
		if($fid){
			if('\\'==substr($fid,0,1)){
				if('\\'==$fid){
					$fid=0;
				}else{
					$fid=$this->getInfo($fid);
					if(!$fid)return 0;
					if($fid['type']!='dir'){
						$this->errCode='0008';
						return 0;
					}
					$fid=$fid['id'];
				}
			}
		}
		if(!$this->verifyName($name,$fid))return 0;
		$data=[
			'name'=>$name,
			'fid'=>$fid,
			'user_id'=>$userID,
		];
		$back=fileDir::create($data);
		if(!$back){
			$this->errCode='00010002';
			return 0;
		}
		return $back->id;
	}
	//添加文件
	public function addFile($data,$path=0,$userID=false){
		if($userID===false)$userID=0;
		if('\\'==substr($path,0,1)){
			if('\\'==$path){
				$fid=0;
			}else{
				$fid=$this->getInfo($path);
				if(!$fid)return 0;
				if($fid['type']!='dir'){
					$this->errCode='0008';
					return 0;
				}
				$fid=$fid['id'];
			}
		}else{
			$fid=0;
		}
		$ids=[];
		foreach($data as $v){$ids[]=$v['id'];}
		$back=fileMain::where(['id'=>['in',$ids]])->column('ext','id');
		$DBdata=[];
		foreach($data as $v){
			$tmp=[
				'dir_id'=>$fid,
				'user_id'=>$userID,
				'file_id'=>$v['id'],
				'less'=>$back[$v['id']]
			];
			
			if($this->verifyName($v['name'],$fid)){
				$name=$v['name'];
			}else{
				$i=0;
				do{
					$i++;
					if(strstr($v['name'],'.')===false){
						$ext='';
						$str=$v['name'];
					}else{
						$pathinfo=explode('.',$v['name']);
						$ext=array_pop($pathinfo);
						$str=implode('.',$pathinfo);
					}
					$name=$str.'_副本'.($i>1?'('.$i.')':'').'.'.$ext;
				}while(!$this->verifyName($name,$fid));
			}
			$tmp['name']=$name;
			$DBdata[]=$tmp;
		}
		$db = new fileItem;
		$back=$db->saveAll($DBdata);
		if($back){
			foreach($back as $k=>$v){
				$back[$k]=$v->toarray();
			}
		}else{
			$this->errCode='00010002';
			return 0;
		}
		return $back;
	}
	
	//获取信息
	public function getInfo($path,$type=''){
		if('\\'==substr($path,0,1)){
			$type='';
			$path=substr($path,1);
			$pathStr='\\';
			if(!$path){
				$this->errCode='0008';
				return 0;
			}
			$path=explode('\\',$path);
			$last=array_pop($path);
			if(!$last){
				$last=array_pop($path);
				$type='dir';
			}
			$fid=0;
			foreach($path as $v){
				if(!$v)continue;
				$fid=fileDir::where(['fid'=>$fid,'name'=>$v])->value('id');
				if(!$fid){
					$this->errCode='0008';
					return 0;
				}
				$pathStr.=$v.'\\';
			}
			$wh=['name'=>$last];
		}else{
			$id=intval($path);
			if(!$id or !$type){
				$this->errCode='00010003';
				return 0;
			}
			$wh=['id'=>$id];
		}
		if($type){
			if($type=='dir'){
				if(isset($fid))$wh['fid']=$fid;
				$info=fileDir::get($wh);
			}elseif($type=='file'){
				if(isset($fid))$wh['dir_id']=$fid;
				$info=fileItem::get($wh);
			}
		}else{
			$tmp=$wh;
			if(isset($fid))$tmp['fid']=$fid;
			$info=fileDir::get($tmp);
			if(!$info){
				$tmp=$wh;
				if(isset($fid))$tmp['dir_id']=$fid;
				$info=fileItem::get($tmp);
			}
		}
		if(!$info){
			$this->errCode='0008';
			return 0;
		}
		$info=$info->toarray();
		if(!isset($pathStr)){
			if(isset($info['fid'])){
				$tmpID=$info['fid'];
			}else{
				$tmpID=$info['dir_id'];
				$tmp=fileMain::field(['size','type'])->where('id',$info['file_id'])->find();
				$info=array_merge($info,$tmp->toarray());
			}
			$tmpID=intval($tmpID);
			$pathStr='\\';
			while($tmpID){
				$tmpID=fileDir::get($tmpID);
				if($tmpID){
					$pathStr='\\'.$tmpID['name'].$pathStr;
					$tmpID=intval($tmpID['fid']);
				}else{
					$this->errCode='0008';
					return 0;
				}
			}
		}
		$pathStr.=$info['name'];
		$pathStr.=isset($info['fid'])?'\\':'';
		$info['path']=$pathStr;
		$info['type']=isset($info['fid'])?'dir':'file';
		return $info;
	}
	//获取列表
	public function getList($path=0){
		if('\\'==substr($path,0,1)){
			$path=substr($path,1);
			if($path){
				$path=explode('\\',$path);
				$fid=0;
				foreach($path as $v){
					if(!$v)continue;
					$fid=fileDir::where(['fid'=>$fid,'name'=>$v])->value('id');
					if(!$fid){
						$this->errCode='0008';
						return 0;
					}
				}
				$id=$fid;
			}else{
				$id=0;
			}
		}else{
			$id=$path?intval($path):0;
		}
		$dirs=fileDir::field('id,name,create_time,update_time')->order('update_time DESC')->where(['fid'=>$id])->select();
		$files=fileItem::field('id,name,less,create_time,update_time')->order('update_time DESC')->where(['dir_id'=>$id])->select();
		$list=[];
		foreach($dirs as $v){
			$v=$v->toarray();
			$v['type']='dir';
			$list[]=$v;
		}
		foreach($files as $v){
			$v=$v->toarray();
			$v['type']='file';
			$list[]=$v;
		}
		return $list;
	}
	//删除
	public function del($ids,$userID=false){
		if($userID===false)$userID=0;
		if(!is_array($ids))$ids=explode(',',$ids);
		$dir_ids=[];
		$file_ids=[];
		foreach($ids as $k=>$v){
			if(strstr($v,'|')===false){
				$tmp=$this->getInfo($v);
				$tmp=[$tmp['id'],$tmp['type']];
			}else{
				$tmp=explode('|',$v);
			}
			if($tmp[1]=='dir'){
				$dir_ids[]=intval($tmp[0]);
			}elseif($tmp[1]=='file'){
				$file_ids[]=intval($tmp[0]);
			}
		}
		if(count($dir_ids)>0)fileDir::destroy($dir_ids);
		if(count($file_ids)>0)fileItem::destroy($file_ids);
		return true;
	}
	//重命名
	public function rename($id,$type,$name,$userID=false){
		if($userID===false)$userID=0;
		if($type=='dir'){
			$fid=fileDir::where('id',$id)->value('fid');
		}elseif($type=='file'){
			$fid=fileItem::where('id',$id)->value('dir_id');
		}
		if($this->verifyName($name,$fid)){
			if($type=='dir'){
				fileDir::where('id',$id)->update(['name'=>$name]);
			}elseif($type=='file'){
				fileItem::where('id',$id)->update(['name'=>$name]);
			}
			return $name;
		}else{
			$this->errCode='0005';
			return 0;
		}
	}
	
	//移动
	public function move($ids,$path,$userID=false){
		if($userID===false)$userID=0;
		if(!is_array($ids))$ids=explode(',',$ids);
		$dir_ids=[];
		$file_ids=[];
		foreach($ids as $k=>$v){
			if(strstr($v,'|')===false){
				$tmp=$this->getInfo($v);
				$tmp=[$tmp['id'],$tmp['type']];
			}else{
				$tmp=explode('|',$v);
			}
			if($tmp[1]=='dir'){
				$dir_ids[]=intval($tmp[0]);
			}elseif($tmp[1]=='file'){
				$file_ids[]=intval($tmp[0]);
			}
		}
		
		if('\\'==substr($path,0,1)){
			if('\\'==$path){
				$fid=0;
			}else{
				$fid=$this->getInfo($path);
				if(!$fid){
					$this->errCode='0008';
					return 0;
				}
				$fid=$fid['id'];
			}
		}else{
			$fid=intval($path);
		}
		$data=[];
		foreach($file_ids as $v){
			$tmp=fileItem::get($v)->toarray();
			unset($tmp['create_time']);
			unset($tmp['delete_time']);
			unset($tmp['update_time']);
			$name=$tmp['name'];
			$i=0;
			$ifc=$tmp['dir_id']==$fid?false:$this->verifyName($name,$fid);
			while(!$ifc){
				$i++;
				if(strstr($tmp['name'],'.')===false){
					$ext='';
					$str=$tmp['name'];
				}else{
					$pathinfo=explode('.',$tmp['name']);
					$ext=array_pop($pathinfo);
					$str=implode('.',$pathinfo);
				}
				$name=$str.'_副本'.($i>1?'('.$i.')':'');
				if($ext)$name.='.'.$ext;
				$ifc=$this->verifyName($name,$fid);
			}
			$tmp['name']=$name;
			$tmp['dir_id']=$fid;
			$data[]=$tmp;
		}
		$db = new fileItem;
		$db->saveAll($data);
		$dir_data=[];
		foreach($dir_ids as $v){
			$tmp=fileDir::get($v)->toarray();
			unset($tmp['create_time']);
			unset($tmp['delete_time']);
			unset($tmp['update_time']);
			$name=$tmp['name'];
			$i=0;
			$ifc=$tmp['fid']==$fid?false:$this->verifyName($name,$fid);
			while(!$ifc){
				$i++;
				if(strstr($tmp['name'],'.')===false){
					$ext='';
					$str=$tmp['name'];
				}else{
					$pathinfo=explode('.',$tmp['name']);
					$ext=array_pop($pathinfo);
					$str=implode('.',$pathinfo);
				}
				$name=$str.'_副本'.($i>1?'('.$i.')':'');
				if($ext)$name.='.'.$ext;
				$ifc=$this->verifyName($name,$fid);
			}
			$tmp['name']=$name;
			$tmp['fid']=$fid;
			$dir_data[]=$tmp;
		}
		$db = new fileDir;
		$db->saveAll($dir_data);
		return true;
	}
	
	//复制
	public function copy($ids,$path,$userID=false){
		if($userID===false)$userID=0;
		if(!is_array($ids))$ids=explode(',',$ids);
		$dir_ids=[];
		$file_ids=[];
		foreach($ids as $k=>$v){
			if(strstr($v,'|')===false){
				$tmp=$this->getInfo($v);
				$tmp=[$tmp['id'],$tmp['type']];
			}else{
				$tmp=explode('|',$v);
			}
			if($tmp[1]=='dir'){
				$dir_ids[]=intval($tmp[0]);
			}elseif($tmp[1]=='file'){
				$file_ids[]=intval($tmp[0]);
			}
		}
		
		if('\\'==substr($path,0,1)){
			if('\\'==$path){
				$fid=0;
			}else{
				$fid=$this->getInfo($path);
				if(!$fid){
					$this->errCode='0008';
					return 0;
				}
				$fid=$fid['id'];
			}
		}else{
			$fid=intval($path);
		}
		$data=[];
		foreach($file_ids as $v){
			$tmp=fileItem::get($v)->toarray();
			unset($tmp['id']);
			unset($tmp['create_time']);
			unset($tmp['delete_time']);
			unset($tmp['update_time']);
			$name=$tmp['name'];
			$i=0;
			$ifc=$tmp['dir_id']==$fid?false:$this->verifyName($name,$fid);
			while(!$ifc){
				$i++;
				if(strstr($tmp['name'],'.')===false){
					$ext='';
					$str=$tmp['name'];
				}else{
					$pathinfo=explode('.',$tmp['name']);
					$ext=array_pop($pathinfo);
					$str=implode('.',$pathinfo);
				}
				$name=$str.'_副本'.($i>1?'('.$i.')':'');
				if($ext)$name.='.'.$ext;
				$ifc=$this->verifyName($name,$fid);
			}
			$tmp['name']=$name;
			$tmp['dir_id']=$fid;
			$data[]=$tmp;
		}
		$db = new fileItem;
		$db->saveAll($data);
		$dir_data=[];
		foreach($dir_ids as $v){
			$tmp=fileDir::get($v)->toarray();
			unset($tmp['id']);
			unset($tmp['create_time']);
			unset($tmp['delete_time']);
			unset($tmp['update_time']);
			$name=$tmp['name'];
			$i=0;
			$ifc=$tmp['fid']==$fid?false:$this->verifyName($name,$fid);
			while(!$ifc){
				$i++;
				if(strstr($tmp['name'],'.')===false){
					$ext='';
					$str=$tmp['name'];
				}else{
					$pathinfo=explode('.',$tmp['name']);
					$ext=array_pop($pathinfo);
					$str=implode('.',$pathinfo);
				}
				$name=$str.'_副本'.($i>1?'('.$i.')':'');
				if($ext)$name.='.'.$ext;
				$ifc=$this->verifyName($name,$fid);
			}
			$tmp['name']=$name;
			$tmp['fid']=$fid;
			$dir_data[]=$tmp;
		}
		$db = new fileDir;
		$db->saveAll($dir_data);
		return true;
	}
	
	//获取文件信息
	public function getFile($id,$getFileID=false){
		$tmp=fileItem::get($id);
		if($getFileID)return $tmp['file_id'];
		$back=fileMain::get($tmp['file_id']);
		$back=$back->toarray();
		$back['name']=$tmp['name'];
		return $back;
	}
	
	//获取文本文件内容
	public function getTxt($id){
		$tmp=fileItem::get($id);
		$back=fileMain::get($tmp['file_id']);
		$back=$back->toarray();
		$back['name']=$tmp['name'];
		$path=config('static_path').str_replace('/',DS,$back['path']);
		$str=file_get_contents($path);
		$data=[
			'id'=>$back['id'],
			'name'=>$back['name'],
			'content'=>$str,
		];
		return $data;
	}
	//修改文本文件内容
	public function saveTxt($id,$content,$userID=false){
		if($userID===false)$userID=0;
		$tmpFile=CACHE_PATH.$this->module.DS;
		add_file_dir($tmpFile);
		$tmpFile.=get_uid();
		$fp = fopen($tmpFile,"w");
		fwrite($fp,$content);
		fclose($fp);
		
		$item=$this->getInfo($id,'file');
		$ext=strtolower($item['less']);
		$event=controller('Common', 'event');
		$back=$event->localFile($tmpFile,$ext);
		$back=array_shift($back);
		if($item['file_id']==$back['id']){
			return $item;
		}else{
			$item['file_id']=$back['id'];
			fileItem::where('id',$id)->update(['file_id'=>$back['id']]);
			return $item;
		}
	}
	
	public function getUrl($id,$userID=false){
		$ids=is_array($id)?$id:strint_to_arrint($id);
		$files=fileItem::where('id','in',$ids)->column('file_id','id');
		$urlArrs=fileMain::where('id','in',$files)->field('id,path,ext')->select();
		$imgExts=['jpg','jpeg','gif','png','bmp'];
		$data=[];
		foreach($urlArrs as $v){
			$tmp=$v['id'];
			unset($v['id']);
			if(in_array(strtolower($v['ext']),$imgExts)){
				$event=controller('Common', 'event'); 
				$thumb=$event->createThumb(config('static_path').str_replace('/',DS,$v['path']));
				$v['thumb']=$thumb;
			}
			$data[$tmp]=$v;
		}
		foreach($files as $k=>$v){
			$files[$k]=$data[$v];
		}
		return $files;
	}
}