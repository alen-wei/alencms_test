<?php
return [
	//@Alen
	'os_code'=>'GB2312',
	'sys_keys'=>'KLH87T78OGIUH7TUI',
	//应用ID
	'app_id'				=>[
		'cncmonitor'=>1,
		'user'=>2,
		'file'=>3,
		'system'=>4,
		'cms'=>5,
		'admin'=>6,
	],
	'queue_switch'=>false,
	//公共资源目录
	'public_path'			=>PUBLIC_PATH,
	'static_path'			=>PUBLIC_PATH.'static'.DS,
	//主题资源文件夹
	'skin_dirname'			=>'_skin',
	//令牌有效时间
	'token_Time'			=>3600,
	//登录有效时间
	'login_Time'			=>604800, //7天
	//缩略图大小
	'thumb_wh'				=>['100'=>'100,100','320'=>'320,320','640'=>'640,640'],
	
	'database'=>[
		'prefix'=> PREFIX.'_',
		// 数据库部署方式:0 集中式(单一服务器),1 分布式(主从服务器)
		'deploy'         => 0,
		// 数据库读写是否分离 主从式有效
		'rw_separate'    => false,
		// 读写分离后 主服务器数量
		'master_num'     => 1,
		// 指定从服务器序号
		'slave_no'       => '',
		// 是否严格检查字段是否存在
		'fields_strict'  => true,
		// 数据集返回类型 array 数组 collection Collection对象
		'resultset_type' => 'array',
		// 是否自动写入时间戳字段
		'auto_timestamp' => true,
		// 是否需要时间字段输出的时候会自动进行格式转换
		'datetime_format'=> false,
		// mongo 强制把_id转换为id 
		'pk_convert_id'  => true,
	],
	// +----------------------------------------------------------------------
    // | 验证码
    // +----------------------------------------------------------------------
	'captcha'  => [
		// 验证码字体大小(px)
		'fontSize' => 16,
		// 是否画混淆曲线
		'useCurve' => false,
		// 是否添加杂点
		'useNoise'=>false,
		 // 验证码图片高度
		'imageH'   => 30,
		// 验证码图片宽度
		'imageW'   => 120,
		// 验证码位数
		'length'   => 4,
		// 验证成功后是否重置        
		'reset'    => true
	],
	// +----------------------------------------------------------------------
    // | 邮件
    // +----------------------------------------------------------------------
	'mail'  => [
		//发件邮箱
		'address'=>'auto@alennet.com',
		//帐号
		'loginname'=>'auto@alennet.com',
		//密码
		'password'=>'Heng7535',
		//smtp 端口
		'port'=>'465',
		//smtp 服务器
		'smtp'=>'smtp.exmail.qq.com',
		//安全协议
		'secure'=>'ssl',
	],
    // +----------------------------------------------------------------------
    // | 应用设置
    // +----------------------------------------------------------------------
	
    // 应用命名空间
    'app_namespace'          => 'app',
    // 应用调试模式
    'app_debug'              => true,
    // 应用Trace
    'app_trace'              => true,
    // 应用模式状态
    'app_status'             => '',
    // 是否支持多模块
    'app_multi_module'       => true,
    // 入口自动绑定模块
    'auto_bind_module'       => false,
    // 注册的根命名空间
    'root_namespace'         => [],
    // 扩展函数文件
    'extra_file_list'        => [THINK_PATH . 'helper' . EXT],
    // 默认输出类型
    'default_return_type'    => 'html',
    // 默认AJAX 数据返回格式,可选json xml ...
    'default_ajax_return'    => 'json',
    // 默认JSONP格式返回的处理方法
    'default_jsonp_handler'  => 'jsonpReturn',
    // 默认JSONP处理方法
    'var_jsonp_handler'      => 'callback',
    // 默认时区
    'default_timezone'       => 'PRC',
    // 是否开启多语言
    'lang_switch_on'         => false,
    // 默认全局过滤方法 用逗号分隔多个
    'default_filter'         => '',
    // 应用类库后缀
    'class_suffix'           => false,
    // 控制器类后缀
    'controller_suffix'      => false,

    // +----------------------------------------------------------------------
    // | 模块设置
    // +----------------------------------------------------------------------

    // 默认模块名
    'default_module'         => 'cncmonitor',
    // 禁止访问模块
    'deny_module_list'       => ['common'],
    // 默认控制器名
    'default_controller'     => 'Index',
    // 默认操作名
    'default_action'         => 'index',
    // 默认验证器
    'default_validate'       => '',
    // 默认的空控制器名
    'empty_controller'       => 'Error',
    // 操作方法后缀
    'action_suffix'          => '',
    // 自动搜索控制器
    'controller_auto_search' => false,

    // +----------------------------------------------------------------------
    // | URL设置
    // +----------------------------------------------------------------------

    // PATHINFO变量名 用于兼容模式
    'var_pathinfo'           => 's',
    // 兼容PATH_INFO获取
    'pathinfo_fetch'         => ['ORIG_PATH_INFO', 'REDIRECT_PATH_INFO', 'REDIRECT_URL'],
    // pathinfo分隔符
    'pathinfo_depr'          => '/',
    // URL伪静态后缀
    'url_html_suffix'        => 'html|js|css',
    // URL普通方式参数 用于自动生成
    'url_common_param'       => false,
    // URL参数方式 0 按名称成对解析 1 按顺序解析
    'url_param_type'         => 0,
    // 是否开启路由
    'url_route_on'           => true,
    // 路由使用完整匹配
    'route_complete_match'   => false,
    // 路由配置文件（支持配置多个）
    'route_config_file'      => ['route'],
    // 是否强制使用路由
    'url_route_must'         => false,
    // 域名部署
    'url_domain_deploy'      => false,
    // 域名根，如thinkphp.cn
    'url_domain_root'        => '',
    // 是否自动转换URL中的控制器和操作名
    'url_convert'            => true,
    // 默认的访问控制器层
    'url_controller_layer'   => 'controller',
    // 表单请求类型伪装变量
    'var_method'             => '_method',

    // +----------------------------------------------------------------------
    // | 模板设置
    // +----------------------------------------------------------------------

    'template'               => [
        // 模板引擎类型 支持 php think 支持扩展
        'type'         => 'Think',
        // 模板路径
        'view_path'    => '',
        // 模板后缀
        'view_suffix'  => 'html',
        // 模板文件名分隔符
        'view_depr'    => DS,
        // 模板引擎普通标签开始标记
        'tpl_begin'    => '{',
        // 模板引擎普通标签结束标记
        'tpl_end'      => '}',
        // 标签库标签开始标记
        'taglib_begin' => '{',
        // 标签库标签结束标记
        'taglib_end'   => '}',
    ],

    // 视图输出字符串内容替换
    'view_replace_str'       => [],
    // 默认跳转页面对应的模板文件
    'dispatch_success_tmpl'  => PUBLIC_PATH . 'template' . DS . 'sys_message.tpl',
    'dispatch_error_tmpl'    => PUBLIC_PATH . 'template' . DS . 'sys_message.tpl',

    // +----------------------------------------------------------------------
    // | 异常及错误设置
    // +----------------------------------------------------------------------

    // 异常页面的模板文件
	'exception_tmpl'   		 => PUBLIC_PATH . 'template' . DS . 'sys_exception.tpl',
    // 错误显示信息,非调试模式有效
    'error_message'          => '页面错误！请稍后再试～',
    // 显示错误信息
    'show_error_msg'         => false,
    // 异常处理handle类 留空使用 \think\exception\Handle
    'exception_handle'       => '',

    // +----------------------------------------------------------------------
    // | 日志设置
    // +----------------------------------------------------------------------

    'log'                    => [
        // 日志记录方式，内置 file socket 支持扩展
        'type'  => 'File',
        // 日志保存目录
        'path'  => LOG_PATH,
        // 日志记录级别
        'level' => [],
    ],

    // +----------------------------------------------------------------------
    // | Trace设置 开启 app_trace 后 有效
    // +----------------------------------------------------------------------
    'trace'                  => [
        // 内置Html Console 支持扩展
        'type' => 'Html',
    ],

    // +----------------------------------------------------------------------
    // | 缓存设置
    // +----------------------------------------------------------------------
    'cache'                  => [ 
        // 驱动方式
        'type'   => 'File',
        // 缓存保存目录
        'path'   => CACHE_PATH,
        // 缓存前缀
        'prefix' => PREFIX.'_',
        // 缓存有效期 0表示永久缓存
        'expire' => 0,
    ],

    // +----------------------------------------------------------------------
    // | 会话设置
    // +----------------------------------------------------------------------

    'session'                => [
        'id'             => '',
        // SESSION_ID的提交变量,解决flash上传跨域
        'var_session_id' => '',
        // SESSION 前缀
        'prefix'         => PREFIX.'_',
        // 驱动方式 支持redis memcache memcached
        'type'           => '',
        // 是否自动开启 SESSION
        'auto_start'     => true,
    ],

    // +----------------------------------------------------------------------
    // | Cookie设置
    // +----------------------------------------------------------------------
    'cookie'                 => [
        // cookie 名称前缀
        'prefix'    => PREFIX.'_',
        // cookie 保存时间
        'expire'    => 0,
        // cookie 保存路径
        'path'      => '/',
        // cookie 有效域名
        'domain'    => DOMAIN,
        //  cookie 启用安全传输
        'secure'    => false,
        // httponly设置
        'httponly'  => '',
        // 是否使用 setcookie
        'setcookie' => true,
    ],

    //分页配置
    'paginate'               => [
        'type'      => 'bootstrap',
        'var_page'  => 'page',
        'list_rows' => 15,
    ],
];
