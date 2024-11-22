<?php
include "conn.inc";
session_start() ;
if ($_SESSION['roles']=='admin' )
{
include "footer.inc";
	if (isset ($_POST ['edit']))
	{
	$id = $_REQUEST ['id'] ;
	$user=$_POST ['username'];
	$roles=$_POST ['roles'];
	$status =$_POST ['status'];
	$email =$_POST ['email'];
	$sql="UPDATE login set username= '".$user."', roles ='".$roles."', email= '".$email."', status ='".$status."' where id='".$id."'";
	if($result= mysqli_query($conn, $sql) )
		{
		echo "User is updated";
		header('location:manageuser.php');
		}
	}
}
?>