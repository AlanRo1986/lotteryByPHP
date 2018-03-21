<?php
// +----------------------------------------------------------------------
// |  lanxinFrame-系统核心处理程序
// +----------------------------------------------------------------------
// | Copyright (c) 2011 http://www.lanxinbase.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: Alan(341455770@qq.com)
// +----------------------------------------------------------------------

if (!defined ('APP_Alan')) {
	exit ( 'Access Denied' );
}

//记录系统启动时间
if (PHP_VERSION >= '5.0.0'){
	$begin_run_time = @microtime(true);
}else{
	$begin_run_time = @microtime();
}

@set_magic_quotes_runtime (0);

define('MAGIC_QUOTES_GPC',get_magic_quotes_gpc()?True:False);

if(!defined('IS_CGI'))
	define('IS_CGI',substr(PHP_SAPI, 0,3)=='cgi' ? 1 : 0 );

if(!defined('_PHP_FILE_')) {
	if(IS_CGI) {
		//CGI/FASTCGI模式下
		$_temp  = explode('.php',$_SERVER["PHP_SELF"]);// /index.php
		define('_PHP_FILE_',  rtrim(str_replace($_SERVER["HTTP_HOST"],'',$_temp[0].'.php'),'/'));
	}else {
		define('_PHP_FILE_',  rtrim($_SERVER["SCRIPT_NAME"],'/'));
	}
}

//定义$_SERVER['REQUEST_URI']兼容性
if (!isset($_SERVER['REQUEST_URI'])){
	if (isset($_SERVER['argv'])){
		$uri = $_SERVER['PHP_SELF'] .'?'. $_SERVER['argv'][0];
	}else{
		$uri = $_SERVER['PHP_SELF'] .'?'. $_SERVER['QUERY_STRING'];
	}
	$_SERVER['REQUEST_URI'] = $uri;
}



if(function_exists('filter_request')){
    filter_request($_GET);
    filter_request($_POST);
}


if(!IS_DEBUG){
	ini_set("display_errors", false);
	error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);
}else{
	error_reporting(0);
}

if(!defined('APP_ROOT')) {
	// 网站URL根目录
	$_root = dirname(_PHP_FILE_);
	$_root = (($_root=='/' || $_root=='\\')?'':$_root);
	$_root = str_replace("/system","",$_root);
	define('APP_ROOT', $_root  );
}

//引入时区配置及定义时间函数
if(function_exists('date_default_timezone_set') && function_exists('app_conf'))
	date_default_timezone_set(app_conf('DEFAULT_TIMEZONE'));


define("TIME_UTC",get_gmtime());   //当前UTC时间戳
define("CLIENT_IP",get_client_ip());  //当前客户端IP
define("SITE_DOMAIN",get_domain());   //站点域名
define("DB_PREFIX",app_conf('DB_PREFIX'));


//加载数据库
require SYSTEM_PATH.'db/db.php';

$pconnect = false;
$db = new mysql_db(app_conf('DB_HOST').":".app_conf('DB_PORT'), app_conf('DB_USER'),app_conf('DB_PWD'),app_conf('DB_NAME'),app_conf('DB_CHAR'),$pconnect);

//载入日志类
require_once SYSTEM_PATH."utils/logger.php";
//logger::write('测试系统');


//定义模板引擎
require  SYSTEM_PATH.'template/template.php';
$tmpl = new AppTemplate;

if (IS_DEBUG){
	$tmpl->caching = false;
}else {
	$tmpl->caching = true;
}

$tmpl->assign("APP_URL",SITE_DOMAIN.APP_ROOT);
$tmpl->assign("APP_ROOT",APP_ROOT);
$tmpl->assign("__APP__",_PHP_FILE_);

$_REQUEST = array_merge($_GET,$_POST);
filter_injection($_REQUEST);

function run_info()
{

	if(!SHOW_DEBUG) return "";

	$query_time = number_format($GLOBALS['db']->queryTime,6);

	if($GLOBALS['begin_run_time']==''||$GLOBALS['begin_run_time']==0)
	{
		$run_time = 0;
	}
	else
	{
		if (PHP_VERSION >= '5.0.0')
		{
			$run_time = number_format(microtime(true) - $GLOBALS['begin_run_time'], 6);
		}
		else
		{
			list($now_usec, $now_sec)     = explode(' ', microtime());
			list($start_usec, $start_sec) = explode(' ', $GLOBALS['begin_run_time']);
			$run_time = number_format(($now_sec - $start_sec) + ($now_usec - $start_usec), 6);
		}
	}

	/* 内存占用情况 */
	if (function_exists('memory_get_usage'))
	{
		$unit=array('B','KB','MB','GB');
		$size = memory_get_usage();
		$used = @round($size/pow(1024,($i=floor(log($size,1024)))),2).' '.$unit[$i];
		$memory_usage = $used;
	}
	else
	{
		$memory_usage = '';
	}


	$str = '查询总数:['.$GLOBALS['db']->queryCount.'] 查询时间:['.$query_time.'] 内存使用情况:['.$memory_usage.'] 系统启动时间:['.$run_time.']<br>';

	foreach($GLOBALS['db']->queryLog as $K=>$sql)
	{
		if($K==0)$str.="<br />SQL语句列表：";
		$str.="<br />行".($K+1).":".$sql;
	}

	return "<div style='width:100%; padding:10px; line-height:22px; border:1px solid #ccc; text-align:left; margin:30px auto; font-size:14px; color:#999; height:auto; overflow-y:auto;'>".$str."</div>";
}


$lang_file = ROOT_PATH.'wap/Lang/'.app_conf("DEFAULT_LANG").'/lang.php';
if(file_exists($lang_file))
	$langwap = require_once $lang_file;

$lang_file = ROOT_PATH.'app/Lang/'.app_conf("DEFAULT_LANG").'/lang.php';
if(file_exists($lang_file))
	$langapp = require_once $lang_file;
	
$lang_file = ROOT_PATH.'admin/Lang/'.app_conf("DEFAULT_LANG").'/lang.php';

if(file_exists($lang_file))
	$langadmin = require_once $lang_file;

$lang = array_merge($langwap,$langapp,$langadmin);

//语言文件
function lang($key)
{
	$key = strtoupper($key);
	if(isset($GLOBALS['lang'][$key]))
	{
		return $GLOBALS['lang'][$key];
	}else{
		return $key;
	}
}
require_once SYSTEM_PATH.'utils/es_cookie.php';
require_once SYSTEM_PATH.'utils/es_string.php';
require_once SYSTEM_PATH.'libs/validate.php';
require_once SYSTEM_PATH.'utils/mail_sender.php';
require_once SYSTEM_PATH.'service/ConfService.php';
//require_once SYSTEM_PATH.'utils/es_sms.php';


if (app_conf('SESSION_AUTO_START')){
	require_once SYSTEM_PATH."utils/es_session.php";
	@es_session::start();
	$user_info = es_session::get('user_info');
	$tmpl->assign("user_info",$user_info);
	
}



function gzip_out($content)
{
	header("Content-type: text/html; charset=utf-8");
	//header("Cache-control: private");  //支持页面回跳
	//$gzip = app_conf("GZIP_ON");
	if( intval($gzip) == 1 )
	{
		if(!headers_sent() && extension_loaded("zlib") && preg_match("/gzip/i",$_SERVER["HTTP_ACCEPT_ENCODING"]))
		{
			$content = gzencode($content,9);
			header("Content-Encoding: gzip");
			header("Content-Length: ".strlen($content));
			echo $content;
		}
		else
			echo $content;
	}else{
		echo $content;
	}

}

$dist_cfg = ROOT_PATH."config/dist_cfg.php";
if(file_exists($dist_cfg))
    $distribution_cfg = require_once $dist_cfg;

//定义缓存
if(!function_exists("load_cache"))
{
    function load_cache()
    {
        global $distribution_cfg;
        $type = $distribution_cfg["CACHE_TYPE"];
        $cacheClass = 'Cache'.ucwords(strtolower(strim($type)))."Service";
        if(file_exists(SYSTEM_PATH."cache/".$cacheClass.".php"))
        {
            require_once SYSTEM_PATH."cache/".$cacheClass.".php";
            if(class_exists($cacheClass))
            {
                $cache = new $cacheClass();
            }
            return $cache;
        }
        else
        {
            $file_cache_file = SYSTEM_PATH.'cache/CacheFileService.php';
            if(file_exists($file_cache_file))
                require_once SYSTEM_PATH.'cache/CacheFileService.php';
            if(class_exists("CacheFileService"))
                $cache = new CacheFileService();
            return $cache;
        }
    }
}

$cache_service_file = SYSTEM_PATH."cache/Cache.php";
if(file_exists($cache_service_file))
    require_once $cache_service_file;
if(class_exists("CacheService"))
    $cache = CacheService::getInstance();
//end 定义缓存


//生成后台配置的动态参数
if(function_exists("update_sys_config")){
	update_sys_config();
}
?>