<?php

// +----------------------------------------------------------------------
// |  lanxinFrame-系统公共函数
// +----------------------------------------------------------------------
// | Copyright (c) 2011 http://www.lanxinbase.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: Alan(341455770@qq.com)
// +----------------------------------------------------------------------
define("FILE_PATH",""); //文件目录，空为根目录
define('APP_Alan',1);

if (!defined("ROOT_PATH")){
	define('ROOT_PATH', str_replace('verify.php', '', str_replace('\\', '/', __FILE__)));
}

//网站根目录
define('APP_ROOT','/');

require ROOT_PATH."system/common.php";	//函数程序预处理


if ($VerifyType == 1){
    require_once SYSTEM_PATH."libs/verify.php";	//函数程序预处理

    $verify = new Verify();
    $verify->length = 6;
    $verify->bg = array(243, 251,mt_rand(0, 255));
    $verify->entry();
}elseif ($VerifyType == 0){
    require_once SYSTEM_PATH."utils/es_image.php";
    es_image::buildImageVerify(6,1);
}

?>