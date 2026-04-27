<h3 class="page-title">Misi</h3>
<?php

	$id = $_GET['id'];
	$id 	= mysqli_real_escape_string($con,stripslashes(strip_tags(htmlspecialchars($id,ENT_QUOTES))));

	$sql = mysqli_query($con,"SELECT * FROM tb_misi WHERE ID = $id");
	$row = mysqli_fetch_array($sql);

?>
<div class="row">
	<div class="col-md-6 col-md-offset-3">
		<div class="panel panel-primary">
			<div class="panel-heading">
				<h3 class="panel-title">Form Mengubah Data Misi</h3>
			</div>
			<div class="panel-body">
				<form method="POST" enctype="multipart/form-data">
					<div class="form-group">
						<label class=" form-control-label">Misi</label>
						<textarea type="text" name="misi" placeholder="Masukkan misi" class="form-control" rows="6"><?php echo $row['MISI']; ?></textarea>
					</div>
					<div class="panel-footer">						
						<a href="?page=misi"  class="btn btn-info btn-sm">
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

		$misi = $_POST['misi'];

		mysqli_query($con,"UPDATE tb_misi set
			MISI	= '$misi'
			WHERE ID = $id
		") or die(mysqli_error());
		mysqli_query($con,"INSERT INTO sslog SET AKUN = '$ssuser', LOG = 'Edit Misi dengan ID = $id', WAKTU = '$datetime' ");
		echo"Data telah tersimpan";
		echo"<meta http-equiv='refresh' content='1; url=?page=misi'>";
		}

?>