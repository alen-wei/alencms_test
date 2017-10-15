{__NOLAYOUT__}<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="x-ua-compatible" content="IE=Edge,chrome=1" />
<meta name="renderer" content="webkit" />

<meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1,user-scalable=no" />
<meta name="format-detection" content="telephone=no" / >
<meta name="format-detection" content="email=no" />

<meta name="mobile-web-app-capable" content="yes" />
<meta name="apple-mobile-web-app-capable" content="yes" />
<meta name="apple-mobile-web-app-status-bar-style" content="black" />

<meta name="author"content="alen" />
<title>跳转提示-{:config('sys_name_full')}$</title>

<link rel="apple-touch-icon" href="/icon/favicon.png" />
<link rel="icon" href="/icon/favicon.png" type="image/png" />

<link href="{$Alen.config.static_url}Bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
<link href="{$Alen.config.static_url}Font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
<link href="{$Alen.config.static_url}Alen/Css/public.css" rel="stylesheet" type="text/css" />
<script src="{$Alen.config.static_url}Jquery/jquery-1.11.1.min.js"></script>

<style>
	.msgbox{ display:table; width: 100%;}
	.msgbox .td{ display: table-cell;}
	.msgbox .td.t{ width:100%; vertical-align: middle;}
	.msgbox .td>i{font-size: 2.6em;}
	
	.ui_callout-danger .msgbox .td>i{ color:#d9534f;}
	.ui_callout-success .msgbox .td>i{ color:#5cb85c;}
</style>
</head>
<body>
<div class="container-fluid">
	<div class="ui_callout ui_callout-{switch name="$code"}{case value="1"}success{/case}{case value="0"}danger{/case}{/switch} fr_mt20">
		<div class="msgbox">
			<div class="td">
				<i class="fa fa-{switch name="$code"}{case value="1"}check-circle{/case}{case value="0"}exclamation-triangle{/case}{/switch} fr_mr20"></i>
			</div>
			<div class="td t">
				<h4 class="fr_ma0"><?php echo(strip_tags($msg));?></h4>
				<p class="text-muted fr_mb0 fr_mt10">页面将在 <b id="wait">{$wait}</b> 秒后自动跳转...<a id="href" href="{$url}">点击这里直接跳转</a>
			</div>
		</div>
	</div>
</div>
</body>
<script type="text/javascript">
	(function(){
		var wait = document.getElementById('wait'),
			href = document.getElementById('href').href;
		var interval = setInterval(function(){
			var time = --wait.innerHTML;
			if(time <= 0) {
				location.href = href;
				clearInterval(interval);
			};
		}, 1000);
	})();
</script>
</html>
