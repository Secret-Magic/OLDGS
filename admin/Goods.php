<?php
session_start();
if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
	include_once "init.php";

	for ($i = 0; $i < 12; $i++) {
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
		$tmpByLmt = intval($_POST['iByLmt']);
		$tmpByPrc = floatval($_POST['iByPrc']);
		$tmpSllPrc = floatval($_POST['iSllPrc']);
		$tmpNts = vldtVrbl($_POST['iNts']);
		$tmpGrp = vldtVrbl($_POST['iGrp']);
		
		if (isset($_POST['btnSave'])) {
			if (isset($tmpId)) {
				if ($tmpId > 0) {
					if (isSaved("gId", "Goods", $tmpId)) {
						if (empty($tmpNm)) {
							$errs[] = " الاسم فارغ";
						} elseif (isSaved("gNm", "Goods", $tmpNm, " AND gId <> " . $tmpId)) {
							$errs[] = " الاسم مكرر ";
						} elseif (!($tmpByLmt >= 0)) {
							$errs[] = "حد الشراء غير صحيح";
						} elseif (!($tmpByPrc >= 0)) {
							$errs[] = "سعر الشراء غير صحيح";
						} elseif (!($tmpSllPrc >= 0)) {
							$errs[] = "سعر البيع غير صحيح";
						} else {
							$sql = "UPDATE Goods SET gNm=?, gByLmt=?, gByPrc=?, gSllPrc=?, gNts=?, gGrp=? WHERE `gId`=? ;";
							$vl = array($tmpNm, $tmpByLmt, $tmpByPrc, $tmpSllPrc, $tmpNts, $tmpGrp, $tmpId);
							$report = "تم حفظ التعديلات";
							$goOn = TRUE;
						}
					} else {
						$errs[] = "تم حذفه بواسطة مستخدم آخر";
					}
				} elseif ($_POST['iId'] == '0') {
					if (empty($tmpNm)) {
						$errs[] = " الاسم فارغ";
					} elseif (isSaved("gNm", "Goods", $tmpNm)) {
						$errs[] = " الاسم مكرر ";
					} elseif (!($tmpByLmt >= 0)) {
						$errs[] = "حد الشراء غير صحيح";
					} elseif (!($tmpByPrc >= 0)) {
						$errs[] = "سعر الشراء غير صحيح";
					} elseif (!($tmpSllPrc >= 0)) {
						$errs[] = "سعر البيع غير صحيح";
					} else {
						$sql = "INSERT INTO Goods (gNm, gByLmt, gByPrc, gSllPrc, gNts, gGrp) VALUES (?,?,?,?,?,?) ;";
						$vl = array($tmpNm, $tmpByLmt, $tmpByPrc, $tmpSllPrc, $tmpNts, $tmpGrp);
						$report = "تم إضافة بيان جديد";
						$goOn = TRUE;
					}
				}
			} else {
				$errs[] = "خطأ غير متوقع";
			}
		} elseif (isset($_POST['btnDlt'])) {
			if (isset($tmpId) && $tmpId > 5) {
				if (isSaved("gId", "Goods", $tmpId)) {
					if (isSaved("pGds", "Pumps", $tmpId)) {
						$errs[] = "يوجد طلمبة مرتبطة بهذا الوقود";
					} else {
						$sql = "DELETE FROM Goods WHERE `gId`=? ;";
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
		if (isset($_GET['iGdTyp'])) {
			$_SESSION['iGdTyp'] = vldtVrbl($_GET['iGdTyp']);
		} elseif (isset($_SESSION['iGdTyp'])) {
			//$_SESSION['iGdTyp'] ;
		} else {
			$_SESSION['iGdTyp'] = 14;
			$report = "لم يتم تحديد نوع الصنف بشكل صحيح" ;
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
				$_SESSION['strSrt'] = "ORDER BY gId";
				break;
			case 1:
				$_SESSION['strSrt'] = "ORDER BY gNm";
				break;
			case 2:
				$_SESSION['strSrt'] = "ORDER BY gByLmt";
				break;
			case 3:
				$_SESSION['strSrt'] = "ORDER BY gByPrc";
				break;
			case 4:
				$_SESSION['strSrt'] = "ORDER BY gSllPrc";
				break;
			case 5:
				$_SESSION['strSrt'] = "ORDER BY gNts";
				break;
			case 6:
				$_SESSION['strSrt'] = "ORDER BY gWrk";
				break;
			case 7:
				$_SESSION['strSrt'] = "ORDER BY gGrp";
				break;
			case 8:
				$_SESSION['strSrt'] = "ORDER BY gAdDt";
				break;
			default:
				$_SESSION['strSrt'] = "ORDER BY gAdDt";
				break;
		}
		$_SESSION['strSrt'] .= ($_SESSION['srtKnd'] == 9652) ? " ASC ;" : " DESC ;";
	} else {
		$report = "لا أظن يمكن تحقيقه";
	}
	$srtSymbl[$_SESSION['srt']] = " &#" . $_SESSION['srtKnd'] . "; ";
	$pageTitle = str_replace("فئة", 'الأصناف :', getNameById('Groups', 'g', $_SESSION['iGdTyp']));
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
					<th> م </th>
					<th class='hiddenCol'> <a href="?srt=0"> <?php echo ($srtSymbl[0]); ?> الكود </a> </th>
					<th> <a href="?srt=1"> <?php echo ($srtSymbl[1]); ?> الاسم </a> </th>
					<th> <a href="?srt=2"> <?php echo ($srtSymbl[2]); ?> حد الشراء </a> </th>
					<th> <a href="?srt=3"> <?php echo ($srtSymbl[3]); ?> سعر الشراء </a> </th>
					<th> <a href="?srt=4"> <?php echo ($srtSymbl[4]); ?> سعر البيع </a> </th>
					<th> <a href="?srt=5"> <?php echo ($srtSymbl[5]); ?> ملاحظات </a> </th>
					<th> <a href="?srt=6"> <?php echo ($srtSymbl[6]); ?> نشط </a> </th>
					<th> <a href="?srt=7"> <?php echo ($srtSymbl[7]); ?> مجموعة </a> </th>
					<th> <a href="?srt=8"> <?php echo ($srtSymbl[8]); ?> تاريخ الانشاء </a> </th>
				</tr>
			</thead>
			<tbody>
				<?php
				$rc = 0;
				$sql = "SELECT * FROM Goods WHERE gGrp IN (SELECT gId FROM Groups WHERE gGrpTyp=" . vldtVrbl($_SESSION['iGdTyp']) . ") AND gNm like '%" . vldtVrbl($_SESSION['strSrch']) . "%' " . $_SESSION['strSrt'];
				$result = getRows($sql);
				foreach ($result as $row) {
					echo ("<tr> <td>" . ++$rc . "</td> <td class='hiddenCol'>" . $row['gId'] . "</td>	<td>" . $row['gNm'] . "</td> 
						<td>" . $row['gByLmt'] . "</td><td>" . $row['gByPrc'] . "</td><td>" . $row['gSllPrc'] . "</td> 
						<td>" . $row['gNts'] . "</td>	<td>" . $row['gWrk'] . "</td> <td>" . $row['gGrp'] . "</td> <td>" . $row['gAdDt'] . "</td> </tr>");
				}
				echo ("<tr> <td>" . ++$rc . "</td> <td class='hiddenCol'>0</td> <td></td> <td></td><td></td> <td></td> <td></td> <td></td> <td></td> <td></td>	</tr>");
				?>
			</tbody>
			<tfoot>
				<tr>
					<th> * </th>
					<td class='hiddenCol'> </td>
					<td> ملاحظات </td>
					<td colspan="7"> </td>
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
				<input class="swing" id="iNm" name="iNm" type="text" maxlength="99" autocomplete="off" placeholder="اسم الصنف" value="<?php echo($tmpNm) ?>" required /><label for="iNm">الاسم</label>
			</span>
			<span>
				<input class="swing" id="iByLmt" name="iByLmt" type="number" maxlength="10" placeholder="حد الطلب أو حد الشراء" value="<?php echo($tmpByLmt) ?>" /><label for="iByLmt">حد الشراء</label>
			</span>
			<span>
				<input class="swing" id="iByPrc" name="iByPrc" type="text" maxlength="10" placeholder="سعر الشراء" value="<?php echo($tmpByPrc) ?>" required /><label for="iByPrc">شراء</label>
			</span>
			<span>
				<input class="swing" id="iSllPrc" name="iSllPrc" type="text" maxlength="10" placeholder="سعر البيع" value="<?php echo($tmpSllPrc) ?>" required /><label for="iSllPrc">بيع</label>
			</span>

			<span>
				<select class="swing" name="iGrp" id="iGrp" placeholder="نوع المجموعة">
					<?php
						$sql = "SELECT * FROM Groups WHERE gWrk=1 AND gGrpTyp = " . vldtVrbl($_SESSION['iGdTyp']) . " ;";
						$result = getRows($sql);
						foreach ($result as $row) {
							$sl = "";
							if ($row['gId'] == $tmpGrp) {
								$sl = " selected ";
							}
							echo ("<option value='" . $row['gId']. "'". $sl. " >" . $row['gNm'] . "</option> ");
						}
					?>
				</select><label for="iGrp"> المجموعة </label>
			</span>
			<span>
				<input class="swing" id="iNts" name="iNts" type="text" maxlength="99" autocomplete="off" placeholder="وصف للصنف" value="<?php echo($tmpNts) ?>" /><label for="iNts">ملاحظات</label>
			</span>

			<button class="btn" type="submit" name="btnSave"> حفظ </button>
			<button class="btn" type="submit" name="btnDlt" id="btnDlt"> حذف </button>
		</form>
	</div>
	<!-------------------------------- Script ---------------------------------->
	<script>
		var frm1 = document.forms[1];
		
		frm1.onsubmit = function (e){
			// e.preventDefault();
			// alert ("soso") ;
		}

		// frm1.addEventListener("onsubmit" , function (e){
		// 	e.preventDefault () ;
		// }) ;
		var x, tbl01 = document.getElementById("mTbl");
		for (x = 1; x < tbl01.rows.length - 1; x = x + 1) {
			tbl01.rows[x].ondblclick = function() {
				document.getElementById('iId').value = this.cells[1].innerHTML;
				document.getElementById('iNm').value = this.cells[2].innerHTML;
				document.getElementById('iByLmt').value = this.cells[3].innerHTML;
				document.getElementById('iByPrc').value = this.cells[4].innerHTML;
				document.getElementById('iSllPrc').value = this.cells[5].innerHTML;
				document.getElementById('iNts').value = this.cells[6].innerHTML;
				if (this.cells[1].innerHTML == 0) {
					document.getElementById("iGrp").selectedIndex = "0";
				} else {
					document.getElementById('iGrp').value = this.cells[8].innerHTML;
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