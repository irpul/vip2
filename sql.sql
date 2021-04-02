-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 02, 2021 at 03:27 PM
-- Server version: 10.4.11-MariaDB
-- PHP Version: 7.4.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `vip2`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `username` varchar(120) COLLATE utf8_persian_ci NOT NULL,
  `password` varchar(120) COLLATE utf8_persian_ci NOT NULL,
  `type` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_persian_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `username`, `password`, `type`) VALUES
(2, 'admin', 'admin', 4095);

-- --------------------------------------------------------

--
-- Table structure for table `blockip`
--

CREATE TABLE `blockip` (
  `ip` varchar(15) COLLATE utf8_persian_ci NOT NULL,
  `allow` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_persian_ci;

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

CREATE TABLE `category` (
  `id` int(11) NOT NULL,
  `title` text COLLATE utf8_persian_ci NOT NULL,
  `day` int(11) NOT NULL,
  `price` int(11) NOT NULL,
  `multi` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_persian_ci;

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

CREATE TABLE `dlinfo` (
  `id` int(11) NOT NULL,
  `userid` int(11) NOT NULL,
  `file` varchar(120) CHARACTER SET utf8 COLLATE utf8_persian_ci NOT NULL,
  `ip` varchar(20) NOT NULL,
  `time` int(11) NOT NULL,
  `hash` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `dlinfo_guest`
--

CREATE TABLE `dlinfo_guest` (
  `id` int(11) NOT NULL,
  `file` varchar(120) CHARACTER SET utf8 COLLATE utf8_persian_ci NOT NULL,
  `ip` varchar(20) NOT NULL,
  `time` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `geoip`
--

CREATE TABLE `geoip` (
  `from` int(10) UNSIGNED NOT NULL,
  `to` int(10) UNSIGNED NOT NULL,
  `country` varchar(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `job`
--

CREATE TABLE `job` (
  `id` int(11) NOT NULL,
  `email` varchar(128) COLLATE utf8_persian_ci NOT NULL,
  `time` int(11) NOT NULL,
  `newslistid` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_persian_ci;

-- --------------------------------------------------------

--
-- Table structure for table `newslist`
--

CREATE TABLE `newslist` (
  `id` int(11) NOT NULL,
  `text` text COLLATE utf8_persian_ci NOT NULL,
  `subject` varchar(120) COLLATE utf8_persian_ci NOT NULL,
  `number` int(11) NOT NULL,
  `sent` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_persian_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payment`
--

CREATE TABLE `payment` (
  `payment_id` int(11) NOT NULL,
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
  `payment_ip` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `plugin`
--

CREATE TABLE `plugin` (
  `plugin_id` int(11) NOT NULL,
  `plugin_uniq` varchar(128) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL,
  `plugin_name` varchar(128) NOT NULL,
  `plugin_type` varchar(128) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL,
  `plugin_status` varchar(2) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL,
  `plugin_time` varchar(30) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `plugin`
--

INSERT INTO `plugin` (`plugin_id`, `plugin_uniq`, `plugin_name`, `plugin_type`, `plugin_status`, `plugin_time`) VALUES
(1, 'irpul', 'ایرپول', 'payment', '1', '');

-- --------------------------------------------------------

--
-- Table structure for table `plugindata`
--

CREATE TABLE `plugindata` (
  `plugindata_id` int(11) NOT NULL,
  `plugindata_uniq` varchar(128) NOT NULL,
  `plugindata_field_name` varchar(256) NOT NULL,
  `plugindata_field_value` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `plugindata`
--

INSERT INTO `plugindata` (`plugindata_id`, `plugindata_uniq`, `plugindata_field_name`, `plugindata_field_value`) VALUES
(5, 'mihanpal', 'merchant', '871ad3afbir'),
(9, 'jahanpay', 'merchant', 'gt32834g557'),
(10, 'irpul', 'token', ''),
(11, 'irpul', 'title', '');

-- --------------------------------------------------------

--
-- Table structure for table `servers`
--

CREATE TABLE `servers` (
  `id` int(11) NOT NULL,
  `url` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `setting`
--

CREATE TABLE `setting` (
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
('سیستم اشتراک ویژه', 1, '        <br>\r\n         پیشنهاد ما به شما تهیه اکانت اشتراک ویژه (VIP) است .\r\n<br>\r\n با هزینه بسیار کم از امکانات سایت بهره مند شوید .\r\n         <br>\r\n         هم اکنون ثبت نام کنید و اکانت خود را خریداری نمایید .\r\n         <br>\r\n         <strong>*</strong> مشخصاتی که انتخاب میکنید جهت اطمینان از طریق ایمیل برای شما ارسال می شود ،\r\n         <br>\r\n   <strong>*</strong> لطفا ایمیل معتبر وارد نمایید. توجه داشته باشید ایمیل با www وارد نکنید\r\n  <br>\r\n<strong>*</strong> شماره تراکنش و مشخصات پرداخت در هنگام خرید برای شما ایمیل می شود\r\n<br>\r\n<strong>*</strong> پرداخت با تمامی کارت های عضو شتاب ( با هر کارتی می توانید پرداخت کنید )\r\n<br>\r\n         <strong>*</strong> اکانت پس از پرداخت به صورت آنی و با مشخصاتی که خودتان انتخاب میکنید فعال خواهد شد.\r\n    <br>\r\n\r\n<strong>*</strong> در صورت بروز هرگونه مشکل در پنل خود تیکت ارسال نمایید تا سریعا درخواست شما پیگیری شود.\r\n<strong></strong> \r\n<div style=\"font:15px BYekan,tahoma;color:black;\">دانلود بدون محدودیت حجمی و تمامی امکانات نامحدود می باشد.</div>\r\n\r\n<strong></strong> \r\n<div style=\"font:18px BYekan,tahoma;color:black;\">با تمامی کارت های بانکی می توانید پرداخت نمایید.</div>\r\n', 300, 20, 10, 1, 24, 1, 'در صورتی که اکانت ویژه ندارید برای برای <font color=\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\"red\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\" size=\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\"4\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\">خرید اشتراک ویژه</font> کلیک کنید\r\n', 'صفحه اصلی سایت', 'http://google.com', 'info@test.com', 'اشتراک ویژه', 'info@test.com', '', 'گزارش خرابی لینک', 'تیکت شما ثبت شد', '</font></div></td></tr><tr bgcolor=\"#ffffff\"><td colspan=\"2\"><div class=\"msg\"><font color=\"#888888\"><div style=\"text-align:right;font-size:13px;font-weight:normal;line-height:30px;font-family:tahoma,sans-serif;width:100%;background-color:#f9f9f9;padding:100px;margin:0px\">\r\n<div style=\"margin:3px\">\r\n<div style=\"font-family:tahoma;font-size:16px;width:500px;margin:auto;direction:rtl;color:navy;background:#d6e0ff;color:#000;border-radius:5px;border:1px dashed #6699ff;padding-left:12px;padding-right:12px;padding-top:5px;padding-bottom:5px\" dir=\"rtl\">\r\nسامانه اشتراک کاربران ویژه\r\n</div></div>\r\n<div style=\"width:500px;margin:0 auto;border:1px solid;color:#000;border-radius:5px;border:1px dashed #339966;padding-left:12px;padding-right:12px;padding-top:10px;padding-bottom:10px;background-color:#dff2bf\" dir=\"rtl\">\r\nاطلاعات تیکت :\r\n<br>\r\nنام کاربری : {username}\r\n<br>\r\nعنوان : {title}\r\n<br>\r\nزمان : {time}\r\n<br>\r\nمتن : {content}\r\n</div>\r\n<div style=\"margin:3px\">\r\n<div style=\"text-align:center;font-family:tahoma;font-size:13px;width:500px;margin:auto;direction:rtl;color:navy;background:#d6e0ff;color:#000;border-radius:5px;border:1px dashed #6699ff;padding-left:12px;padding-right:12px;padding-top:5px;padding-bottom:5px\" dir=\"rtl\">\r\n<a href=\"http://www.movie.asanmusic.com\" target=\"_blank\" rel=\"noreferrer\">صفحه اصلی سایت</a>     <a href=\"http://vip.asanmusic.com/payment.php\" target=\"_blank\" rel=\"noreferrer\">سامانه پرداخت آنلاین</a>   <a href=\"http://www.movie.asanmusic.com/vip\" target=\"_blank\" rel=\"noreferrer\">آموزش کامل استفاده از اکانت</a>\r\n</div>\r\n</div>\r\n</div>', 'به تیکت شما پاسخ داده شد', '</font></div></td></tr><tr bgcolor=\"#ffffff\"><td colspan=\"2\"><div class=\"msg\"><font color=\"#888888\"><div style=\"text-align:right;font-size:13px;font-weight:normal;line-height:30px;font-family:tahoma,sans-serif;width:100%;background-color:#f9f9f9;padding:100px;margin:0px\">\r\n<div style=\"margin:3px\">\r\n<div style=\"font-family:tahoma;font-size:16px;width:500px;margin:auto;direction:rtl;color:navy;background:#d6e0ff;color:#000;border-radius:5px;border:1px dashed #6699ff;padding-left:12px;padding-right:12px;padding-top:5px;padding-bottom:5px\" dir=\"rtl\">\r\nسامانه اشتراک کاربران ویژه\r\n</div></div>\r\n<div style=\"width:500px;margin:0 auto;border:1px solid;color:#000;border-radius:5px;border:1px dashed #339966;padding-left:12px;padding-right:12px;padding-top:10px;padding-bottom:10px;background-color:#dff2bf\" dir=\"rtl\">\r\n\r\nاطلاعات تیکت :\r\n<br>\r\nنام کاربری : {username}\r\n<br>\r\nعنوان : {title}\r\n<br>\r\nزمان : {time}\r\n<br>\r\nمتن : {content}\r\n\r\n\r\n</div>\r\n<div style=\"margin:3px\">\r\n<div style=\"text-align:center;font-family:tahoma;font-size:13px;width:500px;margin:auto;direction:rtl;color:navy;background:#d6e0ff;color:#000;border-radius:5px;border:1px dashed #6699ff;padding-left:12px;padding-right:12px;padding-top:5px;padding-bottom:5px\" dir=\"rtl\">\r\n<a href=\"http://www.movie.asanmusic.com\" target=\"_blank\" rel=\"noreferrer\">صفحه اصلی سایت</a>     <a href=\"http://vip.asanmusic.com/payment.php\" target=\"_blank\" rel=\"noreferrer\">سامانه پرداخت آنلاین</a>   <a href=\"http://www.movie.asanmusic.com/vip\" target=\"_blank\" rel=\"noreferrer\">آموزش کامل استفاده از اکانت</a>\r\n</div>\r\n</div>\r\n</div>', 'اکانت شما با موفقیت ایجاد شد', '</font></div></td></tr><tr bgcolor=\"#ffffff\"><td colspan=\"2\"><div class=\"msg\"><font color=\"#888888\"><div style=\"text-align:right;font-size:13px;font-weight:normal;line-height:30px;font-family:tahoma,sans-serif;width:100%;background-color:#f9f9f9;padding:100px;margin:0px\">\r\n<div style=\"margin:3px\">\r\n<div style=\"font-family:tahoma;font-size:16px;width:500px;margin:auto;direction:rtl;color:navy;background:#d6e0ff;color:#000;border-radius:5px;border:1px dashed #6699ff;padding-left:12px;padding-right:12px;padding-top:5px;padding-bottom:5px\" dir=\"rtl\">\r\nسامانه اشتراک کاربران ویژه\r\n</div></div>\r\n<div style=\"width:500px;margin:0 auto;border:1px solid;color:#000;border-radius:5px;border:1px dashed #339966;padding-left:12px;padding-right:12px;padding-top:10px;padding-bottom:10px;background-color:#dff2bf\" dir=\"rtl\">\r\n\r\nاطلاعات تیکت :\r\n<br>\r\nنام کاربری : {username}\r\n<br>\r\nکلمه عبور : {password}\r\n<br>\r\nایمیل : {email}\r\n<br>\r\nزمان : {time}\r\n<br>\r\nدسته : {category}\r\n<br>\r\nشناسه پرداخت : {resnum} - {refnum}\r\n\r\n</div>\r\n<div style=\"margin:3px\">\r\n<div style=\"text-align:center;font-family:tahoma;font-size:13px;width:500px;margin:auto;direction:rtl;color:navy;background:#d6e0ff;color:#000;border-radius:5px;border:1px dashed #6699ff;padding-left:12px;padding-right:12px;padding-top:5px;padding-bottom:5px\" dir=\"rtl\">\r\n<a href=\"http://www.movie.asanmusic.com\" target=\"_blank\" rel=\"noreferrer\">صفحه اصلی سایت</a>     <a href=\"http://vip.asanmusic.com/payment.php\" target=\"_blank\" rel=\"noreferrer\">سامانه پرداخت آنلاین</a>   <a href=\"http://www.movie.asanmusic.com/vip\" target=\"_blank\" rel=\"noreferrer\">آموزش کامل استفاده از اکانت</a>\r\n</div>\r\n</div>\r\n</div>', 'درخواست فراموشی کلمه عبور', '</font></div></td></tr><tr bgcolor=\"#ffffff\"><td colspan=\"2\"><div class=\"msg\"><font color=\"#888888\"><div style=\"text-align:right;font-size:13px;font-weight:normal;line-height:30px;font-family:tahoma,sans-serif;width:100%;background-color:#f9f9f9;padding:100px;margin:0px\">\r\n<div style=\"margin:3px\">\r\n<div style=\"font-family:tahoma;font-size:16px;width:500px;margin:auto;direction:rtl;color:navy;background:#d6e0ff;color:#000;border-radius:5px;border:1px dashed #6699ff;padding-left:12px;padding-right:12px;padding-top:5px;padding-bottom:5px\" dir=\"rtl\">\r\nسامانه اشتراک کاربران ویژه\r\n</div></div>\r\n<div style=\"width:500px;margin:0 auto;border:1px solid;color:#000;border-radius:5px;border:1px dashed #339966;padding-left:12px;padding-right:12px;padding-top:10px;padding-bottom:10px;background-color:#dff2bf\" dir=\"rtl\">\r\n\r\nدرخواست فراموشی پسورد :\r\n<br>\r\nزمان درخواست : {time}\r\n<br>\r\nلینک : <a href=\"{code}\">{code}</a>\r\n\r\n</div>\r\n<div style=\"margin:3px\">\r\n<div style=\"text-align:center;font-family:tahoma;font-size:13px;width:500px;margin:auto;direction:rtl;color:navy;background:#d6e0ff;color:#000;border-radius:5px;border:1px dashed #6699ff;padding-left:12px;padding-right:12px;padding-top:5px;padding-bottom:5px\" dir=\"rtl\">\r\n<a href=\"http://www.movie.asanmusic.com\" target=\"_blank\" rel=\"noreferrer\">صفحه اصلی سایت</a>     <a href=\"http://vip.asanmusic.com/payment.php\" target=\"_blank\" rel=\"noreferrer\">سامانه پرداخت آنلاین</a>   <a href=\"http://www.movie.asanmusic.com/vip\" target=\"_blank\" rel=\"noreferrer\">آموزش کامل استفاده از اکانت</a>\r\n</div>\r\n</div>\r\n</div>', 'هشدار اول پایان اکانت', '</font></div></td></tr><tr bgcolor=\"#ffffff\"><td colspan=\"2\"><div class=\"msg\"><font color=\"#888888\"><div style=\"text-align:right;font-size:13px;font-weight:normal;line-height:30px;font-family:tahoma,sans-serif;width:100%;background-color:#f9f9f9;padding:100px;margin:0px\">\r\n<div style=\"margin:3px\">\r\n<div style=\"font-family:tahoma;font-size:16px;width:500px;margin:auto;direction:rtl;color:navy;background:#d6e0ff;color:#000;border-radius:5px;border:1px dashed #6699ff;padding-left:12px;padding-right:12px;padding-top:5px;padding-bottom:5px\" dir=\"rtl\">\r\nسامانه اشتراک کاربران ویژه\r\n</div></div>\r\n<div style=\"width:500px;margin:0 auto;border:1px solid;color:#000;border-radius:5px;border:1px dashed #339966;padding-left:12px;padding-right:12px;padding-top:10px;padding-bottom:10px;background-color:#dff2bf\" dir=\"rtl\">\r\n\r\nکاربر گرامی {username}، اکانت شما در تاریخ {endtime} به پایان می رسد، لطفا نسبت به تمدید آن اقدام فرمایید\r\n\r\n</div>\r\n<div style=\"margin:3px\">\r\n<div style=\"text-align:center;font-family:tahoma;font-size:13px;width:500px;margin:auto;direction:rtl;color:navy;background:#d6e0ff;color:#000;border-radius:5px;border:1px dashed #6699ff;padding-left:12px;padding-right:12px;padding-top:5px;padding-bottom:5px\" dir=\"rtl\">\r\n<a href=\"http://www.movie.asanmusic.com\" target=\"_blank\" rel=\"noreferrer\">صفحه اصلی سایت</a>     <a href=\"http://vip.asanmusic.com/payment.php\" target=\"_blank\" rel=\"noreferrer\">سامانه پرداخت آنلاین</a>   <a href=\"http://www.movie.asanmusic.com/vip\" target=\"_blank\" rel=\"noreferrer\">آموزش کامل استفاده از اکانت</a>\r\n</div>\r\n</div>\r\n</div>', 14, 'هشدار دوم پایان اکانت', '</font></div></td></tr><tr bgcolor=\"#ffffff\"><td colspan=\"2\"><div class=\"msg\"><font color=\"#888888\"><div style=\"text-align:right;font-size:13px;font-weight:normal;line-height:30px;font-family:tahoma,sans-serif;width:100%;background-color:#f9f9f9;padding:100px;margin:0px\">\r\n<div style=\"margin:3px\">\r\n<div style=\"font-family:tahoma;font-size:16px;width:500px;margin:auto;direction:rtl;color:navy;background:#d6e0ff;color:#000;border-radius:5px;border:1px dashed #6699ff;padding-left:12px;padding-right:12px;padding-top:5px;padding-bottom:5px\" dir=\"rtl\">\r\nسامانه اشتراک کاربران ویژه\r\n</div></div>\r\n<div style=\"width:500px;margin:0 auto;border:1px solid;color:#000;border-radius:5px;border:1px dashed #339966;padding-left:12px;padding-right:12px;padding-top:10px;padding-bottom:10px;background-color:#dff2bf\" dir=\"rtl\">\r\n\r\nکاربر گرامی {username}، اکانت شما در تاریخ {endtime} به پایان می رسد، لطفا نسبت به تمدید آن اقدام فرمایید\r\n\r\n</div>\r\n<div style=\"margin:3px\">\r\n<div style=\"text-align:center;font-family:tahoma;font-size:13px;width:500px;margin:auto;direction:rtl;color:navy;background:#d6e0ff;color:#000;border-radius:5px;border:1px dashed #6699ff;padding-left:12px;padding-right:12px;padding-top:5px;padding-bottom:5px\" dir=\"rtl\">\r\n<a href=\"http://www.movie.asanmusic.com\" target=\"_blank\" rel=\"noreferrer\">صفحه اصلی سایت</a>     <a href=\"http://vip.asanmusic.com/payment.php\" target=\"_blank\" rel=\"noreferrer\">سامانه پرداخت آنلاین</a>   <a href=\"http://www.movie.asanmusic.com/vip\" target=\"_blank\" rel=\"noreferrer\">آموزش کامل استفاده از اکانت</a>\r\n</div>\r\n</div>\r\n</div>', 22, 'به سیستم بسیار پیشرفته خرید اشتراک ویژه دانلود خوش آمدید . با عضویت در این سیستم با 32 کانکشن فعال به طور همزمان دانلود کنید .\r\n<ul>\r\nبرای تماس با بخش پشتیبانی 24 ساعته سایت وارد پنل خود شوید و تیکت جدید ارسال نمایید.', 'به پنل کاربری خود خوش آمدید.\r\n\r\nهر سوال و مشکلی که داشتید در منوی سمت راست بخش تیکت جدید به ما اعلام نمایید تا شما را راهنمایی نماییم', '', 0, 1, 'info@test.com', 'admin', 'smtp.gmail.com', 465, 'ssl', 'Y/m/d', 1, '', 0, 12, 0);

-- --------------------------------------------------------

--
-- Table structure for table `single`
--

CREATE TABLE `single` (
  `id` int(11) NOT NULL,
  `file` text COLLATE utf8_persian_ci NOT NULL,
  `hash` varchar(50) COLLATE utf8_persian_ci NOT NULL,
  `time` int(11) NOT NULL,
  `userid` int(11) DEFAULT NULL,
  `server` int(11) NOT NULL,
  `paymentid` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_persian_ci;

--
-- Dumping data for table `single`
--

INSERT INTO `single` (`id`, `file`, `hash`, `time`, `userid`, `server`, `paymentid`) VALUES
(1, 'Premium app/Speech to text.zip', '35b3b595a81e823baf490e23de4f0989', 0, NULL, 1, 1),
(2, 'Premium app/Speech to text.zip', '35b3b595a81e823baf490e23de4f0989', 0, NULL, 1, 2),
(3, 'Premium app/Send Anonymous SMS.apk', 'ed512e6215a9572c64d529659d788380', 0, NULL, 1, 7),
(4, 'Serial/Latifeh/1/Latifeh-Episode-30.[AsanMusic].mkv', '8731df237500b9b59798c72f56221d93', 1434666217, 32, 1, 24),
(5, 'Serial/Latifeh/1/index.php', '19b42ae91ff4b37c3f26178360fb402b', 0, NULL, 1, 47),
(6, 'Serial/Latifeh/1/index.php', '19b42ae91ff4b37c3f26178360fb402b', 0, NULL, 1, 48),
(7, 'Serial/Latifeh/1/Latifeh-Episode-31.[AsanMusic].mkv', 'a4b8870bfa6800eef37eec2bf825e201', 0, NULL, 1, 51),
(8, 'Serial/Latifeh/1/Latifeh-Episode-31.[AsanMusic].mkv', 'a4b8870bfa6800eef37eec2bf825e201', 0, NULL, 1, 55),
(9, 'Serial/Latifeh/1/Latifeh-Episode-31.[AsanMusic].mkv', 'a4b8870bfa6800eef37eec2bf825e201', 0, NULL, 1, 56),
(10, 'Serial/Latifeh/1/Latifeh-Episode-31.[AsanMusic].mkv', 'a4b8870bfa6800eef37eec2bf825e201', 2147483647, 42, 1, 59),
(11, 'Serial/Latifeh/1/Latifeh-Episode-31.[AsanMusic].mkv', 'a4b8870bfa6800eef37eec2bf825e201', 0, NULL, 1, 60),
(12, 'Serial/Latifeh/1/Latifeh-Episode-31.720p [AsanMusic].mkv', 'c9410db9d4fd300eec3b3f64f4986b09', 2147483647, 44, 1, 61),
(13, 'Serial/Hannibal/1/Hannibal.S01E01.[AsanMusic].mkv', 'b03639895b87d0f7d9848859c7f3c141', 0, NULL, 1, 64),
(14, 'Serial/Latifeh/1/Latifeh-Episode-22.[AsanMusic].mkv', 'a3155452df6f1b9b20b180ce690c8002', 1434562512, 52, 1, 107),
(15, 'Serial/Latifeh/1/index.php', '19b42ae91ff4b37c3f26178360fb402b', 0, NULL, 1, 111),
(16, 'Serial/Latifeh/1/index.php', '19b42ae91ff4b37c3f26178360fb402b', 0, NULL, 1, 112),
(17, 'Serial/Latifeh/1/Latifeh-Episode-32.[AsanMusic].mkv', '2e10f345f430b4f50aecca9bc66d4a1c', 0, NULL, 1, 131),
(18, 'Serial/Latifeh/1/Latifeh-Episode-32.[AsanMusic].mkv', '2e10f345f430b4f50aecca9bc66d4a1c', 0, NULL, 1, 132),
(19, 'Serial/Latifeh/1/Latifeh-Episode-32.[AsanMusic].mkv', '2e10f345f430b4f50aecca9bc66d4a1c', 1434636023, 61, 1, 133),
(20, 'Serial/Latifeh/1/Latifeh-Episode-32.720p [AsanMusic].mkv', '476308b2c615afc603b6d5e343c433f1', 1434815062, 44, 1, 138),
(21, 'Serial/Latifeh/1/Latifeh-Episode-31.[AsanMusic].mkv', 'a4b8870bfa6800eef37eec2bf825e201', 1434823033, 65, 1, 146),
(22, 'Serial/Latifeh/1/Latifeh-Episode-27.[AsanMusic].mkv', '879b195a11d1f0dfb50c22e0e75bcdda', 0, NULL, 1, 167),
(23, 'Serial/Latifeh/1/Latifeh-Episode-33.[AsanMusic].mkv', '9aabe03153dd000c0171c83d4d0997a1', 1434913017, 76, 1, 177),
(24, 'Serial/Latifeh/1/Latifeh-Episode-36.720p [AsanMusic].mkv', '1ecc8e51f70ba3d32d8142ec2f0477b6', 1435235778, 105, 1, 253),
(25, 'Serial/Latifeh/1/Latifeh-Episode-36.[AsanMusic].mkv', 'f2aa45b195ce2284f3359e394a7f3b88', 0, NULL, 1, 254);

-- --------------------------------------------------------

--
-- Table structure for table `temp`
--

CREATE TABLE `temp` (
  `userid` int(11) NOT NULL,
  `hash` varchar(64) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `temp`
--

INSERT INTO `temp` (`userid`, `hash`) VALUES
(1059, '656417be3a940a9eeae55a463f8dced3992e7e0119ae9bbc73a2734b95bc46b5'),
(1059, '65ac52e287a2c0a6980f5cc35600fa0bc0a82ec2288d7f841f2411dc859a4f07'),
(1400, 'e91ec24cd4358ee7a9a781ae6e52a66551a54d9c296e7ad37f4f58183e129324'),
(1400, '4a84461cf112eee5a527cda5f579ed7104cf1b1539110fb99ce40ea5d1a5efe0'),
(1356, 'abe502acecdb0d58d594d276633fa767b3764103bf2cdaa82fed802dc4a44576'),
(1402, '688dec5becfff0a026e52f41320a175bc3c7a7b22bb70353fb7cb718cb9466f0'),
(1775, '09e60ebb3d6ba2cf1b5ef6510de10f8d2145bbe1f51b06450145d60bd0816dab'),
(2004, '081970c9afb1bf2bfd328bce0d6dd9cb5e242d3b9ace4b8cac4a0c3be0119cb7'),
(1929, '7d937f50b1b554ab062cb7d1cc7b366860f99537e8bb08779303c3377bf95da3'),
(1151, '45a83e99e9b9dc6b3b7b3339dbf93d1d62d14879b7d0362caad9da756a5f8928'),
(1259, '656336ff2952f04db639352c4d3f8dca8c99344f89aabea1fd95783084877425'),
(1259, 'f1a5dc90db0c44596d05bfa3815bed3c6ed662cf9491716d13715134548dc560'),
(2117, '85d06ca2ccc945e2aa8af73730a280a9467a75544dc6efc462404961b82f76fa'),
(1146, '35cff5688b67e51298ce53c1612c554a63de9072ccd3a928c14847c21a3d98b4'),
(2187, '5e9f71e6245225339c3d17b4cfb8f15a30c4760ec3308cb60583391caabefa49'),
(2575, '8c62a94885696b6f0b346e804859e98c673288cfef5e16141bb0bcef3bb11296'),
(2575, '8e34572ebcf633bcd6216355ef24f39e3f600e053bae40345fd58e928ba43754'),
(2575, 'a48bdea62ea5116d2d06a5cb91a8f6aacd3fad4e49fe5ff6b4fa5b704e81ee53'),
(2575, 'caaae07a3df355881634cc70fe10840b378fb80541230da95b295fda05cef136'),
(2118, 'c87584dda5a4d74f8802188c5e553a9c4ff4ba8f7579a68822b4b37a72ebb1aa'),
(2651, 'bcf5936af9ffd6c91375a23e9e4c914c5080ec4d0ad79d485037d6f93de68c4f'),
(1686, '3a79aa3e05ecf5eab3752352aa96d4d9c64273f3056077d6bb4590e173a0dff0'),
(2545, '6243906e11b524319fc281f3769b38cdddc4cce3d887761a6e82327281f7d56d'),
(2545, 'e8312750447a3581685140dee4e849e9f256c1431080a3f6f16d82ab3169e634'),
(2062, '76f9b29be12627b53a5d2b9b3a938f28fc43f0c5fe36111e7043af0d526c46c6'),
(2578, '834d22f737e043de11dce148039776116beef79665cbce71790ba0d823111da0'),
(2218, 'fe8724e06ff0a4eed80786e8114929d076318c883624fcaca05fbafe9a64bfdb'),
(2545, '630b9921eba7cd866d7d463dc9fd8b58eb93379f9f310c0532d8a7fe1d8ce27f'),
(2196, '108f2f0373d23dc3a67e66757217a7f51af663d01b094b76f129068f990f1f6e'),
(2723, '3a50124958720a4532da8bb5b3beb138ca06bfc1ce985520068e899454ebc237'),
(2723, 'effc7404f7bbfbee9fa7646054049b9bd5840555b2335828c6bcd9cda063f26f'),
(2769, '35ec427632a2b9727a8b07fd07af03063d2e716011eed9dd1e24757b0a16ef7a'),
(2721, 'c2f620dd625745cdc5f6adaf1943ff41d2df984f6d2e784b06e4d59ec8b1a841'),
(2218, '4547970c589555774786cef9747c6a87c0b865da2f48440ce23fc86b1c8a163f'),
(2828, '873c88d0862500e228c6e70a1ac0b3994703fe5a6d4f15d5fb9c44325425f82e'),
(2843, 'f86522d758213a59b8398452a3ae92cab3fcff52f514cee94d31060ca63751fd'),
(2545, '47a9c6d58d677f8a852d5ac9dc63d7e08b0b7a6d89893f8a214c6168537f8568'),
(2545, '7c1e687ef085cedaa5d70831354aaf6fb944b6124f15ff9a233be68824e80755'),
(109, 'd39a7076fd1276d486a9dda5bc2e78e64d21f558ad63bfbb9da921ac90e11231'),
(157, 'bbe7d6e7193fb7641c5e18e3f58f7016be6cea9b0a7207f23c20a7d2af65a533'),
(168, 'fbe68acf22d89cb7e9bd46a5587346bb971ec02c4b30051a8c30c63ba4ca8bce'),
(276, '5b32169064b098d9a6e79034339bce3cc016a4be6be12646724196ac3dc73832');

-- --------------------------------------------------------

--
-- Table structure for table `ticket`
--

CREATE TABLE `ticket` (
  `id` int(11) NOT NULL,
  `title` varchar(150) COLLATE utf8_persian_ci NOT NULL,
  `content` text COLLATE utf8_persian_ci NOT NULL,
  `time` int(11) NOT NULL,
  `userid` int(11) NOT NULL,
  `usertype` tinyint(4) NOT NULL,
  `status` tinyint(4) NOT NULL,
  `attach` tinyint(4) NOT NULL,
  `file_name` varchar(100) COLLATE utf8_persian_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_persian_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ticketdata`
--

CREATE TABLE `ticketdata` (
  `id` int(11) NOT NULL,
  `parentid` int(11) NOT NULL,
  `content` text COLLATE utf8_persian_ci NOT NULL,
  `time` int(11) NOT NULL,
  `userid` int(11) NOT NULL,
  `usertype` tinyint(4) NOT NULL,
  `attach` tinyint(4) NOT NULL,
  `file_name` varchar(100) COLLATE utf8_persian_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_persian_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(120) CHARACTER SET utf8 COLLATE utf8_persian_ci NOT NULL,
  `password` varchar(120) CHARACTER SET utf8 COLLATE utf8_persian_ci NOT NULL,
  `starttime` int(11) NOT NULL,
  `endtime` int(20) NOT NULL,
  `mobile` varchar(30) NOT NULL,
  `email` varchar(120) NOT NULL,
  `categoryid` int(11) NOT NULL,
  `active` tinyint(4) NOT NULL,
  `multi` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `users_ref`
--

CREATE TABLE `users_ref` (
  `id` int(11) NOT NULL,
  `userid` int(11) NOT NULL,
  `day` int(11) NOT NULL,
  `from` int(11) NOT NULL,
  `time` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_persian_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `blockip`
--
ALTER TABLE `blockip`
  ADD PRIMARY KEY (`ip`);

--
-- Indexes for table `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `dlinfo`
--
ALTER TABLE `dlinfo`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id` (`id`,`userid`,`ip`,`time`);

--
-- Indexes for table `dlinfo_guest`
--
ALTER TABLE `dlinfo_guest`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id` (`id`,`ip`,`time`);

--
-- Indexes for table `job`
--
ALTER TABLE `job`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `newslist`
--
ALTER TABLE `newslist`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `payment`
--
ALTER TABLE `payment`
  ADD PRIMARY KEY (`payment_id`);

--
-- Indexes for table `plugin`
--
ALTER TABLE `plugin`
  ADD PRIMARY KEY (`plugin_id`),
  ADD KEY `plugin_time` (`plugin_time`);

--
-- Indexes for table `plugindata`
--
ALTER TABLE `plugindata`
  ADD PRIMARY KEY (`plugindata_id`);

--
-- Indexes for table `servers`
--
ALTER TABLE `servers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `single`
--
ALTER TABLE `single`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ticket`
--
ALTER TABLE `ticket`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ticketdata`
--
ALTER TABLE `ticketdata`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users_ref`
--
ALTER TABLE `users_ref`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `category`
--
ALTER TABLE `category`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `dlinfo`
--
ALTER TABLE `dlinfo`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `dlinfo_guest`
--
ALTER TABLE `dlinfo_guest`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `job`
--
ALTER TABLE `job`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `newslist`
--
ALTER TABLE `newslist`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `payment`
--
ALTER TABLE `payment`
  MODIFY `payment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `plugin`
--
ALTER TABLE `plugin`
  MODIFY `plugin_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `plugindata`
--
ALTER TABLE `plugindata`
  MODIFY `plugindata_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `single`
--
ALTER TABLE `single`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `ticket`
--
ALTER TABLE `ticket`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ticketdata`
--
ALTER TABLE `ticketdata`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=359;

--
-- AUTO_INCREMENT for table `users_ref`
--
ALTER TABLE `users_ref`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
