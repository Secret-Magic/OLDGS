<?php
	
	//----------------------------
   function getRC ($sql, $vl) {
      global $conn;
      $stmt = $conn->prepare ($sql) ;
      $stmt->execute ($vl);
        //$row = $stmt->fetch ();
		$rc = $stmt->rowCount() ;
		$stmt->closeCursor ();
		$stmt=NULL ;
		return $rc ;
   }
	//----------------------------
	function isSaved ($fld, $tbl, $vlu, $fld2="") {
		$sql="SELECT $fld FROM $tbl WHERE $fld=?". $fld2;
		$vl=array($vlu);
		// echo "<br> $sql" ;
		return (getRC($sql, $vl) > 0) ;
   }
    //----------------------------
   function getRows ($sql) {
      global $conn;
		$stmt = $conn->prepare ($sql) ;
		$stmt->execute ();
		$rows =  $stmt->fetchall ();
		$stmt->closeCursor ();
		$stmt=NULL ;
		return $rows ;
   }
	//----------------------------
	function getRow ($sql, $vl) {
      global $conn;
		$stmt = $conn->prepare ($sql) ;
		$stmt->execute ($vl);
		$row = $stmt->fetch ();
		$stmt->closeCursor ();
		$stmt=NULL ;
		return $row ;
   }
    //----------------------------
	function vldtVrbl($data) {
		$data = trim($data);
		$data = stripslashes($data);
		$data = htmlspecialchars($data);
        //email : #^[_a-z0-9-]+@[a-z0-9._-]{2,}\.[a-z]{2,}$#
		return $data;
	}
	//----------------------------
	function getNameById($Table, $Fld, $Id) {
      $sql="SELECT * FROM ". $Table ." WHERE `". $Fld."Id` = ? " ;
      $vl=array ($Id);
		$result=getRow($sql, $vl);
		return $result[$Fld."Nm"];
	}
	//----------------------------
	function getTitle() {
		global $pageTitle;
		if (isset($pageTitle)) {
			echo $pageTitle;
		}
		else {
			echo "Title";
		}
   }
   
?>