<?php
//中文语言包
return [
	//自定错误码
	'err000001'       		 => '错误的短信类型',
    'err000002'       		 => '错误的短信变量',
	'err000003'       		 => '远程接口返回错误',
	'err000004'       		 => '同一号码发送次数过于频繁',
	//接口系统级错误码
	'err100001'       		 => '错误的请求KEY',
    'err100002'       		 => '该KEY无请求权限',
	'err100003'       		 => 'KEY过期',
    'err100004'       		 => '错误的OPENID',
	'err100005'       		 => '应用未审核超时，请提交认证',
	'err100007'       		 => '未知的请求源',
    'err100008'       		 => '被禁止的IP',
	'err100009'       		 => '被禁止的KEY',
    'err100011'       		 => '当前IP请求超过限制',
	'err100012'       		 => '请求超过次数限制',
    'err100013'       		 => '测试KEY超过请求限制',
	'err100014'       		 => '系统内部异常',
    'err100020'       		 => '接口维护',
	'err100021'       		 => '接口停用',
	//SMS服务级错误码
	'err205401'       		 => '错误的手机号码',
    'err205402'       		 => '错误的短信模板ID',
    'err205403'       		 => '网络错误,请重试',
	'err205404'       		 => '发送失败，具体原因请参考返回reason',
	'err205405'       		 => '号码异常/同一号码发送次数过于频繁',
	'err205406'       		 => '不被支持的模板',
];