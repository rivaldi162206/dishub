<h3 class="page-title">Management User</h3>

<div class="row">
	<div class="col-md-8 col-md-offset-2">
		<div class="panel panel-primary">
			<div class="panel-heading">
				<h3 class="panel-title">Form Menambah Data Pengguna</h3>
			</div>
			<div class="panel-body">
				<form method="POST" enctype="multipart/form-data">
					<div class="form-group">
						<label class="control-label">Nama Pengguna</label>
						<input name="user" class="form-control" type="text" placeholder="Masukkan nama pengguna">
					</div>
					<div class="form-group">
						<label class="control-label">Password</label>
						<input name="pass" class="form-control" type="password" placeholder="Masukkan password">
					</div>					
					<div class="panel-footer">
						<a href="?page=user"  class="btn btn-info btn-sm">
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

		$user 	= $_POST['user'];
		$pass 	= md5(trim($_POST['pass']));

		mysqli_query($con,"INSERT INTO sslog SET AKUN = '$ssuser', LOG = 'New Akun = $user', WAKTU = '$datetime' ");
		
		mysqli_query($con,"INSERT INTO sslogin set
			SSUSER	= '$user',
			SSPASSWORD	= '$pass',
			LEVEL = 'operator'
		") or die(mysqli_error());
		echo"Data telah tersimpan";
		echo"<meta http-equiv='refresh' content='1; url=?page=user'>";
		}

?>