<?php
	include_once ("config.php");
	buildDB();
	//buildRls();
	insertBdt();
	// ----------------------------------------------------------
	function buildDB() {
		$sql="CREATE DATABASE IF NOT EXISTS `data01`
				DEFAULT CHARACTER SET utf8
				DEFAULT COLLATE utf8_general_ci ; ";
		CnctDb($sql, "s");
		//------------------- لإنشاء جدول sql بناء
		$sql="CREATE TABLE IF NOT EXISTS `data01`.`Users` (
				`usId` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
				`usNm` VARCHAR(99) CHARACTER SET utf32 COLLATE utf32_general_ci NOT NULL ,
				`usCardId` VARCHAR(27) CHARACTER SET utf32 COLLATE utf32_general_ci NULL ,
				`usAddress` VARCHAR(99) CHARACTER SET utf32 COLLATE utf32_general_ci NULL ,
				`usTel` VARCHAR(20) CHARACTER SET utf32 COLLATE utf32_general_ci NULL ,
				`usGrp` INT UNSIGNED NOT NULL DEFAULT '4',
				`usAdDt` DATETIME DEFAULT CURRENT_TIMESTAMP(),
				`usWrk` BOOLEAN NOT NULL DEFAULT '1',
				`usNts` VARCHAR(99) CHARACTER SET utf32 COLLATE utf32_general_ci NULL ,
				PRIMARY KEY (`usId`),	UNIQUE (`usNm`))
				ENGINE  = InnoDB CHARSET = utf8 COLLATE utf8_general_ci COMMENT = 'Users'; ";
		CnctDb($sql, "s");
		//------------------- لإنشاء جدول sql بناء
		$sql="CREATE TABLE IF NOT EXISTS `data01`.`UserLog` (
			`ulId` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
			`ulUsrId` INT UNSIGNED NOT NULL ,
			`ulUsr` VARCHAR(99) CHARACTER SET utf32 COLLATE utf32_general_ci NOT NULL ,
			`ulPsswrd` VARCHAR(255) CHARACTER SET utf32 COLLATE utf32_general_ci NOT NULL ,
			`ulEml` VARCHAR(255) CHARACTER SET utf32 COLLATE utf32_general_ci NOT NULL ,
			`ulMxLg` INT UNSIGNED NOT NULL DEFAULT '0',
			PRIMARY KEY (`ulId`),	UNIQUE (`ulUsrId`), UNIQUE (`ulUsr`),	UNIQUE (`ulEml`))
			ENGINE  = InnoDB CHARSET = utf8 COLLATE utf8_general_ci COMMENT = 'UserLog'; ";
		CnctDb($sql, "s");
		//------------------------------------------------
		$sql="CREATE TABLE IF NOT EXISTS `data01`.`Groups` (
				`gId` INT UNSIGNED NOT NULL AUTO_INCREMENT,
				`gNm` VARCHAR(99) CHARACTER SET utf32 COLLATE utf32_general_ci NOT NULL ,
				`gGrpTyp` INT UNSIGNED NOT NULL DEFAULT '1',
				`gUsr` INT UNSIGNED NOT NULL DEFAULT '1',
				`gAdDt` DATETIME DEFAULT CURRENT_TIMESTAMP(),
				`gWrk` BOOLEAN NOT NULL DEFAULT '1',
				`gNts` VARCHAR(99) CHARACTER SET utf32 COLLATE utf32_general_ci NULL ,
				PRIMARY KEY (`gId`), UNIQUE (`gNm`))
				ENGINE  = InnoDB CHARSET = utf8 COLLATE utf8_general_ci COMMENT = 'Groups'; ";
		CnctDb($sql, "s");
		//------------------------------------------------
		$sql ="CREATE TABLE IF NOT EXISTS `data01`.`Types` (
					`tId` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
					`tNm` VARCHAR(99) CHARACTER SET utf32 COLLATE utf32_general_ci NOT NULL ,
					`tGrp` INT UNSIGNED NOT NULL ,
					`tAdDt` DATETIME DEFAULT CURRENT_TIMESTAMP(),
					`tWrk` BOOLEAN NOT NULL DEFAULT '1',
					`tNts` VARCHAR(99) CHARACTER SET utf32 COLLATE utf32_general_ci NULL ,
					PRIMARY KEY (`tId`), UNIQUE (`tNm`))
					ENGINE  = InnoDB CHARSET = utf8 COLLATE utf8_general_ci COMMENT = 'Types'; ";
		CnctDb($sql, "s"); 
		//------------------------------------------------
		$sql ="CREATE TABLE IF NOT EXISTS `data01`.`Stores` (
					`sId` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
					`sNm` VARCHAR(99) CHARACTER SET utf32 COLLATE utf32_general_ci NOT NULL ,
					`sMax` INT UNSIGNED NOT NULL DEFAULT '0',
					`sGrp` INT UNSIGNED DEFAULT '8',
					`sUsr` INT UNSIGNED NOT NULL DEFAULT '1',
					`sAdDt` DATETIME DEFAULT CURRENT_TIMESTAMP(),
					`sWrk` BOOLEAN NOT NULL DEFAULT '1',
					`sNts` VARCHAR(99) CHARACTER SET utf32 COLLATE utf32_general_ci NULL ,
					PRIMARY KEY (`sId`), UNIQUE (`sNm`))
					ENGINE  = InnoDB CHARSET = utf8 COLLATE utf8_general_ci COMMENT = 'Stores'; ";
		CnctDb($sql, "s");
		//------------------------------------------------
		$sql ="CREATE TABLE IF NOT EXISTS `data01`.`Accounts` (
					`aId` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
					`aNm` VARCHAR(99) CHARACTER SET utf32 COLLATE utf32_general_ci NOT NULL ,
					`aMax` INT UNSIGNED NOT NULL DEFAULT '0',
					`aUsr` INT UNSIGNED NOT NULL DEFAULT '1',
					`aFrstBlnc` INT UNSIGNED NOT NULL DEFAULT '0',
					`aFrstDt` DATE NOT NULL DEFAULT '2020-01-01',
					`aGrp` INT UNSIGNED NOT NULL ,
					`aAdDt` DATETIME DEFAULT CURRENT_TIMESTAMP(),
					`aWrk` BOOLEAN NOT NULL DEFAULT '1',
					`aNts` VARCHAR(99) CHARACTER SET utf32 COLLATE utf32_general_ci NULL ,
					PRIMARY KEY (`aId`),UNIQUE (`aNm`))
					ENGINE  = InnoDB CHARSET = utf8 COLLATE utf8_general_ci COMMENT = 'Custumers'; ";
		CnctDb($sql, "s");
		//------------------------------------------------
		$sql ="CREATE TABLE IF NOT EXISTS `data01`.`Goods` (
					`gId` INT UNSIGNED NOT NULL AUTO_INCREMENT,
					`gNm` VARCHAR(99) CHARACTER SET utf32 COLLATE utf32_general_ci NOT NULL ,
					`gByPrc` DECIMAL(7,2) UNSIGNED NOT NULL DEFAULT '0',
					`gSllPrc` DECIMAL(7,2) UNSIGNED NOT NULL DEFAULT '0',
					`gGrp` INT UNSIGNED NOT NULL ,
					`gFrstBlnc` INT UNSIGNED NOT NULL DEFAULT '0' ,
					`gFrstDt` DATE NOT NULL DEFAULT '2020-01-01',
					`gUsr` INT UNSIGNED NOT NULL DEFAULT '1',
					`gStr` INT UNSIGNED NOT NULL DEFAULT '0',
					`gAdDt` DATETIME DEFAULT CURRENT_TIMESTAMP(),
					`gWrk` BOOLEAN NOT NULL DEFAULT '1',
					`gNts` VARCHAR(99) CHARACTER SET utf32 COLLATE utf32_general_ci NULL ,
					PRIMARY KEY (`gId`),UNIQUE (`gNm`))
					ENGINE  = InnoDB CHARSET = utf8 COLLATE utf8_general_ci COMMENT = 'Goods'; ";
		CnctDb($sql, "s");   
		//------------------------------------------------
		$sql ="CREATE TABLE IF NOT EXISTS `data01`.`Pumps` (
					`pId` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
					`pNm` VARCHAR(99) CHARACTER SET utf32 COLLATE utf32_general_ci NOT NULL ,
					`pFrstNmbr` INT UNSIGNED NOT NULL ,
					`pFrstDt` DATE NOT NULL ,
					`pUsr` INT UNSIGNED NOT NULL DEFAULT '1',
					`pTnk` INT UNSIGNED NOT NULL DEFAULT '0',
					`pGds` INT UNSIGNED NOT NULL DEFAULT '1',
					`pAdDt` DATETIME DEFAULT CURRENT_TIMESTAMP(),
					`pWrk` BOOLEAN NOT NULL DEFAULT '1',
					`pNts` VARCHAR(99) CHARACTER SET utf32 COLLATE utf32_general_ci NULL ,
					PRIMARY KEY (`pId`), UNIQUE (`pNm`))
					ENGINE  = InnoDB CHARSET = utf8 COLLATE utf8_general_ci COMMENT = 'Pumps'; ";
		CnctDb($sql, "s");
		//------------------------------------------------
		$sql ="CREATE TABLE IF NOT EXISTS `data01`.`DailyHeader` (
					`dhId` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
					`dhDt`  DATE NOT NULL ,
					`dhNm` VARCHAR(99) CHARACTER SET utf32 COLLATE utf32_general_ci NOT NULL ,
					`dhUsr` INT UNSIGNED NOT NULL DEFAULT '0',
					`dhNts` VARCHAR(99) CHARACTER SET utf32 COLLATE utf32_general_ci NULL ,
					PRIMARY KEY (`dhId`))
					ENGINE  = InnoDB CHARSET = utf8 COLLATE utf8_general_ci COMMENT = 'Daily Header';";
		CnctDb($sql, "s");
		//------------------------------------------------
		$sql ="CREATE TABLE IF NOT EXISTS `data01`.`PumpOut` (
					`poId` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
					`poDly` INT UNSIGNED NOT NULL ,
					`poPmp` INT UNSIGNED NOT NULL ,
					`poNmbr` INT UNSIGNED NOT NULL ,
					`poQnttyBck` INT UNSIGNED NOT NULL DEFAULT '0',
					`poTnk` INT UNSIGNED NOT NULL DEFAULT '0',
					`poNts` VARCHAR(99) CHARACTER SET utf32 COLLATE utf32_general_ci NULL ,
					PRIMARY KEY (`poId`))
					ENGINE  = InnoDB CHARSET = utf8 COLLATE utf8_general_ci COMMENT = 'Pump Out';";
		CnctDb($sql, "s");
		//------------------------------------------------
		$sql ="CREATE TABLE IF NOT EXISTS `data01`.`PriceList` (
					`plId` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
					`plDt`  DATE NOT NULL ,
					`plGoods` INT UNSIGNED NOT NULL ,
					`plSllPrc` DECIMAL(9,4) UNSIGNED NOT NULL DEFAULT '0',
					`plByPrc` DECIMAL(9,4) UNSIGNED NOT NULL DEFAULT '0',
					`plCmsn` DECIMAL(9,4) UNSIGNED NOT NULL DEFAULT '0',
					`plPkhr` DECIMAL(9,4) UNSIGNED NOT NULL DEFAULT '0',
					`plUsr` INT UNSIGNED NOT NULL DEFAULT '0',
					`plWrk` BOOLEAN NOT NULL DEFAULT '1',
					PRIMARY KEY (`plId`))
					ENGINE  = InnoDB CHARSET = utf8 COLLATE utf8_general_ci COMMENT = 'Goods Price';";
		CnctDb($sql, "s");
		//------------------------------------------------
		$sql ="CREATE TABLE IF NOT EXISTS `data01`.`BillHeader` (
					`bhId` INT UNSIGNED NOT NULL AUTO_INCREMENT,
					`bhDt`  DATE NOT NULL ,
					`bhNmbr` INT UNSIGNED NOT NULL ,
					`bhCstmr` INT UNSIGNED NOT NULL  ,
					`bhBllKnd` INT UNSIGNED NOT NULL ,                
					`bhDscnt` DECIMAL(7,2) NOT NULL DEFAULT '0' ,
					`bhUsr` INT UNSIGNED NOT NULL DEFAULT '0',
					`bhNts` VARCHAR(99) CHARACTER SET utf32 COLLATE utf32_general_ci NULL ,
					PRIMARY KEY (`bhId`))
					ENGINE  = InnoDB CHARSET = utf8 COLLATE utf8_general_ci COMMENT = 'Goods Bill Header'; ";
		CnctDb($sql, "s"); 
		//------------------------------------------------
		$sql ="CREATE TABLE IF NOT EXISTS `data01`.`BillBody` (
					`bbId` INT UNSIGNED NOT NULL AUTO_INCREMENT,
					`bbBllHdr` INT UNSIGNED NOT NULL ,
					`bbGds` INT UNSIGNED NOT NULL ,
					`bbPrc` DECIMAL(7,4) UNSIGNED NOT NULL ,
					`bbQntty` INT UNSIGNED NOT NULL ,
					`bbDscnt` DECIMAL(7,4) NOT NULL DEFAULT '0' ,
					`bbStr` INT UNSIGNED NOT NULL ,
					`bbNts` VARCHAR(99) CHARACTER SET utf32 COLLATE utf32_general_ci NULL ,
					PRIMARY KEY (`bbId`))
					ENGINE  = InnoDB CHARSET = utf8 COLLATE utf8_general_ci COMMENT = 'Goods Buy Bill Body'; ";
		CnctDb($sql, "s");
		//------------------------------------------------
		$sql ="CREATE TABLE IF NOT EXISTS `data01`.`CustumerPayment` (
					`cpId` INT UNSIGNED NOT NULL AUTO_INCREMENT,
					`cpDt`  Date NOT NULL ,
					`cpCstmr` INT UNSIGNED NOT NULL ,
					`cpVl` DECIMAL(9,3) UNSIGNED NOT NULL ,
					`cpStore` INT UNSIGNED NOT NULL ,
					`cpUsr` INT UNSIGNED NOT NULL DEFAULT '0',
					`cpNts` VARCHAR(99) CHARACTER SET utf32 COLLATE utf32_general_ci NULL ,
					PRIMARY KEY (`cpId`))
					ENGINE  = InnoDB CHARSET = utf8 COLLATE utf8_general_ci COMMENT = 'Custumer Payment'; ";
		CnctDb($sql, "s"); 
		//------------------------------------------------
		$sql ="CREATE TABLE IF NOT EXISTS `data01`.`Coupons` (
					`cId` INT UNSIGNED NOT NULL AUTO_INCREMENT,
					`cDt`  DATE NOT NULL ,
					`cCmKnd` INT UNSIGNED NULL ,                
					`cGsKnd` INT UNSIGNED NULL ,                
					`cVl` INT UNSIGNED NOT NULL ,
					`cGsprc` INT UNSIGNED NULL ,
					`cUsr` INT UNSIGNED NOT NULL DEFAULT '0',
					`cWrk` BOOLEAN NOT NULL DEFAULT '1',
					`cNts` VARCHAR(99) CHARACTER SET utf32 COLLATE utf32_general_ci NULL ,
					PRIMARY KEY (`cId`))
					ENGINE  = InnoDB CHARSET = utf8 COLLATE utf8_general_ci COMMENT = 'Coupons'; ";
		CnctDb($sql, "s");
		//------------------------------------------------
		$sql ="CREATE TABLE IF NOT EXISTS `data01`.`CouponInOut` (
					`cioId` INT UNSIGNED NOT NULL AUTO_INCREMENT,
					`cioCpnId` INT UNSIGNED NULL ,                
					`cioVl` INT UNSIGNED NOT NULL ,
					`cioSrcId` INT UNSIGNED NULL ,
					`cioUsr` INT UNSIGNED NULL ,
					`cioDt`  DATE NOT NULL ,
					`cioInOut` BOOLEAN NOT NULL DEFAULT '1',
					`cioNts` VARCHAR(99) CHARACTER SET utf32 COLLATE utf32_general_ci NULL ,
					PRIMARY KEY (`cioId`))
					ENGINE  = InnoDB CHARSET = utf8 COLLATE utf8_general_ci COMMENT = 'Coupon In Out'; ";
		CnctDb($sql, "s"); 
	}
	//------------------------------------------------
	function buildRls () {
				
		$sql = "ALTER TABLE `UserLog` ADD CONSTRAINT `R011` FOREIGN KEY (`ulId`) REFERENCES `Users`(`usId`) ON DELETE RESTRICT ON UPDATE RESTRICT; ";
		CnctDb($sql, "s");

		$sql = "ALTER TABLE `Groups` ADD CONSTRAINT `R021` FOREIGN KEY (`gUsr`) REFERENCES `Users`(`usId`) ON DELETE RESTRICT ON UPDATE RESTRICT; ";
		$sql .= "ALTER TABLE `Groups` ADD CONSTRAINT `R022` FOREIGN KEY (`gGrpTyp`) REFERENCES `GroupsType`(`gtId`) ON DELETE RESTRICT ON UPDATE RESTRICT; ";
		CnctDb($sql, "s");
		
		$sql = "ALTER TABLE `Types` ADD CONSTRAINT `R031` FOREIGN KEY (`tGrp`) REFERENCES `groups`(`gId`) ON DELETE RESTRICT ON UPDATE RESTRICT; ";
		CnctDb($sql, "s");
		
		$sql = "ALTER TABLE `Stores` ADD CONSTRAINT `R041` FOREIGN KEY (`sUsr`) REFERENCES `Users`(`usId`) ON DELETE RESTRICT ON UPDATE RESTRICT; ";
		$sql .= "ALTER TABLE `Stores` ADD CONSTRAINT `R042` FOREIGN KEY (`sGrp`) REFERENCES `Groups`(`gId`) ON DELETE RESTRICT ON UPDATE RESTRICT; ";
		CnctDb($sql, "s");
		
		$sql = "ALTER TABLE `Custumers` ADD CONSTRAINT `R051` FOREIGN KEY (`cUsr`) REFERENCES `Users`(`usId`) ON DELETE RESTRICT ON UPDATE RESTRICT; ";
		$sql .= "ALTER TABLE `Custumers` ADD CONSTRAINT `R052` FOREIGN KEY (`cGrp`) REFERENCES `Groups`(`gId`) ON DELETE RESTRICT ON UPDATE RESTRICT; ";
		CnctDb($sql, "s");
		
		$sql = "ALTER TABLE `Goods` ADD CONSTRAINT `R061` FOREIGN KEY (`gUsr`) REFERENCES `Users`(`usId`) ON DELETE RESTRICT ON UPDATE RESTRICT; ";
		$sql .= "ALTER TABLE `Goods` ADD CONSTRAINT `R062` FOREIGN KEY (`gGrp`) REFERENCES `Groups`(`gId`) ON DELETE RESTRICT ON UPDATE RESTRICT; ";
		$sql .= "ALTER TABLE `Goods` ADD CONSTRAINT `R063` FOREIGN KEY (`gStr`) REFERENCES `Stores`(`sId`) ON DELETE RESTRICT ON UPDATE RESTRICT; ";
		CnctDb($sql, "s");
		
		$sql = "ALTER TABLE `Pumps` ADD CONSTRAINT `R071` FOREIGN KEY (`pUsr`) REFERENCES `Users`(`usId`) ON DELETE RESTRICT ON UPDATE RESTRICT; ";
		$sql .= "ALTER TABLE `Pumps` ADD CONSTRAINT `R072` FOREIGN KEY (`pTnk`) REFERENCES `Stores`(`sId`) ON DELETE RESTRICT ON UPDATE RESTRICT; ";
		CnctDb($sql, "s");
		
		$sql = "ALTER TABLE `DailyHeader` ADD CONSTRAINT `R081` FOREIGN KEY (`dhUsr`) REFERENCES `Users`(`usId`) ON DELETE RESTRICT ON UPDATE RESTRICT; ";
		CnctDb($sql, "s");

		$sql = "ALTER TABLE `PumpOut` ADD CONSTRAINT `R091` FOREIGN KEY (`poPmp`) REFERENCES `Pumps`(`pId`) ON DELETE RESTRICT ON UPDATE RESTRICT; ";
		$sql .= "ALTER TABLE `PumpOut` ADD CONSTRAINT `R092` FOREIGN KEY (`poTnk`) REFERENCES `Stores`(`sId`) ON DELETE RESTRICT ON UPDATE RESTRICT; ";
		CnctDb($sql, "s");

		$sql = "ALTER TABLE `GoodsPrice` ADD CONSTRAINT `R101` FOREIGN KEY (`gpUsr`) REFERENCES `Users`(`usId`) ON DELETE RESTRICT ON UPDATE RESTRICT; ";
		$sql .= "ALTER TABLE `GoodsPrice` ADD CONSTRAINT `R102` FOREIGN KEY (`gpGoods`) REFERENCES `Goods`(`gId`) ON DELETE RESTRICT ON UPDATE RESTRICT; ";
		CnctDb($sql, "s");

		$sql = "ALTER TABLE `GoodsBillHeader` ADD CONSTRAINT `R111` FOREIGN KEY (`gbhUsr`) REFERENCES `Users`(`usId`) ON DELETE RESTRICT ON UPDATE RESTRICT; ";
		$sql .= "ALTER TABLE `GoodsBillHeader` ADD CONSTRAINT `R112` FOREIGN KEY (`gbhCstmr`) REFERENCES `Custumers`(`cId`) ON DELETE RESTRICT ON UPDATE RESTRICT; ";
		$sql .= "ALTER TABLE `GoodsBillHeader` ADD CONSTRAINT `R113` FOREIGN KEY (`gbhBllKnd`) REFERENCES `Types`(`tId`) ON DELETE RESTRICT ON UPDATE RESTRICT; ";
		CnctDb($sql, "s");

		$sql = "ALTER TABLE `GoodsBillBody` ADD CONSTRAINT `R121` FOREIGN KEY (`gbbId`) REFERENCES `GoodsBillHeader`(`gbhId`) ON DELETE RESTRICT ON UPDATE RESTRICT; ";
		$sql .= "ALTER TABLE `GoodsBillBody` ADD CONSTRAINT `R122` FOREIGN KEY (`gbbGds`) REFERENCES `Goods`(`gId`) ON DELETE RESTRICT ON UPDATE RESTRICT; ";
		$sql .= "ALTER TABLE `GoodsBillBody` ADD CONSTRAINT `R123` FOREIGN KEY (`gbbStr`) REFERENCES `Stores`(`sId`) ON DELETE RESTRICT ON UPDATE RESTRICT; ";
		CnctDb($sql, "s");
	
		$sql = "ALTER TABLE `CustumerPayment` ADD CONSTRAINT `R131` FOREIGN KEY (`cpCstmr`) REFERENCES `Custumers`(`cId`) ON DELETE RESTRICT ON UPDATE RESTRICT; ";
		$sql .= "ALTER TABLE `CustumerPayment` ADD CONSTRAINT `R132` FOREIGN KEY (`cpUsr`) REFERENCES `Users`(`usId`) ON DELETE RESTRICT ON UPDATE RESTRICT; ";
		$sql .= "ALTER TABLE `CustumerPayment` ADD CONSTRAINT `R133` FOREIGN KEY (`cpStore`) REFERENCES `Stores`(`sId`) ON DELETE RESTRICT ON UPDATE RESTRICT; ";
		CnctDb($sql, "s");
	
		$sql = "ALTER TABLE `EvaporationRate` ADD CONSTRAINT `R141` FOREIGN KEY (`erGsTyp`) REFERENCES `Goods`(`gId`) ON DELETE RESTRICT ON UPDATE RESTRICT; ";
		$sql .= "ALTER TABLE `EvaporationRate` ADD CONSTRAINT `R142` FOREIGN KEY (`erUsr`) REFERENCES `Users`(`usId`) ON DELETE RESTRICT ON UPDATE RESTRICT; ";
		CnctDb($sql, "s");

		$sql = "ALTER TABLE `Coupons` ADD CONSTRAINT `R151` FOREIGN KEY (`cCmKnd`) REFERENCES `Types`(`tId`) ON DELETE RESTRICT ON UPDATE RESTRICT; ";
		$sql .= "ALTER TABLE `Coupons` ADD CONSTRAINT `R152` FOREIGN KEY (`cUsr`) REFERENCES `Users`(`usId`) ON DELETE RESTRICT ON UPDATE RESTRICT; ";
		$sql .= "ALTER TABLE `Coupons` ADD CONSTRAINT `R153` FOREIGN KEY (`cGsKnd`) REFERENCES `Types`(`tId`) ON DELETE RESTRICT ON UPDATE RESTRICT; ";
		CnctDb($sql, "s");

		$sql = "ALTER TABLE `CouponInOut` ADD CONSTRAINT `R161` FOREIGN KEY (`cioCpnId`) REFERENCES `Coupons`(`cId`) ON DELETE RESTRICT ON UPDATE RESTRICT; ";
		$sql .= "ALTER TABLE `CouponInOut` ADD CONSTRAINT `R162` FOREIGN KEY (`cioUsr`) REFERENCES `Users`(`usId`) ON DELETE RESTRICT ON UPDATE RESTRICT; ";
		$sql .= "ALTER TABLE `CouponInOut` ADD CONSTRAINT `R163` FOREIGN KEY (`cioSrcId`) REFERENCES `Types`(`tId`) ON DELETE RESTRICT ON UPDATE RESTRICT; ";
		CnctDb($sql, "s");
	}
	//------------------------------------------------
	function insertBdt () {
	
		$sql=array();
		$sql[0]="INSERT INTO Users (usId, usNm, usGrp) VALUES (1, 'مبرمج',1 );";
		$sql[1]="INSERT INTO Users (usId, usNm, usGrp) VALUES (2, 'مدير',2);";
		$sql[2]="INSERT INTO Users (usId, usNm, usGrp) VALUES (3, 'مستخدم',3);";
		//$sql[3]="INSERT INTO Users (usId, usNm, usGrp) VALUES (4, 'موظف',4);";
		CnctDb($sql, "m");
		
		$sql=array();
		$sql[0]="INSERT INTO GroupsType (gtId, gtNm) VALUES (1, 'Users');";
		$sql[1]="INSERT INTO GroupsType (gtId, gtNm) VALUES (2, 'Supplier');";
		$sql[2]="INSERT INTO GroupsType (gtId, gtNm) VALUES (3, 'Custumers');";
		$sql[3]="INSERT INTO GroupsType (gtId, gtNm) VALUES (4, 'empty...');";
		$sql[4]="INSERT INTO GroupsType (gtId, gtNm) VALUES (5, 'Kinds');";
		$sql[5]="INSERT INTO GroupsType (gtId, gtNm) VALUES (6, 'Treasury');";
		$sql[6]="INSERT INTO GroupsType (gtId, gtNm) VALUES (7, 'Tanks');";
		$sql[7]="INSERT INTO GroupsType (gtId, gtNm) VALUES (8, 'Oil Stores');";
		$sql[8]="INSERT INTO GroupsType (gtId, gtNm) VALUES (9, 'Stores');";
		CnctDb($sql, "m");

		$sql=array();
		$sql[0]="INSERT INTO Groups (gId, gNm, gGrpTyp, gUsr) VALUES (1, 'مبرمج', 1,1);";
		$sql[1]="INSERT INTO Groups (gId, gNm, gGrpTyp, gUsr) VALUES (2, 'مدير', 1,1);";
		$sql[2]="INSERT INTO Groups (gId, gNm, gGrpTyp, gUsr) VALUES (3, 'مستخدم', 1,1);";
		$sql[3]="INSERT INTO Groups (gId, gNm, gGrpTyp, gUsr) VALUES (4, 'موظف', 1,1);";

		$sql[4]="INSERT INTO Groups (gId, gNm, gGrpTyp, gUsr) VALUES (5, 'خزينة', 6,1);";
		$sql[5]="INSERT INTO Groups (gId, gNm, gGrpTyp, gUsr) VALUES (6, 'تانك', 7,1);";
		$sql[6]="INSERT INTO Groups (gId, gNm, gGrpTyp, gUsr) VALUES (7, 'مخزن زيوت', 8,1);";
		$sql[7]="INSERT INTO Groups (gId, gNm, gGrpTyp, gUsr) VALUES (8, 'مخزن', 9,1);";

		$sql[8]="INSERT INTO Groups (gId, gNm, gGrpTyp, gUsr) VALUES (9, 'مورد', 2,1);";
		$sql[9]="INSERT INTO Groups (gId, gNm, gGrpTyp, gUsr) VALUES (10,'عميل', 3,1);";

		$sql[10]="INSERT INTO Groups (gId, gNm, gGrpTyp, gUsr) VALUES (11, 'م بترولية', 17,1);";
		$sql[11]="INSERT INTO Groups (gId, gNm, gGrpTyp, gUsr) VALUES (12, 'زيوت', 18,1);";
		$sql[12]="INSERT INTO Groups (gId, gNm, gGrpTyp, gUsr) VALUES (13, 'بضاعة', 19,1);";

		$sql[13]="INSERT INTO Groups (gId, gNm, gGrpTyp, gUsr) VALUES (14, 'أنواع الفواتير', 5,1);";
		$sql[14]="INSERT INTO Groups (gId, gNm, gGrpTyp, gUsr) VALUES (15, 'أنواع البونات', 5,1);";
		$sql[15]="INSERT INTO Groups (gId, gNm, gGrpTyp, gUsr) VALUES (16, 'أنواع منتج البونات', 5,1);";
		CnctDb($sql, "m");
	
		$sql=array();
		$sql[0]="INSERT INTO Types (tId, tNm, tGrp) VALUES (1, 'شراء', 118);";
		$sql[1]="INSERT INTO Types (tId, tNm, tGrp) VALUES (2, 'م شراء', 118);";
		$sql[2]="INSERT INTO Types (tId, tNm, tGrp) VALUES (3, 'بيع', 118);";
		$sql[3]="INSERT INTO Types (tId, tNm, tGrp) VALUES (4, 'م بيع', 118);";
		$sql[4]="INSERT INTO Types (tId, tNm, tGrp) VALUES (5, 'أذن صرف', 118);";
		$sql[5]="INSERT INTO Types (tId, tNm, tGrp) VALUES (6, 'أذن إضافة', 118);";
		$sql[6]="INSERT INTO Types (tId, tNm, tGrp) VALUES (7, 'نقل مخازن', 118);";
		$sql[7]="INSERT INTO Types (tId, tNm, tGrp) VALUES (8, 'تسوية', 118);";
		$sql[8]="INSERT INTO Types (tId, tNm, tGrp) VALUES (9, 'رصيد أول المدة', 118);";

		$sql[7]="INSERT INTO Types (tId, tNm, tGrp) VALUES (8, 'بونات داخلية', 15);";
		$sql[8]="INSERT INTO Types (tId, tNm, tGrp) VALUES (9, 'بونات شركة', 15);";

		$sql[9] ="INSERT INTO Types (tId, tNm, tGrp) VALUES (10, 'بون سولار', 16);";
		$sql[10]="INSERT INTO Types (tId, tNm, tGrp) VALUES (11, 'بون 80', 16);";
		$sql[11]="INSERT INTO Types (tId, tNm, tGrp) VALUES (12, 'بون 92', 16);";
		$sql[12]="INSERT INTO Types (tId, tNm, tGrp) VALUES (13, 'بون 95', 16);";
		$sql[13]="INSERT INTO Types (tId, tNm, tGrp) VALUES (14, 'بون غسيل', 16);";
		$sql[14]="INSERT INTO Types (tId, tNm, tGrp) VALUES (15, 'بون زيت 3 لتر', 16);";
		$sql[15]="INSERT INTO Types (tId, tNm, tGrp) VALUES (16, 'بون زيت 4 لتر', 16);";
		$sql[16]="INSERT INTO Types (tId, tNm, tGrp) VALUES (17, 'بون زيت 5 لتر', 16);";
		$sql[17]="INSERT INTO Types (tId, tNm, tGrp) VALUES (18, 'بون زيت 6 لتر', 16);";
		CnctDb($sql, "m");

		$sql=array();
		$sql[0]="INSERT INTO Stores (sId, sNm, sGrp) VALUES (1, 'خزينة رقم 1', 5);";
		$sql[1]="INSERT INTO Stores (sId, sNm, sGrp) VALUES (2, 'تنك 80', 6);";
		$sql[2]="INSERT INTO Stores (sId, sNm, sGrp) VALUES (3, 'تنك 92', 6);";
		$sql[3]="INSERT INTO Stores (sId, sNm, sGrp) VALUES (4, 'تنك 95', 6);";
		$sql[4]="INSERT INTO Stores (sId, sNm, sGrp) VALUES (5, 'تنك سولار', 6);";
		$sql[5]="INSERT INTO Stores (sId, sNm, sGrp) VALUES (6, 'مخزن زيت رقم 1', 7);";
		$sql[6]="INSERT INTO Stores (sId, sNm, sGrp) VALUES (7, 'مخزن رقم 1', 8);";
		CnctDb($sql, "m");

		$sql=array();
		$sql[0]="INSERT INTO Custumers (cId, cNm, cGrp) VALUES (1, 'شركة مصر للبترول', 9);";
		$sql[1]="INSERT INTO Custumers (cId, cNm, cGrp) VALUES (2, 'مورد نقدى', 9);";
		$sql[2]="INSERT INTO Custumers (cId, cNm, cGrp) VALUES (3, 'عميل نقدى', 10);";
		$sql[3]="INSERT INTO Custumers (cId, cNm, cGrp) VALUES (4, 'عميل رقم 2', 10);";
		CnctDb($sql, "m");
	
	}