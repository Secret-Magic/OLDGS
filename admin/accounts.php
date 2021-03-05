<?php
session_start();
if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
	include_once "init.php";

	for ($i = 0; $i < 7; $i++) {
		$srtSymbl[$i] = "   &#9670;";
	}
	//--------------------------------------------
	if ($_SERVER['REQUEST_METHOD'] == "POST") {
		$errs = array();
		$report = "";
		$iss = 0;
		$goOn = FALSE;

		$tmpId = intval($_POST['iId']);
		$tmpNm = vldtVrbl($_POST['iNm']);
		$tmpMax = intval($_POST['iMax']);
		$tmpNts = vldtVrbl($_POST['iNts']);
		$tmpGrp = vldtVrbl($_POST['iGrp']);

		if (isset($_POST['btnSave'])) {
			if (isset($tmpId)) {
				if ($tmpId > 0) {
					if (isSaved("aId", "Accounts", $tmpId)) {
						if (empty($tmpNm)) {
							$errs[] = " الاسم فارغ";
						} elseif (isSaved("aNm", "Accounts", $tmpNm, " AND aId <> " . $tmpId . " AND (aSub=0 OR aSub = " . $_SESSION['Sub']. " )")) {
							$errs[] = " الاسم مكرر ";
						} else {
							$sql = "UPDATE Accounts SET aNm=?, aMax=?, aNts=?, aGrp=? WHERE aId=? ;";
							$vl = array($tmpNm, $tmpMax, $tmpNts, $tmpGrp, $tmpId);
							$report = "تم حفظ التعديلات";
							$goOn = TRUE;
						}
					} else {
						$errs[] = "تم حذفه بواسطة مستخدم آخر";
					}
				} elseif ($tmpId == '0') {
					if (empty($tmpNm)) {
						$errs[] = " الاسم فارغ";
					} elseif (isSaved("aNm", "Accounts", $tmpNm, " AND (aSub=0 OR aSub = " . $_SESSION['Sub']. " )")) {
						$errs[] = " الاسم مكرر ";
					} else {
						$sql = "INSERT INTO Accounts (aNm, aMax, aNts, aGrp, aSub) VALUES (?,?,?,?,?) ;";
						$vl = array($tmpNm, $tmpMax, $tmpNts, $tmpGrp, $_SESSION['tmpSub']);
						$report = "تم إضافة بيان جديد";
						$goOn = TRUE;
					}
				}
			} else {
				$errs[] = "خطأ غير متوقع";
			}
		} elseif (isset($_POST['btnDlt'])) {
			if (isset($tmpId) && $tmpId > 11) {
				if (isSaved("aId", "Accounts", $tmpId)) {
					/* هل الخزينة موجودة فى حسابات أخرى
						هل التانك مسجل فى طلمبات الوردية pumpout
						راجع باقى العلاقات بعد تكملة الشاشات
						*/
					if (isSaved("pTnk", "Pumps", $tmpId)) {
						$errs[] = "يوجد طلمبة مربوطة بهذا التانك";
					} else {
						$sql = "DELETE FROM Accounts WHERE `aId`=? ;";
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

			} else {
				$report =  "لم يحدث شئ";
			}
		} else {
			$iss = 1;
			foreach ($errs as $err) {
				$report .= " # " . $err;
			}
		}
	} //==============================================================
	elseif ($_SERVER['REQUEST_METHOD'] == "GET") {
		if (isset($_GET['iCstmrTyp'])) {
			$_SESSION['iCstmrTyp'] = vldtVrbl($_GET['iCstmrTyp']);
		} elseif (isset($_SESSION['iCstmrTyp'])) {
			//$_SESSION['iCstmrTyp'] ;
		} else {
			$_SESSION['iCstmrTyp'] = 7;
			$report = "لم يتم تحديد نوع الحساب بشكل صحيح" ;
		}
		$_SESSION['tmpSub'] = $_SESSION['Sub'];
		if ($_SESSION['iCstmrTyp'] > 1 and $_SESSION['iCstmrTyp'] < 4) {
			$_SESSION['tmpSub'] = 0;
		}
		//------------------------------------------
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
			$_SESSION['srt'] = 0;
			$_SESSION['srtKnd'] = 9652;
			$_SESSION['strSrt'] = " ;";
		}

		//------------------------------------------
		switch ($_SESSION['srt']) {
			case 0:
				$_SESSION['strSrt'] = "ORDER BY `aId`";
				break;
			case 1:
				$_SESSION['strSrt'] = "ORDER BY aNm";
				break;
			case 2:
				$_SESSION['strSrt'] = "ORDER BY aMax";
				break;
			case 3:
				$_SESSION['strSrt'] = "ORDER BY aNts";
				break;
			case 4:
				$_SESSION['strSrt'] = "ORDER BY aWrk";
				break;
			case 5:
				$_SESSION['strSrt'] = "ORDER BY aGrp";
				break;
			case 6:
				$_SESSION['strSrt'] = "ORDER BY aAdDt";
				break;
			default:
				$_SESSION['strSrt'] = "ORDER BY aAdDt";
				break;
		}
		$_SESSION['strSrt'] .= ($_SESSION['srtKnd'] == 9652) ? " ASC ;" : " DESC ;";
	} else {
		$report = "لا أظن يمكن تحقيقه";
	}
	$srtSymbl[$_SESSION['srt']] = " &#" . $_SESSION['srtKnd'] . "; ";
	$pageTitle = str_replace("فئة", '', getNameById('Groups', 'g', $_SESSION['iCstmrTyp']));
?>
	<!-------------------------------- Table ---------------------------------->
	<div id="dvTbl">
		<form action="<?php echo (vldtVrbl($_SERVER['PHP_SELF'])); ?>" method="get">
			<input class="srch" type="search" name="iSrch" placeholder="بحث بالاسم " value="<?php echo ($_SESSION['strSrch']); ?>">
		</form>
		<table class="mTbl" id="mTbl">
			<caption> <?php getTitle(); ?> </caption>
			<thead>
				<tr>
					<th>م</th>
					<th class='hiddenCol'> <a href="?srt=0"> <?php echo ($srtSymbl[0]); ?> الكود </a> </th>
					<th> <a href="?srt=1"> <?php echo ($srtSymbl[1]); ?> الاسم </a> </th>
					<th> <a href="?srt=2"> <?php echo ($srtSymbl[2]); ?> حد الإئتمان </a> </th>
					<th> <a href="?srt=3"> <?php echo ($srtSymbl[3]); ?> ملاحظات </a> </th>
					<th> <a href="?srt=4"> <?php echo ($srtSymbl[4]); ?> نشط </a> </th>
					<th> <a href="?srt=5"> <?php echo ($srtSymbl[5]); ?> مجموعة </a> </th>
					<th> <a href="?srt=6"> <?php echo ($srtSymbl[6]); ?> تاريخ الانشاء </a> </th>
					<th class='hiddenCol'> <a href="?srt=7"> <?php echo ($srtSymbl[7]); ?> الفرع </a> </th>
				</tr>
			</thead>
			<tbody>
				<?php
				$rc = 0;
				$sql = "SELECT * FROM Accounts WHERE (aSub=0 OR aSub = " . $_SESSION['Sub'] . " ) AND aGrp IN (SELECT gId FROM Groups WHERE gGrpTyp=" . vldtVrbl($_SESSION['iCstmrTyp']) . ") AND aNm like '%" . vldtVrbl($_SESSION['strSrch']) . "%' " . $_SESSION['strSrt'];
				$result = getRows($sql);
				foreach ($result as $row) {
					echo ("<tr> <td>" . ++$rc . "</td> <td class='hiddenCol'>" . $row['aId'] . "</td>
							<td>" . $row['aNm'] . "</td> <td>" . $row['aMax'] . "</td>
							<td>" . $row['aNts'] . "</td> <td>" . $row['aWrk'] . "</td> 
							<td>" . $row['aGrp'] . "</td> <td>" . $row['aAdDt'] . "</td>
							<td class='hiddenCol'>" . $row['aSub'] . "</td> </tr>");
				}
				echo ("<tr> <td>" . ++$rc . "</td> <td class='hiddenCol'>0</td> <td></td> <td></td> <td></td> <td></td> <td></td> <td></td> <td class='hiddenCol'></td>	</tr>");
				?>
			</tbody>
			<tfoot>
				<tr>
					<th> * </th>
					<td class='hiddenCol'> </td>
					<td> ملاحظات </td>
					<td colspan="6"> </td>
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
				<input class="swing" id="iNm" name="iNm" type="text" maxlength="99" autocomplete="off" placeholder="اسم الحساب" value="<?php echo($tmpNm) ?>" required /><label for="iNm">الاسم</label>
			</span>
			<span>
				<input class="swing" id="iMax" name="iMax" type="number" maxlength="10" value="<?php echo($tmpMax) ?>" /><label for="iMax">المسموح</label>
			</span>
			<span>
				<input class="swing" id="iNts" name="iNts" type="text" maxlength="99" autocomplete="off" placeholder="وصف الحساب" value="<?php echo($tmpNts) ?>" /><label for="iNts">ملاحظات</label>
			</span>
			<span>
				<select class="swing" name="iGrp" id="iGrp">
					<?php
					$sql = "SELECT * FROM Groups WHERE gWrk=1 AND gGrpTyp = " . $_SESSION['iCstmrTyp'] . " ;";
					$result = getRows($sql);
					foreach ($result as $row) {
						$sl = "";
						if ($row['gId'] == $tmpGrp) {
							$sl = " selected ";
						}
						echo "<option value='" . $row['gId'] . "' ". $sl. " >" . $row['gNm'] . "</option> ";
					}
					?>
				</select><label for="iGrp"> المجموعة: </label>
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
				document.getElementById('iNm').value = this.cells[2].innerHTML;
				document.getElementById('iMax').value = this.cells[3].innerHTML;
				document.getElementById('iNts').value = this.cells[4].innerHTML;

				if (this.cells[1].innerHTML == 0) {
					document.getElementById("iGrp").selectedIndex = "0";
				} else {
					document.getElementById('iGrp').value = this.cells[6].innerHTML;
				}
				showInptScrn();
			}
		}

		function isValidForm() {
			return isVldInpt('iNm', 'ادخل الاسم');
		}
	</script>
<?php
		include_once TPL . 'footer.php';
	} else {
		header("location: index.php");
		exit();
	}
?>