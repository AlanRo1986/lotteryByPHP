<?php

define("APP_Alan",1);

if (!defined("ROOT_PATH")){
	define('ROOT_PATH', str_replace('index.php', '', str_replace('\\', '/', __FILE__)));
}

//网站根目录
define('APP_ROOT','/');

require ROOT_PATH."system/common.php";	//函数程序预处理

require SYSTEM_PATH."app.php";	//路由分配

App::run();

?>