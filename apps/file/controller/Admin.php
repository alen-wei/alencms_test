<?php
namespace app\File\controller;
class Admin extends \base\Admin{
	protected $module='File';
    static $adminInfo=[
        'name'=>'文件管理',
        'icon'=>'fa fa-folder-open',
    ];
	static $ctrlLists=[
        'lists'=>[
			'id'=>1,
			'name'=>'文件列表',
		],
		'show'=>[
			'id'=>2,
			'name'=>'查看文件',
		],
		'download'=>[
			'id'=>3,
			'name'=>'下载文件',
		],
		'del'=>[
			'id'=>4,
			'name'=>'删除文件',
		],
		
    ];
	static $adminNav=['lists'];
	
	protected function _show($request,$user){
		$this->page_title='查看文件';
		$id=$request->param('id');
		$event=controller($this->module.'/Common', 'event');
        $data=$event->getFile($id);
		if(!$data)return set_err_back('00010003',$this->module);
		$event=controller('User/Common', 'event');
		$userData=$event->getUser($data->user_id,true,false,'id,name,delete_time,img');
		
		foreach (\app\File\event\Common::$typeExt as $kk=>$vv){
			if(in_array(strtolower($data->ext),$vv)){
				$extType=$kk;
				break;
			}
		}
		if(!isset($extType))$extType=null;
		return [
			'userData'=>$userData,
			'data'=>$data,
			'extType'=>$extType,
		];
	}
	protected function _del($request,$user){
		$id=$request->param('id');
		$url=verify_url_from($request->param('url'));
		$url=$url?$url:verify_url_from($request->server('HTTP_REFERER'));
		$event=controller($this->module.'/Common', 'event');
		$back=$event->delFile($id,true);
		if(!$back)return set_err_back($event->errCode,$this->module);
		$this->success(lang('Operation succeeded'), $url?$url:'lists');
		exit();
	}
	protected function _download($request,$user){
		$id=$request->param('id');
		$event=controller($this->module.'/Common', 'event');
		$back=$event->downloadFile($id);
		if($back){
			exit();
		}else{
			return set_err_back($event->errCode,$this->module);
		}
	}
	protected function _lists($request,$user){
		$this->page_title='文件列表';
        $num=config('admin.page')['gs'];
		$p=$request->param('p');
        if(!$p)$p=1;
		$field='id,user_id,path,ext,size,apply,create_time,update_time';
		$order='create_time DESC,id DESC';
		
		$update=$request->param('update');
		$startdate=$request->param('startdate');
		$enddate=$request->param('enddate');
		$type=$request->param('type');
		$userid=$request->param('userid');
		
		if($userid){
			$event=controller('User/Common', 'event');
			$userData=$event->getUser($userid,true,false);
			if(!$userData)return set_err_back($event->errCode,$this->module);
		}
		
		$where=[];
		if($update){
			$startdate='';
			$enddate='';
			$tmp=str_to_date($update);
			$where['create_time']=['>=',$tmp];
		}else{
			if($startdate and $enddate){
				$where['create_time']=['between',[txt_to_time($startdate),txt_to_time($enddate)]];
			}else{
				if($startdate)$where['create_time']=['>=',txt_to_time($startdate)];
				if($enddate)$where['create_time']=['<',txt_to_time($enddate)];
			}	
		}
		$typeExt=\app\File\event\Common::$typeExt;
		if($type){
			if(isset($typeExt[$type])){
				$exts=$typeExt[$type];
				foreach($exts as $k=>$v){
					$exts[$k]=strtoupper($v);
				}
				$where['ext']=['IN',$exts];
			}else{
				$type="";
			}
		}
		$event=controller($this->module.'/Common', 'event');
        $count=$event->getLists($where,'id',null,true);
        $MaxP=ceil($count/$num);
        if($p>$MaxP)$p=$MaxP;
        $list=$event->getLists($where,$field,$order,$p.','.$num);
		$extType=[];
		$uIDs=[];
		foreach($list as $v){
			$uIDs[]=$v->user_id;
			if(isset($extType[$v->ext]))continue;
			foreach ($typeExt as $kk=>$vv){
				if(in_array(strtolower($v->ext),$vv)){
					$extType[$v->ext]=$kk;
					break;
				}
			}
			if(!isset($extType[$v->ext]))$extType[$v->ext]=null;
		}
		$uIDs=array_unique(array_filter($uIDs));
		$event=controller('User/Common', 'event');
		$tmp=$event->getLists(['id'=>['IN',$uIDs]],'id,name,delete_time',null,null,true);
		$userList=[];
		foreach ($tmp as $v){$userList[$v->id]=$v;}
		
		$urlParam=get_url_param(null,['p'],true);
		$data=[
			'extType'=>$extType,
			'userList'=>$userList,
            'list'=>$list,
            'count'=>$count,
            'urlParam'=>$urlParam,
            'page'=>[
                'num'=>$num,
                'max'=>$MaxP,
                'index'=>$p,
            ],
			'cond'=>[
				'update'=>$update,
				'startdate'=>$startdate,
				'enddate'=>$enddate,
				'type'=>$type,
				'userid'=>$userid,
			],
        ];
		if(isset($userData))$data['userData']=$userData;
        return $data;
	}

}
