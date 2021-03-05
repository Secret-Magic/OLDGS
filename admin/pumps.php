<?php
session_start();
if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
	include_once "init.php";

	$pageTitle = "الطلمبات";

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
		$tmpNm = vldtVrbl($_POST['iNm']);
		$tmpFrstNmbr = vldtVrbl($_POST['iFrstNmbr']);
		$tmpFrstDt = vldtVrbl($_POST['iFrstDt']);
		$tmpTnk = vldtVrbl($_POST['iTnk']);
		$tmpGds = vldtVrbl($_POST['iGds']);
		$tmpNts = vldtVrbl($_POST['iNts']);

		if (isset($_POST['btnSave'])) {
			if (isset($tmpId)) {
				if ($tmpId > 0) {
					if (isSaved("pId", "Pumps", $tmpId)) {
						if (empty($tmpNm)) {
							$errs[] = " الاسم فارغ";
						} elseif (isSaved("pNm", "Pumps", $tmpNm, " AND pId <> " . $tmpId)) {
							$errs[] = " الاسم مكرر ";
						} elseif (!($tmpFrstNmbr > 0)) {
							$errs[] = " رقم صحيح أكبر من الصفر ";
						} elseif (empty($tmpFrstDt)) {
							$errs[] = " أدخل التاريخ";
						} else {
							$sql = "UPDATE Pumps SET pNm=?, pFrstNmbr=?,pFrstDt=?, pTnk=?,pGds=?, pNts=?  WHERE `pId`=? ;";
							$vl = array($tmpNm, $tmpFrstNmbr, $tmpFrstDt, $tmpTnk, $tmpGds, $tmpNts, $tmpId);
							$report = "تم حفظ التعديلات";
							$goOn = TRUE;
						}
					} else {
						$errs[] = "تم حذفه بواسطة مستخدم آخر";
					}
				} elseif ($tmpId == '0') {
					if (empty($tmpNm)) {
						$errs[] = " الاسم فارغ";
					} elseif (isSaved("pNm", "Pumps", $tmpNm)) {
						$errs[] = " الاسم مكرر ";
					} elseif (!($tmpFrstNmbr > 0)) {
						$errs[] = " رقم صحيح أكبر من الصفر ";
					} elseif (empty($tmpFrstDt)) {
						$errs[] = " أدخل التاريخ";
					} else {
						$sql = "INSERT INTO Pumps (pNm, pFrstNmbr, pFrstDt, pTnk, pGds, pNts, pSub) VALUES (?,?,?,?,?,?,?) ;";
						$vl = array($tmpNm, $tmpFrstNmbr, $tmpFrstDt, $tmpTnk, $tmpGds, $tmpNts, $_SESSION['Sub']);
						$report = "تم إضافة بيان جديد";
						$goOn = TRUE;
					}
				}
			} else {
				$errs[] = "خطأ غير متوقع";
			}
		} elseif (isset($_POST['btnDlt'])) {
			if (isset($tmpId) && $tmpId > 0) {
				if (isSaved("pId", "Pumps", $tmpId)) {
					if (isSaved("poPmp", "Pumpout", $tmpId)) {
						$errs[] = "هذه الطلمية مسجلة فى الوردية";
					} else {
						$sql = "DELETE FROM Pumps WHERE `pId`=? ;";
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
			$_SESSION['srt'] = 0;
			$_SESSION['srtKnd'] = 9652;
			$_SESSION['strSrt'] = " ;";
		}
		//------------------------------------------
		switch ($_SESSION['srt']) {
			case 0:
				$_SESSION['strSrt'] = "ORDER BY `pId`";
				break;
			case 1:
				$_SESSION['strSrt'] = "ORDER BY pNm";
				break;
			case 2:
				$_SESSION['strSrt'] = "ORDER BY pFrstNmbr";
				break;
			case 3:
				$_SESSION['strSrt'] = "ORDER BY pFrstDt";
				break;
			case 4:
				$_SESSION['strSrt'] = "ORDER BY pTnk";
				break;
			case 5:
				$_SESSION['strSrt'] = "ORDER BY aNm";
				break;
			case 6:
				$_SESSION['strSrt'] = "ORDER BY pGds";
				break;
			case 7:
				$_SESSION['strSrt'] = "ORDER BY gNm";
				break;
			case 8:
				$_SESSION['strSrt'] = "ORDER BY pNts";
				break;
			case 9:
				$_SESSION['strSrt'] = "ORDER BY pWrk";
				break;
			case 10:
				$_SESSION['strSrt'] = "ORDER BY pAdDt";
				break;
			default:
				$_SESSION['strSrt'] = "ORDER BY `pAdDt`";
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
					<th> <a href="?srt=1"> <?php echo ($srtSymbl[1]); ?> الاسم </a> </th>
					<th> <a href="?srt=2"> <?php echo ($srtSymbl[2]); ?> العداد السرى </a> </th>
					<th> <a href="?srt=3"> <?php echo ($srtSymbl[3]); ?> التاريخ </a> </th>
					<th class='hiddenCol'> <a href="?srt=4"> <?php echo ($srtSymbl[4]); ?> كود التانك </a> </th>
					<th> <a href="?srt=5"> <?php echo ($srtSymbl[5]); ?> التانك </a> </th>
					<th class='hiddenCol'> <a href="?srt=6"> <?php echo ($srtSymbl[6]); ?> كود الوقود </a> </th>
					<th> <a href="?srt=7"> <?php echo ($srtSymbl[7]); ?> نوع الوقود </a> </th>
					<th> <a href="?srt=8"> <?php echo ($srtSymbl[8]); ?> ملاحظات </a> </th>
					<th> <a href="?srt=9"> <?php echo ($srtSymbl[9]); ?> نشط </a> </th>
					<th> <a href="?srt=10"> <?php echo ($srtSymbl[10]); ?> تاربخ الانشاء </a> </th>
				</tr>
			</thead>
			<tbody>
				<?php
					$rc = 0;
					$sql = "SELECT * FROM Pumps INNER JOIN Accounts ON pTnk=AId INNER JOIN Goods ON pGds=gId WHERE pSub = " . $_SESSION['Sub'] . " AND pNm like '%" . vldtVrbl($_SESSION['strSrch']) . "%' " . $_SESSION['strSrt'];
					$result = getRows($sql);
					foreach ($result as $row) {
						echo ("<tr> <td>" . ++$rc . "</td> <td  class='hiddenCol'>" . $row['pId'] . "</td>
								<td>" . $row['pNm'] . "</td> <td>" . $row['pFrstNmbr'] . "</td>
								<td>" . $row['pFrstDt'] . "</td> <td class='hiddenCol'>" . $row['pTnk'] . "</td> <td>" . $row['aNm'] . "</td> 
								<td class='hiddenCol'>" . $row['pGds'] . "</td> <td>" . $row['gNm'] . "</td>
								<td>" . $row['pNts'] . "</td> <td>" . $row['pWrk'] . "</td> <td>" . $row['pAdDt'] . "</td> </tr>");
					}
					echo ("<tr> <td>" . ++$rc . "</td> <td class='hiddenCol'>0</td> <td></td> <td></td> <td></td> <td class='hiddenCol'></td> <td></td> <td class='hiddenCol'></td> <td></td> <td></td> <td></td> <td></td>	</tr>");
				?>
			</tbody>
			<tfoot>
				<tr>
					<th> * </th>
					<td class='hiddenCol'> </td>
					<td> ملاحظات </td>
					<td colspan="9"> </td>
				</tr>
			</tfoot>
		</table>
	</div>
	<!-------------------------------- Input Screen ---------------------------->
	<div class="row" id="row">
		<div class="btnClose" id="btnClose"> &#10006; </div>
		<form action="<?php echo ($_SERVER['PHP_SELF']); ?>" method="post" name="iFrm" id="iFrm" onsubmit="return isValidForm()">
			<input class="" id="iId" name="iId" type="hidden" value="<?php echo($tmpId); ?>" />
			<span>
				<input class="swing" id="iNm" name="iNm" type="text" maxlength="99" autocomplete="off" placeholder="اسم الطلمبة" value="<?php echo($tmpNm); ?>" required /><label for="iNm">الاسم</label>
			</span>
			<span>
				<input class="swing" id="iFrstNmbr" name="iFrstNmbr" type="number" maxlength="10" placeholder="العداد السرى" value="<?php echo($tmpFrstNmbr); ?>" required /><label for="iFrstNmbr">العداد</label>
			</span>
			<span>
				<input class="swing" style="padding-right: 100px;" id="iFrstDt" name="iFrstDt" type="date" value="<?php echo($tmpFrstDt); ?>" required /><label for="iFrstDt">التاريخ</label>
			</span>
			<span>
				<select class="swing" name="iTnk" id="iTnk" placeholder="اسم التانك">
					<?php
						$sql = "SELECT * FROM accounts WHERE aWrk=1 AND aSub = " . $_SESSION['Sub'] . " AND aGrp IN (SELECT gId FROM Groups WHERE gWrk=1 AND gGrpTyp=9) ;";
						$result = getRows($sql);
						foreach ($result as $row) {
							$sl = "";
							if ($row['aId'] ==  $tmpId) {
								$sl =" selected ";
							}
							echo ("<option value='" . $row['aId'] . "'". $sl. ">" . $row['aNm'] . "</option> ");
						}
					?>
				</select><label for="iTnk"> التانك </label>
			</span>
			<span>
				<select class="swing" name="iGds" id="iGds" placeholder="نوع الوقود">
					<?php
						$sql = "SELECT * FROM Goods WHERE gWrk=1 AND gGrp IN (SELECT gId FROM Groups WHERE gWrk=1 AND gGrpTyp=12) ;";
						$result = getRows($sql);
						foreach ($result as $row) {
							$sl = "";
							if ($row['gId'] ==  $tmpId) {
								$sl =" selected ";
							}
							echo ("<option value='" . $row['gId'] . "'". $sl .">" . $row['gNm'] . "</option> ");
						}
					?>
				</select><label for="iGds"> نوع الوقود </label>
			</span>
			<span>
				<input class="swing" id="iNts" name="iNts" type="text" maxlength="99" autocomplete="off" placeholder="وصف للطلمبة" <?php echo($tmpNts); ?> /><label for="iNts">ملاحظات</label>
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
				document.getElementById('iFrstNmbr').value = this.cells[3].innerHTML;
				document.getElementById('iFrstDt').value = this.cells[4].innerHTML;
				console.log(this.cells[4].innerHTML);
				document.getElementById('iNts').value = this.cells[9].innerHTML;
				if (this.cells[1].innerHTML == 0) {
					var iniDay = new Date();
					document.getElementById("iTnk").selectedIndex = "0";
					document.getElementById("iGds").selectedIndex = "0";
					document.getElementById('iFrstDt').value = iniDay.toISOString().substr(0, 10);;
				} else {
					document.getElementById('iTnk').value = this.cells[5].innerHTML;
					document.getElementById('iGds').value = this.cells[7].innerHTML;
				}
				showInptScrn();
			}
		}

		function isValidForm() {
			var f = document.getElementById('myfooter');
			var minDt = new Date("2020");
			var maxDt = new Date();

			if (isVldInpt('iNm', 'ادخل الاسم')) {
				var x = document.getElementById('iFrstNmbr');
				if (isSafeInteger(x.valueOf)) {
					var x = document.getElementById('iFrstDt');
					if (isNaN(Date.parse(x.value)) && x.value > minDt && x.value <= maxDt) {
						return TRUE;
					} else {
						f.innerHTML = " أدخل التاريخ";
						return FALSE;
					}
				} else {
					f.innerHTML = " رقم صحيح أكبر من الصفر";
					// x.setAttribute('placeholder', f.innerHTML) ;
					return FALSE;
				}
			} else {
				return FALSE;
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