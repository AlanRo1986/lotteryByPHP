<?php
/**
 * Created by PhpStorm.
 * User: alan.luo
 * Date: 2017/10/19
 * Time: 19:18
 */

class EmailService
{
    private $mail;

    public function send($user,$lottery,$copyEmail){

        if ($user){
            $this->sendUser($user,$lottery);
        }

        if ($copyEmail){
            $this->sendToUs($user,$lottery,$copyEmail);
        }

    }

    protected function sendUser($user,$lottery){
        $mail = new mail_sender();
        $mail->AddAddress($user['email']); // 接收人
        $mail->IsHTML(TRUE); // 设置邮件格式为 HTML
        $mail->Subject = app_conf("SITENAME"); // 标题
        //$mail->Body = sprintf("您已经抽中了%s，我们的工作人员会在24小时内会联系您",$lottery['goods']); // 内容
		
		//$body = sprintf("<p>尊敬的".$user['firstName'].$user['lastName']."，您好：</p><p>恭喜您抽中了<b>EightCap澳洲易汇<font style='color:red'>%s</font></b>，我们的工作人员会在24小时内联系您。</p>",$lottery['goods']);// 内容
        $mail->Body =  $this->getUserWelcomeEmailHtmlTmpl($user['firstName'].$user['lastName'],$lottery['goods']);
	
        //goods
        $is_success = $mail->Send();

        $resmail = $mail->ErrorInfo;
        if(!$is_success){
            logger::write("发送邮件失败:".serialize($user)." lottery:".serialize($lottery)." err:".$resmail);
        }
    }

    protected function sendToUs($user,$lottery,$copyEmail){
        $mail = new mail_sender();
        $mail->AddAddress($copyEmail); // 接收人
        $mail->IsHTML(TRUE); // 设置邮件格式为 HTML
        $mail->Subject = app_conf("SITENAME"); // 标题
        $mail->Body = $this->getUsHtml($user,$lottery); // 内容

        $is_success = $mail->Send();

        $resmail = $mail->ErrorInfo;

        if(!$is_success){
            logger::write("发送邮件失败:".serialize($user)." lottery:".serialize($lottery)." err:".$resmail);
        }
    }

    private function getUsHtml($user,$lottery){
        $str = <<<EOF
        <p>姓名:{$user['firstName']}{$user['lastName']}</p>
        <p>邮箱地址:{$user['email']}</p>
        <p>手机号码:{$user['mobile']}</p>
        <p>抽中奖品:{$lottery['goods']}</p>
EOF;

        return $str;
    }
	
	private function getUserFoot(){
		$str = <<<EOF
		<div style="height:30px;"></div>
		<p></p>
		<p></p>
		<p></p>
		<p>如果您对外汇交易感兴趣或者正在寻找一家值得信赖的平台，那您不妨考虑一下我们EightCap澳洲易汇。</p>
<p>这是我们官网的地址 http://www.eightcap.com.cn/</p>
<p>平台模拟账户下载地址 https://download.mql5.com/cdn/web/eightcap.pty.ltd/mt4/eightcap4setup.exe</p>
<p>真实账户申请链接 https://apply.eightcap.com/</p>
<p>官方宣传视频腾讯链接：https://v.qq.com/x/page/v0347scsuxf.html</p>
<p>我们平台的优势是银联即时免费出入金，入金秒到，出金1小时到，欧美点差1点起，黄金2点起，杠杆最高400倍，ASIC全牌执照监管391441，资金安全存放在澳大利亚国民银行信托基金，如果您想要交易外汇，不放考虑一下我们，
后续任何问题，您可以直接回复本邮件、拨打我们的服务电话0086383734800或者添加我们的在线客服QQ：3255809907联系我们。 </p>
<p>祝您交易愉快！</p>
<p></p>
<p></p>
<p></p>
EOF;
		return $str;
	}
	
	/**
	 * final email tmplate;
	 */
	private function getUserWelcomeEmailHtmlTmpl($realName,$prize){
		
		$html = <<<EOF
		<div>	
<style>
	a
	{
		text-decoration: none !important;
	}
	a:hover
	{
 		font-weight: inherit !important;
 		text-decoration: none !important;
	}
	img
	{
		display: block;
		margin: auto;
	}
</style>
<table border="0" cellspacing="0" cellpadding="0" width="100%" bgcolor="#f2f2f2">
	<tbody>
		<tr>
			<td>
				<table border="0" cellspacing="0" cellpadding="0" width="600" align="center">
					<tbody>
                    <!-- Header Section BEGIN -->
						<tr>
							<td>
								<table border="0" cellspacing="0" cellpadding="0" width="600" align="center">
									<tbody>
									<!-- Space Begin-->
										<tr>
											<td>
												<table border="0" cellspacing="0" cellpadding="0" width="600" align="center">
													<tbody><tr>
														<td style="line-height: 35px;">&nbsp;
														</td>
													</tr>			
												</tbody></table>
											</td>
										</tr>
									<!-- Space End -->
                                    <!-- Email Header Banner BEGIN -->
										<tr>
											<td>
												<table border="0" cellspacing="0" cellpadding="0" width="600" align="center">
													<tbody><tr>
														<td align="center" width="100%"><img src="http://cdn.eightcap.com/static/email/eightcap-email-header-box-2017.png" width="105" height="112" alt="eightcap-email-header" border="0">
														</td>
													</tr>
												</tbody></table>
											</td>
										</tr>
                                    <!-- Email Header Banner END -->
									<!-- Space Begin-->
										<tr>
											<td>
												<table border="0" cellspacing="0" cellpadding="0" width="600" align="center">
													<tbody><tr>
														<td style="line-height: 10px;">&nbsp;
														</td>
													</tr>			
												</tbody></table>
											</td>
										</tr>
									<!-- Space End -->
									</tbody>
								</table>
							</td>
						</tr>
					<!-- Header Section END -->
					<!-- Main Section BEGIN -->
						<tr>
							<td bgcolor="#ffffff">
								<table border="0" cellspacing="0" cellpadding="0" width="600" align="center">
									<tbody>
										<tr>
											<td>
												<table border="0" cellspacing="0" cellpadding="0" width="530" align="center">
													<tbody>
													<!-- Content Begins -->
                                                  		<tr>
															<td>
                                                            	<table border="0" cellpadding="0" cellspacing="0" width="100%" align="center">
                                                                	<tbody>
																	<tr>
																		<td style="padding: 50px 0px 55px 0px; vertical-align: top; text-align: left; font-family: Microsoft YaHei, '微软雅黑', 'Open Sans', STXihei, '华文细黑', Tahoma, sans-serif; font-style: normal; color: #010101;">
																		<span style="font-size: 20px; line-height: 170%; font-weight: 300;">
																		尊敬的{$realName}, 您好:<br><br>恭喜您抽中了EightCap澳洲易汇
																		<span style="color: #ff0000; font-weight: 800;">{$prize}</span>, 
																		我们的工作人员会在24小时内电话或者邮件联系您. 您也可以直接回复本邮件或者登录我们官网 www.eightcap.com.cn 联系我们的在线QQ客服.<br><br>
																		</span>
																		<span style="color: #353535; font-weight: 800; font-size: 24px; line-height: 180%;">
																			EightCap易汇––您最值得信赖的外汇贵金属平台
																		</span><br><br>
																		<span style="font-size: 20px; line-height: 170%; font-weight: 800; color: #ff2727;">✦&nbsp;&nbsp;监管严格</span><br>
																		<span style="font-size: 18px; line-height: 170%; font-weight: 300;">受澳大利亚证券和投资委员会ASIC严格监管(AFSL 391441)</span><br>
																		<span style="font-size: 20px; line-height: 170%; font-weight: 800; color: #ff2727;">✦&nbsp;&nbsp;资金安全</span><br>
																		<span style="font-size: 18px; line-height: 170%; font-weight: 300;">客户资金安全存放在澳大利亚国民银行独立信托账户, 安全可靠</span><br>
																		<span style="font-size: 20px; line-height: 170%; font-weight: 800; color: #ff2727;">✦&nbsp;&nbsp;出入金快</span><br>
																		<span style="font-size: 18px; line-height: 170%; font-weight: 300;">中国银联即时免费出入金, 入金秒到, 出金1小时到</span><br>
																		<span style="font-size: 20px; line-height: 170%; font-weight: 800; color: #ff2727;">✦&nbsp;&nbsp;超低点差</span><br>
																		<span style="font-size: 18px; line-height: 170%; font-weight: 300;">免佣标准账户点差1点起, 原始点差账户点差0点起</span><br>
																		<span style="font-size: 20px; line-height: 170%; font-weight: 800; color: #ff2727;">✦&nbsp;&nbsp;服务器稳定</span><br>
																		<span style="font-size: 18px; line-height: 170%; font-weight: 300;">香港, 东京, 纽约, 新加坡, 首尔服务器稳定支持, 下单执行快至0.01秒</span><br>
																		<span style="font-size: 20px; line-height: 170%; font-weight: 800; color: #ff2727;">✦&nbsp;&nbsp;交易方法不限</span><br>
																		<span style="font-size: 18px; line-height: 170%; font-weight: 300;">允许拔头皮和对冲等交易方法</span><br><br>
																		<span style="color: #353535; font-weight: 800; font-size: 24px; line-height: 180%;">EightCap易汇祝您交易愉快!</span>
																		</td>
																	</tr>
                                                                </tbody></table>
                                                            </td>
														</tr>
													<!-- Content Ends -->
                                                    </tbody>
												</table>
                                            </td>
										</tr>
                                    </tbody>
								</table>
							</td>
						</tr>
					<!-- Main Section END -->
					</tbody>
				</table>
			</td>
		</tr>
	<!-- Footer Section BEGIN -->
		<tr>
			<td>
				<table border="0" cellspacing="0" cellpadding="0" width="600" align="center">
					<tbody>
					<!-- Contact Section Begin -->
						<tr>
							<td>
								<table border="0" cellspacing="0" cellpadding="0" width="600" align="center">
									<tbody>
									<!-- Space Begin-->
										<tr>
											<td>
												<table border="0" cellspacing="0" cellpadding="0" width="600" align="center">
													<tbody><tr>
														<td style="line-height: 50px;">&nbsp;
														</td>
													</tr>			
												</tbody></table>
											</td>
										</tr>
									<!-- Space End -->
										<tr>
											<td>
												<table border="0" cellspacing="0" cellpadding="0" width="600" align="center">
													<tbody>
                                                 		<!-- Contact Title Begin -->
                                                  		<tr>
															<td>
                                                            	<table border="0" cellpadding="0" cellspacing="0" width="535" align="center">
                                                                	<tbody><tr>
																		<td style="padding: 0px 0px 35px 0px; vertical-align: top; text-align: center; font-family: Microsoft YaHei, '微软雅黑', 'Open Sans', STXihei, '华文细黑', Tahoma, sans-serif; font-style: normal;"><span style="font-size: 40px; line-height: 150%; font-weight: 800; color: #bdc5cb; letter-spacing: 5px;">联系我们</span><br><br><span style="font-weight: 300; font-size: 20px; line-height: 170%; color: #87a5b8;">如果您有任何问题, 欢迎随时咨询我们.</span>
																		</td>
																	</tr>
                                                                </tbody></table>
                                                            </td>
														</tr>
                                                  		<!-- Contact Title End -->
                                                  		<!-- Contact Buttons Begin -->
                                                   		<tr>
                                                			<td>
                                                    			<table border="0" cellspacing="0" cellpadding="0" width="600" align="center">
                                                        			<tbody><tr>
                                           								<td background="http://cdn.eightcap.com/static/email/email-phone-support-background-image.jpg" bgcolor="#02BC6D" width="295" height="180" valign="middle" align="center" style="padding: 0px 0px 0px 0px; border-right: 5px solid #ececeb; text-align: center; font-style: normal; font-weight: 800; font-family: Microsoft YaHei, '微软雅黑', 'Open Sans', STXihei, '华文细黑', Tahoma, sans-serif; color: #ffffff; line-height: 100%;">
                                                               			<!--[if mso]>
  																			<v:roundrect xmlns:v="urn:schemas-microsoft-com:vml" xmlns:w="urn:schemas-microsoft-com:office:word" href="tel:+61383734800" style="height:180px;v-text-anchor:middle;width:295px;" arcsize="1%" fillcolor="#02BC6D" strokecolor="#02BC6D">
    																			<w:anchorlock/>
   																				<center style="color:#ffffff;font-family: Microsoft YaHei, '微软雅黑', 'Open Sans', STXihei, '华文细黑', Tahoma, sans-serif;font-size:24px;font-style: normal;font-weight:800; letter-spacing: 4px;"><img src="http://cdn.eightcap.com/static/email/email-phone-support-image.png" width="100" height="100" alt="phone-support-image" border="0" style="padding-top: 15px;"/><br>&#26381;&#21153;&#30005;&#35805;</center>
  																			</v:roundrect>
																		<![endif]-->
                                                               			<a href="tel:+61383734800" target="_top" style="color: #ffffff; text-decoration: none; display: inline-block; font-size: 24px; height: 180px; width: 295px; -webkit-text-size-adjust: none; mso-hide: all; letter-spacing: 4px;"><img src="http://cdn.eightcap.com/static/email/email-phone-support-image.png" width="100" height="100" alt="phone-support-image" border="0" style="padding-top: 15px;"><br>服务电话</a>
                                                                		</td>
                                                                		<td background="http://cdn.eightcap.com/static/email/email-online-chat-background-image.jpg" bgcolor="#0078D7" width="295" height="180" style="padding: 0px 0px 0px 0px; border-left: 5px solid #ececeb; text-align: center; font-style: normal; font-weight: 800; font-family: Microsoft YaHei, '微软雅黑', 'Open Sans', STXihei, '华文细黑', Tahoma, sans-serif; color: #ffffff; line-height: 100%;">
                                                                		<!--[if mso]>
  																			<v:roundrect xmlns:v="urn:schemas-microsoft-com:vml" xmlns:w="urn:schemas-microsoft-com:office:word" href="http://www.eightcap.com.cn/contact-us/online-chat/" style="height:180px;v-text-anchor:middle;width:295px;" arcsize="1%" fillcolor="#0078D7" strokecolor="#0078D7">
    																			<w:anchorlock/>
   																				<center style="color:#ffffff;font-family: Microsoft YaHei, '微软雅黑', 'Open Sans', STXihei, '华文细黑', Tahoma, sans-serif;font-size:24px;font-style: normal;font-weight:800; letter-spacing: 4px;"><img src="http://cdn.eightcap.com/static/email/email-online-chat-image.png" width="100" height="100" alt="phone-support-image" border="0" style="padding-top: 15px;"/><br>&#22312;&#32447;&#23458;&#26381;</center>
  																			</v:roundrect>
																		<![endif]-->
                                                               			<a href="http://www.eightcap.com.cn/contact-us/online-chat/" target="_blank" style="color: #ffffff; text-decoration: none; display: inline-block; font-size: 24px; height: 180px; width: 295px; -webkit-text-size-adjust: none; mso-hide: all; letter-spacing: 4px;"><img src="http://cdn.eightcap.com/static/email/email-online-chat-image.png" width="100" height="100" alt="online-chat-image" border="0" style="padding-top: 15px;"><br>在线客服</a>
                                                                		</td>
                                            						</tr>
                                            					</tbody></table>
                                            				</td>
                                            			</tr>
                                                   		<!-- Contact Buttons End -->
                                                    </tbody>
												</table>
                                            </td>
										</tr>
                                    </tbody>
								</table>
							</td>
						</tr>
					<!-- Contact Section End -->
					<!-- Space Begin-->
                        <tr>
							<td>
								<table border="0" cellspacing="0" cellpadding="0" width="600" align="center">
									<tbody>
										<tr>
											<td>
												<table border="0" cellspacing="0" cellpadding="0" width="100%">
													<tbody><tr>
														<td style="line-height: 40px;">&nbsp;
														</td>
													</tr>
                                                </tbody></table>      
                                            </td>
										</tr>
                                    </tbody>
								</table>
							</td>
						</tr>
					<!-- Space End -->
					<!-- Website Button Begin -->
						<tr>
							<td>
								<table border="0" cellspacing="0" cellpadding="0" width="600" align="center">
									<tbody>
										<tr>
											<td>
												<table border="0" cellspacing="0" cellpadding="0" width="100%" align="center">
													<tbody>
                                                  		<tr>
															<td>
                                                            	<table border="0" cellpadding="0" cellspacing="0" width="100%" align="center">
                                                                	<tbody><tr>
																		<td style="border-top: 3px dashed #e5e5e5; border-bottom: 3px dashed #e5e5e5; padding: 30px 0px 30px 0px; vertical-align: top; text-align: center; font-style: normal; font-family: Microsoft YaHei, '微软雅黑', 'Open Sans', STXihei, '华文细黑', Tahoma, sans-serif; font-size: 24px; line-height: 100%; font-weight: 800; color: #87a5b8; letter-spacing: 2px;"><a href="http://www.eightcap.com.cn/" target="_blank" style="color: #87a5b8; font-weight: 800;"><span style="color: #d51a20;">❮❮</span>&nbsp;&nbsp;进入官方网站&nbsp;&nbsp;<span style="color: #d51a20;">❯❯</span></a>
																		</td>
																	</tr>
                                                                </tbody></table>
                                                            </td>
														</tr>
                                                    </tbody>
												</table>
                                            </td>
										</tr>
                                    </tbody>
								</table>
							</td>
						</tr>
					<!-- Website Button End -->
					<!-- Space Begin-->
                        <tr>
							<td>
								<table border="0" cellspacing="0" cellpadding="0" width="600" align="center">
									<tbody>
										<tr>
											<td>
												<table border="0" cellspacing="0" cellpadding="0" width="100%">
													<tbody><tr>
														<td style="line-height: 35px;">&nbsp;
														</td>
													</tr>
                                                </tbody></table>      
                                            </td>
										</tr>
                                    </tbody>
								</table>
							</td>
						</tr>
					<!-- Space End -->
					<!-- Disclaimer Begin -->
						<tr>
							<td>
								<table border="0" cellpadding="0" cellspacing="0" width="600" align="center">
									<tbody>
										<tr>
											<td>
												<table border="0" cellpadding="0" cellspacing="0" width="535" align="center">
													<tbody><tr>
														<td style="padding: 0px 0px 55px 0px; vertical-align: top; text-align: center; color: #87a5b8; font-family: Microsoft YaHei, '微软雅黑', 'Open Sans', STXihei, '华文细黑', Tahoma, sans-serif; font-style: normal;"><span style="font-size: 14px; font-weight: 300; line-height: 150%;">Copyright © 2017 <span style="font-weight: 800;">EightCap Pty Ltd.</span> All rights reserved.<br><br><span style="letter-spacing: 3px;">邮寄地址:</span><br><span style="font-weight: 800;">356 Collins Street, Level 11, Melbourne, VIC 3000, AU</span></span><br><br><span style="font-size: 12px; font-weight: 300; line-height: 130%;">请注意本邮件中包含的信息只适用于收件人本人. 本邮件内容是保密的. 如果您不是收件人本人请不要阅读. 复制, 使用或者披露给他人. 如果您错误地收到了本邮件, 请您告知发件人并且将本邮件内容从收件箱中删除.<br><br><span style="font-weight: 800;">免责申明:</span> 外汇保证金交易存在高风险. 申请和订立任何外汇保证金交易和差价合约前, 请您谨慎考虑您的目标, 财务状况, 需求和经验水平, 另外您没有任何放弃资产的权利. 外汇保证金和差价合约杠杆的作用是放大您的盈利和损失. 在正式交易前请确保您完全理解存在的风险, 包括本金亏损可能高于您最初的投入, 如无法承担损失, 请不要贸然投资. 外汇保证金交易可能不适合每一位投资者, 请确认您已完全理解存在的风险, 并在必要时咨询独立的专业顾问. 产品披露声明(PDS)和金融服务指南(FSG)可以从我们的官网下载, 您也可以致电我们索取备份. 另外请注意, 本邮件提供给您或通过我们工作人员获得的任何建议仅为一般性的建议, 并没有考虑到您的目标, 财务状况或实际需求. 因此, 在使用我们的服务并做任何决定前, 您应考虑我们的建议是否适合您. 投资者应该确保自己经营的交易账户及交易满足能够承受的亏损限度为宜. 我们任何推广都没有涉及到改变或干涉个人的风险平估态度或者鼓励个人进行不符合个人交易策略的交易.</span><br><br><span style="color: #142f65; font-weight: 800; font-size: 14px; line-height: 150%;"><a href="http://www.eightcap.com.cn/about-eightcap/our-commitment/legal-documentation/" target="_blank" style="color: #142f65; text-decoration: none; border-bottom: 1px solid #87a5b8; letter-spacing: 1px;">法律文件</a>&nbsp;&nbsp;&nbsp;<span style="font-weight: 300; color: #87a5b8;">|</span>&nbsp;&nbsp;&nbsp;<a href="http://cdn.eightcap.com/legal/8cap-privacy-policy.pdf" target="_blank" style="color: #142f65; text-decoration: none; border-bottom: 1px solid #87a5b8; letter-spacing: 1px;">隐私声明</a>&nbsp;&nbsp;&nbsp;<span style="font-weight: 300; color: #87a5b8;">|</span>&nbsp;&nbsp;&nbsp;<a href="http://www.eightcap.com.cn/clients/faqs/" target="_blank" style="color: #142f65; text-decoration: none; border-bottom: 1px solid #87a5b8; letter-spacing: 1px;">常见问题</a></span>
														</td>
													</tr>
												</tbody></table>
											</td>
										</tr>
									</tbody>
								</table>
							</td>
						</tr>
					<!-- Disclaimer End -->
					</tbody>
				</table>
			</td>
		</tr>
	<!-- Footer Section End -->
	</tbody>
</table>
</div>
EOF;
		
		return $html;
	}
		

}