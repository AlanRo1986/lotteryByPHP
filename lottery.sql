-- phpMyAdmin SQL Dump
-- version phpStudy 2014
-- http://www.phpmyadmin.net
--
-- 主机: localhost
-- 生成日期: 2018 年 01 月 19 日 10:28
-- 服务器版本: 5.5.53
-- PHP 版本: 5.4.45

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- 数据库: `lottery`
--

-- --------------------------------------------------------

--
-- 表的结构 `lx_conf`
--

CREATE TABLE IF NOT EXISTS `lx_conf` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `group_id` int(11) NOT NULL DEFAULT '0',
  `input_type` int(1) NOT NULL DEFAULT '0' COMMENT '配置输入的类型 0:文本输入 1:密码框',
  `is_effect` int(1) NOT NULL DEFAULT '1',
  `tip` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=16 ;

--
-- 转存表中的数据 `lx_conf`
--

INSERT INTO `lx_conf` (`id`, `name`, `content`, `group_id`, `input_type`, `is_effect`, `tip`) VALUES
(3, 'password', '123', 0, 1, 1, '管理密码'),
(4, 'SITE_DOMAIN', 'tmp.com', 0, 0, 1, '网站域名'),
(5, 'SITENAME', 'EightCap易汇', 0, 0, 1, '网站名称'),
(6, 'KeyWords', 'EightCap易汇,圣诞大抽奖22', 0, 0, 1, 'SEO关键词'),
(7, 'Description', 'EightCap易汇,圣诞大抽奖33', 0, 0, 1, 'SEO描述'),
(8, 'email_host', 'smtp.ym.163.com', 0, 0, 1, '邮件服务器stmp地址'),
(9, 'email_port', '25', 0, 0, 1, '邮件服务器端口'),
(10, 'email_id', 'service@lanxinbase.com', 0, 0, 1, '邮件服务器用户名'),
(11, 'email_pass', '', 0, 1, 1, '邮件服务器密码'),
(12, 'email_addr', 'service@lanxinbase.com', 0, 0, 1, '邮件发送人地址'),
(13, 'copyEmail', 'jessica.feng@eightcap.com', 0, 0, 1, '中奖信息邮件抄送地址'),
(14, 'lotteryStartTime', '2017-10-20 00:00:00', 0, 0, 1, '抽奖活动开始时间'),
(15, 'lotteryEndTime', '2017-12-20 00:00:00', 0, 0, 1, '抽奖活动结束时间(注意格式)');

-- --------------------------------------------------------

--
-- 表的结构 `lx_lottery`
--

CREATE TABLE IF NOT EXISTS `lx_lottery` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `userId` int(11) NOT NULL,
  `goodsName` varchar(255) NOT NULL,
  `createTime` int(11) NOT NULL,
  `ipAddr` varchar(15) NOT NULL,
  `status` int(1) NOT NULL,
  `isEffect` int(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=14 ;

--
-- 转存表中的数据 `lx_lottery`
--

INSERT INTO `lx_lottery` (`id`, `userId`, `goodsName`, `createTime`, `ipAddr`, `status`, `isEffect`) VALUES
(1, 1, '继续努力', 1513050157, '112.11.245.61', 0, 0),
(2, 1, '100美金交易赠金', 1513050165, '112.11.245.61', 0, 1),
(3, 2, '继续努力', 1513050169, '103.31.112.120', 0, 0),
(4, 2, '继续努力', 1513050178, '103.31.112.120', 0, 0),
(5, 2, '50美金交易赠金', 1513050190, '103.31.112.120', 0, 1),
(6, 3, '100美金交易赠金', 1513051673, '112.11.245.61', 1, 1),
(7, 4, '继续努力', 1513051792, '112.11.245.61', 0, 0),
(8, 4, '100美金交易赠金', 1513051800, '112.11.245.61', 0, 1),
(9, 5, '100美金交易赠金', 1513052004, '103.31.112.120', 0, 1),
(10, 6, '继续努力', 1513133496, '203.220.71.123', 0, 0),
(11, 6, '250美金交易赠金', 1513133512, '203.220.71.123', 0, 1),
(12, 7, '继续努力', 1513325942, '112.11.245.61', 0, 0),
(13, 7, '100美金交易赠金', 1513325950, '112.11.245.61', 0, 1);

-- --------------------------------------------------------

--
-- 表的结构 `lx_user`
--

CREATE TABLE IF NOT EXISTS `lx_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `firstName` varchar(20) NOT NULL,
  `lastName` varchar(20) NOT NULL,
  `email` varchar(50) NOT NULL,
  `mobile` varchar(20) NOT NULL,
  `createTime` int(11) NOT NULL,
  `ipAddr` varchar(15) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=8 ;

--
-- 转存表中的数据 `lx_user`
--

INSERT INTO `lx_user` (`id`, `firstName`, `lastName`, `email`, `mobile`, `createTime`, `ipAddr`) VALUES
(7, 'luo', 'ziping', 'lanxine@qq.com', '18580021590', 1513325932, '112.11.245.61');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
