<?php
// +----------------------------------------------------------------------
// |  lanxinFrame
// +----------------------------------------------------------------------
// | Copyright (c) 2011 http://www.lanxinbase.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: Alan(341455770@qq.com)
// +----------------------------------------------------------------------

class confModule extends appBaseHome{
	public function __construct(){
		parent::__construct();
	}
	public function index(){

        $password = $_REQUEST['password'];
        if ($password == app_conf("password")){
            $conf = new ConfService();
            $data = $conf->getAll();
            $GLOBALS['tmpl']->assign("data",$data);
        }else{
            $password = null;
        }
        $GLOBALS['tmpl']->assign("password",$password);
        $GLOBALS['tmpl']->assign("program_title","系统配置");
        $GLOBALS['tmpl']->display("conf.html");
	}

	public function save(){
        $data = array(
            "code"=>0,"info"=>"err","data"=>null
        );
        try{
            $conf = new ConfService();
            foreach ($_POST as $k => $v){
                if ($k == "password"){
                    if (strlen($v) > 5 ){
                        $conf->update($k,md5($v));
                    }
                }else{
                    if (app_conf($k) != $v){
                        $conf->update($k,$v);
                    }
                }
            }
            $data = array(
                "code"=>1,"info"=>"保存成功","data"=>null
            );
            unlink(PUBILC_PATH."sys_config.php");

        }catch (Exception $e){
            $data['info'] = $e->getMessage();
        }
        ajax_return($data);
    }
	
}
?>