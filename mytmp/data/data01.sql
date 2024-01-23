-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: 15 يونيو 2021 الساعة 15:26
-- إصدار الخادم: 10.4.11-MariaDB
-- PHP Version: 7.4.5

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `data01`
--

-- --------------------------------------------------------

--
-- بنية الجدول `accounts`
--

CREATE TABLE `accounts` (
  `aId` int(10) UNSIGNED NOT NULL,
  `aNm` varchar(99) CHARACTER SET utf32 NOT NULL,
  `aMax` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `aFrstBlnc` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `aFrstDt` date NOT NULL,
  `aGrp` int(10) UNSIGNED NOT NULL,
  `aWrk` tinyint(1) NOT NULL DEFAULT 1,
  `aNts` varchar(99) CHARACTER SET utf32 DEFAULT NULL,
  `aSub` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `aAdDt` datetime DEFAULT current_timestamp(),
  `aUsr` int(10) UNSIGNED NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Accounts';

--
-- إرجاع أو استيراد بيانات الجدول `accounts`
--

INSERT INTO `accounts` (`aId`, `aNm`, `aMax`, `aFrstBlnc`, `aFrstDt`, `aGrp`, `aWrk`, `aNts`, `aSub`, `aAdDt`, `aUsr`) VALUES
(1, 'شركة مصر للبترول', 0, 0, '2020-01-01', 106, 1, NULL, 1, '2020-04-08 22:47:22', 1),
(2, 'مورد نقدى', 0, 0, '2020-01-01', 106, 1, NULL, 1, '2020-04-08 22:47:22', 1),
(3, 'عميل نقدى', 0, 0, '2020-01-01', 107, 1, NULL, 1, '2020-04-08 22:47:22', 1),
(4, 'الخزينة رقم1 a', 10, 0, '2020-01-01', 108, 1, '', 1, '2020-05-16 18:45:23', 1),
(5, 'تنك سولار 1', 22500, 0, '2020-01-01', 109, 1, '', 1, '2020-05-16 18:46:14', 1),
(6, 'تنك 80 رقم 1', 22500, 0, '2020-01-01', 109, 1, '', 1, '2020-05-16 18:46:50', 1),
(7, 'تنك 92 رقم 1', 35000, 0, '2020-01-01', 109, 1, '', 1, '2020-05-16 18:47:04', 1),
(8, 'تنك 95 رقم 1', 15000, 0, '2020-01-01', 109, 1, '', 1, '2020-05-16 18:47:17', 1),
(9, 'مخزن زيت رقم1', 0, 0, '2020-01-01', 110, 1, '', 1, '2020-05-16 19:21:51', 1),
(10, 'الفاترينة', 0, 0, '2020-01-01', 110, 1, '', 1, '2020-12-17 22:27:44', 1),
(11, 'مخزن أصناف رقم 1', 0, 0, '2020-01-01', 111, 1, '', 1, '2020-05-16 19:22:14', 1),
(12, 'مخزن البونات', 0, 0, '0000-00-00', 19, 1, '', 1, '2021-03-27 18:33:19', 1),
(13, 'مخزن الوردية', 0, 0, '2020-01-01', 110, 1, '', 1, '2020-12-17 22:28:34', 1),
(22, 'ح رصيد أول المدة', 0, 0, '2020-01-01', 115, 1, '', 1, '2020-06-19 21:31:49', 1),
(29, 'فوزى عيادة', 100000, 0, '2020-01-01', 107, 1, '', 1, '2020-12-17 22:30:38', 1),
(30, 'محمد غزالة', 100000, 0, '2020-01-01', 107, 1, '', 1, '2020-12-17 22:30:58', 1),
(31, 'محمود سوبية', 100000, 0, '2020-01-01', 107, 1, '', 1, '2020-12-17 22:31:12', 1),
(32, 'محمود الشهاوى', 100000, 0, '2020-01-01', 107, 1, '', 1, '2020-12-17 22:31:37', 1),
(35, 'جمال الزغبى', 0, 0, '2020-01-01', 102, 1, '', 0, '2021-01-14 19:13:41', 1),
(36, 'محمد القشاوى', 0, 0, '2020-01-01', 103, 1, '', 0, '2021-01-14 19:13:58', 1),
(37, 'أنور', 0, 0, '2020-01-01', 104, 1, '', 1, '2021-01-14 19:14:09', 1),
(38, 'حسن الصعيدى', 0, 0, '2020-01-01', 105, 1, '', 1, '2021-01-14 19:14:25', 1),
(39, 'رزق بحيرى', 0, 0, '2020-01-01', 105, 1, '', 1, '2021-01-14 19:14:34', 1),
(40, 'السعدنى', 0, 0, '2020-01-01', 105, 1, '', 1, '2021-01-14 19:14:41', 1),
(42, 'خزينة كفر البطيخ', 0, 0, '2020-01-01', 108, 1, '', 3, '2021-01-18 13:56:37', 1),
(43, 'تنك كفر البطيخ', 0, 0, '2020-01-01', 109, 1, '', 3, '2021-01-18 14:05:48', 1),
(44, 'مخزن زيت كفر البطيخ', 0, 0, '2020-01-01', 110, 1, '', 3, '2021-01-18 14:10:46', 1),
(45, 'مخزن كفر البطيخ', 0, 0, '2020-01-01', 111, 1, '', 3, '2021-01-18 14:11:06', 1),
(46, 'شركة مصر للبترول ك ب', 0, 0, '2020-01-01', 106, 1, '', 3, '2021-01-18 14:11:37', 1),
(47, 'عميل نقدى ك ب', 0, 0, '2020-01-01', 107, 1, '', 3, '2021-01-18 14:11:56', 1),
(48, 'أرصده ك ب', 0, 0, '2020-01-01', 115, 1, '', 3, '2021-01-18 14:12:29', 1),
(49, 'محمود عبد العظيم', 0, 0, '2020-01-01', 104, 1, '', 3, '2021-01-18 14:52:28', 1),
(55, 'خزينة رقم 1 الكورنيش', 0, 0, '2020-01-01', 108, 1, '', 1, '2021-02-15 20:52:33', 1),
(66, 'مخزن البونات', 0, 0, '0000-00-00', 19, 1, NULL, 10, '2021-04-10 22:46:15', 1);

-- --------------------------------------------------------

--
-- بنية الجدول `billbody`
--

CREATE TABLE `billbody` (
  `bbId` int(10) UNSIGNED NOT NULL,
  `bbBllHdr` int(10) UNSIGNED NOT NULL,
  `bbStr` int(10) UNSIGNED NOT NULL,
  `bbGds` int(10) UNSIGNED NOT NULL,
  `bbQntty` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `bbPrc` decimal(7,4) UNSIGNED NOT NULL DEFAULT 0.0000,
  `bbDscnt` decimal(7,4) NOT NULL DEFAULT 0.0000,
  `bbNts` varchar(99) CHARACTER SET utf32 DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Goods Buy Bill Body';

--
-- إرجاع أو استيراد بيانات الجدول `billbody`
--

INSERT INTO `billbody` (`bbId`, `bbBllHdr`, `bbStr`, `bbGds`, `bbQntty`, `bbPrc`, `bbDscnt`, `bbNts`) VALUES
(10, 16, 13, 7, 1, '120.0000', '0.0000', ''),
(11, 16, 13, 10, 2, '205.0000', '0.0000', ''),
(12, 16, 13, 8, 3, '155.0000', '0.0000', ''),
(35, 21, 13, 7, 4, '120.0000', '0.0000', ''),
(36, 17, 9, 7, 4, '120.0000', '0.0000', ''),
(37, 21, 9, 7, 0, '120.0000', '0.0000', ''),
(38, 21, 12, 15, 2, '270.0000', '0.0000', ''),
(39, 23, 66, 7, 22, '120.0000', '0.0000', '');

-- --------------------------------------------------------

--
-- بنية الجدول `billheader`
--

CREATE TABLE `billheader` (
  `bhId` int(10) UNSIGNED NOT NULL,
  `bhNmbr` int(10) UNSIGNED NOT NULL,
  `bhDt` date NOT NULL,
  `bhNm` int(10) UNSIGNED NOT NULL,
  `bhDscnt` decimal(7,2) NOT NULL DEFAULT 0.00,
  `bhKnd` int(10) UNSIGNED NOT NULL,
  `bhNts` varchar(99) CHARACTER SET utf32 DEFAULT NULL,
  `bhSub` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `bhUsr` int(10) UNSIGNED NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Goods Bill Header';

--
-- إرجاع أو استيراد بيانات الجدول `billheader`
--

INSERT INTO `billheader` (`bhId`, `bhNmbr`, `bhDt`, `bhNm`, `bhDscnt`, `bhKnd`, `bhNts`, `bhSub`, `bhUsr`) VALUES
(16, 1, '2021-01-15', 38, '0.00', 8, '', 1, 0),
(17, 1, '2021-01-15', 3, '0.00', 3, '', 1, 0),
(18, 1, '2021-01-01', 22, '0.00', 9, '', 1, 0),
(19, 1, '2021-01-15', 1, '0.00', 1, '', 1, 0),
(21, 2, '2021-03-20', 35, '0.00', 8, '', 1, 0),
(22, 1, '2021-03-24', 3, '0.00', 10, '', 1, 0),
(23, 1, '2021-04-10', 35, '0.00', 8, '', 10, 0);

-- --------------------------------------------------------

--
-- بنية الجدول `goods`
--

CREATE TABLE `goods` (
  `gId` int(10) UNSIGNED NOT NULL,
  `gNm` varchar(99) CHARACTER SET utf32 NOT NULL,
  `gByLmt` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `gByPrc` decimal(7,2) UNSIGNED NOT NULL DEFAULT 0.00,
  `gSllPrc` decimal(7,2) UNSIGNED NOT NULL DEFAULT 0.00,
  `gGrp` int(10) UNSIGNED NOT NULL,
  `gWrk` tinyint(1) NOT NULL DEFAULT 1,
  `gNts` varchar(99) CHARACTER SET utf32 DEFAULT NULL,
  `gAdDt` datetime DEFAULT current_timestamp(),
  `gUsr` int(10) UNSIGNED NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Goods';

--
-- إرجاع أو استيراد بيانات الجدول `goods`
--

INSERT INTO `goods` (`gId`, `gNm`, `gByLmt`, `gByPrc`, `gSllPrc`, `gGrp`, `gWrk`, `gNts`, `gAdDt`, `gUsr`) VALUES
(1, 'سولار', 14000, '6.25', '6.75', 112, 1, '', '2020-05-04 18:11:35', 1),
(2, 'بنزين 80', 12000, '6.00', '6.25', 112, 1, '', '2020-05-04 19:02:08', 1),
(3, 'بنزين 92', 18000, '7.25', '7.50', 112, 1, '', '2020-05-05 20:55:51', 1),
(4, 'بنزين 95', 5000, '8.25', '8.50', 112, 1, '', '2020-06-19 17:50:02', 1),
(7, 'زيت 7500', 10, '100.00', '120.00', 113, 1, '', '2020-12-17 22:40:27', 1),
(8, 'زيت 10000', 10, '135.00', '155.00', 113, 1, '', '2020-12-17 22:41:00', 1),
(10, 'زيت مصر 1', 10, '190.00', '205.00', 113, 1, '', '2021-01-13 17:11:14', 1),
(11, 'مناديل', 10, '11.00', '15.00', 114, 1, '', '2021-01-13 17:18:43', 1),
(15, 'بون داخلية 40 لتر سولار', 0, '270.00', '270.00', 152, 1, '', '2021-03-27 18:16:07', 1);

-- --------------------------------------------------------

--
-- بنية الجدول `groups`
--

CREATE TABLE `groups` (
  `gId` int(10) UNSIGNED NOT NULL,
  `gNm` varchar(99) CHARACTER SET utf32 NOT NULL,
  `gGrpTyp` int(10) UNSIGNED NOT NULL,
  `gWrk` tinyint(1) NOT NULL DEFAULT 1,
  `gNts` varchar(99) CHARACTER SET utf32 DEFAULT NULL,
  `gAdDt` datetime DEFAULT current_timestamp(),
  `gUsr` int(10) UNSIGNED NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Groups';

--
-- إرجاع أو استيراد بيانات الجدول `groups`
--

INSERT INTO `groups` (`gId`, `gNm`, `gGrpTyp`, `gWrk`, `gNts`, `gAdDt`, `gUsr`) VALUES
(1, 'فئة الفروع', 0, 1, '', '2020-05-30 22:04:48', 1),
(2, 'فئة المبرمجين', 0, 1, '', '2020-05-30 22:05:56', 1),
(3, 'فئة المديرين', 0, 1, '', '2020-05-31 00:37:03', 1),
(4, 'فئة الموظفين', 0, 1, '', '2020-05-31 00:38:18', 1),
(5, 'فئة العمال', 0, 1, '', '2020-05-31 00:38:41', 1),
(6, 'فئة الموردين', 0, 1, '', '2020-05-31 00:39:57', 1),
(7, 'فئة العملاء', 0, 1, '', '2020-05-31 00:40:19', 1),
(8, 'فئة الخزائن', 0, 1, '', '2020-05-31 00:40:32', 1),
(9, 'فئة التانكات', 0, 1, '', '2020-05-31 00:40:51', 1),
(10, 'فئة مخازن الزيت', 0, 1, '', '2020-05-31 00:41:05', 1),
(11, 'فئة المخازن', 0, 1, '', '2020-05-31 00:41:23', 1),
(12, 'فئة مواد بترولية', 0, 1, '', '2020-05-31 00:41:41', 1),
(13, 'فئة الزيوت', 0, 1, '', '2020-05-31 00:41:56', 1),
(14, 'فئة الأصناف', 0, 1, '', '2020-05-31 00:42:10', 1),
(15, 'فئة رصيد أول المدة', 0, 1, '', '2020-05-31 00:42:27', 1),
(16, 'فئة العملات', 0, 1, '', '2020-05-31 00:42:38', 1),
(17, 'فئة متفرقة', 0, 1, '', '2020-05-31 00:42:50', 1),
(18, 'فئة البونات', 0, 1, NULL, '2021-01-19 14:28:36', 1),
(19, 'فئة مخازن البونات', 0, 1, '', '2021-04-10 21:50:30', 1),
(101, 'مجموعة فروع دمياط', 1, 1, '', '2020-12-18 17:08:59', 1),
(102, 'مجموعة المبرمجين', 2, 1, '', '2020-04-08 22:47:22', 1),
(103, 'مجموعة المديرين', 3, 1, '', '2020-04-08 22:47:22', 1),
(104, 'مجموعة الموظفين', 4, 1, '', '2020-04-08 22:47:22', 1),
(105, 'مجموعة العمال', 5, 1, '', '2020-04-08 22:47:22', 1),
(106, 'مجموعة الموردين', 6, 1, '', '2020-04-08 22:47:22', 1),
(107, 'مجموعة العملاء', 7, 1, '', '2020-04-08 22:47:22', 1),
(108, 'مجموعة الخزائن', 8, 1, '', '2020-04-08 22:47:22', 1),
(109, 'مجموعة التانكات', 9, 1, '', '2020-04-08 22:47:22', 1),
(110, 'مجموعة مخازن زيوت', 10, 1, '', '2020-04-08 22:47:22', 1),
(111, 'مجموعة المخازن', 11, 1, '', '2020-04-08 22:47:22', 1),
(112, 'مجموعة المواد البترولية', 12, 1, '', '2020-04-08 22:47:22', 1),
(113, 'مجموعة الزيوت', 13, 1, '', '2020-04-08 22:47:22', 1),
(114, 'مجموعة الأصناف', 14, 1, '', '2020-04-08 22:47:22', 1),
(115, 'مجموعة رصيد أول المدة', 15, 1, '', '2020-05-09 17:24:40', 1),
(116, 'مجموعة العملات', 16, 1, '', '2020-04-20 16:44:38', 1),
(117, 'مجموعة متفرقة', 17, 1, '', '2020-04-20 16:42:28', 1),
(118, 'مجموعة أنواع الفواتير', 17, 1, '', '2020-04-08 22:47:22', 1),
(119, 'مجموعة أنواع البونات', 17, 1, '', '2020-04-08 22:47:22', 1),
(120, 'مجموعة أنواع منتج البونات', 17, 1, '', '2020-04-08 22:47:22', 1),
(147, 'مجموعة فروع القاهرة', 1, 1, '', '2020-12-18 17:16:21', 1),
(148, 'مجموعة موظف تحت التدريب', 4, 1, '', '2020-12-18 18:14:40', 1),
(149, 'مجموعة العهدة النقدية', 8, 1, '', '2020-12-18 18:31:25', 1),
(152, 'بونات الدخلية 2020', 18, 1, '', '2021-01-19 14:33:42', 1),
(153, 'بونات الشركة 2020', 18, 1, '', '2021-01-19 14:33:56', 1),
(154, 'بونات الشركة 2019', 18, 1, '', '2021-01-19 14:34:09', 1),
(155, 'بونات الشركة 2018', 18, 1, '', '2021-01-19 14:34:24', 1);

-- --------------------------------------------------------

--
-- بنية الجدول `payments`
--

CREATE TABLE `payments` (
  `pId` int(10) UNSIGNED NOT NULL,
  `pDt` date NOT NULL,
  `pCstmr` int(10) UNSIGNED NOT NULL,
  `pVl` decimal(9,3) UNSIGNED NOT NULL,
  `pStr` int(10) UNSIGNED NOT NULL,
  `pNts` varchar(99) CHARACTER SET utf32 DEFAULT NULL,
  `pUsr` int(10) UNSIGNED NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Payment';

--
-- إرجاع أو استيراد بيانات الجدول `payments`
--

INSERT INTO `payments` (`pId`, `pDt`, `pCstmr`, `pVl`, `pStr`, `pNts`, `pUsr`) VALUES
(1, '2021-01-18', 29, '2222.000', 4, '', 0),
(2, '2021-01-18', 29, '1111.000', 4, '', 0),
(3, '2021-02-18', 30, '0.000', 55, '', 0);

-- --------------------------------------------------------

--
-- بنية الجدول `permission`
--

CREATE TABLE `permission` (
  `pId` int(10) UNSIGNED NOT NULL,
  `pUsr` int(10) UNSIGNED NOT NULL,
  `pOprtn` int(10) UNSIGNED NOT NULL,
  `pRd` tinyint(1) NOT NULL,
  `pAdd` tinyint(1) NOT NULL,
  `pEdt` tinyint(1) NOT NULL,
  `pDlt` tinyint(1) NOT NULL,
  `pPrnt` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf32;

-- --------------------------------------------------------

--
-- بنية الجدول `pricelist`
--

CREATE TABLE `pricelist` (
  `plId` int(10) UNSIGNED NOT NULL,
  `plDt` date NOT NULL,
  `plGds` int(10) UNSIGNED NOT NULL,
  `plByPrc` decimal(9,4) UNSIGNED NOT NULL DEFAULT 0.0000,
  `plSllPrc` decimal(9,4) UNSIGNED NOT NULL DEFAULT 0.0000,
  `plCmsn` decimal(4,4) UNSIGNED NOT NULL DEFAULT 0.0000,
  `plPkhr` decimal(4,4) UNSIGNED NOT NULL DEFAULT 0.0000,
  `plWrk` tinyint(1) NOT NULL DEFAULT 1,
  `plUsr` int(10) UNSIGNED NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Goods Price';

--
-- إرجاع أو استيراد بيانات الجدول `pricelist`
--

INSERT INTO `pricelist` (`plId`, `plDt`, `plGds`, `plByPrc`, `plSllPrc`, `plCmsn`, `plPkhr`, `plWrk`, `plUsr`) VALUES
(9, '2020-01-01', 1, '6.6650', '6.7500', '0.0850', '0.0000', 1, 0),
(10, '2020-01-01', 2, '6.1805', '6.2500', '0.0695', '0.0050', 1, 0),
(11, '2020-01-01', 3, '7.3925', '7.5000', '0.1075', '0.0050', 1, 0),
(12, '2020-01-01', 4, '8.3550', '8.5000', '0.1450', '0.0050', 1, 0),
(13, '2021-01-01', 3, '7.3875', '7.5000', '0.1125', '0.0050', 1, 0),
(14, '2021-01-01', 4, '8.3500', '8.5000', '0.1500', '0.0050', 1, 0),
(15, '2021-01-01', 7, '96.0000', '120.0000', '0.0000', '0.0000', 1, 0),
(16, '2021-01-01', 8, '135.0000', '155.0000', '0.0000', '0.0000', 1, 0);

-- --------------------------------------------------------

--
-- بنية الجدول `pumpout`
--

CREATE TABLE `pumpout` (
  `poId` int(10) UNSIGNED NOT NULL,
  `poDly` int(10) UNSIGNED NOT NULL,
  `poPmp` int(10) UNSIGNED NOT NULL,
  `poNmbr` int(10) UNSIGNED NOT NULL,
  `poPrc` decimal(7,2) UNSIGNED NOT NULL DEFAULT 0.00,
  `poQnttyBck` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `poTnk` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `poNts` varchar(99) CHARACTER SET utf32 DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Pump Out';

--
-- إرجاع أو استيراد بيانات الجدول `pumpout`
--

INSERT INTO `pumpout` (`poId`, `poDly`, `poPmp`, `poNmbr`, `poPrc`, `poQnttyBck`, `poTnk`, `poNts`) VALUES
(121, 16, 21, 1000000, '0.00', 0, 5, NULL),
(122, 16, 22, 2000000, '0.00', 0, 5, NULL),
(123, 16, 23, 3000000, '0.00', 0, 7, NULL),
(124, 16, 24, 4000000, '0.00', 0, 6, ''),
(125, 16, 25, 5000000, '0.00', 0, 7, NULL),
(126, 16, 26, 6000000, '0.00', 0, 6, NULL),
(127, 16, 27, 7000000, '0.00', 0, 8, NULL),
(128, 16, 28, 8000000, '0.00', 0, 7, NULL),
(137, 21, 21, 1000010, '0.00', 0, 5, ''),
(138, 21, 22, 2000000, '0.00', 0, 5, NULL),
(139, 21, 23, 3000000, '0.00', 0, 7, NULL),
(140, 21, 24, 4000000, '0.00', 0, 6, NULL),
(141, 21, 25, 5000000, '0.00', 0, 7, NULL),
(142, 21, 26, 6000000, '0.00', 0, 6, NULL),
(143, 21, 27, 7000000, '0.00', 0, 8, NULL),
(144, 21, 28, 8000000, '0.00', 0, 7, NULL);

-- --------------------------------------------------------

--
-- بنية الجدول `pumps`
--

CREATE TABLE `pumps` (
  `pId` int(10) UNSIGNED NOT NULL,
  `pNm` varchar(99) CHARACTER SET utf32 NOT NULL,
  `pFrstNmbr` int(10) UNSIGNED NOT NULL,
  `pFrstDt` date NOT NULL,
  `pTnk` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `pGds` int(10) UNSIGNED NOT NULL DEFAULT 1,
  `pWrk` tinyint(1) NOT NULL DEFAULT 1,
  `pNts` varchar(99) CHARACTER SET utf32 DEFAULT NULL,
  `pSub` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `pAdDt` datetime DEFAULT current_timestamp(),
  `pUsr` int(10) UNSIGNED NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Pumps';

--
-- إرجاع أو استيراد بيانات الجدول `pumps`
--

INSERT INTO `pumps` (`pId`, `pNm`, `pFrstNmbr`, `pFrstDt`, `pTnk`, `pGds`, `pWrk`, `pNts`, `pSub`, `pAdDt`, `pUsr`) VALUES
(21, 'ط1 م1 ع1', 1000000, '2021-01-01', 5, 1, 1, '', 1, '2021-01-15 13:26:02', 1),
(22, 'ط1 م2 ع1', 2000000, '2021-01-01', 5, 1, 1, '', 1, '2021-01-15 13:28:18', 1),
(23, 'ط2 م1 ع1', 3000000, '2021-01-01', 7, 3, 1, '', 1, '2021-01-15 13:28:44', 1),
(24, 'ط2 م2 ع1', 4000000, '2021-01-01', 6, 2, 1, '', 1, '2021-01-15 13:30:07', 1),
(25, 'ط3 م1 ع1', 5000000, '2021-01-01', 7, 3, 1, '', 1, '2021-01-15 13:30:31', 1),
(26, 'ط3 م2 ع1', 6000000, '2021-01-01', 6, 2, 1, '', 1, '2021-01-15 13:30:51', 1),
(27, 'ط4 م1 ع1', 7000000, '2021-01-01', 8, 4, 1, '', 1, '2021-01-15 13:31:18', 1),
(28, 'ط4 م2 ع1', 8000000, '2021-01-01', 7, 3, 1, '', 1, '2021-01-15 13:31:41', 1),
(29, 'ط كفر البطيخ', 100, '2021-01-18', 43, 2, 1, '', 3, '2021-01-18 14:13:17', 1);

-- --------------------------------------------------------

--
-- بنية الجدول `subsidiary`
--

CREATE TABLE `subsidiary` (
  `ssId` int(10) UNSIGNED NOT NULL,
  `ssNm` varchar(99) CHARACTER SET utf32 NOT NULL,
  `ssGrp` int(10) UNSIGNED DEFAULT NULL,
  `ssWrk` tinyint(1) NOT NULL DEFAULT 1,
  `ssNts` varchar(99) CHARACTER SET utf32 DEFAULT NULL,
  `ssAdDt` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- إرجاع أو استيراد بيانات الجدول `subsidiary`
--

INSERT INTO `subsidiary` (`ssId`, `ssNm`, `ssGrp`, `ssWrk`, `ssNts`, `ssAdDt`) VALUES
(1, 'فرع الكورنيش', 101, 1, '', '2020-06-12 01:11:46'),
(3, 'فرع كفر البطيخ', 101, 1, '', '2020-06-12 01:26:33'),
(10, 'فرع العزبة', 101, 1, '', '2021-04-10 22:46:14');

-- --------------------------------------------------------

--
-- بنية الجدول `types`
--

CREATE TABLE `types` (
  `tId` int(10) UNSIGNED NOT NULL,
  `tNm` varchar(99) CHARACTER SET utf32 NOT NULL,
  `tGrp` int(10) UNSIGNED NOT NULL,
  `tWrk` tinyint(1) NOT NULL DEFAULT 1,
  `tNts` varchar(99) CHARACTER SET utf32 DEFAULT NULL,
  `tAdDt` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Types';

--
-- إرجاع أو استيراد بيانات الجدول `types`
--

INSERT INTO `types` (`tId`, `tNm`, `tGrp`, `tWrk`, `tNts`, `tAdDt`) VALUES
(1, 'شراء', 118, 1, NULL, '2020-04-08 22:47:22'),
(2, 'م شراء', 118, 1, NULL, '2020-04-08 22:47:22'),
(3, 'بيع', 118, 1, NULL, '2020-04-08 22:47:22'),
(4, 'م بيع', 118, 1, NULL, '2020-04-08 22:47:22'),
(5, 'أذن صرف', 118, 1, NULL, '2020-04-08 22:47:22'),
(6, 'أذن إضافة', 118, 1, NULL, '2020-04-08 22:47:22'),
(7, 'نقل مخازن', 118, 1, NULL, '2020-04-08 22:47:22'),
(8, 'وردية', 118, 1, NULL, '2020-04-08 22:47:22'),
(9, 'رصيد أول المدة', 118, 1, NULL, '2020-04-08 22:47:22'),
(10, 'بون سولار', 16, 1, NULL, '2020-04-08 22:47:22'),
(11, 'بون 80', 16, 1, NULL, '2020-04-08 22:47:22'),
(12, 'بون 92', 16, 1, NULL, '2020-04-08 22:47:22'),
(13, 'بون 95', 16, 1, NULL, '2020-04-08 22:47:22'),
(14, 'بون غسيل', 16, 1, NULL, '2020-04-08 22:47:22'),
(15, 'بون زيت 3 لتر', 16, 1, NULL, '2020-04-08 22:47:22'),
(16, 'بون زيت 4 لتر', 16, 1, NULL, '2020-04-08 22:47:22'),
(17, 'بون زيت 5 لتر', 16, 1, NULL, '2020-04-08 22:47:22'),
(18, 'بون زيت 6 لتر', 16, 1, NULL, '2020-04-08 22:47:22');

-- --------------------------------------------------------

--
-- بنية الجدول `userlog`
--

CREATE TABLE `userlog` (
  `ulId` int(10) UNSIGNED NOT NULL,
  `ulUsrId` int(10) UNSIGNED NOT NULL,
  `ulUsr` varchar(99) CHARACTER SET utf32 NOT NULL,
  `ulPsswrd` varchar(255) CHARACTER SET utf32 NOT NULL,
  `ulEml` varchar(255) CHARACTER SET utf32 NOT NULL,
  `ulMxLg` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `ulAdDt` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='UserLog';

--
-- إرجاع أو استيراد بيانات الجدول `userlog`
--

INSERT INTO `userlog` (`ulId`, `ulUsrId`, `ulUsr`, `ulPsswrd`, `ulEml`, `ulMxLg`, `ulAdDt`) VALUES
(26, 35, 'sm', '40bd001563085fc35165329ea1ff5c5ecbdbbeef', '111@222.com', 0, '2021-02-17 22:44:22'),
(27, 36, 'moh', '40bd001563085fc35165329ea1ff5c5ecbdbbeef', '112@222.com', 0, '2021-02-17 22:44:22'),
(28, 37, 'anwr', '40bd001563085fc35165329ea1ff5c5ecbdbbeef', '113@222.com', 0, '2021-02-17 22:44:22');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `accounts`
--
ALTER TABLE `accounts`
  ADD PRIMARY KEY (`aId`);

--
-- Indexes for table `billbody`
--
ALTER TABLE `billbody`
  ADD PRIMARY KEY (`bbId`),
  ADD KEY `c041` (`bbBllHdr`);

--
-- Indexes for table `billheader`
--
ALTER TABLE `billheader`
  ADD PRIMARY KEY (`bhId`);

--
-- Indexes for table `goods`
--
ALTER TABLE `goods`
  ADD PRIMARY KEY (`gId`),
  ADD UNIQUE KEY `gNm` (`gNm`);

--
-- Indexes for table `groups`
--
ALTER TABLE `groups`
  ADD PRIMARY KEY (`gId`),
  ADD UNIQUE KEY `gNm` (`gNm`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`pId`);

--
-- Indexes for table `permission`
--
ALTER TABLE `permission`
  ADD PRIMARY KEY (`pId`);

--
-- Indexes for table `pricelist`
--
ALTER TABLE `pricelist`
  ADD PRIMARY KEY (`plId`),
  ADD KEY `c031` (`plGds`);

--
-- Indexes for table `pumpout`
--
ALTER TABLE `pumpout`
  ADD PRIMARY KEY (`poId`),
  ADD KEY `C011` (`poDly`);

--
-- Indexes for table `pumps`
--
ALTER TABLE `pumps`
  ADD PRIMARY KEY (`pId`),
  ADD UNIQUE KEY `pNm` (`pNm`);

--
-- Indexes for table `subsidiary`
--
ALTER TABLE `subsidiary`
  ADD PRIMARY KEY (`ssId`),
  ADD UNIQUE KEY `ssNm` (`ssNm`);

--
-- Indexes for table `types`
--
ALTER TABLE `types`
  ADD PRIMARY KEY (`tId`),
  ADD UNIQUE KEY `tNm` (`tNm`);

--
-- Indexes for table `userlog`
--
ALTER TABLE `userlog`
  ADD PRIMARY KEY (`ulId`),
  ADD UNIQUE KEY `ulUsr` (`ulUsr`),
  ADD UNIQUE KEY `ulEml` (`ulEml`),
  ADD UNIQUE KEY `ulUsrId` (`ulUsrId`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `accounts`
--
ALTER TABLE `accounts`
  MODIFY `aId` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=67;

--
-- AUTO_INCREMENT for table `billbody`
--
ALTER TABLE `billbody`
  MODIFY `bbId` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT for table `billheader`
--
ALTER TABLE `billheader`
  MODIFY `bhId` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `goods`
--
ALTER TABLE `goods`
  MODIFY `gId` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `groups`
--
ALTER TABLE `groups`
  MODIFY `gId` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=162;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `pId` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `permission`
--
ALTER TABLE `permission`
  MODIFY `pId` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pricelist`
--
ALTER TABLE `pricelist`
  MODIFY `plId` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `pumpout`
--
ALTER TABLE `pumpout`
  MODIFY `poId` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=145;

--
-- AUTO_INCREMENT for table `pumps`
--
ALTER TABLE `pumps`
  MODIFY `pId` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `subsidiary`
--
ALTER TABLE `subsidiary`
  MODIFY `ssId` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `types`
--
ALTER TABLE `types`
  MODIFY `tId` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `userlog`
--
ALTER TABLE `userlog`
  MODIFY `ulId` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- قيود الجداول المحفوظة
--

--
-- القيود للجدول `billbody`
--
ALTER TABLE `billbody`
  ADD CONSTRAINT `c041` FOREIGN KEY (`bbBllHdr`) REFERENCES `billheader` (`bhId`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- القيود للجدول `pricelist`
--
ALTER TABLE `pricelist`
  ADD CONSTRAINT `c031` FOREIGN KEY (`plGds`) REFERENCES `goods` (`gId`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- القيود للجدول `pumpout`
--
ALTER TABLE `pumpout`
  ADD CONSTRAINT `C011` FOREIGN KEY (`poDly`) REFERENCES `billheader` (`bhId`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- القيود للجدول `userlog`
--
ALTER TABLE `userlog`
  ADD CONSTRAINT `C021` FOREIGN KEY (`ulUsrId`) REFERENCES `accounts` (`aId`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
