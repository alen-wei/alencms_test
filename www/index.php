<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

// [ 应用入口文件 ]
//header("Access-Control-Allow-Origin: *");

// 定义应用目录
define('APP_PATH', __DIR__ . '/../apps/');
//定义第三方类库目录目录
define('EXTEND_PATH', '../framework/alen/extend/');
// 定义总框架目录
define('FRAME_PATH', __DIR__ . '/../framework/');
// 绑定模块
//define('BIND_MODULE','cncmonitor');
// 加载框架引导文件
require FRAME_PATH.'thinkphp/start.php';
