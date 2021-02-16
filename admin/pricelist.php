<?php
   session_start ();
   if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
      include_once "init.php" ;
      include_once TPL. 'navbar.php' ;
      include_once TPL. 'slider.php' ;
	
		$pageTitle = "قائمة الأسعار";
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
						if (isSaved("plId", "PriceList", intval($_POST['iId']))) {
							if (empty(vldtVrbl($_POST['iGds']))) {
								$errs[]=" اسم الصنف فارغ";
							}
							
							else {
								
								$sql="UPDATE PriceList SET plGds=?, plDt=?, plByPrc=?, plSllPrc=?, plCmsn=? , plPkhr=? WHERE plId=? ";
								$vl=array(vldtVrbl($_POST['iGds']), vldtVrbl($_POST['iDt']), vldtVrbl($_POST['iByPrc']), vldtVrbl($_POST['iSllPrc']), vldtVrbl($_POST['iCmsn']), vldtVrbl($_POST['iPkhr']) , vldtVrbl($_POST['iId']));
								$report="تم حفظ التعديلات";
								$goOn= TRUE ;
							}
						}
						else {
							$errs[]="تم حذفه بواسطة مستخدم آخر";
						} 
					}
					elseif($_POST['iId'] == '0') {
						if (empty(vldtVrbl($_POST['iGds']))) {
							$errs[]=" اسم الصنف فارغ";
						}
						elseif (empty(vldtVrbl($_POST['iDt']))) {
							$errs[]=" أدخل التاريخ";
                  }
                  
						else {
							$sql="INSERT INTO PriceList (plGds, plDt, plByPrc, plSllPrc, plCmsn, plPkhr) VALUES (?,?,?,?,?,?) ";
							$vl=array( vldtVrbl($_POST['iGds']), vldtVrbl($_POST['iDt']), vldtVrbl($_POST['iByPrc']), vldtVrbl($_POST['iSllPrc']) , vldtVrbl($_POST['iCmsn']), vldtVrbl($_POST['iPkhr']));
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
				if (isset($_POST['iId']) && $_POST['iId']>0) {
					if (isSaved("plId", "PriceList", intval($_POST['iId']))) {
                  $sql="DELETE FROM PriceList WHERE `plId`=? ;";
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
         if (isset($_GET['iPlTyp'])){
				$_SESSION['iPlTyp']=vldtVrbl($_GET['iPlTyp'])	;
			}elseif (isset($_SESSION['iPlTyp'])) {
				//$_SESSION['iPlTyp'] ;
			}else{
				$_SESSION['iPlTyp']=1 ;
			}
			//------------------------------------------
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
					$_SESSION['strSrt'] = "ORDER BY plId";
					break;
				case 1:
					$_SESSION['strSrt'] = "ORDER BY plGds";
					break;
				case 2:
					$_SESSION['strSrt'] = "ORDER BY gNm";
					break;
				case 3:
					$_SESSION['strSrt'] = "ORDER BY plDt";
					break;
				case 4:
					$_SESSION['strSrt'] = "ORDER BY plByPrc";
					break;
				case 5:
					$_SESSION['strSrt'] = "ORDER BY plSllPrc";
					break;
				case 6:
					$_SESSION['strSrt'] = "ORDER BY plCmsn";
               break;
            case 7:
               $_SESSION['strSrt'] = "ORDER BY plPkhr";
               break;
				default:
					$_SESSION['strSrt'] = "ORDER BY plId";
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
							<th class='hiddenCol'> <a href="?srt=1" > <?php echo($srtSymbl[1]); ?> كود الصنف </a> </th>
							<th> <a href="?srt=2" > <?php echo($srtSymbl[2]); ?> اسم الصنف </a> </th>
                     <th> <a href="?srt=3" > <?php echo($srtSymbl[3]); ?> التاريخ </a> </th>
							<th> <a href="?srt=4" > <?php echo($srtSymbl[4]); ?> سعر الشراء </a> </th>
							<th> <a href="?srt=5" > <?php echo($srtSymbl[5]); ?> سعر البيع  </a> </th>
							<th> <a href="?srt=6" > <?php echo($srtSymbl[6]); ?> العمولة </a> </th>
							<th> <a href="?srt=7" > <?php echo($srtSymbl[7]); ?> البخر </a> </th>
						</tr>
					</thead>
					<tbody>
						<?php 
                     $rc=0;   
                     if ($_SESSION['iPlTyp']==1) {  
							   $sql="SELECT * FROM PriceList INNER JOIN Goods ON plGds=gId WHERE gGrp IN (SELECT gId FROM Groups WHERE gGrpTyp =12) AND gNm like '%".vldtVrbl($_SESSION['strSrch'])."%' ".$_SESSION['strSrt']  ;
                     }
                     else {
                        $sql="SELECT * FROM PriceList INNER JOIN Goods ON plGds=gId WHERE gGrp IN (SELECT gId FROM Groups WHERE gGrpTyp !=12) AND gNm like '%".vldtVrbl($_SESSION['strSrch'])."%' ".$_SESSION['strSrt']  ;
                     }
                     $result=getRows($sql);
							foreach($result as $row) {
								echo (
                           "<tr>
                              <td>". ++$rc. "</td> <td class='hiddenCol'>". $row['plId']. "</td>
											<td class='hiddenCol'>". $row['plGds']. "</td> <td>". $row['gNm'] . "</td> 
                                 <td>". $row['plDt']."</td>
                                 <td>". $row['plByPrc']."</td>
											<td>". $row['plSllPrc'] ."</td> 
											<td>". $row['plCmsn'] ."</td>
                                 <td>". $row['plPkhr']."</td>
                           </tr>"
                        );
							}
							echo ("<tr> <td>". ++$rc. "</td> <td class='hiddenCol'>0</td> <td class='hiddenCol'></td><td></td> <td></td> <td></td> <td></td> <td></td> <td></td>	</tr>");
						?>
					</tbody>
					<tfoot>
						<tr>
							<th> * </th><td class='hiddenCol'> </td><td class='hiddenCol'>  </td><td>ملاحظات</td><td colspan="5"> </td>
						</tr>
					</tfoot>
				</table>
			</div>
			<!-------------------------------- Input Screen ---------------------------->
			<div class="row" id="row">
				<div class="btnClose" id="btnClose" > &#10006; </div>
				<form action="<?php echo ($_SERVER['PHP_SELF']); ?>" method="post" name="iFrm" onsubmit="return isValidForm()">
					<input class="" id="iId" name="iId" type="hidden" />
					<span>
						<select class="swing" name="iGds" id="iGds" placeholder="اسم الصنف">
							<?php
								if ($_SESSION['iPlTyp']==1) {
									$sql="SELECT * FROM Goods WHERE gWrk=1 AND gGrp IN (SELECT gId FROM Groups WHERE gGrpTyp =12) ;" ;
								}
								else {
									$sql="SELECT * FROM Goods WHERE gWrk=1 AND gGrp IN (SELECT gId FROM Groups WHERE gGrpTyp !=12) ;" ;
								}
								$result=getRows($sql);
								foreach($result as $row) {
									echo ("<option value=". $row['gId']. ">". $row['gNm']. "</option> ");
								}
							?>
						</select><label for="iGds"> الاسم </label>
					</span>
					<span>
						<input class="swing" style="padding-right: 100px;" id="iDt" name="iDt" type="date" required/><label for="iDt">التاريخ</label>
					</span>
					<?php
                  if ($_SESSION['iPlTyp']==1) {
							echo ('<span> <input class="swing" id="iByPrc" name="iByPrc" type="text"  placeholder="سعر الشراء" /><label for="iByPrc">الشراء</label> </span>');
							echo ('<span> <input class="swing" id="iSllPrc" name="iSllPrc" type="text" placeholder="سعر البيع" onchange="getByPrc();" /><label for="iSllPrc">البيع</label> </span>');
							echo ('<span> <input class="swing" id="iCmsn" name="iCmsn" type="text" placeholder="قيمة العمولة أو الخصم" onchange="getByPrc();" /><label for="iCmsn">العمولة</label> </span>');
							echo ('<span> <input class="swing" id="iPkhr" name="iPkhr" type="text" placeholder="نسبة البخر" /><label for="iPkhr">البخر</label> </span>');
						} else {
							echo ('<span> <input class="swing" id="iByPrc" name="iByPrc" type="text" placeholder="سعر الشراء" /><label for="iByPrc">الشراء</label> </span>');
							echo ('<span> <input class="swing" id="iSllPrc" name="iSllPrc" type="text" placeholder="سعر البيع" /><label for="iSllPrc">البيع</label> </span>');
							echo ('<span> <input class="swing" id="iCmsn" name="iCmsn" type="text" placeholder="قيمة العمولة أو الخصم" /><label for="iCmsn">العمولة</label> </span>');
							echo ('<input class="swing" id="iPkhr" name="iPkhr" type="text" style="display:none ;" />');
						}
					?>
					
					<button class ="btn" type="submit" name="btnSave" > حفظ  </button>
					<button class ="btn" type="submit" name="btnDlt" id="btnDlt" > حذف </button>
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
					document.getElementById('iGds').value = this.cells[2].innerHTML;
					document.getElementById('iDt').value = this.cells[4].innerHTML;
					document.getElementById('iByPrc').value = this.cells[5].innerHTML;
					document.getElementById('iSllPrc').value =this.cells[6].innerHTML;
					document.getElementById('iCmsn').value = this.cells[7].innerHTML;
					document.getElementById('iPkhr').value = this.cells[8].innerHTML;
					showInptScrn();
				}
			}	
			function getByPrc () {
				document.getElementById('iByPrc').value = document.getElementById('iSllPrc').value - document.getElementById('iCmsn').value ;
			}
		</script>
<?php
		include_once TPL. 'footer.php' ;
	}else {
		header("location: index.php");
		exit ();
	}
?>