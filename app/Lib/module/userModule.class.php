<?php
// +----------------------------------------------------------------------
// |  lanxinFrame
// +----------------------------------------------------------------------
// | Copyright (c) 2011 http://www.lanxinbase.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: Alan(341455770@qq.com)
// +----------------------------------------------------------------------

require ROOT_PATH . "system/service/UserService.php";
require ROOT_PATH . "system/service/LotteryService.php";
class userModule extends appBaseHome{
	public function __construct(){
		parent::__construct();
	}

	public function index(){

	    $password = $_REQUEST['password'];
        $lottery = new LotteryService();
	    if ($password == app_conf("password")){
	        $page = $_REQUEST['page'] ? intval($_REQUEST['page']) : 1;
            $keywords = htmlspecialchars($_REQUEST['keywords']);
            $condition = null;

            if (strlen($keywords) > 0){
                $condition = "(mobile like '%%s%' or email like '%%s%' or lastName like '%%s%' or firstName like '%%s%')";
                $condition = sprintf($condition,$keywords,$keywords,$keywords,$keywords);
            }
            $data = $lottery->getAll($condition,$page);
            $count = $lottery->getCount($condition);

            $page = new Page(ceil($count / app_conf("PAGE_SIZE")),app_conf("PAGE_SIZE"));

            $GLOBALS['tmpl']->assign("data",$data);
            $GLOBALS['tmpl']->assign("password",$password);
            $GLOBALS['tmpl']->assign("keywords",$keywords);
            $GLOBALS['tmpl']->assign("count",$count);
            $GLOBALS['tmpl']->assign("pageCount",ceil($count / app_conf("PAGE_SIZE")));
            $GLOBALS['tmpl']->assign("pages",$page->show());

        }else{
            $password = null;
            $data = $lottery->getAllWinUser(null,1);
            $arr = array();
            foreach ($data as $k => $v){
                $arr[$k]['firstName'] = $v['firstName'] . "**";
                $arr[$k]['email'] = hideEmail($v['email']);
                $arr[$k]['mobile'] = hideMobile($v['mobile']);
                $arr[$k]['goodsName'] = $v['goodsName'];
            }
            $res = array(
                "code"=>1,"info"=>"err","data"=>$arr
            );
            if ($_REQUEST['isAjax'] == 1){
                ajax_return($res);
                return;
            }
        }
        $GLOBALS['tmpl']->assign("program_title","中奖名单");
        $GLOBALS['tmpl']->display("user.html");
	}


    public function save(){

	    $firstName = trim(htmlspecialchars($_REQUEST["firstName"]));
	    $lastName = trim(htmlspecialchars($_REQUEST["lastName"]));
	    $email = trim(htmlspecialchars($_REQUEST["email"]));
        $mobile = trim(htmlspecialchars($_REQUEST["mobile"]));

        $data = array(
          "code"=>0,"info"=>"err","data"=>null
        );

        try{
            if ($firstName == ""){
                throw new Exception("请填写姓");
            }
            if($lastName == ""){
                throw new Exception("请填写名字");
            }
            if (!check_email($email)){
                throw new Exception("请输入正确的邮箱地址");
            }
            if (!check_mobile($mobile)){
                throw new Exception("请输入正确的手机号码");
            }

            $userPojo = new UserService();
            $user = $userPojo->getRow(sprintf("(email='%s' or mobile='%s')",$email,$mobile));


            if (!$user){
                $user = array(
                    "firstName" => $firstName,
                    "lastName" => $lastName,
                    "email" => $email,
                    "mobile" => $mobile,
                    "createTime" => TIME_UTC,
                    "ipAddr" => CLIENT_IP
                );
                $userPojo->insert($user);

            }

            $token = encrypt($user['mobile']."#".$user['email']."#".TIME_UTC);
            $data['code'] = 1;
            $data['info'] = "注册成功,请抽奖.";
            $data['data'] = $token;

        }catch (Exception $e){
            $data['info'] = $e->getMessage();
        }
        ajax_return($data);
    }

    public function read(){
        $id = intval($_REQUEST['id']);
        if ($id > 0){
            $lottery = new LotteryService();
            $lottery->update("update ".DB_PREFIX."lottery set status = 1 where id = ".$id);
        }
        $data = array(
            "code"=>1,"info"=>"err","data"=>null
        );
        ajax_return($data);
    }

}
?>