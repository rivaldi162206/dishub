<?php
	mysqli_query($con,"INSERT INTO sslog SET AKUN = '$ssuser', LOG = 'Hapus Foto dengan ID = $_GET[id]', WAKTU = '$datetime' ");
	$data=mysqli_fetch_array(mysqli_query($con,"SELECT * FROM tb_galeri WHERE ID='$_GET[id]'"));
	if($data['IMAGE'] != "") unlink("../images/galeri/$data[IMAGE]");

	mysqli_query($con,"DELETE FROM tb_galeri WHERE ID='$_GET[id]'") or die(mysqli_error());
	echo"Data telah dihapus";
	echo"<meta http-equiv='refresh' content='1; url=?page=galeri'>";

?>