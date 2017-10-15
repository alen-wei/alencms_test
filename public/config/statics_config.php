<?php
return [
	'rdbms'=>[
		'MySQL'=>[
			'type'           => 'mysql',
			'hostname'       => '127.0.0.1',
			'hostport'       => '3306',
			'database'       => 'tp',
			'username'       => 'root',
			'password'       => 'root',
		],
	],
	'nosql'=>[
		'MongoDB'=>[
			'type'           => '\think\mongo\Connection',
			'hostname'       => '127.0.0.1',
			'hostport'       => '27017',
			'database'       => 'tp',
			'username'       => 'alencms',
			'password'       => 'heng7535',
		],
	],
	'cache'=>[
		'Redis'=>[
			'type'   => 'redis',
			'host'=>'127.0.0.1',
			'port'=>'6379',
			'password'=>'heng7535',
		],
	],
];