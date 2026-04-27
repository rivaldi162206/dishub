<h3 class="page-title">Management User</h3>
<?php

	$id = $_GET['id'];

	$sql = mysqli_query($con,"SELECT * FROM sslogin WHERE ID = $id");
	$row = mysqli_fetch_array($sql);

?>
<div class="row">
	<div class="col-md-8 col-md-offset-2">
		<div class="panel panel-primary">
			<div class="panel-heading">
				<h3 class="panel-title">Form Reset Data Pengguna</h3>
			</div>
			<div class="panel-body">
				<form method="POST" enctype="multipart/form-data">
					<div class="form-group">
						<label class="control-label">Nama Pengguna</label>
						<input name="user" class="form-control" type="text" value="<?php echo $row['SSUSER']; ?>">
					</div>
					<div class="form-group">
						<label class="control-label">Password</label>
						<input name="pass" class="form-control" type="password" placeholder="Masukkan password yang baru">
					</div>					
					<div class="panel-footer">
						<button class="btn btn-primary" name="submit" type="submit"><i class="fa fa-fw fa-lg fa-check-circle"></i>Simpan</button>&nbsp;&nbsp;&nbsp;
						<a class="btn btn-secondary" href="?page=user"><i class="fa fa-fw fa-lg fa-times-circle"></i>Cancel</a>
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

		mysqli_query($con,"INSERT INTO sslog SET AKUN = '$ssuser', LOG = 'Reset Akun = $user', WAKTU = '$datetime' ");
		
		mysqli_query($con,"UPDATE sslogin set
			SSUSER	= '$user',
			SSPASSWORD	= '$pass' WHERE ID = $id ") or die(mysqli_error());
		echo"Data telah tersimpan";
		echo"<meta http-equiv='refresh' content='1; url=?page=user'>";
		}

?>