<?php
namespace app\Pushser\controller;
use base\Ajax;
class Api extends Ajax{
	protected $module='Pushser';
	#验证帐号是否可以用
	protected function _getToken($request){
		$event=controller('User/Common', 'event');
		$user=$event->verifyLogin(true);
		$now=get_now_time();
		$ips=get_gateway_ips();
		if($ips){
			$data=['type'=>'ws'];
		}else{
			$ips=url('select');
			$data=['type'=>'ajax'];
		}
		$uid=$user?$user['id']:0;
		$data['token']=str_encrypt($now.'.'.$uid,$now);
		//$data['token'].='aaaa';
		$data['time']=$now;
		$data['url']=$ips;
		return $data;
	}
	
	protected function _select($request){
		return true;
	}
}