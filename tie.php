<?php
chdir('C:\AppServ\www\jhs');
//chdir('/var/www/jhs');
require_once("becomedrupal.php");

// 取Drupal個人資料
global $user;

       $username = '' . $user->name;
       $token = '' . $user->pass;

	echo "<script language='JavaScript'>";
	echo "localStorage.username = ' " .  $username . " ' ;"; 
	echo "localStorage.token = ' " . $token . " ' ;";
	echo "</script>";
?>
