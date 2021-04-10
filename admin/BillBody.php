<?php
session_start();
if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
	include_once "init.php";

	$pageTitle = "محتوى الفاتورة";

	for ($i = 0; $i < 11; $i++) {
		$srtSymbl[$i] = "   &#9670;";
	}
	//--------------------------------------------
	if ($_SERVER['REQUEST_METHOD'] == "POST") {
		$errs = array();
		$report = "";
		$iss = 0;
		$goOn = FALSE;

		$tmpId = intval($_POST['iId']);
		$tmpBll = intval($_SESSION['iBll']);
		$tmpStr = intval($_POST['iStr']);
		$tmpGds = intval($_POST['iGds']);
		$tmpQntty = intval($_POST['iQntty']);
		$tmpPrc = floatval($_POST['iPrc']);
		$tmpDscnt = floatval($_POST['iDscnt']);
		$tmpNts = vldtVrbl($_POST['iNts']);

		if (isset($_POST['btnSave'])) {
			if (isset($tmpId)) {
				if ($tmpId > 0) {
					if (isSaved("bbId", "BillBody", $tmpId)) {
						if (isSaved("bbBllHdr", "BillBody", $tmpBll, "AND bbStr =  " . $tmpStr . " AND bbGds = " . $tmpGds . " AND bbId != " . $tmpId)) {
							$errs[] = "هذا الصنف مسجل من قبل فى هذا المخزن";
						} else {
							$sql = "UPDATE BillBody SET bbGds=?, bbStr=?, bbQntty=?, bbPrc=?, bbDscnt=?, bbNts=?  WHERE `bbId`=? ;";
							$vl = array($tmpGds, $tmpStr, $tmpQntty, $tmpPrc, $tmpDscnt, $tmpNts, $tmpId);
							$report = "تم حفظ التعديلات";
							$goOn = TRUE;
						}
					} else {
						$errs[] = "تم حذفه بواسطة مستخدم آخر";
					}
				} elseif ($tmpId == '0') {
					if (empty($tmpStr)) {
						$errs[] = "اختر المخزن";
					} elseif (empty($tmpGds)) {
						$errs[] = "اختر الصنف";
					} elseif (isSaved("bbBllHdr", "BillBody", $tmpBll, "AND bbStr =  " . $tmpStr . " AND bbGds = " . $tmpGds)) {
						$errs[] = "هذا الصنف مسجل من قبل فى هذا الخزن";
					} else {
						$sql = "INSERT INTO BillBody (bbBllHdr, bbGds, bbStr, bbQntty, bbPrc,bbDscnt, bbNts) VALUES (?,?,?,?,?,?,?) ;";
						$vl = array($tmpBll, $tmpGds, $tmpStr, $tmpQntty, $tmpPrc, $tmpDscnt, $tmpNts);
						$report = "تم إضافة بيان جديد";
						$goOn = TRUE;
					}
				}
			} else {
				$errs[] = "خطأ غير متوقع";
			}
		} elseif (isset($_POST['btnDlt'])) {
			if (isset($tmpId) && $tmpId > 0) {
				$sql = "DELETE FROM BillBody WHERE `bbId`=? ;";
				$vl = array($tmpId);
				$report = "تم حذفه";
				$goOn = TRUE;
			} else {
				$report = " غير مسموح بالحذف";
			}
		}
		if ($goOn) {
			if (getRC($sql, $vl) > 0) {
				
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
		if (isset($_GET['iBll'])) {
			$_SESSION['iBll'] = vldtVrbl($_GET['iBll']);
		} elseif (isset($_SESSION['iBll'])) {
			//$_SESSION['strSrch'] ;
		} else {
			$_SESSION['iBll'] = 1;
			$report = "لم يتم تحديد نوع الفاتورة بشكل صحيح" ;
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
				$_SESSION['strSrt'] = "ORDER BY `bbId`";
				break;
			case 1:
				$_SESSION['strSrt'] = "ORDER BY `bbBllHdr`";
				break;
			case 2:
				$_SESSION['strSrt'] = "ORDER BY bbStr";
				break;
			case 3:
				$_SESSION['strSrt'] = "ORDER BY aNm";
				break;
			case 4:
				$_SESSION['strSrt'] = "ORDER BY bbGds";
				break;
			case 5:
				$_SESSION['strSrt'] = "ORDER BY gNm";
				break;
			case 6:
				$_SESSION['strSrt'] = "ORDER BY bbQntty";
				break;
			case 7:
				$_SESSION['strSrt'] = "ORDER BY bbPrc";
				break;
			case 8:
				$_SESSION['strSrt'] = "ORDER BY bbDscnt";
				break;
			case 9:
				$_SESSION['strSrt'] = "ORDER BY bbNts";
				break;
			case 10:
				$_SESSION['strSrt'] = "ORDER BY bbNts";
				break;
			default:
				$_SESSION['strSrt'] = "ORDER BY `bbId`";
				break;
		}
		$_SESSION['strSrt'] .= ($_SESSION['srtKnd'] == 9652) ? " ASC ;" : " DESC ;";
	} else {
		$report = "لا أظن يمكن تحقيقه";
	}
	$srtSymbl[$_SESSION['srt']] = " &#" . $_SESSION['srtKnd'] . "; ";
?>
	<!-------------------------------- Table ---------------------------------->
	<div class="dvTbl" id="dvTbl">
		<table class="mTbl">
			<thead>
				<tr>
					<th class='hiddenCol'> الكود </th>
					<th> رقم الفاتورة </th>
					<th> نوع الفاتورة </th>
					<th class='hiddenCol'> كود الاسم </th>
					<th> الاسم </th>
					<th> التاريخ </th>
					<th> ملاحظات </th>
				</tr>
			</thead>
			<tbody>
				<?php
					$sql = "SELECT * FROM BillHeader INNER JOIN Accounts ON bhNm=AId WHERE bhSub= " . $_SESSION['Sub'] . " AND bhId=? ";
					$vl = array($_SESSION['iBll']);
					$row = getRow($sql, $vl);
					$tmpKnd = ($row['bhKnd'] == 1 || $row['bhKnd'] == 9);
					echo ("<tr> 
								<td class='hiddenCol'>" . $row['bhId'] . "</td> <td>" . $row['bhNmbr'] . "</td><td>" . getNameById("Types", "t", $row['bhKnd']) . "</td>
								<td class='hiddenCol'>" . $row['bhNm'] . "</td>  <td>" . $row['aNm'] . "</td> 
								<td>" . $row['bhDt'] . "</td> <td>" . $row['bhNts'] . "</td>
							</tr>");
				?>
			</tbody>
		</table>
		<table class="mTbl" id="mTbl">
			<caption> <?php getTitle(); ?> </caption>
			<thead>
				<tr>
					<th>م</th>
					<th class='hiddenCol'><a href="?srt=0"> <?php echo ($srtSymbl[0]); ?> كود العملية </a> </th>
					<th class='hiddenCol'><a href="?srt=1"> <?php echo ($srtSymbl[1]); ?> كود الفاتورة </a> </th>
					<th class='hiddenCol'><a href="?srt=2"> <?php echo ($srtSymbl[2]); ?> كود المخزن </a> </th>
					<th><a href="?srt=3"> <?php echo ($srtSymbl[3]); ?> اسم المخزن </a> </th>
					<th class='hiddenCol'><a href="?srt=4"> <?php echo ($srtSymbl[4]); ?> كود الصنف </a> </th>
					<th><a href="?srt=5"> <?php echo ($srtSymbl[5]); ?> اسم الصنف </a> </th>
					<th><a href="?srt=6"> <?php echo ($srtSymbl[6]); ?> الكمية </a> </th>
					<th><a href="?srt=7"> <?php echo ($srtSymbl[7]); ?> السعر </a> </th>
					<th><a href="?srt=8"> <?php echo ($srtSymbl[8]); ?> الخصم </a> </th>
					<th><a href="?srt=9"> <?php echo ($srtSymbl[9]); ?> الاجمالى </a> </th>
					<th><a href="?srt=10"> <?php echo ($srtSymbl[10]); ?> ملاحظات </a> </th>
				</tr>
			</thead>
			<tbody>
				<?php
				$rc = 0; $sumPrc = 0; $sumAll = 0;
				$sql = "SELECT * FROM BillBody INNER JOIN Accounts ON bbStr=AId INNER JOIN Goods ON bbGds=gId WHERE bbBllHdr = " . vldtVrbl($_SESSION['iBll']) . "  " . $_SESSION['strSrt'];
				$result = getRows($sql);
				foreach ($result as $row) {
					$sumPrc = $row['bbPrc'] * $row['bbQntty'] - $row['bbDscnt'] ;
					$sumAll = $sumAll + $sumPrc ;
					echo ("<tr>
								<td>" . ++$rc . "</td> <td class='hiddenCol'>" . $row['bbId'] . "</td> <td class='hiddenCol'>" . $row['bbBllHdr'] . " </td>
								<td class='hiddenCol'>" . $row['bbStr'] . "</td> <td>" . $row['aNm'] . " </td> 
								<td class='hiddenCol'>" . $row['bbGds'] . "</td> <td>" . $row['gNm'] . " </td>
								<td>" . $row['bbQntty'] . "</td> <td>" . $row['bbPrc'] . "</td> 
								<td>" . $row['bbDscnt'] . "</td> <td>" . $sumPrc . "</td> <td>" . $row['bbNts'] . "</td> 
							</tr>");
				}
				echo ("<tr> <td>" . ++$rc . "</td> <td class='hiddenCol'>0</td> <td class='hiddenCol'></td> <td class='hiddenCol'></td><td></td> <td class='hiddenCol'></td><td></td> <td></td> <td></td><td></td> <td></td> <td></td>	</tr>");
				?>
			</tbody>
			<tfoot>
				<tr>
					<th> * </th>
					<td class='hiddenCol'> </td>
					<td class='hiddenCol'> </td>
					<td class='hiddenCol'> </td>
					<td> ملاحظات </td>
					<td colspan="7"> <?php echo ($sumAll); ?> </td>
				</tr>
			</tfoot>
		</table>
	</div>
	<!-------------------------------- Input Screen ---------------------------->
	<div class="row" id="row">
		<div class="btnClose" id="btnClose"> &#10006; </div>
		<form action="<?php echo ($_SERVER['PHP_SELF']); ?>" method="post" name="iFrm" id="iFrm">
			<input class="" id="iId" name="iId" type="hidden" value="<?php echo($tmpId) ?>" />
			<input class="" id="iBll" name="iBll" type="hidden" value="<?php echo($tmpBll) ?>" />
			<span>
				<select class="swing" name="iStr" id="iStr" placeholder="اسم المخزن">
					<?php
					if ($tmpKnd) {
						$sql = "SELECT * FROM Accounts WHERE aSub=" . $_SESSION['Sub'] . " AND aGrp IN (SELECT gId FROM Groups WHERE gGrpTyp BETWEEN 9 AND 11)";
					} else {
						$sql = "SELECT * FROM Accounts WHERE aSub=" . $_SESSION['Sub'] . " AND aGrp IN (SELECT gId FROM Groups WHERE gGrpTyp BETWEEN 10 AND 11)";
					}
					$result = getRows($sql);
					foreach ($result as $row) {
						echo ("<option value=" . $row['aId'] . ">" . $row['aNm'] . "</option> ");
					}
					?>
				</select><label for="iStr"> المخزن </label>
			</span>
			<span>
				<select class="swing" name="iGds" id="iGds" placeholder="اسم الصنف">
					<?php
					if ($tmpKnd) {
						$sql = "SELECT * FROM Goods ;";
					} else {
						$sql = "SELECT * FROM Goods WHERE gGrp IN (SELECT gId FROM Groups WHERE gGrpTyp!=12) ;";
					}
					$result = getRows($sql);
					foreach ($result as $row) {
						$sl = "";
						if ($row['gId'] == $tmpGds) {
							$sl = " selected ";
						}
						echo ("<option value='" . $row['gId']. "'". $sl . ">" . $row['gNm'] . "</option> ");
					}
					?>
				</select><label for="iGds"> الصنف </label>
			</span>
			<!-- قائمة لجلب سعر الصنف المختار  -->
			<select class="hiddenCol" name="tmp" id="tmp" >
				<?php
					foreach ($result as $row) {
						$sl = "";
						if ($row['gId'] == $tmpGds) {
							$sl = " selected ";
						}
						echo ("<option value='" . $row['gId']. "'". $sl . ">" . $row['gSllPrc']. "</option> ");
					}
				?>
			</select>
			<span>
				<input class="swing" id="iQntty" name="iQntty" type="number" min="0" maxlength="10" placeholder="كمية الصنف" autocomplete="off" value="<?php echo($tmpQntty) ?>" /><label for="iQntty">الكمية</label>
			</span>
			<span>
				<input class="swing" id="iPrc" name="iPrc" type="text" autocomplete="off" placeholder="سعر الصنف" value="<?php echo($tmpPrc) ?>" pattern="[0-9]+(\.[0-9]*)?" /><label for="iPrc">السعر</label>
			</span>
			<span>
				<input class="swing" id="iDscnt" name="iDscnt" type="text" autocomplete="off" placeholder="قيمة الخصم على الصنف" value="<?php echo($tmpDscnt) ?>" pattern="[0-9]+(\.[0-9]*)?" /><label for="iDscnt">الخصم</label>
			</span>
			<span>
				<input class="swing" id="iNts" name="iNts" type="text" maxlength="99" autocomplete="off" placeholder="وصف الصنف" value="<?php echo($tmpNts) ?>" /><label for="iNts">ملاحظات</label>
			</span>

			<button class="btn" type="submit" name="btnSave"> حفظ </button>
			<button class="btn" type="submit" name="btnDlt" id="btnDlt"> حذف </button>
		</form>
	</div>
	<!-------------------------------- Script ---------------------------------->
	<script>
		var x;
		var tbl01 = document.getElementById("mTbl");
		var gdsId = document.getElementById('iGds'),tmp = document.getElementById('tmp') ;

		gdsId.onchange = function () {
			tmp.selectedIndex = this.selectedIndex ;
			document.getElementById('iPrc').value = tmp.item(tmp.selectedIndex).text ;
		}
		for (x = 1; x < tbl01.rows.length - 1; x = x + 1) {
			tbl01.rows[x].ondblclick = function() {
				document.getElementById('iId').value = this.cells[1].innerHTML;
				document.getElementById('iBll').value = this.cells[2].innerHTML;
				document.getElementById('iQntty').value = this.cells[7].innerHTML;
				
				document.getElementById('iDscnt').value = this.cells[9].innerHTML;
				document.getElementById('iNts').value = this.cells[11].innerHTML;
				if (document.getElementById('iId').value ==0) {
					document.getElementById('iStr').selectedIndex = 0;
					document.getElementById('iGds').selectedIndex = 0;
					document.getElementById('iPrc').value = tmp.item(tmp.selectedIndex).text ;
				} else {
					document.getElementById('iStr').value = this.cells[3].innerHTML;
					document.getElementById('iGds').value = this.cells[5].innerHTML;
					document.getElementById('iPrc').value = this.cells[8].innerHTML;
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