<?php
session_start();
if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
	include_once "init.php";
	include_once TPL . 'navbar.php';
	include_once TPL . 'slider.php';

	$goOn = FALSE;

	for ($i = 0; $i < 7; $i++) {
		$srtSymbl[$i] = "   &#9670;";
	}
	//--------------------------------------------
	if ($_SERVER['REQUEST_METHOD'] == "POST") {
		$errs = array();
		if (isset($_POST['btnSave'])) {
			if (isset($_POST['iId'])) {
				if (intval($_POST['iId']) > 0) {
					if (isSaved("aId", "Accounts", intval($_POST['iId']))) {
						if (empty(vldtVrbl($_POST['iNm']))) {
							$errs[] = " الاسم فارغ";
						} elseif (isSaved("aNm", "Accounts", vldtVrbl($_POST['iNm']), " AND aId<>" . vldtVrbl($_POST['iId']) . " AND aSub = " . $_SESSION['tmpSub'])) {
							$errs[] = " الاسم مكرر ";
						} else {
							$sql = "UPDATE Accounts SET aNm=?, aMax=?, aNts=?, aGrp=? WHERE aId=? ;";
							$vl = array(vldtVrbl($_POST['iNm']), vldtVrbl($_POST['iMax']), vldtVrbl($_POST['iNts']), vldtVrbl($_POST['iGrp']), vldtVrbl($_POST['iId']));
							$report = "تم حفظ التعديلات";
							$goOn = TRUE;
						}
					} else {
						$errs[] = "تم حذفه بواسطة مستخدم آخر";
					}
				} elseif ($_POST['iId'] == '0') {
					if (empty(vldtVrbl($_POST['iNm']))) {
						$errs[] = " الاسم فارغ";
					} elseif (isSaved("aNm", "Accounts", vldtVrbl($_POST['iNm']), " AND aSub = " . $_SESSION['tmpSub'])) {
						$errs[] = " الاسم مكرر ";
					} else {
						$sql = "INSERT INTO Accounts (aNm, aMax, aNts, aGrp, aSub) VALUES (?,?,?,?,?) ;";
						$vl = array(vldtVrbl($_POST['iNm']), vldtVrbl($_POST['iMax']), vldtVrbl($_POST['iNts']), vldtVrbl($_POST['iGrp']), $_SESSION['tmpSub']);
						$report = "تم إضافة بيان جديد";
						$goOn = TRUE;
					}
				}
			} else {
				$errs[] = "خطأ غير متوقع";
			}
		} elseif (isset($_POST['btnDlt'])) {
			if (isset($_POST['iId']) && intval($_POST['iId']) > 11) {
				if (isSaved("aId", "Accounts", intval($_POST['iId']))) {
					/* هل الخزينة موجودة فى حسابات أخرى
						هل التانك مسجل فى طلمبات الوردية pumpout
						راجع باقى العلاقات بعد تكملة الشاشات
						*/
					if (isSaved("pTnk", "Pumps", intval($_POST['iId']))) {
						$errs[] = "يوجد طلمبة مربوطة بهذا التانك";
					} else {
						$sql = "DELETE FROM Accounts WHERE `aId`=? ;";
						$vl = array(intval($_POST['iId']));
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
			$report = "";
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
			$_SESSION['srt'] = 6;
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
		echo ("خطأ غير متوقع");
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
				$sql = "SELECT * FROM Accounts WHERE aSub = " . $_SESSION['tmpSub'] . " AND aGrp IN (SELECT gId FROM Groups WHERE gGrpTyp=" . vldtVrbl($_SESSION['iCstmrTyp']) . ") AND aNm like '%" . vldtVrbl($_SESSION['strSrch']) . "%' " . $_SESSION['strSrt'];
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
		<form action="<?php echo ($_SERVER['PHP_SELF']); ?>" method="post" name="iFrm" onsubmit="return isValidForm()">
			<input class="" id="iId" name="iId" type="hidden" />
			<span>
				<input class="swing" id="iNm" name="iNm" type="text" maxlength="99" autocomplete="off" placeholder="اسم الحساب" required /><label for="iNm">الاسم</label>
			</span>
			<span>
				<input class="swing" id="iMax" name="iMax" type="number" maxlength="10" /><label for="iMax">المسموح</label>
			</span>
			<span>
				<input class="swing" id="iNts" name="iNts" type="text" maxlength="99" autocomplete="off" placeholder="وصف الحساب" /><label for="iNts">ملاحظات</label>
			</span>
			<span>
				<select class="swing" name="iGrp" id="iGrp">
					<?php
					$sql = "SELECT * FROM Groups WHERE gWrk=1 AND gGrpTyp = " . $_SESSION['iCstmrTyp'] . " ;";
					$result = getRows($sql);
					foreach ($result as $row) {
						echo "<option value=" . $row['gId'] . ">" . $row['gNm'] . "</option> ";
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