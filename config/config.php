<?php
if (!defined('APP_Alan')) {
	exit ( 'Access Denied!' );
}

if (!file_exists ( ROOT_PATH . "config/db_config.php" ))
	exit ( 'No dbConf file!' );

//数据库配置
$db_config = require ROOT_PATH . "config/db_config.php";

//加载系统配置信息
if(file_exists(ROOT_PATH.'public/sys_config.php'))
{
	$sys_conf =	require ROOT_PATH.'public/sys_config.php';
}else {
    $sys_conf = array();
}

//时区
$timezone = array ('DEFAULT_TIMEZONE' => 'PRC');


$config = array(
		
		//控制器的后缀
		'LIB_PREFIX'	        =>	'Module.class',

		/* 默认设定 */
		'DEFAULT_CTL'           =>  'index',	// 默认的控制器层名称
		'DEFAULT_ACT'           =>  'index', 	// 默认的控制器层名称
		'DEFAULT_LANG'          =>  'zh-cn', 	// 默认语言
		'DEFAULT_THEME'         =>  'default',	// 默认模板主题名称
		'DEFAULT_MODULE'        =>  'app', 		// 默认模块
		'DEFAULT_TIMEZONE'      =>  'PRC',		// 默认时区
		'URL_MODEL'	=>	1,						//0 (普通模式); 1 (PATHINFO 模式);

		'COOKIE_EXPIRE'         =>  3600,        // Cookie有效期
		'COOKIE_DOMAIN'         =>  '',     	 // Cookie有效域名
		'COOKIE_PATH'           =>  '/',    	 // Cookie路径

		'SESSION_AUTO_START'    =>  true,   	  // 是否自动开启Session
		'AUTH_KEY'              =>  'LanXinByAlan_',
		'AUTH_KEY_APP'              =>  'a4e938a46575644578f0ee7d223307931',

		/*其他定义*/
		'GZIP_ON'				=>	0,		    //0不压缩 1压缩
		'TEMPLATE'				=>	'default',	//默认模板
		'CURRENCY_UNIT'			=>	'￥'	,
		'VERIFY_IMAGE'			=>	1,			//是否开启验证码

		'ALLOW_IMAGE_EXT'	=>	'jpg,gif,png,bmp',
		'MAX_IMAGE_SIZE'	=>  '3000000',	//上传的图片大小限制
		'WATER_MARK'	=>	'',	//水印图片
		'WATER_ALPHA'	=>	'75',
		'WATER_POSITION'	=>	'4',


        /*站点关闭*/
        'WWW_OPEN'	=>	0,	//0.开启,1.关闭
        'CLOSE_HTML'	=>	'网站升级中,暂时关闭....',
        'TIME_ZONE'				=>	0,          //时区+8
        'PAGE_SIZE' =>  20,                     //商品页显示数

//        'password'  => md5("123456"),
//        'SITE_DOMAIN'  => "tmp.com",
//        'SITENAME'  => "EightCap易汇",
//        'KeyWords'  => "EightCap易汇,圣诞大抽奖",
//        'Description'  => "EightCap易汇,圣诞大抽奖",
//
//        'email_host'  => "smtp.ym.163.com",
//        'email_port'  => 25,
//        'email_id'  => "service@lanxinbase.com",
//        'email_pass'  => "lzp1124",
//        'email_addr'  => "service@lanxinbase.com",
//        'lotteryStartTime'  => "2017-10-20 00:00:00",
//        'lotteryEndTime'  => "2017-12-20 00:00:00",
//        'copyEmail'  => "lanxine@qq.com",



);

//合成数组
$config = array_merge($db_config,$config,$timezone,$sys_conf);

//初始化目录
if(!file_exists(ROOT_PATH.'public/runtime/'))
	mkdir(ROOT_PATH.'public/runtime/',0777);
if(!file_exists(ROOT_PATH.'public/runtime/app/'))
	mkdir(ROOT_PATH.'public/runtime/app/',0777);
if(!file_exists(ROOT_PATH.'public/runtime/app/db_caches/'))
	mkdir(ROOT_PATH.'public/runtime/app/db_caches/',0777);
if(!file_exists(ROOT_PATH.'public/runtime/admin/db_caches/'))
	mkdir(ROOT_PATH.'public/runtime/admin/db_caches/',0777);
if(!file_exists(ROOT_PATH.'public/runtime/wap/db_caches/'))
	mkdir(ROOT_PATH.'public/runtime/wap/db_caches/',0777);
if(!file_exists(ROOT_PATH.'public/runtime/admin/'))
	mkdir(ROOT_PATH.'public/runtime/admin/',0777);
if(!file_exists(ROOT_PATH.'public/runtime/wap/'))
	mkdir(ROOT_PATH.'public/runtime/wap/',0777);
if(!file_exists(ROOT_PATH.'public/runtime/app/cache/'))
	mkdir(ROOT_PATH.'public/runtime/app/cache/',0777);
if(!file_exists(ROOT_PATH.'public/runtime/admin/cache/'))
	mkdir(ROOT_PATH.'public/runtime/admin/cache/',0777);
if(!file_exists(ROOT_PATH.'public/runtime/wap/cache/'))
	mkdir(ROOT_PATH.'public/runtime/wap/cache/',0777);
if(!file_exists(ROOT_PATH.'public/logger/'))
	mkdir(ROOT_PATH.'public/logger/',0777);
if(!file_exists(ROOT_PATH.'public/runtime/app/tpl_caches/'))
	mkdir(ROOT_PATH.'public/runtime/app/tpl_caches/',0777);
if(!file_exists(ROOT_PATH.'public/runtime/app/tpl_compiled/'))
	mkdir(ROOT_PATH.'public/runtime/app/tpl_compiled/',0777);
if(!file_exists(ROOT_PATH.'public/runtime/wap/tpl_caches/'))
	mkdir(ROOT_PATH.'public/runtime/wap/tpl_caches/',0777);
if(!file_exists(ROOT_PATH.'public/runtime/wap/tpl_compiled/'))
	mkdir(ROOT_PATH.'public/runtime/wap/tpl_compiled/',0777);
if(!file_exists(ROOT_PATH.'public/runtime/admin/tpl_caches/'))
	mkdir(ROOT_PATH.'public/runtime/admin/tpl_caches/',0777);
if(!file_exists(ROOT_PATH.'public/runtime/admin/tpl_compiled/'))
	mkdir(ROOT_PATH.'public/runtime/admin/tpl_compiled/',0777);
if(!file_exists(ROOT_PATH.'public/session/'))
	mkdir(ROOT_PATH.'public/session/',0777);
if(!file_exists(ROOT_PATH.'public/upload/'))
	mkdir(ROOT_PATH.'public/upload/',0777);
if(!file_exists(ROOT_PATH.'public/runtime/data/'))
    mkdir(ROOT_PATH.'public/runtime/data/',0777);


?>