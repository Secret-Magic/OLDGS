<?php
session_start();
if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
	include_once "init.php";

	$pageTitle = "اليومية";
	
	for ($i = 0; $i < 7; $i++) {
		$srtSymbl[$i] = "   &#9670;";
	}
	//--------------------------------------------
	if ($_SERVER['REQUEST_METHOD'] == "POST") {
		$errs = array();
		$report = "";
		$pk = 0;
		$iss = 0;
		$goOn = FALSE;

		$tmpId = intval($_POST['iId']);
		$tmpNm = intval($_POST['iNm']);
		$tmpDt = vldtVrbl($_POST['iDt']);
		$tmpNmbr = intval($_POST['iNmbr']);
		$tmpNts = vldtVrbl($_POST['iNts']);

		if (isset($_POST['btnSave'])) {
			if (isset($tmpId)) {
				if ($tmpId > 0) {
					if (isSaved("bhId", "BillHeader", $tmpId)) {
						if (empty($tmpNm)) {
							$errs[] = " الاسم فارغ";
						} else {
							$sql = "UPDATE BillHeader SET bhNm=?, bhDt=?, bhNts=?  WHERE `bhId`=? ;";
							$vl = array($tmpNm, $tmpDt, $tmpNts, $tmpId);
							$report = "تم حفظ التعديلات";
							$goOn = TRUE;
						}
					} else {
						$errs[] = "تم حذفه بواسطة مستخدم آخر";
					}
				} elseif ($tmpId == '0') {
					if (empty($tmpNm)) {
						$errs[] = " الاسم فارغ";
					} else {
						$sql = "INSERT INTO BillHeader (bhNmbr, bhNm, bhDt, bhNts, bhSub , bhKnd) VALUES (?,?,?,?,?,?) ;";
						$vl = array($tmpNmbr, $tmpNm, $tmpDt, $tmpNts, $_SESSION['Sub'], 8);
						$report = "تم إضافة بيان جديد";
						$goOn = TRUE;
						$pk = 2;
					}
				}
			} else {
				$errs[] = "خطأ غير متوقع";
			}
		} elseif (isset($_POST['btnDlt'])) {
			if (isset($tmpId) && $tmpId > 1) {
				if (isSaved("bhId", "BillHeader", $tmpId)) {
					if (isSaved("bhSub", "BillHeader", $_SESSION['Sub'], " AND bhId > " . $tmpId)) {
						$errs[] = "يتعذر الحذف لأنه تم تسجيل وردية بعدها";
						/*نسمح للمدير بالحذف */
					} else {
						/*if (isSaved("gGrpTyp", "Groups", intval($_POST['iId']))) {
								$errs[]="يوجد مجموعات لهذه الفئة";
							}
							*/
						$sql = "DELETE FROM BillHeader WHERE `bhId`=? ;";
						$vl = array($tmpId);
						$report = "تم حذفه";
						$goOn = TRUE;
					}
				} else {
					$errs[] = "تم حذفه بواسطة مستخدم آخر";
				}
			} else {
				$errs[] = " غير مسموح بالحذف";
			}
		}
		if ($goOn) {
			if (getRC($sql, $vl) > 0) {
				if ($pk == 2) {
					$sql = "SELECT MAX(`bhId`) as `mId` FROM BillHeader WHERE bhKnd=8 AND bhSub= ?";
					$vl = array($_SESSION['Sub']);
					$row = getRow($sql, $vl);
					$mId = $row['mId'];
					$sql = "SELECT * FROM Pumps WHERE pWrk=1 AND pSub= " . $_SESSION['Sub'];
					$result = getRows($sql);
					foreach ($result as $row) {
						if (isSaved("poPmp", "PumpOut", $row['pId'])) {
							$sql = "SELECT MAX(`poNmbr`) as `mNmbr` FROM PumpOut WHERE poPmp= ?";
							$vl = array($row['pId']);
							$row2 = getRow($sql, $vl);
							$mNmbr = $row2['mNmbr'];
						} else {
							$mNmbr = $row['pFrstNmbr'];
						}
						$sql = "INSERT INTO PumpOut (poDly, poPmp, poNmbr, poTnk) VALUES (?,?,?,?)";
						$vl = array($mId, $row['pId'], $mNmbr, $row['pTnk']);
						getRC($sql, $vl);
					}
				}
			} else {
				$report = "لم يحدث شئ";
			}
		} else {
			$iss = 1;
			foreach ($errs as $err) {
				$report .= " # " . $err;
			}
		}
	} //==============================================================
	elseif ($_SERVER['REQUEST_METHOD'] == "GET") {
		if (isset($_GET['iSrch'])) {
			$_SESSION['strSrch'] = vldtVrbl($_GET['iSrch']);
		} elseif (isset($_SESSION['strSrch'])) {
			//$_SESSION['strSrch'] ;
		} else {
			$_SESSION['strSrch'] = " ";
		}
		//------------------------------------------
		if (isset($_GET['srt'])) {
			if (isset($_SESSION['srt']) && $_SESSION['srt'] == vldtVrbl($_GET['srt'])) {
				$_SESSION['srtKnd'] = 19314 - $_SESSION['srtKnd'];
			} else {
				$_SESSION['srt'] = vldtVrbl($_GET['srt']);
				$_SESSION['srtKnd'] = 9652;
			}
		} else {
			$_SESSION['srt'] = 1;
			$_SESSION['srtKnd'] = 9652;
			$_SESSION['strSrt'] = " ;";
		}
		//------------------------------------------
		switch ($_SESSION['srt']) {
			case 0:
				$_SESSION['strSrt'] = "ORDER BY `bhId`";
				break;
			case 1:
				$_SESSION['strSrt'] = "ORDER BY bhNmbr";
				break;
			case 2:
				$_SESSION['strSrt'] = "ORDER BY bhNm";
				break;
			case 3:
				$_SESSION['strSrt'] = "ORDER BY aNm";
				break;
			case 4:
				$_SESSION['strSrt'] = "ORDER BY bhDt";
				break;
			case 5:
				$_SESSION['strSrt'] = "ORDER BY bhNts";
				break;
			default:
				$_SESSION['strSrt'] = "ORDER BY `bhNmbr`";
				break;
		}
		$_SESSION['strSrt'] .= ($_SESSION['srtKnd'] == 9652) ? " ASC ;" : " DESC ;";
	} else {
		$report = "لا أظن يمكن تحقيقه";
	}
	$srtSymbl[$_SESSION['srt']] = " &#" . $_SESSION['srtKnd'] . "; ";
?>

	<!-------------------------------- Table ---------------------------------->
	<div id="dvTbl">
		<form action="<?php echo (vldtVrbl($_SERVER['PHP_SELF'])); ?>" method="get">
			<input class="srch" type="search" name="iSrch" id="srch" placeholder="بحث بالاسم " value="<?php echo ($_SESSION['strSrch']); ?>">
		</form>
		<table class="mTbl" id="mTbl">
			<caption> <?php getTitle(); ?> </caption>
			<thead>
				<tr>
					<th>م</th>
					<th class='hiddenCol'> <a href="?srt=0"> <?php echo ($srtSymbl[0]); ?> الكود </a> </th>
					<th> <a href="?srt=1"> <?php echo ($srtSymbl[1]); ?> رقم اليومية </a> </th>
					<th class='hiddenCol'> <a href="?srt=2"> <?php echo ($srtSymbl[2]); ?> كود الاسم </a> </th>
					<th> <a href="?srt=3"> <?php echo ($srtSymbl[3]); ?> الاسم </a> </th>
					<th> <a href="?srt=4"> <?php echo ($srtSymbl[4]); ?> التاريخ </a> </th>
					<th> <a href="?srt=5"> <?php echo ($srtSymbl[5]); ?> ملاحظات </a> </th>
					<th> <a href="?srt=6"> <?php echo ($srtSymbl[6]); ?> العمليات</a> </th>
				</tr>
			</thead>
			<tbody>
				<?php
				$rc = 0;
				$sql = "SELECT * FROM BillHeader INNER JOIN Accounts ON bhNm=aId WHERE bhKnd=8 AND bhSub= " . $_SESSION['Sub'] . " AND bhNm like '%" . vldtVrbl($_SESSION['strSrch']) . "%' " . $_SESSION['strSrt'];
				$result = getRows($sql);
				foreach ($result as $row) {
					$sql = "SELECT * FROM PumpOut WHERE poDly =? ";
					$vl = array($row['bhId']);
					$po[0] = getRC($sql, $vl);
					$sql = "SELECT * FROM BillBody WHERE bbStr=13 AND bbBllHdr =? ";
					$vl = array($row['bhId']);
					$po[1] = getRC($sql, $vl);
					$sql = "SELECT * FROM BillBody WHERE bbStr=12 AND bbBllHdr =? ";
					$vl = array($row['bhId']);
					$po[2] = getRC($sql, $vl);
					echo ("<tr> <td>" . ++$rc . "</td> <td class='hiddenCol'>" . $row['bhId'] . "</td>
							<td>" . $row['bhNmbr'] . "</td>
							<td class='hiddenCol'>" . $row['bhNm'] . "</td> <td>" . $row['aNm'] . "</td>
							<td>" . $row['bhDt'] . "</td>	<td>" . $row['bhNts'] . "</td> <td> <div class='btns' >
							<button><a href ='PumpOut.php?iDly=" . $row['bhId'] . "' target='_blank' >" . $po[0] . " = الطلمبات</a></button>
							<button><a href ='BillBody.php?iStr=13&iBll=" . $row['bhId'] . "' target='_blank'>" . $po[1] . " = الزيوت </a></button>
							<button><a href ='PumpOut.php?iDly=" . $row['bhId'] . "' target='_blank'> المصروفات</a></button>
							<button><a href ='BillBody.php?iStr=12&iBll=" . $row['bhId'] . "' target='_blank'> " . $po[2] . " = البونات</a></button>
							<button><a href ='PumpOut.php?iDly=" . $row['bhId'] . "' target='_blank'> النولون</a></button>
							</div> </td></tr>");
				}
				$sql = "SELECT * FROM BillHeader WHERE bhKnd=8 AND bhSub=?";
				$vl = array($_SESSION['Sub']);
				$nmbr = getRC($sql, $vl) + 1;
				echo ("<tr> <td>" . ++$rc . "</td> <td class='hiddenCol'>0</td> <td>$nmbr</td> <td class='hiddenCol'></td><td></td> <td></td> <td></td> <td></td>	</tr>");
				?>
			</tbody>
			<tfoot>
				<tr>
					<th> * </th>
					<td class='hiddenCol'> </td>
					<td> ملاحظات </td>
					<td colspan="5"> </td>
				</tr>
			</tfoot>
		</table>
	</div>
	<!-------------------------------- Input Screen ---------------------------->
	<div class="row" id="row">
		<div class="btnClose" id="btnClose"> &#10006; </div>
		<form action="<?php echo ($_SERVER['PHP_SELF']); ?>" method="post" name="iFrm" id="iFrm" >
			<input class="" id="iId" name="iId" type="hidden" value="<?php echo($tmpId) ?>" />
			<span>
				<input class="swing" id="iNmbr" name="iNmbr" type="number" value="<?php echo($tmpNmbr) ?>" readonly /><label for="iNmbr">رقم الوردية</label>
			</span>
			<span>
				<input class="swing" id="iDt" name="iDt" type="date" style="padding-right: 100px;" value="<?php echo($tmpDt) ?>" required /><label for="iDt">التاريخ</label>
			</span>
			<span>
				<select class="swing" name="iNm" id="iNm" placeholder="اسم رئيس الوردية أو المسئول">
					<?php
					for ($i = 2; $i < 6; $i++) {
						echo "<optgroup label='" . getNameById("Groups", "g", $i) . ":'>";
						$sql = "SELECT * FROM Accounts WHERE aGrp IN (SELECT gId FROM Groups WHERE gGrpTyp =" . $i . ") AND (aSub=0 || aSub= " . $_SESSION['Sub'] . ")";
						$result = getRows($sql);
						foreach ($result as $row) {
							$sl = "";
							if ($row['aId'] == $tmpNm) {
								$sl = " selected ";
							}
							echo ("<option value='" . $row['aId']."'". $sl. ">" . $row['aNm'] . "</option>");
						}
						echo "</optgroup>";
					}
					?>
				</select><label for="iNm"> الاسم </label>
			</span>
			<span>
				<input class="swing" id="iNts" name="iNts" type="text" maxlength="99" autocomplete="off" placeholder="وصف للوردية" value="<?php echo($tmpNts) ?>" /><label for="iNts">ملاحظات</label>
			</span>
			<button class="btn" type="submit" name="btnSave"> حفظ </button>
			<button class="btn" type="submit" name="btnDlt" id="btnDlt"> حذف </button>
		</form>
	</div>
	<!-------------------------------- Script ---------------------------------->
	<script>
		var x;
		var tbl01 = document.getElementById("mTbl");
		for (x = 1; x < tbl01.rows.length - 1; x = x + 1) {
			tbl01.rows[x].ondblclick = function() {
				document.getElementById('iId').value = this.cells[1].innerHTML;
				document.getElementById('iNmbr').value = this.cells[2].innerHTML;
				document.getElementById('iNts').value = this.cells[6].innerHTML;
				if (document.getElementById('iId').value == 0) {
					var iniDay = new Date();
					document.getElementById('iDt').value = iniDay.toISOString().substr(0, 10);
					document.getElementById('iNm').selectedIndex = "0" ;
				} else {
					document.getElementById('iDt').value = this.cells[5].innerHTML;
					document.getElementById('iNm').value = this.cells[3].innerHTML;
				}
				showInptScrn();
			}
		}
	</script>
<?php
		include_once TPL . 'footer.php';
	} else {
		header("location: index.php");
		exit();
	}
?>