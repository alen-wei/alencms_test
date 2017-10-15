<?php
namespace app\Admin\controller;
class Admin extends \base\Admin{
	protected $module='Admin';
    static $adminInfo=[
        'name'=>'系统管理',
        'icon'=>'fa fa-cogs',
    ];
	static $ctrlLists=[
        'grouplists'=>[
			'id'=>1,
			'name'=>'管理组列表',
		],
        'groupshow'=>[
			'id'=>2,
			'name'=>'查看管理组',
		],
		'groupsave'=>[
			'id'=>3,
			'name'=>'修改管理组',
		],
		'groupadd'=>[
			'id'=>4,
			'name'=>'新建管理组',
		],
		'groupdel'=>[
			'id'=>5,
			'name'=>'删除管理组',
		],
		'config'=>[
			'id'=>6,
			'name'=>'系统设置',
		],
    ];
	static $adminNav=['grouplists','config'];
	
	
	protected function _config($request,$user){
		$staticData=['cache'=>[]];
		$extLists=['Redis','Memcache','Memcached'];
		foreach ($extLists as $v){
			$staticData['cache'][$v]=extension_loaded($v);
		}
		$extLists=['MongoDB'];
		$staticData['nosql']=[];
		foreach ($extLists as $v){
			$staticData['nosql'][$v]=extension_loaded($v);
		}
		$extLists=['FileInfo','curl','iconv','ZIP','PDO','OpenSSL'];
		$staticData['exts']=[];
		foreach ($extLists as $v){
			$staticData['exts'][$v]=extension_loaded($v);
		}
		$config=include CONFIG_PATH.'config.php';
		
		$dbArr=include CONFIG_PATH.'dbconfig.php';
		
		return [
			'staticData'=>$staticData,
			'config'=>$config,
			'dbArr'=>$dbArr,
		];
	}
	protected function _grouplists($request,$user){
		$this->page_title='管理组列表';
		$num=20;
        $p=$request->param('p');
        if(!$p)$p=1;
		
		$cond_search=$request->param('search');
		$cond_filter=$request->param('filter');
		
		$search=$cond_search;
		$filter=$cond_filter;
		
		$where=[];
		if($cond_filter){
            $tmp= explode('&',$cond_filter);
            foreach ($tmp as $v){
                $v= explode('=', $v);
                $where[$v[0]]=$v[1];
            }
        }
		if($cond_search)$where['name']=['like','%'.$cond_search.'%'];
		
		$event=controller($this->module.'/Auth', 'event');
		
		$count=$event->getGroupLists($where,true);
        $MaxP=ceil($count/$num);
        if($p>$MaxP)$p=$MaxP;
		$lists=$event->getGroupLists($where);
		
		$urlParam=get_url_param(null,['p'],true);
		$data=[
			'list'=>$lists,
			'cond'=>[
				'search'=>$cond_search,
				'filter'=>$cond_filter,
			],
			'count'=>$count,
			'urlParam'=>$urlParam,
			'page'=>[
                'num'=>$num,
                'max'=>$MaxP,
                'index'=>$p,
            ],
		];
		return $data;
	}
	protected function _groupshow($request,$user){
		$this->page_title='查看管理组';
		$id=$request->param('id');
		$type=$request->param('type');
		$type=$type?$type:'info';
		$event=controller($this->module.'/Auth', 'event');
		$group=$event->getGroup($id);
		if(!$group)return set_err_back($event->errCode,$this->module);
		$auth=$event->getGroupAuth($id);
		$tmp=config('app_id');
        $authList=[];
        foreach ($tmp as $k=>$v){
            $moduleName = '\\app\\'.$k.'\\controller\\Admin';
            if(!class_exists($moduleName))continue;
            if(!isset($moduleName::$adminInfo))continue;
			if(!isset($moduleName::$ctrlLists))continue;
            $tmpArr=$moduleName::$adminInfo;
			$tmpArr['id']=$v;
			$tmpArr['item']=$moduleName::$ctrlLists;
			$authList[$k]=$tmpArr;
        }
		$event=controller('User/Common', 'event');
		
		$num=20;
        $p=$request->param('p');
        if(!$p)$p=1;
		$count=$event->getLists(['admin'=>$id],'id',null,true);
        $MaxP=ceil($count/$num);
        if($p>$MaxP)$p=$MaxP;
		$userLists=$event->getLists(['admin'=>$id],'id,img,name,login_time','id DESC',$p.','.$num);
		$urlParam=get_url_param(null,['p'],true);
		return [
			'userLists'=>$userLists,
			'type'=>$type,
			'group'=>$group,
			'auth'=>$auth,
			'authList'=>$authList,
			'urlParam'=>$urlParam,
			'count'=>$count,
			'page'=>[
                'num'=>$num,
                'max'=>$MaxP,
                'index'=>$p,
            ],
		];
	}
	protected function _groupsave($request,$user){
		$postData=$request->param();
		$id=$postData['id'];
		$name=$postData['group_name'];
		$status=$postData['group_status'];
		unset($postData['id']);
		unset($postData['group_name']);
		unset($postData['group_status']);
		$appArr=config('app_id');
		$tmpOP=[];
		foreach ($postData as $k=>$v){
			if(!isset($appArr[$k]))return set_err_back('00010003',$this->module);
			if($v!=0 and $v!=''){
				$v=strint_to_arrint($v);
				$v=implode(',', $v);
				if(!$v)return set_err_back('00010003',$this->module);
			}
			$tmpOP[$appArr[$k]]=$v;
		}
		$event=controller($this->module.'/Auth', 'event');
		$back=$event->saveGroup([
			'name'=>$name,
			'status'=>$status,
		],$id);
		if(!$back)return set_err_back($event->errCode,$this->module);
		if($tmpOP){
			$back=$event->saveGroupAuth($tmpOP,$id);
			if(!$back)return set_err_back($event->errCode,$this->module);
		}
		$LogObj=controller('user/Log', 'event');
        $LogObj->add('groupsave',null,$user['id'],'',$id);
		return $back;
	}
	protected function _groupdel($request,$user){
		$ids=$request->param('ids');
		$url=verify_url_from($request->param('url'));
		$url=$url?$url:verify_url_from($request->server('HTTP_REFERER'));
		$event=controller($this->module.'/Auth', 'event');
		$back=$event->delGroup($ids);
		if($back===false)return set_err_back($event->errCode,$this->module);
        $LogObj=controller('User/Log', 'event');
        $LogObj->add('groupdel',null,$user['id'],'',$ids);
		$this->success(lang('Operation succeeded'), $url?$url:'lists');
		exit();
	}
	
	protected function _groupadd($request,$user){
		$postData=$request->post();
		if($postData){	//处理数据
			$name=$postData['group_name'];
			$status=$postData['group_status'];
			unset($postData['group_name']);
			unset($postData['group_status']);
			$appArr=config('app_id');
			$tmpOP=[];
			foreach ($postData as $k=>$v){
				if(!isset($appArr[$k]))return set_err_back('00010003',$this->module);
				if($v!=0 and $v!=''){
					$v=strint_to_arrint($v);
					$v=implode(',', $v);
					if(!$v)return set_err_back('00010003',$this->module);
				}
				$tmpOP[$appArr[$k]]=$v;
			}
			$event=controller($this->module.'/Auth', 'event');
			$id=$event->addGroup([
				'name'=>$name,
				'status'=>$status,
			]);
			if(!$id)return set_err_back($event->errCode,$this->module);
			$back=$event->saveGroupAuth($tmpOP,$id);
			if(!$back)return set_err_back($event->errCode,$this->module);
			$LogObj=controller('user/Log', 'event');
			$LogObj->add('groupadd',null,$user['id'],'',$id);
			return ['id'=>$id];
		}
		$this->page_title='新建用户';
		$this->Tpl='groupshow';
		$tmp=config('app_id');
        $authList=[];
        foreach ($tmp as $k=>$v){
            $moduleName = '\\app\\'.$k.'\\controller\\Admin';
            if(!class_exists($moduleName))continue;
            if(!isset($moduleName::$adminInfo))continue;
			if(!isset($moduleName::$ctrlLists))continue;
            $tmpArr=$moduleName::$adminInfo;
			$tmpArr['id']=$v;
			$tmpArr['item']=$moduleName::$ctrlLists;
			$authList[$k]=$tmpArr;
        }
		
		return [
			'type'=>'add',
			'authList'=>$authList,
		];
	}
}
