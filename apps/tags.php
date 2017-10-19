<?php
// 应用行为扩展定义文件
return [
    // 应用初始化
    'app_init'     => ['\\base\\Behavior'],
    // 应用开始
    'app_begin'    => [],
    // 模块初始化
    'module_init'  => [],
    // 操作开始执行
    'action_begin' => [],
    // 视图内容过滤
    'view_filter'  => [],
    // 日志写入
    'log_write'    => [],
    // 应用结束
    'app_end'      => [],
	//任务失败统一回调,有四种定义方式
    'queue_failed'=>['\\base\\Behavior'],
];
