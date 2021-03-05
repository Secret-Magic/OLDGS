<?php
session_start();
if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
	include_once "init.php";
	include_once TPL . 'navbar.php';
	include_once TPL . 'slider.php';

	$pageTitle = "بيانات الدخول";

	for ($i = 0; $i < 9; $i++) {
		$srtSymbl[$i] = "   &#9670;";
	}
	//--------------------------------------------
	if ($_SERVER['REQUEST_METHOD'] == "POST") {
		$errs = array();
		$report = "";
		$iss = 0;
		$goOn = FALSE;

		$tmpId		 = intval($_POST['iId']);
		$tmpUsrId 	 = intval($_POST['iUsrId']);
		$tmpUsr 		 = vldtVrbl($_POST['iUsr']);
		$tmpioPsswrd = $_POST['ioPsswrd'];
		$tmpinPsswrd = vldtVrbl($_POST['inPsswrd']);
		$tmpEml 		 = vldtVrbl($_POST['iEml']);
		$tmpMxLg 	 = intval($_POST['iMxLg']);

		if (isset($_POST['btnSave'])) {
			if (isset($tmpId)) {
				if ($tmpId > 0) {
					if (isSaved("ulId", "UserLog", $tmpId)) {
						if (isSaved("ulUsrId", "UserLog", $tmpUsrId, " AND ulId <> " . $tmpId)) {
							$errs[] = " اسم المستخدم مكرر ";
						} elseif (empty($tmpUsr)) {
							$errs[] = " اسم الدخول فارغ";
						} elseif (isSaved("ulUsr", "UserLog", $tmpUsr, " AND ulId <> " . $tmpId)) {
							$errs[] = " اسم الدخول مكرر ";
						} else {
							if (empty($tmpinPsswrd)) {
								$hashedpass = $_POST['ioPsswrd'];
							} else {
								$hashedpass = sha1($tmpinPsswrd);
							}
							$sql = "UPDATE UserLog SET ulUsrId=?, ulUsr=?, ulPsswrd=?, ulEml=?, ulMxLg=? WHERE ulId=? ";
							$vl = array($tmpUsrId, $tmpUsr, $hashedpass, $tmpEml, $tmpMxLg, $tmpId);
							$report = "تم حفظ التعديلات";
							$goOn = TRUE;
						}
					} else {
						$errs[] = "تم حذفه بواسطة مستخدم آخر";
					}
				} elseif ($tmpId == '0') {
					if (isSaved("ulUsrId", "UserLog", $tmpUsrId, " AND ulId <> " . $tmpId)) {
						$errs[] = " اسم المستخدم مكرر ";
					} elseif (empty($tmpUsr)) {
						$errs[] = " اسم الدخول فارغ";
					} elseif (isSaved("ulUsr", "UserLog", $tmpUsr)) {
						$errs[] = " اسم الدخول مكرر ";
					} elseif (isSaved("ulEml", "UserLog", $tmpEml)) {
						$errs[] = " البريد الالكترونى مكرر ";
					} elseif (empty($tmpinPsswrd)) {
						$errs[] = " كلمة السر فارغة";
					} else {
						$hashedpass = sha1($tmpinPsswrd);
						$sql = "INSERT INTO UserLog (ulUsrId, ulUsr, ulPsswrd, ulEml, ulMxLg) VALUES (?,?,?,?,?) ";
						$vl = array($tmpUsrId, $tmpUsr, $hashedpass, $tmpEml, $tmpMxLg);
						$report = "تم إضافة بيان جديد";
						$goOn = TRUE;
					}
				}
			} else {
				$errs[] = "خطأ غير متوقع";
			}
		} elseif (isset($_POST['btnDlt'])) {
			if (isset($tmpId) && $tmpId > 1) {
				if (isSaved("ulId", "UserLog", $tmpId)) {
					$sql = "DELETE FROM UserLog WHERE `ulId`=? ;";
					$vl = array($tmpId);
					$report = "تم حذفه";
					$goOn = TRUE;
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
				$_SESSION['strSrt'] = "ORDER BY ulId";
				break;
			case 1:
				$_SESSION['strSrt'] = "ORDER BY ulUsrId";
				break;
			case 2:
				$_SESSION['strSrt'] = "ORDER BY aNm";
				break;
			case 3:
				$_SESSION['strSrt'] = "ORDER BY ulUsr";
				break;
			case 4:
				$_SESSION['strSrt'] = "ORDER BY ulPsswrd";
				break;
			case 5:
				$_SESSION['strSrt'] = "ORDER BY ulEml";
				break;
			case 6:
				$_SESSION['strSrt'] = "ORDER BY ulMxLg";
				break;
			default:
				$_SESSION['strSrt'] = "ORDER BY ulId";
				break;
		}
		$_SESSION['strSrt'] .= ($_SESSION['srtKnd'] == 9652) ? " ASC ;" : " DESC ;";
	} else {
		echo ("خطأ غير متوقع");
	}
	$srtSymbl[$_SESSION['srt']] = " &#" . $_SESSION['srtKnd'] . "; ";
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
					<th>م</th>
					<th class='hiddenCol'> <a href="?srt=0"> <?php echo ($srtSymbl[0]); ?> الكود </a> </th>
					<th class='hiddenCol'> <a href="?srt=1"> <?php echo ($srtSymbl[1]); ?> كود المستخدم </a> </th>
					<th> <a href="?srt=2"> <?php echo ($srtSymbl[2]); ?> اسم المستخدم </a> </th>
					<th> <a href="?srt=3"> <?php echo ($srtSymbl[3]); ?> اسم الدخول </a> </th>
					<th> <a href="?srt=4"> <?php echo ($srtSymbl[4]); ?> كلمة المرور </a> </th>
					<th> <a href="?srt=5"> <?php echo ($srtSymbl[5]); ?> البريد الالكترونى </a> </th>
					<th> <a href="?srt=6"> <?php echo ($srtSymbl[6]); ?> حد الدخول </a> </th>
				</tr>
			</thead>
			<tbody>
				<?php
					$rc = 0;
					$sql = "SELECT * FROM UserLog INNER JOIN Accounts ON ulUsrId=aId WHERE (aSub=0 OR aSub=" . $_SESSION['Sub'] . ") AND aNm like '%" . vldtVrbl($_SESSION['strSrch']) . "%' " . $_SESSION['strSrt'];
					$result = getRows($sql);
					foreach ($result as $row) {
						echo ("<tr> <td>" . ++$rc . "</td> <td class='hiddenCol'>" . $row['ulId'] . "</td>
								<td class='hiddenCol'>" . $row['ulUsrId'] . "</td> <td>" . $row['aNm'] . "</td> 
								<td>" . $row['ulUsr'] . "</td> <td>" . $row['ulPsswrd'] . "</td> 
								<td>" . $row['ulEml'] . "</td> <td>" . $row['ulMxLg'] . "</td> </tr>");
					}
					echo ("<tr> <td>" . ++$rc . "</td> <td class='hiddenCol'>0</td> <td class='hiddenCol'></td> <td></td> <td></td> <td></td> <td></td> <td></td>	</tr>");
				?>
			</tbody>
			<tfoot>
				<tr>
					<th> * </th>
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
			<input class="" id="iId" name="iId" type="hidden" value="<?php echo($tmpId) ?>" />
			<span>
				<select class="swing" name="iUsrId" id="iUsrId">
					<?php
						for ($i=2; $i<5; $i++) {
							echo "<optgroup label='" . getNameById("Groups", "g", $i) . ":'>";
							$sql = "SELECT * FROM Accounts WHERE (aSub=0 || aSub = " . $_SESSION['Sub'] . " ) AND aWrk=1 AND aGrp IN (SELECT gId FROM Groups WHERE gGrpTyp =". $i. ") ;";
							$result = getRows($sql);
							foreach ($result as $row) {
								$sl = "";
								if ($row['aId'] == $tmpUsrId) {
									$sl = " selected ";
								}
								echo ("<option value='" . $row['aId'] . "' ". $sl. ">" . $row['aNm'] . "</option> ");
							}
							echo ('</optgroup>');
						}
					?>
				</select><label for="iUsrId"> المستخدم </label>
			</span>
			<span>
				<input class="swing" id="iUsr" name="iUsr" type="text" maxlength="99" autocomplete="off" placeholder="اسم الدخول" value="<?php echo($tmpUsr) ?>" required /><label for="iUsr">الاسم</label>
			</span>
			<input class="" id="ioPsswrd" name="ioPsswrd" type="hidden" maxlength="99" value="<?php echo($tmpioPsswrd) ?>" />
			<span>
				<input class="swing" id="inPsswrd" name="inPsswrd" type="password" maxlength="99" placeholder="كلمة السر" value="<?php echo($tmpinPsswrd) ?>" /><label for="inPsswrd">كلمة المرور</label>
			</span>
			<span>
				<input class="swing" id="iEml" name="iEml" type="text" maxlength="99" autocomplete="off" placeholder="البريد الالكترونى" value="<?php echo($tmpEml) ?>" /><label for="iEml">البريد</label>
			</span>
			<span>
				<input class="swing" id="iMxLg" name="iMxLg" type="number" maxlength="10" value="<?php echo($tmpMxLg) ?>" /><label for="iMxLg">حد الدخول</label>
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
				
				document.getElementById('iUsr').value = this.cells[4].innerHTML;
				document.getElementById('ioPsswrd').value = this.cells[5].innerHTML;
				document.getElementById('inPsswrd').value = "";
				document.getElementById('iEml').value = this.cells[6].innerHTML;
				document.getElementById('iMxLg').value = this.cells[7].innerHTML;
				if (document.getElementById('iId').value == 0) {
					document.getElementById('iUsrId').selectedIndex=0;
				} else {
					document.getElementById('iUsrId').value = this.cells[2].innerHTML;
				}
				showInptScrn();
			}
		}

		function isValidForm() {
			var f = document.getElementById('myfooter');
			var x = document.getElementById('iUsrId');
			if (x.value) {
				if (isVldInpt('iUsr', " اسم الدخول فارغ")) {
					var x = document.getElementById('inPsswrd');
					var id = document.getElementById('iId');
					if (!(x.value === '' && id.value == 0)) {
						if (isVldInpt('iEml', " البريد الالكترونى فارغ")) {
							return TRUE;
						}
					} else {
						f.innerHTML = " كلمة السر فارغة";
					}
				}
			} else {
				f.innerHTML = 'اختر المستخدم';
			}
			return false;
		}
	</script>
<?php
	include_once TPL . 'footer.php';
} else {
	header("location: index.php");
	exit();
}
?>