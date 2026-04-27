<h3 class="page-title">Berita</h3>
<?php

	$id = $_GET['id'];

	$sql = mysqli_query($con,"SELECT * FROM tb_berita WHERE ID = $id");
	$row = mysqli_fetch_array($sql);

?>

<div class="row">
	<div class="col-md-8 col-md-offset-2">
		<div class="panel panel-primary">
			<div class="panel-heading">
				<h3 class="panel-title">Form Edit Data Berita</h3>
			</div>
			<div class="panel-body">
				<form method="POST" enctype="multipart/form-data">
					<div class="form-group">
						<label class="control-label">Judul</label>
						<input name="judul" class="form-control" type="text" value="<?php echo $row['JUDUL']; ?>">
					</div>
					<div class="form-group">
						<label class="control-label" style="margin: 0 30px 35px 0;">Gambar</label>
						<img src="../images/berita/<?php echo $row['IMAGE']; ?>" width="20%" class="border border-primary img-thumbnail">
						<input name="gambar" class="form-control" type="file">
					</div>
					<div class="form-group">
					<label class="control-label">Berita</label>
						<textarea name="berita" class="form-control" id="summernote" ><?php echo $row['BERITA']; ?></textarea>
					</div>					
					<div class="panel-footer">
						<a href="?page=berita"  class="btn btn-info btn-sm">
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
				
		$nama_gambar 	= $_FILES['gambar']['name'];
		$lokasi_gambar 	= $_FILES['gambar']['tmp_name'];
		$tipe_gambar	= $_FILES['gambar']['type'];

		$judul 		= $_POST['judul'];
		$berita 	= $_POST['berita'];
		$tanggal 	= date('Ymd');

		$ekstensi_diperbolehkan = array('jpg','jpeg','png');
	    $x = explode('.', $nama_gambar);
	    $ekstensi = strtolower(end($x));


		if($lokasi_gambar==""){
			mysqli_query($con,"UPDATE tb_berita SET
					JUDUL	= '$judul',
					BERITA	= '$berita',
					TANGGAL	= '$tanggal' WHERE ID = $id ") or die(mysqli_error());
			mysqli_query($con,"INSERT INTO sslog SET AKUN = '$ssuser', LOG = 'Edit Berita dengan ID = $id', WAKTU = '$datetime' ");
			echo "Data telah tersimpan";
		}else{
			if(in_array($ekstensi, $ekstensi_diperbolehkan) === true){
				$img = $row['IMAGE'];
				unlink("../images/berita/$img");
				move_uploaded_file($lokasi_gambar,"../images/berita/$nama_gambar");
				mysqli_query($con,"UPDATE tb_berita SET
						JUDUL	= '$judul',
						BERITA	= '$berita',
						TANGGAL	= '$tanggal',
						IMAGE 	= '$nama_gambar' WHERE ID = $id ") or die(mysqli_error());
				mysqli_query($con,"INSERT INTO sslog SET AKUN = '$ssuser', LOG = 'Edit Berita dengan ID = $id', WAKTU = '$datetime' ");
				echo "Data telah tersimpan";
			}else{
				echo "Ekstensi Tidak Diperbolehkan";
			}
		}
		echo "<meta http-equiv='refresh' content='1; url=?page=berita'>";
	}

?>