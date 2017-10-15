<?php
namespace app\User\controller;
use think\Validate;
use base\Ajax;
class Api extends Ajax{
	protected $module='User';
	#验证帐号是否可以用
	protected function _login($request){
		$captcha=$request->param('_verify');
		if(!captcha_check($captcha))return set_err_back('00010005',false);
		$userStr=$request->param('user');
		$password=$request->param('password');
		if($request->param('_encrypt'))$password=str_decrypt_easy($password);
		$event=controller('Common', 'event');
		$userData=$event->loginUser($userStr,$password);
		if(!$userData)return set_err_back($event->errCode,$this->module);
		$url=url('html/index');
		return ['_txt'=>$userData['name'].','.lang('come back'),'_url'=>$url];
	}
	#验证帐号是否可以用
	protected function _verifyUser($request){
		$userStr=$request->param('val');
		$event=controller('Common', 'event');
		$type=$event->verifyUserStr($userStr);
		if(!$type)return set_err_back($event->errCode,$this->module);
		if('user'==$type){
			$back=$event->verifyUser($userStr);
		}else{
			$back=$event->verifyBindings($userStr,$type);
		}
		if(!$back)return set_err_back($event->errCode,$this->module);
		return true;
	}
	#验证手机/邮箱验证码并注册用户
	protected function _signupVerify($request){
		$this->echoHtml=true;
		
		//return set_err_back('00020002',false);
		
		$key=$request->param('key');
		$token=$request->param('token');
		$userData=cache($key);
		if(!$userData)return set_err_back('00020002',false);
		$event=controller('Common', 'event');
		if($token){
			$type=$request->param('type');
			$userStr=$request->param('account');
			if($userStr!=$userData['user'])return set_err_back('00020002',false);
		}else{
			$code=$request->param('code');
			$type=$event->verifyUserStr($userData['user']);
		}
		$back=verify_data($userData['user'],$type,$token?$token:$code);
		if($back){
			$back=$event->addUser($userData);
			if($back){
				$back=$event->loginUser($userData['user'],$userData['password']);
				cache($key,NULL);
				$url=url('html/index');
				return ['_txt'=>$userData['name'].','.lang('thank join'),'_url'=>$url];
			}else{
				return set_err_back($event->errCode,$this->module);
			}
		}else{
			return set_err_back($token?'00020002':'00010005',false);
		}
	}
	#注册用户
	protected function _signup($request){
		$dataArr=[];
		$_verify=false;
		$userStr=$request->param('user');
		$cacheID=$request->param('key');
		if($cacheID){
			$userData=cache($cacheID);
			if(!$userData)return set_err_back('00020002',false);
			if($userData['user']==$userStr){
				$_verify=true;
			}else{
				return set_err_back('00020002',false);
			}
		}else{
			$captcha=$request->param('_verify');
			if(!captcha_check($captcha)){
				return set_err_back('00010005',false);
			}
		}
		if($_verify){
			$dataArr=$userData;
		}else{
			$dataArr['user']=$userStr;
			$dataArr['name']=$request->param('name');
			$dataArr['password']=$request->param('password');
			if($request->param('_encrypt'))$dataArr['password']=str_decrypt_easy($dataArr['password']);
			
			$cacheID=get_uid();
		}
		$event=controller('Common', 'event');
		$userType=$event->verifyUserStr($userStr);
		switch($userType){
			case 'phone':
			case 'mail':
				$back=$event->verifyBindings($userStr,$userType);
				if(!$back)return set_err_back($event->errCode,$this->module);
				$back=verify_data($userStr,$userType);
				if(!$back)return set_err_back('00010002',false);
				if('phone'==$userType){
					$smsObj=new \juhe\Sms();
					$back=$smsObj->send($userStr,'verify',['code'=>$back['code'],'hour'=>format_time_secs(config('token_Time'))]);
					$err=$back?false:$smsObj->getErrTxt();
				}else{
					$url=url('signupVerify','type='.$userType.'&key='.$cacheID.'&account='.$userStr.'&token='.$back['token'],'html',true);
					$ds=config('template.view_depr');
					$path=APP_PATH.strtolower($this->module).$ds.'view'.$ds.config('app_theme').$ds.config('default_lang').$ds.'tpl'.$ds;
					$view = new \think\View();
					$html=$view->fetch($path.'signupVerifyMaill.html',['code'=>$back['code'],'url'=>$url,'hour'=>format_time_secs(config('token_Time'))]);
					$mailData=[
						'title'=>lang('thank join'),
						'body'=>$html,
					];
					$back=send_mail($userStr,$mailData['title'],$mailData['body']);
					$err=$back===true?false:$back;
				}
				if($err)return ['_err'=>'00000000','_txt'=>$err];
				cache($cacheID,$dataArr,config('token_Time')+600);
				return ['type'=>'verify','key'=>$cacheID];
			case 'user':
			break;
			default:
				$data=set_err_back('0007',$this->module);
				return $data;
			break;
		}
		$back=$event->addUser($dataArr);
		if($back){
			$back=$event->loginUser($dataArr['user'],$dataArr['password']);
			$url=url('html/index');
			return ['_txt'=>$dataArr['name'].','.lang('thank join'),'_url'=>$url];
		}else{
			return set_err_back($event->errCode,$this->module);
		}
	}
	#退出登录
	protected function _logout($request){
		$this->echoHtml=true;
		$event=controller('Common', 'event');
		if(!$event->logoutUser())return set_err_back($event->errCode,$this->module);
		$url=$request->param('url');
		$url=$url?$url:$request->server('HTTP_REFERER');
		$url=$url?$url:url('html/login');
		return ['_txt'=>lang('bye bye'),'_url'=>$url];
	}
	#修改用户信息
	protected function _saveInfo($request){
		$event=controller('Common', 'event');
		$user=$event->verifyLogin();
		if(!$user)return set_err_back('00020004',false);
		$op=[];
		if($request->param('name'))$op['name']=$request->param('name');
		if($request->param('img'))$op['img']=$request->param('img');
		
		$back=$event->saveInfo($op,$user['id']);
		if(!$back)return set_err_back($event->errCode,$this->module);
		return $user['id'];
	}
	#修改密码
	protected function _uploadPassword($request){
		$event=controller('Common', 'event');
		$user=$event->verifyLogin();
		if(!$user)return set_err_back('00020004',false);
		
		$old=$request->param('old');
		$new=$request->param('new');
		$repeat=$request->param('repeat');
		if(!$old)return set_err_back('0016',$this->module);
		if(!$new)return set_err_back('0017',$this->module);
		if($old==$new)return set_err_back('0018',$this->module);
		if(!$repeat)return set_err_back('0019',$this->module);
		if($new!=$repeat)return set_err_back('0020',$this->module);
		if($request->param('_encrypt')){
			$old=str_decrypt_easy($old);
			$new=str_decrypt_easy($new);
			$repeat=str_decrypt_easy($repeat);
		}
		if(!$event->verifyPassword($old,$user['id']))return set_err_back('0021',$this->module);
		$back=$event->savePassword($new,$user['id']);
		if(!$back)return set_err_back($event->errCode,$this->module);
		return $user['id'];
	}
	#绑定数据
	protected function _saveBinding($request){
		$code=$request->param('code');
		$noLogin=false;
		$event=controller('Common', 'event');
		if($code and !\think\Validate::is($code,'number'))$noLogin=true;
		if(!$noLogin){
			$user=$event->verifyLogin();
			if(!$user)return set_err_back('00020004',false);
		}
		
		$userID=$request->param('id');
		$userID=$userID?str_decrypt($userID):$user['id'];
		
		$type=$request->param('type');
		if(!$type)return set_err_back('00010003',false);
		
		if($code){ //验证code
			$account=$request->param('account');
			$back=verify_data($account,$type,$code);
			if($back){
				$back=$event->addBindings($userID,$account,$type);
				if(!$back)return set_err_back($event->errCode,$this->module);
				return $account;
			}else{
				return set_err_back('00010005',false);
			}
		}
		$token=$request->param('token');
		$now=get_now_time();
		if($token){  //重发
			$arr=str_decrypt($token);
			$arr=explode('|',$arr);
			if($now-intval($arr[0])>config('token_Time'))return set_err_back('00020002',false);
			$new=$arr[1];
		}else{  //验证数据
			$captcha=$request->param('_verify');
			if(!captcha_check($captcha))return set_err_back('00010005',false);
			
			$old=$request->param('old');
			$new=$request->param('new');
			
			$back=$event->getBindings(['user_id'=>$userID,'type'=>$event->bindingsType[$type]],false,1);
			if($back){
				if($back->account!=$old)return set_err_back('0022',$this->module);
				if($old==$new)return set_err_back('0024',$this->module);
			}
			
			if('phone'==$type){
				if(!\think\Validate::regex($new,'^(13|15|18|17)[0-9]{9}$'))return set_err_back('0023',$this->module);
			}elseif('mail'==$type){
				if(!\think\Validate::is($new,'email'))return set_err_back('0025',$this->module);
			}
			
			$back=$event->verifyBindings($new,$type);
			if(!$back)return set_err_back($event->errCode,$this->module);
		}
		$back=verify_data($new,$type);
		if('phone'==$type){
			$smsObj=new \juhe\Sms();
			$back=$smsObj->send($new,'verify',['code'=>$back['code'],'hour'=>format_time_secs(config('token_Time'))]);
			$err=$back?false:$smsObj->getErrTxt();
		}elseif('mail'==$type){
			$url=url('saveBinding','type='.$type.'&id='.str_encrypt($userID).'&account='.$new.'&code='.$back['token'],'html',true);
			$ds=config('template.view_depr');
			$path=APP_PATH.strtolower($this->module).$ds.'view'.$ds.config('app_theme').$ds.config('default_lang').$ds.'tpl'.$ds;
			$view = new \think\View();
			$html=$view->fetch($path.'uploadVerifyMaill.html',['code'=>$back['code'],'url'=>$url,'hour'=>format_time_secs(config('token_Time'))]);
			$mailData=[
				'title'=>lang('thank trust'),
				'body'=>$html,
			];
			$back=send_mail($new,$mailData['title'],$mailData['body']);
			$err=$back===true?false:$back;
		}
		$token=str_encrypt($now.'|'.$new);
		return $err?['_err'=>'00000000','_txt'=>$err]:['account'=>$new,'token'=>$token];
	}
}