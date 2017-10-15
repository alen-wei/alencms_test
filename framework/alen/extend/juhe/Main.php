<?php
namespace juhe;
class Main{
	protected $backType='json';
	protected $dbConfig=[
		// 数据库类型
		'type'           => 'sqlite',
		// 服务器地址
		'hostname'       => __DIR__.DS.'db'.DS.'log.php',
		// 数据库名
		'database'       => 'main',
		// 用户名
		'username'       => '',
		// 密码
		'password'       => '',
		// 端口
		'hostport'       => '',
		// 连接dsn
		'dsn'            => 'sqlite:'.__DIR__.DS.'db'.DS.'log.php',
		// 数据库连接参数
		'params'         => [],
		// 数据库编码默认采用utf8
		'charset'        => 'utf8',
		// 数据库表前缀
		'prefix'         => PREFIX.'_',
		// 数据库调试模式
		'debug'          => false,
		// 是否自动写入时间戳字段
		'auto_timestamp' => false,
		
	];
	protected $errCodeArr;
	public $errCode;
	protected function db($tab=''){
		
		//$this->modName
		return db('juhe_'.($tab?$tab:$this->modName),$this->dbConfig);
	}
	protected function loadLang($lang=''){
		$lang=$lang?$lang:config('default_lang');
		$file=__DIR__.DS.'lang'.DS.$lang.'.php';
		$this->errCodeArr=include($file);
	}
	protected function lang($str){
		if(!$this->errCodeArr)$this->loadLang();
		return isset($this->errCodeArr[$str])?$this->errCodeArr[$str]:false;
	}
	protected function errTxt($code){
		return $this->lang('err'.$code);
	}
	public function getErrTxt(){
		return $this->errCode?$this->errTxt($this->errCode):false;
	}
}
?>