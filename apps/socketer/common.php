<?php
//连接订阅服务
function connect_channel(){
	\Channel\Client::connect('127.0.0.1', 2206);
}
//连接事务服务
function get_new_business(){
	$business = new \Workerman\Connection\AsyncTcpConnection("ws://127.0.0.1:2356");
	return $business;
}
//处理发送数据
function handle_send_data(){
	
}