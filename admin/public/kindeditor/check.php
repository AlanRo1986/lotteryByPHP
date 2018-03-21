<?php
if(!defined('ROOT_PATH')) 
	define('ROOT_PATH', str_replace('admin/public/kindeditor/check.php', '', str_replace('\\', '/', __FILE__)));

require ROOT_PATH.'/system/common.php';


$admin_info = es_session::get('admin_info');

if($admin_info['id'] == 0 && !es_session::get("user_info")){
	app_redirect("404.html");
	exit();
}
?>
