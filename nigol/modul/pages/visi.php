<h3 class="page-title">Visi</h3>
<?php

	$sql = mysqli_query($con,"SELECT * FROM tb_visi");
	$row = mysqli_fetch_array($sql);

?>
<div class="content mt-3">
	<div class="row">
		<div class="col-md-2"></div>
		<div class="col-md-8">
      <div class="panel">
        <div class="panel-heading">
          <h3 class="panel-title">Data Visi</h3>
        </div>
        <div class="panel-body">
          <form method="post">
            <div class="form-group">
                <textarea name="visi" type="text" class="form-control" rows="5"><?php echo $row['VISI']; ?></textarea>
            </div>
            <div>
                <button type="submit" name="submit" class="btn btn-lg btn-info btn-block">
                    Simpan
                </button>
            </div>
          </form>
        </div>
      </div>
		</div>
		<div class="col-md-2"></div>
	</div>
</div>

<?php

	if (isset($_POST['submit'])) {
				
		$visi 	= $_POST['visi'];

		mysqli_query($con,"INSERT INTO sslog SET AKUN = '$ssuser', LOG = 'Edit Visi', WAKTU = '$datetime' ");
		
		mysqli_query($con,"UPDATE tb_visi set
			VISI	= '$visi' ") or die(mysqli_error());
		echo"<meta http-equiv='refresh' content='1; url=?page=visi'>";
		}

?>