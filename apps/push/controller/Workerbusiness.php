<?php
namespace app\push\controller;
use think\worker\Server;
$WorkermanPath='workerman/workerman'.(IS_WIN?'-for-win':'').'/';
require_once VENDOR_PATH.$WorkermanPath.'Channel/Client.php';
require_once VENDOR_PATH.$WorkermanPath.'GlobalData/Client.php';
class Workerbusiness extends Server{
    protected $socket = 'websocket://0.0.0.0:2356';
	protected $name='business';
    /**
     * 收到信息
     * @param $connection
     * @param $data
     */
    public function onMessage($connection, $data){
		$str=print_r($data,true);
		//Log::write($str,'notice');
		echo 'get:'.$connection->id.':'.$str."\n";
        $connection->send('我收到你的信息了:'.$str);
    }

    /**
     * 当连接建立时触发的回调函数
     * @param $connection+
     */
    public function onConnect($connection){
		//连接成功开始验证
		echo 'aaa';
    }

    /**
     * 当连接断开时触发的回调函数
     * @param $connection
     */
    public function onClose($connection){

    }

    /**
     * 当客户端的连接上发生错误时触发
     * @param $connection
     * @param $code
     * @param $msg
     */
    public function onError($connection, $code, $msg){
        echo "error $code $msg\n";
    }

    /**
     * 每个进程启动
     * @param $worker
     */
    public function onWorkerStart($worker){
		global $globalData;
		#连接Channel服务器
		\Channel\Client::connect('127.0.0.1', 2206);
		#连接GlobalData服务器
		$globalData = new \GlobalData\Client('127.0.0.1:2207');
    }
	
	
}
?>