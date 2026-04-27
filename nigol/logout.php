<?php
	if(!empty($_SESSION['id'])){
		include 'database.php';
	    date_default_timezone_set('Asia/Jakarta');
	    $datetime = date('Y-m-d H:i:s');
	    mysqli_query($con,"INSERT INTO sslog SET AKUN = '$ssuser', LOG = 'Logout', WAKTU = '$datetime' ");
		session_destroy();
		$page = $_GET['page'];
		echo "Anda telah keluar, silakan Login Lagi";
		echo"<meta http-equiv='refresh' content='1; url=http://dishub.bangkalankab.go.id/nigol'>";
	}else{
		echo"<meta http-equiv='refresh' content='1; url=http://dishub.bangkalankab.go.id/nigol/'>";
	}
	
?>