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

$DB=new SQLite('log.db');
$back=$DB->getlist('SELECT * FROM log ORDER BY times DESC LIMIT 0,20');
if(count($back)<1){
	echo '无数据';
	exit();
}
foreach($back as $v){
	echo '操作ID:'.$v['sid'].'&nbsp;&nbsp;&nbsp;&nbsp;内容:'.$v['info'].'&nbsp;&nbsp;&nbsp;&nbsp;接收时间:'.date('Y-m-d H:i:s',$v['times']).'<br />';
}
