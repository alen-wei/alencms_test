<?php
namespace juhe;
class Sms extends Main{
	protected $key='f707bf06d5cb4d87db020db0c84a9723';
	protected $modName='sms';
	protected $space=60;
	protected $tplArr=[
		'verify'=>['id'=>27602,'val'=>['code','hour']],  //验证码
	];
	public $smsID;
	#发送信息
	public function send($phone,$type,$val){
		$url='http://v.juhe.cn/sms/send';
		$now=get_now_time();
		if(!$phone){
			$this->errCode='205401';
			return 0;
		}
		if(!$type){
			$this->errCode='000001';
			return 0;
		}
		if(!is_array($val)){
			$this->errCode='000002';
			return 0;
		}
		if(isset($this->tplArr[$type])){
			$tpl=$this->tplArr[$type];
		}else{
			$this->errCode='000001';
			return 0;
		}
		$tpl_id=$tpl['id'];
		$tpl_value=[];
		foreach($tpl['val'] as $v){
			if(isset($val[$v])){
				$tpl_value[]='#'.$v.'#='.$val[$v];
			}else{
				$this->errCode='000002';
				return 0;
			}
		}
		$db=$this->db();
		$times=$db->where('phone',$phone)->order('times DESC')->value('times');
		if($now-$times<$this->space){
			$this->errCode='000004';
			return 0;
		}
		$tpl_value=urlencode(implode('&',$tpl_value));
		$postData=[
			'mobile'=>$phone,
			'tpl_id'=>$tpl_id,
			'tpl_value'=>$tpl_value,
			'key'=>$this->key,
			'dtype'=>$this->backType,
		];
		$back=get_url_file($url,$postData);
		//$back='{"reason":"操作成功","result":{"sid":"201701200941566612571103","fee":1,"count":1},"error_code":0}';
		if(!$back){
			$this->errCode='000003';
			return 0;
		}
		$back=json_decode($back,true);
		if(!is_array($back)){
			$this->errCode='000003';
			return 0;
		}
		if($back['error_code']){
			$this->errCode=$back['error_code'];
			return 0;
		}
		if(isset($back['result']['sid'])){
			$this->smsID=$back['result']['sid'];
			//记录
			$db->insert([
				'sid'=>$this->smsID,
				'phone'=>$phone,
				'type'=>$type,
				'val'=>json_encode($val,JSON_UNESCAPED_UNICODE),
				'times'=>$now,
			]);
			return true;
		}
		$this->errCode='000003';
		return 0;
	}
}
?>