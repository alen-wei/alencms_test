<?php
namespace app\System\event;
use app\System\model\publicTreeview;
class Treeview{
	protected $module='System';
	public $errCode='';
	#添加节点
	public function add($fid,$type){
		$data=[
			'type'=>$type,
			'fid'=>$fid,
		];
		if($fid){
			$wh=['id'=>$fid];
			$fCls=publicTreeview::get($wh);
			$num=$fCls->num;
			$back=publicTreeview::where([
				'num'=>['>=',$num],
				'leftnum'=>['>',$num],
				'tid'=>$fCls['tid'],
			])->setInc('leftnum',1);
			if($back===false){
				$this->errCode='00010002';
				return 0;
			}
			$back=publicTreeview::where([
				'num'=>['>=',$num],
				'tid'=>$fCls['tid'],
			])->setInc('num',1);
			if($back===false){
				$this->errCode='00010002';
				return 0;
			}
			$data['tid']=$fCls['tid'];
			$data['level']=intval($fCls['level'])+1;
			$data['num']=$num;
			$data['leftnum']=$num;
		}else{
			$data['level']=1;
			$data['num']=1;
			$data['leftnum']=1;
		}
		$back=publicTreeview::create($data);
		if(!$back){
			$this->errCode='00010002';
			return 0;
		}
		$data=['path'=>$fid?$fCls['path'].','.$back->id:$back->id,'id'=>$back->id];
		if(!$fid)$data['tid']=$back->id;
		$back=publicTreeview::update($data);
		if(!$back){
			$this->errCode='00010002';
			return 0;
		}
		return $back->id;
	}
	#删除节点
	public function del($fid,$type){
		$wh=['id'=>$fid];
		$fCls=publicTreeview::get($wh);
		if(!$fCls){
			$this->errCode='00010002';
			return 0;
		}
		$num=$fCls->num;
		$tid=$fCls->tid;
		$back=publicTreeview::where([
			'num'=>['between',[$fCls->leftnum,$num]],
			'tid'=>$tid,
		])->order('num DESC')->select();
		if(!$back){
			$this->errCode='00010002';
			return 0;
		}
		$cou=count($back);
		$delIds=[];
		foreach($back as $v){
			$delIds[]=$v->id;
		}
		$back=publicTreeview::destroy($delIds);
		if(!$back){
			$this->errCode='00010002';
			return 0;
		}
		$back=publicTreeview::where([
			'num'=>['>',$num],
			'leftnum'=>['>',$num],
			'tid'=>$tid,
		])->setDec('leftnum',$cou);
		if(!$back){
			$this->errCode='00010002';
			return 0;
		}
		$back=publicTreeview::where([
			'num'=>['>',$num],
			'tid'=>$tid,
		])->setDec('num',$cou);
		if(!$back){
			$this->errCode='00010002';
			return 0;
		}
		return $delIds;
	}
}