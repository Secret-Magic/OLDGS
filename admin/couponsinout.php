<?php
session_start();
if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
	include_once "init.php";
	include_once TPL . 'navbar.php';
	include_once TPL . 'slider.php';

	$pageTitle = "البونات";
	$goOn = FALSE;

	for ($i = 0; $i < 9; $i++) {
		$srtSymbl[$i] = "   &#9670;";
	}
	//--------------------------------------------
	if ($_SERVER['REQUEST_METHOD'] == "POST") {
		if (isset($_POST['btnSave'])) {
			
			if (isset($_POST['iId'])) {
				if ($_POST['iId'] > 0) {
					if (isSaved("cioId", "CouponInOut", intval($_POST['iId']))) {
						if (isSaved("bhSub", "BillHeader", $_SESSION['Sub'], " AND bhKnd=11 AND bhId > " . $_SESSION['iDly'])) {
							$errs[] = "يتعذر التعديل لأنه تم تسجيل وردية بعدها";
							/*نسمح للمدير بالتعديل */
						} else {
							$sql = "UPDATE CouponInOut SET  cioCpnId=?, cioQntty=?,cioPrc=?, cioDscnt=?, cioNts=?  WHERE `cioId`=? ;";
							$vl = array(vldtVrbl($_POST['iCpnId']), vldtVrbl($_POST['iQntty']), vldtVrbl($_POST['iPrc']), vldtVrbl($_POST['iDscnt']), vldtVrbl($_POST['iNts']), vldtVrbl($_POST['iId']));
							$report = "تم حفظ التعديلات";
							$goOn = TRUE;
						}
					} else {
						$errs[] = "تم حذفه بواسطة مستخدم آخر";
					}
				} elseif ($_POST['iId'] == '0') {
					
					$sql = "INSERT INTO CouponInOut (cioSrcId, cioCpnId, cioQntty, cioPrc, cioDscnt, cioNts) VALUES (?,?,?,?,?,?) ;";
					$vl = array( $_SESSION['iDly'], vldtVrbl($_POST['iCpnId']), vldtVrbl($_POST['iQntty']), vldtVrbl($_POST['iPrc']), vldtVrbl($_POST['iDscnt']) , vldtVrbl($_POST['iNts'])) ;
					$report = "تم إضافة بيان جديد";
					$goOn = True;
					
				}
			} else {
				$errs[] = "خطأ غير متوقع";
			} 
		} elseif (isset($_POST['btnDlt'])) {
				if (isset($_POST['iId']) && intval($_POST['iId']) > 0) {
					if (isSaved("cioId", "CouponInOut", intval($_POST['iId']))) {
						
						$sql = "DELETE FROM CouponInOut WHERE `cioId`=? ;";
						$vl = array(vldtVrbl($_POST['iId']));
						$report = "تم حذفه";
						$goOn = TRUE;
						
					} else {
						$c[] = "تم حذفه بواسطة مستخدم آخر";
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
		if (isset($_GET['iDly'])) {
			$_SESSION['iDly'] = vldtVrbl($_GET['iDly']);
		} elseif (isset($_SESSION['iDly'])) {
			//$_SESSION['strSrch'] ;
		} else {
			$_SESSION['iDly'] = 1;
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
				$_SESSION['strSrt'] = "ORDER BY `cioId`";
				break;
			case 1:
				$_SESSION['strSrt'] = "ORDER BY `cioCpnId`";
				break;
			case 2:
				$_SESSION['strSrt'] = "ORDER BY cioQntty";
				break;
			case 3:
				$_SESSION['strSrt'] = "ORDER BY cioPrc";
				break;
			case 4:
				$_SESSION['strSrt'] = "ORDER BY cioDscnt";
				break;
			case 5:
				$_SESSION['strSrt'] = "ORDER BY cioNts";
				break;
			default:
				$_SESSION['strSrt'] = "ORDER BY `cioId`";
				break;
		}
		$_SESSION['strSrt'] .= ($_SESSION['srtKnd'] == 9652) ? " ASC ;" : " DESC ;";
	} else {
		echo ("خطأ غير متوقع");
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
					<th class='hiddenCol'><a href="?srt=2"> <?php echo ($srtSymbl[2]); ?> كود البون </a> </th>
					<th><a href="?srt=3"> <?php echo ($srtSymbl[3]); ?> اسم البون </a> </th>
					<th><a href="?srt=4"> <?php echo ($srtSymbl[4]); ?> الكمية </a> </th>
					<th><a href="?srt=5"> <?php echo ($srtSymbl[5]); ?> السعر </a> </th>
					<th><a href="?srt=6"> <?php echo ($srtSymbl[6]); ?> الخصم </a> </th>
					<th><a href="?srt=7"> <?php echo ($srtSymbl[7]); ?> ملاحظات </a> </th>
				</tr>
			</thead>
			<tbody>
				<?php
				$rc = 0;
				$sql = "SELECT * FROM CouponInOut INNER JOIN Coupons ON cioCpnId=cId WHERE cioSrcId = " . vldtVrbl($_SESSION['iDly']) . "  " . $_SESSION['strSrt'];
				$result = getRows($sql);
				foreach ($result as $row) {
					echo ("<tr> <td>" . ++$rc . "</td> <td class='hiddenCol'>" . $row['cioId'] . "</td> <td class='hiddenCol'>" . $row['cioSrcId'] . "</td>
							<td class='hiddenCol'>" . $row['cioCpnId'] . "</td> <td>" . $row['cNm'] . "</td> 
							<td>" . $row['cioQntty'] . "</td> <td>" . $row['cioPrc'] . "</td>
							<td>" . $row['cioDscnt'] . "</td> <td>" . $row['cioNts'] . "</td> </tr>");
				}
				echo ("<tr> <td>". ++$rc. "</td> <td class='hiddenCol'>0</td> <td class='hiddenCol'></td> <td class='hiddenCol'></td> <td></td><td></td> <td></td> <td></td> <td></td>	</tr>");
				?>
			</tbody>
			<tfoot>
				<tr>
					<th> * </th>
					<td class='hiddenCol'> </td>
					<td class='hiddenCol'> </td>
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
			<input class="" id="iDly" name="iDly" type="hidden" />

			<span>
				<select class="swing" name="iCpnId" id="iCpnId" placeholder="اسم البون">
					<?php
						$sql = "SELECT * FROM Coupons WHERE cWrk=1 ;";
						$result = getRows($sql);
						foreach ($result as $row) {
							echo ("<option value=" . $row['cId'] . ">" . $row['cNm'] . "</option> ");
						}
					?>
				</select><label for="iCpnId"> اسم البون </label>
			</span>
			<!-- قائمة لجلب سعر البون المختار  -->
			<select class="hiddenCol" name="tmp" id="tmp" >
				<?php
					$sql = "SELECT * FROM Coupons WHERE cWrk=1 ;";
					$result = getRows($sql);
					foreach ($result as $row) {
						echo ("<option value=" . $row['cId'] . ">" . $row['cByPrc']. "</option> ");
					}
				?>
			</select>
			<span>
				<input class="swing" id="iQntty" name="iQntty" type="number" maxlength="10" autocomplete="off" placeholder="الكمية أو العدد" /><label for="iQntty">الكمية</label>
			</span>
			<span>
				<input class="swing" id="iPrc" name="iPrc" type="number" maxlength="10" autocomplete="off" placeholder="قيمة البون" required /><label for="iPrc">القيمة</label>
			</span>
			<span>
				<input class="swing" id="iDscnt" name="iDscnt" type="number" maxlength="10" autocomplete="off" placeholder="قيمة الخصم" /><label for="iDscnt">الخصم</label>
			</span>
			<span>
				<input class="swing" id="iNts" name="iNts" type="text" maxlength="99" autocomplete="off" placeholder="ملاحظات" /><label for="iNts">ملاحظات</label>
			</span>

			<button class="btn" type="submit" name="btnSave"> حفظ </button>
			<button class="btn" type="submit" name="btnDlt" id="btnDlt"> حذف </button>
		</form>
	</div>
	<!-------------------------------- Script ---------------------------------->
	<script>
		var x, tbl01 = document.getElementById("mTbl");
		var cpnId = document.getElementById('iCpnId'),tmp = document.getElementById('tmp') ;

		cpnId.onchange = function () {
			tmp.selectedIndex = this.selectedIndex ;
			document.getElementById('iPrc').value = tmp.item(tmp.selectedIndex).text ;
		}

		for (x = 1; x < tbl01.rows.length - 1; x = x + 1) {
			tbl01.rows[x].ondblclick = function() {
				document.getElementById('iId').value = this.cells[1].innerHTML;
				document.getElementById('iDly').value = this.cells[2].innerHTML;
				document.getElementById('iQntty').value = this.cells[5].innerHTML;
				document.getElementById('iDscnt').value = this.cells[7].innerHTML;
				document.getElementById('iNts').value = this.cells[8].innerHTML;
				if (document.getElementById('iId').value ==0) {
					cpnId.selectedIndex = 0
					document.getElementById('iPrc').value = tmp.item(tmp.selectedIndex).text ;
				} else {
					cpnId.value = this.cells[3].innerHTML;
					document.getElementById('iPrc').value = this.cells[6].innerHTML;
				}
				// tmp.selectedIndex = cpnId.selectedIndex ;
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