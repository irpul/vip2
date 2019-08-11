-- phpMyAdmin SQL Dump
-- version 4.0.10.14
-- http://www.phpmyadmin.net
--
-- Host: localhost:3306
-- Generation Time: Jan 25, 2017 at 12:27 PM
-- Server version: 5.6.34
-- PHP Version: 5.6.20

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE IF NOT EXISTS `admin` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(120) COLLATE utf8_persian_ci NOT NULL,
  `password` varchar(120) COLLATE utf8_persian_ci NOT NULL,
  `type` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_persian_ci AUTO_INCREMENT=3 ;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `username`, `password`, `type`) VALUES
(2, 'admin', 'admin', 4095);

-- --------------------------------------------------------

--
-- Table structure for table `blockip`
--

CREATE TABLE IF NOT EXISTS `blockip` (
  `ip` varchar(15) COLLATE utf8_persian_ci NOT NULL,
  `allow` tinyint(4) NOT NULL,
  PRIMARY KEY (`ip`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_persian_ci;

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

CREATE TABLE IF NOT EXISTS `category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` text COLLATE utf8_persian_ci NOT NULL,
  `day` int(11) NOT NULL,
  `price` int(11) NOT NULL,
  `multi` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_persian_ci AUTO_INCREMENT=14 ;

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`id`, `title`, `day`, `price`, `multi`) VALUES
(7, ' 15 روز', 15, 20000, 0),
(8, '  1 ماهه', 30, 30000, 0),
(9, ' 2 ماهه', 60, 55000, 0),
(10, ' 3 ماهه', 90, 80000, 0),
(11, ' 6 ماهه', 180, 150000, 0),
(12, '1 ساله', 360, 300000, 0),
(13, ' نامحدود', 720, 600000, 0);

-- --------------------------------------------------------

--
-- Table structure for table `dlinfo`
--

CREATE TABLE IF NOT EXISTS `dlinfo` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `userid` int(11) NOT NULL,
  `file` varchar(120) CHARACTER SET utf8 COLLATE utf8_persian_ci NOT NULL,
  `ip` varchar(20) NOT NULL,
  `time` int(11) NOT NULL,
  `hash` varchar(50) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id` (`id`,`userid`,`ip`,`time`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `dlinfo_guest`
--

CREATE TABLE IF NOT EXISTS `dlinfo_guest` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `file` varchar(120) CHARACTER SET utf8 COLLATE utf8_persian_ci NOT NULL,
  `ip` varchar(20) NOT NULL,
  `time` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id` (`id`,`ip`,`time`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `geoip`
--

CREATE TABLE IF NOT EXISTS `geoip` (
  `from` int(10) unsigned NOT NULL,
  `to` int(10) unsigned NOT NULL,
  `country` varchar(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `job`
--

CREATE TABLE IF NOT EXISTS `job` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(128) COLLATE utf8_persian_ci NOT NULL,
  `time` int(11) NOT NULL,
  `newslistid` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_persian_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `newslist`
--

CREATE TABLE IF NOT EXISTS `newslist` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `text` text COLLATE utf8_persian_ci NOT NULL,
  `subject` varchar(120) COLLATE utf8_persian_ci NOT NULL,
  `number` int(11) NOT NULL,
  `sent` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_persian_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `payment`
--

CREATE TABLE IF NOT EXISTS `payment` (
  `payment_id` int(11) NOT NULL AUTO_INCREMENT,
  `payment_user` varchar(128) NOT NULL,
  `payment_email` varchar(128) NOT NULL,
  `payment_mobile` varchar(30) NOT NULL,
  `payment_password` varchar(120) NOT NULL,
  `payment_categoryid` int(11) NOT NULL,
  `payment_amount` int(11) NOT NULL,
  `payment_ref` int(11) NOT NULL,
  `payment_gateway` varchar(128) NOT NULL,
  `payment_res_num` varchar(64) DEFAULT NULL,
  `payment_ref_num` varchar(64) DEFAULT NULL,
  `payment_status` varchar(2) NOT NULL DEFAULT '1',
  `payment_rand` varchar(64) NOT NULL,
  `payment_time` varchar(30) NOT NULL,
  `payment_ip` varchar(15) NOT NULL,
  PRIMARY KEY (`payment_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `plugin`
--

CREATE TABLE IF NOT EXISTS `plugin` (
  `plugin_id` int(11) NOT NULL AUTO_INCREMENT,
  `plugin_uniq` varchar(128) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL,
  `plugin_name` varchar(128) NOT NULL,
  `plugin_type` varchar(128) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL,
  `plugin_status` varchar(2) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL,
  `plugin_time` varchar(30) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL,
  PRIMARY KEY (`plugin_id`),
  KEY `plugin_time` (`plugin_time`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

-- --------------------------------------------------------

--
-- Table structure for table `plugindata`
--

CREATE TABLE IF NOT EXISTS `plugindata` (
  `plugindata_id` int(11) NOT NULL AUTO_INCREMENT,
  `plugindata_uniq` varchar(128) NOT NULL,
  `plugindata_field_name` varchar(256) NOT NULL,
  `plugindata_field_value` text NOT NULL,
  PRIMARY KEY (`plugindata_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=10 ;

-- --------------------------------------------------------

--
-- Table structure for table `servers`
--

CREATE TABLE IF NOT EXISTS `servers` (
  `id` int(11) NOT NULL,
  `url` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `setting`
--

CREATE TABLE IF NOT EXISTS `setting` (
  `sitename` varchar(256) COLLATE utf8_persian_ci NOT NULL,
  `ajax` int(11) NOT NULL,
  `paymentinfo` text COLLATE utf8_persian_ci NOT NULL,
  `pagelimit` int(11) NOT NULL,
  `guestspeed` int(11) NOT NULL,
  `guesttime` int(11) NOT NULL,
  `guestcaptcha` int(11) NOT NULL,
  `guestreserve` int(11) NOT NULL,
  `vipbantime` int(11) NOT NULL,
  `dltext` text COLLATE utf8_persian_ci NOT NULL,
  `linktitle` varchar(256) COLLATE utf8_persian_ci NOT NULL,
  `linkurl` text COLLATE utf8_persian_ci NOT NULL,
  `email` varchar(50) COLLATE utf8_persian_ci NOT NULL,
  `name` varchar(120) COLLATE utf8_persian_ci NOT NULL,
  `replyto` varchar(120) COLLATE utf8_persian_ci NOT NULL,
  `signature` text COLLATE utf8_persian_ci NOT NULL,
  `reportsubject` varchar(120) COLLATE utf8_persian_ci NOT NULL,
  `ticket_new_subject` varchar(60) COLLATE utf8_persian_ci NOT NULL,
  `ticket_new_content` mediumtext COLLATE utf8_persian_ci NOT NULL,
  `ticket_answer_subject` varchar(60) COLLATE utf8_persian_ci NOT NULL,
  `ticket_answer_content` mediumtext COLLATE utf8_persian_ci NOT NULL,
  `register_subject` varchar(60) COLLATE utf8_persian_ci NOT NULL,
  `register_content` mediumtext COLLATE utf8_persian_ci NOT NULL,
  `forgot_subject` varchar(60) COLLATE utf8_persian_ci NOT NULL,
  `forgot_content` mediumtext COLLATE utf8_persian_ci NOT NULL,
  `ekhtar1_subject` varchar(60) COLLATE utf8_persian_ci NOT NULL,
  `ekhtar1_content` mediumtext COLLATE utf8_persian_ci NOT NULL,
  `ekhtar1_time` int(11) NOT NULL,
  `ekhtar2_subject` varchar(60) COLLATE utf8_persian_ci NOT NULL,
  `ekhtar2_content` mediumtext COLLATE utf8_persian_ci NOT NULL,
  `ekhtar2_time` int(11) NOT NULL,
  `news_top` longtext COLLATE utf8_persian_ci NOT NULL,
  `news_right` longtext COLLATE utf8_persian_ci NOT NULL,
  `news_bottom` longtext COLLATE utf8_persian_ci NOT NULL,
  `issmtp` tinyint(4) NOT NULL,
  `smtpauth` tinyint(1) NOT NULL,
  `smtpusername` varchar(120) COLLATE utf8_persian_ci NOT NULL,
  `smtppassword` varchar(120) COLLATE utf8_persian_ci NOT NULL,
  `smtphost` varchar(120) COLLATE utf8_persian_ci NOT NULL,
  `smtpport` int(11) NOT NULL,
  `smtpsecure` varchar(10) COLLATE utf8_persian_ci NOT NULL,
  `timeformat` varchar(50) COLLATE utf8_persian_ci NOT NULL,
  `ref_day` int(11) NOT NULL,
  `blockip` varchar(400) COLLATE utf8_persian_ci NOT NULL,
  `singletime` int(11) NOT NULL,
  `singleprice` int(11) NOT NULL,
  `emaillimit` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_persian_ci;

--
-- Dumping data for table `setting`
--

INSERT INTO `setting` (`sitename`, `ajax`, `paymentinfo`, `pagelimit`, `guestspeed`, `guesttime`, `guestcaptcha`, `guestreserve`, `vipbantime`, `dltext`, `linktitle`, `linkurl`, `email`, `name`, `replyto`, `signature`, `reportsubject`, `ticket_new_subject`, `ticket_new_content`, `ticket_answer_subject`, `ticket_answer_content`, `register_subject`, `register_content`, `forgot_subject`, `forgot_content`, `ekhtar1_subject`, `ekhtar1_content`, `ekhtar1_time`, `ekhtar2_subject`, `ekhtar2_content`, `ekhtar2_time`, `news_top`, `news_right`, `news_bottom`, `issmtp`, `smtpauth`, `smtpusername`, `smtppassword`, `smtphost`, `smtpport`, `smtpsecure`, `timeformat`, `ref_day`, `blockip`, `singletime`, `singleprice`, `emaillimit`) VALUES
('سیستم اشتراک ویژه', 1, '        <br>\r\n         پیشنهاد ما به شما تهیه اکانت اشتراک ویژه (VIP) است .\r\n<br>\r\n با هزینه بسیار کم از امکانات سایت بهره مند شوید .\r\n         <br>\r\n         هم اکنون ثبت نام کنید و اکانت خود را خریداری نمایید .\r\n         <br>\r\n         <strong>*</strong> مشخصاتی که انتخاب میکنید جهت اطمینان از طریق ایمیل برای شما ارسال می شود ،\r\n         <br>\r\n   <strong>*</strong> لطفا ایمیل معتبر وارد نمایید. توجه داشته باشید ایمیل با www وارد نکنید\r\n  <br>\r\n<strong>*</strong> شماره تراکنش و مشخصات پرداخت در هنگام خرید برای شما ایمیل می شود\r\n<br>\r\n<strong>*</strong> پرداخت با تمامی کارت های عضو شتاب ( با هر کارتی می توانید پرداخت کنید )\r\n<br>\r\n         <strong>*</strong> اکانت پس از پرداخت به صورت آنی و با مشخصاتی که خودتان انتخاب میکنید فعال خواهد شد.\r\n    <br>\r\n\r\n<strong>*</strong> در صورت بروز هرگونه مشکل در پنل خود تیکت ارسال نمایید تا سریعا درخواست شما پیگیری شود.\r\n<strong></strong> \r\n<div style="font:15px BYekan,tahoma;color:black;">دانلود بدون محدودیت حجمی و تمامی امکانات نامحدود می باشد.</div>\r\n\r\n<strong></strong> \r\n<div style="font:18px BYekan,tahoma;color:black;">با تمامی کارت های بانکی می توانید پرداخت نمایید.</div>\r\n', 300, 20, 10, 1, 24, 1, 'در صورتی که اکانت ویژه ندارید برای برای <font color=\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\"red\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\" size=\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\"4\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\">خرید اشتراک ویژه</font> کلیک کنید\r\n', 'صفحه اصلی سایت', 'http://example.com', 'info@example.com', 'اشتراک ویژه', 'info@example.com', '', 'گزارش خرابی لینک', 'تیکت شما ثبت شد', '</font></div></td></tr><tr bgcolor="#ffffff"><td colspan="2"><div class="msg"><font color="#888888"><div style="text-align:right;font-size:13px;font-weight:normal;line-height:30px;font-family:tahoma,sans-serif;width:100%;background-color:#f9f9f9;padding:100px;margin:0px">\r\n<div style="margin:3px">\r\n<div style="font-family:tahoma;font-size:16px;width:500px;margin:auto;direction:rtl;color:navy;background:#d6e0ff;color:#000;border-radius:5px;border:1px dashed #6699ff;padding-left:12px;padding-right:12px;padding-top:5px;padding-bottom:5px" dir="rtl">\r\nسامانه اشتراک کاربران ویژه\r\n</div></div>\r\n<div style="width:500px;margin:0 auto;border:1px solid;color:#000;border-radius:5px;border:1px dashed #339966;padding-left:12px;padding-right:12px;padding-top:10px;padding-bottom:10px;background-color:#dff2bf" dir="rtl">\r\nاطلاعات تیکت :\r\n<br>\r\nنام کاربری : {username}\r\n<br>\r\nعنوان : {title}\r\n<br>\r\nزمان : {time}\r\n<br>\r\nمتن : {content}\r\n</div>\r\n<div style="margin:3px">\r\n<div style="text-align:center;font-family:tahoma;font-size:13px;width:500px;margin:auto;direction:rtl;color:navy;background:#d6e0ff;color:#000;border-radius:5px;border:1px dashed #6699ff;padding-left:12px;padding-right:12px;padding-top:5px;padding-bottom:5px" dir="rtl">\r\n<a href="http://www.movie.asanmusic.com" target="_blank" rel="noreferrer">صفحه اصلی سایت</a>     <a href="http://vip.asanmusic.com/payment.php" target="_blank" rel="noreferrer">سامانه پرداخت آنلاین</a>   <a href="http://www.movie.asanmusic.com/vip" target="_blank" rel="noreferrer">آموزش کامل استفاده از اکانت</a>\r\n</div>\r\n</div>\r\n</div>', 'به تیکت شما پاسخ داده شد', '</font></div></td></tr><tr bgcolor="#ffffff"><td colspan="2"><div class="msg"><font color="#888888"><div style="text-align:right;font-size:13px;font-weight:normal;line-height:30px;font-family:tahoma,sans-serif;width:100%;background-color:#f9f9f9;padding:100px;margin:0px">\r\n<div style="margin:3px">\r\n<div style="font-family:tahoma;font-size:16px;width:500px;margin:auto;direction:rtl;color:navy;background:#d6e0ff;color:#000;border-radius:5px;border:1px dashed #6699ff;padding-left:12px;padding-right:12px;padding-top:5px;padding-bottom:5px" dir="rtl">\r\nسامانه اشتراک کاربران ویژه\r\n</div></div>\r\n<div style="width:500px;margin:0 auto;border:1px solid;color:#000;border-radius:5px;border:1px dashed #339966;padding-left:12px;padding-right:12px;padding-top:10px;padding-bottom:10px;background-color:#dff2bf" dir="rtl">\r\n\r\nاطلاعات تیکت :\r\n<br>\r\nنام کاربری : {username}\r\n<br>\r\nعنوان : {title}\r\n<br>\r\nزمان : {time}\r\n<br>\r\nمتن : {content}\r\n\r\n\r\n</div>\r\n<div style="margin:3px">\r\n<div style="text-align:center;font-family:tahoma;font-size:13px;width:500px;margin:auto;direction:rtl;color:navy;background:#d6e0ff;color:#000;border-radius:5px;border:1px dashed #6699ff;padding-left:12px;padding-right:12px;padding-top:5px;padding-bottom:5px" dir="rtl">\r\n<a href="http://www.movie.asanmusic.com" target="_blank" rel="noreferrer">صفحه اصلی سایت</a>     <a href="http://vip.asanmusic.com/payment.php" target="_blank" rel="noreferrer">سامانه پرداخت آنلاین</a>   <a href="http://www.movie.asanmusic.com/vip" target="_blank" rel="noreferrer">آموزش کامل استفاده از اکانت</a>\r\n</div>\r\n</div>\r\n</div>', 'اکانت شما با موفقیت ایجاد شد', '</font></div></td></tr><tr bgcolor="#ffffff"><td colspan="2"><div class="msg"><font color="#888888"><div style="text-align:right;font-size:13px;font-weight:normal;line-height:30px;font-family:tahoma,sans-serif;width:100%;background-color:#f9f9f9;padding:100px;margin:0px">\r\n<div style="margin:3px">\r\n<div style="font-family:tahoma;font-size:16px;width:500px;margin:auto;direction:rtl;color:navy;background:#d6e0ff;color:#000;border-radius:5px;border:1px dashed #6699ff;padding-left:12px;padding-right:12px;padding-top:5px;padding-bottom:5px" dir="rtl">\r\nسامانه اشتراک کاربران ویژه\r\n</div></div>\r\n<div style="width:500px;margin:0 auto;border:1px solid;color:#000;border-radius:5px;border:1px dashed #339966;padding-left:12px;padding-right:12px;padding-top:10px;padding-bottom:10px;background-color:#dff2bf" dir="rtl">\r\n\r\nاطلاعات تیکت :\r\n<br>\r\nنام کاربری : {username}\r\n<br>\r\nکلمه عبور : {password}\r\n<br>\r\nایمیل : {email}\r\n<br>\r\nزمان : {time}\r\n<br>\r\nدسته : {category}\r\n<br>\r\nشناسه پرداخت : {resnum} - {refnum}\r\n\r\n</div>\r\n<div style="margin:3px">\r\n<div style="text-align:center;font-family:tahoma;font-size:13px;width:500px;margin:auto;direction:rtl;color:navy;background:#d6e0ff;color:#000;border-radius:5px;border:1px dashed #6699ff;padding-left:12px;padding-right:12px;padding-top:5px;padding-bottom:5px" dir="rtl">\r\n<a href="http://www.movie.asanmusic.com" target="_blank" rel="noreferrer">صفحه اصلی سایت</a>     <a href="http://vip.asanmusic.com/payment.php" target="_blank" rel="noreferrer">سامانه پرداخت آنلاین</a>   <a href="http://www.movie.asanmusic.com/vip" target="_blank" rel="noreferrer">آموزش کامل استفاده از اکانت</a>\r\n</div>\r\n</div>\r\n</div>', 'درخواست فراموشی کلمه عبور', '</font></div></td></tr><tr bgcolor="#ffffff"><td colspan="2"><div class="msg"><font color="#888888"><div style="text-align:right;font-size:13px;font-weight:normal;line-height:30px;font-family:tahoma,sans-serif;width:100%;background-color:#f9f9f9;padding:100px;margin:0px">\r\n<div style="margin:3px">\r\n<div style="font-family:tahoma;font-size:16px;width:500px;margin:auto;direction:rtl;color:navy;background:#d6e0ff;color:#000;border-radius:5px;border:1px dashed #6699ff;padding-left:12px;padding-right:12px;padding-top:5px;padding-bottom:5px" dir="rtl">\r\nسامانه اشتراک کاربران ویژه\r\n</div></div>\r\n<div style="width:500px;margin:0 auto;border:1px solid;color:#000;border-radius:5px;border:1px dashed #339966;padding-left:12px;padding-right:12px;padding-top:10px;padding-bottom:10px;background-color:#dff2bf" dir="rtl">\r\n\r\nدرخواست فراموشی پسورد :\r\n<br>\r\nزمان درخواست : {time}\r\n<br>\r\nلینک : <a href="{code}">{code}</a>\r\n\r\n</div>\r\n<div style="margin:3px">\r\n<div style="text-align:center;font-family:tahoma;font-size:13px;width:500px;margin:auto;direction:rtl;color:navy;background:#d6e0ff;color:#000;border-radius:5px;border:1px dashed #6699ff;padding-left:12px;padding-right:12px;padding-top:5px;padding-bottom:5px" dir="rtl">\r\n<a href="http://www.movie.asanmusic.com" target="_blank" rel="noreferrer">صفحه اصلی سایت</a>     <a href="http://vip.asanmusic.com/payment.php" target="_blank" rel="noreferrer">سامانه پرداخت آنلاین</a>   <a href="http://www.movie.asanmusic.com/vip" target="_blank" rel="noreferrer">آموزش کامل استفاده از اکانت</a>\r\n</div>\r\n</div>\r\n</div>', 'هشدار اول پایان اکانت', '</font></div></td></tr><tr bgcolor="#ffffff"><td colspan="2"><div class="msg"><font color="#888888"><div style="text-align:right;font-size:13px;font-weight:normal;line-height:30px;font-family:tahoma,sans-serif;width:100%;background-color:#f9f9f9;padding:100px;margin:0px">\r\n<div style="margin:3px">\r\n<div style="font-family:tahoma;font-size:16px;width:500px;margin:auto;direction:rtl;color:navy;background:#d6e0ff;color:#000;border-radius:5px;border:1px dashed #6699ff;padding-left:12px;padding-right:12px;padding-top:5px;padding-bottom:5px" dir="rtl">\r\nسامانه اشتراک کاربران ویژه\r\n</div></div>\r\n<div style="width:500px;margin:0 auto;border:1px solid;color:#000;border-radius:5px;border:1px dashed #339966;padding-left:12px;padding-right:12px;padding-top:10px;padding-bottom:10px;background-color:#dff2bf" dir="rtl">\r\n\r\nکاربر گرامی {username}، اکانت شما در تاریخ {endtime} به پایان می رسد، لطفا نسبت به تمدید آن اقدام فرمایید\r\n\r\n</div>\r\n<div style="margin:3px">\r\n<div style="text-align:center;font-family:tahoma;font-size:13px;width:500px;margin:auto;direction:rtl;color:navy;background:#d6e0ff;color:#000;border-radius:5px;border:1px dashed #6699ff;padding-left:12px;padding-right:12px;padding-top:5px;padding-bottom:5px" dir="rtl">\r\n<a href="http://www.movie.asanmusic.com" target="_blank" rel="noreferrer">صفحه اصلی سایت</a>     <a href="http://vip.asanmusic.com/payment.php" target="_blank" rel="noreferrer">سامانه پرداخت آنلاین</a>   <a href="http://www.movie.asanmusic.com/vip" target="_blank" rel="noreferrer">آموزش کامل استفاده از اکانت</a>\r\n</div>\r\n</div>\r\n</div>', 14, 'هشدار دوم پایان اکانت', '</font></div></td></tr><tr bgcolor="#ffffff"><td colspan="2"><div class="msg"><font color="#888888"><div style="text-align:right;font-size:13px;font-weight:normal;line-height:30px;font-family:tahoma,sans-serif;width:100%;background-color:#f9f9f9;padding:100px;margin:0px">\r\n<div style="margin:3px">\r\n<div style="font-family:tahoma;font-size:16px;width:500px;margin:auto;direction:rtl;color:navy;background:#d6e0ff;color:#000;border-radius:5px;border:1px dashed #6699ff;padding-left:12px;padding-right:12px;padding-top:5px;padding-bottom:5px" dir="rtl">\r\nسامانه اشتراک کاربران ویژه\r\n</div></div>\r\n<div style="width:500px;margin:0 auto;border:1px solid;color:#000;border-radius:5px;border:1px dashed #339966;padding-left:12px;padding-right:12px;padding-top:10px;padding-bottom:10px;background-color:#dff2bf" dir="rtl">\r\n\r\nکاربر گرامی {username}، اکانت شما در تاریخ {endtime} به پایان می رسد، لطفا نسبت به تمدید آن اقدام فرمایید\r\n\r\n</div>\r\n<div style="margin:3px">\r\n<div style="text-align:center;font-family:tahoma;font-size:13px;width:500px;margin:auto;direction:rtl;color:navy;background:#d6e0ff;color:#000;border-radius:5px;border:1px dashed #6699ff;padding-left:12px;padding-right:12px;padding-top:5px;padding-bottom:5px" dir="rtl">\r\n<a href="http://www.movie.asanmusic.com" target="_blank" rel="noreferrer">صفحه اصلی سایت</a>     <a href="http://vip.asanmusic.com/payment.php" target="_blank" rel="noreferrer">سامانه پرداخت آنلاین</a>   <a href="http://www.movie.asanmusic.com/vip" target="_blank" rel="noreferrer">آموزش کامل استفاده از اکانت</a>\r\n</div>\r\n</div>\r\n</div>', 22, 'به سیستم بسیار پیشرفته خرید اشتراک ویژه دانلود خوش آمدید . با عضویت در این سیستم با 32 کانکشن فعال به طور همزمان دانلود کنید .\r\n<ul>\r\nبرای تماس با بخش پشتیبانی 24 ساعته سایت وارد پنل خود شوید و تیکت جدید ارسال نمایید.', 'به پنل کاربری خود خوش آمدید.\r\n\r\nهر سوال و مشکلی که داشتید در منوی سمت راست بخش تیکت جدید به ما اعلام نمایید تا شما را راهنمایی نماییم', '', 0, 1, 'example@yahoo.com', 'example@yahoo.com', 'smtp.gmail.com', 465, 'ssl', 'Y/m/d', 1, '', 0, 12, 0);

-- --------------------------------------------------------

--
-- Table structure for table `single`
--

CREATE TABLE IF NOT EXISTS `single` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `file` text COLLATE utf8_persian_ci NOT NULL,
  `hash` varchar(50) COLLATE utf8_persian_ci NOT NULL,
  `time` int(11) NOT NULL,
  `userid` int(11) DEFAULT NULL,
  `server` int(11) NOT NULL,
  `paymentid` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_persian_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `temp`
--

CREATE TABLE IF NOT EXISTS `temp` (
  `userid` int(11) NOT NULL,
  `hash` varchar(64) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `ticket`
--

CREATE TABLE IF NOT EXISTS `ticket` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(150) COLLATE utf8_persian_ci NOT NULL,
  `content` text COLLATE utf8_persian_ci NOT NULL,
  `time` int(11) NOT NULL,
  `userid` int(11) NOT NULL,
  `usertype` tinyint(4) NOT NULL,
  `status` tinyint(4) NOT NULL,
  `attach` tinyint(4) NOT NULL,
  `file_name` varchar(100) COLLATE utf8_persian_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_persian_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `ticketdata`
--

CREATE TABLE IF NOT EXISTS `ticketdata` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `parentid` int(11) NOT NULL,
  `content` text COLLATE utf8_persian_ci NOT NULL,
  `time` int(11) NOT NULL,
  `userid` int(11) NOT NULL,
  `usertype` tinyint(4) NOT NULL,
  `attach` tinyint(4) NOT NULL,
  `file_name` varchar(100) COLLATE utf8_persian_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_persian_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(120) CHARACTER SET utf8 COLLATE utf8_persian_ci NOT NULL,
  `password` varchar(120) CHARACTER SET utf8 COLLATE utf8_persian_ci NOT NULL,
  `starttime` int(11) NOT NULL,
  `endtime` int(20) NOT NULL,
  `mobile` varchar(30) NOT NULL,
  `email` varchar(120) NOT NULL,
  `categoryid` int(11) NOT NULL,
  `active` tinyint(4) NOT NULL,
  `multi` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `users_ref`
--

CREATE TABLE IF NOT EXISTS `users_ref` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `userid` int(11) NOT NULL,
  `day` int(11) NOT NULL,
  `from` int(11) NOT NULL,
  `time` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_persian_ci AUTO_INCREMENT=1 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
