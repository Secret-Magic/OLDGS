<?php
   session_start ();
   if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
      include_once "init.php" ;
      include_once TPL. 'navbar.php' ;
      include_once TPL. 'slider.php' ;
		
		$pageTitle = "الفواتير";
		$goOn = FALSE ;
			
		for ($i=0 ; $i<8 ; $i++) {
			$srtSymbl[$i]="   &#9670;" ;
		}
		//--------------------------------------------
		if ($_SERVER['REQUEST_METHOD']=="POST"){
			if(isset($_POST['btnSave'])) {	
				if (isset($_POST['iId'])) {
					if ($_POST['iId']>0 ) {
                  if (isSaved("bhId", "BillHeader", intval($_POST['iId']))) {
                     $sql="UPDATE BillHeader SET bhNm=?, bhDt=?, bhNts=?  WHERE `bhId`=? ;";
                     $vl=array(vldtVrbl($_POST['iNm']), vldtVrbl($_POST['iDt']),vldtVrbl($_POST['iNts']), vldtVrbl($_POST['iId']));
                     $report="تم حفظ التعديلات";
                     $goOn= TRUE ;
                  }
                  else {
							$errs[]="تم حذفها بواسطة مستخدم آخر";
						}   
					}
					elseif($_POST['iId'] == '0') {
                  $sql="INSERT INTO BillHeader (bhNmbr, bhNm, bhDt, bhNts, bhSub, bhKnd) VALUES (?,?,?,?,?,?) ;";
                  $vl=array(vldtVrbl($_POST['iNmbr']), vldtVrbl($_POST['iNm']), vldtVrbl($_POST['iDt']), vldtVrbl($_POST['iNts']), $_SESSION['Sub'], $_SESSION['iKnd']);
                  $report="تم إضافة بيان جديد";
                  $goOn = TRUE ;
					}
            }
            else {
					$errs[]="خطأ غير متوقع";
				}  
         }
         elseif(isset($_POST['btnDlt'])){
            if (isset($_POST['iId']) && intval($_POST['iId'])>1) {
					if (isSaved("bhId", "BillHeader", intval($_POST['iId']))) {
						/*if (isSaved("gGrpTyp", "Groups", intval($_POST['iId']))) {
							$errs[]="يوجد مجموعات لهذه الفئة";
						}
						else {*/
							$sql="DELETE FROM BillHeader WHERE `bhId`=? ;";
                     $vl=array(vldtVrbl($_POST['iId']));
                     $report="تم حذفه";
							$goOn=TRUE;
						//}
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
         if (isset($_GET['iKnd'])){
				$_SESSION['iKnd']=vldtVrbl($_GET['iKnd'])	;
			}elseif (isset($_SESSION['iKnd'])) {
				//$_SESSION['iKnd'] ;
			}else{
				$_SESSION['iKnd']=1 ;
			}
			switch ($_SESSION['iKnd']) {
				case 1:
					$pageTitle="فواتير الشراء" ;
					break ;
				case 2:
					$pageTitle="فواتير مرتجع الشراء" ;
					break ;	
				case 3:
					$pageTitle="فواتير البيع" ;
					break ;
				case 4:
					$pageTitle="فواتير مرتجع البيع" ;
					break ;
				case 5:
					$pageTitle="إذن إضافة" ;
					break ;
				case 6:
					$pageTitle="إذن صرف" ;
					break ;
				case 7:
					$pageTitle="إذن نقل مخازن" ;
					break ;
				case 8:
					$pageTitle="فواتير تسوية" ;
					break ;	
				case 9:
					$pageTitle="رصيد أول المدة" ;
					break ;
				default:
					$pageTitle="فواتير البيع" ;
					break ;
			}
         //------------------------------------------
         if (isset($_GET['iPrsn'])){
				$_SESSION['iPrsn']=vldtVrbl($_GET['iPrsn'])	;
			}elseif (isset($_SESSION['iPrsn'])) {
				//$_SESSION['iPrsn'] ;
			}else{
				$_SESSION['iPrsn']=6 ;
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
				$_SESSION['srt']=1 ;
				$_SESSION['srtKnd']=9652 ;
				$_SESSION['strSrt']=" ;" ;
			}
			//------------------------------------------
			switch ($_SESSION['srt']) {
				case 0:
					$_SESSION['strSrt'] = "ORDER BY `bhId`";
					break;
				case 1:
					$_SESSION['strSrt'] = "ORDER BY bhNmbr";
					break;
				case 2:
					$_SESSION['strSrt'] = "ORDER BY bhNm";
					break;
            case 3:
					$_SESSION['strSrt'] = "ORDER BY aNm";
					break;
				case 4:
					$_SESSION['strSrt'] = "ORDER BY bhDt";
					break;
				case 5:
					$_SESSION['strSrt'] = "ORDER BY bhNts";
               break;
				default:
					$_SESSION['strSrt'] = "ORDER BY `bhNmbr`";
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
						<th class='hiddenCol'> <a href="?srt=0" > <?php echo($srtSymbl[0]); ?> الكود 	</a> </th>
						<th> <a href="?srt=1" > <?php echo($srtSymbl[1]); ?> رقم الفاتورة 	 </a> </th>
                  <th class='hiddenCol'> <a href="?srt=2" > <?php echo($srtSymbl[2]); ?> كود الاسم	 </a> </th>
                  <th> <a href="?srt=3" > <?php echo($srtSymbl[3]); ?> الاسم 	 </a> </th>
						<th> <a href="?srt=4" > <?php echo($srtSymbl[4]); ?> التاريخ </a> </th>
						<th> <a href="?srt=5" > <?php echo($srtSymbl[5]); ?> ملاحظات  </a> </th>
						<th> <a href="?srt=6" > <?php echo($srtSymbl[6]); ?> العمليات</a> </th>
					</tr>
				</thead>
				<tbody>
					<?php 
						$rc=0;     
						$sql="SELECT * FROM BillHeader INNER JOIN Accounts ON bhNm=aId WHERE bhSub= ". $_SESSION['Sub']. " AND bhKnd=". $_SESSION['iKnd'] ."  ". $_SESSION['strSrt']  ;
						$result=getRows($sql);
						foreach($result as $row) {
							$sql="SELECT * FROM BillBody WHERE bbBllHdr =? " ;
                     $vl =array($row['bhId']) ;
                     $po=getRC($sql, $vl) ;
							echo ("<tr> <td>". ++$rc. "</td> <td class='hiddenCol'>". $row['bhId']. "</td>
										<td>". $row['bhNmbr']. "</td>
										<td class='hiddenCol'>". $row['bhNm']. "</td> <td>". $row['aNm']. "</td> <td>". $row['bhDt']."</td>
										<td>". $row['bhNts']. "</td> <td> <div class='btns' >
										<button><a href ='BillBody.php?iBll=". $row['bhId']. "' target=''> ". $po ." = الأصناف</a></button>
										</div> </td></tr>");
						}
						$sql="SELECT * FROM BillHeader WHERE bhSub=? AND bhKnd=?";
						$vl=array($_SESSION['Sub'], $_SESSION['iKnd']);
						$nmbr =getRC($sql, $vl)+1;
						echo ("<tr> <td>". ++$rc. "</td> <td class='hiddenCol'>0</td> <td>$nmbr</td> <td class='hiddenCol'></td><td></td> <td></td> <td></td> <td></td>	</tr>");
					?>
				</tbody>
				<tfoot>
					<tr>
						<th> * </th>
						<td class='hiddenCol'>   </td>
						<td> ملاحظات </td>
						<td class='hiddenCol'>  </td><td>  </td>
						<td>  </td><td>  </td>
						<td>  </td>
					</tr>
				</tfoot>
			</table>
		</div>
		<!-------------------------------- Input Screen ---------------------------->
		<div class="inptScrn" id="inptScrn">
			<div class="btnClose" id="btnClose" > &#10006; </div>
			<form action="<?php echo ($_SERVER['PHP_SELF']); ?>" method="post" name="iFrm" onsubmit="return isValidForm()">
				<input class="inpt" type="hidden" name="iId" id="iId" />
				<label class="lbl" for="iNmbr">رقم الفاتورة  : </label>
				<input class="inpt dbl" type="number" name="iNmbr" id="iNmbr" readonly />
            <label class="lbl" for="iNm">اسم  : </label>
            <select class="inpt" name="iNm" id="iNm">
               <?php
                  $sql="SELECT * FROM Accounts WHERE aSub= ". $_SESSION['Sub'] . " AND aGrp IN (SELECT gId FROM Groups WHERE gGrpTyp = ". $_SESSION['iPrsn'] .")" ;
                  $result=getRows($sql);
                  foreach($result as $row) {
                     echo ("<option value=". $row['aId']. ">". $row['aNm']. "</option> ");
                  }
               ?>
            </select>
				<label class="lbl" for="iDt">  التاريخ : </label>
				<input class="inpt dbl" type="date" name="iDt" id="iDt" />
				<label class="lbl" for="iNts">  ملاحظات: </label>
				<input class="inpt" type="text" name="iNts" id="iNts" maxlength="99" />
				
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
					document.getElementById('iNmbr').value = this.cells[2].innerHTML;
					document.getElementById('iNm').value = this.cells[3].innerHTML;
					document.getElementById('iDt').value = this.cells[5].innerHTML;
					document.getElementById('iNts').value = this.cells[6].innerHTML;
					if (document.getElementById('iId').value ==0) {
                  var iniDay = new Date();
                  document.getElementById('iDt').value = iniDay.toISOString().substr(0, 10);
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