<?php
namespace base;
class Admin extends Tpl{
	use TraitAjax,TraitTpl;
	function __construct(){
		$this->now=get_now_time();
		$this->skinFile=['css'=>'admin.css','js'=>'admin.js'];
	}
	//验证操作权限
    protected function verifyCtrl($ctrl=false){
        $module=strtolower(request()->module());
        $module_id=config('app_id.'.$module);
        $ctrl=$ctrl?$ctrl:strtolower(request()->action());
        $ctrl_id=isset($this::$ctrlLists[$ctrl])?$this::$ctrlLists[$ctrl]['id']:0;
        $event=controller('admin/Common', 'event');
        $user=$event->verifyAdmin($ctrl_id,$module_id,false);
        if($user===0){
            $this->error(lang('please login'),'admin/html/login');
        }elseif($user===false){
			$this->error(lang('access restricted'));
			//access restricted
            //$this->redirect('admin/html/errmsg',['type'=>'access_restricted']);
        }
        return $user;
    }
	//通用列表函数
	protected function getDataLists($event,$where=null,$order=null,$field=true,$num=0){
		$lists=$event->getLists();
		$num=$num?$num:config('admin.page')['gs'];
		$p=request()->param('p');
		if(!$p)$p=1;
		$count=$event->getLists($where,true,null,true);
		$MaxP=ceil($count/$num);
		if($p>$MaxP)$p=$MaxP;
		$lists=$event->getLists($where,$field,$order,$p.','.$num);
		$urlParam=get_url_param(null,['p'],true);
		$data=[
			'lists'=>$lists,
			'count'=>$count,
			'urlParam'=>$urlParam,
			'page'=>[
				'num'=>$num,
				'max'=>$MaxP,
				'index'=>$p,
			],
        ];
		return $data;
	}
	
	//代理函数
	public function _empty(){
		$request=request();
        $ctrl=$request->action();
		$act='_'.$ctrl;
		if(method_exists($this,$act)){
            $tmp=true;
            if(isset($this->noVerify)){
                if(in_array($ctrl,$this->noVerify)){
                    $tmp=false;
                }
            }
            if($tmp){
                $user=$this->verifyCtrl($ctrl);
            }else{
                $event=controller('admin/Common', 'event');
                $user=$event->verifyAdmin(0,0,false);
            }
			$data=$this->$act($request,$user);
			if($data){
				$data=(array)$data;
				if($request->isAjax()){
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
					return $this->echoBack($request);
				}else{
					if(isset($data['_err'])){
						$this->error(isset($data['_txt'])?$data['_txt']:get_err_data($data['_err']),isset($data['_url'])?$data['_url']:null,$data['_err']);
					}else{
						
						$data['admin_ctrl']=$ctrl;
						return $this->echoTemplate($data,$request,'admin');
					}
				}
			}else{
				abort(500,get_err_data('00010002'));
			}
		}else{
			abort(404,get_err_data('00010004'));
		}
	}
}