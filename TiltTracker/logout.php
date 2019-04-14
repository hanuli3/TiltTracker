<?php
require_once "config.php"; include("riot-methods.php");

//original source: // original source: https://daveismyname.blog/login-and-registration-system-with-php
// Initialize the session
session_start();

// Unset all of the session variables
$_SESSION = array();
 
// Destroy the session.
session_destroy();
 
if(!isset($_COOKIE["summoner"])) 
{setcookie("summoner", "", time() - 3600);}

// Redirect to login page
header("location: login.php");
exit;
?>