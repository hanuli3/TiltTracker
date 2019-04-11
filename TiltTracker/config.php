<?php
   define('DB_SERVER', 'localhost');
   define('DB_USERNAME', 'admin');
   define('DB_PASSWORD', 'password');
   define('DB_DATABASE', 'tiltbase');
   $link = mysqli_connect(DB_SERVER,DB_USERNAME,DB_PASSWORD,DB_DATABASE);

   if($link === false){
      die("ERROR: Could not connect. " . mysqli_connect_error());
  }
?>