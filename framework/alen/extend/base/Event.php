<?php
namespace base;
class Event{
	public $model='';
	//获取信息
	public function get($wh,$isID=true,$tmp_field=true,$del=false){
		if($isID){
			$whereArr=['id'=>['=',$wh]];
		}else{
			$whereArr=$wh;
		}
		if($tmp_field===true or $tmp_field==='*'){
            $field=true;
        }elseif(!is_array($tmp_field)){
            $field=explode(',',$tmp_field);
        }
		$db=$del?$this->model::withTrashed()->field($field):$this->model::field($field);
		return $db->where($whereArr)->find();
	}
	//获取列表
	public function getLists($wh=null,$tmp_field=true,$order=null,$page=null,$del=false){
		if($tmp_field===true or $tmp_field==='*'){
            $field=true;
        }elseif(!is_array($tmp_field)){
            $field=explode(',',$tmp_field);
        }
		$db=$del?$this->model::withTrashed()->field($field):$this->model::field($field);
		if($wh)$db=$db->where($wh);
		if($page){
            if($page===true){
                return $db->count();
            }else{
				if($order){
					$order=order_to_arr($order);
					$db=$db->order($order);
				}
                $db=$db->page($page);
            }
        }
		$data=$db->select();
		return $data;
	}
	
	
}
