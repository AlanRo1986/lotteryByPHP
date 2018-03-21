<?php
// +----------------------------------------------------------------------
// |  lanxinFrame
// +----------------------------------------------------------------------
// | Copyright (c) 2011 http://www.lanxinbase.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: Alan(341455770@qq.com)
// +----------------------------------------------------------------------

if (!defined('APP_Alan')) {
	exit ( 'Access Denied!' );
}

class adminBaseHome{
	public function __construct(){
		require_once 'common.php';
		self::_checkLogin();
		self::_checkAuth();
		
		require ROOT_PATH.'wap/Lib/page.php';
		require SYSTEM_PATH."utils/es_imagecls.php";
		
	}
	
	/**
	 * 成功提示函数
	 * @param string $message 提示信息内容
	 * @param string $url 跳转地址
	 * @param int $time 多少秒跳转，默认3秒
	 */
	public function success($message, $url = '', $time = 3) {
		$link = $js_link = '';
		if(empty($url)) {
			$link = 'javascript:window.history.back();';
			$js_link = 'window.history.back();';
		} else {
			$link = $url;
			$js_link = 'window.location.href = "'.$url.'";'; // 如果 $url 为空，跳转到上一页，否则跳转到指定的页面
		}
		$alertmsg = lang('ALERTMSG');
		$alertmsg = str_replace("{time}", $time, $alertmsg);
		$alertmsg = str_replace("{link}", $link, $alertmsg);

		echo <<<HTML
<!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8">
<title>操作成功</title>
<style type="text/css">
body { margin-top:150px; font: 12px/1.9 Arial, Helvetica, sans-serif; }
#message { margin:0 auto; width:500px; text-align:center; }
#header { padding:15px 10px; border-bottom:1px dotted blue; font-weight:bold; }
#message { border:2px solid #3fa9f5; }
#header { color:#1FD52F; }
#footer { padding:10px 5px; }
#time { font-weight:bold; }
#time, #footer a { color:#FF0000; }
</style>
</head>
	
<body>
<div id="message">
    <div id="header"> $message </div>
    <div id="footer"> {$alertmsg} </div>
</div>
<script type="text/javascript">
var int = 0, i = $time;
int = window.setInterval(function() {
	i--;
	document.getElementById('time').innerHTML = i;
	if(i == 0) {
		window.clearInterval(int);
		$js_link
	}
}, 1000);
</script>
</body>
</html>
HTML;
		exit;
	}
	/**
	 * 错误提示函数
	 * @param string $message 提示信息内容
	 * @param string $url 跳转地址
	 * @param int $time 多少秒跳转，默认3秒
	 */
	public function error($message, $url = '', $time = 3) {
		global $_lang;
		$link = $js_link = '';
		if(empty($url)) {
			$link = 'javascript:window.history.back();';
			$js_link = 'window.history.back();';
		} else {
			$link = $url;
			$js_link = 'window.location.href = "'.$url.'";'; // 如果 $url 为空，跳转到上一页，否则跳转到指定的页面
		}
		$alertmsg = lang('ALERTMSG');
		$alertmsg = str_replace("{time}", $time, $alertmsg);
		$alertmsg = str_replace("{link}", $link, $alertmsg);
		echo <<<HTML
<!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8">
<title>错误页面提示</title>
<style type="text/css">
body { margin-top:150px; font: 12px/1.9 Arial, Helvetica, sans-serif; }
#message { margin:0 auto; width:500px; text-align:center; }
#header { padding:15px 10px; border-bottom:1px dotted blue; font-weight:bold; }
#message { border:2px solid #F00; }
#header { color:#F00; }
#footer { padding:10px 5px; }
#time { font-weight:bold; }
#time, #footer a { color:#FF0000; }
</style>
</head>
	
<body>
<div id="message">
    <div id="header"> $message </div>
    <div id="footer"> {$alertmsg} </div>
</div>
<script type="text/javascript">
var int = 0, i = $time;
int = window.setInterval(function() {
	i--;
	document.getElementById('time').innerHTML = i;
	if(i == 0) {
		window.clearInterval(int);
		$js_link
	}
}, 1000);
</script>
</body>
</html>
HTML;
		exit;
	}
	
	
	private static function _checkLogin(){
		//管理员的SESSION
		$admin_time = es_session::get('admin_time');
		if($admin_time < TIME_UTC){es_session::delete('admin_info');es_session::delete('admin_time');}
		
		$admin_info = es_session::get('admin_info');
	
		
		if($admin_info['id'] == 0 && MODULE_NAME != 'Public'){
			app_redirect(_PHP_FILE_."?ctl=login");
		}elseif ($admin_info['id'] > 0){
			$menu = require_once SYSTEM_PATH."menu.php";
			$GLOBALS['tmpl']->assign('menu',$menu);
			
			$admin_info = $GLOBALS['db']->getRow("SELECT * FROM ".DB_PREFIX."admin where id=".$admin_info['id']);
			es_session::set('admin_info',$admin_info);
			es_session::set('admin_time',TIME_UTC+3600);
		}
		
	}
	
	private static function _checkAuth(){
		//不是默认管理员进行权限设置
		if($GLOBALS['admin_info']['is_default'] != 1){
			
		}
	
	}
	
	/**
	 * 上传图片的通公基础方法
	 *
	 * @return array
	 */
	protected function uploadImage()
	{
		if(app_conf("WATER_MARK")!="")
			$water_mark = ROOT_PATH.app_conf("WATER_MARK");  //水印
		else
			$water_mark = "";
		$alpha = app_conf("WATER_ALPHA");   //水印透明
		$place = app_conf("WATER_POSITION");  //水印位置
		 
		$upImg = new es_imagecls();
		
		//设置上传文件大小
		$upImg->max_size  = app_conf('MAX_IMAGE_SIZE') ;  /* 配置于config */
		$file = array();
		$k = 0;
		foreach ($_FILES as $v){
			$upImg->init($v);
			$res = $upImg->save();
			
			if($res){
				if(file_exists($water_mark)){
					$upImg->water($res['local_target'],$water_mark,$alpha,$place);
				}
				
				//登记上传文件的扩展信息
				$file[$k] =  $res;
				$file[$k]['extension']  = $res['name'].$res['ext'];
				$file[$k]['savepath']   = $res['target'];
				$file[$k]['savename']   = $res['name'].$res['ext'];
				$file[$k]['recpath'] = $res['target'];
				$file[$k]['bigrecpath'] = $res['local_target'];
				
			}else{
				$file = false;
			}
			$k++;
		}
		if($file){
			return array("status"=>1,'data'=>$file,'info'=>'上传成功');
		}else{
			return array("status"=>0,'data'=>null,'info'=>$upImg->error());
		}
		
	}
	
	
	/**
	 * 上传文件公共基础方法
	 *
	 * @return array
	 */
	protected function uploadFile(){
	
		$upImg = new es_imagecls();
		
		//设置上传文件大小
		$upImg->max_size  = app_conf('MAX_IMAGE_SIZE') ;  /* 配置于config */
		
		$file = array();
		$k = 0;
		foreach ($_FILES as $v){
			$upImg->init($v);
			$res = $upImg->save();
				
			if($res){
		
				//登记上传文件的扩展信息
				$file[$k] =  $res;
				$file[$k]['extension']  = $res['name'].$res['ext'];
				$file[$k]['savepath']   = $res['target'];
				$file[$k]['savename']   = $res['name'].$res['ext'];
				$file[$k]['recpath'] = $res['target'];
				$file[$k]['bigrecpath'] = $res['local_target'];
				$k++;
			}else{
				$file = false;
			}
		}
		if($file){
			return array("status"=>1,'data'=>$file,'info'=>'上传成功');
		}else{
			return array("status"=>0,'data'=>null,'info'=>$upImg->error());
		}
	
		
	}
}
?>