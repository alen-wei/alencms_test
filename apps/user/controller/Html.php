<?php
namespace app\User\controller;
use base\Tpl;
class Html extends Tpl{
	protected $module='User';
	protected $active_id;
	protected $active;
	protected $ctrl;
	protected $user;
	
	#路由处理命令
	protected function _command($request,$active='index',$ctrl=''){
		$this->active=$request->param('__active');
		$this->active=$this->active?$this->active:$active;
		$this->ctrl=$request->param('__ctrl');
		$this->ctrl=$this->ctrl?$this->ctrl:$ctrl;
		if(!$this->active)abort(404,get_err_data('00010004'));
		if($this->active=='login' or $this->active=='signup'){
			$command='_'.$this->active;
			$this->Tpl=$this->active;
			return $this->$command($request);
		}else{
			$event=controller('Common', 'event');
			if(!$event->verifyLogin())$this->redirect('login');
			$this->user=$event->verifyLogin();
			
			$navFile=APP_PATH.$request->module().DS.'data'.DS.'nav.php';
			$navArr=include($navFile);
			foreach($navArr as $k=>$v){
				if($v['name']==$this->active){
					$this->active_id=$k;
					if($this->ctrl){
						if(!isset($v['subnav'][$this->ctrl]))abort(404,get_err_data('00010004'));
					}else{
						reset($v['subnav']);
						$tmp=each($v['subnav']);
						$this->ctrl=$tmp['key'];
					}
					$this->page_title=$v['subnav'][$this->ctrl];
					break;
				}
			}
			if(!$this->active_id)abort(404,get_err_data('00010004'));
			$__tplName=$this->active.'_'.$this->ctrl;
			$command='com_'.$__tplName;
			if(!method_exists($this,$command))abort(404,get_err_data('00010004'));
			$this->Tpl='_main';
			$back=$this->$command($request);
			if(is_array($back)){
				$back['__tplName']=config('app_theme').DS.config('default_lang').DS.$__tplName;
				$back['__active']=$this->active;
				$back['__ctrl']=$this->ctrl;
				$back['__activeID']=$this->active_id;
				$back['user']=$this->user;
				$back['navArr']=$navArr;
			}
			$this->cachePrefix=$this->module.'_'.$__tplName;
			return $back;
		}
	}
	#个人中心
	protected function _index($request){return $this->_command($request);}
	protected function com_index_index($request){
		$event=controller('Common', 'event');
		$data=[];
		//绑定数据
		$bindings=[];
		$back=$event->getBindings($this->user['id'],true,0);
		if($back){
			foreach($back as $v){
				foreach($event->bindingsType as $kk=>$vv){
					if($vv==$v->type){
						$bindings[$kk]=$v->name;
						break;
					}
				}
			}
		}
		foreach($event->bindingsType as $k=>$v){
			if(!isset($bindings[$k]))$bindings[$k]='';
		}
		$data['bindings']=$bindings;
		//上次登录时间
		$event=controller('Log', 'event');
		$back=$event->get('login',false,1,'create_time');
		$data['loginTime']=$back[0]->create_time;
		
		return $data;
    }
	#基础信息
	protected function com_info_basic($request){
		$event=controller('Common', 'event');
		$data=[];
		//绑定数据
		$bindings=[];
		$bindingsType=['phone'=>1,'mail'=>2];
		$back=$event->getBindings($this->user['id'],true,0);
		if($back){
			foreach($back as $v){
				foreach($bindingsType as $kk=>$vv){
					if($vv==$v->type){
						$bindings[$kk]=$v->name;
						break;
					}
				}
			}
		}
		foreach($bindingsType as $k=>$v){
			if(!isset($bindings[$k]))$bindings[$k]='';
		}
		$data['bindings']=$bindings;
		return $data;
	}
	#基础信息
	protected function com_info_portrait($request){
		$event=controller('Common', 'event');
		$data=[];
		return $data;
	}
	#绑定手机
	protected function com_safe_phone($request){
		$event=controller('Common', 'event');
		$data=[];
		$back=$event->getBindings(['user_id'=>$this->user['id'],'type'=>$event->bindingsType['phone']],false,1);
		$data['binding']=$back?$back->toarray():'';
		return $data;
	}
	#绑定邮箱
	protected function com_safe_mail($request){
		$event=controller('Common', 'event');
		$data=[];
		$back=$event->getBindings(['user_id'=>$this->user['id'],'type'=>$event->bindingsType['mail']],false,1);
		$data['binding']=$back?$back->toarray():'';
		return $data;
	}
	#修改密码
	protected function com_safe_password($request){
		$event=controller('Common', 'event');
		$data=[];
		return $data;
	}
	#注册 登录 重定向
	protected function login_redirect($request){
		$event=controller('Common', 'event');
		if($event->verifyLogin(false))$this->redirect('index');
		if(!$request->param('noredirect')){
			$url=$request->param('url');			
			if(!verify_url_from($url)){
				$url=$request->server('HTTP_REFERER');
				if(!verify_url_from($url))$url='';
			}
			if($url){
				$dUrl=[url('login','','',true),url('signup','','',true),url('api/logout','','',true)];
				foreach($dUrl as $v){
					if(stripos($url,$v)===0){
						$url='';
						break;
					}
				}
				if($url)$this->redirect(url($request->action(),'noredirect=1').'?url='.urlencode($url));
			}
		}
		$this->skinFile['css']='login.css';
		$this->page_title='注册 / 登录';
	}
	protected function _signup($request){
		$this->login_redirect($request);
		$this->Tpl='login';
		$url=$request->param('url');
		if(!verify_url_from($url))$url='';
		$data=['type'=>'signup','url'=>urldecode($url)];
		return $data;
	}
	protected function _login($request){
		$this->login_redirect($request);
		$url=$request->param('url');
		if(!verify_url_from($url))$url='';
		$data=['type'=>'login','url'=>urldecode($url)];
		return $data;
    }
}
