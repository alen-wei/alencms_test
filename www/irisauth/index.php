<?php
error_reporting(0);
date_default_timezone_set('PRC'); 
class SQLite{
	function __construct($file){
		try{
			$this->connection=new PDO('sqlite:'.$file);
		}catch(PDOException $e){
			try{
				$this->connection=new PDO('sqlite2:'.$file);
			}catch(PDOException $e){
				exit('error!');
	
			}
		}
	}
	
	function __destruct(){
		$this->connection=null;
	}
	
	function query($sql){ //直接运行SQL，可用于更新、删除数据
		return $this->connection->query($sql);
	}
	
	function getlist($sql){ //取得记录列表
		$recordlist=array();
		foreach($this->query($sql) as $rstmp){
			$recordlist[]=$rstmp;
		}
		return $recordlist;
	}
	
	function Execute($sql){
		return $this->query($sql)->fetch();
	}
	function RecordArray($sql){
		return $this->query($sql)->fetchAll();
	}
	function RecordCount($sql){
		return count($this->RecordArray($sql));
	}
	function RecordLastID(){
		return $this->connection->lastInsertId();
	}
}
#============================
function backFun($d){
	echo json_encode($d);
	exit();
}
#=============================

$data=$_REQUEST;
if(!isset($data['id']))backFun(array('status'=>0,'err'=>'无操作ID',));
if(!isset($data['sn']))backFun(array('status'=>0,'err'=>'无设备SN',));
if(!isset($data['userid']))backFun(array('status'=>0,'err'=>'无用户ID',));
if(!isset($data['groups']))backFun(array('status'=>0,'err'=>'无用户组',));
if(!isset($data['times']))backFun(array('status'=>0,'err'=>'无认证时间戳',));

$DB=new SQLite('log.db');

$back=$DB->getlist('SELECT id,sid FROM log WHERE sid="'.$data['id'].'"');
if(count($back)>0)backFun(array('status'=>0,'err'=>'操作ID重复',));

$str=$data['sn'].','.$data['userid'].','.$data['groups'].','.$data['times'];
$DB->query('insert into log(sid,info,times) values("'.$data['id'].'","'.$str.'","'.time().'")');
backFun(array('status'=>1,'id'=>$data['id'],));