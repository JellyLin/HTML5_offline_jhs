<?php
chdir('C:\AppServ\www\jhs');
//chdir('/var/www/jhs');
require_once("becomedrupal.php");

// 取Drupal個人資料
global $user;

echo $user->name;

?>
