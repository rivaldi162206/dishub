<h3 class="page-title">Reset Password User</h3>

<div class="row">
	<div class="col-md-8 col-md-offset-2">
		<div class="panel panel-primary">
			<div class="panel-heading">
				<h3 class="panel-title">Form Reset Password</h3>
			</div>
			<div class="panel-body">
				<form method="POST" enctype="multipart/form-data">
					<div class="form-group">
						<label class="control-label">Masukkan Password Lama</label>
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

		$pass 	= md5(trim($_POST['pass']));
		
		$row = mysqli_num_rows(mysqli_query($con,"SELECT * FROM sslogin WHERE SSPASSWORD = '$pass' "));

		if ($row > 0) {
			echo"<meta http-equiv='refresh' content='1; url=?page=user_res_dat'>";
		}else{
			echo"Password salah";
			echo"<meta http-equiv='refresh' content='1; url=?page=user'>";
		}
	}

?>
