<?php
namespace app\Pushser\controller;
use think\worker\Server;
use Workerman\Lib\Timer;
class Business extends Server{
	use \base\TraitWorker;
	protected $name='business';
	protected $protocol  = 'websocket';
    protected $host      = '0.0.0.0';
    protected $port      = '2356';
	protected $gatewayList=[];
    /**
     * 收到信息
     * @param $connection
     * @param $data
     */
    public function onMessage($con, $msg){
		$data=handle_get_data($msg);
		if(!$data)return;
		if($data['type']=='verify'){
			$back=$this->_verifyConnect($con,$data);
			if($back){
				$this->echoStatus($con,'verify success');
				$con->lastTime = get_now_time();
			}else{
				$this->echoStatus($con,'verify failed');
				$con->close(handle_send_data([
					'type'=>'verify',
					'err'=>'token err',
				]));
			}
			return;
		}
		if(!$this->_verifyConnectId($con->id))return;
		//用来记录上次收到消息的时间
		$con->lastTime = get_now_time();
		switch($data['type']){
			case 'ping':
				$this->echoStatus($con,'heart');
			break;
		}
		
    }

    /**
     * 当连接建立时触发的回调函数
     * @param $connection+
     */
    public function onConnect($con){
    }

    /**
     * 当连接断开时触发的回调函数
     * @param $connection
     */
    public function onClose($con){
		//更新网关列表
		$back=$this->_gatewayDelConnect($con->id);
		if($back)$this->echoStatus($con,'connection close');
    }

    /**
     * 每个进程启动
     * @param $worker
     */
    public function onWorkerStart($worker){
		#连接Channel服务器
		connect_channel();
		//检测心跳
		Timer::add(1, function()use($worker){
			 $now = get_now_time();
			 foreach($worker->connections as $v) {
				if(empty($v->lastTime)) {
					$v->lastTime = $now;
					continue;
				}
				//上次通讯时间间隔大于心跳间隔，则认为客户端已经下线，关闭连接
				$HEARTBEAT_TIME=config('HEARTBEAT_TIME');
				if($now - $v->lastTime > $HEARTBEAT_TIME+5) {
					$v->close();
				}
			 }
		});
    }
	//验证网关
	protected function _verifyConnect(&$con,$data){
		$jg=60;
		$now=get_now_time();
		if($now-$data['time']>$jg)return false;
		$tokenStr=str_decrypt($data['token'],$data['time']);
		if(!$tokenStr)return false;
		$key= config('sys_keys');
		$tokenMD5=md5($data['time'].'|'.$key);
		if($tokenStr!=$tokenMD5)return false;
		$back=$this->_gatewayAddConnect($data['ips'],$con->id);  //更新网关列表
		if(!$back)return false;
		con_send($con,[
			'type'=>'verify',
			'time'=>$now,
		]);
		return true;
	}
	//添加网关列表
	protected function _gatewayAddConnect($ips,$id){
		$id=intval($id);
		$gatewayList=&$this->gatewayList;
		if(isset($gatewayList[$ips])){
			$gatewayList[$ips]['processes']++;
			$gatewayList[$ips]['id'][]=$id;
		}else{
			$gatewayList[$ips]=[
				'processes'=>1,
				'id'=>[$id],
			];
		}
		$this->_uploadCahe();
		return true;
	}
	//删除网关列表
	protected function _gatewayDelConnect($id){
		$id=intval($id);
		$delIps=null;
		$gatewayList=&$this->gatewayList;
		$tmpK=$this->_verifyConnectId($id);
		if(!$tmpK)return false;
		if($gatewayList[$tmpK]['processes']<=1){
			$delIps=$tmpK;
			unset($gatewayList[$tmpK]);
		}else{
			$ak=array_search($id,$gatewayList[$tmpK]['id']);
			unset($gatewayList[$tmpK]['id'][$ak]);
			$gatewayList[$tmpK]['processes']--;
		}
		$this->_uploadCahe($delIps);
		return true;
	}
	//删除连接ID
	protected function _verifyConnectId($id){
		$id=intval($id);
		$gatewayList=&$this->gatewayList;
		if(count($gatewayList)>1)return false;
		foreach($gatewayList as $k=>$v){
			if(in_array($id, $v['id']))return $k;
		}
		return false;
	}
	//更新缓存
	protected function _uploadCahe($delIps=null){
		$cacheName=config('gatewayListName');
		$cache=cache($cacheName);
		if($delIps){
			if(!is_array($delIps))$delIps=[$delIps];
			foreach($delIps as $v){
				unset($cache[$v]);
			}
		}else{
			foreach($this->gatewayList as $k=>$v){
				$cache[$k]=$v;
			}
		}
		cache($cacheName,$cache);
		return true;
	}
}