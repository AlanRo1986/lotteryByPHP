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

class es_cookie
{
    // 判断Cookie是否存在
    static function is_set($name) {
        return isset($_COOKIE[$name]);
    }

    // 获取某个Cookie值
    static function get($name) {
        $value   = $_COOKIE[$name];
        return $value;
    }

    // 设置某个Cookie值
    static function set($name,$value,$expire='',$path='',$domain='') {
    	$path = app_conf("COOKIE_PATH");
     	$domain = app_conf("COOKIE_DOMAIN");
        $expire =   !empty($expire)?TIME_UTC+$expire:TIME_UTC+app_conf('COOKIE_EXPIRE');
        setcookie($name, $value,$expire,$path,$domain);

    }

    // 删除某个Cookie值
    static function delete($name) {
        es_cookie::set($name,'',0);
    }

    // 清空Cookie值
    static function clear() {
        unset($_COOKIE);
    }
}
?>