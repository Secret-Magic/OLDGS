-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 31, 2020 at 08:01 AM
-- Server version: 10.4.11-MariaDB
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
-- Table structure for table `accounts`
--

CREATE TABLE `accounts` (
  `aId` int(10) UNSIGNED NOT NULL,
  `aNm` varchar(99) CHARACTER SET utf32 NOT NULL,
  `aMax` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `aUsr` int(10) UNSIGNED NOT NULL DEFAULT 1,
  `aFrstBlnc` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `aFrstDt` date NOT NULL DEFAULT '2020-01-01',
  `aGrp` int(10) UNSIGNED NOT NULL,
  `aAdDt` datetime DEFAULT current_timestamp(),
  `aWrk` tinyint(1) NOT NULL DEFAULT 1,
  `aNts` varchar(99) CHARACTER SET utf32 DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Custumers';

--
-- Dumping data for table `accounts`
--

INSERT INTO `accounts` (`aId`, `aNm`, `aMax`, `aUsr`, `aFrstBlnc`, `aFrstDt`, `aGrp`, `aAdDt`, `aWrk`, `aNts`) VALUES
(1, 'شركة مصر للبترول', 0, 1, 0, '2020-01-01', 106, '2020-04-08 22:47:22', 1, NULL),
(2, 'مورد نقدى', 0, 1, 0, '2020-01-01', 106, '2020-04-08 22:47:22', 1, NULL),
(3, 'عميل نقدى', 0, 1, 0, '2020-01-01', 107, '2020-04-08 22:47:22', 1, NULL),
(4, 'الخزينة رقم1', 0, 1, 0, '2020-01-01', 108, '2020-05-16 18:45:23', 1, ''),
(5, 'تنك سولار رقم1', 0, 1, 0, '2020-01-01', 109, '2020-05-16 18:46:14', 1, ''),
(6, 'تنك بنزين 80 رقم 1', 0, 1, 0, '2020-01-01', 109, '2020-05-16 18:46:50', 1, ''),
(7, 'تنك بنزين 92 رقم 1', 0, 1, 0, '2020-01-01', 109, '2020-05-16 18:47:04', 1, ''),
(8, 'تنك بنزين 95 رقم 1', 0, 1, 0, '2020-01-01', 109, '2020-05-16 18:47:17', 1, ''),
(9, 'مخزن زيت رقم1', 0, 1, 0, '2020-01-01', 110, '2020-05-16 19:21:51', 1, ''),
(11, 'مخزن أصناف رقم 1', 0, 1, 0, '2020-01-01', 111, '2020-05-16 19:22:14', 1, '');

-- --------------------------------------------------------

--
-- Table structure for table `billbody`
--

CREATE TABLE `billbody` (
  `bbId` int(10) UNSIGNED NOT NULL,
  `bbBllHdr` int(10) UNSIGNED NOT NULL,
  `bbGds` int(10) UNSIGNED NOT NULL,
  `bbPrc` decimal(7,2) UNSIGNED NOT NULL,
  `bbQntty` int(10) UNSIGNED NOT NULL,
  `bbDscnt` decimal(7,2) NOT NULL DEFAULT 0.00,
  `bbStr` int(10) UNSIGNED NOT NULL,
  `bbNts` varchar(99) CHARACTER SET utf32 DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Goods Buy Bill Body';

-- --------------------------------------------------------

--
-- Table structure for table `billheader`
--

CREATE TABLE `billheader` (
  `bhId` int(10) UNSIGNED NOT NULL,
  `bhDt` date NOT NULL,
  `bhNmbr` int(10) UNSIGNED NOT NULL,
  `bhCstmr` int(10) UNSIGNED NOT NULL,
  `bhBllKnd` int(10) UNSIGNED NOT NULL,
  `bhDscnt` decimal(7,2) NOT NULL DEFAULT 0.00,
  `bhUsr` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `bhNts` varchar(99) CHARACTER SET utf32 DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Goods Bill Header';

--
-- Dumping data for table `billheader`
--

INSERT INTO `billheader` (`bhId`, `bhDt`, `bhNmbr`, `bhCstmr`, `bhBllKnd`, `bhDscnt`, `bhUsr`, `bhNts`) VALUES
(1, '2020-05-02', 100, 1, 1, '0.00', 0, ''),
(2, '2020-05-02', 1, 2, 2, '0.00', 0, '');

-- --------------------------------------------------------

--
-- Table structure for table `couponinout`
--

CREATE TABLE `couponinout` (
  `cioId` int(10) UNSIGNED NOT NULL,
  `cioCpnId` int(10) UNSIGNED DEFAULT NULL,
  `cioVl` int(10) UNSIGNED NOT NULL,
  `cioSrcId` int(10) UNSIGNED DEFAULT NULL,
  `cioUsr` int(10) UNSIGNED DEFAULT NULL,
  `cioDt` date NOT NULL,
  `ciInOut` tinyint(1) NOT NULL DEFAULT 1,
  `ciNts` varchar(99) CHARACTER SET utf32 DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Coupon In Out';

-- --------------------------------------------------------

--
-- Table structure for table `coupons`
--

CREATE TABLE `coupons` (
  `cId` int(10) UNSIGNED NOT NULL,
  `cDt` date NOT NULL,
  `cCmKnd` int(10) UNSIGNED DEFAULT NULL,
  `cGsKnd` int(10) UNSIGNED DEFAULT NULL,
  `cVl` int(10) UNSIGNED NOT NULL,
  `cGsprc` int(10) UNSIGNED DEFAULT NULL,
  `cUsr` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `cWrk` tinyint(1) NOT NULL DEFAULT 1,
  `cNts` varchar(99) CHARACTER SET utf32 DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Coupons';

-- --------------------------------------------------------

--
-- Table structure for table `custumerpayment`
--

CREATE TABLE `custumerpayment` (
  `cpId` int(10) UNSIGNED NOT NULL,
  `cpDt` date NOT NULL,
  `cpCstmr` int(10) UNSIGNED NOT NULL,
  `cpVl` decimal(9,3) UNSIGNED NOT NULL,
  `cpStore` int(10) UNSIGNED NOT NULL,
  `cpUsr` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `cpNts` varchar(99) CHARACTER SET utf32 DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Custumer Payment';

-- --------------------------------------------------------

--
-- Table structure for table `dailyheader`
--

CREATE TABLE `dailyheader` (
  `dhId` int(10) UNSIGNED NOT NULL,
  `dhNmbr` int(10) UNSIGNED NOT NULL,
  `dhDt` date NOT NULL,
  `dhNm` varchar(99) CHARACTER SET utf32 NOT NULL,
  `dhUsr` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `dhNts` varchar(99) CHARACTER SET utf32 DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Daily Header';

--
-- Dumping data for table `dailyheader`
--

INSERT INTO `dailyheader` (`dhId`, `dhNmbr`, `dhDt`, `dhNm`, `dhUsr`, `dhNts`) VALUES
(1, 1, '2020-05-01', 'البداية', 0, ''),
(2, 2, '2020-05-02', '2حسن', 0, ''),
(3, 3, '0000-00-00', 'السعدنى', 0, ''),
(4, 4, '0000-00-00', 'رزق', 0, '');

-- --------------------------------------------------------

--
-- Table structure for table `evaporationrate`
--

CREATE TABLE `evaporationrate` (
  `erId` int(10) UNSIGNED NOT NULL,
  `erDt` date NOT NULL,
  `erVl` decimal(7,4) UNSIGNED NOT NULL,
  `erGsTyp` int(10) UNSIGNED NOT NULL,
  `erUsr` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `erWrk` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Evaporation rate';

-- --------------------------------------------------------

--
-- Table structure for table `goods`
--

CREATE TABLE `goods` (
  `gId` int(10) UNSIGNED NOT NULL,
  `gNm` varchar(99) CHARACTER SET utf32 NOT NULL,
  `gByPrc` decimal(7,2) UNSIGNED NOT NULL DEFAULT 0.00,
  `gSllPrc` decimal(7,2) UNSIGNED NOT NULL DEFAULT 0.00,
  `gGrp` int(10) UNSIGNED NOT NULL,
  `gFrstBlnc` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `gFrstDt` date NOT NULL DEFAULT '2020-01-01',
  `gUsr` int(10) UNSIGNED NOT NULL DEFAULT 1,
  `gStr` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `gAdDt` datetime DEFAULT current_timestamp(),
  `gWrk` tinyint(1) NOT NULL DEFAULT 1,
  `gNts` varchar(99) CHARACTER SET utf32 DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Goods';

--
-- Dumping data for table `goods`
--

INSERT INTO `goods` (`gId`, `gNm`, `gByPrc`, `gSllPrc`, `gGrp`, `gFrstBlnc`, `gFrstDt`, `gUsr`, `gStr`, `gAdDt`, `gWrk`, `gNts`) VALUES
(1, 'سولار', '0.00', '0.00', 112, 0, '2020-05-01', 1, 5, '2020-05-04 18:11:35', 1, ''),
(2, 'بنزين 95', '8.25', '8.50', 112, 199, '2020-05-02', 1, 8, '2020-05-04 19:02:08', 1, ''),
(3, 'بنزين 80', '6.00', '6.25', 112, 1000, '2020-05-02', 1, 6, '2020-05-05 20:55:51', 1, '');

-- --------------------------------------------------------

--
-- Table structure for table `goodsprice`
--

CREATE TABLE `goodsprice` (
  `gpId` int(10) UNSIGNED NOT NULL,
  `gpDt` date NOT NULL,
  `gpGoods` int(10) UNSIGNED NOT NULL,
  `gpSllPrc` decimal(9,3) UNSIGNED NOT NULL,
  `gpCmsn` decimal(9,3) UNSIGNED NOT NULL,
  `gpUsr` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `gpWrk` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Goods Price';

-- --------------------------------------------------------

--
-- Table structure for table `groups`
--

CREATE TABLE `groups` (
  `gId` int(10) UNSIGNED NOT NULL,
  `gNm` varchar(99) CHARACTER SET utf32 NOT NULL,
  `gGrpTyp` int(10) UNSIGNED NOT NULL,
  `gUsr` int(10) UNSIGNED NOT NULL DEFAULT 1,
  `gAdDt` datetime DEFAULT current_timestamp(),
  `gWrk` tinyint(1) NOT NULL DEFAULT 1,
  `gNts` varchar(99) CHARACTER SET utf32 DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Groups';

--
-- Dumping data for table `groups`
--

INSERT INTO `groups` (`gId`, `gNm`, `gGrpTyp`, `gUsr`, `gAdDt`, `gWrk`, `gNts`) VALUES
(1, 'فئة الفروع', 0, 1, '2020-05-30 22:04:48', 1, ''),
(2, 'فئة المبرمجين', 0, 1, '2020-05-30 22:05:56', 1, ''),
(3, 'فئة المديرين', 0, 1, '2020-05-31 00:37:03', 1, ''),
(4, 'فئة المستخدمين', 0, 1, '2020-05-31 00:38:18', 1, ''),
(5, 'فئة الموظفين', 0, 1, '2020-05-31 00:38:41', 1, ''),
(6, 'فئة الموردين', 0, 1, '2020-05-31 00:39:57', 1, ''),
(7, 'فئة العملاء', 0, 1, '2020-05-31 00:40:19', 1, ''),
(8, 'فئة الخزائن', 0, 1, '2020-05-31 00:40:32', 1, ''),
(9, 'فئة التانكات', 0, 1, '2020-05-31 00:40:51', 1, ''),
(10, 'فئة مخازن الزيت', 0, 1, '2020-05-31 00:41:05', 1, ''),
(11, 'فئة المخازن', 0, 1, '2020-05-31 00:41:23', 1, ''),
(12, 'فئة مواد بترولية', 0, 1, '2020-05-31 00:41:41', 1, ''),
(13, 'فئة الزيوت', 0, 1, '2020-05-31 00:41:56', 1, ''),
(14, 'فئة الأصناف', 0, 1, '2020-05-31 00:42:10', 1, ''),
(15, 'فئة رصيد أول المدة', 0, 1, '2020-05-31 00:42:27', 1, ''),
(16, 'فئة العملات', 0, 1, '2020-05-31 00:42:38', 1, ''),
(17, 'فئة متفرقة', 0, 1, '2020-05-31 00:42:50', 1, ''),
(101, 'مجموعة الفروع', 1, 1, '2020-05-09 17:25:33', 1, ''),
(102, 'مجموعة المبرمجين', 2, 1, '2020-04-08 22:47:22', 1, ''),
(103, 'مجموعة المديرين', 3, 1, '2020-04-08 22:47:22', 1, ''),
(104, 'مجموعة المستخدمين', 4, 1, '2020-04-08 22:47:22', 1, ''),
(105, 'مجموعة الموظفين', 5, 1, '2020-04-08 22:47:22', 1, ''),
(106, 'مجموعة الموردين', 6, 1, '2020-04-08 22:47:22', 1, ''),
(107, 'مجموعة العملاء', 7, 1, '2020-04-08 22:47:22', 1, ''),
(108, 'مجموعة الخزائن', 8, 1, '2020-04-08 22:47:22', 1, ''),
(109, 'مجموعة التانكات', 9, 1, '2020-04-08 22:47:22', 1, ''),
(110, 'مجموعة مخازن زيوت', 10, 1, '2020-04-08 22:47:22', 1, ''),
(111, 'مجموعة المخازن', 11, 1, '2020-04-08 22:47:22', 1, ''),
(112, 'مجموعة المواد البترولية', 12, 1, '2020-04-08 22:47:22', 1, ''),
(113, 'مجموعة الزيوت', 13, 1, '2020-04-08 22:47:22', 1, ''),
(114, 'مجموعة الأصناف', 14, 1, '2020-04-08 22:47:22', 1, ''),
(115, 'مجموعة رصيد أول المدة', 15, 1, '2020-05-09 17:24:40', 1, ''),
(116, 'مجموعة العملات', 16, 1, '2020-04-20 16:44:38', 1, ''),
(117, 'مجموعة متفرقة', 17, 1, '2020-04-20 16:42:28', 1, ''),
(118, 'مجموعة أنواع الفواتير', 17, 1, '2020-04-08 22:47:22', 1, ''),
(119, 'مجموعة أنواع البونات', 17, 1, '2020-04-08 22:47:22', 1, ''),
(120, 'مجموعة أنواع منتج البونات', 17, 1, '2020-04-08 22:47:22', 1, '');

-- --------------------------------------------------------

--
-- Table structure for table `pumpout`
--

CREATE TABLE `pumpout` (
  `poId` int(10) UNSIGNED NOT NULL,
  `poDly` int(10) UNSIGNED NOT NULL,
  `poPmp` int(10) UNSIGNED NOT NULL,
  `poNmbr` int(10) UNSIGNED NOT NULL,
  `poQnttyBck` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `poTnk` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `poNts` varchar(99) CHARACTER SET utf32 DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Pump Out';

--
-- Dumping data for table `pumpout`
--

INSERT INTO `pumpout` (`poId`, `poDly`, `poPmp`, `poNmbr`, `poQnttyBck`, `poTnk`, `poNts`) VALUES
(1, 1, 1, 10, 0, 5, ''),
(4, 2, 1, 2000, 20, 5, ''),
(5, 1, 2, 22, 5, 5, '');

-- --------------------------------------------------------

--
-- Table structure for table `pumps`
--

CREATE TABLE `pumps` (
  `pId` int(10) UNSIGNED NOT NULL,
  `pNm` varchar(99) CHARACTER SET utf32 NOT NULL,
  `pFrstNmbr` int(10) UNSIGNED NOT NULL,
  `pFrstDt` date NOT NULL,
  `pUsr` int(10) UNSIGNED NOT NULL DEFAULT 1,
  `pTnk` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `pAdDt` datetime DEFAULT current_timestamp(),
  `pWrk` tinyint(1) NOT NULL DEFAULT 1,
  `pNts` varchar(99) CHARACTER SET utf32 DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Pumps';

--
-- Dumping data for table `pumps`
--

INSERT INTO `pumps` (`pId`, `pNm`, `pFrstNmbr`, `pFrstDt`, `pUsr`, `pTnk`, `pAdDt`, `pWrk`, `pNts`) VALUES
(1, 'طلمبة1 مسدس1 سولار', 10001000, '2020-01-01', 0, 5, '2020-04-19 13:58:43', 1, 'صاحب الفكرة'),
(2, 'طلمبة1 مسدس2 سولار', 20000000, '2020-05-01', 0, 5, '2020-05-05 21:03:38', 1, '');

-- --------------------------------------------------------

--
-- Table structure for table `types`
--

CREATE TABLE `types` (
  `tId` int(10) UNSIGNED NOT NULL,
  `tNm` varchar(99) CHARACTER SET utf32 NOT NULL,
  `tGrp` int(10) UNSIGNED NOT NULL,
  `tAdDt` datetime DEFAULT current_timestamp(),
  `tWrk` tinyint(1) NOT NULL DEFAULT 1,
  `tNts` varchar(99) CHARACTER SET utf32 DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Types';

--
-- Dumping data for table `types`
--

INSERT INTO `types` (`tId`, `tNm`, `tGrp`, `tAdDt`, `tWrk`, `tNts`) VALUES
(1, 'شراء', 14, '2020-04-08 22:47:22', 1, NULL),
(2, 'م شراء', 14, '2020-04-08 22:47:22', 1, NULL),
(3, 'بيع', 14, '2020-04-08 22:47:22', 1, NULL),
(4, 'م بيع', 14, '2020-04-08 22:47:22', 1, NULL),
(5, 'أذن صرف', 14, '2020-04-08 22:47:22', 1, NULL),
(6, 'أذن إضافة', 14, '2020-04-08 22:47:22', 1, NULL),
(7, 'نقل مخازن', 14, '2020-04-08 22:47:22', 1, NULL),
(8, 'بونات داخلية', 15, '2020-04-08 22:47:22', 1, NULL),
(9, 'بونات شركة', 15, '2020-04-08 22:47:22', 1, NULL),
(10, 'بون سولار', 16, '2020-04-08 22:47:22', 1, NULL),
(11, 'بون 80', 16, '2020-04-08 22:47:22', 1, NULL),
(12, 'بون 92', 16, '2020-04-08 22:47:22', 1, NULL),
(13, 'بون 95', 16, '2020-04-08 22:47:22', 1, NULL),
(14, 'بون غسيل', 16, '2020-04-08 22:47:22', 1, NULL),
(15, 'بون زيت 3 لتر', 16, '2020-04-08 22:47:22', 1, NULL),
(16, 'بون زيت 4 لتر', 16, '2020-04-08 22:47:22', 1, NULL),
(17, 'بون زيت 5 لتر', 16, '2020-04-08 22:47:22', 1, NULL),
(18, 'بون زيت 6 لتر', 16, '2020-04-08 22:47:22', 1, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `userlog`
--

CREATE TABLE `userlog` (
  `ulId` int(10) UNSIGNED NOT NULL,
  `ulUsrId` int(10) UNSIGNED NOT NULL,
  `ulUsr` varchar(99) CHARACTER SET utf32 NOT NULL,
  `ulPsswrd` varchar(255) CHARACTER SET utf32 NOT NULL,
  `ulEml` varchar(255) CHARACTER SET utf32 NOT NULL,
  `ulMxLg` int(10) UNSIGNED NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='UserLog';

--
-- Dumping data for table `userlog`
--

INSERT INTO `userlog` (`ulId`, `ulUsrId`, `ulUsr`, `ulPsswrd`, `ulEml`, `ulMxLg`) VALUES
(1, 1, 'sm', '40bd001563085fc35165329ea1ff5c5ecbdbbeef', '121@34.com', 0),
(5, 3, 'asd', '40bd001563085fc35165329ea1ff5c5ecbdbbeef', '12@34.com', 0),
(6, 2, 'admin', '1c6637a8f2e1f75e06ff9984894d6bd16a3a36a9', '122@34.com', 0);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `usId` int(10) UNSIGNED NOT NULL,
  `usNm` varchar(99) CHARACTER SET utf32 NOT NULL,
  `usCardId` varchar(27) CHARACTER SET utf32 DEFAULT NULL,
  `usAddress` varchar(99) CHARACTER SET utf32 DEFAULT NULL,
  `usTel` varchar(20) CHARACTER SET utf32 DEFAULT NULL,
  `usGrp` int(10) UNSIGNED NOT NULL DEFAULT 4,
  `usAdDt` datetime DEFAULT current_timestamp(),
  `usWrk` tinyint(1) NOT NULL DEFAULT 1,
  `usNts` varchar(99) CHARACTER SET utf32 DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Users';

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`usId`, `usNm`, `usCardId`, `usAddress`, `usTel`, `usGrp`, `usAdDt`, `usWrk`, `usNts`) VALUES
(1, 'جمال', '', '', '', 102, '2020-04-08 22:47:22', 1, ''),
(2, 'مدير الشركة', '', '', '', 103, '2020-04-08 22:47:22', 1, ''),
(3, 'مستخدم 1', '', '', '', 104, '2020-04-08 22:47:22', 1, ''),
(4, 'موظف', '', '', '', 105, '2020-04-20 15:58:48', 1, ''),
(14, 'ffجمال', '', '', '', 102, '2020-05-25 11:15:06', 1, '');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `accounts`
--
ALTER TABLE `accounts`
  ADD PRIMARY KEY (`aId`),
  ADD UNIQUE KEY `aNm` (`aNm`) USING BTREE;

--
-- Indexes for table `billbody`
--
ALTER TABLE `billbody`
  ADD PRIMARY KEY (`bbId`);

--
-- Indexes for table `billheader`
--
ALTER TABLE `billheader`
  ADD PRIMARY KEY (`bhId`);

--
-- Indexes for table `couponinout`
--
ALTER TABLE `couponinout`
  ADD PRIMARY KEY (`cioId`);

--
-- Indexes for table `coupons`
--
ALTER TABLE `coupons`
  ADD PRIMARY KEY (`cId`);

--
-- Indexes for table `custumerpayment`
--
ALTER TABLE `custumerpayment`
  ADD PRIMARY KEY (`cpId`);

--
-- Indexes for table `dailyheader`
--
ALTER TABLE `dailyheader`
  ADD PRIMARY KEY (`dhId`),
  ADD UNIQUE KEY `dhNmbr` (`dhNmbr`);

--
-- Indexes for table `evaporationrate`
--
ALTER TABLE `evaporationrate`
  ADD PRIMARY KEY (`erId`);

--
-- Indexes for table `goods`
--
ALTER TABLE `goods`
  ADD PRIMARY KEY (`gId`),
  ADD UNIQUE KEY `gNm` (`gNm`);

--
-- Indexes for table `goodsprice`
--
ALTER TABLE `goodsprice`
  ADD PRIMARY KEY (`gpId`);

--
-- Indexes for table `groups`
--
ALTER TABLE `groups`
  ADD PRIMARY KEY (`gId`),
  ADD UNIQUE KEY `gNm` (`gNm`);

--
-- Indexes for table `pumpout`
--
ALTER TABLE `pumpout`
  ADD PRIMARY KEY (`poId`);

--
-- Indexes for table `pumps`
--
ALTER TABLE `pumps`
  ADD PRIMARY KEY (`pId`),
  ADD UNIQUE KEY `pNm` (`pNm`);

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
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`usId`),
  ADD UNIQUE KEY `usNm` (`usNm`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `accounts`
--
ALTER TABLE `accounts`
  MODIFY `aId` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `billbody`
--
ALTER TABLE `billbody`
  MODIFY `bbId` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `billheader`
--
ALTER TABLE `billheader`
  MODIFY `bhId` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `couponinout`
--
ALTER TABLE `couponinout`
  MODIFY `cioId` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `coupons`
--
ALTER TABLE `coupons`
  MODIFY `cId` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `custumerpayment`
--
ALTER TABLE `custumerpayment`
  MODIFY `cpId` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `dailyheader`
--
ALTER TABLE `dailyheader`
  MODIFY `dhId` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `evaporationrate`
--
ALTER TABLE `evaporationrate`
  MODIFY `erId` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `goods`
--
ALTER TABLE `goods`
  MODIFY `gId` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `goodsprice`
--
ALTER TABLE `goodsprice`
  MODIFY `gpId` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `groups`
--
ALTER TABLE `groups`
  MODIFY `gId` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=138;

--
-- AUTO_INCREMENT for table `pumpout`
--
ALTER TABLE `pumpout`
  MODIFY `poId` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `pumps`
--
ALTER TABLE `pumps`
  MODIFY `pId` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `types`
--
ALTER TABLE `types`
  MODIFY `tId` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `userlog`
--
ALTER TABLE `userlog`
  MODIFY `ulId` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `usId` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
