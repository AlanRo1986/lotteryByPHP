<?php
// +----------------------------------------------------------------------
// |  lanxinFrame
// +----------------------------------------------------------------------
// | Copyright (c) 2011 http://www.lanxinbase.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: Alan(341455770@qq.com)
// +----------------------------------------------------------------------

class testModule extends adminBaseHome
{
	public function index(){

		$page = intval($_REQUEST['page']);
		if($page==0)
		    $page = 1;

		$memberid = $GLOBALS['member_info']['member_id'];

		$limit = " LIMIT " . (($page - 1) * intval(app_conf('PAGES_NUM'))) . "," . app_conf('PAGES_NUM');

		$sql = "select * from ".DB_PREFIX."favorites a,".DB_PREFIX."goods b where
		    b.goods_id=a.fav_id and a.fav_type = 'goods' and a.member_id = ".$memberid.$limit;
		$countsql = "select count(1) from ".DB_PREFIX."favorites where fav_type = 'goods' and member_id = ".$memberid;

		$list = $GLOBALS['db']->getAll($sql);

        $count = $GLOBALS['db']->getOne($countsql);
        $pages = new Page(ceil($count/intval(app_conf('PAGES_NUM'))), app_conf('PAGES_NUM'));

        $GLOBALS['tmpl']->assign('pages', $pages->show());
        $GLOBALS['tmpl']->assign('list', $list);

        $GLOBALS['tmpl']->assign('page_title','test');
        $GLOBALS['tmpl']->display('index.html');
	}
}
?>
