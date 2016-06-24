<?php
ob_start();
session_start();

date_default_timezone_set('Europe/London');

define('DBHOST','localhost');
define('DBUSER','root');
define('DBPASS','');
define('DBNAME','rpg_db');

define('DIR','http://localhost:8080/rpg');
define('SITEEMAIL','noreply@rpgo.com');

try{
   $db = new PDO("mysql:host=" .DBHOST.";dbname=".DBNAME, DBUSER, DBPASS);
   $db->setAttribute(PDO:: ATTR_ERRMODE, PDO:: ERRMODE_EXCEPTION);
}
catch(PDOException $e){
   echo '<p class="bg-danger">'.$e->getMessage().'</p>';
   exit();
}

include ('classes/user.php');
include ('classes/phpmailer/mail.php');
$user = new User($db);
?>
