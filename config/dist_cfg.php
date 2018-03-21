<?php

return array(
    'CACHE_TYPE' =>   "File", //File,Memcached,MemcacheSASL,Xcache,Db
    'CACHE_LOG'  =>   false,  //是否需要在本地记录cache的key列表

    "CACHE_CLIENT"	=>	"", //备选配置,使用到的有memcached,memcacheSASL,DBCache
    "CACHE_PORT"	=>	"", //备选配置（memcache使用的端口，默认为11211,DB为3306）
    "CACHE_USERNAME"	=>	"",  //备选配置
    "CACHE_PASSWORD"	=>	"",  //备选配置
    "CACHE_DB"	=>	"",  //备选配置,用DB做缓存时的库名
    "CACHE_TABLE"	=>	"",  //备选配置,用DB做缓存时的表名
);

?>