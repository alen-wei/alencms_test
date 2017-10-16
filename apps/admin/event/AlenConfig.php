<?php
namespace app\Admin\event;
use \think\Config;
class AlenConfig {
	protected $module='Admin';
	public $errCode='';
    public $errText='';
	static $configFile=CONFIG_PATH.'config.php';
	static $staticFile=CONFIG_PATH.'statics_config.php';
	static $ConstFile=CONFIG_PATH.'define.php';
	static $ConstName=['DOMAIN','PREFIX'];
	//列表
	static function getConfig($static=false){
		return include $static?self::$staticFile:self::$configFile;
	}
	static function getConstLists(){
		$data=[];
		foreach (self::$ConstName as $v){
			$data[$v]=constant($v);
		}
		return $data;
	}
	static function setConfig($data){
		$content=var_export($data,true);
		$content='<?php return '.$content.';';
		$fp = fopen(self::$configFile,"w");
		fwrite($fp, $content);
		fclose($fp);
		return true;
	}
	static function setConst($data){
		$content='';
		foreach($data as $k=>$v){
			$content.='define(\''.$k.'\',\''.$v.'\');';
		}
		$content='<?php '.$content;
		$fp = fopen(self::$ConstFile,"w");
		fwrite($fp, $content);
		fclose($fp);
		return true;
	}
	static function loadConfig(){
		//加载配置
		$cog_arr=self::getConfig();
		$db_arr=self::getConfig(true);
		foreach ($cog_arr['db_config'] as $k=>$v){
			if(isset($db_arr[$k][$v]))$cog_arr['db_config'][$k]=$db_arr[$k][$v];
		}
		$db_cog=$cog_arr['db_config'];
		$setArr=[];
		//debug
		$debug=$cog_arr['debug'];
		unset($cog_arr['debug']);
		if(!isset($db_cog['public']))$db_cog['public']=[];
		$db_cog['public']['debug']=$debug;
		$db_cog['public']['sql_explain']=$debug;
		$cog_arr['app_debug']=$debug;
		$cog_arr['app_trace']=$debug;
		//设置cache
		$cache=Config::get('cache');
		$cache=array_merge($cache,$db_cog['cache']);
		$setArr['cache']=$cache;
		//Config::set($cache,'cache');
		unset($db_cog['cache']);
		//设置session
		if('redis'==strtolower($cache['type'])){
			unset($cache['persistent']);
			unset($cache['expire']);
			unset($cache['timeout']);
			$tmp=Config::get('session');
			$cache['prefix']=$tmp['prefix'];
			$cache['session_name']=PREFIX.'_session_';
			$tmp=array_merge($tmp,$cache);
			$setArr['session']=$tmp;
			//Config::set($tmp,'session');
		}
		//设置数据库
		$bd_public=$db_cog['public'];
		unset($db_cog['public']);
		$bd_public=array_merge(Config::get('database'),$bd_public);
		foreach($db_cog as $k=>$v){
			if($k=='rdbms')$k='database';
			$tmp=Config::get($k);
			if(!$tmp)$tmp=[];
			$tmp=array_merge($tmp,$bd_public,$v);
			$setArr[$k]=$tmp;
			//Config::set($tmp,$k);
		}
		unset($cog_arr['db_config']);
		$setArr=array_merge($cog_arr,$setArr);
		Config::set($setArr);
	}
}