<?php
session_start();
unset($_SESSION['user']);
echo"Goodbye!";
header("location:loginpharmacy.html");
?>