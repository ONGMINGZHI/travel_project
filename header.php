<?php
session_start();
$db = new PDO("mysql:host=localhost;dbname=traveldb", "root", "");
if(!isset($_SESSION['user'])){
    header("Location: login.php");
    exit;
}

?>