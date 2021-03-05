<nav class="navbar">
   <ul>
      <li><a class=" " href="dashboard.php">الرئيسية</a></li>
      <li><a class=" " id="" href="#news">News</a></li>
      <li><a class=" " href="#contact">Contact</a></li>
      <li><a class=" " href="#about">About</a></li>
      <li><a class=" "> <?php echo ($_SESSION['logInfo']); ?> </a></li>
      <li><a class=" " href="logout.php" title="<?php echo ($_SESSION['logInfo']); ?>"> خروج </a> </li>
   </ul>
</nav>