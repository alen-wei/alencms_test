<?php
namespace base;
trait TraitTpl{
	protected $now;
	protected $cachePrefix=false;
	protected $Tpl;
	protected $page_title;
	protected $skinFile=['css'=>'style.css','js'=>'function.js'];
	
	function __construct(){
		$this->now=get_now_time();
	}
	
	//设置页面信息
	protected function setPageInfo($data=[]){
		if(!is_array($data))$data=[];
		$data['page_title']=$this->page_title?$this->page_title:config('sys_name');
		$data['page_keywords']='';
		$data['page_description']='';
		return $data;
	}
	//输出
	protected function echoTemplate($data,$request,$lessDir=''){
		$publicTemplate=config('public_path').'template'.DS;
		$publicTemplate=mb_convert_encoding($publicTemplate,"UTF-8",config('os_code'));
		
		if(!is_array($data))$data=[];
		$data['public_template']=[
			'frame'=>$publicTemplate.'frame.html',
			'head'=>$publicTemplate.'head.html',
			'before'=>$publicTemplate.'before.html',
			'after'=>$publicTemplate.'after.html',
			'loadfile'=>$publicTemplate.'loadfile.html',
			
			'skinfile'=>$this->skinFile,
		];
		$data['static_url']=config('static_url');
		$data['skin_url']=config('static_url').config('skin_dirname').'/'.strtolower($this->module).'/'.config('app_theme').'/'.config('default_lang').'/';
		$data=$this->setPageInfo($data);
		$ds=config('template.view_depr');
		$path=config('app_theme').$ds.config('default_lang').$ds;
		if($lessDir)$path.=$lessDir.$ds;
		$data['public_template']['tpl_dir']=$path;
		$tplFile=$path;
		$tplFile.=$this->Tpl?$this->Tpl:$request->action();
		if($this->cachePrefix===false){
			return view($tplFile,$data);
		}else{
			$viewOP=$this->cachePrefix===true?['tpl_cache'=>false]:['cache_prefix'=>md5($this->cachePrefix).'_'];
			$filePath=APP_PATH.$request->module().DS.'view'.DS.$tplFile.'.'.config('template.view_suffix');
			$view = new \think\View($viewOP);
			return $view->fetch($filePath,$data);
			//$content=file_get_contents($filePath);
			//return $view->display($content,$data);
		}
	}
}
?>