<?php
namespace base;
class Behavior{
	public function appInit(&$params){	
		\app\Admin\event\AlenConfig::loadConfig();
	}
	
	
}