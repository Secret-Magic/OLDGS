<?php
session_start();
if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
   header("location: dashboard.php");
   exit();
}
$nonav = "";
include_once "init.php";
$user = "";
$pass = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
   $user = vldtVrbl($_POST['user']);
   $pass = vldtVrbl($_POST['pass']);
   $sub = intval($_POST['iSub']);
   $hashedpass = sha1($pass);
   // إضافة مقطع نصى لكلمة السر

   // $sql = "SELECT * FROM UserLog INNER JOIN Accounts ON ulUsrId=aId WHERE ulUsr = ? AND ulPsswrd = ? AND (aSub=0 OR aSub = ?)";
   // $vl = array($user, $hashedpass) ;

   $sql = "SELECT * FROM UserLog WHERE ulUsr = ? AND ulPsswrd = ? ";
   $vl = array($user, $hashedpass);
   if (getRC($sql, $vl) == 1) {
      $result = getRow($sql, $vl);
      if ($result['ulMxLg'] == 1) {
         $_SESSION["loggedin"] = FALSE;
         $report = "غير مسموح بالدخول";
      } else {
         $sql = "SELECT aSub, aGrp FROM Accounts WHERE aId=?";
         $vl = array($result['ulUsrId']);
         $result2 = getRow($sql, $vl);
         if ($result2['aSub'] == 0 || $result2['aSub'] == $sub) {
            $_SESSION['loggedin'] = TRUE;
            $_SESSION['UserId'] = $result['ulUsrId'];
            $_SESSION['Sub'] = $sub;
            $_SESSION['logInfo'] = 'المستخدم: ' . getNameById('Accounts', 'a', $_SESSION['UserId']) . '=== الفرع: ' . getNameById('Subsidiary', 'ss', $_SESSION['Sub']);
            if ($result['ulMxLg'] > 1) {
               $sql = "UPDATE UserLog SET ulMxLg = ulMxLg - 1 WHERE ulId = ?";
               $vl = array($result['ulId']);
               getRC($sql, $vl);
            }
            //elseif ($result['ulMxLg'] == 0) {
            header('Location:dashboard.php');
            exit();
         } else {
            $_SESSION["loggedin"] = FALSE;
            $report = "غير مسموح بدخول هذا الفرع";
         }
      }
   } else {
      $_SESSION["loggedin"] = FALSE;
      $report = "اسم المستخدم أو كلمة المرور غير صحيحة";
   }
}
?>
<div class="LoginForm">
   <img src="layout/imgs/bk1.png" alt="pump" />
   <form class="login" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
      <img src="layout/imgs/login.png" alt="lock" />
      <h3> شاشة الدخول </h3>
      <input class="inpt" type="text" name="user" placeholder="اسم المستخدم" autocomplete="off" value="<?php echo ($user); ?>" />
      <input class="inpt" type="password" name="pass" placeholder="كلمة المرور" autocomplete="new-password" value="<?php echo ($pass); ?>" />
      <select class="inpt" name="iSub" id="iSub">
         <?php
         $sql = "SELECT * FROM Subsidiary WHERE ssWrk=1";
         $result = getRows($sql);
         foreach ($result as $row) {
            $sl = "";
            if ($row['ssId'] == $sub) {
               $sl = " selected ";
            }
            echo ("<option value = '" . $row['ssId'] . "' " . $sl . " >" . $row['ssNm'] . "</option> ");
         }
         ?>
      </select>
      <input class="btn inpt" type="submit" value="دخول" />
   </form>
   <!--   وهومية لحجز مساحة لتوسيط شاشة الادخال لكى تعادل الصورة div -->
   <div></div>
</div>
<section>
   <article>
      <?php
      include_once TPL . 'footer.php';
      ?>