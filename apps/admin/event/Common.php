<?php
namespace app\Admin\event;

class Common {
	protected $module='Admin';
	public $errCode='';
    public $errText='';
	//验证后台登录
	public function verifyAdmin($ctrl_id=0,$module_id=0,$tourl=true){
        $event=controller('user/Common', 'event');
        $user=$event->verifyLogin(false);
        if(!$user){
            $this->logoutAdmin(false);
            return 0;
        }
        $tabName='admin';
		$now=get_now_time();
        $str=session($tabName)?session($tabName):cookie($tabName);
        $strArr=explode('.',$str);
        if(count($strArr)!=2)return 0;
		$strArr[1]=intval($strArr[1]);
        if($now-intval($strArr[1])>config('login_Time'))return 0;
        $strArr[0]=intval(str_decrypt($strArr[0],$strArr[1]));
        if($strArr[0]<1)return 0;
        if($strArr[0]!=$user['id']){
            $this->logoutAdmin(false);
            return 0;
        }
		$event=controller('User/Common', 'event');
		$user=$event->getUser($user['id']);
		
		$event=controller('Admin/Auth', 'event');
		$group=$event->getGroup($user->admin);
		if($group->status!=1){
			$this->logoutAdmin(false);
			return 0;
		}
		if(!$this->setAdmin($user))return 0;
        if($module_id){
			$Group=$event->getGroupAuth($user->admin);
			if(!isset($Group[$module_id]))return false;
			if($Group[$module_id]=='')return false;
			if($Group[$module_id]!=0 and $ctrl_id!=0){
				if(!in_array($ctrl_id,$Group[$module_id]))return false;
			}
        }
        return $user;
	}
    //设置后台登录
    public function setAdmin($userData){
        $tabName='admin';
		$now=get_now_time();
        cookie($tabName,str_encrypt($userData['id'],$now).'.'.$now);
        session($tabName,str_encrypt($userData['id'],$now).'.'.$now);
        return true;
    }
    //退出后台
    public function logoutAdmin($log=true){
        if($log){
            $LogObj=controller('user/Log', 'event');
            $LogObj->add('logout',false,false,'admin');
        }
		$tabName='admin';
		cookie($tabName,null);
		session($tabName,null);
		return true;
	}
    //登录后台
    public function loginAdmin($userStr,$password){
        $event=controller('user/Common', 'event');
        $userData=$event->loginUser($userStr,$password,false,false);
        if(!$userData){
			$this->errCode=strlen($event->errCode)==8?$event->errCode:(config('app_id.user')+1000).$event->errCode;
			return 0;
		}
        if($userData->admin<1){
            $this->errCode='0001';
            return 0;
        }
		$event=controller('Auth', 'event');
		$group=$event->getGroup($userData->admin);
		if($group->status!=1){
			$this->errCode='0003';
			return 0;
		}
		$event=controller('user/Common', 'event');
        $old_user=$event->verifyLogin(false);
        $now=get_now_time();
        if($old_user){
            if($old_user['id']!=$userData['id']){
                if(!$event->logoutUser())return 0;
                if(!$event->setLogin($userData))return 0;
                $event->loginLog($userData);
            }
        }else{
            if(!$event->setLogin($userData))return 0;
            $event->loginLog($userData);
        }
        if(!$this->setAdmin($userData))return 0;
        $LogObj=controller('user/Log', 'event');
        $LogObj->add('login',false,$userData['id'],'admin');
        return $userData;
	}
}