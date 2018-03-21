<?php
// +----------------------------------------------------------------------
// |  lanxinFrame
// +----------------------------------------------------------------------
// | Copyright (c) 2011 http://www.lanxinbase.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: Alan(341455770@qq.com)
// +----------------------------------------------------------------------

class mysql_db {
	var $link_id = NULL;

	var $settings = array ();

	var $queryCount = 0;
	var $queryTime = '';
	var $queryLog = array ();

	var $max_cache_time = 0; // 最大的缓存时间，以秒为单位

	var $cache_data_dir = 'public/runtime/app/db_caches/';
	var $root_path = '';

	var $error_message = array ();
	var $platform = '';
	var $version = '';
	var $dbhash = '';
	var $starttime = 0;
	var $timeline = 0;
	var $timezone = 0;

	var $link_list = array (); //分布查询链接池

	var $iftransacte = false; //是否开始事务

	function __construct($dbhost, $dbuser, $dbpw, $dbname = '', $charset = 'utf8', $pconnect = 0) {
		$this->mysql_db($dbhost, $dbuser, $dbpw, $dbname, $charset, $pconnect);
	}

	function mysql_db($dbhost, $dbuser, $dbpw, $dbname = '', $charset = 'utf8', $pconnect = 0) {
		$this->root_path = ROOT_PATH;
		$this->settings = array (
				'dbhost' => $dbhost,
				'dbuser' => $dbuser,
				'dbpw' => $dbpw,
				'dbname' => $dbname,
				'charset' => $charset,
				'pconnect' => $pconnect
		);


	}


	function connect($dbhost, $dbuser, $dbpw, $dbname = '', $charset = 'utf8', $pconnect = 0) {

			if (PHP_VERSION >= '4.2') {
				$this->link_id = @ mysql_connect($dbhost, $dbuser, $dbpw, true);
			} else {
				$this->link_id = @ mysql_connect($dbhost, $dbuser, $dbpw);

				mt_srand((double) microtime() * 1000000); // 对 PHP 4.2 以下的版本进行随机数函数的初始化工作
			}
			if (!$this->link_id) {

				$this->ErrorMsg("Can't Connect MySQL Server($dbhost)!");

				return false;
			}


		$this->dbhash = md5($this->root_path . $dbhost . $dbuser . $dbpw . $dbname);
		$this->version = mysql_get_server_info($this->link_id);

		/* 如果mysql 版本是 4.1+ 以上，需要对字符集进行初始化 */
		if ($this->version > '4.1') {
			if ($charset != 'latin1') {
				mysql_query("SET character_set_connection=$charset, character_set_results=$charset, character_set_client=binary", $this->link_id);
			}
			if ($this->version > '5.0.1') {
				mysql_query("SET sql_mode=''", $this->link_id);
			}
		}

		$sqlcache_config_file = $this->root_path . $this->cache_data_dir . 'sqlcache_config_file_' . $this->dbhash . '.php';

		$this->starttime = time();

		if (!file_exists($sqlcache_config_file)) {
			if ($dbhost != '.') {
				$result = mysql_query("SHOW VARIABLES LIKE 'basedir'", $this->link_id);
				$row = mysql_fetch_assoc($result);
				if (!empty ($row['Value'] { 1 }) && $row['Value'] { 1 }
					== ':' && !empty ($row['Value'] { 2 }) && $row['Value'] { 2 }
					== "\\") {
					$this->platform = 'WINDOWS';
				} else {
					$this->platform = 'OTHER';
				}
			} else {
				$this->platform = 'WINDOWS';
			}

			if ($this->platform == 'OTHER' && ($dbhost != '.' && strtolower($dbhost) != 'localhost:3306' && $dbhost != '127.0.0.1:3306') || (PHP_VERSION >= '5.1' && date_default_timezone_get() == 'UTC')) {
				$result = mysql_query("SELECT UNIX_TIMESTAMP() AS timeline, UNIX_TIMESTAMP('" . date('Y-m-d H:i:s', $this->starttime) . "') AS timezone", $this->link_id);
				$row = mysql_fetch_assoc($result);

				if ($dbhost != '.' && strtolower($dbhost) != 'localhost:3306' && $dbhost != '127.0.0.1:3306') {
					$this->timeline = $this->starttime - $row['timeline'];
				}

				if (PHP_VERSION >= '5.1' && date_default_timezone_get() == 'UTC') {
					$this->timezone = $this->starttime - $row['timezone'];
				}
			}

			$content = '<' . "?php\r\n" .
			'$this->mysql_config_cache_file_time = ' . $this->starttime . ";\r\n" .
			'$this->timeline = ' . $this->timeline . ";\r\n" .
			'$this->timezone = ' . $this->timezone . ";\r\n" .
			'$this->platform = ' . "'" . $this->platform . "';\r\n?" . '>';

			@ file_put_contents($sqlcache_config_file, $content);
		}
		@ include ($sqlcache_config_file);

		/* 选择数据库 */
		if ($dbname) {
			if (mysql_select_db($dbname, $this->link_id) === false) {
				$this->ErrorMsg("Can't select MySQL database($dbname)!");

				return false;
			} else {
				return true;
			}
		} else {
			return true;
		}
	}

	function select_database($dbname) {
		return mysql_select_db($dbname, $this->link_id);
	}

	function query($sql, $type = "SILENT") {
		if (!SHOW_DEBUG)
			$type = "SILENT";

		if ($this->link_id === NULL) {
			$this->connect($this->settings['dbhost'], $this->settings['dbuser'], $this->settings['dbpw'], $this->settings['dbname'], $this->settings['charset'], $this->settings['pconnect']);
		}
		$query_link = $this->link_id;



		if ($this->queryCount++ <= 99) {
			$this->queryLog[] = $sql;
		}

		/* 当当前的时间大于类初始化时间的时候，自动执行 ping 这个自动重新连接操作 */
		if (PHP_VERSION >= '4.3' && time() > $this->starttime + 1) {
			mysql_ping($query_link);
		}

		if (PHP_VERSION >= '5.0.0') {
			$begin_query_time = microtime(true);
		} else {
			$begin_query_time = microtime();
		}
		if (!($query = mysql_query($sql, $query_link)) && $type != 'SILENT') {
			$this->error_message[]['message'] = 'MySQL Query Error';
			if ($pid)
				$this->error_message[]['message'] = 'MySQL Query Error:' . $pid;
			$this->error_message[]['sql'] = $sql;
			$this->error_message[]['error'] = mysql_error($query_link);
			$this->error_message[]['errno'] = mysql_errno($query_link);

			$this->ErrorMsg($sql);

			return false;
		}
		if (PHP_VERSION >= '5.0.0') {
			$query_time = microtime(true) - $begin_query_time;
		} else {
			list ($now_usec, $now_sec) = explode(' ', microtime());
			list ($start_usec, $start_sec) = explode(' ', $begin_query_time);
			$query_time = ($now_sec - $start_sec) + ($now_usec - $start_usec);
		}
		$this->queryTime += $query_time;

		if (SHOW_LOG) {
			$str = $sql;
			logger :: write($str, logger :: DEBUG, logger :: FILE, "db");
		}
		//echo $sql."<br/><br/>======================================<br/><br/>";
		return $query;
	}

	function affected_rows() {
		return mysql_affected_rows($this->link_id);
	}

	function error() {
		return mysql_error($this->link_id);
	}

	function errno() {
		return mysql_errno($this->link_id);
	}

	function insert_id() {
		return mysql_insert_id($this->link_id);
	}

	function close() {
		return mysql_close($this->link_id);
	}

	function ErrorMsg($message = '', $sql = '') {
		if ($message) {
			echo "<b>error info</b>: $message\n\n<br /><br />";
		} else {
			echo "<b>MySQL server error report:";
		}

	}




	/**
	*
	* @param unknown_type $sql
	* @return unknown|Ambigous <>|string|boolean
	*/
	function getOne($sql) {
		$res = false;
		if (!IS_DEBUG) {
			$GLOBALS['cache']->set_dir(ROOT_PATH . $this->cache_data_dir);
			$res = $GLOBALS['cache']->get($sql);
		}
		if ($res !== false) {
			return $res;
		}

		$res = $this->query($sql, "");
		if ($res !== false) {
			$row = mysql_fetch_row($res);

			if ($row !== false) {
				if (!IS_DEBUG) {
					$GLOBALS['cache']->set_dir(ROOT_PATH . $this->cache_data_dir);
					$GLOBALS['cache']->set($sql, $row[0], $this->max_cache_time);

				}
				return $row[0];
			} else {
				if (!IS_DEBUG) {

					$GLOBALS['cache']->set_dir(ROOT_PATH . $this->cache_data_dir);
					$GLOBALS['cache']->set($sql, '', $this->max_cache_time);

				}
				return '';
			}
		} else {
			if (!IS_DEBUG) {

				$GLOBALS['cache']->set_dir(ROOT_PATH . $this->cache_data_dir);
				$GLOBALS['cache']->set($sql, '', $this->max_cache_time);

			}
			return false;
		}
	}

	function getAll($sql) {

		$res = false;
		if (!IS_DEBUG) {
			$GLOBALS['cache']->set_dir(ROOT_PATH . $this->cache_data_dir);
			$res = $GLOBALS['cache']->get($sql);
		}
		if ($res !== false) {
			return $res;
		}

		$res = $this->query($sql, "");
		if ($res !== false) {
			$arr = array ();
			while ($row = mysql_fetch_assoc($res)) {
				$arr[] = $row;
			}

			if (!IS_DEBUG) {
				$GLOBALS['cache']->set_dir(ROOT_PATH . $this->cache_data_dir);
				$GLOBALS['cache']->set($sql, $arr, $this->max_cache_time);
			}
			return $arr;
		} else {
			if (!IS_DEBUG) {

				$GLOBALS['cache']->set_dir(ROOT_PATH . $this->cache_data_dir);
				$GLOBALS['cache']->set($sql, '', $this->max_cache_time);

			}
			return false;
		}
	}

	function getRow($sql) {
		$res = false;
		if (!IS_DEBUG) {

			$GLOBALS['cache']->set_dir(ROOT_PATH . $this->cache_data_dir);
			$res = $GLOBALS['cache']->get($sql);

		}
		if ($res !== false) {
			return $res;
		}

		$res = $this->query($sql, "");
		if ($res !== false) {
			$res = mysql_fetch_assoc($res);
			if (!IS_DEBUG) {

				$GLOBALS['cache']->set_dir(ROOT_PATH . $this->cache_data_dir);
				if ($res)
					$GLOBALS['cache']->set($sql, $res, $this->max_cache_time);
				else
					$GLOBALS['cache']->set($sql, '', $this->max_cache_time);

			}
			return $res;
		} else {
			if (!IS_DEBUG) {
				$GLOBALS['cache']->set_dir(ROOT_PATH . $this->cache_data_dir);
				$GLOBALS['cache']->set($sql, '', $this->max_cache_time);
			}
			return false;
		}
	}

	/**
	* 针对数据的查询缓存返回的当前时间戳，用于查询
	* @param unknown_type $time
	*/
	function getCacheTime($time) {
		return intval($time / $this->max_cache_time) * $this->max_cache_time;
	}

	/**
	 * 读取字段
	 * @param unknown $sql
	 * @return multitype:Ambigous <> |boolean
	 */
	function getCol($sql) {
		$res = $this->query($sql);
		if ($res !== false) {
			$arr = array ();
			while ($row = mysql_fetch_row($res)) {
				$arr[] = $row[0];
			}
			return $arr;
		} else {
			return false;
		}
	}

	function autoExecute($table, $field_values, $mode = 'INSERT', $where = '', $querymode = '') {
		$field_names = $this->getCol('DESC ' . DB_PREFIX.$table);
		
		$sql = '';
		if ($mode == 'INSERT') {
			$fields = $values = array ();
			foreach ($field_names AS $value) {
				if (@ array_key_exists($value, $field_values) == true) {
					$fields[] = $value;
					$field_values[$value] = stripslashes($field_values[$value]);
					$values[] = "'" . addslashes($field_values[$value]) . "'";
				}
			}

			if (!empty ($fields)) {
				$sql = 'INSERT INTO ' . DB_PREFIX.$table . ' (' . implode(', ', $fields) . ') VALUES (' . implode(', ', $values) . ')';
			}
		} else {
			$sets = array ();
			foreach ($field_names AS $value) {
				if (array_key_exists($value, $field_values) == true) {
					$field_values[$value] = stripslashes($field_values[$value]);
					$sets[] = $value . " = '" . addslashes($field_values[$value]) . "'";
				}
			}

			if (!empty ($sets)) {
				$sql = 'UPDATE ' . DB_PREFIX.$table . ' SET ' . implode(', ', $sets) . ' WHERE ' . $where;
			}
		}
		
		if ($sql) {
			return $this->query($sql, $querymode);
		} else {
			return false;
		}
	}
    /**
     * 开始事务
     */
	function beginTransaction(){
	    if ($this->link_id === NULL) {
	        $this->connect($this->settings['dbhost'], $this->settings['dbuser'], $this->settings['dbpw'], $this->settings['dbname'], $this->settings['charset'], $this->settings['pconnect']);
	    }
	    if ($this->iftransacte){
	        mysql_query('START TRANSACTION',$this->link_id);
	        $this->iftransacte = false;
	    }
	}
    /**
     * 提交事务
     */
	function commit(){
	    if (!$this->iftransacte){
	        $result = mysql_query('COMMIT',$this->link_id);
	        $this->iftransacte = true;
	        if (!$result){
	            $this->error_message[]['message'] = 'MySQL commit Error';
	            $this->error_message[]['error'] = mysql_error($this->link_id);
	            $this->error_message[]['errno'] = mysql_errno($this->link_id);
	            $this->ErrorMsg($result);
	        }

	        if (SHOW_LOG) {
	            logger :: write($result, logger :: DEBUG, logger :: FILE, "db");
	        }
	    }
	}
    /**
     * 回滚事务
     */
	function rollback(){
	    if (!$this->iftransacte){
	        $result = mysql_query('ROLLBACK',$this->link_id);
	        $this->iftransacte = true;

	        if (!$result){
	            $this->error_message[]['message'] = 'MySQL rollback Error';
	            $this->error_message[]['error'] = mysql_error($this->link_id);
	            $this->error_message[]['errno'] = mysql_errno($this->link_id);
	            $this->ErrorMsg($result);
	        }

	        if (SHOW_LOG) {
	            logger :: write($result, logger :: DEBUG, logger :: FILE, "db");
	        }
	    }
	}

	/**
	 * 批量插入
	 *
	 * @param string $table_name 表名
	 * @param array $field_values 待插入数据
	 * @return mixed
	 */
	function insertAll($table, $field_values = array()){
	    if (count($field_values) <= 0) return false;

	    $fields = array_keys($field_values[0]);
	    array_walk($fields);
	    $values = array();
	    foreach ($field_values as $data) {
	        $value = array();
	        foreach ($data as $val) {
	            $val = addslashes(stripslashes($val));//重新加斜线，防止从数据库直接读取出错
	            $val = "'".$val."'";

	            if (is_scalar($val)){
	                $value[] = $val;
	            }
	        }
	        $values[] = '('.implode(',',$value).')';
	    }
	    $sql = 'INSERT INTO `'.DB_PREFIX.$table.'` ('.implode(',',$fields).') VALUES '.implode(',',$values);
	    $result = $this->query($sql, '');
	    $insert_id = $this->insert_id();

	    return $insert_id ? $insert_id : $result;
	}

}
?>