<?php
include "conn.inc";
session_start();
if ($_SESSION['roles']=='admin') 
{
	include "footer.inc";
	$id = $_REQUEST['id'];
	// sending query
	$sql= "DELETE FROM login WHERE id ='".$id."'";
	mysqli_query($conn, $sql) or die(mysql_error());
	header ("Location: manageuser.php");
}
else
{
echo "You are not allowed to delete this";	
}
?>