<?php
namespace app\File\controller;
use think\Validate;
use base\Ajax;
class Upload extends Ajax{
	protected $module='File';
	//主函数
	protected function upFun($name,$validate){
		$request=request();
		$md5=$request->param('md5');
		
		if(isset($validate['ext'])){
			if(!$validate['ext'] or $validate['ext']=='*')unset($validate['ext']);
		}
		if($md5)return $this->_postMD5($request,isset($validate['ext'])?implode(',',$validate['ext']):'');
		$name=$name?$name:'files';
		$event=controller('Common', 'event');
		$back=$event->uploadFile($validate,$name);
		return $back?$back:$event->errCode;
	}
	//编辑器上传
	protected function _postEdit($request){
		$validate=[
			'size'=>get_file_size('5M'),
			'ext'=>\app\File\event\Common::$typeExt['img'],
		];
		$name=$request->param('editor');
		$name=$name?$name:'upload';
		$back=$this->upFun($name,$validate);
		if(!$request->param('md5')){
			if(is_numeric($back)){
				$err=set_err_back($back,$this->module);
			}else{
				foreach($back as $k=>$v){
					if(!isset($v['err'])){
						$path=config('static_path').str_replace('/',DS,$v['url']);
						$event=controller('Common', 'event'); 
						$thumb=$event->createThumb($path);
						$back[$k]['thumb']=$thumb;
					}
				}
			}	
		}
		if(!isset($err)){
			foreach($back as $v){
				$msg=$v['path'].$v['url'];
				break;
			}
		}
		$josnArr=[
			'err'=>isset($err)?$err['_txt']:'',
			'msg'=>isset($msg)?$msg:'',
		];
		$html='';
		$CKEditorFuncNum=$request->param('CKEditorFuncNum');
		if($CKEditorFuncNum!=''){
			$html='<script>';
			if($josnArr['err']){
				$html.='alert("'.$err['_txt'].'");window.parent.CKEDITOR.tools.callFunction('.$CKEditorFuncNum.',"","")';
			}else{
				$html.='window.parent.CKEDITOR.tools.callFunction('.$CKEditorFuncNum.',"'.$josnArr['msg'].'","")';
			}
			$html.='</script>';
		}else{
			$html=json_encode($josnArr);
		}
		echo $html;
		exit();
	}
	//上传图片
	protected function _postImg($request){
		$validate=[
			'size'=>get_file_size('5M'),
			'ext'=>\app\File\event\Common::$typeExt['img'],
		];
		$back=$this->upFun($request->param('name'),$validate);
		if(!$request->param('md5')){
			if(is_numeric($back))return set_err_back($back,$this->module);
			foreach($back as $k=>$v){
				if(!isset($v['err'])){
					$path=config('static_path').str_replace('/',DS,$v['url']);
					$event=controller('Common', 'event'); 
					$thumb=$event->createThumb($path);
					$back[$k]['thumb']=$thumb;
				}
			}
		}
		return $back;
	}
	//上传视频
	protected function _postVideo($request){
		$validate=[
			'size'=>get_file_size('200M'),
			'ext'=>\app\File\event\Common::$typeExt['video'],
		];
		$back=$this->upFun($request->param('name'),$validate);
		if(is_numeric($back))return set_err_back($back,$this->module);
		return $back;
	}
	//上传文件
	protected function _postFile($request){
		$noExt=['code','img','video'];
		$tmpExt=[];
		foreach (\app\File\event\Common::$typeExt as $k=>$v){
			if(in_array($k,$noExt))continue;
			$tmpExt=array_merge($tmpExt,$v);
		}
		$validate=[
			'size'=>get_file_size('100M'),
			'ext'=>$tmpExt,
		];
		$back=$this->upFun($request->param('name'),$validate);
		if(is_numeric($back))return set_err_back($back,$this->module);
		return $back;
	}
	//上传
	protected function _postExplorer($request){
		$validate=[
			'size'=>get_file_size('500M'),
		];
		$back=$this->upFun($request->param('name'),$validate);
		if(is_numeric($back))return set_err_back($back,$this->module);
		return $back;
	}
	
	//md5上传
	protected function _postMD5($request,$exts=''){
		$md5=$request->param('md5');
		$ext=$request->param('ext');
		$ext=$ext?$ext:$exts;
		if(!$ext)return set_err_back('0002',$this->module);
		$event=controller('Common', 'event');
		$back=$event->getDbData(['md5'=>$md5]);
		if($back){
			$extArr=(!$ext or $ext=='*')?[strtolower($back->ext)]:explode(',',strtolower($ext));
			if(in_array(strtolower($back->ext),$extArr)){
				$name='md5';
				$data=[
					'url'=>$back->path,
					'path'=>config('static_url'),
					'id'=>$back->id,
				];
				if(in_array(strtolower($back->ext),['jpg','jpeg','gif','png','bmp']))$data['thumb']=$event->createThumb(config('static_path').str_replace('/',DS,$back->path));
				return $data;
			}else{
				return set_err_back('0004',$this->module);
			}
		}else{
			return set_err_back('0003',$this->module);
		}
	}
	/*
	//下载远程文件
	private function httpCopy($url, $file="", $timeout=60) {
		$file = empty($file) ? pathinfo($url,PATHINFO_BASENAME) : $file;
		$dir = pathinfo($file,PATHINFO_DIRNAME);
		!is_dir($dir) && @mkdir($dir,0755,true);
		$url = str_replace(" ","%20",$url);
		
		if(function_exists('curl_init')) {
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
			$temp = curl_exec($ch);
			if(@file_put_contents($file, $temp) && !curl_error($ch)) {
				return $file;
			} else {
				return false;
			}
		} else {
			$opts = array(
				"http"=>array(
					"method"=>"GET",
					"header"=>"",
					"timeout"=>$timeout,
				),
			);
			$context = stream_context_create($opts);
			if(@copy($url, $file, $context)) {
			//$http_response_header
				return $file;
			} else {
				return false;
			}
		}
	}
	//下载远程图片
	public function _downImg(){
		$exts=array('jpg','jpeg','gif','png','bmp');
		$source=$this->_postData['url'];
		$this->_SS=0;
		$name=uniqid();
		$tmpName=TEMP_PATH.$name;
		$tmpName=$this->httpCopy($source,$tmpName);
		$finfo = finfo_open(FILEINFO_MIME);
		$mimetype = finfo_file($finfo, $tmpName);
		$mimetype=explode(';',$mimetype);
		$mimetype=$mimetype[0];
		$fileext=strtolower(get_mime_type($mimetype,false));
		if(!in_array($fileext,$exts)){
			@unlink($tmpName); 
			$this->_Info='远程文件不是图片';
		}
		$this->thumb($tmpName,false,1920,$tmpName);
		$size=filesize($tmpName);
		if($size>get_file_size('3M')){
			$this->_Info='远程文件超过3M';
			return;
		}
		$md5=md5_file($tmpName);
		$this->postMD5($md5,$exts);
		if($this->_SS){ //秒传
			$this->_Data[0]['source']=$source;
			@unlink($tmpName);
			return true;
		}
		$dir='UploadFiles/'.date('Ymd').'/';
		add_file_dir(FILESDIR.$dir);
		$url=$dir.$name.'.'.$fileext;
		rename($tmpName,FILESDIR.$url);
		$tmpdata=array(
			'filepath'=>$url,
			'ext'=>$fileext,
			'type'=>$mimetype,
			'size'=>$size,
			'md5'=>$md5,
		);
		$back=$this->setDbData($tmpdata);
		$file=array(
			'url'=>$url,
			'path'=>FILESURL,
			'id'=>$back,
			'source'=>$source,
		);
		$file['thumb']=$this->thumb($file['url'],true);
		$this->_SS=1;
		$this->_Info='下载成功';
		$this->_Data=array(0=>$file);
	}
	public function _getWxImg(){
		$this->_SS=0;
		$op=$op?$op:$this->_postData;
		$mid=$op['mid'];
		$type=$op['type']?$op['type']:'base64';
		$verify=$op['verify']?true:false;
		if($verify){
			$dir='UploadFiles/temp/Weixin_';
			$dir=FILESDIR.$dir;
			$tmpName='tmpMedia_'.md5($mid);
			if(file_exists($dir.$tmpName)){
				$file=$dir.$tmpName;
			}else{
				$this->_SS=0;
				$this->_Info='缓存中找不到此文件:';
				$this->_Data=array('mid'=>$mid,'file'=>$tmpName);
				return false;
			}
		}
		if(!IS_POST and $_GET['mid']){
			$mid=$_GET['mid'];
			$type='img';
		}
		if(!$file){
			$wxApi = A('General/Weixin');
			$file=$wxApi->call('getTmpMedia',array('mid'=>$mid));
			if(!intval($file['status'])){
				$this->_Info=$file['info'];
				return false;
			}
			$file=FILESDIR.$file['data'];
		}
		$finfo = finfo_open(FILEINFO_MIME);
		$mimetype = finfo_file($finfo, $file);
		$mimetype=explode(';',$mimetype);
		$mimetype=$mimetype[0];
		$exts=array('jpg','jpeg','gif','png','bmp');
		$fileext=strtolower(get_mime_type($mimetype,false));
		if(in_array($fileext,$exts)){
			if($verify){
				$md5=md5_file($file);
				$this->postMD5($md5,$exts);
				if($this->_SS or $this->_Data==1){
					unlink($file);
					return false;
				}
				$this->_SS=0;
				$this->_Info='';
				$newname=uniqid();
				$newname.='.'.$fileext;
				$zdir='UploadFiles/'.date('Ymd').'/';
				$dir=FILESDIR.$zdir;
				add_file_dir($dir);
				rename($file,$dir.$newname);
				//copy($file,$dir.$newname);
				$this->_SS=1;
				$this->_Info='获取文件成功';
				$tmpdata=array(
					'filepath'=>$zdir.$newname,
					'ext'=>$fileext,
					'type'=>$mimetype,
					'size'=>filesize($dir.$newname),
					'md5'=>$md5,
				);
				$back=$this->setDbData($tmpdata);
				
				$backdata=array(
					'url'=>$zdir.$newname,
					'path'=>FILESURL,
					'id'=>$back,
				);
				$backdata['thumb']=$this->thumb($backdata['url'],true);
				$this->_Data=array(0=>$backdata);
			}else{
				$img=file_get_contents($file);
				if($type=='base64'){
					$this->_SS=1;
					$this->_Data= 'data:image/'.$fileext.';base64,'.base64_encode($img);
					return;
				}elseif($type=='img'){
					header( 'Content-Type: image/'.$fileext );
					echo $img;
					exit();
				}
				$this->_Info='type参数出错';
			}
		}else{
			$this->_Info='这不是一个图片文件';
		}
	}
	*/
}