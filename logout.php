<?php
session_start();
unset($_SESSION["sessionusersid"]);
header("Location: login.php");
?>