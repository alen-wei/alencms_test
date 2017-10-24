<?php
namespace base;
trait TraitWorker{
	protected function init(){
		$this->worker->name=$this->name;
    }
	public function getPort(){
		return $this->port;
	}
	public function echoStatus(&$con,$data,$err=false){
		$msg=is_array($data)?json_encode($data,JSON_UNESCAPED_UNICODE):$data;
		$echoStr='==['.get_now_time('Y-m-d H:i:s').']================='."\r\n";
		$echoStr.='cid:'.$con->id."\r\n";
		$echoStr.=$err?'err':'msg';
		$echoStr.=':'.$msg;
		$echoStr.="\r\n";
		echo mb_convert_encoding($echoStr,config('os_code'),"UTF-8");
	}
	 /**
     * 当客户端的连接上发生错误时触发
     * @param $connection
     * @param $code
     * @param $msg
     */
    public function onError($con, $code, $msg){
		$this->echoStatus($con,$code.$msg,true);
    }
}