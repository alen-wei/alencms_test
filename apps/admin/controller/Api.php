<?php
namespace app\Admin\controller;
//use think\Validate;
use base\Ajax;
class Api extends Ajax{
	protected $module='Admin';
        #登陆登录
	protected function _login($request){
            $captcha=$request->param('code');
            if(!captcha_check($captcha,'admin'))return set_err_back('00010005',false);
            $userStr=$request->param('name');
            $password=$request->param('pw');
            if($request->param('_encrypt'))$password=str_decrypt_easy($password);
            $event=controller('Common', 'event');
            $userData=$event->loginAdmin($userStr,$password);
            if(!$userData){
                if($event->errText)return ['_err'=>true,'_txt'=>lang('Administrator status').'：'.$event->errText];
                return set_err_back($event->errCode,$this->module);
            }
            $url=url('html/frame');
            return ['_txt'=>$userData['name'],'_url'=>$url];
	}
	#退出登录
	protected function _logout($request){
		$this->echoHtml=true;
		$event=controller('Common', 'event');
		if(!$event->logoutAdmin())return set_err_back($event->errCode,$this->module);
		$url=$request->param('url');
		$url=$url?$url:$request->server('HTTP_REFERER');
		$url=$url?$url:url('login');
		return ['_txt'=>lang('bye bye'),'_url'=>$url];
	}
	
}