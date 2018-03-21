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

class appBaseHome{
	public function __construct(){
		
		if(app_conf("WWW_OPEN")==1){
			$GLOBALS['tmpl']->assign("page_title",'网站临时关闭');
			$GLOBALS['tmpl']->assign("html",app_conf("CLOSE_HTML"));
			$GLOBALS['tmpl']->display("shop_close.html");
			exit;
		}
		require_once SYSTEM_PATH . 'libs/page.php';

	}
}
?>