<?php
namespace app\User\event;

use app\User\model\userLog;
use app\User\model\userLogType;

class Log{
	protected $module='User';
	public $errCode='';
	public $type=[
		'login'=>1,
		'logout'=>2,
	];
    public function getType($whStr,$backObj=false){
		$backlist=false;
		if(is_numeric($whStr)){
			$wh=['module_id'=>$whStr];
			$backObj=true;
			$backlist=true;
		}else{
			$wh=['ctrl'=>$whStr];
		}
        $db=userLogType::field($backObj?$backObj:'id')->where($wh);
		$data=$backlist?$db->select():$db->find();
        if(!$data){
            $this->errCode='00010003';
            return 0;
        }
        return $backObj?$data:$data->id;
        
    }
	public function add($type,$modifyUser=false,$userID=false,$info='',$less=''){
        $typeID=$this->getType($type);
		if(!$typeID)return 0;
		if($userID===false){
			$event=controller($this->module.'/Common', 'event');
			$back=$event->verifyLogin();
			$userID=$back?$back['id']:0;
		}
        if(!$modifyUser)$modifyUser=$userID;
        $modifyUser=is_array($modifyUser)?$modifyUser:strint_to_arrint($modifyUser);
        $data=[];
        foreach($modifyUser as $v){
            $data[]=[
                'modify_user_id'=>$v,
                'user_id'=>$userID,
                'type'=>$typeID,
                'info'=>$info,
                'less'=>$less,
            ];
        }
        //$db=new userLog;
		//$back = $db->saveAll($data);
		foreach ($data as $v){
			$back=userLog::create($v);
		}
        if(!$back){
            $this->errCode='00010002';
            return 0;
        }
		return $back;
	}
	public function del($ids){
		$idArr=str_to_arr($ids);
		userLog::destroy($idArr);
		return true;
	}
	
	public function getLists($wh=null,$page=null){
		$db=userLog::field('id,user_id,modify_user_id,type,info,less,ips,create_time');
		
		if(!$wh)$wh=[];
		$db=$db->whereNull('delete_time')->where($wh);
		
		if($page){
            if($page===true){
                return $db->count();
            }else{
				$tmp=$db->order('create_time','DESC')->order('id','DESC')->page($page)->select();
				$list=[];
				$typeList=[];
				foreach($tmp as $v){
					$typeList[]=$v->type;
					$list[]=$v->toarray();
				}
				$typeList=array_unique(array_filter($typeList));
				$tmp=userLogType::field('id,ctrl,module_id')->where(['id'=>['IN',$typeList]])->select();
				$typeList=[];
				foreach ($tmp as $v){$typeList[$v->id]=$v->toarray();}
				foreach($list as $k=>$v){
					unset($typeList[$v['type']]['id']);
					$list[$k]=array_merge($v,$typeList[$v['type']]);
				}
				return $list;
            }
        }
	}
	public function get($type,$where=false,$limit=0,$field=false,$userID=false){
		if(!isset($this->type[$type]))return 0;
		if($userID===false){
			$event=controller('Common', 'event');
			$back=$event->verifyLogin();
			$userID=$back?$back['id']:0;
		}
		$wh=[
			'user_id'=>$userID,
			'type'=>$this->type[$type],
		];
		$back=userLog::where($wh)->order('create_time desc');
		if($field===false){
			$back=$back->field('delete_time',true);
		}else{
			$back=$back->field($field);
		}
		$limit=intval($limit);
		if($limit)$back=$back->limit($limit);
		$back=$back->select();
		return $back;
	}
}