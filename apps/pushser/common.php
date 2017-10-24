<?php
//连接订阅服务
function connect_channel(){
	$channelIps=config('channelIps');
	$channelIps=explode(':', $channelIps);
	\Channel\Client::connect($channelIps[0],$channelIps[1]);
}
function con_send(&$con,$msg){
	$data=handle_send_data($msg);
	$now=get_now_time();
	$con->sendTime=$now;
	return $con->send($data);
}
//连接事务服务
function get_new_business(){
	$business = new \Workerman\Connection\AsyncTcpConnection('ws://'.config('businessIps'));
	return $business;
}
//处理发送数据
function handle_send_data($data){
	return json_encode($data,JSON_UNESCAPED_UNICODE);
}
//处理接受数据
function handle_get_data($data){
	return json_decode($data,true);
}

//链接验证
function send_connect_verify(&$con,&$obj){
	$key= config('sys_keys');
	$now= get_now_time();
	$token=str_encrypt(md5($now.'|'.$key),$now);
	$data=[
		'type'=>'verify',
		'token'=>$token,
		'time'=>$now,
		'ips'=>$obj->gatewayIps,
	];
	con_send($con,$data);
}
//链接验证
function get_gateway_ips(){
	$cacheName=config('gatewayListName');
	$cache=cache($cacheName);
	if(!$cache or count($cache)<1)return false;
	foreach($cache as $k=>$v){
		return $k;
	}
}
