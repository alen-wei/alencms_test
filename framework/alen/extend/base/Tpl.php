<?php
namespace base;
use think\Controller;
class Tpl extends Controller{
	use TraitTpl;
	//代理函数
	public function _empty(){
		$request=request();
		$act='_'.$request->action();
		if(method_exists($this,$act)){
			$data=$this->$act($request);
			if($data){
				if(isset($data['_err'])){
					$this->error(isset($data['_txt'])?$data['_txt']:get_err_data($data['_err']),isset($data['_url'])?$data['_url']:null,$data['_err']);
				}else{
					return $this->echoTemplate($data,$request);
				}
			}else{
				abort(500,get_err_data('00010002'));
			}
		}else{
			abort(404,get_err_data('00010004'));
		}
	}
	
}
?>