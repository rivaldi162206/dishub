<h3 class="page-title">Dokumen</h3>


<div class="row">
	<div class="col-md-8 col-md-offset-2">
		<div class="panel panel-primary">
			<div class="panel-heading">
				<h3 class="panel-title">Form Menambah Data Berita</h3>
			</div>
			<div class="panel-body">
				<form method="POST" enctype="multipart/form-data">
					<div class="form-group">
						<label class="control-label">Judul</label>
						<input name="judul" class="form-control" type="text" placeholder="Masukkan judul dokumen">
					</div>
					<div class="form-group">
						<label class="control-label">Gambar</label>
						<input name="gambar" class="form-control" type="file">
					</div>
					<div class="form-group">
						<label class="control-label">Dokumen</label>
						<input name="dokumen" class="form-control" type="file">
					</div>
					<div class="form-group">
						<label class="control-label">Kategori</label>
						<select class="form-control" name="kategori">
						  <option value="renstra">Renstra</option>
						  <option value="renja">Renja</option>
						  <option value="sakip">Sakip</option>
						  <option value="lakip">Lakip</option>
						  <option value="iku">IKU</option>
						  <option value="pk">Perjanjian Kinerja</option>
						  <option value="pad">PAD</option>
						  <option value="uji kendaraan">Uji Kendaraan</option>
						</select>
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
		$kategori   = $_POST['kategori'];
		$tanggal 	= date('Ymd');

		if($lokasi_gambar=="" || $file_tmp ==""){
			mysqli_query($con,"INSERT INTO tb_dokumen set
					JUDUL	= '$judul',
					KATEGORI = '$kategori',
					TANGGAL	= '$tanggal'
				") or die(mysqli_error());
		}else{
			if(in_array($ekstensi, $ekstensi_diperbolehkan) === true){
				if($ukuran < 20044070){	
					move_uploaded_file($lokasi_gambar,"../images/file/$nama_gambar");
					move_uploaded_file($file_tmp,"../images/file/$dokumen");
					$query = mysqli_query($con,"INSERT INTO tb_dokumen set
							JUDUL	= '$judul',
							DOKUMEN	= '$dokumen',
							KATEGORI = '$kategori',
							TANGGAL	= '$tanggal',
							IMAGE 	= '$nama_gambar'
						") or die(mysqli_error());
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

		mysqli_query($con,"INSERT INTO sslog SET AKUN = '$ssuser', LOG = 'Input Dokuemn', WAKTU = '$datetime' ");
		
		echo"<meta http-equiv='refresh' content='1; url=?page=dokumen'>";
		
	}

?>