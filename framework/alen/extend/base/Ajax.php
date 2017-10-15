<?php
namespace base;
use think\Controller;
class Ajax extends Controller{
	use TraitAjax;
	//代理函数
	public function _empty(){
		$verify=$this->verify();
		if($verify===true){
			$request=request();
			$act='_'.$request->action();
			if(method_exists($this,$act)){
				$data=$this->$act($request);
				if($data){
					if(isset($data['_err'])){
						$this->setErr($data['_err'],isset($data['_txt'])?$data['_txt']:0,isset($data['_url'])?$data['_url']:'');
					}else{
						$this->back=array(
							'status'=>1,
							'info'=>$this->now,
						);
						if(isset($data['_url'])){
							$this->back['url']=$data['_url'];
							unset($data['_url']);
						}
						$this->back['data']=$data;
					}
				}else{
					$this->setErr('00010002');
				}
			}else{
				$this->setErr('00020003');
			}
		}else{
			$this->setErr($verify?$verify:'00020001');
		}
		return $this->echoBack($request);
	}
	
}
?>