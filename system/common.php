<?php
// +----------------------------------------------------------------------
// |  lanxinFrame-系统公共函数
// +----------------------------------------------------------------------
// | Copyright (c) 2011 http://www.lanxinbase.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: Alan(341455770@qq.com)
// +----------------------------------------------------------------------

if (!defined('APP_Alan')) {
	exit ( 'Access Denied!' );
}

require ROOT_PATH."system/define.php";
require ROOT_PATH."config/config.php";	//读取配置文件
require SYSTEM_PATH . "system_init.php";

/**
 * 生成缩略图函数（支持图片格式：gif、jpeg、png和bmp）
 *
 * @param string $src源图片路径
 * @param int $width缩略图宽度（只指定高度时进行等比缩放）
 * @param int $width缩略图高度（只指定宽度时进行等比缩放）
 * @param string $filename保存路径（不指定时直接输出到浏览器）
 * @return bool
 */

/**
 * 获取GMTime
 *
 * @return number
 */
function get_gmtime() {
	//return (time () - date ( 'Z' ));
    return (time ());
}

/**
 * 格式化linux时间戳
 *
 * @param number $utc_time
 * @param string $format
 * @return string
 */
function to_date($utc_time, $format = 'Y-m-d H:i:s') {
	if (empty ( $utc_time )) {
		return '';
	}
	$timezone = intval ( app_conf ( 'TIME_ZONE' ) );
	$time = $utc_time + $timezone * 3600;
	return date ( $format, $time );
}
/**
 * 将文本时间转换成时间戳 时间格式:Y-m-d H:i:s
 *
 * @param string $str
 * @return number
 */
function to_strtimespan($str) {
	$timezone = intval ( app_conf ( 'TIME_ZONE' ) );
	$time = intval ( strtotime ( $str ) );
	if ($time != 0)
		$time = $time - $timezone * 3600;
	return $time;
}

/**
 * 判断是否手机访问
 *
 * @return boolean
 */
function isMobile() {
	// 如果有HTTP_X_WAP_PROFILE则一定是移动设备
	if (isset ( $_SERVER ['HTTP_X_WAP_PROFILE'] )) {
		return true;
	}
	
	// 如果via信息含有wap则一定是移动设备,部分服务商会屏蔽该信息
	if (isset ( $_SERVER ['HTTP_VIA'] )) {
		// 找不到为flase,否则为true
		return stristr ( $_SERVER ['HTTP_VIA'], "wap" ) ? true : false;
	}
	
	// 判断手机发送的客户端标志,兼容性有待提高
	if (isset ( $_SERVER ['HTTP_USER_AGENT'] )) {
		$clientkeywords = array (
				'nokia',
				'sony',
				'ericsson',
				'mot',
				'samsung',
				'htc',
				'sgh',
				'lg',
				'sharp',
				'sie-',
				'philips',
				'panasonic',
				'alcatel',
				'lenovo',
				'iphone',
				'ipod',
				'blackberry',
				'meizu',
				'android',
				'netfront',
				'symbian',
				'ucweb',
				'windowsce',
				'palm',
				'operamini',
				'operamobi',
				'openwave',
				'nexusone',
				'cldc',
				'midp',
				'wap',
				'mobile'
		);
		// 从HTTP_USER_AGENT中查找手机浏览器的关键字
		if (preg_match ( "/(" . implode ( '|', $clientkeywords ) . ")/i", strtolower ( $_SERVER ['HTTP_USER_AGENT'] ) )) {
			return true;
		}
		
	}
	// 协议法，因为有可能不准确，放到最后判断
	if (isset ( $_SERVER ['HTTP_ACCEPT'] )) {
		// 如果只支持wml并且不支持html那一定是移动设备
		// 如果支持wml和html但是wml在html之前则是移动设备
		if ((strpos ( $_SERVER ['HTTP_ACCEPT'], 'vnd.wap.wml' ) !== false) && (strpos ( $_SERVER ['HTTP_ACCEPT'], 'text/html' ) === false || (strpos ( $_SERVER ['HTTP_ACCEPT'], 'vnd.wap.wml' ) < strpos ( $_SERVER ['HTTP_ACCEPT'], 'text/html' )))) {
			return true;
		}
	}
}
/**
 * 过滤请求
 *
 * @param unknown $request
 */
function filter_request(&$request) {
	if (MAGIC_QUOTES_GPC) {
		foreach ( $request as $k => $v ) {
			if (is_array ( $v )) {
				filter_request ( $request [$k] );
			} else {
				$request [$k] = stripslashes ( trim ( $v ) );
			}
		}
	}
}
/**
 * 在每个请求包含$_GET and $_POST为每个双引号添加反斜杠
 *
 * @param unknown $request
 */
function adddeepslashes(&$request) {
	foreach ( $request as $k => $v ) {
		if (is_array ( $v )) {
			adddeepslashes ( $request [$k] );
		} else {
			$request [$k] = addslashes ( trim ( $v ) );
		}
	}
}
/**
 * 读取配置信息
 *
 * @param string $name
 * @return string
 */
function app_conf($name) {
	return stripslashes ( $GLOBALS ['config'] [$name] );
}
/**
 * url跳转
 *
 * @param string $url="?ctl=login"
 * @param number $time秒
 * @param string $msg显示信息
 */
function app_redirect($url, $time = 0, $msg = '') {
	if (! defined ( "SITE_DOMAIN" ))
		define ( "SITE_DOMAIN", get_domain () );
		// 多行URL地址支持
	$url = str_replace ( array (
			"\n",
			"\r"
	), '', $url );

	if (empty ( $msg ))
		$msg = "系统将在{$time}秒之后自动跳转到{$url}！";
	if (! headers_sent ()) {
		if (0 === $time) {
			if (substr ( $url, 0, 1 ) == "/") {
				header ( "Location:" . SITE_DOMAIN . $url );
			} else {
				header ( "Location:" . $url );
			}
		} else {
			header ( "refresh:{$time};url={$url}" );
			echo ($msg);
		}
		exit ();
	} else {
		$str = "<meta http-equiv='Refresh' content='{$time};URL={$url}'>";
		if ($time != 0)
			$str .= $msg;
		exit ( $str );
	}
}
/**
 * 获取本站域名包含http://
 *
 * @return string
 */
function get_domain() {
	/* 协议 */
	$protocol = get_http ();
	$host = '';
	/* 域名或IP地址 */
	if (isset ( $_SERVER ['HTTP_X_FORWARDED_HOST'] )) {
		$host = $_SERVER ['HTTP_X_FORWARDED_HOST'];
	} elseif (isset ( $_SERVER ['HTTP_HOST'] )) {
		$host = $_SERVER ['HTTP_HOST'];
	} else {
		/* 端口 */
		if (isset ( $_SERVER ['SERVER_PORT'] )) {
			$port = ':' . $_SERVER ['SERVER_PORT'];

			if ((':80' == $port && 'http://' == $protocol) || (':443' == $port && 'https://' == $protocol)) {
				$port = '';
			}
		} else {
			$port = '';
		}

		if (isset ( $_SERVER ['SERVER_NAME'] )) {
			$host = $_SERVER ['SERVER_NAME'] . $port;
		} elseif (isset ( $_SERVER ['SERVER_ADDR'] )) {
			$host = $_SERVER ['SERVER_ADDR'] . $port;
		}
	}

	return $protocol . $host;
}
/**
 * 获取协议http或https
 * @return string
 */
function get_http() {
	return (isset ( $_SERVER ['HTTPS'] ) && (strtolower ( $_SERVER ['HTTPS'] ) != 'off')) ? 'https://' : 'http://';
}
/**
 * 获取域名不包含HTTP://
 * @return unknown
 */
function get_host()
{
	/* 域名或IP地址 */
	if (isset($_SERVER['HTTP_X_FORWARDED_HOST']))
	{
		$host = $_SERVER['HTTP_X_FORWARDED_HOST'];
	}
	elseif (isset($_SERVER['HTTP_HOST']))
	{
		$host = $_SERVER['HTTP_HOST'];
	}
	else
	{
		if (isset($_SERVER['SERVER_NAME']))
		{
			$host = $_SERVER['SERVER_NAME'];
		}
		elseif (isset($_SERVER['SERVER_ADDR']))
		{
			$host = $_SERVER['SERVER_ADDR'];
		}
	}
	return $host;
}

/**
 * 获取客户端IP地址
 * @param integer $type 返回类型 0 返回IP地址 1 返回IPV4地址数字
 * @param boolean $adv 是否进行高级模式获取（有可能被伪装）
 * @return mixed
 */
function get_client_ip($type = 0,$adv=TRUE) {
    $type       =  $type ? 1 : 0;
    static $ip  =   NULL;
    if ($ip !== NULL) return $ip[$type];
    if($adv){
        if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $arr    =   explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
            $pos    =   array_search('unknown',$arr);
            if(false !== $pos) unset($arr[$pos]);
            $ip     =   trim($arr[0]);
        }elseif (isset($_SERVER['HTTP_CLIENT_IP'])) {
            $ip     =   $_SERVER['HTTP_CLIENT_IP'];
        }elseif (isset($_SERVER['REMOTE_ADDR'])) {
            $ip     =   $_SERVER['REMOTE_ADDR'];
        }
    }elseif (isset($_SERVER['REMOTE_ADDR'])) {
        $ip     =   $_SERVER['REMOTE_ADDR'];
    }
    // IP地址合法验证
    $long = sprintf("%u",ip2long($ip));
    $ip   = $long ? array($ip, $long) : array('0.0.0.0', 0);
    return $ip[$type];
}
/**
 * 后期使用,动态配置生成
 *
 */
function update_sys_config()
{
	$filename = PUBILC_PATH."sys_config.php";
	if(!file_exists($filename))
	{
	    $conf = new ConfService();
        $sys_configs = $conf->getAll();

		$config_str = "<?php //".TIME_UTC."\n";
		$config_str .= "return array(\n";
		foreach($sys_configs as $k=>$v)
		{
			$config_str.="'".$v['name']."'=>'".addslashes($v['content'])."',\n";
		}
		$config_str.=");\n ?>";
		file_put_contents($filename,$config_str);
		$config = require_once $filename;
		$GLOBALS['config'] = array_merge($GLOBALS['config'],$config);

	}
}

/**
 * 解析URL标签
 * @param unknown $str = u:wap|index#index|id=10&name=abc
 * @return unknown
 */
function parse_url_tag($str)
{
	$key = md5("URL_TAG_".$str);
	if(isset($GLOBALS[$key]))
	{
		return $GLOBALS[$key];
	}

	$str = substr($str,2);
	$str_array = explode("|",$str);
	$app_index = $str_array[0];
	$route = $str_array[1];

	$param_tmp = explode("&",$str_array[2]);
	$param = array();
	foreach($param_tmp as $item)
	{
		if($item!='')
			$item_arr = explode("=",$item);
		if($item_arr[0]&&$item_arr[1])
			$param[$item_arr[0]] = $item_arr[1];
	}

	$GLOBALS[$key]= url($app_index,$route,$param,'app');
	return $GLOBALS[$key];
}
/**
 * 封装URL//{url a="index" r="login"}
 * @param string $app_index=wap
 * @param string $route=index#index
 * @param array $param get参数
 * @return unknown|string
 */
function url($app_index,$route="index",$param=array(),$from = 'app')
{
	$key = md5("URL_KEY_".$app_index.$route.serialize($param));


	$route_array = explode("#",$route);

	if(isset($param)&&$param!=''&&!is_array($param))
	{
		$param['id'] = $param;
	}

	$module = strtolower(trim($route_array[0]));
	$action = strtolower(trim($route_array[1]));

	if(!$module)$module="";
	if(!$action)$action="";

	//原始模式
	$url = SITE_DOMAIN."/?m=".$from."&";

	if($module&&$module!='')
		$url .= "ctl=".$module."&";
	if($action&&$action!='')
		$url .= "act=".$action."&";
	if(count($param)>0)
	{
		foreach($param as $k=>$v)
		{
			if($k&&$v)
				$url =$url.$k."=".urlencode($v)."&";
		}
	}

	if(substr($url,-1,1)=='&'||substr($url,-1,1)=='?') $url = substr($url,0,-1);

	return $url;

}
/**
 * 过滤数据库SQL语句注入
 * @param unknown $request
 */
function filter_injection(&$request)
{
	$pattern = "/(select[\s])|(insert[\s])|(update[\s])|(delete[\s])|(from[\s])|(where[\s])/i";
	foreach($request as $k=>$v)
	{
		if(preg_match($pattern,$k,$match))
		{
			die("SQL Injection denied!");
		}

		if(is_array($v))
		{
			filter_injection($v);
		}
		else
		{

			if(preg_match($pattern,$v,$match))
			{
				die("SQL Injection denied!");
			}
		}
	}

}
/**
 * 增加对特殊符号转义,避免SQL注入
 * @param unknown $content
 * @return string
 */
function quotes($content)
{
	if (is_array($content))
	{
		foreach ($content as $key=>$value)
		{
			$content[$key] = addslashes($value);
		}
	} else
	{
		$content = addslashes($content);
	}
	return $content;
}
/**
 * 删首位空并过滤特殊符号增加\转义
 * @param unknown $str
 */
function strim($str)
{
	return quotes(htmlspecialchars(trim($str)));
}
/**
 * 删首位空并过滤特殊符号增加\转义
 * @param unknown $str
 */
function btrim($str)
{
	return quotes(trim($str));
}
/**
 * 判断是否UTF8编码
 * @param unknown $string
 * @return number
 */
function is_u8($string)
{
	return preg_match('%^(?:
		 [\x09\x0A\x0D\x20-\x7E]            # ASCII
	   | [\xC2-\xDF][\x80-\xBF]             # non-overlong 2-byte
	   |  \xE0[\xA0-\xBF][\x80-\xBF]        # excluding overlongs
	   | [\xE1-\xEC\xEE\xEF][\x80-\xBF]{2}  # straight 3-byte
	   |  \xED[\x80-\x9F][\x80-\xBF]        # excluding surrogates
	   |  \xF0[\x90-\xBF][\x80-\xBF]{2}     # planes 1-3
	   | [\xF1-\xF3][\x80-\xBF]{3}          # planes 4-15
	   |  \xF4[\x80-\x8F][\x80-\xBF]{2}     # plane 16
   )*$%xs', $string);
}

/**
 * 清除系统缓存
 */
function clear_cache()
{

	//数据缓存
	clear_dir_file(PUBILC_PATH."runtime/app/tpl_caches/");
	clear_dir_file(PUBILC_PATH."runtime/app/tpl_compiled/");
	clear_dir_file(PUBILC_PATH."runtime/app/db_caches/");
	clear_dir_file(PUBILC_PATH."runtime/app/cache/");

	clear_dir_file(PUBILC_PATH."runtime/wap/tpl_caches/");
	clear_dir_file(PUBILC_PATH."runtime/wap/tpl_compiled/");
	clear_dir_file(PUBILC_PATH."runtime/wap/db_caches/");
	clear_dir_file(PUBILC_PATH."runtime/wap/cache/");
	
	clear_dir_file(PUBILC_PATH."runtime/admin/tpl_caches/");
	clear_dir_file(PUBILC_PATH."runtime/admin/tpl_compiled/");
	clear_dir_file(PUBILC_PATH."runtime/admin/db_caches/");
	clear_dir_file(PUBILC_PATH."runtime/admin/cache/");

	return true;

}
/**
 * 删除目录下含子目录下的缓存文件
 * @param unknown $path
 * @return boolean
 */
function clear_dir_file($path)
{
	if ( $dir = opendir( $path ) )
	{
		while ( $file = readdir( $dir ) )
		{
			$check = is_dir( $path. $file );
			if ( !$check )
			{
				@unlink( $path . $file );
			}
			else
			{
				if($file!='.'&&$file!='..')
				{
					clear_dir_file($path.$file."/");
				}
			}
		}
		closedir( $dir );
		rmdir($path);
		return true;
	}
}
/**
 * 格式化金钱
 * @param unknown $price
 * @param number $decimals
 * @return string
 */
function format_price($price,$decimals=2)
{
	return app_conf("CURRENCY_UNIT")."".number_format($price,2);
}
/**
 * 返回以原数组某个值为下标的新数据
 *
 * @param array $array
 * @param string $key
 * @param int $type 1一维数组2二维数组
 * @return array
 */
function array_under_reset($array, $key, $type=1){
    if (is_array($array)){
        $tmp = array();
        foreach ($array as $v) {
            if ($type === 1){
                $tmp[$v[$key]] = $v;
            }elseif($type === 2){
                $tmp[$v[$key]][] = $v;
            }
        }
        return $tmp;
    }else{
        return $array;
    }
}


/**
 * utf8 字符串截取支持中文
 * @param string $str
 * @param number $start
 * @param number $length
 * @param string $charset
 * @param string $suffix
 * @return string|unknown
 */
function msubstr($str, $start=0, $length=15, $charset="utf-8", $suffix=true)
{
	if(function_exists("mb_substr"))
	{
		$slice =  mb_substr($str, $start, $length, $charset);
		if($suffix&$slice!=$str) return $slice.'...';

		return $slice;
	}
	elseif(function_exists('iconv_substr')) {
		return iconv_substr($str,$start,$length,$charset);
	}
	$re['utf-8']   = "/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|[\xe0-\xef][\x80-\xbf]{2}|[\xf0-\xff][\x80-\xbf]{3}/";
	$re['gb2312'] = "/[\x01-\x7f]|[\xb0-\xf7][\xa0-\xfe]/";
	$re['gbk']    = "/[\x01-\x7f]|[\x81-\xfe][\x40-\xfe]/";
	$re['big5']   = "/[\x01-\x7f]|[\x81-\xfe]([\x40-\x7e]|\xa1-\xfe])/";
	preg_match_all($re[$charset], $str, $match);
	$slice = join("",array_slice($match[0], $start, $length));
	if($suffix&&$slice!=$str) return $slice;
	return $slice;
}

/**
 * JSON兼容
 * JSON编码
 */
if(!function_exists("json_encode"))
{
	function json_encode($data)
	{
		require_once 'libs/json.php';
		$JSON = new JSON();
		return $JSON->encode($data);
	}
}
/**
 * JSON解码
 */
if(!function_exists("json_decode"))
{
	function json_decode($data)
	{
		require_once 'libs/json.php';
		$JSON = new JSON();
		return $JSON->decode($data,1);
	}
}

/**
 * 邮箱格式验证
 * @param string $email
 * @return boolean
 */
function check_email($email)
{
	if(!preg_match("/^\w+((-\w+)|(\.\w+))*\@[A-Za-z0-9]+((\.|-)[A-Za-z0-9]+)*\.[A-Za-z0-9]+$/",$email))
	{
		return false;
	}
	else
		return true;
}

/**
 * 验证手机号码
 * @param unknown $mobile
 * @return boolean
 */
function check_mobile($mobile)
{
	if(!empty($mobile) && !preg_match("/^\d{6,}$/",$mobile))
	{
		return false;
	}
	else
		return true;
}

/**
 * 字符编码转换
 */
if(!function_exists("iconv"))
{
	function iconv($in_charset,$out_charset,$str)
	{
		require 'libs/iconv.php';
		$chinese = new Chinese();
		return $chinese->Convert($in_charset,$out_charset,$str);
	}
}

function unicode_encode($name) {//to Unicode
	$name = iconv('UTF-8', 'UCS-2', $name);
	$len = strlen($name);
	$str = '';
	for($i = 0; $i < $len - 1; $i = $i + 2) {
		$c = $name[$i];
		$c2 = $name[$i + 1];
		if (ord($c) > 0) {// 两个字节的字
			$cn_word = '\\'.base_convert(ord($c), 10, 16).base_convert(ord($c2), 10, 16);
			$str .= strtoupper($cn_word);
		} else {
			$str .= $c2;
		}
	}
	return $str;
}

function unicode_decode($name) {//Unicode to
	$pattern = '/([\w]+)|(\\\u([\w]{4}))/i';
	preg_match_all($pattern, $name, $matches);
	if (!empty($matches)) {
		$name = '';
		for ($j = 0; $j < count($matches[0]); $j++) {
			$str = $matches[0][$j];
			if (strpos($str, '\\u') === 0) {
				$code = base_convert(substr($str, 2, 2), 16, 10);
				$code2 = base_convert(substr($str, 4), 16, 10);
				$c = chr($code).chr($code2);
				$c = iconv('UCS-2', 'UTF-8', $c);
				$name .= $c;
			} else {
				$name .= $str;
			}
		}
	}
	return $name;
}

/**
 * ajax返回
 * @param all $data
 */
function ajax_return($data)
{
	header("Content-Type:text/html; charset=utf-8");
	echo(json_encode($data));
	exit;
}
/**
 * 隐藏手机号码
 * @param unknown $mobile
 * @return mixed|string
 */
function hideMobile($mobile){
	if($mobile!="")
		return preg_replace('#(\d{3})\d{5}(\d{3})#', '${1}*****${2}',$mobile);
	else
		return "";
}
/**
 * 隐藏部分身份证
 * @param unknown $idcard
 * @return mixed|string
 */
function hideIdCard($idcard){
	if($idcard!="")
		return preg_replace('#(\d{14})\d{4}|(\w+)#', '${1}****',$idcard);
	else
		return "";

}
/**
 * 隐藏部分邮箱
 * @param unknown $email
 * @return mixed|string
 */
function hideEmail($email){
	if($email!="")
		return preg_replace('#(\w{2})\w+\@+#', '${1}****@${3}', $email);
	else
		return "";
}
/**
 * 获取表单KEY
 */
function HASH_KEY(){
	if(!es_session::is_set("HASH_KEY")){
		es_session::set("HASH_KEY",es_string::rand_string(50));
	}
	return es_session::get("HASH_KEY");
}
/**
 * 检查表单KEY
 */
function check_hash_key(){

	if(md5(HASH_KEY())==md5($_REQUEST['fhash'])){
		return true;
	}
	else
		return false;
}

/**
 * 内容写入文件
 *
 * @param string $filepath 待写入内容的文件路径
 * @param string/array $data 待写入的内容
 * @param  string $mode 写入模式，如果是追加，可传入“append”
 * @return bool
 */
function write_file($filepath, $data, $mode = null)
{
	if (!is_array($data) && !is_scalar($data)) {
		return false;
	}

	$data = var_export($data, true);

	$data = "<?php defined('APP_Alan') or exit('Access Invalid!'); return ".$data.";";
	$mode = $mode == 'append' ? FILE_APPEND : null;
	if (false === file_put_contents($filepath,($data),$mode)){
		return false;
	}else{
		return true;
	}
}

/**
 * 记录和统计时间（微秒）
 * @param unknown $start
 * @param string $end
 * @param number $dec
 * @return string
 */
function addUpTime($start,$end='',$dec=3) {
	static $_info = array();
	if(!empty($end)) { // 统计时间
		if(!isset($_info[$end])) {
			$_info[$end]   =  microtime(TRUE);
		}
		return number_format(($_info[$end]-$_info[$start]),$dec);
	}else{ // 记录时间
		$_info[$start]  =  microtime(TRUE);
	}
}



/**
 * 手机端显示错误的信息框
 * @param string $msg
 * @param number $ajax
 * @param string $jump
 * @param number $stay
 */
function showErrM($msg,$jump='')
{
	echo "<script>alert('".$msg."');location.href='".$jump."';</script>";exit;
}

/**
 * 手机端显示成功的信息框
 * @param string $msg
 * @param number $ajax
 * @param string $jump跳转链接
 * @param number $stay
 */
function showSuccessM($msg,$jump='')
{
	echo "<script>alert('".$msg."');location.href='".$jump."';</script>";exit;
}

/**
 * APP端显示错误
 * @param unknown $msg
 * @param number $ajax
 * @param string $jump
 */
function showErr($msg,$ajax=0,$jump='')
{
	if($ajax==1)
	{
		$result['status'] = 0;
		$result['info'] = $msg;
		$result['jump'] = $jump;
		header("Content-Type:text/html; charset=utf-8");
		echo(json_encode($result));exit;
	}
	else
	{

		$GLOBALS['tmpl']->assign('page_title',"操作错误 - ".$msg);
		$GLOBALS['tmpl']->assign('msg',$msg);
		if($jump=='')
		{
			$jump = $_SERVER['HTTP_REFERER'];
		}
		if(!$jump&&$jump=='')
			$jump = ROOT_PATH."/";
		$GLOBALS['tmpl']->assign('jump',$jump);
		$GLOBALS['tmpl']->display("error.html");
		exit;
	}
}

/**
 * APP端显示成功
 * @param unknown $msg
 * @param number $ajax
 * @param string $jump
 */
function showSuccess($msg,$ajax=0,$jump='')
{
	if($ajax==1)
	{
		$result['status'] = 1;
		$result['info'] = $msg;
		$result['jump'] = $jump;
		header("Content-Type:text/html; charset=utf-8");
		echo(json_encode($result));exit;
	}
	else
	{
		$GLOBALS['tmpl']->assign('page_title',"操作成功 - ".$msg);
		$GLOBALS['tmpl']->assign('msg',$msg);
		if($jump=='')
		{
			$jump = $_SERVER['HTTP_REFERER'];
		}
		if(!$jump&&$jump=='')
			$jump = APP_ROOT."/";
		$GLOBALS['tmpl']->assign('jump',$jump);
		$GLOBALS['tmpl']->display("success.html");
		exit;
	}
}




/**
 * 解析WAP_URL标签{wap_url a="index" r="init"}
 * @param unknown $str = u:shop|acate#index|id=10&name=abc
 * @return unknown|boolean
 */
function parse_wap_url_tag($str)
{
	$key = md5("WAP_URL_TAG_".$str);
	if(isset($GLOBALS[$key]))
	{
		return $GLOBALS[$key];
	}

	$str = substr($str,2);
	$str_array = explode("|",$str);
	$app_index = $str_array[0];
	$route = $str_array[1];
	$param_tmp = explode("&",$str_array[2]);
	$param = array();

	foreach($param_tmp as $item)
	{
		if($item!='')
			$item_arr = explode("=",$item);
		if($item_arr[0]&&$item_arr[1])
			$param[$item_arr[0]] = $item_arr[1];
	}
	$GLOBALS[$key]= wap_url($app_index,$route,$param);

	return $GLOBALS[$key];
}

//wap重写下使用原始链接
function wap_url($app_index,$route="index",$param=array())
{
	return url($app_index,$route,$param,'wap');
}

/**
 * 抛出异常
 * @param unknown $error
 */
function throw_exception($error){
    if (IS_DEBUG){
        print_r($error);
    }else{
        exit();
    }
}

/**
 * 规范数据返回函数
 * @param boolean status
 * @param string $msg
 * @param array $data
 * @return multitype:unknown
 */
function callback($status = true, $msg = '', $data = array()) {
    return array('status' => $status, 'msg' => $msg, 'data' => $data);
}



/**
 * 检查验证码是否输入正确
 */
function checkverify()
{
	if (app_conf("VERIFY_IMAGE") == 1) {
		$verify = md5(trim($_REQUEST['verify']));
		$session_verify = es_session::get('verify');
		if ($verify == $session_verify) {
			return true;
		}else {
			return false;
		}
	} else {
		return true;
	}
}


/**
 * 返回字符串首大写字母
 * @param string $res
 */
// function getfirstchar($res = '') {
//     $first_char = function($s0){
//         $fchar = ord($s0{0});
//         if($fchar >= ord("A") and $fchar <= ord("z") )return strtoupper($s0{0});
//         $s1 = iconv("UTF-8","gb2312", $s0);
//         $s2 = iconv("gb2312","UTF-8", $s1);
//         if($s2 == $s0){$s = $s1;}else{$s = $s0;}
//         $asc = ord($s{0}) * 256 + ord($s{1}) - 65536;
//         if($asc >= -20319 and $asc <= -20284) return "A";
//         if($asc >= -20283 and $asc <= -19776) return "B";
//         if($asc >= -19775 and $asc <= -19219) return "C";
//         if($asc >= -19218 and $asc <= -18711) return "D";
//         if($asc >= -18710 and $asc <= -18527) return "E";
//         if($asc >= -18526 and $asc <= -18240) return "F";
//         if($asc >= -18239 and $asc <= -17923) return "G";
//         if($asc >= -17922 and $asc <= -17418) return "H";
//         if($asc >= -17417 and $asc <= -16475) return "J";
//         if($asc >= -16474 and $asc <= -16213) return "K";
//         if($asc >= -16212 and $asc <= -15641) return "L";
//         if($asc >= -15640 and $asc <= -15166) return "M";
//         if($asc >= -15165 and $asc <= -14923) return "N";
//         if($asc >= -14922 and $asc <= -14915) return "O";
//         if($asc >= -14914 and $asc <= -14631) return "P";
//         if($asc >= -14630 and $asc <= -14150) return "Q";
//         if($asc >= -14149 and $asc <= -14091) return "R";
//         if($asc >= -14090 and $asc <= -13319) return "S";
//         if($asc >= -13318 and $asc <= -12839) return "T";
//         if($asc >= -12838 and $asc <= -12557) return "W";
//         if($asc >= -12556 and $asc <= -11848) return "X";
//         if($asc >= -11847 and $asc <= -11056) return "Y";
//         if($asc >= -11055 and $asc <= -10247) return "Z";
//         return null;
//     };

//     return $first_char($res);

// }

/**
 * 加密函数
 *
 * @param string $txt 需要加密的字符串
 * @param string $key 密钥
 * @return string 返回加密结果
 */
function encrypt($txt, $key = ''){
    if (empty($txt)) return $txt;
    if (empty($key)) $key = md5(app_conf('md5_key'));
    $chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789-_.";
    $ikey ="-x6g6ZWm2G9g_vr0Bo.pOq3kRIxsZ6rm";
    $nh1 = rand(0,64);
    $nh2 = rand(0,64);
    $nh3 = rand(0,64);
    $ch1 = $chars{$nh1};
    $ch2 = $chars{$nh2};
    $ch3 = $chars{$nh3};
    $nhnum = $nh1 + $nh2 + $nh3;
    $knum = 0;$i = 0;
    while(isset($key{$i})) $knum +=ord($key{$i++});
    $mdKey = substr(md5(md5(md5($key.$ch1).$ch2.$ikey).$ch3),$nhnum%8,$knum%8 + 16);
    $txt = base64_encode(time().'_'.$txt);
    $txt = str_replace(array('+','/','='),array('-','_','.'),$txt);
    $tmp = '';
    $j=0;$k = 0;
    $tlen = strlen($txt);
    $klen = strlen($mdKey);
    for ($i=0; $i<$tlen; $i++) {
        $k = $k == $klen ? 0 : $k;
        $j = ($nhnum+strpos($chars,$txt{$i})+ord($mdKey{$k++}))%64;
        $tmp .= $chars{$j};
    }
    $tmplen = strlen($tmp);
    $tmp = substr_replace($tmp,$ch3,$nh2 % ++$tmplen,0);
    $tmp = substr_replace($tmp,$ch2,$nh1 % ++$tmplen,0);
    $tmp = substr_replace($tmp,$ch1,$knum % ++$tmplen,0);
    return $tmp;
}

/**
 * 解密函数
 *
 * @param string $txt 需要解密的字符串
 * @param string $key 密匙
 * @return string 字符串类型的返回结果
 */
function decrypt($txt, $key = '', $ttl = 0){
    if (empty($txt)) return $txt;
    if (empty($key)) $key = md5(app_conf('md5_key'));

    $chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789-_.";
    $ikey ="-x6g6ZWm2G9g_vr0Bo.pOq3kRIxsZ6rm";
    $knum = 0;$i = 0;
    $tlen = @strlen($txt);
    while(isset($key{$i})) $knum +=ord($key{$i++});
    $ch1 = @$txt{$knum % $tlen};
    $nh1 = strpos($chars,$ch1);
    $txt = @substr_replace($txt,'',$knum % $tlen--,1);
    $ch2 = @$txt{$nh1 % $tlen};
    $nh2 = @strpos($chars,$ch2);
    $txt = @substr_replace($txt,'',$nh1 % $tlen--,1);
    $ch3 = @$txt{$nh2 % $tlen};
    $nh3 = @strpos($chars,$ch3);
    $txt = @substr_replace($txt,'',$nh2 % $tlen--,1);
    $nhnum = $nh1 + $nh2 + $nh3;
    $mdKey = substr(md5(md5(md5($key.$ch1).$ch2.$ikey).$ch3),$nhnum % 8,$knum % 8 + 16);
    $tmp = '';
    $j=0; $k = 0;
    $tlen = @strlen($txt);
    $klen = @strlen($mdKey);
    for ($i=0; $i<$tlen; $i++) {
        $k = $k == $klen ? 0 : $k;
        $j = strpos($chars,$txt{$i})-$nhnum - ord($mdKey{$k++});
        while ($j<0) $j+=64;
        $tmp .= $chars{$j};
    }
    $tmp = str_replace(array('-','_','.'),array('+','/','='),$tmp);
    $tmp = trim(base64_decode($tmp));

    if (preg_match("/\d{10}_/s",substr($tmp,0,11))){
        if ($ttl > 0 && (time() - substr($tmp,0,11) > $ttl)){
            $tmp = null;
        }else{
            $tmp = substr($tmp,11);
        }
    }
    return $tmp;
}

/**
 * 取地区列表
 * @param number $deep
 * @param number $id
 * @param string $field
 * @return unknown|multitype:
 */
function getAreaList($deep = 1 ,$id = 0 ,$field = "id,area_name"){
	if($id > 0){
		$condtion = "parent_id=".$id." and ";
	}  
	$condtion .= "deep = ".$deep;
	
	$sql = "select ".$field." from ".DB_PREFIX."area where ".$condtion;

	$list = $GLOBALS['db']->getAll($sql);
	if($list){
		return $list;
	}else {
		return array();
	}
}

function checkLogin(){
	if(es_session::is_set('is_login') && intval(es_session::get('is_login'))==1){
		return true;
	}else {
		app_redirect(wap_url('index','login'));
	}
}


?>