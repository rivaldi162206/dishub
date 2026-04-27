<h3 class="page-title">Dokumen</h3>
<?php

	$id = $_GET['id'];

	$sql = mysqli_query($con,"SELECT * FROM tb_dokumen WHERE ID = $id");
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
						<img src="../images/file/<?php echo $row['IMAGE']; ?>" width="20%" class="border border-primary img-thumbnail">
						<input name="gambar" class="form-control" type="file">
					</div>
					<div class="form-group">
						<label class="control-label" style="margin: 0 30px 35px 0;">Dokumen</label>
						<p><?php echo $row['DOKUMEN']; ?></p>
						<input name="dokumen" class="form-control" type="file">
					</div>					
					<div class="panel-footer">
						<a href="?page=dokumen"  class="btn btn-info btn-sm">
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
		
	$ekstensi_diperbolehkan	= array('docx','doc','xls','xlsx','pdf');
		$dokumen = $_FILES['dokumen']['name'];
		$x = explode('.', $dokumen);
		$ekstensi = strtolower(end($x));
		$ukuran	= $_FILES['dokumen']['size'];
		$file_tmp = $_FILES['dokumen']['tmp_name'];

		$nama_gambar 	= $_FILES['gambar']['name'];
		$lokasi_gambar 	= $_FILES['gambar']['tmp_name'];
		$tipe_gambar	= $_FILES['gambar']['type'];

		$judul 		= $_POST['judul'];
		$tanggal 	= date('Ymd');


		if($lokasi_gambar=="" || $file_tmp ==""){
			mysqli_query($con,"UPDATE tb_dokumen SET
					JUDUL	= '$judul',
					TANGGAL	= '$tanggal' WHERE ID = $id ") or die(mysqli_error());
		}else{
			if(in_array($ekstensi, $ekstensi_diperbolehkan) === true){
				if($ukuran < 20044070){	
					$img = $row['IMAGE'];
					$doc = $row['DOKUMEN'];
					unlink("../images/file/$img");
					unlink("../images/file/$doc");
					move_uploaded_file($lokasi_gambar,"../images/file/$nama_gambar");
					move_uploaded_file($file_tmp,"../images/file/$dokumen");
					$query = mysqli_query($con,"UPDATE tb_dokumen SET
							JUDUL	= '$judul',
							DOKUMEN	= '$dokumen',
							TANGGAL	= '$tanggal',
							IMAGE 	= '$nama_gambar' WHERE ID = $id ") or die(mysqli_error());
					if($query){
							echo 'Data Telah Tersimpan';
					}else{
							echo 'Gagal Menyimpan';
						}
				}else{
					echo 'Ukuran File Terlalu Besar';
				}
			}else{
				echo 'Ekstensi tidak diperbolehkan';
			}
		}
		mysqli_query($con,"INSERT INTO sslog SET AKUN = '$ssuser', LOG = 'Edit Dokumen dengan ID = $id', WAKTU = '$datetime' ");
		
		echo"<meta http-equiv='refresh' content='1; url=?page=dokumen'>";
	}

?>