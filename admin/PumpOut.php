<?php
session_start();
if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
	include_once "init.php";

	$pageTitle = "خرج الطلمبات";

	for ($i = 0; $i < 9; $i++) {
		$srtSymbl[$i] = "   &#9670;";
	}
	//--------------------------------------------
	if ($_SERVER['REQUEST_METHOD'] == "POST") {
		$errs = array();
		$report = "";
		$iss = 0;
		$goOn = FALSE;

		$tmpId = intval($_POST['iId']);
		$tmpDly = intval($_POST['iDly']);
		$tmpPmp = intval($_POST['iPmp']);
		$tmpPmpNm = vldtVrbl($_POST['iPmpNm']);
		$tmpNmbr = intval($_POST['iNmbr']);
		$tmpQnttyBck = intval($_POST['iQnttyBck']);
		$tmpTnk = intval($_POST['iTnk']);
		$tmpNts = vldtVrbl($_POST['iNts']);

		if (isset($_POST['btnSave'])) {
			if (isset($tmpId)) {
				if ($tmpId > 0) {
					if (isSaved("poId", "PumpOut", $tmpId)) {
						if (isSaved("bhSub", "BillHeader", $_SESSION['Sub'], " AND bhKnd=8 AND bhId > " . $tmpDly)) {
							$errs[] = "يتعذر التعديل لأنه تم تسجيل وردية بعدها";
							/*نسمح للمدير بالتعديل */
						} else {
							$sql = "SELECT MAX(poNmbr) AS mNm FROM `pumpout` WHERE poDly != " . $_POST['iDly'] . " AND `poPmp`= ?";
							$vl = array($tmpPmp);
							$row = getRow($sql, $vl);
							$mNm = $row['mNm'];
							if ($mNm == NULL) {
								$sql = "SELECT * FROM `Pumps` WHERE pId =? ";
								$vl = array($tmpPmp);
								$row = getRow($sql, $vl);
								$mNm = $row['pFrstNmbr'];
							}
							if ($_POST['iNmbr'] >= $mNm) {
								$sql = "UPDATE PumpOut SET  poNmbr=?, poQnttyBck=?,poTnk=?, poNts=?  WHERE `poId`=? ;";
								$vl = array($tmpNmbr, $tmpQnttyBck, $tmpTnk, $tmpNts, $tmpId);
								$report = "تم حفظ التعديلات";
								$goOn = TRUE;
							} else {
								$errs[] = " العداد السرى أقل من البداية";
							}
						}
					} else {
						$errs[] = "تم حذفه بواسطة مستخدم آخر";
					}
				}
			} else {
				$errs[] = "خطأ غير متوقع";
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
		if (isset($_GET['iDly'])) {
			$_SESSION['iDly'] = vldtVrbl($_GET['iDly']);
		} elseif (isset($_SESSION['iDly'])) {
			//$_SESSION['strSrch'] ;
		} else {
			$_SESSION['iDly'] = 1;
			$report = "لم يتم تحديد الوردية بشكل صحيح" ;
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
			$_SESSION['srt'] = 3;
			$_SESSION['srtKnd'] = 9652;
			$_SESSION['strSrt'] = " ;";
		}
		//------------------------------------------
		switch ($_SESSION['srt']) {
			case 0:
				$_SESSION['strSrt'] = "ORDER BY `poId`";
				break;
			case 1:
				$_SESSION['strSrt'] = "ORDER BY `poDly`";
				break;
			case 2:
				$_SESSION['strSrt'] = "ORDER BY poPmp";
				break;
			case 3:
				$_SESSION['strSrt'] = "ORDER BY pNm";
				break;
			case 4:
				$_SESSION['strSrt'] = "ORDER BY poNmbr";
				break;
			case 5:
				$_SESSION['strSrt'] = "ORDER BY poQnttyBck";
				break;
			case 6:
				$_SESSION['strSrt'] = "ORDER BY poTnk";
				break;
			case 7:
				$_SESSION['strSrt'] = "ORDER BY aNm";
				break;
			case 8:
				$_SESSION['strSrt'] = "ORDER BY poNts";
				break;
			default:
				$_SESSION['strSrt'] = "ORDER BY `pNm`";
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
					<th> رقم اليومية </th>
					<th class='hiddenCol'> كود الاسم </th>
					<th> الاسم </th>
					<th> التاريخ </th>
					<th> ملاحظات </th>
				</tr>
			</thead>
			<tbody>
				<?php
				$sql = "SELECT * FROM BillHeader INNER JOIN Accounts ON bhNm=aId WHERE bhSub= " . $_SESSION['Sub'] . " AND bhId=? ";
				$vl = array($_SESSION['iDly']);
				$row = getRow($sql, $vl);
				echo ("<tr> <td class='hiddenCol'>" . $row['bhId'] . "</td> <td>" . $row['bhNmbr'] . "</td>
						<td class='hiddenCol'>" . $row['bhNm'] . "</td><td>" . $row['aNm'] . "</td> <td>" . $row['bhDt'] . "</td>
						<td>" . $row['bhNts'] . "</td> </tr>");
				?>
			</tbody>
		</table>
		<table class="mTbl" id="mTbl">
			<caption> <?php getTitle(); ?> </caption>
			<thead>
				<tr>
					<th>م</th>
					<th class='hiddenCol'><a href="?srt=0"> <?php echo ($srtSymbl[0]); ?> كودالعملية </a> </th>
					<th class='hiddenCol'><a href="?srt=1"> <?php echo ($srtSymbl[1]); ?> كود اليومية </a> </th>
					<th class='hiddenCol'><a href="?srt=2"> <?php echo ($srtSymbl[2]); ?> كود الطلمبة </a> </th>
					<th><a href="?srt=3"> <?php echo ($srtSymbl[3]); ?> اسم الطلمبة </a> </th>
					<th><a href="?srt=4"> <?php echo ($srtSymbl[4]); ?> العداد السرى </a> </th>
					<th><a href="?srt=5"> <?php echo ($srtSymbl[5]); ?> المرتجع </a> </th>
					<th class='hiddenCol'><a href="?srt=6"> <?php echo ($srtSymbl[6]); ?> كود التانك </a> </th>
					<th><a href="?srt=7"> <?php echo ($srtSymbl[7]); ?> التانك </a> </th>
					<th><a href="?srt=8"> <?php echo ($srtSymbl[8]); ?> ملاحظات </a> </th>
				</tr>
			</thead>
			<tbody>
				<?php
				$rc = 0;
				$sql = "SELECT * FROM PumpOut INNER JOIN Pumps ON poPmp=pId INNER JOIN Accounts ON poTnk=aId WHERE poDly = " . vldtVrbl($_SESSION['iDly']) . "  " . $_SESSION['strSrt'];
				$result = getRows($sql);
				foreach ($result as $row) {
					echo ("<tr> <td>" . ++$rc . "</td> <td class='hiddenCol'>" . $row['poId'] . "</td> <td class='hiddenCol'>" . $row['poDly'] . "</td>
							<td class='hiddenCol'>" . $row['poPmp'] . "</td> <td>" . $row['pNm'] . "</td> 
							<td>" . $row['poNmbr'] . "</td> <td>" . $row['poQnttyBck'] . "</td>
							<td class='hiddenCol'>" . $row['poTnk'] . "</td> <td>" . $row['aNm'] . "</td> 
							<td>" . $row['poNts'] . "</td> </tr>");
				}
				//echo ("<tr> <td>". ++$rc. "</td> <td class='hiddenCol'>0</td> <td class='hiddenCol'></td> <td class='hiddenCol'></td> <td></td><td></td> <td></td> <td></td> <td></td> <td></td>	</tr>");
				?>
			</tbody>
			<tfoot>
				<tr>
					<th> * </th>
					<td class='hiddenCol'> </td>
					<td class='hiddenCol'> </td>
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
		<form action="<?php echo ($_SERVER['PHP_SELF']); ?>" method="post" name="iFrm" onsubmit="return isValidForm()">
			<input class="" id="iId" name="iId" type="hidden" value="<?php echo($tmpId) ?>" />
			<input class="" id="iDly" name="iDly" type="hidden" value="<?php echo($tmpDly) ?>" />
			<input class="" id="iPmp" name="iPmp" type="hidden"  value="<?php echo($tmpPmp) ?>" />

			<span>
				<input class="swing" id="iPmpNm" name="iPmpNm" type="text" maxlength="99" autocomplete="off" placeholder="اسم الطلمبة" value="<?php echo($tmpPmpNm) ?>" readonly /><label for="iPmpNm">الطلمبة</label>
			</span>
			<span>
				<input class="swing" id="iNmbr" name="iNmbr" type="number" maxlength="10" placeholder="العداد السرى" value="<?php echo($tmpNmbr) ?>" required /><label for="iNmbr">العداد</label>
			</span>
			<span>
				<input class="swing" id="iQnttyBck" name="iQnttyBck" type="number" placeholder="كمية المرتجع" value="<?php echo($tmpQnttyBck) ?>" required /><label for="iQnttyBck">المرتجع</label>
			</span>
			<span>
				<select class="swing" name="iTnk" id="iTnk" placeholder="اسم التانك">
					<?php
					$sql = "SELECT * FROM Accounts WHERE aSub=" . $_SESSION['Sub'] . " AND aGrp IN (SELECT gId FROM Groups WHERE gGrpTyp=9) ;";
					$result = getRows($sql);
					foreach ($result as $row) {
						$sl = "";
						if ($row['aId'] == $tmpTnk) {
							$sl = " selected ";
						}
						echo ("<option value='" . $row['aId']."'". $sl . ">" . $row['aNm'] . "</option> ");
					}
					?>
				</select><label for="iTnk"> التانك </label>
			</span>
			<span>
				<input class="swing" id="iNts" name="iNts" type="text" maxlength="99" autocomplete="off" placeholder="وصف للطلمبة" value="<?php echo($tmpNts) ?>" /><label for="iNts">ملاحظات</label>
			</span>

			<button class="btn" type="submit" name="btnSave"> حفظ </button>
			<!-- <button class="btn" type="submit" name="btnDlt" id="btnDlt"> حذف </button> -->
		</form>
	</div>
	<!-------------------------------- Script ---------------------------------->
	<script>
		var x;
		var tbl01 = document.getElementById("mTbl");
		for (x = 1; x < tbl01.rows.length - 1; x = x + 1) {
			tbl01.rows[x].ondblclick = function() {
				document.getElementById('iId').value = this.cells[1].innerHTML;
				document.getElementById('iDly').value = this.cells[2].innerHTML;
				document.getElementById('iPmp').value = this.cells[3].innerHTML;
				document.getElementById('iPmpNm').value = this.cells[4].innerHTML;
				document.getElementById('iNmbr').value = this.cells[5].innerHTML;
				document.getElementById('iQnttyBck').value = this.cells[6].innerHTML;
				document.getElementById('iTnk').value = this.cells[7].innerHTML;
				document.getElementById('iNts').value = this.cells[9].innerHTML;
				showInptScrn();
				// document.getElementById('btnDlt').style.display = "none";
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