<?php
   session_start ();
   if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
      include_once "init.php" ;
      include_once TPL. 'navbar.php' ;
      include_once TPL. 'slider.php' ;

		$pageTitle = "محتوى الفاتورة";
		$goOn = FALSE ;
			
		for ($i=0 ; $i<10 ; $i++) {
			$srtSymbl[$i]="   &#9670;" ;
		}
		//--------------------------------------------
		if ($_SERVER['REQUEST_METHOD']=="POST"){
			if(isset($_POST['btnSave'])) {	
				if (isset($_POST['iId'])) {
					if ($_POST['iId']>0 ) {
                  if (isSaved("bbId", "BillBody", intval($_POST['iId']))) {
							if (isSaved("bbBllHdr", "BillBody", intval($_SESSION['iBll']),"AND bbStr =  ". vldtVrbl($_POST['iStr']). " AND bbGds = ". vldtVrbl($_POST['iGds']). " AND bbId != ". intval($_POST['iId']))) {
								$errs[]= "هذا الصنف مسجل من قبل فى هذا الخزن" ;
							}
                     else {
                        $sql="UPDATE BillBody SET bbGds=?, bbStr=?, bbQntty=?, bbPrc=?, bbDscnt=?, bbNts=?  WHERE `bbId`=? ;";
                        $vl=array(vldtVrbl($_POST['iGds']), vldtVrbl($_POST['iStr']), vldtVrbl($_POST['iQntty']),vldtVrbl($_POST['iPrc']), vldtVrbl($_POST['iDscnt']), vldtVrbl($_POST['iNts']), vldtVrbl($_POST['iId']));
                        $report="تم حفظ التعديلات";
                        $goOn= TRUE ;
                     }
                  }
                  else {
							$errs[]="تم حذفه بواسطة مستخدم آخر";
						}   
					}
					elseif($_POST['iId'] == '0') {
						if (empty($_POST['iStr'])) {
							$errs[]="اختر المخزن";
						}elseif (empty($_POST['iGds'])) {
							$errs[]="اختر الصنف";
						}elseif (isSaved("bbBllHdr", "BillBody", intval($_SESSION['iBll']),"AND bbStr =  ". vldtVrbl($_POST['iStr']). " AND bbGds = ". vldtVrbl($_POST['iGds']))) {
							$errs[]= "هذا الصنف مسجل من قبل فى هذا الخزن" ;
                  }else {
							$sql="INSERT INTO BillBody (bbBllHdr, bbGds, bbStr, bbQntty, bbPrc,bbDscnt, bbNts) VALUES (?,?,?,?,?,?,?) ;";
							$vl=array(vldtVrbl($_SESSION['iBll']), vldtVrbl($_POST['iGds']), vldtVrbl($_POST['iStr']), vldtVrbl($_POST['iQntty']), vldtVrbl($_POST['iPrc']),vldtVrbl($_POST['iDscnt']), vldtVrbl($_POST['iNts']));
                     $report="تم إضافة بيان جديد";
                     $goOn=TRUE;
						}
					}
				}else {
					$report="خطأ غير متوقع";
				}  
			}elseif(isset($_POST['btnDlt'])){
				if (isset($_POST['iId']) && $_POST['iId']>0) {
					$sql="DELETE FROM BillBody WHERE `bbId`=? ;";
					$vl=array(vldtVrbl($_POST['iId']));
               $report="تم حذفه";
               $goOn=TRUE;
				}
				else {
					$report=" غير مسموح بالحذف";
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
			if (isset($_GET['iBll'])){
				$_SESSION['iBll']=vldtVrbl($_GET['iBll'])	;
			}elseif (isset($_SESSION['iBll'])) {
				//$_SESSION['strSrch'] ;
			}else{
				$_SESSION['iBll']=1 ;
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
				default:
					$_SESSION['strSrt'] = "ORDER BY `bbId`";
					break;
			} 
			$_SESSION['strSrt'] .= ($_SESSION['srtKnd']==9652) ? " ASC ;" : " DESC ;" ;  
		}
		else{
			echo ("خطأ غير متوقع");
		}
		$srtSymbl[$_SESSION['srt']]=" &#".$_SESSION['srtKnd']."; ";	
?>
	<!-------------------------------- Table ---------------------------------->
		<div class="dvTbl" id="dvTbl">
         <table class="mTbl">
				<thead>
					<tr>
						<th class='hiddenCol'> الكود 	</th>
						<th> رقم الفاتورة </th>
						<th> نوع الفاتورة </th>
                  <th class='hiddenCol'> كود الاسم  </th>
                  <th> الاسم 	 </th>
						<th> التاريخ </th>
						<th> ملاحظات  </th>
					</tr>
				</thead>
				<tbody>
					<?php 
                  $sql="SELECT * FROM BillHeader INNER JOIN Accounts ON bhNm=AId WHERE bhSub= ". $_SESSION['Sub']. " AND bhId=? "  ;
                  $vl=array($_SESSION['iBll']);
						$row=getRow($sql, $vl);
						$tmpKnd= ($row['bhKnd']==1 || $row['bhKnd']==9) ;
						echo (
							"<tr> 
								<td class='hiddenCol'>". $row['bhId']. "</td> <td>". $row['bhNmbr']. "</td><td>". getNameById("Types","t", $row['bhKnd'])."</td>
								<td class='hiddenCol'>". $row['bhNm']. "</td>  <td>". $row['aNm'] ."</td> 
								<td>". $row['bhDt']."</td> <td>". $row['bhNts']. "</td>
							</tr>"
						);
					?>
				</tbody>
         </table>
			<table class="mTbl" id="mTbl">
				<caption> <?php getTitle(); ?> </caption>
				<thead>
					<tr>
						<th>م</th>
						<th class='hiddenCol'><a href="?srt=0" > <?php echo($srtSymbl[0]); ?> كود العملية  </a> </th>
						<th class='hiddenCol'><a href="?srt=1" > <?php echo($srtSymbl[1]); ?> كود الفاتورة  </a> </th>
						<th class='hiddenCol'><a href="?srt=2" > <?php echo($srtSymbl[2]); ?> كود المخزن  </a> </th>
						<th><a href="?srt=3" > <?php echo($srtSymbl[3]); ?> اسم المخزن  </a> </th>
						<th class='hiddenCol'><a href="?srt=4" > <?php echo($srtSymbl[4]); ?> كود الصنف </a> </th>
						<th><a href="?srt=5" > <?php echo($srtSymbl[5]); ?> اسم الصنف 	 </a> </th>
						<th><a href="?srt=6" > <?php echo($srtSymbl[6]); ?> الكمية    </a> </th>
						<th><a href="?srt=7" > <?php echo($srtSymbl[7]); ?> السعر 		 </a> </th>
                  <th><a href="?srt=8" > <?php echo($srtSymbl[8]); ?> الخصم 	  </a> </th>
                  <th><a href="?srt=9" > <?php echo($srtSymbl[9]); ?> ملاحظات 	  </a> </th>
					</tr>
				</thead>
				<tbody>
					<?php 
						$rc=0;     
						$sql="SELECT * FROM BillBody INNER JOIN Accounts ON bbStr=AId INNER JOIN Goods ON bbGds=gId WHERE bbBllHdr = ". vldtVrbl($_SESSION['iBll'])."  ".$_SESSION['strSrt']  ;
						$result=getRows($sql);
						foreach($result as $row) {
							echo (
								"<tr>
									<td>". ++$rc. "</td> <td class='hiddenCol'>". $row['bbId']. "</td> <td class='hiddenCol'>". $row['bbBllHdr'] ." </td>
									<td class='hiddenCol'>". $row['bbStr']. "</td> <td>". $row['aNm'] ." </td> 
									<td class='hiddenCol'>". $row['bbGds']. "</td> <td>". $row['gNm'] ." </td>
									<td>". $row['bbQntty']."</td> <td>". $row['bbPrc'] ."</td> 
									<td>". $row['bbDscnt']."</td> <td>". $row['bbNts']."</td> 
								</tr>"
							);
						}
						echo ("<tr> <td>". ++$rc. "</td> <td class='hiddenCol'>0</td> <td class='hiddenCol'></td> <td class='hiddenCol'></td><td></td> <td class='hiddenCol'></td><td></td> <td></td> <td></td> <td></td> <td></td>	</tr>");
					?>
				</tbody>
				<tfoot>
					<tr>
						<th> * </th> <td class='hiddenCol'>   </td> <td class='hiddenCol'>  </td>
						<td class='hiddenCol'> 	 </td> <td> ملاحظات	 </td> <td colspan="6"> 	 </td> 
					</tr>
				</tfoot>
			</table>
		</div>
		<!-------------------------------- Input Screen ---------------------------->
		<div class="row" id="row">
			<div class="btnClose" id="btnClose" > &#10006; </div>
			<form action="<?php echo ($_SERVER['PHP_SELF']); ?>" method="post" name="iFrm" onsubmit="return isValidForm()">
				<input class="" id="iId" name="iId" type="hidden"  />
				<input class="" id="iBll" name="iBll" type="hidden"  />
				<span>
					<select class="swing" name="iStr" id="iStr" placeholder="اسم المخزن" >
						<?php
							if ($tmpKnd) {
								$sql="SELECT * FROM Accounts WHERE aSub=". $_SESSION['Sub'] . " AND aGrp IN (SELECT gId FROM Groups WHERE gGrpTyp BETWEEN 9 AND 11)" ;	
							}else {
								$sql="SELECT * FROM Accounts WHERE aSub=". $_SESSION['Sub'] . " AND aGrp IN (SELECT gId FROM Groups WHERE gGrpTyp BETWEEN 10 AND 11)" ;
							}
							$result=getRows($sql);
							foreach($result as $row) {
								echo ("<option value=". $row['aId']. ">". $row['aNm']. "</option> ");
							}
						?>
					</select><label for="iStr"> المخزن </label>
				</span>
				<span>
					<select class="swing" name="iGds" id="iGds" placeholder="اسم الصنف" >
						<?php
							if ($tmpKnd) {
								$sql="SELECT * FROM Goods ;" ;
							} else {
								$sql="SELECT * FROM Goods WHERE gGrp IN (SELECT gId FROM Groups WHERE gGrpTyp!=12) ;" ;
							}
							$result=getRows($sql);
							foreach($result as $row) {
								echo ("<option value=". $row['gId']. ">". $row['gNm']. "</option> ");
							}
						?>
					</select><label for="iGds"> الصنف </label>
				</span>
				<span>
					<input class="swing" id="iQntty" name="iQntty" type="number" min="0" maxlength="10" placeholder="كمية الصنف" autocomplete="off"  /><label for="iQntty">الكمية</label>
				</span>
				<span>
					<input class="swing" id="iPrc" name="iPrc" type="text" autocomplete="off" placeholder="سعر الصنف" pattern="[0-9]+(\.[0-9]*)?" /><label for="iPrc">السعر</label>
				</span>
				<span>
					<input class="swing" id="iDscnt" name="iDscnt" type="text" autocomplete="off" placeholder="قيمة الخصم على الصنف" pattern="[0-9]+(\.[0-9]*)?" /><label for="iDscnt">الخصم</label>
				</span>
				<span>
					<input class="swing" id="iNts" name="iNts" type="text" maxlength = "99" autocomplete="off" placeholder="وصف الصنف" /><label for="iNts">ملاحظات</label>
				</span>

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
					document.getElementById('iId').value 		= this.cells[1].innerHTML;
					document.getElementById('iBll').value 		= this.cells[2].innerHTML;
					document.getElementById('iStr').value 		= this.cells[3].innerHTML;
					document.getElementById('iGds').value 		= this.cells[5].innerHTML;
					document.getElementById('iQntty').value	= this.cells[7].innerHTML;
					document.getElementById('iPrc').value 		= this.cells[8].innerHTML;
					document.getElementById('iDscnt').value 	= this.cells[9].innerHTML;
					document.getElementById('iNts').value 		= this.cells[10].innerHTML;
					showInptScrn();
				}
			}	
		</script>
<?php
      include_once TPL. 'footer.php' ;
	}
	else {
      header("location: index.php");
      exit ();
   }
?>