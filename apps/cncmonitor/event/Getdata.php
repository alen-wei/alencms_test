<?php
namespace app\CncMonitor\event;

use app\CncMonitor\model\cncmonitorMachine;
use app\CncMonitor\model\cncmonitorWorkshop;
use app\CncMonitor\model\cncmonitorIrisLog;
use app\CncMonitor\model\cncmonitorStatus;

class Getdata{
	protected $module='CncMonitor';
	//protected $apiUrl='http://192.168.10.11:8080/api/';
	//protected $apiUrl='http://165.254.5.225:8080/api/';
	protected $apiUrl='http://127.0.0.1:8080/api/';
	
	protected $statusApi=[
			'0000'=>'run',
			'0001'=>'standby',
			'0002'=>'warning',
			'0003'=>'task',
		];
	protected $fieldsApi=[
			'0004'=>'feedrateper',  //进给倍率
			'0005'=>'workpiece',    //主程序号
			'0006'=>'error',        //报警信息
			'0007'=>'finished',     //加工件数
			'0008'=>'mission',	    //计划加工数
			'0009'=>'subprogram',   //当前子程序号
			'0010'=>'feedrate',	    //进给速度   mm/min   48000mm
			'0011'=>'spindle',	    //主轴转速   rpm      20000
			'0012'=>'axis',	        //轴坐标     mm
			'0013'=>'auth',	        //权限
		];
	protected $fieldsArr=['id','name','workshop','power','run','standby','task','warning','finished','mission'];
	protected $fieldsRealArr=['status','mission_status','feedrate','workpiece','error','subprogram','feedrateper','spindle','axis','auth'];
	public $errCode='';
	private function _getXml($xmlfile,$tabName){
		$xml = new \DOMDocument();
		$xml->load($xmlfile);
		$back=array();
		foreach($xml->getElementsByTagName($tabName) as $list){
			$value = $list->nodeValue;
			$back[]=trim($value);
		}
		return $back;
	}
	private function _getXmlAll($xmlfile){
		$xml = new \DOMDocument(); 
		$xml->load($xmlfile);
		$back=[];
		$Machine  = $xml ->getElementsByTagName("Machine");
		foreach($Machine as $v){
			$id=intval($v->getElementsByTagName("Index")->item(0)->nodeValue);
			foreach($v->getElementsByTagName("Date") as $vv){
				$DateTime=$vv->getElementsByTagName("DateTime")->item(0)->nodeValue;
				$DateTime=get_time_str($DateTime);
				$Run=intval($vv->getElementsByTagName("Run")->item(0)->nodeValue);
				$PowerOn=intval($vv->getElementsByTagName("PowerOn")->item(0)->nodeValue);
				$DateTime=str_replace('/','-',$DateTime);
				if(!isset($back[$DateTime]))$back[$DateTime]=[];
				if(!isset($back[$DateTime][$id]))$back[$DateTime][$id]=[];
				$back[$DateTime][$id]['id']=$id+1;
				$back[$DateTime][$id]['power']=$PowerOn;
				$back[$DateTime][$id]['run']=$Run;
			}
		}
		return $back;
	}
	private function _setBackData($data,$fields){
		$ffArr=[];
		//get_now_time()
		foreach($data as $k=>$v){$ffArr[]=$k;}
		$warning=(int)(($data['power']-$data['run'])*0.50);
		$mission=(int)(3600*24/10);
		if(!$fields or in_array('task',$fields))$data['task']=(int)($data['run']*0.85);
		if(!$fields or in_array('warning',$fields))$data['warning']=$warning;
		if(!$fields or in_array('standby',$fields))$data['standby']=$data['power']-$data['run']-$warning;
		if(!$fields or in_array('mission',$fields))$data['mission']=$mission;
		if(!$fields or in_array('finished',$fields))$data['finished']=(int)((get_now_time()-strtotime(get_now_time('Y-m-d')))/10)-$data['id'];
		if(!$fields or in_array('status',$fields))$data['status']=rand(0,4);
		if(!$fields or in_array('mission_status',$fields))$data['mission_status']=rand(0,2);
		if(!$fields or in_array('workpiece',$fields))$data['workpiece']='ALENWEI095';
		
		foreach($ffArr as $v){
			if($fields and !in_array($v,$fields))unset($data[$v]);
		}
		//print_r($data);
		return $data;
	}
	
	private function getApiData($url,$data=false){
		$back=get_url_file($url,$data);
		//print_r($back);
		$back=str_replace('\\','',substr($back,1,strlen($back)-2));
		$back=str_replace(':"{',':{',$back);
		$back=str_replace('}"','}',$back);
		$back=str_replace(':"[',':[',$back);
		$back=str_replace(']"',']',$back);	
		if($back=='null')return false;
		return json_decode($back,true);
	}
	private function _getApiNameData($arr,$all=true){
		$arr=$all?$arr['Childs']:[0=>$arr];
		$data=[];
		foreach($arr as $v){
			foreach($v['Devices'] as $vv){
				$id=$this->_getID($vv['Key']);
				$data[$id]=['id'=>$id,'name'=>$vv['Name']?$vv['Name']:'机床 #'.$id,'workshop'=>$this->_getID($v['Key'])];
			}
		}
		return $data;
	}
	private function _getApiArrData($data=false,$constantly=false,$fields){
		if($data){
			$fieldsApi=$this->statusApi;
			$back=[];
			
			foreach($data as $k=>$v){
				$back[$k]=[];
				foreach($v as $kk=>$vv){
					if(isset($fieldsApi[$kk])){
						$back[$k][$fieldsApi[$kk]]=$vv;
					}
				}
			}
			
			//'0003'=>'task',
		}
		if($constantly){
			$realBack=[];
			foreach($constantly as $k=>$v){
				$status=0;
				foreach($this->statusApi as $kk=>$vv){
					if(isset($v[$kk])){
						if(intval($v[$kk])>0){
							if($vv=='task'){
								if($status!=1){
									unset($v[$kk]);
									continue;
								}
							}
							$status=$this->_getID($kk)+1;
						}
						unset($v[$kk]);
					}
					
				}
				foreach($v as $kk=>$vv){
					if(isset($this->fieldsApi[$kk])){
						
						$v[$this->fieldsApi[$kk]]=$vv;
					}
					unset($v[$kk]);
				}
				$v['status']=$status;
				$realBack[$k]=$v;
			}
		}
		$tmpBack=$data?$back:$realBack;
		$tmp=[];
		foreach($tmpBack as $k=>$v){
			if($data){
				if(isset($realBack[$k]))$v=array_merge($v,$realBack[$k]);
			}else{
				if(isset($data[$k]))$v=array_merge($v,$data[$k]);
			}
			if(isset($v['task']))$v['task']=$v['task']>$v['run']?$v['run']:$v['task'];
			$id=$this->_getID($k);
			if(!$fields or in_array('power',$fields))$v['power']=$v['run']+$v['standby']+$v['warning'];
			//if(!$fields or in_array('mission',$fields))$v['mission']=$mission;
			//if(!$fields or in_array('finished',$fields))$v['finished']=(int)((get_now_time()-strtotime(get_now_time('Y-m-d')))/10)-$id;
			if(!$fields or in_array('mission_status',$fields))$v['mission_status']=rand(0,2);
			if(!$fields or in_array('workpiece',$fields)){
				$v['workpiece']=$v['workpiece']?$v['workpiece']:'未知';
			}
			//if(!$fields or in_array('error',$fields))$v['error']=[];
			//if($id!=9)$v['error']=[];
			foreach($v as $kk=>$vv){
				if(in_array($kk,$fields)){
					if(in_array($kk,$this->statusApi))$v[$kk]=(int)$v[$kk];
				}else{
					unset($v[$kk]);
				}
			}
			if(!$fields or in_array('id',$fields) or in_array('name',$fields) or in_array('workshop',$fields))$v['id']=$id;
			$tmp[]=$v;
		}
		return $tmp;
	}
	private function _getID($id){
		return (int)$id;
	}
	private function _setID($id,$cLen=4){
		$cs=$cLen-strlen($id);
		$str=$id;
		while($cs>0){
			$str='0'.$str;
			$cs--;
		}
		return ''.$str;
	}
	//获取名称
	public function getName($ids=0,$workshop=0){
		$cachePath=strtolower($this->module).DS;
		$url=$this->apiUrl.'BaseInfo/{workshop}frame';
		if('all'===$workshop){
			$frame=1;
			$workshop=0;
		}else{
			$frame=0;
		}
		if($workshop){//车间数据
			$workshop=(int)$workshop;
			$dbBack=cncmonitorMachine::all(['workshop_id'=>$workshop]);  //读取数据库缓存
			if($dbBack){
				$data=[];
				foreach($dbBack as $v){
					$data[$v->id]=[
						'id'=>$v->id,
						'name'=>$v->name,
						'workshop'=>$v->workshop_id,
					];
				}
			}else{  //API拉取数据
				$url=str_replace('{workshop}',$this->_setID($workshop).'/',$url);
				$back=$this->getApiData($url);
				if(!$back){
					$this->errCode='0001';
					return 0;
				}
				$data=$this->_getApiNameData($back,false);
				//写入数据库缓存
				$dbData=[];
				foreach($data as $k=>$v){
					$v['workshop_id']=$v['workshop'];
					unset($v['workshop']);
					$dbData[]=$v;
				}
				$db=new cncmonitorMachine;
				$back=$db->saveAll($dbData,false);
			}
		}else{//全部数据
			$workshopData=cncmonitorWorkshop::all();  //读取数据库缓存
			if($workshopData){
				foreach($workshopData as $k=>$v){
					$v=$v->toarray();
					$workshopData[$k]=$v;
				}
				if(!$frame){
					$dbData=cncmonitorMachine::all();
					$data=[];
					foreach($dbData as $v){
						$data[$v->id]=[
							'id'=>$v->id,
							'name'=>$v->name,
							'workshop'=>$v->workshop_id,
						];
					}
				}
			}else{  //API拉取数据
				$url=str_replace('{workshop}','',$url);
				$back=$this->getApiData($url);
				if(!$back){
					$this->errCode='0001';
					return 0;
				}
				$data=$this->_getApiNameData($back);
				//写入数据库缓存
				$cache=[];
				$workshopData=[];
				foreach($data as $v){
					$tmp=$v['workshop'];
					unset($v['workshop']);
					if(!isset($cache[$tmp])){
						$cache[$tmp]=[];
						$workshopData[]=['id'=>$tmp,'name'=>'车间 #'.$tmp];
					}
					$cache[$tmp][]=$v;
				}
				foreach($cache as $k=>$v){
					$cache_db=new cncmonitorMachine;
					$tmp=$cache_db->where(['workshop_id'=>$k])->find();
					if(!$tmp){
						foreach($v as $kk=>$vv){$v[$kk]['workshop_id']=$k;}
						$back=$cache_db->saveAll($v,false);
					}
				}
				$cache_db=new cncmonitorWorkshop;
				$back=$cache_db->saveAll($workshopData,false);
			}
		}
		if($frame)return $workshopData;
		if(!$ids){
			sort($data);
			return $data;
		}
		if($ids and !is_array($ids))$ids=strint_to_arrint($ids);
		$tmp=[];
		foreach($ids as $v){if(isset($data[$v]))$tmp[]=$data[$v];}
		return $tmp;
	}
	//获取数据
	public function getData($ids=0,$time=0,$endtime=0,$fields=false,$workshop=false){
		$url=$this->apiUrl.'RealData/deviceAndType';
		$historyUrl=$this->apiUrl.'HistoryTotal';
		
		$nowDay=strtotime(get_now_time('Y-m-d'));
		$time=$time?strtotime($time):$nowDay;
		$constantly=$time==$nowDay and !$endtime?true:false;
		//处理ID
		if($ids){
			if($workshop){
				if(!is_numeric($ids)){
					$this->errCode='00010003';
					return 0;
				}
				$nameData=$this->getName(0,$ids);
				if(!$nameData){
					$this->errCode='0001';
					return 0;
				}
			}else{
				if(!is_array($ids))$ids=strint_to_arrint($ids);
			}
		}else{
			$nameData=$this->getName();
			//print_r($nameData);
		}
		if(isset($nameData)){
			$ids=[];
			foreach($nameData as $v){
				$ids[]=$v['id'];
			}
		}
		$idsStr='';
		foreach($ids as $v){
			$idsStr.=$idsStr?',':'';
			$idsStr.=$this->_setID($v);
		}
		//处理字段
		$tmp=$constantly?array_merge($this->fieldsArr,$this->fieldsRealArr):$this->fieldsArr;
		if($fields){
			if(!is_array($fields))$fields=strpos($fields,',')===false?(array)$fields:explode(',',$fields);
			$tmpFields=[];
			foreach($fields as $v){
				if(in_array($v,$tmp))$tmpFields[]=$v;
			}
			$fields=$tmpFields?$tmpFields:$tmp;
		}else{
			$fields=$tmp;	
		}
		//实时数据
		if(array_intersect($fields,$this->fieldsRealArr)){
			$fieldStr=implode(',',array_keys(array_merge($this->statusApi,$this->fieldsApi)));
			//$fieldStr=implode(',',array_keys($this->statusApi));
			$postData='id_dvc='.urlencode($idsStr).'&id_tpy='.urlencode($fieldStr);
			//echo $url.'?'.$postData;
			$realData=$this->getApiData($url,$postData);
			if(!$realData){
				$this->errCode='0001';
				return 0;
			}
		}
		//历史数据
		if(array_intersect($fields,$this->fieldsArr)){
			$fieldStr=implode(',',array_keys($this->statusApi));
			$postData='id_dvc={ids}&id_tpy={fields}&id_Tb={time}&id_Te={time}';
			$postData=str_replace('{ids}',urlencode($idsStr),$postData);
			$postData=str_replace('{fields}',urlencode($fieldStr),$postData);
			$endtime=$endtime?strtotime($endtime):$time;
			$cs=3600*24;
			$tmpTime=$time;
			$data=[];
			while($tmpTime<=$endtime){
				$tmpPost=str_replace('{time}',urlencode(get_time_str($tmpTime)),$postData);
				$tmp=$this->getApiData($historyUrl,$tmpPost);
				if(is_array($tmp)){
					$data=array_merge($data,$tmp);
					$tmpTime+=$cs;
				}
			}
			if(!$data){
				$this->errCode='0001';
				return 0;
			}
		}
		//是否需要名字和车间
		if(!$fields or in_array('name',$fields) or in_array('workshop',$fields)){
			if(!isset($nameData))$nameData=$this->getName($ids);
			$tmp=[];
			foreach($nameData as $v){
				$tmp[$v['id']]=$v;
				unset($tmp[$v['id']]['id']);
			}
			$nameData=$tmp;
		}else{
			if(!isset($nameData))unset($nameData);
		}
		//处理返回数据
		if(isset($realData)){
			$tmp=$this->_getApiArrData(isset($data)?array_shift($data):false,$realData,$fields);
			$back=[date('Y-m-d',$time)=>$tmp];
		}else{
			$back=[];
			$realData=isset($realData)?$realData:false;
			foreach($data as $k=>$v){
				$tmp=$this->_getApiArrData($v,$realData,$fields);
				$back[date('Y-m-d',strtotime($k))]=$tmp;
			}
		}
		if(isset($nameData)){
			//print_r($nameData);
			//exit();
			foreach($back as $k=>$v){
				foreach($v as $kk=>$vv){
					//if(!$fields or in_array('error',$fields))$v[$kk]['error']=['00001'=>'测试错误'];
					if(!$fields or in_array('name',$fields))$v[$kk]['name']=$nameData[$vv['id']]['name'];
					if(!$fields or in_array('workshop',$fields))$v[$kk]['workshop']=$nameData[$vv['id']]['workshop'];
					if($fields and !in_array('id',$fields))unset($v[$kk]['id']);
				}
				$back[$k]=$v;
			}
		}
		return $back;
	}
	//获取程序
	public function getCode($id,$codeID=''){
		$url=$this->apiUrl.'Program';
		$url.='/'.$this->_setID($id);
		if($codeID){
			$url.='/'.$codeID;
			$back=get_url_file($url);
			$back=str_replace('\\n',"\n",substr($back,1,-1));
			return $back;
		}else{
			$back=$this->getApiData($url);
			foreach($back as $k=>$v){
				$back[$k]='O'.$v;
			}
			return $back;
		}
	}
	//删除程序
	public function delCode($id,$codeID){
		$url=$this->apiUrl.'Program/DeletePro';
		$codeID='O'==substr($codeID,0,1)?substr($codeID,1):$codeID;
		$postData='id_dvc='.$this->_setID($id).'&id_proName='.$codeID;
		$back=intval($this->getApiData($url,$postData));
		if($back==-1){
			$this->errCode='0001';
			return 0;
		}
		return true;
	}
	//修改程序
	public function saveCode($id,$content){
		$url=$this->apiUrl.'Program';
		//$content=str_replace("\n",'\\n',$content);
		$content=urlencode($content);
		$postData='id_dvc='.$this->_setID($id).'&_Content='.$content;
		//echo $content;
		$back=get_url_file($url,$postData);
		//echo $back;
		//exit();
		if($back==-1){
			$this->errCode='0001';
			return 0;
		}
		return true;
	}
	public function getAuthCncStaff($userID){
		
	}
	public function setAuthCnc_post($id,$status){
		$url=$this->apiUrl.'MacUserRight/';
		$postData='id_dvc='.$this->_setID($id).'&id_Right='.$status;
		$back=intval(get_url_file($url,$postData));
		\Think\Log::write('返回：'.$back,'notice');
		if($back==-1){
			$this->errCode='0001';
			return 0;
		}
		return true;
	}
	//锁定
	public function setAuthCnc($actid,$sn,$userID,$groups,$times){
		$snArr=[
			'2000010100009'=>1,
		];
		$groupsArr=[
			'201401072301503956229eb79724b50'=>1,
			'201401072301208739344289316a321'=>2,
			'2014010700232782407100b02680d47'=>3,
		];
		$DBdata=[
			'id'=>$actid,
			'sn'=>$sn,
			'staff_id'=>$userID,
			'act_time'=>$times,
			'groups'=>$groups,
		];
		
		$tmp=cncmonitorIrisLog::where('id',$actid)->find();
		if($tmp){
			$this->errCode='0003';
			return 0;
		}
		$id=$snArr[$sn];
		$status=isset($groupsArr[$groups])?$groupsArr[$groups]:0;
		
		$tmp=$this->getData($id,0,0,'auth');
		$tmp=array_shift($tmp);
		$tmp=array_shift($tmp);
		$auth=$tmp['auth'];
		
		$back=cncmonitorIrisLog::create($DBdata);
		//$back='aaa';
		
		if(!$back){
			$this->errCode='00010002';
			return 0;
		}
		
		$back=cncmonitorStatus::get($id);
		$DBdata=[
			'id'=>$id,
			'status'=>$status,
			'act_id'=>$actid,
			'staff_id'=>$userID,
		];
		if($back){
			if(intval($auth)==$status and intval($back->staff_id)==intval($userID)){
				$DBdata['status']=0;
				$DBdata['staff_id']=0;
				$status=0;
			}
			$back=cncmonitorStatus::update($DBdata);
		}else{
			$back=cncmonitorStatus::create($DBdata);
		}
		if(!$back){
			$this->errCode='00010002';
			return 0;
		}
		
		$back=$this->setAuthCnc_post($id,$status);
		if(!$back)return 0;
		
		return $id;
	}
	
}