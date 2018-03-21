<?php
// +----------------------------------------------------------------------
// |  lanxinFrame-应用程序初始化接口
// +----------------------------------------------------------------------
// | Copyright (c) 2011 http://www.lanxinbase.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: Alan(341455770@qq.com)
// +----------------------------------------------------------------------

if (!defined ('APP_Alan')) {
	exit ( 'Access Denied' );
}


final class App{
	public static $_lib = null;
	public static $_config = null;

	public static function init(){
		self::setAutoLibs();
		//require ROOT_PATH."model.php";

	}

	public static function run(){
		self::$_config = $GLOBALS['config'];
		self::init();
		self::autoLoad();
		self::$_lib['route']->setUrlType(self::$_config['URL_MODEL']);
		$url_array = self::$_lib['route']->getUrlArray();
		self::routeToCm($url_array);
	}

	/**
	 * 自动加载类库
	 * @access public
	 * @param  array $_lib
	 */
	public static function autoLoad(){
		foreach (self::$_lib as $k => $v){
			require self::$_lib[$k];
			$lib = ucfirst($k);
			self::$_lib[$k] = new $lib();
		}

		//初始化 缓存

	}
	/**
	 * 加载类库
	 * @access public
	 * @param  string $class_name 类库名称
	 * @return object
	 */
	public static function newLib($class_name){
		$app_lib = $sys_lib ='';
		$app_lib = ROOT_PATH."system/libs/".$class_name.self::$_config['LIB_PREFIX'].".php";
		return new $app_lib;
	}

	/**
	 * 设置自动加载的类库
	 */
	public static function setAutoLibs(){
		self::$_lib = array(
				'route'  =>		ROOT_PATH.'system/core/route.php'
		);
	}
	/**
	 * 根据url分发到个APP和model
	 * @access public
	 * @param array $url_array 链接数组
	 */
	public static function routeToCm($url_array = array()){
		//app类型入口
		$app = isset($url_array['m']) ? $url_array['m'] : self::$_config['DEFAULT_MODULE'];


		if (strtolower($app) == 'admin' && !defined ('APP_Admin')) {
			exit ( 'Access Denied' );
		}
		
		//载入基础父类
		require ROOT_PATH.$app."/Lib/".$app."_init.php";
		
		//赋值控制器跟函数入口
		$ctl = isset($url_array['ctl']) ? $url_array['ctl']:self::$_config['DEFAULT_CTL'];
		$act = isset($url_array['act']) ? $url_array['act']:self::$_config['DEFAULT_ACT'];
		
		$ctl_file = ROOT_PATH.$app."/Lib/module/".$ctl.self::$_config['LIB_PREFIX'].".php";

		if (isset($url_array['params'])){
			$params = $url_array['params'];
		}

		//输入基本的配置信息
		$GLOBALS['tmpl']->cache_dir     = ROOT_PATH . 'public/runtime/'.$app.'/tpl_caches';
		$GLOBALS['tmpl']->compile_dir    = ROOT_PATH . 'public/runtime/'.$app.'/tpl_compiled';
		$GLOBALS['tmpl']->template_dir   = ROOT_PATH .$app. '/Tpl/' . app_conf("TEMPLATE");
		
		//设置数据库缓存
		$GLOBALS['db']->cache_data_dir = 'public/runtime/'.$app.'/db_caches';
		define('MODULE_NAME', $ctl);
		define('ACTION_NAME', $act);
	
		$GLOBALS['tmpl']->assign('module',$ctl);
		$GLOBALS['tmpl']->assign('action',$act);
		$GLOBALS['tmpl']->assign('TML_PATH',SITE_DOMAIN."/".$app."/Tpl/". app_conf("TEMPLATE")."/");

		$GLOBALS['tmpl']->assign("hashkey",HASH_KEY());
		$GLOBALS['from'] = $app;
		$GLOBALS['ctl'] = $ctl;
		$GLOBALS['tmpl']->assign("title",app_conf('SITENAME'));
		$GLOBALS['tmpl']->assign("KeyWords",app_conf('KeyWords'));
		$GLOBALS['tmpl']->assign("Description",app_conf('Description'));

		if (file_exists($ctl_file)){

			require $ctl_file;
			$ctl = $ctl.'Module';

			$ctl = new $ctl;

			if ($act){
				if(method_exists($ctl, $act)){
					isset($params) ? $ctl->$act($params):$ctl->$act();
				}
			}
		}else{

			$tml_path = ROOT_PATH.$app. '/Tpl/' . app_conf("TEMPLATE")."/".$ctl.".html";
			if (file_exists($tml_path)){
				$GLOBALS['tmpl']->display($ctl.".html");
			}else {
				die('Access Denied!['.$ctl.']');
			}

		}

	}

}

?>