<?php
// +----------------------------------------------------------------------
// |  lanxinFrame-管理后台公共函数
// +----------------------------------------------------------------------
// | Copyright (c) 2011 http://www.lanxinbase.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: Alan(341455770@qq.com)
// +----------------------------------------------------------------------

if (!defined('APP_Admin')) {
	exit ( 'Access Denied!' );
}


/**
 * 管理员保存日志
 * @param string $msg
 * @param number $status
 */
function saveLog($msg = '',$status = 1){
	$admin_info = es_session::get('admin_info');
	$newArr = array();
	$newArr['log_info'] = $msg;
	$newArr['log_time'] = TIME_UTC;
	$newArr['log_admin'] = $admin_info['id'];
	$newArr['log_ip'] = CLIENT_IP;
	$newArr['log_status'] = $status;
	$newArr['module'] = MODULE_NAME;
	$newArr['action'] = ACTION_NAME;
	
	return $GLOBALS['db']->autoExecute('admin_log',$newArr);
}

function filter($str){
	$str = str_replace("'","\\'",$str );
	return $str;
}
?>