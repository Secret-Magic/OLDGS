<?php
   session_start ();
   if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
      include_once "init.php" ;
      include_once TPL. 'navbar.php' ;
      include_once TPL. 'slider.php' ;
?>
   
      <!-- <img src="layout/imgs/station-02.jpg" alt = "Gas Station" /> -->
   
<?php
      include_once TPL. 'footer.php' ;
   }else {
      header("location: index.php");
      exit ();
   }
?>