<?php
include "conn.inc";
$id=$_REQUEST['id'];
$sql="select * from login where id ='".$id."'"; 
$result=mysqli_query($conn, $sql);
$row=mysqli_fetch_array($result);
include'footer.inc';
?>
<form name="form" method="post" action="updateuser.php?id=<?php echo $id; ?>">
<input name="id" type="hidden" value="<?php echo $id;?>"/>
<p>Username <input type="text" name="username" required value="<?php echo $row['username']; ?>"/></p> 
<p>Roles<input type="text" name="roles" required value="<?php echo $row['roles']; ?>"/></p> 
<p>Email<input type="text" name="email" required value="<?php echo $row['email']; ?>" /></p>
<p>Status<input type="text" name="status" required value="<?php echo $row['status']; ?>"/></p> 
<p><input name="edit" type="submit" value="Update"/></p>
</form>