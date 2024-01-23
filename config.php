<?php
	
	session_start();
	if(!isset($_SESSION["UserId"])){
		//header("Location: login.php");
		//header ('Refresh:5;URL= chat.php');
		//exit(); 
	}
	else{
		// echo "<pre>";
		// 	print_r ($_SESSION);
		// echo "</pre>";
	}
	//session_unset();
	//session_destroy();

	//$sql="DELETE FROM Users WHERE uId=5;";
	
	//CnctDb($sql,'s');
	// echo "<pre>";
	// print_r ($rows);
	// echo "</pre>";

	function CnctDb($sql, $st="s") {
		$host = $_SERVER['SERVER_NAME'];
		$user = "root";
		$pass = "";
		$db   = "data01";
		$optn =array(
			PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"
		);
			
		try {
			$conn = new PDO("mysql:host=$host;dbname=$db", $user, $pass,$optn);
			$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			//echo "<br/> Connected successfully <br/>";
			//-----------------------------------------------
			if ($st=='s') { 
				$cRows=$conn->exec($sql);
				$last_id = $conn->lastInsertId();
				//echo $cRows . " records UPDATED successfully";
				//echo $sql. " :is created successfully. Last inserted ID is: ". $last_id;
				return $last_id;
			}	//-----------------------------------------------
			elseif ($st=="m") {
				$conn->beginTransaction();
				foreach($sql as $stmt) {
					$conn->exec($stmt);
				}
				$conn->commit();
				//echo "Multi records created successfully";
			}	//-----------------------------------------------
			elseif ($st=="ps") {
				$stmt = $conn->prepare($sql);
				$stmt->execute();
				//echo $stmt->rowCount() . " records UPDATED successfully";
				$rows=$stmt->fetchall(PDO::FETCH_ASSOC);
				$stmt->closeCursor();
				return $rows;
			}	//-----------------------------------------------
			elseif ($st=="psr") {
				$stmt = $conn->prepare($sql[0]);
				$stmt->execute($sql[1]);
				$cRows=$stmt->rowCount();
				//echo $stmt->rowCount() . " records UPDATED successfully";
				$stmt->closeCursor();
				return $cRows;
			} //-----------------------------------------------
			elseif ($st=="pm") {
				$stmt = $conn->prepare("INSERT INTO MyGuests (firstname, lastname, email) 
				VALUES (:firstname, :lastname, :email)");
				$stmt->bindParam(':firstname', $firstname);
				$stmt->bindParam(':lastname', $lastname);
				$stmt->bindParam(':email', $email);

				$stmt->bindValue(':uid', (int)$uid, PDO::PARAM_INT);
				$stmt->bindValue(':tid', (int)$tid, PDO::PARAM_INT);
				// insert a row
				$firstname = "John";
				$lastname = "Doe";
				$email = "john@example.com";
				$stmt->execute();
				// insert another row
				$firstname = "Mary";
				$lastname = "Moe";
				$email = "mary@example.com";
				$stmt->execute();
				// insert another row
				$firstname = "Julie";
				$lastname = "Dooley";
				$email = "julie@example.com";
				$stmt->execute();
				echo "New records created successfully";
				$stmt->closeCursor();
			} 
		}
		catch(PDOException $e) {
      		$conn->rollback();
			echo "Connection failed: ". $e->getMessage();
		}
		$stmt=NULL;
		$conn=NULL;
	}
	
