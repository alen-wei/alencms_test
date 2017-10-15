<?php
return [
	'sys_name'=>'美纳科技',
	'sys_name_full'=>'广东美纳防伪科技有限公司',
	'sys_url'=>'http://127.0.0.1/',
	
	'app_theme'=>'default',
    'default_lang'=>'zh-cn',
	
	'static_url'			=>'http://127.0.0.1:81/',
	'static_path'			=>PUBLIC_PATH.'static'.DS,
	
	'db_config'=>[
		'rdbms'=>'MySQL',
		'nosql'=>'MongoDB',
		'cache'=>'Redis',
		'public'=>[
			'debug'=>true,
			'sql_explain'=> true,
		],
	],
	
];