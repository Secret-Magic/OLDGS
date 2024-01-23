<?php
	include_once('config.php');		
	$pageTitle = "الفواتير";
	$goOn = TRUE ;
		
	for ($i=0 ; $i<9 ; $i++) {
		$srtSymbl[$i]="   &#9670;" ;
	}
	//--------------------------------------------
	if ($_SERVER['REQUEST_METHOD']=="POST"){
		$sql=array();
		if(isset($_POST['btnSave'])) {	
			if (isset($_POST['iId'])) {
				if ($_POST['iId']>0 ) {
					$sql[0]="SELECT * FROM BillHeader WHERE bhId=?";
					$sql[1]=array(vldtVrbl($_POST['iId']));
					if (CnctDb($sql,'psr') > 0) {
						$sql[0]="UPDATE BillHeader SET bhDt=?, bhNmbr=?, bhCstmr=?, bhBllKnd=?, bhDscnt=?, bhNts=? WHERE bhId=? ;";
						$sql[1]=array(vldtVrbl($_POST['iDt']), vldtVrbl($_POST['iNmbr']), vldtVrbl($_POST['iCstmr']), vldtVrbl($_SESSION['iKnd']), vldtVrbl($_POST['iDscnt']), vldtVrbl($_POST['iNts']), vldtVrbl($_POST['iId']));
						$report="تم حفظ التعديلات";
					}else {
						$report="تم حذفه بواسطة مستخدم آخر";
						$goOn=FALSE;
					}   
				}
				elseif($_POST['iId'] == '0') {
					$sql[0]="SELECT * FROM BillHeader WHERE bhNmbr=?";
					$sql[1]=array(vldtVrbl($_POST['iNmbr']));
					if (empty($_POST['iNmbr'])||CnctDb($sql,'psr') > 0) {
						$report="الاسم فارغ";
						$goOn=FALSE;
					}else {
						$sql[0]="INSERT INTO BillHeader (bhDt, bhNmbr, bhCstmr, bhBllKnd, bhDscnt, bhNts) VALUES (?,?,?,?,?,?) ;";
						$sql[1]=array(vldtVrbl($_POST['iDt']), vldtVrbl($_POST['iNmbr']), vldtVrbl($_POST['iCstmr']), vldtVrbl($_SESSION['iKnd']), vldtVrbl($_POST['iDscnt']), vldtVrbl($_POST['iNts']));
						$report="تم إضافة بيان جديد";
					}
				}
			}else {
				$report="خطأ غير متوقع";
				$goOn=FALSE;
			}  
		}elseif(isset($_POST['btnDlt'])){
			if (isset($_POST['iId']) && $_POST['iId']>0) {
				$sql[0]="DELETE FROM BillHeader WHERE bhId=? ;";
				$sql[1]=array(vldtVrbl($_POST['iId']));
				$report="تم حذفه";
			}else {
				$report=" غير مسموح بالحذف";
				$goOn=FALSE;
			}  
		}
		if ($goOn) {
			CnctDb($sql,'psr');
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
			$_SESSION['srt']=1;
			$_SESSION['srtKnd']=9652 ;
			$_SESSION['strSrt']=" ;" ;
		}
		
		//------------------------------------------
		switch ($_SESSION['srt']) {
			case 0:
				$_SESSION['strSrt'] = "ORDER BY `bhId`";
				break;
			case 1:
				$_SESSION['strSrt'] = "ORDER BY bhDt";
				break;
			case 2:
				$_SESSION['strSrt'] = "ORDER BY bhNmbr";
				break;
			case 3:
			case 4:
				$_SESSION['strSrt'] = "ORDER BY bhCstmr";
				break;
			case 5:
			case 6:
				$_SESSION['strSrt'] = "ORDER BY bhBllKnd";
				break;
			case 7:
				$_SESSION['strSrt'] = "ORDER BY bhDscnt";
				break;
			case 8:
				$_SESSION['strSrt'] = "ORDER BY bhNts";
				break;
			default:
				$_SESSION['strSrt'] = "ORDER BY bhDt";
				break;
		} 
		$_SESSION['strSrt'] .= ($_SESSION['srtKnd']==9652) ? " ASC ;" : " DESC ;" ;  
	}else{
		echo ("ggggggg");
	}
	$srtSymbl[$_SESSION['srt']]=" &#".$_SESSION['srtKnd']."; ";	
?>
<!-- ========================================================= -->
<!DOCTYPE html>
<html lang="ar">   
	<head>
		<meta charset="UTF-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0" />
		<meta http-equiv="X-UA-Compatible" content="ie=edge" />
		<link rel="stylesheet" href="css/mystyle01.css" />
		<link rel="icon" href="img/icon.png" />
		<title> <?php getTitle(); ?> </title>
		<!-- <style> 
			div.dvTbl table.mTbl th:nth-child(2) , div.dvTbl table.mTbl td:nth-child(2),
			div.dvTbl table.mTbl th:nth-child(6) , div.dvTbl table.mTbl td:nth-child(6),
			div.dvTbl table.mTbl th:nth-child(7) , div.dvTbl table.mTbl td:nth-child(7) {
				display: none;  
			} 
		</style> -->
	</head>
	<body>
		<!-------------------------------- Table ---------------------------------->
		<div class="dvTbl" id="dvTbl">
			<form action= "<?php echo (vldtVrbl($_SERVER['PHP_SELF'])); ?>" method="get">
				<input type="search" name="iSrch" id="srch" placeholder= "بحث بالاسم " value= "<?php echo ($_SESSION['strSrch']); ?>" >
			</form>
			<table class="mTbl" id="mTbl">
				<caption> <?php getTitle(); ?> </caption>
				<thead>
					<tr>
						<th id="tI">م</th>
						<th id="tId"> 		<a href="?srt=0" > <?php echo($srtSymbl[0]); ?> الكود 	 </a> </th>
						<th id="tDt"> 		<a href="?srt=1" > <?php echo($srtSymbl[1]); ?> التاريخ </a> </th>
						<th id="tNmbr"> 	<a href="?srt=2" > <?php echo($srtSymbl[2]); ?> رقم الفاتورة </a> </th>
						<th id="tCstmr"> 	<a href="?srt=3" > <?php echo($srtSymbl[3]); ?> كود العميل/المورد </a> </th>
						<th id="tCstmrN"> 	<a href="?srt=4" > <?php echo($srtSymbl[4]); ?> العميل/المورد </a> </th>
						<th id="tBllKnd"> 	<a href="?srt=5" > <?php echo($srtSymbl[5]); ?> نوع الفاتورة 	</a> </th>
						<th id="tBllKndN"> 	<a href="?srt=6" > <?php echo($srtSymbl[6]); ?> نوع الفاتورة 	</a> </th>
						<th id="tDscnt"> 	<a href="?srt=7" > <?php echo($srtSymbl[7]); ?> الخصم </a> </th>
						<th id="tNts">  	<a href="?srt=8" > <?php echo($srtSymbl[8]); ?> ملاحظات </a> </th>
					</tr>
				</thead>
				<tbody>
					<?php 
						$rc=0;     
						$sql="SELECT * FROM BillHeader WHERE bhBllKnd = ". vldtVrbl($_SESSION['iKnd']). " ". $_SESSION['strSrt']  ; //. " AND cNm like '%".vldtVrbl($_SESSION['strSrch'])."%' "
						$result=CnctDb($sql,'ps');
						foreach($result as $row) {
							echo ("<tr> <td>". ++$rc. "</td> <td>". $row['bhId']. "</td>
										<td>". $row['bhDt']. "</td> <td>". $row['bhNmbr']. "</td> 
										<td>". $row['bhCstmr']."</td> <td>". getNameById("Custumers", "c", $row['bhCstmr'])."</td>
										<td>". $row['bhBllKnd']."</td> <td>". getNameById("Types","t",$row['bhBllKnd'])."</td> 
										<td>". $row['bhDscnt']. "</td> <td>". $row['bhNts']. "</td> </tr>");
						}
						echo ("<tr> <td>". ++$rc. "</td> <td>0</td> <td></td> <td></td><td></td> <td></td> <td></td> <td></td> <td></td> <td></td>	</tr>");
					?>
				</tbody>
				<tfoot>
					<tr>
						<th> * </th>
						<td>   </td>
						<td> ملاحظات </td>
						<td> 	 </td><td> 	 </td>
						<td> 	 </td><td> 	 </td>
						<td> 	 </td><td> 	 </td>
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
				<label class="lbl" for="iDt"> التاريخ : </label>
				<input class="inpt" type="date" name="iDt" id="iDt" />
				<label class="lbl" for="iNmbr"> رقم الفاتورة : </label>
				<input class="inpt" type="number" name="iNmbr" id="iNmbr" maxlength="10"  />
				<label class="lbl" for="iCstmr"> العميل/المورد: </label>
				<select class="inpt" name="iCstmr" id="iCstmr">
					<?php
						$sql="SELECT * FROM Custumers WHERE cGrp IN (SELECT gId FROM Groups WHERE gGrpTyp BETWEEN 2 AND 3) ;" ;
						$result=CnctDb($sql,'ps');
						foreach($result as $row) {
							echo ("<option value=". $row['cId']. ">". $row['cNm']. "</option> ");
						}
					?>
				</select>
				<label class="lbl" for="iDscnt"> الخصم : </label>
				<input class="inpt" type="number" name="iDscnt" id="iDscnt" />
				<label class="lbl" for="iNts">  ملاحظات : </label>
				<input class="inpt" type="text" name="iNts" id="iNts" maxlength="99" />
				
				
				<button class ="btn" type="submit" name="btnSave">حفظ  &#9997;  </button>
				<button class ="btn" type="submit" name="btnDlt"  id="btnDlt" >&#9988; حذف </button> 
				
			</form>
		</div>  
		<!-------------------------------- Script ---------------------------------->
		<script src="js/main.js" ></script>
		<script> 
			var x;
			var tbl01=document.getElementById("mTbl");
			for (x = 1; x < tbl01.rows.length-1; x = x + 1) {
				tbl01.rows[x].ondblclick = function () {
					document.getElementById('iId').value = this.cells[1].innerHTML;
					document.getElementById('iDt').value = this.cells[2].innerHTML;
					document.getElementById('iNmbr').value = this.cells[3].innerHTML;
					document.getElementById('iCstmr').value = this.cells[4].innerHTML;
					//document.getElementById('iBllKnd').value = this.cells[5].innerHTML;
					document.getElementById('iDscnt').value = this.cells[8].innerHTML;
					document.getElementById('iNts').value = this.cells[9].innerHTML;
					showInptScrn();
				}
			}	
		</script>
	</body>
</html>
