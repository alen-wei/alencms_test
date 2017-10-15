<?php
namespace app\Cms\controller;
use base\Tpl;

class index extends Tpl{
	protected $module='Cms';
	protected function _index(){
                session_start();
                echo session_create_id();
                echo '<br />';
                
		echo get_uid(true);
                echo '<br />';
                
                echo session_id();
		exit();
	}
}