<?php
// +----------------------------------------------------------------------
// |  lanxinFrame
// +----------------------------------------------------------------------
// | Copyright (c) 2011 http://www.lanxinbase.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: Alan(341455770@qq.com)
// +----------------------------------------------------------------------

if (!defined ('APP_Alan')) {
    exit ( 'Access Denied' );
}
class es_session
{
	static $sess_id = "";
	static function id()
	{
		if(self::$sess_id)
			return self::$sess_id;
		else
			return session_id();
	}
	static function set_sessid($sess_id)
	{
		self::$sess_id = $sess_id;
	}
	static function start()
	{
		session_save_path(ROOT_PATH.'public/session');
		@session_start();
	}

	// 判断session是否存在
	static function is_set($name) {
		self::start();
		$tag = isset($_SESSION[app_conf("AUTH_KEY").$name]);
		self::close();
		return $tag;
	}

	// 获取某个session值
	static function get($name) {
		self::start();
		$value   = $_SESSION[app_conf("AUTH_KEY").$name];
		self::close();
		return $value;
	}

	// 设置某个session值
	static function set($name,$value) {
		self::start();
		$_SESSION[app_conf("AUTH_KEY").$name]  =   $value;
		self::close();
	}

	// 删除某个session值
	static function delete($name) {
		self::start();
		unset($_SESSION[app_conf("AUTH_KEY").$name]);
		self::close();
	}

	// 清空session
	static function clear() {
		@session_destroy();
	}

	//关闭session的读写
	static function close()
	{
		@session_write_close();
	}

	static function  is_expired()
    {
    	self::start();
        if (isset($_SESSION[app_conf("AUTH_KEY")."expire"]) && $_SESSION[app_conf("AUTH_KEY")."expire"] < TIME_UTC) {
            $tag =  true;
        } else {
        	$_SESSION[app_conf("AUTH_KEY")."expire"] = TIME_UTC+(intval(app_conf("EXPIRED_TIME"))*60);
            $tag = false;
        }
        return $tag;
        self::close();
    }

}
//end session
?>
