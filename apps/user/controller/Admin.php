<?php
namespace app\User\controller;
class Admin extends \base\Admin{
	protected $module='User';
    static $adminInfo=[
        'name'=>'用户中心',
        'icon'=>'fa fa-user-circle',
    ];
	static $ctrlLists=[
        'lists'=>[
			'id'=>1,
			'name'=>'用户列表',
		],
        'show'=>[
			'id'=>2,
			'name'=>'查看用户',
		],
		'save'=>[
			'id'=>3,
			'name'=>'修改用户',
		],
		'add'=>[
			'id'=>4,
			'name'=>'新建用户',
		],
		'del'=>[
			'id'=>5,
			'name'=>'删除用户',
		],
		'log'=>[
			'id'=>6,
			'name'=>'查看日志',
		],
		'dellog'=>[
			'id'=>6,
			'name'=>'删除日志',
		],
		'noticelists'=>[
			'id'=>7,
			'name'=>'公告列表',
		],
		'noticeadd'=>[
			'id'=>8,
			'name'=>'发布公告',
		],
		'noticesave'=>[
			'id'=>9,
			'name'=>'修改公告',
		],
		'noticedel'=>[
			'id'=>10,
			'name'=>'删除公告',
		],
		
    ];
	static $adminNav=['lists','noticelists','log'];
    //protected $noVerify=['lists'];
    
    //日期字符处理
    private function TextToDate($str){
        return str_to_date($str);
    }
	
	
	
	protected function _noticeadd($request,$user){
		$data=$request->param();
		if($data){
			if(!$data['name'])return set_err_back('00010009',$this->module);
			$content=$data['content'];
			unset($data['content']);
			$data['txt']=get_html_txts($content);
			if(!$data['img'] and $data['autoimg']){
				$imgArr=get_html_img($content);
				if($imgArr){
					$data['img']=$imgArr[0][0];
				}
			}
			unset($data['autoimg']);
			if(!$data['publish_time'])$data['publish_time']=get_now_time();
			if(!$data['expire_time'])$data['expire_time']=0;
			
			
			print_r($data);
			exit();
		}
		return true;
	}
	protected function _noticelists($request,$user){
		$event=controller($this->module.'/Notice', 'event');
		
		
		$lists=$event->getLists();
		return true;
	}
	protected function _logtype($request,$user){
		$module=$request->param('module');
		$event=controller($this->module.'/Log', 'event');
		$moduleID=config('app_id');
		if(!isset($moduleID[$module]))return set_err_back('00010003',$this->module);
		$back=$event->getType($moduleID[$module]);
		$data=[];
		if($back){
			foreach ($back as $v){
				$data[]=[
					'id'=>$v->id,
					'name'=>$v->ctrl,
					'txt'=>get_log_txt($v->ctrl,null,null,$moduleID[$module]),
				];
			}
		}
		return [
			'moduleid'=>$moduleID[$module],
			'list'=>$data,
		];
	}
	
	protected function _dellog($request,$user){
		$this->page_title='删除日志';
		$ids=$request->param('ids');
		$url=verify_url_from($request->param('url'));
		$url=$url?$url:verify_url_from($request->server('HTTP_REFERER'));
		$event=controller($this->module.'/Log', 'event');
		$back=$event->del($ids);
		if($back===false)return set_err_back($event->errCode,$this->module);
		$this->success(lang('Operation succeeded'), $url?$url:'log');
		exit();
		
	}
	protected function _log($request,$user){
		$this->page_title='操作日志';
        $num=config('admin.page')['gs']*2;
		$p=$request->param('p');
        if(!$p)$p=1;
		
		$case=$request->param('case');
		$startdate=$request->param('startdate');
		$enddate=$request->param('enddate');
		$module=$request->param('module');
		$ctrl=$request->param('ctrl');
		$userid=$request->param('userid');
		
		$event=controller($this->module.'/Log', 'event');
		$where=[];
		if($case){
			$startdate='';
			$enddate='';
			$tmp=$this->TextToDate($case);
			$where['create_time']=['>=',$tmp];
		}else{
			if($startdate and $enddate){
				$where['create_time']=['between',[txt_to_time($startdate),txt_to_time($enddate)]];
			}else{
				if($startdate)$where['create_time']=['>=',txt_to_time($startdate)];
				if($enddate)$where['create_time']=['<',txt_to_time($enddate)];
			}	
		}
		
		if($userid)$where['user_id|modify_user_id']=(int)$userid;
		if($module){
			$moduleID=config('app_id');
			if(isset($moduleID[$module])){
				$back=$event->getType($ctrl?$ctrl:$moduleID[$module],true);
				if(!is_array($back))$back=[$back];
				$tmp=[];
				foreach($back as $v){
					$tmp[]=$v->id;
				}
				$where['type']=['in',$tmp];
			}
		}
		$count=$event->getLists($where,true);
        $MaxP=ceil($count/$num);
        if($p>$MaxP)$p=$MaxP;
        $list=$event->getLists($where,$p.','.$num);
		
		$event=controller($this->module.'/Common', 'event');
		if($userid){
			$userData=$event->getUser($userid,true,false);
			if(!$userData)return set_err_back($event->errCode,$this->module);
		}
		$userIds=[];
		foreach($list as $v){
			$userIds[]=$v['user_id'];
			$userIds[]=$v['modify_user_id'];
		}
		$userIds=array_unique(array_filter($userIds));
		$tmp=$event->getLists(['id'=>['in',$userIds]],'id,name,status,delete_time',null,null,true);
		$userLists=[];
		foreach($tmp as $v){$userLists[$v->id]=$v;}
		$urlParam=get_url_param(null,['p'],true);
		
		#模块列表
		$tmp=config('app_id');
        $appArr=[];
        foreach ($tmp as $k=>$v){
            $moduleName = '\\app\\'.$k.'\\controller\\Admin';
            if(!class_exists($moduleName))continue;
            if(!isset($moduleName::$adminInfo))continue;
            if(!isset($moduleName::$adminNav))continue;
            $appArr[$v]=[
				'txt'=>$moduleName::$adminInfo['name'],
				'name'=>$k,
				'id'=>$v,
			];
        }
        $data=[
			'appArr'=>$appArr,
            'list'=>$list,
			'userLists'=>$userLists,
            'count'=>$count,
            'urlParam'=>$urlParam,
			'cond'=>[
				'case'=>$case,
				'startdate'=>$startdate,
				'enddate'=>$enddate,
				'module'=>$module,
				'ctrl'=>$ctrl,
				'userid'=>$userid,
			],
            'page'=>[
                'num'=>$num,
                'max'=>$MaxP,
                'index'=>$p,
            ],
        ];
		if(isset($userData))$data['userData']=$userData;
        return $data;
	}
	//删除用户
	protected function _del($request,$user){
		$ids=$request->param('ids');
		$url=verify_url_from($request->param('url'));
		$url=$url?$url:verify_url_from($request->server('HTTP_REFERER'));
		$event=controller($this->module.'/Common', 'event');
		$back=$event->delUser($ids);
		if($back===false)return set_err_back($event->errCode,$this->module);
        $LogObj=controller($this->module.'/Log', 'event');
        $LogObj->add('deluser',$ids,$user['id']);
		$this->success(lang('Operation succeeded'), $url?$url:'lists');
		exit();
	}
	//新建用户
	protected function _add($request,$user){
		$postData=$request->post();
		if($postData){	//处理数据
            
            if(!isset($postData['main_user']) or !$postData['main_user'])return set_err_back('0030',$this->module);
            if(!isset($postData['main_password']))return set_err_back('0005',$this->module);
            if(isset($postData['_encrypt']) and $postData['_encrypt'])$postData['main_password']=str_decrypt_easy($postData['main_password']);
            if(!$postData['main_password'])return set_err_back('0005',$this->module);
           
			$dataArr=[];
			foreach ($postData as $k=>$v){
				$k=explode('_',$k);
				$k1=$k[0];
				array_shift($k);
				if(!isset($dataArr[$k1]))$dataArr[$k1]=[];
				$k2= implode('_', $k);
				$dataArr[$k1][$k2]=$v;
			}
            $event=controller($this->module.'/Common', 'event');
            #获取数据
            $tmp=$dataArr['bindings'];      //绑定数据
			unset($dataArr['bindings']);
			$bindings=[];
			foreach ($tmp as $k=>$v){
				$k=explode('_', $k);
				if(!isset($bindings[$k[0]]))$bindings[$k[0]]=[];
				$bindings[$k[0]][$k[1]]=$v;
			}
            $main=$dataArr['main'];      //基本数据
            unset($dataArr['main']);
            $info=$dataArr['info'];     //详细数据
            unset($dataArr['info']);
            #验证数据
            $back=$event->__verifyData(array_merge($main,$info),'info');
            if(!$back)return set_err_back($event->errCode,$this->module);
            foreach ($bindings as $k=>$v){
                if(!isset($v['account']) or !$v['account'])continue;
                $back=$event->verifyBindings($v['account'],$k);
                if(!$back)return set_err_back($event->errCode,$this->module);
            }
            #新建用户
            $userId=$event->addUser($main,true);
            if(!$userId)return set_err_back($event->errCode,$this->module);
            $back=$event->saveInfo($info,$userId);
			if(!$back)return set_err_back($event->errCode,$this->module);
            foreach($bindings as $k=>$v){
				if(!isset($v['account']) or !$v['account'])continue;
				$back=$event->addBindings($userId,$v['account'],$k,$v['status'],false);
				if(!$back)return set_err_back($event->errCode,$this->module);
			}
            $LogObj=controller($this->module.'/Log', 'event');
            $LogObj->add('adduser',$userId,$user['id']);
            return ['id'=>$userId];
		}else{	//显示页面
			$this->page_title='新建用户';
			$this->Tpl='show';
			$event=controller('Admin/Auth', 'event');
			$groups=$event->getGroupLists();
			return ['groups'=>$groups];
		}
	}
	//编辑用户
	protected function _save($request,$user){
		$event=controller($this->module.'/Common', 'event');
		$data=$request->param();
		if(isset($data['ids'])){
			//批量
			$ids=$data['ids'];
			unset($data['ids']);
			$returnData=$ids;
			$back=$event->batchSaveInfo($data,$ids);
		}else{
			//单个
			$id=$data['id'];
			$type=$data['_type'];
			unset($data['id']);
			unset($data['_type']);
			$returnData=$id;
			
			if($type=='bindings'){
				//绑定数据
				$bindings=[];
				$tmp=$event->getBindings(['user_id'=>$id],false,count($event->bindingsType),false);
				$oldBindings=[];
				foreach($tmp as $v){
					$k=array_search($v->type,$event->bindingsType);
					if(!$k)continue;
					$oldBindings[$k]=$v->account;
				}
				foreach ($data as $k=>$v){
					$k=explode('_', $k);
					if(!isset($bindings[$k[0]]))$bindings[$k[0]]=[];
					$bindings[$k[0]][$k[1]]=$v;
				}
				foreach($bindings as $k=>$v){
					if($v['account']==''){
						$tmp=false;
					}else{
						$tmp=isset($oldBindings[$k])?($oldBindings[$k]!=$v['account']?true:false):true;
					}
					$back=$event->addBindings($id,$v['account'],$k,$v['status'],$tmp);
					if(!$back)return set_err_back($event->errCode,$this->module);
				}
				$back=true;
			}else{
				if(isset($data['password'])){
					$back=$event->savePassword($data['password'],$id);
					if($back===false)return set_err_back($event->errCode,$this->module);
					unset($data['password']);
				}
				$back=$event->saveInfo($data,$id);
			}
		}
		if(!$back)return set_err_back($event->errCode,$this->module);
        $LogObj=controller($this->module.'/Log', 'event');
        $LogObj->add('saveuser',isset($ids)?$ids:$id,$user['id']);
		return $returnData;
	}
    //显示用户
	protected function _show($request,$user){
		$this->page_title='编辑用户信息';
        $id=$request->param('id');
        $event=controller($this->module.'/Common', 'event');
        $main=$event->getUser($id,true,false);
		//基础资料
		if(!$main)$this->error(lang('no data'));
		//绑定资料
		$bID=[];
		$bindings=[];
		foreach ($event->bindingsType as $v){$bID[]=$v;}
		$bID= implode(',', $bID);
		$tmp=$event->getBindings(['user_id'=>$id,'type'=>['in',$bID]],false,count($event->bindingsType),false);
		if($tmp){
			foreach ($tmp as $v){
				$k=array_search($v->type,$event->bindingsType);
				if($k)$bindings[$k]=$v;
			}
		}
		//详细资料
		$info=$event->getInfo(['sex','txts'],$id);
		$event=controller('Admin/Auth', 'event');
		$groups=$event->getGroupLists();
        return [
			'groups'=>$groups,
			'main'=>$main,
			'bindings'=>$bindings,
			'info'=>$info,
			'user'=>$user,
		];
    }
    //用户列表
	protected function _lists($request,$user){
		$this->page_title='用户列表';
        $num=config('admin.page')['gs'];
        $field='id,user,name,img,login_time,create_time,admin,type,status';
        $p=$request->param('p');
        if(!$p)$p=1;

        $cond_key=$request->param('key');
        $cond_search=$request->param('search');
        $cond_sort=$request->param('sort');
        $cond_filter=$request->param('filter');
        $cond_key=$cond_key?$cond_key:'name';
        $cond_search=$cond_search?$cond_search:'';
        $cond_sort=$cond_sort?$cond_sort:'login_time desc';
        $cond_filter=$cond_filter?$cond_filter:'';
        $tmp= explode(' ',$cond_sort);
        $order= implode(' ', $tmp).',id DESC';
        $where=[];
        if($cond_filter){
            $tmp= explode('&',$cond_filter);
            foreach ($tmp as $v){
                $v= explode('=', $v);
                if($v[0]=='create_time' or $v[0]=='login_time'){
                    $v[1]=$this->TextToDate($v[1]);
                     $where[$v[0]]=['>=',$v[1]];
                }else{
                    $where[$v[0]]=$v[1];
                }
            }
        }
        if($cond_key=='all'){
            $field.=',phone,mail';
            $where['phone|mail|name|user']=['like','%'.$cond_search.'%'];
        }elseif($cond_key=='phone' or $cond_key=='mail'){
            $field.=','.$cond_key;
            if($cond_search)$where[$cond_key]=['like','%'.$cond_search.'%'];
        }else{
            if($cond_search)$where[$cond_key]=['like','%'.$cond_search.'%'];
        }
        $event=controller($this->module.'/Common', 'event');
        $count=$event->getLists($where,'id',null,true);
        $MaxP=ceil($count/$num);
        if($p>$MaxP)$p=$MaxP;
        $list=$event->getLists($where,$field,$order,$p.','.$num);
       
		$urlParam=get_url_param(null,['p'],true);
        $data=[
            'list'=>$list,
            'count'=>$count,
            'urlParam'=>$urlParam,
            'page'=>[
                'num'=>$num,
                'max'=>$MaxP,
                'index'=>$p,
            ],
            'cond'=>[
                'key'=>$cond_key,
                'search'=>$cond_search,
                'sort'=>strtolower($cond_sort),
                'filter'=>$cond_filter,
            ],
        ];
        return $data;
	}
	
}