<h3 class="page-title">Konfirmasi Reset Password User</h3>

<div class="row">
	<div class="col-md-8 col-md-offset-2">
		<div class="panel panel-primary">
			<div class="panel-heading">
				<h3 class="panel-title">Form Password Konfirmasi</h3>
			</div>
			<div class="panel-body">
				<form method="POST" enctype="multipart/form-data">
					<div class="form-group">
						<label class="control-label">Masukkan Password Baru</label>
						<input name="pass1" class="form-control" type="password" placeholder="Masukkan password">
					</div>
					<div class="form-group">
						<label class="control-label">Konfirmasi Password Baru</label>
						<input name="pass2" class="form-control" type="password" placeholder="Masukkan password">
					</div>					
					<div class="panel-footer">
						<a href="?page=user_res"  class="btn btn-info btn-sm">
							<i class="fa fa-reply"></i> Kembali
						</a>
						<button type="submit" name="submit" class="btn btn-success btn-sm">
							<i class="fa fa-dot-circle-o"></i> Simpan
						</button>
					</div>
				</form>
			</div>
		</div>
    </div>
</div>

<?php

	if (isset($_POST['submit'])) {

		$pass1 	= md5(trim($_POST['pass1']));
		$pass2 	= md5(trim($_POST['pass2']));

		if ($pass1 == $pass2) {
			mysqli_query($con,"UPDATE sslogin SET SSPASSWORD	= '$pass1' WHERE ID = $ssid ") or die(mysqli_error());
			mysqli_query($con,"INSERT INTO sslog SET AKUN = '$ssuser', LOG = 'Reset Password', WAKTU = '$datetime' ");
			echo"Data telah disimpan, silahkan login kembali";
			echo"<meta http-equiv='refresh' content='1; url=?page=keluar'>";
		}else{
			echo"Password tidak sama";
			echo"<meta http-equiv='refresh' content='1; url=?page=user_res_dat'>";
		}
		
		
	}

?>