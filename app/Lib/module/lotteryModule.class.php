<?php
// +----------------------------------------------------------------------
// |  lanxinFrame
// +----------------------------------------------------------------------
// | Copyright (c) 2011 http://www.lanxinbase.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: Alan(341455770@qq.com)
// +----------------------------------------------------------------------
require ROOT_PATH . "system/service/LotteryService.php";
require ROOT_PATH . "system/service/UserService.php";
require ROOT_PATH . "system/service/EmailService.php";
class lotteryModule extends appBaseHome{

    private $startTime;
    private $endTime;
    private $copyEmail;
    private $prize;

	public function __construct(){
		parent::__construct();
		$this->startTime = to_strtimespan(app_conf("lotteryStartTime"));
		$this->endTime = to_strtimespan(app_conf("lotteryEndTime"));
		$this->copyEmail = app_conf("copyEmail");

		$this->prize = array(
            1 => array('prizeId'=>11,'goods' => "继续努力", 'pro0' => 60, 'pro1' => 30,'pro2' => 0),
            2 => array('prizeId'=>1,'goods' => "50美金交易赠金", 'pro0' => 20, 'pro1' => 10,'pro2' => 20),
            3 => array('prizeId'=>5,'goods' => "100美金交易赠金", 'pro0' => 15, 'pro1' => 45,'pro2' => 65),
            4 => array('prizeId'=>2,'goods' => "250美金交易赠金", 'pro0' => 5, 'pro1' => 10,'pro2' => 10),
            5 => array('prizeId'=>7,'goods' => "250美金交易赠金", 'pro0' => 4, 'pro1' => 4,'pro2' => 4),
            6 => array('prizeId'=>3,'goods' => "700美金交易赠金", 'pro0' => 1, 'pro1' => 1,'pro2' => 1),
            7 => array('prizeId'=>9,'goods' => "1000美金交易赠金", 'pro0' => 0, 'pro1' => 0,'pro2' => 0),
            8 => array('prizeId'=>12,'goods' => "IPhone X", 'pro0' => 0, 'pro1' => 0,'pro2' => 0),
            9 => array('prizeId'=>4,'goods' => "澳大利亚东海岸精品7日游", 'pro0' => 0, 'pro1' => 0,'pro2' => 0)
        );
//        继续努力                                60%    30%     0%
//        50美金交易赠金                         20%	    10%     20%
//        100美金交易赠金                        15%    45%     65%
//        250美金交易赠金                        5%    10%     10%
//        350美金交易赠金                        4%     4%      4%
//        700美金交易赠金                        1%     1%      1%
//        1000美金交易赠金                       0%     0%      0%
//        IPhone X                                0%     0%      0%
//        澳大利亚东海岸精品7日游              0%     0%      0%


	}
	public function index(){
	    $GLOBALS['tmpl']->assign("program_title","新年大抽奖");
        $GLOBALS['tmpl']->display("lottery.html");
	}

    public function doWork(){
        $data = array(
            "code"=>0,"info"=>"err","data"=>null
        );


        try{

//            if(strpos($_SERVER['HTTP_REFERER'],app_conf("SITE_DOMAIN")."/lottery") == false){
//                throw new Exception("非法请求!");
//            }
            if ($this->startTime > TIME_UTC){
                throw new Exception("活动未开始!");
            }
            if ($this->endTime < TIME_UTC){
                throw new Exception("活动已结束!");
            }

            $token = decrypt($_REQUEST['token']);
            $user = explode("#",$token);
            if (count($user) != 3){
                throw new Exception("请先填写用户信息,再进行抽奖!");
            }
            $count = $this->checkAccess($user);
            $lottery = $this->getLottery($count);
            if (!$lottery){
                throw new Exception("网络繁忙!");
            }

            $this->save($lottery,$user);
            $lottery['prizeId'] = $lottery['prizeId'] * -30 + 30;
            unset($lottery['pro0']);
            unset($lottery['pro1']);
            unset($lottery['pro2']);

            $data = array(
                "code"=>1,"info"=>$lottery['goods'],"data"=>$lottery
            );

            if ($lottery['goods'] != "继续努力"){
                $data['info'] = sprintf("恭喜您抽中了%s，我们的工作人员会在24小时内会联系您",$lottery['goods']);
            }else{
                if ($count == 0){
                    $data['info'] = "继续努力，您还有2次抽奖机会";
                }elseif($count == 1){
                    $data['info'] = "还差一点，您还有1次抽奖机会";
                }
            }

        }catch (Exception $e){
            $data['info'] = $e->getMessage();
        }
        ajax_return($data);
    }

    protected function checkAccess($params){
        $userPojo = new UserService();
        $data = $userPojo->getRow(sprintf("(email='%s' or mobile='%s')",$params[1],$params[0]));

        if (!$data){
            throw new Exception("非法请求!");
        }

        $lotteryPojo = new LotteryService();
        $lottery = $lotteryPojo->getRow(sprintf("userId = %s and isEffect = 1",$data['id']));
        if($lottery){
            throw new Exception(sprintf("您已经抽中了%s，我们的工作人员会在24小时内会联系您",$lottery['goodsName']));
        }

        $count = $lotteryPojo->getCount(sprintf("userId = %s",$data['id']));
        if($count >= 3){
            throw new Exception("您的抽奖次数已经用完，立即注册真实账户，交易获得更多积分，直接兑换超值好礼！");
        }

        return $count;
    }

    /**
     * 进行抽奖操作
     * @param $count
     * @return array|mixed
     */
    protected function getLottery($count)
    {
        $arr = array();
        $random = mt_rand(1, 100);
        foreach ($this->prize as $k => $v){
            $random = $random - $v['pro'.$count];
            if ($random <= 0) {
                $arr = $v;
                break;
            }
        }
        return $arr;
    }

    //        继续努力                                60%    30%     0%
//        50美金交易赠金                         20%	    10%     20%
//        100美金交易赠金                        15%    45%     65%
//        250美金交易赠金                        5%    10%     10%
//        350美金交易赠金                        4%     4%      4%
//        700美金交易赠金                        1%     1%      1%
//        1000美金交易赠金                       0%     0%      0%
//        IPhone X                                0%     0%      0%
//        澳大利亚东海岸精品7日游              0%     0%      0%

    /**
     * 保存抽奖数据
     * @param $lottery
     * @param $params
     * @return bool
     * @throws Exception
     */
    protected function save($lottery, $params)
    {
        $userPojo = new UserService();
        $data = $userPojo->getRow(sprintf("(email='%s' or mobile='%s')",$params[1],$params[0]));
        if (!$data){
            throw new Exception("非法请求!");
        }

        $arr = array(
            "userId"=>$data['id'],
            "goodsName"=>$lottery['goods'],
            "createTime"=>TIME_UTC,
            "ipAddr"=>CLIENT_IP,
            "status"=>0,
            "isEffect"=>$lottery['goods'] == "继续努力" ? 0 : 1
        );

        $pojo = new LotteryService();
        if(!$pojo->insert($arr)){
            throw new Exception("网络繁忙!");
        }

        if ($arr['isEffect'] == 1){
            $email = new EmailService();
            $email->send($data,$lottery,$this->copyEmail);
        }
        return true;
    }


}
?>