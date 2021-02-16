<?php
session_start();
if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
	include_once "init.php";
	include_once TPL . 'navbar.php';
	include_once TPL . 'slider.php';

	$pageTitle = " مجموعات ";
	$goOn = FALSE;

	for ($i = 0; $i < 6; $i++) {
		$srtSymbl[$i] = "    &#9670;";
	}
	//--------------------------------------------
	if ($_SERVER['REQUEST_METHOD'] == "POST") {
		$errs = array();
		if (isset($_POST['btnSave'])) {
			if (isset($_POST['iId'])) {
				if ($_POST['iId'] > 0) {
					if (isSaved("gId", "Groups", intval($_POST['iId']))) {
						if (empty(vldtVrbl($_POST['iNm']))) {
							$errs[] = " الاسم فارغ";
						} elseif (isSaved("gNm", "Groups", vldtVrbl($_POST['iNm']), " AND gId<>" . vldtVrbl($_POST['iId']))) {
							$errs[] = " الاسم مكرر ";
						} else {
							$sql = "UPDATE Groups SET gNm=?, gNts=? WHERE gId=? ;";
							$vl = array(vldtVrbl($_POST['iNm']), vldtVrbl($_POST['iNts']), intval($_POST['iId']));
							$report = "تم حفظ التعديلات";
							$goOn = TRUE;
						}
					} else {
						$errs[] = "تم حذفه بواسطة مستخدم آخر";
					}
				} elseif ($_POST['iId'] == '0') {
					if (empty(vldtVrbl($_POST['iNm']))) {
						$errs[] = " الاسم فارغ";
					} elseif (isSaved("gNm", "Groups", vldtVrbl($_POST['iNm']))) {
						$errs[] = " الاسم مكرر ";
					} else {
						$sql = "INSERT INTO Groups (gNm, gNts, gGrpTyp) VALUES (?,?,?) ;";
						$vl = array(vldtVrbl($_POST['iNm']), vldtVrbl($_POST['iNts']), vldtVrbl($_POST['iGrp']));
						$report = "تم إضافة بيان جديد";
						$goOn = TRUE;
					}
				}
			} else {
				$errs[] = "خطأ غير متوقع";
			}
		} elseif (isset($_POST['btnDlt'])) {
			if (isset($_POST['iId']) && intval($_POST['iId']) > 100) {
				if (isSaved("gId", "Groups", intval($_POST['iId']))) {
					if (isSaved("gGrpTyp", "Groups", intval($_POST['iId']))) {
						$errs[] = "لايمكن الحذف ... يوجد مجموعات لهذه الفئة";
					} elseif (isSaved("aGrp", "Accounts", intval($_POST['iId']))) {
						$errs[] = "لايمكن الحذف ... يوجد حسابات لهذه المجموعة";
					} elseif (isSaved("gGrp", "Goods", intval($_POST['iId']))) {
						$errs[] = "لايمكن الحذف ... يوجد أصناف لهذه المجموعة";
					} elseif (isSaved("tGrp", "Types", intval($_POST['iId']))) {
						$errs[] = "لايمكن الحذف ... يوجد حسابات فرعية لهذه المجموعة";
					} elseif (isSaved("ssGrp", "Subsidairy", intval($_POST['iId']))) {
						$errs[] = "لايمكن الحذف ... يوجد فروع لهذه المجموعة";
					} else {
						$sql = "DELETE FROM Groups WHERE `gId`=? ;";
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
				$report = "لم يحدث شئ";
			}
		} else {
			$report = "";
			foreach ($errs as $err) {
				$report .= " # " . $err;
			}
		}
	} //==============================================================
	elseif ($_SERVER['REQUEST_METHOD'] == "GET") {
		if (isset($_GET['iGrpTyp']) && intval($_GET['iGrpTyp']) >= 0 && intval($_GET['iGrpTyp']) < 19) {

			$_SESSION['iGrpTyp'] = intval($_GET['iGrpTyp']);
		} elseif (isset($_SESSION['iGrpTyp'])) {
			//$_SESSION['iGrpTyp'] ;
		} else {
			$_SESSION['iGrpTyp'] = 1;
		}
		//------------------------------------------
		if (isset($_GET['iSrch'])) {
			$_SESSION['strSrch'] = vldtVrbl($_GET['iSrch']);
		} elseif (isset($_SESSION['strSrch'])) {
			//$_SESSION['strSrch'] ;
		} else {
			$_SESSION['strSrch'] = "";
		}
		//------------------------------------------
		if (isset($_GET['srt'])) {
			if (isset($_SESSION['srt']) && $_SESSION['srt'] === vldtVrbl($_GET['srt'])) {
				$_SESSION['srtKnd'] = 19314 - $_SESSION['srtKnd'];
			} else {
				$_SESSION['srt'] = vldtVrbl($_GET['srt']);
				$_SESSION['srtKnd'] = 9652;
			}
		} else {
			$_SESSION['srt'] = 5;
			$_SESSION['srtKnd'] = 9652;
			$_SESSION['strSrt'] = " ;";
		}
		//------------------------------------------
		switch ($_SESSION['srt']) {
			case 0:
				$_SESSION['strSrt'] = "ORDER BY gId";
				break;
			case 1:
				$_SESSION['strSrt'] = "ORDER BY gNm";
				break;
			case 2:
				$_SESSION['strSrt'] = "ORDER BY gNts";
				break;
			case 3:
				$_SESSION['strSrt'] = "ORDER BY gWrk";
				break;
			case 4:
				$_SESSION['strSrt'] = "ORDER BY gGrpTyp";
				break;
			case 5:
				$_SESSION['strSrt'] = "ORDER BY gAdDt";
				break;
			default:
				$_SESSION['srt'] = 5;
				$_SESSION['srtKnd'] = 9652;
				$_SESSION['strSrt'] = "ORDER BY gAdDt";
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
			<input class="srch" type="search" name="iSrch" placeholder="بحث بالاسم " value="<?php echo ($_SESSION['strSrch']); ?>">
		</form>
		<table class="mTbl" id="mTbl">
			<caption> <?php getTitle();
							echo getNameById("Groups", "g", $_SESSION['iGrpTyp']); ?> </caption>

			<thead>
				<tr>
					<th>م</th>
					<th class='hiddenCol'> <a href="?srt=0"> <?php echo ($srtSymbl[0]); ?> الكود </a> </th>
					<th> <a href="?srt=1"> <?php echo ($srtSymbl[1]); ?> الاسم </a> </th>
					<th> <a href="?srt=2"> <?php echo ($srtSymbl[2]); ?> ملاحظات </a> </th>
					<th> <a href="?srt=3"> <?php echo ($srtSymbl[3]); ?> نشط </a> </th>
					<th> <a href="?srt=4"> <?php echo ($srtSymbl[4]); ?> نوع المجموعة </a> </th>
					<th> <a href="?srt=5"> <?php echo ($srtSymbl[5]); ?> تاريخ الاضافة </a> </th>
				</tr>
			</thead>
			<tbody>
				<?php
				$rc = 0;
				$sql = "SELECT * FROM Groups WHERE gGrpTyp=" . vldtVrbl($_SESSION['iGrpTyp']) . " AND gNm like '%" . vldtVrbl($_SESSION['strSrch']) . "%' " . $_SESSION['strSrt'];
				$result = getRows($sql);
				foreach ($result as $row) {
					echo ("<tr> <td>" . ++$rc . "</td> <td class='hiddenCol'>" . $row['gId'] . "</td>
											<td>" . $row['gNm'] . "</td> 
											<td>" . $row['gNts'] . "</td> <td>" . $row['gWrk'] . "</td> 
											<td>" . $row['gGrpTyp'] . "</td> <td>" . $row['gAdDt'] . "</td></tr>");
				}
				echo ("<tr> <td>" . ++$rc . "</td> <td class='hiddenCol'>0</td> <td></td> <td></td> <td></td> <td>" . $_SESSION['iGrpTyp'] . "</td> <td></td> </tr>");
				?>
			</tbody>
			<tfoot>
				<tr>
					<th> * </th>
					<td class='hiddenCol'> </td>
					<td> ملاحظات </td>
					<td colspan="4"> </td>
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
				<input class="swing" id="iNm" name="iNm" type="text" maxlength="99" autocomplete="off" placeholder="اسم المجموعة أو الفئة" required /><label for="iNm">الاسم</label>
			</span>
			<span>
				<input class="swing" id="iNts" name="iNts" type="text" maxlength="99" autocomplete="off" placeholder="وصف للمجموعة أو الفئة" /><label for="iNts">ملاحظات</label>
			</span>
			<input class="" id="iGrp" name="iGrp" type="hidden" />
			<button class="btn" type="submit" name="btnSave"> حفظ </button>
			<button class="btn" type="submit" name="btnDlt" id="btnDlt"> حذف </button>
		</form>
	</div>
	<!-------------------------------- Script ---------------------------------->
	<script src="layout/js/main.js"></script>
	<script>
		var x;
		var tbl01 = document.getElementById("mTbl");
		for (x = 1; x < tbl01.rows.length - 1; x = x + 1) {
			tbl01.rows[x].ondblclick = function() {
				document.getElementById('iId').value = this.cells[1].innerHTML;
				document.getElementById('iNm').value = this.cells[2].innerHTML;
				document.getElementById('iNts').value = this.cells[3].innerHTML;
				document.getElementById('iGrp').value = this.cells[5].innerHTML;
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