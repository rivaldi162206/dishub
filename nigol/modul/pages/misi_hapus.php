<?php
	mysqli_query($con,"INSERT INTO sslog SET AKUN = '$ssuser', LOG = 'Hapus Misi dengan ID = $_GET[id]', WAKTU = '$datetime' ");

	mysqli_query($con,"DELETE FROM tb_misi WHERE ID='$_GET[id]'") or die(mysqli_error());
	echo"Data telah dihapus";
	echo"<meta http-equiv='refresh' content='1; url=?page=misi'>";

?>