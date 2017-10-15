<?php
namespace app\Admin\controller;
use base\Tpl;
class Calls extends Tpl{
	protected $module='Admin';
	
	protected function _frame(){
		echo 'aa';
		exit();
	}
}