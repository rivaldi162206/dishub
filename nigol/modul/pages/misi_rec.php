<h3 class="page-title">Misi</h3>


<div class="row">
	<div class="col-md-6 col-md-offset-3">
		<div class="panel panel-primary">
			<div class="panel-heading">
				<h3 class="panel-title">Form Menambah Data Misi</h3>
			</div>
			<div class="panel-body">
				<form method="POST" enctype="multipart/form-data">
					<div class="form-group">
						<label class=" form-control-label">Misi</label>
						<textarea type="text" name="misi" placeholder="Masukkan misi" class="form-control" rows="6"></textarea>
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

		mysqli_query($con,"INSERT INTO tb_misi set
			MISI	= '$misi'
		") or die(mysqli_error());
		mysqli_query($con,"INSERT INTO sslog SET AKUN = '$ssuser', LOG = 'Input Misi', WAKTU = '$datetime' ");
		echo"Data telah tersimpan";
		echo"<meta http-equiv='refresh' content='1; url=?page=misi'>";
		}

?>