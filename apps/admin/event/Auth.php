<?php
namespace app\Admin\event;

use app\Admin\model\adminUser;

use app\Admin\model\adminAuth;
use app\Admin\model\adminGroup;

class Auth {
	protected $module='Admin';
	public $errCode='';
    public $errText='';
	//列表
	public function getGroupLists($wh=[],$page=null){
		$db=adminGroup::field(true);
		if($wh)$db=$db->where($wh);
		if($page){
            if($page===true){
                return $db->count();
            }else{
				$db=$db->page($page);
            }
        }
		$lists=$db->select();
		return $lists;
	}
	//获取信息
	public function getGroup($wh,$isID=true){
		$where=is_array($wh)?$wh:['id'=>$wh];
		$db=adminGroup::field(true)->where($where);
		$group=$db->find();
		if(!$group){
			$this->errCode='0002';
			return 0;
		}
		return $group;
	}
	public function saveGroup($data,$wh,$isID=true){
		$obj=$this->getGroup($wh);
		foreach($data as $k=>$v){
			$obj->$k=$v;
		}
		$back=$obj->save();
		if($back===FALSE){
			$this->errCode='00010002';
			return 0;
		}
		return true;
	}
	public function addGroup($data){
		$back=adminGroup::create($data);
		if(!$back){
			$this->errCode='00010002';
			return 0;
		}
		return $back->id;
	}
	
	public function delGroup($ids){
		$idArr=is_array($ids)?$ids:strint_to_arrint($ids);
		adminGroup::destroy($idArr);
		return true;
	}
	
	//获取权限
	public function getGroupAuth($wh,$isID=true){
		$where=is_array($wh)?$wh:['group_id'=>$wh];
		$db=adminAuth::field(true)->where($where);
		$tmp=$db->select();
		if(!$tmp)return [];
		$data=[];
		foreach ($tmp as $v){
			$data[$v->module]=($v->ctrl_id=='0' or $v->ctrl_id=='')?$v->ctrl_id:explode(',', $v->ctrl_id);
		}
		return $data;
	}
	//修改权限
	public function saveGroupAuth($data,$id,$isID=true){
		foreach($data as $k=>$v){
			$dbObj=adminAuth::where(['group_id'=>$id,'module'=>$k])->find();
			if($dbObj){
				$dbObj->ctrl_id=$v;
				$back=$dbObj->save();
			}else{
				$back=adminAuth::create([
					'group_id'=>$id,
					'module'=>$k,
					'ctrl_id'=>$v,
				]);
			}
		}
		if($back===FALSE){
			$this->errCode='00010002';
			return 0;
		}
		return true;
	}
}