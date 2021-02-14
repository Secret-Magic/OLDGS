<nav class="navbar">
      <ul>
            <li><a class="active" href="dashboard.php">الرئيسية</a></li>
            <li><a href="#news">News</a></li>
            <li><a href="#contact">Contact</a></li>
            <li><a href="#about">About</a></li>
            <li><a> <?php echo ($_SESSION['logInfo']); ?> </a></li>
            <li><a href="logout.php" title="<?php echo ($_SESSION['logInfo']); ?>"> خروج </a> </li>
      </ul>
</nav>