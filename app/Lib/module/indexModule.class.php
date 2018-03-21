<?php
// +----------------------------------------------------------------------
// |  lanxinFrame
// +----------------------------------------------------------------------
// | Copyright (c) 2011 http://www.lanxinbase.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: Alan(341455770@qq.com)
// +----------------------------------------------------------------------

class indexModule extends appBaseHome{
	public function __construct(){
		parent::__construct();
	}
	public function index(){

		$GLOBALS['tmpl']->display("index.html");

	}

    private function test()
    {

        $mail = new mail_sender();
        $mail->AddAddress('luoziping1225@126.com'); // 接收人
        $mail->IsHTML(TRUE); // 设置邮件格式为 HTML
        $mail->Subject = 'test测试'; // 标题

        $mail->Body =  "测试的内容："+to_date(get_gmtime());


        $is_success = $mail->Send();

        $resmail = $mail->ErrorInfo;
        var_dump($is_success);
        var_dump($resmail);



        die;

    }

}
?>