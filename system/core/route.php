<?php
// +----------------------------------------------------------------------
// | lanxinFrame-路由程序
// +----------------------------------------------------------------------
// | Copyright (c) 2011 http://www.lanxinbase.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: Alan(341455770@qq.com)
// +----------------------------------------------------------------------
if (! defined ( 'APP_Alan' )) {
	exit ( 'Access Denied' );
}
final class Route {
	public $url_query;
	public $url_type;
	public $route_url = array ();
	public function __construct() {
		$this->url_query = parse_url ( $_SERVER ['REQUEST_URI'] );
	}
	public function setUrlType($url_type = 0) {
		if ($url_type == 0 || $url_type == 1) {
			$this->url_type = $url_type;
		} else {
			trigger_error ( "URL mode specified does not exist" );
		}
	}
	public function getUrlArray() {
		$this->makeUrl ();
		return $this->route_url;
	}
	public function makeUrl() {
		switch ($this->url_type) {
			case 0 :
				$this->queryToArray ();
				break;
			case 1 :
				$this->pathinfoToArray ();
				break;
		}
	}
	
	/**
	 * 普通路由模式
	 * /?m=wap&ctl=index&act=test&ab=1&ac=2
	 */
	public function queryToArray() {
		if (!isset($_REQUEST ['ctl'] ) && !isset($_REQUEST ['m'])) {
			$this->url_type = 1;
			$this->makeUrl ();
			return;
		}
		
		$this->route_url = array ();
		if (isset ( $_REQUEST ['m'] )) {
			$this->route_url ['m'] = $_REQUEST ['m'];
		}
		if (isset ( $_REQUEST ['ctl'] )) {
			$this->route_url ['ctl'] = $_REQUEST ['ctl'];
		}
		if (isset ( $_REQUEST ['act'] )) {
			$this->route_url ['act'] = $_REQUEST ['act'];
		}

	}
	
	/**
	 * pathinfo模式
	 * /模块/控制器/方法入口/参数1/参数1值/?ab=1&ac=2
	 * /app/index/test/a/1/?name=test&pwd=123456
	 * http://ww6.cn/app/index/test/a/1/?name=test&pwd=123456
	 * $_get['a'] = 1;
	 */
	public function pathinfoToArray() {
		$this->url_query ['path'] = substr ( $this->url_query ['path'], 1 );
		$arr = ! empty ( $this->url_query ['path'] ) ? explode ( "/", $this->url_query ['path'] ) : array ();
		
		$array = $tmp = array ();
		
		if (count ( $arr ) > 0) {
			for($i = 0; $i < count ( $arr ); $i ++) {
				if ($i == 0) {
					$this->route_url ['ctl'] = $arr [$i];
				} elseif ($i == 1) {
					$this->route_url ['act'] = $arr [$i];
				} else {
					if ($i % 2 == 0) {
						$array [$arr [$i - 1]] = $arr [$i];
						$_REQUEST [$arr [$i - 1]] = $arr [$i];
					}
				}
			}
			$this->route_url ['params'] = $array; // $this->test($params) 可以直接接收参数
		} else {
			$this->route_url = array ();
		}
	}
}
?>



