<?php
namespace app\Socketer\controller;
use think\worker\Server;
class Gateway extends Server{
    protected $socket = 'websocket://0.0.0.0:2346';
	protected $name='gateway';
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
		/*
		$ip=$connection->getRemoteIp();
		global $globalData;
		if(in_array($ip,$this->serIp)){
			//echo '服务器已连接'
			#记录IP
			if(isset($globalData->serIp)){
				$serIp=$globalData->serIp;
				if(!in_array($ip,$serIp)){
					$serIp[]=$ip;
					$globalData->cas('serIp', $serIp);
				}
			}else{
				$serIp=[];
				$serIp[]=$ip;
				$globalData->add('serIp', $serIp);
			}
		}else{
			//echo '客户端已连接';
		}
		*/
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
		#连接Channel服务器
		connect_channel();
		#连接business服务器
		$business = get_new_business();
		$business->onConnect=function($con){
			//sys_keys
			$verifyData=[
				'verify'=> config('sys_keys'),
			];
			
			$con->send($verifyData);
			//连接成功开始验证
		};
		$business->onClose=function($con) {
			//如果连接断开，则在5秒后重连
			$con->reConnect(5);
		};
		$business->onMessage=function($con,$msg){
		
		};
		$business->connect();
		
    }
	
	
}