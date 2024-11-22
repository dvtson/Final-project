<?php
include "conn.inc";
session_start () ;
if(isset ($_POST['submit'])&& !empty($_POST['username']))
{
	$sql="select username from login where username LIKE '".$_POST['username']."'";
	if ($result=mysqli_query($conn, $sql) )
	{
	if (mysqli_num_rows ($result)==0)
	{
		$sql="INSERT INTO login(username, password, roles, status, email) VALUES ('".$_POST ['username']."', '".$_POST['password']."',
		'".$_POST['roles']."','".$_POST ['status']."','".$_POST['email']."')";
		if (mysqli_query ($conn, $sql) )
		echo "New user is created";
		else
		die (mysqli_error) ;
	}
	else
	{ 
	echo "User exists! Try another one!";
	}
	}
}
include "footer.inc";
?>