<h3 class="page-title">Dokumen</h3>

		
<a href="?page=dokumen_rec" class="btn btn-primary btn-sm" style="margin-bottom: 10px;"><i class="fa fa-plus-square-o"></i> New Record</a>
<table id="bootstrap-data-table" class="table table-striped table-bordered">
	<thead>
		<tr>
			<th width="15px">No</th>
			<th>Judul</th>
			<th>Dokumen</th>
			<th>Kategori</th>
			<th width="250px">Gambar</th>
			<th width="20px">Opsi</th>
		</tr>
	</thead>
	<tbody>
		<?php
			$no = 0;
			$sql = mysqli_query($con,"SELECT * FROM tb_dokumen ORDER BY ID DESC");
			while ($row = mysqli_fetch_array($sql)) {
				$no++;
		?>
				<tr>
					<td><?php echo $no;?></td>
					<td><?php echo $row['JUDUL'];?></td>
					<td><?php echo $row['DOKUMEN']; ?></td>
					<td><?php echo $row['KATEGORI']; ?></td>
					<td><img src="../images/file/<?php echo $row['IMAGE']; ?>" width="60%"></td>
					<td>
						<div class="bs-component" style="margin-bottom: 15px;">
							<div class="btn-group" role="group" aria-label="Basic example">
								<!-- <a href="?page=dokumen_edit&id=<?php echo $row['ID']; ?>" class="btn btn-warning btn-sm" ><i class="fa fa-pencil-square-o"></i></a> -->
								<a href="?page=dokumen_hapus&id=<?php echo $row['ID']; ?>" class="btn btn-danger btn-sm" ><i class="fa fa-trash"></i></a>
							</div>
			            </div>
					</td>
				</tr>
		<?php
			}
		?>
	</tbody>
</table>