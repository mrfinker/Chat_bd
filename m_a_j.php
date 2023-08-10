<?php
include("connexion.php");
session_start();
$query = "UPDATE login_details SET last_activity = NOW() WHERE login_details_id = '".$_SESSION['login_details_id']."'";
$statement = $connection->prepare($query);
$statement->execute();
?>