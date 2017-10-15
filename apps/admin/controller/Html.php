<?php
namespace app\Admin\controller;
use base\Tpl;
class Html extends Tpl{
	protected $module='Admin';
    protected function getNav(){
        $appArr=config('app_id');
        $data=[];
        foreach ($appArr as $k=>$v){
            $moduleName = '\\app\\'.$k.'\\controller\\Admin';
            if(!class_exists($moduleName))continue;
            if(!isset($moduleName::$adminInfo))continue;
            if(!isset($moduleName::$adminNav))continue;
			if(!isset($moduleName::$ctrlLists))continue;
            $tmp=$moduleName::$adminInfo;
            $tmp['item']=[];
            foreach($moduleName::$adminNav as $vv){
                $tmp['item'][]=[
                    'name'=>$moduleName::$ctrlLists[$vv]['name'],
                    'url'=>url($k.'/admin/'.$vv),
                ];
            }
            $data[]=$tmp;
        }
        return $data;
    }
    //主框架
	protected function _frame(){
		$this->page_title=config('sys_name').'-后台管理';
		$event=controller('Common', 'event');
		$user=$event->verifyAdmin();
		if(!$user){
			$url=url('login','noredirect=1').'?url='.urlencode(get_this_url());
			$this->redirect($url);
			exit();
		}
		$event=controller('Auth', 'event');
		$group=$event->getGroup($user->admin);
        $nav=$this->getNav();
		$data=[
			'group'=>$group,
			'user'=>$user,
            'nav'=>$nav,
			'url'=>$nav[0]['item'][0]['url'],
		];
		return $data;
	}
	//提示
	protected function _errmsg($request){
		$data=true;
		return $data;
	}
    //登录
	protected function _login($request){
        $url=verify_url_from($request->param('url'));
        $event=controller('Common', 'event');
        if($event->verifyAdmin(0,0,false))$this->redirect($url?$url:'frame');
		if(!$request->param('noredirect')){
			$url=$url?$url:verify_url_from($request->server('HTTP_REFERER'));
            $dUrl=[url('login','','',true),url('api/logout','','',true)];
            if($url){
                foreach($dUrl as $v){
                    if(stripos($url,$v)===0){
                        $url='';
                        break;
                    }
                }
                $this->redirect(url($request->action(),'noredirect=1').'?url='.urlencode($url));
            }
		}
		return ['url'=>$url];
	}
}