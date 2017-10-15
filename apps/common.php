<?php
//当前时间函数
function get_now_time($format='',$times=''){
	$timemod=0;
	if(!$times)$times=time()+$timemod*3600;
	return $format?date($format,$times):$times;
}
//转换时间函数
function txt_to_time($t_date){
        $year=((int)substr($t_date,0,4));//取得年份
        $month=((int)substr($t_date,5,2));//取得月份
        $day=((int)substr($t_date,8,2));//取得几号
        $hh=((int)substr($t_date,11,2));//取得小时
        $mm=((int)substr($t_date,14,2));//取得分
        $ss=((int)substr($t_date,17,2));//取得秒
        return mktime($hh,$mm,$ss,$month,$day,$year);
}
//判断手机
function is_user_mobile() {
	$user_agent = $_SERVER['HTTP_USER_AGENT'];
	$mobile_agents = Array("240x320","acer","acoon","acs-","abacho","ahong","airness","alcatel","amoi","android","anywhereyougo.com","applewebkit/525","applewebkit/532","asus","audio","au-mic","avantogo","becker","benq","bilbo","bird","blackberry","blazer","bleu","cdm-","compal","coolpad","danger","dbtel","dopod","elaine","eric","etouch","fly ","fly_","fly-","go.web","goodaccess","gradiente","grundig","haier","hedy","hitachi","htc","huawei","hutchison","inno","ipad","ipaq","ipod","jbrowser","kddi","kgt","kwc","lenovo","lg ","lg2","lg3","lg4","lg5","lg7","lg8","lg9","lg-","lge-","lge9","longcos","maemo","mercator","meridian","micromax","midp","mini","mitsu","mmm","mmp","mobi","mot-","moto","nec-","netfront","newgen","nexian","nf-browser","nintendo","nitro","nokia","nook","novarra","obigo","palm","panasonic","pantech","philips","phone","pg-","playstation","pocket","pt-","qc-","qtek","rover","sagem","sama","samu","sanyo","samsung","sch-","scooter","sec-","sendo","sgh-","sharp","siemens","sie-","softbank","sony","spice","sprint","spv","symbian","tablet","talkabout","tcl-","teleca","telit","tianyu","tim-","toshiba","tsm","up.browser","utec","utstar","verykool","virgin","vk-","voda","voxtel","vx","wap","wellco","wig browser","wii","windows ce","wireless","xda","xde","zte");
	$is_mobile = false;
	foreach($mobile_agents as $device) {
		if(stristr($user_agent, $device)) {
			$is_mobile = true;
			break;
		}
	}
	return $is_mobile;
}
//获取系统
function get_user_os(){
	if(!empty($_SERVER['HTTP_USER_AGENT'])){
		$agent = $_SERVER['HTTP_USER_AGENT'];
		$type = 'other';
		if(is_user_mobile()){
			if(stristr($agent, 'iphone') || stristr($agent, 'ipad') || stristr($agent, 'ipod')){
				$type = 'ios';
			}elseif(stristr($agent, 'android')){
				$type = 'android';
			}
		}else{
			if(stristr($agent, 'win')){
				$type = 'win';
			}elseif(stristr($agent, 'mac')){
				$type = 'mac';
			}
		}
		return $type;
	}else{
		return "unknow";
	} 
}
//获取浏览器
function get_user_browser(){
	if(!empty($_SERVER['HTTP_USER_AGENT'])){
		$br = $_SERVER['HTTP_USER_AGENT'];
		if(strpos($br, 'MicroMessenger')!==false){
			return 'weixin';
		}else{		
			if (preg_match('/MSIE/i',$br)) {    
				$br = 'msie';
			}
			elseif (preg_match('/Firefox/i',$br)) {
				$br = 'firefox';
			}
			elseif (preg_match('/Chrome/i',$br)) {
				$br = 'chrome';
			}
			elseif (preg_match('/Safari/i',$br)) {
				$br = 'safari';
			}
			elseif (preg_match('/Opera/i',$br)) {
				$br = 'opera';
			}else {
				$br = 'other';
			}
			return $br;
		}
	}
	else{
		return "unknow";
	} 
}
//获取日志文字
function get_log_txt($type,$info=null,$less=null,$module_id=null){
	$thisModule=strtolower(request()->module());
	if($module_id){
		$module=array_search($module_id,config('app_id'));
		if(!$module)return '';
	}else{
		$module=$thisModule;
	}
	$str='log|'.$type;
	if($info)$str.='|'.$info;
	if($less)$str.='|'.$less;
	
	if($module==$thisModule)return lang($str);
	
	$file=APP_PATH.strtolower($module).DS.'lang'.DS.config('default_lang').'.php';
	$arr=include($file);
	return isset($arr[$str])?$arr[$str]:'';
}
//获取错误详细信息
function get_err_data($code){
    $module=(int)substr($code,0,4);
    if($module<1000){
        $file=FRAME_PATH.'alen/lang/'.config('default_lang').'/err/'.$module.'.php';
        $id=(int)substr($code,4);
    }else{
        $module-=1000;
        $tmp=config('app_id');
        $kk=array_search($module,$tmp);
        $file=APP_PATH.strtolower($kk).DS.'lang'.DS.config('default_lang').'.php';
        $id='err'.substr($code,4);
    }
    if(file_exists($file)){
        $arr=include($file);
        $str=isset($arr[$id])?$arr[$id]:0;
    }else{
        $str=false;
    }
    return $str;
}
//数字串转数组
function strint_to_arrint($str,$ds=','){
	$arr=str_to_arr($str,$ds=',');
	if($arr===false)return false;
	foreach($arr as $k=>$v){$arr[$k]=intval($v);}
	$arr=array_unique(array_filter($arr));
	return $arr?$arr:false;
}
function str_to_arr($str,$ds=','){
	if(is_array($str)){
		$arr=$str;
	}else{
		$arr=strpos($str,$ds)===false?(array)$str:explode($ds,$str);
	}
	$arr=array_unique(array_filter($arr));
	return $arr?$arr:false;
}
//日期字符处理
function str_to_date($str){
	$time=get_now_time();
	$arr=[
		'd'=>'days',
		'w'=>'week',
		'm'=>'months',
		'y'=>'years',
		'h'=>'hours',
		'i'=>'minutes',
	];
	$dw=substr($str, -1);

	$sz=(float)substr($str, 0 , -1);
	if($dw=='d' or $dw=='w' or $dw=='m' or $dw=='y'){
		$sz-=1;
		if($dw=='m'){
			$time=txt_to_time(date('Y-m-01 H:i:s',$time));
		}elseif($dw=='y'){
			$time=txt_to_time(date('Y-01-01 H:i:s',$time));
		}elseif($dw=='w'){
			$w=date('w',$time);
			if($w==0)$w=7;
			$time=strtotime('-'.($w-1).' days',$time);
		}
	}
	$tmp=strtotime('-'.$sz.' '.$arr[$dw],$time);
	$tmp=txt_to_time(date('Y-m-d',$tmp));
	//return date('Y-m-d H:i:s',$tmp);
	return $tmp;
}
//设置错误返回信息
function set_err_back($code,$module){
    if(strlen($code)==8){
        $data=[
            '_err'=>$code,
            '_txt'=>get_err_data($code),
        ];
    }else{
        $data=['_err'=>(config('app_id.'.strtolower($module))+1000).$code];
        if(strtolower($module)==strtolower(request()->module())){
            $data['_txt']=lang('err'.$code);
        }else{
            $file=APP_PATH.strtolower($module).DS.'lang'.DS.config('default_lang').'.php';
            $errData=include($file);
            $data['_txt']=$errData['err'.$code];
        }
    }
    return $data;
}
//时间字符串去0
function get_time_str($times=0,$ds='-'){
	$times=$times?is_numeric($times)?(int)$times:strtotime($times):get_now_time();
	$tt=array(
		0=>date('Y',$times),
		1=>intval(date('m',$times)),
		2=>intval(date('d',$times)),
	);
	return implode($ds,$tt);
}

//order转换为数组格式
function order_to_arr($order){
	if(is_array($order))return $order;
	$tmp= explode(',', $order);
	$orderArr=[];
	foreach ($tmp as $v){
		$v=array_unique(array_filter(explode(' ',trim($v))));
		$ff=array_shift($v);
		$vv=array_shift($v);
		$orderArr[$ff]=$vv;
	}
	return $orderArr;
}
//获取whereArr中的用到的字段
function get_where_field($whereArr){
	$ff=[];
	foreach ($whereArr as $k=>$v){
		if(false===strpos($k,'|')){
			$ff[]=$k;
			continue;
		}
		$k=array_unique(array_filter(explode('|', $k)));
		foreach ($k as $vv){
			if(false===strpos($vv,'&')){
				$ff[]=trim($vv);
				continue;
			}
			$vv=array_unique(array_filter(explode('&', $vv)));
			foreach ($vv as $kk){
				$ff[]=trim($kk);
			}

		}
	}
	return $ff;
}		
//获取文件大小
function get_file_size($size){
	$size=$size?$size:0;
	$dwi=0;
	$dw=array('B','K','M','G','T');
	$zh=strtoupper(substr($size,-1));
	if(is_numeric($zh)){
		$size=intval($size);
		while($size>1024 and $dwi<count($dw)){
			$size=$size/1024;
			$dwi++;
		}
		return round($size,2).$dw[$dwi];
	}else{
		$size=floatval(substr($size,0,-1));
		while($zh!=$dw[$dwi] and $dwi<count($dw)){
			$size=$size*1024;
			$dwi++;
		}
		return $dwi<count($dw)?$size:false;
	}
}
//时间转换
function format_time_secs($times){
	$arr=[0,60,60,24];
	$dw=['秒','分','小时','天'];
	//var dw=['s','m','h','d'];
	$times=intval($times);
	$str='';
	$i=0;
	do{
		$sdw=$dw[$i];
		$i++;
		if(isset($dw[$i]) and $times>=$arr[$i]){
			$nn=$times%$arr[$i];
			if($nn>0 or $str)$str=$nn.$sdw.$str;
			$times=intval($times/$arr[$i]);
		}else{
			$str=$times.$sdw.$str;
			$times=0;
		}
	}while(isset($dw[$i]) && $times>0);
	return $str;
}

//获取远程数据
function get_url_file($url,$postData=false,$rUrl=false,$ip=false){
	$header=[];
	//$header=['Content-Type: text/json','X-Requested-With:XMLHttpRequest'];
	if($ip)array_push($header,'CLIENT-IP:'.$ip,'X-FORWARDED-FOR:'.$ip);
	#初始化一个 cURL 对象
	$curl = curl_init();
	#设置你需要抓取的URL
	curl_setopt($curl, CURLOPT_URL, $url);
	#发送文件头
	if($header)curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
	#是否将头文件的信息作为数据流输出
	curl_setopt($curl, CURLOPT_HEADER, 0);
	#是否将内容保存到字符串中
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
	#构造来路 
	if($rUrl)curl_setopt($curl, CURLOPT_REFERER, $rUrl);
	if($postData){
		#启用时会发送一个常规的POST请求，类型为：application/x-www-form-urlencoded，就像表单提交的一样。
		curl_setopt ( $curl, CURLOPT_POST, 1 );
		#全部数据使用HTTP协议中的"POST"操作来发送。要发送文件，在文件名前面加上@前缀并使用完整路径。这个参数可以通过urlencoded后的字符串类似'para1=val1&para2=val2&...'或使用一个以字段名为键值，字段数据为值的数组
		/*坑：
		postData是数组时Content-Type为application/x-www-form-urlencoded
		postData是字符时Content-Type为multipart/form-data
		*/
		curl_setopt ( $curl, CURLOPT_POSTFIELDS,$postData);
	}
	#运行cURL，请求网页
	$data = curl_exec($curl);
	#关闭URL请求
	curl_close($curl);
	return $data;
	//$res=file_get_contents($url);
    //return $res;
}
//读取文件
function get_temp_file($filename,$cache=true){
	$path=$cache?CACHE_PATH.$filename:$filename;
	return json_decode(trim(substr(file_get_contents($path), 15)),true);
}
//写入文件
function set_temp_file($filename, $content,$cache=true){
	$path=$cache?CACHE_PATH.$filename:$filename;
	$fp = fopen($path,"w");
	fwrite($fp, "<?php exit();?>".json_encode($content,JSON_UNESCAPED_UNICODE));
	fclose($fp);
}
//新建文件夹
function add_file_dir($path,$mode=0777){
   if(!file_exists($path)){
	   add_file_dir(dirname($path), $mode);
	   mkdir($path,$mode);
   }
}
//简单字符串解密
function str_decrypt_easy($str){
	$maxs=65535;
	$strArr=explode('A',$str);
	$key=$strArr[0]/2;
	unset($strArr[0]);
	$mmStr='';
	foreach($strArr as $v){
		$v-=$key;
		if($v<0)$v+=$maxs;
		$mmStr.=chr($v);
	}
	return $mmStr;
}

//加密算法
function str_encrypt($str,$key='',$expiry=0){
	$str=str_replace('/','-',str_authcode($str,1,$key,$expiry));
	$str=str_replace('+','_',$str);
	return urlencode($str);
}
//解密算法
function str_decrypt($str,$key=''){
	$str=str_replace('-','/',rawurldecode($str));
	$str=str_replace('_','+',$str);
	return str_authcode($str,'DECODE',$key);
}
// $string： 明文 或 密文  
// $operation：DECODE表示解密,其它表示加密  
// $key： 密匙  
// $expiry：密文有效期  
function str_authcode($string, $operation = 'DECODE', $key = '', $expiry = 0) {  
	// 动态密匙长度，相同的明文会生成不同密文就是依靠动态密匙  
	$ckey_length = 4;  
	   
	// 密匙  
	$key = md5($key ? $key : config('sys_keys'));  
	   
	// 密匙a会参与加解密  
	$keya = md5(substr($key, 0, 16));  
	// 密匙b会用来做数据完整性验证  
	$keyb = md5(substr($key, 16, 16));  
	// 密匙c用于变化生成的密文  
	$keyc = $ckey_length ? ($operation == 'DECODE' ? substr($string, 0, $ckey_length): substr(md5(microtime()), -$ckey_length)) : '';  
	// 参与运算的密匙  
	$cryptkey = $keya.md5($keya.$keyc);  
	$key_length = strlen($cryptkey);  
	// 明文，前10位用来保存时间戳，解密时验证数据有效性，10到26位用来保存$keyb(密匙b)，解密时会通过这个密匙验证数据完整性  
	// 如果是解码的话，会从第$ckey_length位开始，因为密文前$ckey_length位保存 动态密匙，以保证解密正确  
	$string = $operation == 'DECODE' ? base64_decode(substr($string, $ckey_length)) : sprintf('%010d', $expiry ? $expiry + time() : 0).substr(md5($string.$keyb), 0, 16).$string;  
	$string_length = strlen($string);  
	$result = '';
	$box = range(0, 255);  
	$rndkey = array();  
	// 产生密匙簿  
	for($i = 0; $i <= 255; $i++) {  
		$rndkey[$i] = ord($cryptkey[$i % $key_length]);  
	}  
	// 用固定的算法，打乱密匙簿，增加随机性，好像很复杂，实际上对并不会增加密文的强度  
	for($j = $i = 0; $i < 256; $i++) {  
		$j = ($j + $box[$i] + $rndkey[$i]) % 256;  
		$tmp = $box[$i];  
		$box[$i] = $box[$j];  
		$box[$j] = $tmp;  
	}  
	// 核心加解密部分  
	for($a = $j = $i = 0; $i < $string_length; $i++) {  
		$a = ($a + 1) % 256;  
		$j = ($j + $box[$a]) % 256;  
		$tmp = $box[$a];  
		$box[$a] = $box[$j];  
		$box[$j] = $tmp;  
		// 从密匙簿得出密匙进行异或，再转成字符  
		$result .= chr(ord($string[$i]) ^ ($box[($box[$a] + $box[$j]) % 256]));  
	}  
	if($operation == 'DECODE') {  
		// substr($result, 0, 10) == 0 验证数据有效性  
		// substr($result, 0, 10) - time() > 0 验证数据有效性  
		// substr($result, 10, 16) == substr(md5(substr($result, 26).$keyb), 0, 16) 验证数据完整性  
		// 验证数据有效性，请看未加密明文的格式  
		if((substr($result, 0, 10) == 0 || substr($result, 0, 10) - time() > 0) && substr($result, 10, 16) == substr(md5(substr($result, 26).$keyb), 0, 16)) {  
			return substr($result, 26);  
		} else {  
			return '';  
		}  
	} else {  
		// 把动态密匙保存在密文里，这也是为什么同样的明文，生产不同密文后能解密的原因  
		// 因为加密后的密文可能是一些特殊字符，复制过程可能会丢失，所以用base64编码  
		return $keyc.str_replace('=', '', base64_encode($result));  
	} 
}
//发邮件
function send_mail($email,$title='',$body='',$name=''){
	$mail = new \mailer\PHPMailer;
	
	$tpl=config('public_path').'template'.DS.'mail.html';
	$view = new \think\View();
	$html=$view->fetch($tpl,['title'=>$title,'html'=>$body]);
	
	
	$config=config('mail');
	$mail->CharSet = 'utf-8';//设定邮件编码，默认ISO-8859-1，如果发中文此项必须设置，否则乱码，
	$mail->IsSMTP();//设定使用SMTP服务
	$mail->SMTPSecure = $config['secure'];; 
	$mail->SMTPDebug = 0;//关闭SMTP调试功能//1=errors and messages//2=messages only
	$mail->SMTPAuth  = true;//启用SMTP验证功能
	$mail->Port      = $config['port'];  // SMTP服务器的端口号
	$mail->Host      = $config['smtp'];
	$mail->Body      = $html; //设置邮件正文
	$mail->From      = $config['address'];//设置发件邮箱
	$mail->FromName  = $name?$name:config('sys_name');//设置发件人名字
	$mail->Subject   = $title;//设置邮件标题
	$mail->Username  = $config['loginname'];//设置用户名和密码
	$mail->Password  = $config['password'];
	
	$mail->isHTML(true); 
	$mail->AddAddress($email);//添加收件人地址
	
	return $mail->Send()?true:$mail->ErrorInfo;
}
//获取唯一的id
function get_uid($guid=false){
	return $guid?get_guid():strtoupper(substr(md5(uniqid(rand(),true)),8,16));
}
//获取全局唯一的id
function get_guid($namespace='',$split='-'){
	static $guid = '';
	$uid =uniqid("", true);
	$data=$namespace;
	$data.=@$_SERVER['REQUEST_TIME'];
	$data.=@$_SERVER['HTTP_USER_AGENT'];
	$data.=@$_SERVER['LOCAL_ADDR'];
	$data.=@$_SERVER['LOCAL_PORT'];
	$data.=@$_SERVER['REMOTE_ADDR'];
	$data.=@$_SERVER['REMOTE_PORT'];
	$hash=strtoupper(hash('ripemd128', $uid . $guid . md5($data)));
	$guid=substr($hash,0,8).$split.substr($hash,8,4).$split.substr($hash,12,4).$split.substr($hash,16,4).$split.substr($hash,20,12);
	return $guid;
}

//获取用户头像
function get_user_img($img='',$thumb=''){
	$d_url=$img?get_static_img($img,$thumb):config('static_url').config('skin_dirname').'/user/'.config('app_theme').'/'.config('default_lang').'/img/user.jpg';
	return $d_url;
}
//获取图片
function get_static_img($img='',$thumb=''){
	$thumbArr=config('thumb_wh');
	if(!isset($thumbArr[$thumb]))$thumb='';
	$url=$img;
	if($thumb){
		$tmpArr= explode('/',$img);
		$upPath=\app\File\event\Common::$upPath;
		$thumbPath=\app\File\event\Common::$thumbPath;
		if($tmpArr[0]==$upPath){
			$tmpArr[0]=$thumb;
			$tmpArr= implode('/', $tmpArr);
			$url=$thumbPath.'/'.$tmpArr;
		}
	}
	return config('static_url').$url;
}
function get_static_file($url){
	return config('static_url').$url;
}

//验证url来源
function verify_url_from($url){
	if(!$url)return false;
	return $url;
}
//验证
function verify_data($account,$type,$verify=''){
	if(!$type or !$account)return 0;
	$tokenTime=config('token_Time');
	$prefix='verifyData_'.md5($account).'_';
	if($verify){
		if(\think\Validate::is($verify,'number')){  //code
			if($verify.':'.$account==cache($prefix.$type)){
				cache($prefix.$type, null);
				return true;
			}else{
				return 0;
			}
		}else{  //token
			$time=get_now_time();
			$data=str_decrypt($verify);
			if(!$data)return 0;
			$data=json_decode($data,true);
			$data['str']=str_decrypt($data['str'],$data['time']);
			if($time-$data['time']<=$tokenTime and $data['str']==$account and $data['type']==$type and $data['code'].':'.$account==cache($prefix.$type)){
				cache($prefix.$type, null);
				return true;
			}else{
				return 0;
			}
		}
	}else{
		$code=rand(100000,999999);
		$token=[
			'time'=>get_now_time(),
			'type'=>$type,
			'str'=>$account,
			'code'=>$code,
		];
		$token['str']=str_encrypt($token['str'],$token['time']);
		$token=str_encrypt(json_encode($token));
		cache($prefix.$type,$code.':'.$account,$tokenTime+600);
		$data=[
			'code'=>$code,
			'token'=>$token,
		];
		return $data;
	}
}

//获取当前页面url
function get_this_url(){
	$pageURL = 'http';
	if($_SERVER["HTTPS"] == "on")$pageURL .= "s";
	$pageURL .= "://";
	$pageURL .= $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"];
	return $pageURL;
}
//获取url中的参数
function get_url_param($url=null,$omit=[],$toStr=false){
	if(!$url)$url=get_this_url();
	$tmp= explode('?', $url);
	if(count($tmp)<=1 or !$tmp[1])return $toStr?'':[];
	//$l_url=$tmp[0];
	$tmp=explode('&', $tmp[1]);
	$getArr=[];
	$str='';
	foreach ($tmp as $v){
		$v=explode('=', $v);
		if(in_array($v[0], $omit))continue;
		$getArr[]=[$v[0]=>$v[1]];
		$str.=$str?'&':'?';
		$str.=$v[0].'='.$v[1];
	}
	return $toStr?$str:$getArr;
}

function get_status_txt($val=false){
    $statusArr = [1=>lang('normal'),0=>lang('disable'),2=>lang('to be verified')];
    return $val===false?$statusArr:$statusArr[$val];
}
//获取翻页html
function get_page_html($index,$max,$num=5,$uri=''){
    if($max<=1)return '';
    $yb=($num-1)/2;
    $i=0;
    $max_s=$min_s=$index;
    $num-=1;
    for($i=1;$i<=floor($yb);$i++){
        if($min_s<=1)break;
        $min_s=$index-$i;
        $num--;
    }
    $i=0;
    while($num>0 and $max_s<$max){
        $i++;
        $max_s=$index+$i;
        $num--;
    }
    $uri.=$uri?'&':'?';
    $html='<ul class="pagination">';
    $html.='<li'.($index<=1?' class="disabled"':'').'><a href="'.($index<=1?'javascript:void(0)':$uri.'p='.($index-1)).'">&#8249;</a></li>';
    if($min_s>1)$html.='<li><a href="'.$uri.'p=1">1</a></li>';
    if($min_s>2)$html.='<li class="omit"><span>...</span></li>';
    for($i=$min_s;$i<=$max_s;$i++){
        $html.='<li'.($i==$index?' class="active"':'').'><a href="'.($i==$index?'javascript:void(0)':$uri.'p='.$i).'">'.$i.'</a></li>';
    }
    if($max_s<$max-1)$html.='<li class="omit"><span>...</span></li>';
    if($max_s<$max)$html.='<li><a href="'.$uri.'p='.$max.'">'.$max.'</a></li>';
    $html.='<li'.($index>=$max?' class="disabled"':'').'><a href="'.($index>=$max?'javascript:void(0)':$uri.'p='.($index+1)).'">&#8250;</a></li>';
    $html.='</ul>';
    return $html;
}
function arr_dyadic_multi($val,&$arr,$ds='-'){
	if(is_array($ds)){
		$k=array_shift($ds);
		if(count($ds)>0){
			if(!isset($arr[$k]))$arr[$k]=[];
			arr_dyadic_multi($val,$arr[$k],$ds);
		}else{
			$arr[$k]=$val;
		}
	}else{
		foreach($val as $k=>$v){
			$k=explode($ds, $k);
			arr_dyadic_multi($v,$arr,(array)$k);
		}
	}
}