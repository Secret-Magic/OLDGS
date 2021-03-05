<?php
session_start();
if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
	include_once "init.php";

	$pageTitle = "المدفوعات";
	$goOn = FALSE;

	for ($i = 0; $i < 11; $i++) {
		$srtSymbl[$i] = "   &#9670;";
	}
	//--------------------------------------------
	if ($_SERVER['REQUEST_METHOD'] == "POST") {
		if (isset($_POST['btnSave'])) {
			if (isset($_POST['iId'])) {
				if ($_POST['iId'] > 0) {
					if (isSaved("pId", "Payments", intval($_POST['iId']))) {
						if (empty(vldtVrbl($_POST['iCstmr']))) {
							$errs[] = " الاسم فارغ";
						} else {
							$sql = "UPDATE Payments SET pDt=?, pCstmr=?,pVl=?, pStr=?, pNts=?  WHERE `pId`=? ;";
							$vl = array(vldtVrbl($_POST['iDt']), vldtVrbl($_POST['iCstmr']), vldtVrbl($_POST['iVl']), vldtVrbl($_POST['iStr']), vldtVrbl($_POST['iNts']), vldtVrbl($_POST['iId']));
							$report = "تم حفظ التعديلات";
							$goOn = TRUE;
						}
					} else {
						$errs[] = "تم حذفه بواسطة مستخدم آخر";
					}
				} elseif ($_POST['iId'] == '0') {
					if (FALSE) {
						$err[] = "";
					} else {
						$sql = "INSERT INTO Payments (pDt, pCstmr, pVl, pStr, pNts) VALUES (?,?,?,?,?) ;";
						$vl = array(vldtVrbl($_POST['iDt']), vldtVrbl($_POST['iCstmr']), vldtVrbl($_POST['iVl']), vldtVrbl($_POST['iStr']), vldtVrbl($_POST['iNts']));
						$report = "تم إضافة بيان جديد";
						$goOn = TRUE;
					}
				}
			} else {
				$errs[] = "خطأ غير متوقع";
			}
		} elseif (isset($_POST['btnDlt'])) {
			if (isset($_POST['iId']) && intval($_POST['iId']) > 0) {
				if (isSaved("pId", "Payments", intval($_POST['iId']))) {
					if (FALSE) {
						$errs[] = "";
					} else {
						$sql = "DELETE FROM Payments WHERE `pId`=? ;";
						$vl = array(vldtVrbl($_POST['iId']));
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
				$_SESSION['strSrt'] = "ORDER BY `pId`";
				break;
			case 1:
				$_SESSION['strSrt'] = "ORDER BY pDt";
				break;
			case 2:
				$_SESSION['strSrt'] = "ORDER BY pCstmr";
				break;
			case 3:
				$_SESSION['strSrt'] = "ORDER BY aNm";
				break;
			case 4:
				$_SESSION['strSrt'] = "ORDER BY pVl";
				break;
			case 5:
				$_SESSION['strSrt'] = "ORDER BY pStr";
				break;
			case 6:
				$_SESSION['strSrt'] = "ORDER BY aNm2";
				break;
			case 7:
				$_SESSION['strSrt'] = "ORDER BY pNts";
				break;
			default:
				$_SESSION['strSrt'] = "ORDER BY `pDt`";
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
					<th> م </th>
					<th class='hiddenCol'> <a href="?srt=0"> <?php echo ($srtSymbl[0]); ?> الكود </a> </th>
					<th> <a href="?srt=1"> <?php echo ($srtSymbl[1]); ?> التاريخ </a> </th>
					<th class='hiddenCol'> <a href="?srt=2"> <?php echo ($srtSymbl[2]); ?> كود الحساب </a> </th>
					<th> <a href="?srt=3"> <?php echo ($srtSymbl[3]); ?> الحساب </a> </th>
					<th> <a href="?srt=4"> <?php echo ($srtSymbl[4]); ?> المبلغ </a> </th>
					<th class='hiddenCol'> <a href="?srt=5"> <?php echo ($srtSymbl[5]); ?> كود الخزينة </a> </th>
					<th> <a href="?srt=6"> <?php echo ($srtSymbl[6]); ?> الخزينة </a> </th>
					<th> <a href="?srt=7"> <?php echo ($srtSymbl[7]); ?> ملاحظات </a> </th>
				</tr>
			</thead>
			<tbody>
				<?php
				$rc = 0;
				$sql = "SELECT * FROM Payments INNER JOIN Accounts ON pCstmr=aId WHERE (aSub=0 OR aSub=" . $_SESSION['Sub'] . ") AND aNm like '%" . vldtVrbl($_SESSION['strSrch']) . "%' " . $_SESSION['strSrt'];
				$result = getRows($sql);
				foreach ($result as $row) {
					echo ("<tr> <td>" . ++$rc . "</td> <td class='hiddenCol'>" . $row['pId'] . "</td>
										<td>" . $row['pDt'] . "</td> <td class='hiddenCol'>" . $row['pCstmr'] . "</td>
										<td>" . $row['aNm'] . "</td> <td>" . $row['pVl'] . "</td> <td class='hiddenCol'>" . $row['pStr'] . "</td> 
										<td>" . getNameById('accounts', 'a',  $row['pStr']) . "</td> <td>" . $row['pNts'] . "</td> </tr>");
				}
				echo ("<tr> <td>" . ++$rc . "</td> <td class='hiddenCol'>0</td> <td></td> <td class='hiddenCol'></td> <td></td> <td></td> <td class='hiddenCol'></td> <td></td> <td></td>	</tr>");
				?>
			</tbody>
			<tfoot>
				<tr>
					<th> * </th>
					<td> </td>
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
				<input class="swing" id="iDt" name="iDt" type="date" style="padding-right: 100px;" required /><label for="iDt">التاريخ</label>
			</span>
			<span>
				<select class="swing" name="iCstmr" id="iCstmr" placeholder="اسم العميل">
					<?php
						for ($i=2 ; $i <=8 ; $i++) {
							echo "<optgroup label='" . getNameById("Groups", "g", $i) . ":'>";
							$sql = "SELECT * FROM accounts WHERE aWrk=1 AND (aSub=0 OR aSub = " . $_SESSION['Sub'] . ") AND aGrp IN (SELECT gId FROM Groups WHERE gGrpTyp= " .$i . ") ;";
							$result = getRows($sql);
							foreach ($result as $row) {
								echo ("<option value=" . $row['aId'] . ">" . $row['aNm'] . "</option> ");
							}
							echo "</optgroup>";
						}
					?>
				</select><label for="iCstmr"> الاسم </label>
			</span>
			<span>
				<input class="swing" id="iVl" name="iVl" type="number" required /><label for="iVl">المبلغ</label>
			</span>
			<span>
				<select class="swing" name="iStr" id="iStr" placeholder="اسم الخزينة">
					<?php
						for ($i=2 ; $i <=8 ; $i++) {
							echo "<optgroup label='" . getNameById("Groups", "g", $i) . ":'>";
							$sql = "SELECT * FROM accounts WHERE aWrk=1 AND (aSub=0 OR aSub = " . $_SESSION['Sub'] . ") AND aGrp IN (SELECT gId FROM Groups WHERE gGrpTyp= " .$i . ") ;";
							$result = getRows($sql);
							foreach ($result as $row) {
								echo ("<option value=" . $row['aId'] . ">" . $row['aNm'] . "</option> ");
							}
							echo "</optgroup>";
						}
					?>
				</select><label for="iStr"> الخزينة </label>
			</span>
			<span>
				<input class="swing" id="iNts" name="iNts" type="text" maxlength="99" autocomplete="off" placeholder="وصف للعملية" /><label for="iNts">ملاحظات</label>
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
				document.getElementById('iVl').value = this.cells[5].innerHTML;
				document.getElementById('iNts').value = this.cells[8].innerHTML;
				if (this.cells[1].innerHTML == 0) {
					var iniDay = new Date();
					document.getElementById('iDt').value = iniDay.toISOString().substr(0, 10);
					document.getElementById('iCstmr').selectedIndex = "0";
					document.getElementById('iStr').selectedIndex = "0";
				} else {
					document.getElementById('iDt').value = this.cells[2].innerHTML;
					document.getElementById('iCstmr').value = this.cells[3].innerHTML;
					document.getElementById('iStr').value = this.cells[6].innerHTML;
				}
				showInptScrn();
			}
		}

		function isValidForm() {
			var f = document.getElementById('myfooter');
			var minDt = new Date("2020");
			var maxDt = new Date();
			/*
				if (isVldInpt('iNm', 'ادخل الاسم') ) {
					var x = document.getElementById('iFrstNmbr');
					if (isSafeInteger(x.valueOf)) {
						var x = document.getElementById('iFrstDt');
                  if (isNaN(Date.parse(x.value)) && x.value > minDt && x.value <= maxDt) {
                     return TRUE ;
                  }
                  else {
                     f.innerHTML = " أدخل التاريخ";
                     return FALSE ;
                  }
					}
					else {
						f.innerHTML = " رقم صحيح أكبر من الصفر";
						// x.setAttribute('placeholder', f.innerHTML) ;
						return FALSE ;
					}
				}
				else {
					return FALSE ;
				}*/
		}
	</script>
<?php
	include_once TPL . 'footer.php';
} else {
	header("location: index.php");
	exit();
}
?>