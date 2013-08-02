-- phpMyAdmin SQL Dump
-- version 4.0.3
-- http://www.phpmyadmin.net
--
-- 主机: localhost
-- 生成日期: 2013 年 08 月 02 日 19:52
-- 服务器版本: 5.1.70-community
-- PHP 版本: 5.2.17

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- 数据库: `dzmcms`
--
CREATE DATABASE IF NOT EXISTS `dzmcms` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `dzmcms`;

-- --------------------------------------------------------

--
-- 表的结构 `dzmc_goods`
--

CREATE TABLE IF NOT EXISTS `dzmc_goods` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '编号',
  `name` varchar(255) NOT NULL COMMENT '商品名称',
  `suk` varchar(255) DEFAULT NULL COMMENT '简码',
  `unit` varchar(255) NOT NULL COMMENT '单位',
  `categories_id` int(11) NOT NULL COMMENT '分类id',
  `jinjia` varchar(255) NOT NULL COMMENT '进货价',
  `price` varchar(255) NOT NULL COMMENT '零售价',
  `jiangli_jifen` varchar(255) DEFAULT NULL COMMENT '奖励积分',
  `diyong_jifen` varchar(255) DEFAULT NULL COMMENT '可抵用奖励积分',
  `inventory` int(255) DEFAULT NULL COMMENT '库存',
  `remark` text NOT NULL COMMENT '备注',
  `add_date` int(22) NOT NULL COMMENT '添加时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='商品' AUTO_INCREMENT=5 ;

--
-- 转存表中的数据 `dzmc_goods`
--

INSERT INTO `dzmc_goods` (`id`, `name`, `suk`, `unit`, `categories_id`, `jinjia`, `price`, `jiangli_jifen`, `diyong_jifen`, `inventory`, `remark`, `add_date`) VALUES
(1, '手机', 'shouj', '个', 1, '10', '20', '1', '5', 1000, '备注', 0),
(2, '打火机', 'dhj', '个', 2, '0.5', '1', '1', '0', 999, '火机', 0),
(4, '打火机', 'dhj', '个', 2, '0.5', '1', '1', '0', 999, '火机', 11111);

-- --------------------------------------------------------

--
-- 表的结构 `dzmc_member`
--

CREATE TABLE IF NOT EXISTS `dzmc_member` (
  `card` int(11) NOT NULL COMMENT '读卡数字串',
  `cardid` int(11) NOT NULL COMMENT '会员卡上的编号',
  `card_type` int(11) NOT NULL COMMENT '卡的类型',
  `cash_pledge` varchar(125) DEFAULT NULL COMMENT '押金',
  `name` varchar(125) DEFAULT NULL COMMENT '姓名',
  `nickname` varchar(125) DEFAULT NULL COMMENT '昵称',
  `phone` varchar(15) NOT NULL COMMENT '手机号',
  `email` varchar(255) DEFAULT NULL COMMENT '邮箱',
  `identity_card` varchar(125) DEFAULT NULL COMMENT '身份证',
  `sex` int(1) NOT NULL COMMENT '性别1为男',
  `grade` int(11) NOT NULL COMMENT '等级',
  `birthday` int(15) DEFAULT NULL COMMENT '生日',
  `annual_fee` varchar(125) DEFAULT NULL COMMENT '年费',
  `annual_fee_end_time` int(15) DEFAULT NULL COMMENT '年费到期时间',
  `balance` varchar(255) NOT NULL DEFAULT '0.0' COMMENT '余额',
  `customer_manager` varchar(255) DEFAULT NULL COMMENT '客户经理',
  `address` text COMMENT '地址',
  `qq` varchar(64) DEFAULT NULL COMMENT 'qq号',
  `work_unit` text COMMENT '工作单位',
  `occupation` text COMMENT '职业',
  `eligibility` int(1) NOT NULL DEFAULT '1' COMMENT '参赛资格',
  `match_number` int(11) NOT NULL DEFAULT '0' COMMENT '大赛次数',
  `representative_club` text COMMENT '代表俱乐部',
  `representative_city` varchar(255) DEFAULT NULL COMMENT '代表城市',
  `add_date` int(15) NOT NULL COMMENT '添加时间',
  `jiangli_jifen` varchar(255) NOT NULL DEFAULT '0' COMMENT '奖励积分',
  `pwd` varchar(255) NOT NULL COMMENT '密码',
  PRIMARY KEY (`card`),
  UNIQUE KEY `card` (`card`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='会员表';

--
-- 转存表中的数据 `dzmc_member`
--

INSERT INTO `dzmc_member` (`card`, `cardid`, `card_type`, `cash_pledge`, `name`, `nickname`, `phone`, `email`, `identity_card`, `sex`, `grade`, `birthday`, `annual_fee`, `annual_fee_end_time`, `balance`, `customer_manager`, `address`, `qq`, `work_unit`, `occupation`, `eligibility`, `match_number`, `representative_club`, `representative_city`, `add_date`, `jiangli_jifen`, `pwd`) VALUES
(736955981, 800522, 1, '', '刘维', '', '13026105388', '1040811569@qq.com', '', 1, 1, 0, '', 0, '1632', '', '', '', '', '', 1, 0, '', '武汉', 1374316916, '0', '670b14728ad9902aecba32e22fa4f6bd'),
(737239805, 800329, 1, '', '刘德华', '华仔', '13026105388', '2579192831@qq.com', '429004199110162258', 1, 1, 1374451200, '100', 1405987200, '1000', '', '硚口区青年路嘉新大厦2502', '', '', '', 1, 0, '', '武汉', 1374488970, '0', ''),
(736940301, 800340, 1, '100', '郭富城', '富城', '13886143620', '1040811569@qq.com', '429004199110162254', 1, 1, 1215475200, '200', 1343260800, '622', '晓菲', '湖北省武汉市洪山区雄楚大街489号领秀城8栋', '996159513', '华秦', '歌手', 1, 0, 'pk365', '中文/拼音', 1374922571, '0', '');

-- --------------------------------------------------------

--
-- 表的结构 `dzmc_settings`
--

CREATE TABLE IF NOT EXISTS `dzmc_settings` (
  `variable` varchar(32) NOT NULL DEFAULT '',
  `value` text,
  UNIQUE KEY `variable` (`variable`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `dzmc_settings`
--

INSERT INTO `dzmc_settings` (`variable`, `value`) VALUES
('sitename', 'PK365俱乐部'),
('sitephone', '13026105388'),
('siteaddress', '湖北省武汉市'),
('siteemail', 'v@dazan.cn'),
('sitestatus', '1'),
('siteclosereason', '本网站长的过于风流倜傥,特关闭2小时以面壁思过'),
('siteurl', 'http://dzmcms.dazan.cn/'),
('siteurlrewrite', 'none'),
('sitebrowser', ''),
('sitedescription', ''),
('rate', '1'),
('siteqq', '1040811569');

-- --------------------------------------------------------

--
-- 表的结构 `dzmc_systemaction`
--

CREATE TABLE IF NOT EXISTS `dzmc_systemaction` (
  `id` tinyint(3) NOT NULL AUTO_INCREMENT,
  `fid` tinyint(3) NOT NULL DEFAULT '0',
  `title` varchar(50) NOT NULL DEFAULT '',
  `action` varchar(50) NOT NULL DEFAULT '',
  `todo` varchar(255) DEFAULT NULL,
  `do` varchar(255) DEFAULT NULL,
  `page` varchar(255) NOT NULL DEFAULT '',
  `listnum` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=66 ;

--
-- 转存表中的数据 `dzmc_systemaction`
--

INSERT INTO `dzmc_systemaction` (`id`, `fid`, `title`, `action`, `todo`, `do`, `page`, `listnum`) VALUES
(1, 0, '系统设置', '0', '0', '0', '0', 1),
(2, 1, '系统参数设置', 'system_set', 'show', '', 'system_set.inc.php', 1),
(4, 1, '管理员管理', 'system_user', 'edituser', '1', 'system_user.inc.php', 0),
(60, 56, '添加商品分类', 'goods_clasadd', 'clasadd', NULL, 'goods.inc.php', 0),
(59, 56, '商品分类管理', 'goods_class', 'class', NULL, 'goods.inc.php', 0),
(58, 56, '商品添加', 'goods_add', 'add', NULL, 'goods.inc.php', 0),
(57, 56, '商品列表', 'goods_list', 'list', NULL, 'goods.inc.php', 0),
(56, 0, '商品管理', '', NULL, NULL, '', 0),
(63, 61, '非物品销售', 'buy_cash', 'cash', NULL, 'buy.inc.php', 0),
(62, 61, '商品销售', 'buy_list', 'list', NULL, 'buy.inc.php', 0),
(61, 0, '销售管理', '', NULL, NULL, '', 0),
(41, 88, '数据库优化', 'database', 'list', '', 'data.inc.php', 3),
(42, 0, '会员管理', '', NULL, NULL, '', 0),
(43, 42, '会员查询', 'member_find', 'find', NULL, 'member.inc.php', 0),
(45, 42, '添加会员', 'member_add', 'add', NULL, 'member.inc.php', 0),
(46, 42, '账户充值', 'member_pay', 'pay', NULL, 'member.inc.php', 0),
(47, 42, '积分兑换', 'member_credits', 'credits', NULL, 'member.inc.php', 0),
(48, 42, '积分变动', 'member_jifenlog', 'jifenlog', NULL, 'member.inc.php', 0),
(49, 42, '转卡', 'member_cardtransfer', 'cardtransfer', NULL, 'member.inc.php', 0),
(50, 42, '会员等级', 'member_grade', 'grade', NULL, 'member.inc.php', 0),
(51, 42, '添加等级', 'member_gradadd', 'gradadd', NULL, 'member.inc.php', 0),
(52, 42, '会员导出', 'member_export', 'export', NULL, 'member.inc.php', 0),
(53, 42, '会员导入', 'member_import', 'import', NULL, 'member.inc.php', 0),
(54, 42, '密码修改', 'member_changePassword', 'changePassword', NULL, 'member.inc.php', 0),
(55, 42, '会员卡挂失', 'member_guashi', 'guashi', NULL, 'member.inc.php', 0),
(64, 61, '销售记录', 'buy_log', 'log', NULL, 'buy.inc.php', 0),
(65, 61, '退货记录', 'buy_back', 'back', NULL, 'buy.inc.php', 0);

-- --------------------------------------------------------

--
-- 表的结构 `dzmc_systemuser`
--

CREATE TABLE IF NOT EXISTS `dzmc_systemuser` (
  `id` mediumint(9) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL DEFAULT '',
  `zname` varchar(50) NOT NULL COMMENT '真实姓名',
  `password` varchar(50) NOT NULL DEFAULT '',
  `lastlogintime` int(10) NOT NULL DEFAULT '0',
  `lastloginip` varchar(40) NOT NULL DEFAULT '',
  `actions` text NOT NULL,
  `userlevel` tinyint(1) NOT NULL DEFAULT '0',
  `QQ` varchar(15) NOT NULL COMMENT 'QQ',
  `email` varchar(255) NOT NULL COMMENT '手机',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=250 ;

--
-- 转存表中的数据 `dzmc_systemuser`
--

INSERT INTO `dzmc_systemuser` (`id`, `username`, `zname`, `password`, `lastlogintime`, `lastloginip`, `actions`, `userlevel`, `QQ`, `email`) VALUES
(1, 'admin', '刘维', 'e10adc3949ba59abbe56e057f20f883e', 1375462224, '127.0.0.1', 'all', 1, '0', 'hubei_java@qq.com');

-- --------------------------------------------------------

--
-- 表的结构 `dzmc_urls`
--

CREATE TABLE IF NOT EXISTS `dzmc_urls` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `url` text NOT NULL COMMENT '连接地址',
  `alias` varchar(40) DEFAULT NULL COMMENT '别名',
  `add_date` int(10) NOT NULL COMMENT '添加日期',
  `annotation` text COMMENT '注释',
  `times` int(20) DEFAULT '0' COMMENT '次数',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='链接表' AUTO_INCREMENT=10005 ;

--
-- 转存表中的数据 `dzmc_urls`
--

INSERT INTO `dzmc_urls` (`id`, `url`, `alias`, `add_date`, `annotation`, `times`) VALUES
(10000, 'http://www.baidu.com', 'dazan', 1370010810, NULL, 2),
(154, 'http://www.dazan.cn', 'qq', 1370010810, NULL, 6),
(156, 'http://www.dazan.cn', 'www', 1370010810, NULL, 0),
(158, 'http://www.dazan.cn', NULL, 1370010810, NULL, 0),
(162, 'http://www.dazan.cn', NULL, 1370010810, NULL, 0),
(163, 'http://www.taobao.comhttp://www.baidu.com', NULL, 1370010810, NULL, 0),
(164, 'http://www.dazan.cn', NULL, 1370010810, NULL, 0),
(165, 'http://www.taobao.comhttp://www.baidu.com', NULL, 1370010810, NULL, 0),
(166, 'http://www.dazan.cn', NULL, 1370010810, NULL, 0),
(168, 'http://www.dazan.cn', NULL, 1370010810, NULL, 0),
(170, 'http://www.dazan.cn', NULL, 1370010810, NULL, 0),
(180, 'http://www.baidu.com', NULL, 1370010815, NULL, 0),
(181, 'http://www.dazan.cn', NULL, 1370010815, NULL, 0),
(183, 'http://www.dazan.cn', NULL, 1370010815, NULL, 0),
(185, 'http://www.dazan.cn', NULL, 1370010815, NULL, 1),
(187, 'http://www.dazan.cn', NULL, 1370010815, NULL, 0),
(191, 'http://www.dazan.cn', NULL, 1370010815, NULL, 0),
(193, 'http://www.dazan.cn', NULL, 1370010815, NULL, 0),
(195, 'http://www.dazan.cn', NULL, 1370010815, NULL, 0),
(197, 'http://www.dazan.cn', NULL, 1370010815, NULL, 0),
(199, 'http://www.dazan.cn', NULL, 1370010815, NULL, 0),
(200, 'http://www.taobao.comhttp://www.baidu.com', NULL, 1370010815, NULL, 0),
(201, 'http://www.dazan.cn', NULL, 1370010815, NULL, 0),
(202, 'http://www.taobao.comhttp://www.baidu.com', NULL, 1370010815, NULL, 0),
(203, 'http://www.dazan.cn', NULL, 1370010815, NULL, 0),
(204, 'http://www.taobao.comhttp://www.baidu.com', NULL, 1370010815, NULL, 0),
(205, 'http://www.dazan.cn', NULL, 1370010815, NULL, 0),
(206, 'http://www.taobao.com', NULL, 1370010815, NULL, 0),
(228, 'http://www.dazan.cn', NULL, 1370010819, NULL, 0),
(230, 'http://www.dazan.cn', NULL, 1370010819, NULL, 0),
(232, 'http://www.dazan.cn', NULL, 1370010819, NULL, 0),
(233, 'http://www.taobao.com', NULL, 1370010819, NULL, 0),
(10001, 'http://www.dazan.cn/module-12.html', '', 1370878992, '', 0),
(10002, 'http://www.baidu.com', NULL, 1370881258, NULL, 0),
(10003, 'http://www.dazan.cn', NULL, 1370881258, NULL, 0),
(10004, 'http://www.taobao.com', NULL, 1370881258, NULL, 0);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
