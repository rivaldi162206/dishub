<?php
	$data = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM sslogin WHERE ID ='$_GET[id]' "));
	mysqli_query($con,"INSERT INTO sslog SET AKUN = '$ssuser', LOG = 'Delete Akun = $data[SSUSER]', WAKTU = '$datetime' ");
	mysqli_query($con,"DELETE FROM sslogin WHERE ID='$_GET[id]' ") or die(mysqli_error());
	echo"Data telah dihapus";
	echo"<meta http-equiv='refresh' content='1; url=?page=user'>";

?>