<?php
namespace app\User\event;

use think\Validate;

use app\User\model\userMain;
use app\User\model\userBindings;
use app\User\model\userInfo;

class Common{
	use \traits\controller\Jump;
    
	protected $module='User';
	public $errCode='';
	public $bindingsType=[
		'phone'=>1,
		'mail'=>2,
		'qq'=>3,
		'weixin'=>4,
	];
	public $bindingsVerify=[
		'phone'=>[
			'rule'=>['regex','^(13|15|18|17)[0-9]{9}$'],
			'err'=>'0023',
		],
		'mail'=>[
			'rule'=>'email',
			'err'=>'0025',
		],
		'qq'=>'',
		'weixin'=>'',
	];
	public $infoVerify=[
		'user'=>[
			'rule'=>['regex','^[A-Za-z][A-Za-z0-9\-\_]*$'],
			'err'=>'0026',
		],
		'name'=>[
			'rule'=>'chsDash',
			'err'=>'0027',
		],			
		'password'=>[
			'rule'=>['min',6],
			'err'=>'0028',
		],
		'sex'=>[
			'rule'=>['in',['0','1','2']],
			'err'=>'0029',
		],
		'img'=>'',
		'status'=>'',
	];
	//验证数据
	public function __verifyData($data,$type){
		$tmp=$type.'Verify';
		$verifyData=isset($this->$tmp)?$this->$tmp:$tmp;
		foreach ($data as $k=>$v){
			if(!isset($verifyData[$k]) or !$verifyData[$k])continue;
			$rule=$verifyData[$k]['rule'];
			if(is_array($rule)){
				$funName=$rule[0];
				if(Validate::$funName($v,$rule[1]))continue;
			}else{
				if(Validate::is($v,$rule))continue;
			}
			$this->errCode=$verifyData[$k]['err'];
			return 0;
		}
		return true;
	}
	#获取用户
	public function getUser($userStr,$isID=true,$status=1,$field=true){
		if(!$isID){
			$type=$this->verifyUserStr($userStr);
			if(!$type)return 0;
			if($type=='user'){
				$whereArr=['user'=>['=',$userStr]];
			}else{
				$back=$this->getBindings(['account'=>$userStr,'type'=>$this->bindingsType[$type]],false);
				if(!$back)return 0;
				$uid=$back->user_id;
			}
		}else{
			$uid=$userStr;
		}
		if(!isset($whereArr))$whereArr=['id'=>['=',$uid]];
		if($status!==false)$whereArr['status']=['=',$status];
		if(!$field)$field=true;
		$data=userMain::field($field)->where($whereArr)->find();
		if($data){
			return $data;
		}else{
			$this->errCode='0012';
			return 0;
		}
	}
	#验证登录
	public function verifyLogin($tourl=true){
		$tabName='user';
		$tmp=cache($tabName.'_all');
		$now=get_now_time();
		if(!$tmp){
			$this->redoLogin();
			return 0;
		}
		$senArr=session($tabName);
		$cokStr=cookie($tabName);
		if(!$cokStr and !$senArr)return 0;
		if($senArr){
			if($now-intval($senArr['time'])>config('login_Time'))$senArr='';
			if($senArr['time']<=$tmp['time'])return 0;
			$tmp=cache($tabName.'_'.$senArr['id']);
			if($tmp){
				if($senArr['time']<=$tmp['time']){
					if($tmp['txt'] and $tourl)$this->error(lang($tmp['txt']),'User/Html/login');
					return 0;
				}
			}
		}
		if($cokStr){
			$cokArr=explode('.',$cokStr);
			if(count($cokArr)!=2)$cokStr='';
			$cokArr[1]=intval($cokArr[1]);
			
			if($cokArr[1]<=$tmp['time'])return 0;
			
			if($now-intval($cokArr[1])>config('login_Time'))$cokStr='';
			$cokArr[0]=intval(str_decrypt($cokArr[0],$cokArr[1]));
			if($cokArr[0]<1)$cokStr='';
			$tmp=cache($tabName.'_'.$cokArr[0]);
			if($tmp){
				if($cokArr[1]<=$tmp['time']){
					if($tmp['txt'] and $tourl)$this->error(lang($tmp['txt']),'User/Html/login');
					return 0;
				}
			}
		}
		if(!$cokStr and !$senArr)return 0;
		$userData=$senArr?$senArr:false;
		if(!$userData and $senArr and $cokStr){
			if($cokArr[0]!=$senArr['id'])return 0;
		}
		if(!$userData and !$senArr and $cokStr){
			$userData=$this->getUser($cokArr[0]);
			if(!$userData)return 0;
		}
		if(!$this->setLogin($userData))return 0;
		return session($tabName);
	}
	#设置重新登录
	public function redoLogin($userID=0,$txt=''){
		$tabName='user';
		$now=get_now_time();
		$data=['time'=>$now];
		if($txt)$data['txt']=$txt;
		$userID=intval($userID);
		if($userID<1)$userID='all';
		cache($tabName.'_'.$userID,$data);
		return true;
	}
	#设置登录
	public function setLogin($userData){
		$tabName='user';
		$now=get_now_time();
		$tmp=cache($tabName.'_all');
		if(!$tmp)$this->redoLogin();
		cookie($tabName,str_encrypt($userData['id'],$now).'.'.$now);
		session($tabName,[
			'id'=>$userData['id'],
			'name'=>$userData['name'],
			'keys'=>$userData['keys'],
			'img'=>$userData['img'],
			'time'=>$now,
		]);
		return true;
	}
	#更新登录数据
	public function updateLogin(){
		$tabName='user';
		session($tabName,null);
		return true;
	}
	#退出登录
	public function logoutUser($log=true){
        if($log){
            $LogObj=controller($this->module.'/Log', 'event');
            $LogObj->add('logout');
        }
		$tabName='user';
		cookie($tabName,null);
		session($tabName,null);
		return true;
	}
	#登录日志
    public function loginLog($userData){
        userMain::where('id',$userData['id'])->update(['login_time'=> get_now_time()]);
        $LogObj=controller($this->module.'/Log', 'event');
        $LogObj->add('login',false,$userData['id']);
    }
    #登录
	public function loginUser($userStr,$password,$log=true,$setLogin=true){
		$userData=$this->getUser($userStr,false);
		if(!$userData){
			$this->errCode='0015';
			return 0;
		}
		if($password==$userData->password_text){
            if($setLogin){
                if(!$this->setLogin($userData))return 0;
            }
            if($log)$this->loginLog($userData);
			return $userData;
		}else{
			$this->errCode='0013';
			return 0;
		}
	}
	#添加用户
	public function addUser($data,$isUsername=false){
		if(!isset($data['password']) or !$data['password']){
			$this->errCode='0005';
			return 0;
		}
		if(!isset($data['user']) or !$data['name']){
			$this->errCode=$isUsername?'0030':'0001';
			return 0;
		}
		if(!isset($data['name']) or !$data['name']){
			$this->errCode='0006';
			return 0;
		}
		if($isUsername){
			$back=$this->verifyUser($data['user']);
			if(!$back)return 0;
		}else{
			$type=$this->verifyUserStr($data['user']);
			if(!$type)return 0;
			if($type=='user'){
				$back=$this->verifyUser($data['user']);
				if(!$back)return 0;
			}else{
				$back=$this->verifyBindings($data['user'],$type);
				if(!$back)return 0;
				$BindingsData=[
					'account'=>$data['user'],
					'type'=>$type,
				];
				unset($data['user']);
			}
		}
		if(!$this->__verifyData($data,'info'))return 0;
		
		$key=get_uid();
		$data['password']=str_encrypt($data['password'],$key);
		$data['keys']=$key;
		$db=new userMain;
		$back=$db->save($data);
		if(!$back){
			$this->errCode='0009';
			return 0;
		}
		$userId=$db->id;
		
		if(isset($data['img']) and $data['img']){
			$event=controller('File/Common', 'event');
			$event->applyFile($data['img'],$userId,$this->module,'userimg');
		}
		
		if(isset($BindingsData)){
			$BindingsData['userid']=$userId;
			$back=$this->addBindings($BindingsData['userid'],$BindingsData['account'],$BindingsData['type']);
			if($back){
				return $userId;
			}else{
				$this->errCode='0010';
				return 0;
			}
		}else{
			return $userId;
		}
		
		
	}
	
	#删除用户
	public function delUser($ids=''){
		$idArr=is_array($ids)?$ids:strint_to_arrint($ids);
		userMain::destroy($idArr);
		return true;
	}
	#获取绑定数据
	public function getBindings($where,$isID=true,$limit=1,$status=1){
		$whereArr=[];
		if($status!=false){
			$whereArr['status']=['=',$status];
		}
		if($isID){
			$whereArr['user_id']=['=',$where];
			$db=userBindings::where($whereArr);
		}else{
			$db=userBindings::where($whereArr);
			$db=$db->where($where);
		}
		if($limit==1){
			$data=$db->find();
		}else{
			$limit=intval($limit);
			if($limit)$db=$db->limit($limit);
			$data=$db->select();
		}
		if($data){
			return $data;
		}else{
			$this->errCode='0011';
			return 0;
		}
	}
	#添加绑定数据
	public function addBindings($userId,$account,$type,$status=1,$verify=true){
		if($verify){
			if(!$this->verifyBindings($account,$type))return 0;
		}else{
			if($account!=''){
				if(!$this->__verifyData([$type=>$account],'bindings'))return 0;
			}
		}
		$str=$account;
		$id=false;
		$data=[
			'user_id'=>$userId,
			'account'=>$account,
			'less'=>'',
			'type'=>$this->bindingsType[$type],
			'name'=>$account,
			'expire_time'=>0,
			'status'=>$status,
		];
		if($str){
			switch($type){
				case 'phone':
					$arr=str_split($str);
					for($i=3;$i<=6;$i++){$arr[$i]='*';}
					$str=implode('',$arr);
				break;
				case 'mail':
					$arr=explode('@',$str);
					$zs=strlen($arr[0]);
					$gs=intval($zs/2);
					$gs=$gs>5?5:$gs;
					$arr[0]=substr($arr[0],0,$zs-$gs);
					for($i=0;$i<$gs;$i++){$arr[0].='*';}
					$str=implode('@',$arr);
				break;
			}
		}
		$id=userBindings::get(['user_id'=>$userId,'type'=>$this->bindingsType[$type]]);
		if($id){$id=$id->id;}
		if($str)$data['name']=$str;
		//$db=new userBindings;
		if($id){
			$data['id']=$id;
			$back=userBindings::update($data);
		}else{
			$back=userBindings::create($data);
		}
		if($back){
			return true;
		}else{
			$this->errCode='0000';
			return 0;
		}
	}
	#验证绑定数据是否可用
	public function verifyBindings($account,$type){
		if(!$this->__verifyData([$type=>$account],'bindings'))return 0;
		$back=userBindings::get(['account' =>$account,'type'=>$this->bindingsType[$type],]);
		if($back){
			switch($type){
				case 'phone':
					$code='0003';
				break;
				case 'mail':
					$code='0004';
				break;
			}
			$this->errCode=$code?$code:'0000';
			return 0;
		}else{
			return true;
		}
	}
	#验证用户名是否可用
	public function verifyUser($user){
		if(!$this->__verifyData(['user'=>$user],'info'))return 0;
		$back=userMain::get(['user' =>$user,]);
		if($back){
			$this->errCode='0002';
			return 0;
		}else{
			return true;
		}
	}
	#判断字符串是哪种用户名
	public function verifyUserStr($userStr){
		
		if($this->__verifyData(['mail'=>$userStr],'bindings')){
			return 'mail';
		}
		if($this->__verifyData(['phone'=>$userStr],'bindings')){
			return 'phone';
		}
		if($this->__verifyData(['user'=>$userStr],'info')){
			return 'user';
		}
		$this->errCode='0007';
		return 0;
	}
	#获取用户信息
	public function getInfo($data,$userID){
		$infoField=userInfo::getTableInfo(userInfo::getTable(),'fields');
		unset($infoField[0]);
		$mainField=userMain::getTableInfo(userMain::getTable(),'fields');
		$field=[];
		$tab=[];
		foreach($data as $v){
			if(in_array($v,$mainField)){
				if(!in_array('userMain',$tab))$tab[]='userMain';
				$field[]='a.'.$v;
			}elseif(in_array($v,$infoField)){
				if(!in_array('userInfo',$tab))$tab[]='userInfo';
				$field[]='b.'.$v;
			}
		}
		if(in_array('userMain',$tab)){
			$tmp=userMain::field($field)->alias('a');
		}
		if(in_array('userInfo',$tab)){
			if(isset($tmp)){
				$tmp=$tmp->join('user_info b','a.id=b.id','LEFT');
			}else{
				$tmp=userInfo::field($field)->alias('b');
			}
		}
		$tmp=$tmp->where((count($tab)>1?'a.':'').'id='.$userID);
		return $tmp->find();
	}
	#验证用户信息是否可用
	public function verifyUserInfo($data){
		if(!$this->__verifyData($data,'info'))return 0;
		$backData=true;
		foreach($data as $k=>$v){
			switch($k){
				case 'name':  //昵称
					if(!$v){
						$this->errCode='0006';
						return 0;
					}
					$backData=1;
				break;
				case 'img': //头像
					$backData=1;
				break;
				case 'status': //状态
					$backData=1;
				break;
			
			}
		}
		return $backData;
	}
	
	#批量修改用户信息
	public function batchSaveInfo($data,$ids){
		$idArr=is_array($ids)?$ids:strint_to_arrint($ids);
		$upLogin=false;
		$tmp=$this->verifyUserInfo($data);
		if(!$tmp)return 0;
		if($tmp===1)$upLogin=true;
		
		$back=userMain::where('id','IN',$idArr)->update($data);
		
		if($back===false){
			$this->errCode='00010002';
			return 0;
		}
		if($upLogin){
			$actUserID=$this->verifyLogin(false);
			$actUserID=isset($actUserID['id'])?$actUserID['id']:0;
			if(in_array($actUserID,$idArr))$this->updateLogin();
		}
		return true;
	}
	
	#修改用户信息
	public function saveInfo($data,$userID){
		$infoField=userInfo::getTableInfo(userInfo::getTable(),'fields');
		unset($infoField[0]);
		$mainField=userMain::getTableInfo(userMain::getTable(),'fields');
		$dbObj=[
			'userInfo'=>userInfo::alias('a'),
			'userMain'=>userMain::alias('a'),
		];
		
		$upData=[];
		$upLogin=false;
		
		foreach($data as $k=>$v){
			if(in_array($k,$mainField)){
				if(!isset($upData['userMain']))$upData['userMain']=[];
				$upData['userMain'][$k]=$v;
			}elseif(in_array($k,$infoField)){
				if(!isset($upData['userInfo']))$upData['userInfo']=[];
				$upData['userInfo'][$k]=$v;
			}
		}
		
		foreach($upData as $k=>$v){
			$tmp=$this->verifyUserInfo($v);
			if(!$tmp)return 0;
			if($tmp===1)$upLogin=true;
			if($dbObj[$k]->where('id',$userID)->count()<1){
				if($k=='userMain'){
					$this->errCode='0012';
					return 0;
				}else{
					$v['id']=$userID;
					$back=$dbObj[$k]->insert($v);
				}
			}else{
				$back=$dbObj[$k]->where('id',$userID)->update($v);
			}
		}
		
		
		if(isset($upData['userMain']['img']) and $upData['userMain']['img']){
			$event=controller('File/Common', 'event');
			$event->applyFile($upData['userMain']['img'],$userID,$this->module,'userimg');
		}
		if($back===false){
			$this->errCode='00010002';
			return 0;
		}
		if($upLogin){
			$actUserID=$this->verifyLogin(false);
			$actUserID=isset($actUserID['id'])?$actUserID['id']:0;
			if($userID==$actUserID)$this->updateLogin();
		}
		return true;
	}
	#修改用户密码
	public function savePassword($pw,$userID){
		if(!$this->__verifyData(['password'=>$pw],'info'))return 0;
		$user=$this->getUser($userID);
		if(!$user){
			$this->errCode='0012';
			return 0;
		}
		$keys=$user->keys;
		if($user->password_text!=$pw){
			$newPw=str_encrypt($pw,$keys);
			$back=userMain::where('id',$userID)->update(['password'=>$newPw]);
			if(!$back){
				$this->errCode='00010002';
				return 0;
			}
			$this->redoLogin($userID,'upload password login');
		}
		return true;
	}
	#验证用户密码
	public function verifyPassword($pw,$userID){
		$back=userMain::field('password,keys')->find($userID);
		if(!$back){
			$this->errCode='0012';
			return 0;
		}
		return $pw==$back->password_text?true:false;
	}
    #获取用户列表
    public function getLists($wh=null,$tmp_field=true,$order=null,$page=null,$del=false){
		//$del=true;
        $bindingsFF=['phone','mail'];
        $buildSql=[];
        if($tmp_field===true or $tmp_field==='*'){
            $field=userMain::getTableInfo(userMain::getTable(),'fields');
            $field=array_merge($field,$bindingsFF);
        }elseif(!is_array($tmp_field)){
            $field=explode(',',$tmp_field);
        }
        $tmp=$del?userMain::withTrashed()->field($field):userMain::field($field);
		
        $whereArr=$wh?$wh:[];
		$whereFF=array_merge(get_where_field($whereArr),$field);
        foreach($bindingsFF as $ff){
            if(in_array($ff,$whereFF)){
                $bTpye=$this->bindingsType[$ff];
                $buildSql[$ff]=userBindings::where(['type'=>$bTpye])->field('user_id,account as '.$ff)->buildSql();
            } 
        }
		
        if($buildSql){
            $tmp->alias('a');
            foreach($buildSql as $k=>$v){
                $tmp->join([$v=>$k.'_tab'],'a.id='.$k.'_tab.user_id','LEFT');
            }
        }
        if($whereArr)$tmp=$tmp->where($whereArr);
        if($page){
            if($page===true){
                return $tmp->count();
            }else{
				if($order){
					$order=order_to_arr($order);
					$tmp=$tmp->order($order);
				}
                $tmp=$tmp->page($page);
            }
        }
        $data=$tmp->select();
        return $data;
    }
}