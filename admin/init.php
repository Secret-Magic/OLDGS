<?php
   
   ini_set ('display-errors', 'on');
   // error_reporting (E_All) ;

   define ("TPL" , "includes/tpl/") ;
   define ("CSS" , "layout/css/" ) ;
   define ("JS" , "layout/js/" ) ;
   define ("LG" , "includes/lang/" ) ;

   // define ("MG" , "include/images/");
   include_once TPL. 'config.php' ;
   include_once LG. 'en.php' ;
   include_once 'includes/func/myfun.php' ;
   include_once TPL. 'header.php' ;
   if(!isset($nonav)) {
      include_once TPL . 'navbar.php';
      include_once TPL . 'slider.php'; 
   }
