<?php
$host = "localhost"; //$_SERVER['SERVER_NAME'];
$user = "root";
$pass = "";
$db   = "data01";
$optn = array(
	PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"
);

try {
	$conn = new PDO("mysql:host=$host;dbname=$db", $user, $pass, $optn);
	$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	echo "<br/> Connected successfully <br/>";
} catch (PDOException $e) {
	//$conn->rollback();
	echo "Connection failed: " . $e->getMessage();
}
