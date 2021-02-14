<?php
   session_start ();
   if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
      include_once "init.php" ;
      include_once TPL. 'navbar.php' ;
      include_once TPL. 'slider.php' ;
   
		$goOn = FALSE ;
			
		for ($i=0 ; $i<12 ; $i++) {
			$srtSymbl[$i]="   &#9670;" ;
		}
		//--------------------------------------------
		if ($_SERVER['REQUEST_METHOD']=="POST"){
         $errs = array();
			if(isset($_POST['btnSave'])) {	
				if (isset($_POST['iId'])) {
					if ($_POST['iId']>0 ) {
                  if (isSaved("gId", "Goods", intval($_POST['iId']))) {
							if (empty(vldtVrbl($_POST['iNm']))) {
								$errs[]=" الاسم فارغ";
							}
							elseif (isSaved("gNm", "Goods", vldtVrbl($_POST['iNm']), " AND gId <> ".vldtVrbl($_POST['iId']))) {
								$errs[]=" الاسم مكرر ";
							}
							elseif (!(intval($_POST['iByPrc'])>=0)) {
								$errs[]="سعر الشراء غير صحيح";
							}
							elseif (!(intval($_POST['iSllPrc'])>=0)) {
								$errs[]="سعر البيع غير صحيح";
                     }
                     else {
                        $sql="UPDATE Goods SET gNm=?, gByPrc=?, gSllPrc=?, gNts=?, gGrp=? WHERE `gId`=? ;";
                        $vl=array(vldtVrbl($_POST['iNm']), vldtVrbl($_POST['iByPrc']), vldtVrbl($_POST['iSllPrc']), vldtVrbl($_POST['iNts']), vldtVrbl($_POST['iGrp']), vldtVrbl($_POST['iId']));
                        $report="تم حفظ التعديلات";
                        $goOn= TRUE ;
                     }
                  }
                  else {
							$errs[]="تم حذفه بواسطة مستخدم آخر";
						}   
					}
					elseif($_POST['iId'] == '0') {
                  if (empty(vldtVrbl($_POST['iNm']))) {
							$errs[]=" الاسم فارغ";
						}
						elseif (isSaved("usNm", "Users", vldtVrbl($_POST['iNm']))) {
							$errs[]=" الاسم مكرر ";
                  }
                  elseif (!(intval($_POST['iByPrc'])>=0)) {
                     $errs[]="سعر الشراء غير صحيح";
                  }
                  elseif (!(intval($_POST['iSllPrc'])>=0)) {
                     $errs[]="سعر البيع غير صحيح";
                  }
						else {
							$sql="INSERT INTO Goods (gNm, gByPrc, gSllPrc, gNts, gGrp) VALUES (?,?,?,?,?) ;";
							$vl=array(vldtVrbl($_POST['iNm']), vldtVrbl($_POST['iByPrc']), vldtVrbl($_POST['iSllPrc']), vldtVrbl($_POST['iNts']),vldtVrbl($_POST['iGrp']));
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
            if (isset($_POST['iId']) && intval($_POST['iId'])>5) {
					if (isSaved("gId", "Goods", intval($_POST['iId']))) {
						if (isSaved("pGds", "Pumps", intval($_POST['iId']))) {
							$errs[]="يوجد طلمبة مرتبطة بهذا الوقود";
						}
						else {
							$sql="DELETE FROM Goods WHERE `gId`=? ;";
							$vl = array(intval($_POST['iId']));
							$report="تم حذفه";
							$goOn=TRUE;
						}
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
			if (isset($_GET['iGdTyp'])){
				$_SESSION['iGdTyp']=vldtVrbl($_GET['iGdTyp'])	;
			}elseif (isset($_SESSION['iGdTyp'])) {
				//$_SESSION['iGdTyp'] ;
			}else{
				$_SESSION['iGdTyp']=14 ;
			}
			//------------------------------------------
			if (isset($_GET['iSrch'])){
				$_SESSION['strSrch']=vldtVrbl($_GET['iSrch'])	;
			}elseif (isset($_SESSION['strSrch'])) {
				//$_SESSION['strSrch'] ;
			}else{
				$_SESSION['strSrch']=" " ;
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
				$_SESSION['srt']=7 ;
				$_SESSION['srtKnd']=9652 ;
				$_SESSION['strSrt']=" ;" ;
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
					$_SESSION['strSrt'] = "ORDER BY gByPrc";
					break;
				case 3:
					$_SESSION['strSrt'] = "ORDER BY gSllPrc";
					break;
				case 4:
					$_SESSION['strSrt'] = "ORDER BY gNts";
					break;
				case 5:
					$_SESSION['strSrt'] = "ORDER BY gWrk";
					break;
				case 6:
					$_SESSION['strSrt'] = "ORDER BY gGrp";
					break;
				case 7:
					$_SESSION['strSrt'] = "ORDER BY gAdDt";
					break;
				default:
					$_SESSION['strSrt'] = "ORDER BY gAdDt";
					break;
			} 
			$_SESSION['strSrt'] .= ($_SESSION['srtKnd']==9652) ? " ASC ;" : " DESC ;" ;  
		}else{
			echo ("خطأ غير متوقع");
		}
      $srtSymbl[$_SESSION['srt']]=" &#".$_SESSION['srtKnd']."; ";	
      $pageTitle =str_replace("فئة",'الأصناف :', getNameById('Groups', 'g', $_SESSION['iGdTyp'])) ;
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
							<th> م </th>
							<th> <a href="?srt=0" > <?php echo($srtSymbl[0]); ?> الكود 	   </a> </th>
							<th> <a href="?srt=1" > <?php echo($srtSymbl[1]); ?> الاسم 	    </a> </th>
							<th> <a href="?srt=2" > <?php echo($srtSymbl[2]); ?> سعر الشراء </a> </th>
							<th> <a href="?srt=3" > <?php echo($srtSymbl[3]); ?> سعر البيع  </a> </th>
							<th> <a href="?srt=4" > <?php echo($srtSymbl[4]); ?> ملاحظات 	</a> </th>
							<th> <a href="?srt=5" > <?php echo($srtSymbl[5]); ?> نشط        </a> </th>
							<th> <a href="?srt=6" > <?php echo($srtSymbl[6]); ?> مجموعة     </a> </th>
							<th> <a href="?srt=7" > <?php echo($srtSymbl[7]);?>تاريخ الانشاء</a> </th>
						</tr>
					</thead>
					<tbody>
						<?php 
							$rc=0;     
							$sql="SELECT * FROM Goods WHERE gGrp IN (SELECT gId FROM Groups WHERE gGrpTyp=". vldtVrbl($_SESSION['iGdTyp']). ") AND gNm like '%".vldtVrbl($_SESSION['strSrch'])."%' ".$_SESSION['strSrt']  ;
							$result=getRows($sql);
							foreach($result as $row) {
								echo ("<tr> <td>". ++$rc. "</td> <td>". $row['gId']. "</td>	<td>". $row['gNm']. "</td> <td>". $row['gByPrc']."</td>
											<td>". $row['gSllPrc'] ."</td> <td>". $row['gNts'] ."</td>
											<td>". $row['gWrk']."</td> <td>". $row['gGrp']. "</td> <td>". $row['gAdDt']. "</td> </tr>");
							}
							echo ("<tr> <td>". ++$rc. "</td> <td>0</td> <td></td> <td></td> <td></td> <td></td> <td></td> <td></td> <td></td>	</tr>");
						?>
					</tbody>
					<tfoot>
						<tr>
							<th> * </th> <td>   </td> <td> ملاحظات </td>
							<td> 	 </td> 	<td> 	 </td> 	<td> 	 </td>
							<td> 	 </td> <td> 	 </td> 
							<td> 	 </td>
						</tr>
					</tfoot>
				</table>
			</div>
			<!-------------------------------- Input Screen ---------------------------->
			<div class="inptScrn" id="inptScrn">
				<div class="btnClose" id="btnClose" > &#10006; </div>
				<form action="<?php echo ($_SERVER['PHP_SELF']); ?>" method="post" name="iFrm" onsubmit="return isValidForm()">
					<input class="inpt" type="hidden" name="iId" id="iId" />
					<label class="lbl" for="iNm">اسم الصنف : </label>
					<input class="inpt" type="text" name="iNm" id="iNm" maxlength="99" required />
					<label class="lbl" for="iByPrc"> سعر الشراء : </label>
					<input class="inpt" type="text" name="iByPrc" id="iByPrc" maxlength="8" pattern="[0-9]+(\.[0-9][0-9]?)?" />
					<label class="lbl" for="iSllPrc">  سعر البيع: </label>
					<input class="inpt" type="text" name="iSllPrc" id="iSllPrc" maxlength="8" pattern="[0-9]+(\.[0-9][0-9]?)?" />
					<label class="lbl" for="iNts">  ملاحظات: </label>
					<input class="inpt" type="text" name="iNts" id="iNts" maxlength="99" />
					<label class="lbl" for="iGrp">  المجموعة: </label>
					<select class="inpt" name="iGrp" id="iGrp">
						<?php
							$sql="SELECT * FROM Groups WHERE gWrk=1 AND gGrpTyp = ". vldtVrbl($_SESSION['iGdTyp']) . " ;" ;
							$result=getRows($sql);
							foreach($result as $row) {
								echo ("<option value=". $row['gId']. ">". $row['gNm']. "</option> ");
							}
						?>
					</select>

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
					document.getElementById('iNm').value 		= this.cells[2].innerHTML;
					document.getElementById('iByPrc').value 	= this.cells[3].innerHTML;
					document.getElementById('iSllPrc').value 	= this.cells[4].innerHTML;
					document.getElementById('iNts').value 		= this.cells[5].innerHTML;
               if (this.cells[1].innerHTML == 0) {
						document.getElementById ("iGrp").selectedIndex ="0" ;
					}
					else {
						document.getElementById('iGrp').value = this.cells[7].innerHTML;
					}
					showInptScrn();
				}
			}	
		</script>
<?php
      include_once TPL. 'footer.php' ;
   }else {
      header("location: index.php");
      exit ();
   }
?>