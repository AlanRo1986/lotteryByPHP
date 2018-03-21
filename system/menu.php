<?php 
if (!defined ('APP_Alan')) {
	exit ( 'Access Denied' );
}
return array( 
			"index"	=>	array("name" =>	"系统首页", "key"	=>	"index"),
			"user"	=>	array("name" =>	"用户管理", "key"	=>	"index"),
			"goods"	=>	array("name" =>	"数据管理", "key"	=>	"index"),
			
			"adv"	=>	array(
					"name"	=>	"广告管理",
					"key"	=>	"adv",
					"nodes"	=>	array(
							array("name"=>"添加广告","module"=>"adv","action"=>"add"),
							array("name"=>"广告列表","module"=>"adv","action"=>"index")
					),
			),
			"Public"	=>	array("name" =>	"注销", "key"	=>	"loginout")
		);	

?>