<?php
namespace app\Pushser\controller;
use think\worker\Server;
use Workerman\Lib\Timer;
class Gateway extends Server{
	use \base\TraitWorker;
	protected $name='gateway';
	protected $protocol  = 'websocket';
    protected $host      = '0.0.0.0';
    protected $port      = '2346';
	protected $business;
	protected $verifyTime;
	protected $pingID;
	public $gatewayIps='127.0.0.1';
	
	protected function _verifyUser(&$con,$data){
		$now= get_now_time();
		$token=$data['token'];
		$time=$data['time'];
		if($now-$time>config('user_token_time'))return false;
		
		str_decrypt($token,$time);
		
	}
    /**
     * 收到信息
     * @param $connection
     * @param $data
     */
    public function onMessage($con, $msg){
		$data=handle_get_data($msg);
		if(!$data)return;
		if($data['type']=='verify'){
			_verifyUser($con,$data);
			//$token=$data['token'];
			//$con->send('我收到你的信息了:'.$token);
		}
		/*
		$str=print_r($data,true);
		//Log::write($str,'notice');
		echo 'get:'.$con->id.':'.$str."\n";
        $connection->send('我收到你的信息了:'.$str);
		* 
		*/
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
     * 每个进程启动
     * @param $worker
     */
    public function onWorkerStart($worker){
		$this->gatewayIps.=':'.$this->port;
		#连接Channel服务器
		connect_channel();
		#连接business服务器
		$this->business = get_new_business();
		$this->business->onConnect=function($con){
			//连接成功开始验证
			send_connect_verify($con,$this);
			$this->echoStatus($con,'start verify');
		};
		$this->business->onClose=function($con) {
			//如果连接断开，则在5秒后重连
			$this->verifyTime=0;
			Timer::del($this->pingID);
			$this->echoStatus($con,'business connect close',true);
			$con->reConnect(5);
		};
		$this->business->onMessage=function($con,$msg){
			$data=handle_get_data($msg);
			if(!$data)return;
			if(!$this->verifyTime){
				//如果没有验证的话，只接受验证信息
				if($data['type']=='verify'){
					if(isset($data['err'])){
						$this->echoStatus($con,$data['err'],true);
					}else{
						$this->verifyTime=$data['time'];
						//心跳
						$this->echoStatus($con,'verify success');
						$self=&$this;
						$this->pingID=Timer::add(1, function()use(&$con,&$self){
							$now=get_now_time();
							$HEARTBEAT_TIME=config('HEARTBEAT_TIME');
							if($now-$con->sendTime>$HEARTBEAT_TIME){
								con_send($con,['type'=>'ping']);
								$self->echoStatus($con,'heart');
							}
						});
						//$con->sendTime
					}
				}
				return;
			}
		};
		$this->business->connect();
		
    }
	
	
}