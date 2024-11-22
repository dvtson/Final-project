<?php
include"conn.inc";
session_start();
$username =$_POST['username'];
$password =$_POST['password'];
$sql="select username, password, roles from login where
username ='".$username."'AND password ='".$password."'";

$result=mysqli_query($conn,$sql);

$sql2="select roles from login where username ='".$username."'";
$result2=mysqli_query($conn,$sql2);
$row=mysqli_fetch_array($result2);
if ($row>0)
{
$role=$row['roles'];
$_SESSION['roles']=$role;
}
if(mysqli_num_rows($result)>0&&$role=='admin')
{
$_SESSION['user']=$username;

header("location:admindex.php");
}elseif(mysqli_num_rows($result)>0&&$role
!=='admin')
{
echo"You are not allowed to view this page";
}
else
{
echo"Incorrect user and password. Login again!<br><br>";
require"loginpharmacy.html";
}
?>