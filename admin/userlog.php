<?php
   session_start ();
   if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
      include_once "init.php" ;
      include_once TPL. 'navbar.php' ;
      include_once TPL. 'slider.php' ;
	
		$pageTitle = "بيانات الدخول";
		$goOn = FALSE ;
		
		for ($i=0 ; $i<9 ; $i++) {
			$srtSymbl[$i]="   &#9670;" ;
		}
		//--------------------------------------------
		if ($_SERVER['REQUEST_METHOD']=="POST"){
			$errs=array();
			if(isset($_POST['btnSave'])) {	
				if (isset($_POST['iId'])) {
					if ($_POST['iId']>0 ) {
						if (isSaved("ulId", "UserLog", intval($_POST['iId']))) {
							if (isSaved("ulUsrId", "UserLog", vldtVrbl($_POST['iUsrId']), " AND ulId <> ".vldtVrbl($_POST['iId']))) {
								$errs[]=" اسم المستخدم مكرر ";
							}
							elseif (empty(vldtVrbl($_POST['iUsr']))) {
								$errs[]=" اسم الدخول فارغ";
							}
							elseif (isSaved("ulUsr", "UserLog", vldtVrbl($_POST['iUsr']), " AND ulId <> ".vldtVrbl($_POST['iId']))) {
								$errs[]=" اسم الدخول مكرر ";
							}
							else {
								if (empty(vldtVrbl($_POST['inPsswrd']))){
									$hashedpass = $_POST['ioPsswrd'];
								}
								else {
									$hashedpass = sha1 (vldtVrbl($_POST['inPsswrd']));
								}
								$sql="UPDATE UserLog SET ulUsrId=?, ulUsr=?, ulPsswrd=?, ulEml=?, ulMxLg=? WHERE ulId=? ";
								$vl=array(vldtVrbl($_POST['iUsrId']), vldtVrbl($_POST['iUsr']), $hashedpass, vldtVrbl($_POST['iEml']), vldtVrbl($_POST['iMxLg']), vldtVrbl($_POST['iId']));
								$report="تم حفظ التعديلات";
								$goOn= TRUE ;
							}
						}
						else {
							$errs[]="تم حذفه بواسطة مستخدم آخر";
						} 
					}
					elseif($_POST['iId'] == '0') {
						if (isSaved("ulUsrId", "UserLog", vldtVrbl($_POST['iUsrId']), " AND ulId <> ".vldtVrbl($_POST['iId']))) {
							$errs[]=" اسم المستخدم مكرر ";
						}
						elseif (empty(vldtVrbl($_POST['iUsr']))) {
							$errs[]=" اسم الدخول فارغ";
						}
						elseif (isSaved("ulUsr", "UserLog", vldtVrbl($_POST['iUsr']))) {
							$errs[]=" اسم الدخول مكرر ";
                  }
                  elseif (isSaved("ulEml", "UserLog", vldtVrbl($_POST['iEml']))) {
							$errs[]=" البريد الالكترونى مكرر ";
						}
						elseif (empty(vldtVrbl($_POST['inPsswrd']))) {
							$errs[]=" كلمة السر فارغة";
						}
						else {
							$hashedpass = sha1 (vldtVrbl($_POST['inPsswrd']));
							$sql="INSERT INTO UserLog (ulUsrId, ulUsr, ulPsswrd, ulEml, ulMxLg) VALUES (?,?,?,?,?) ";
							$vl=array( vldtVrbl($_POST['iUsrId']), vldtVrbl($_POST['iUsr']), $hashedpass, vldtVrbl($_POST['iEml']), vldtVrbl($_POST['iMxLg']) );
							$report="تم إضافة بيان جديد";
							$goOn = TRUE ;
						}
					}
				}
				else {
					$errs[]="خطأ غير متوقع";
				}  
			}
			elseif(isset($_POST['btnDlt'])){
				if (isset($_POST['iId']) && $_POST['iId']>1) {
					if (isSaved("ulId", "UserLog", intval($_POST['iId']))) {
						$sql="DELETE FROM UserLog WHERE `ulId`=? ;";
						$vl=array(intval($_POST['iId']));
						$report="تم حذفه";
						$goOn=TRUE;
					}
					else {
						$errs[]="تم حذفه بواسطة مستخدم آخر";
					}
				}
				else {
					$errs[]=" غير مسموح بالحذف";
				}  
			}
			if ($goOn) {
				if (getRC($sql, $vl)>0) {
					
				}
				else {
					$report = "لم يحدث شئ" ;
				}
			} 
			else {
				$report = "";
				foreach ($errs as $err) {
					$report .= " # " . $err ; 
				}
			}
		} //==============================================================
		elseif ($_SERVER['REQUEST_METHOD']=="GET") {
			if (isset($_GET['iSrch'])){
				$_SESSION['strSrch']=vldtVrbl($_GET['iSrch'])	;
			}elseif (isset($_SESSION['strSrch'])) {
				//$_SESSION['strSrch'] ;
			}else{
				$_SESSION['strSrch']="" ;
			}
			//------------------------------------------
			if (isset($_GET['srt'])) {
				if (isset($_SESSION['srt']) && $_SESSION['srt']==vldtVrbl($_GET['srt']) ){
						$_SESSION['srtKnd']=19314-$_SESSION['srtKnd'] ;
				}else {
					$_SESSION['srt']=vldtVrbl($_GET['srt']);
					$_SESSION['srtKnd']=9652;
				}
			}else {
				$_SESSION['srt']=0 ;
				$_SESSION['srtKnd']=9652 ;
				$_SESSION['strSrt']=" ;" ;
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
			$_SESSION['strSrt'] .= ($_SESSION['srtKnd']==9652) ? " ASC ;" : " DESC ;" ;  
		}else{
			echo ("خطأ غير متوقع");
		}
		$srtSymbl[$_SESSION['srt']]=" &#".$_SESSION['srtKnd']."; ";	
?>
		<!-------------------------------- Table ---------------------------------->
			<div id="dvTbl">
				<form action= "<?php echo (vldtVrbl($_SERVER['PHP_SELF'])); ?>" method="get">
					<input class="srch" type="search" name="iSrch" id="srch" placeholder= "بحث بالاسم " value= "<?php echo ($_SESSION['strSrch']); ?>" >
				</form>
				<table class="mTbl" id="mTbl">
					<caption> <?php getTitle(); ?> </caption>
					<thead>
						<tr>
							<th>م</th>
							<th class='hiddenCol'> <a href="?srt=0" > <?php echo($srtSymbl[0]); ?> الكود 	 </a> </th>
							<th class='hiddenCol'> <a href="?srt=1" > <?php echo($srtSymbl[1]); ?> كود المستخدم </a> </th>
							<th> <a href="?srt=2" > <?php echo($srtSymbl[2]); ?> اسم المستخدم </a> </th>
							<th> <a href="?srt=3" > <?php echo($srtSymbl[3]); ?> اسم الدخول </a> </th>
							<th> <a href="?srt=4" > <?php echo($srtSymbl[4]); ?> كلمة المرور  </a> </th>
							<th> <a href="?srt=5" > <?php echo($srtSymbl[5]); ?> البريد الالكترونى </a> </th>
							<th> <a href="?srt=6" > <?php echo($srtSymbl[6]); ?> حد الدخول </a> </th>
						</tr>
					</thead>
					<tbody>
						<?php 
							$rc=0;     
							$sql="SELECT * FROM UserLog INNER JOIN Accounts ON ulUsrId=aId WHERE (aSub=0 || aSub=". $_SESSION['Sub']. ") AND aNm like '%".vldtVrbl($_SESSION['strSrch'])."%' ".$_SESSION['strSrt']  ;
							$result=getRows($sql);
							foreach($result as $row) {
								echo ("<tr> <td>". ++$rc. "</td> <td class='hiddenCol'>". $row['ulId']. "</td>
											<td class='hiddenCol'>". $row['ulUsrId']. "</td> <td>". $row['aNm'] . "</td> 
											<td>". $row['ulUsr']."</td>
											<td>". $row['ulPsswrd'] ."</td> 
											<td>". $row['ulEml'] ."</td>
											<td>". $row['ulMxLg']."</td> </tr>");
							}
							echo ("<tr> <td>". ++$rc. "</td> <td class='hiddenCol'>0</td> <td class='hiddenCol'></td> <td></td> <td></td> <td></td> <td></td> <td></td>	</tr>");
						?>
					</tbody>
					<tfoot>
						<tr>
							<th> * </th><td class='hiddenCol'> </td><td class='hiddenCol'> </td><td> ملاحظات </td><td> </td>	<td> </td><td> </td><td> </td>
						</tr>
					</tfoot>
				</table>
			</div>
			<!-------------------------------- Input Screen ---------------------------->
			<div class="inptScrn" id="inptScrn">
				<div class="btnClose" id="btnClose" > &#10006; </div>
				<form action="<?php echo ($_SERVER['PHP_SELF']); ?>" method="post" name="iFrm" onsubmit="return isValidForm()">
					<input class="inpt" type="hidden" name="iId" id="iId" />
					<label class="lbl" for="iUsrId">اسم المستخدم : </label>
					<select class="inpt" name="iUsrId" id="iUsrId">
						<?php
							//$sql="SELECT * FROM Users WHERE usId NOT IN (SELECT ulUsrId FROM UserLog) AND usGrp IN (SELECT gId FROM Groups WHERE gGrpTyp BETWEEN 2 AND 4) ;" ;
							$sql="SELECT * FROM Accounts WHERE (aSub=0 || aSub = ". $_SESSION['Sub'] . " ) AND aWrk=1 AND aGrp IN (SELECT gId FROM Groups WHERE gGrpTyp BETWEEN 2 AND 4) ;" ;
							$result=getRows($sql);
							foreach($result as $row) {
								echo ("<option value=". $row['aId']. ">". $row['aNm']. "</option> ");
							}
						?>
					</select>
					<label class="lbl" for="iUsr"> اسم الدخول : </label>
					<input class="inpt" type="text" name="iUsr" id="iUsr" maxlength="99" />
					<label class="lbl" for="inPsswrd">  كلمة المرور: </label>
					<input class="inpt" type="hidden" name="ioPsswrd" id="ioPsswrd" maxlength="255" />
					<input class="inpt" type="password" name="inPsswrd" id="inPsswrd" maxlength="255" />
					<label class="lbl" for="iEml"> البريد الالكترونى : </label>
					<input class="inpt" type="text" name="iEml" id="iEml" />
					<label class="lbl" for="iMxLg">  حد الدخول: </label>
					<input class="inpt" type="number" name="iMxLg" id="iMxLg" maxlength="10" />

					<button class ="btn" type="submit" name="btnSave" > حفظ  </button>
					<button class ="btn" type="submit" name="btnDlt"  id="btnDlt" > حذف </button> 
				</form>
			</div>  
		<!-------------------------------- Script ---------------------------------->
		<script src="layout/js/main.js" ></script>
		<script> 
			var x;
			var tbl01=document.getElementById("mTbl");
			for (x = 1; x < tbl01.rows.length-1; x = x + 1) {
				tbl01.rows[x].ondblclick = function () {
					document.getElementById('iId').value = this.cells[1].innerHTML;
					document.getElementById('iUsrId').value = this.cells[2].innerHTML;
					document.getElementById('iUsr').value = this.cells[4].innerHTML;
					document.getElementById('ioPsswrd').value = this.cells[5].innerHTML;
					document.getElementById('inPsswrd').value ="";
					document.getElementById('iEml').value = this.cells[6].innerHTML;
					document.getElementById('iMxLg').value = this.cells[7].innerHTML;
					if (document.getElementById('iId').value ==0) {
						document.getElementById('iMxLg').value =0 ;
					}
					showInptScrn();
				}
			}	 
			function isValidForm() {
				var f = document.getElementById('myfooter');
				var x = document.getElementById('iUsrId');
				if (x.value) {
					if (isVldInpt('iUsr',  " اسم الدخول فارغ")) {
						var x = document.getElementById('inPsswrd');
						var id = document.getElementById('iId');
						if (!(x.value === '' && id.value==0)) {
							if (isVldInpt('iEml', " البريد الالكترونى فارغ")) {
								return TRUE ;
							}
						}
						else {
							f.innerHTML = " كلمة السر فارغة";
						}
					}
				}
				else {
					f.innerHTML = 'اختر المستخدم';
				}
				return false;
			}
		</script>
<?php
        include_once TPL. 'footer.php' ;
    }else {
        header("location: index.php");
        exit ();
    }
?>