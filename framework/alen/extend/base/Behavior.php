<?php
namespace base;
use \think\Config;
class Behavior{
	public function appInit(&$params){	
		//加载配置
		$cog_arr=include CONFIG_PATH.'config.php';
		$db_arr=include CONFIG_PATH.'dbconfig.php';
		
		
		foreach ($cog_arr['db_config'] as $k=>$v){
			if(isset($db_arr[$k][$v]))$cog_arr['db_config'][$k]=$db_arr[$k][$v];
		}
		$db_cog=$cog_arr['db_config'];
		//设置cache
		$cache=Config::get('cache');
		$cache=array_merge($cache,$db_cog['cache']);
		Config::set($cache,'cache');
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
			Config::set($tmp,'session');
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
			Config::set($tmp,$k);
		}
		unset($cog_arr['db_config']);
		Config::set($cog_arr);
	}
	
	
}