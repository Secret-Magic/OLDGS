<?php
ob_start();
session_start();
if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
	include_once "init.php";
	include_once TPL . 'navbar.php';
	include_once TPL . 'slider.php';

	$pageTitle = "أنواع المجموعات";
	$goOn = FALSE;

	for ($i = 0; $i < 5; $i++) {
		$srtSymbl[$i] = "   &#9670;";
	}
	//--------------------------------------------
	if ($_SERVER['REQUEST_METHOD'] == "POST") {
		$errs = array();
		if (isset($_POST['btnSave'])) {
			if (isset($_POST['iId'])) {
				if ($_POST['iId'] > 0) {
					if (isSaved("gtId", "GroupsType", intval($_POST['iId']))) {
						if (empty(vldtVrbl($_POST['iNm']))) {
							$errs[] = " الاسم فارغ";
						} elseif (isSaved("gtNm", "GroupsType", vldtVrbl($_POST['iNm']), " AND gtId<>" . vldtVrbl($_POST['iId']))) {
							$errs[] = " الاسم مكرر ";
						} else {
							$sql = "UPDATE GroupsType SET gtNm=?, gtNts=? WHERE gtId=? ;";
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
					} elseif (isSaved("gtNm", "GroupsType", vldtVrbl($_POST['iNm']))) {
						$errs[] = " الاسم مكرر ";
					} else {
						$sql = "INSERT INTO GroupsType (gtNm, gtNts) VALUES (?,?) ;";
						$vl = array(vldtVrbl($_POST['iNm']), vldtVrbl($_POST['iNts']));
						$report = "تم إضافة بيان جديد";
						$goOn = TRUE;
					}
				}
			} else {
				$errs[] = "خطأ غير متوقع";
			}
		} elseif (isset($_POST['btnDlt'])) {
			if (isset($_POST['iId']) && intval($_POST['iId']) > 17) {
				if (isSaved("gtId", "GroupsType", intval($_POST['iId']))) {
					if (isSaved("gGrpTyp", "Groups", intval($_POST['iId']))) {
						$errs[] = "يوجد مجموعات لهذه الفئة";
					} else {
						$sql = "DELETE FROM GroupsType WHERE `gtId`=? ;";
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
		if (isset($_GET['iSrch'])) {
			$_SESSION['strSrch'] = vldtVrbl($_GET['iSrch']);
		} elseif (isset($_SESSION['strSrch'])) {
			//$_SESSION['strSrch'] ;
		} else {
			$_SESSION['strSrch'] = "";
		}
		//------------------------------------------
		if (isset($_GET['srt'])) {
			if (isset($_SESSION['srt']) && $_SESSION['srt'] === intval($_GET['srt'])) {
				$_SESSION['srtKnd'] = 19314 - $_SESSION['srtKnd'];
			} else {
				$_SESSION['srt'] = intval($_GET['srt']);
				$_SESSION['srtKnd'] = 9652;
			}
		} else {
			$_SESSION['srt'] = 4;
			$_SESSION['srtKnd'] = 9652;
			$_SESSION['strSrt'] = " ;";
		}
		//------------------------------------------
		switch ($_SESSION['srt']) {
			case 0:
				$_SESSION['strSrt'] = "ORDER BY gtId";
				break;
			case 1:
				$_SESSION['strSrt'] = "ORDER BY gtNm";
				break;
			case 2:
				$_SESSION['strSrt'] = "ORDER BY gtNts";
				break;
			case 3:
				$_SESSION['strSrt'] = "ORDER BY gtWrk";
				break;
			case 4:
				$_SESSION['strSrt'] = "ORDER BY gtAdDt";
				break;
			default:
				$_SESSION['srt'] = 4;
				$_SESSION['srtKnd'] = 9652;
				$_SESSION['strSrt'] = "ORDER BY gtAdDt";
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
			<caption> <?php getTitle(); ?> </caption>
			<thead>
				<tr>
					<th>م</th>
					<th> <a href="?srt=0"> <?php echo ($srtSymbl[0]); ?> الكود </a> </th>
					<th> <a href="?srt=1"> <?php echo ($srtSymbl[1]); ?> الاسم </a> </th>
					<th> <a href="?srt=2"> <?php echo ($srtSymbl[2]); ?> ملاحظات </a> </th>
					<th> <a href="?srt=3"> <?php echo ($srtSymbl[3]); ?> نشط </a> </th>
					<th> <a href="?srt=4"> <?php echo ($srtSymbl[4]); ?> تاريخ الاضافة </a> </th>

				</tr>
			</thead>
			<tbody>
				<?php
				$rc = 0;
				$sql = "SELECT * FROM GroupsType WHERE gtNm like '%" . vldtVrbl($_SESSION['strSrch']) . "%' " . $_SESSION['strSrt'];
				$result = getRows($sql);
				foreach ($result as $row) {
					echo ("<tr> <td>" . ++$rc . "</td> 
									<td>" . $row['gtId'] . "</td>
									<td>" . $row['gtNm'] . "</td> 
									<td>" . $row['gtNts'] . "</td> <td>" . $row['gtWrk'] . "</td> <td>" . $row['gtAdDt'] . "</td></tr>");
				}
				echo ("<tr> <td>" . ++$rc . "</td> <td>0</td> <td></td> <td></td> <td></td> <td></td> </tr>");
				?>
			</tbody>
			<tfoot>
				<tr>
					<th> * </th>
					<td> </td>
					<td> ملاحظات </td>
					<td colspan="3"> </td>
				</tr>
			</tfoot>
		</table>
	</div>
	<!-------------------------------- Input Screen ---------------------------->
	<!-- <div class="inptScrn" id="inptScrn" style="<?php if (!$goOn) {
																		echo 'display:block;';
																	} ?> "> -->
	<div class="inptScrn" id="inptScrn">
		<div class="btnClose" id="btnClose"> &#10006; </div>
		<form action="<?php echo ($_SERVER['PHP_SELF']); ?>" method="post" name="iFrm" onsubmit="return isValidForm()">
			<input class="inpt" type="hidden" name="iId" id="iId" />
			<label class="lbl" for="iNm">اسم نوع المجموعة : </label>
			<input class="inpt" type="text" name="iNm" id="iNm" maxlength="99" required />
			<label class="lbl" for="iNts"> ملاحظات: </label>
			<input class="inpt" type="text" name="iNts" id="iNts" maxlength="99" />

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
ob_end_flush();
?>