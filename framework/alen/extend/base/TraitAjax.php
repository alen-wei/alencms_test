<?php
namespace base;
trait TraitAjax{
	protected $now;
	protected $back=[];
	protected $echoHtml=false;
	
	function __construct(){
		$this->now=get_now_time();
	}
	//验证
	protected function verify(){
		return true;
	}
	//输出
	protected function echoBack($request){
		if(!$this->echoHtml)return json($this->back);
		if($request->isAjax()){
			return json($this->back);
		}else{
			$url=isset($this->back['url'])?$this->back['url']:$request->server('HTTP_REFERER');
			$url=$url?$url:null;
			if(is_array($this->back['data'])){
				if(isset($this->back['data']['_txt'])){
					$txt=$this->back['data']['_txt'];
				}else{
					$txt=array_shift($this->back['data']);
				}
				if(is_array($txt))$txt=implode('',$txt);
			}else{
				$txt=$this->back['data'];
			}
			if($this->back['status']){
				$this->success($txt,$url,$this->back['info']);
			}else{
				$this->error($txt,$url,$this->back['info']);
			}
		}
	}
	//设置错误
	protected function setErr($code,$txt=0,$url=''){
		$this->back=array(
			'status'=>0,
			'info'=>$code,
			'data'=>$txt?$txt:get_err_data($code),
		);
		if($url)$this->back['url']=$url;
	}
}
?>