<?php
namespace app\System\controller;
class Param{
    public function jsFile(){
		$data=[
			'os'=>get_user_os(),
			'view'=>get_user_browser(),
			'static_url'=>config('static_url'),
			'upload_url'=>url('File/Upload/postFile'),
			'upload_img_url'=>url('File/Upload/postImg'),
			'upload_wximg_url'=>url('File/Upload/postWxImg'),
		];
		$html='_sys={};';
		foreach($data as $k=>$v){$html.='_sys.'.$k.'="'.$v.'";';}
		//$request =request();
		header('Content-Type: application/x-javascript; charset=UTF-8');
		echo $html;
		exit();
    }
}