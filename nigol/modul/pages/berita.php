<h3 class="page-title">Berita</h3>

		
<a href="?page=berita_rec" class="btn btn-primary btn-sm" style="margin-bottom: 10px;"><i class="fa fa-plus-square-o"></i> New Record</a>
<table id="bootstrap-data-table" class="table table-striped table-bordered">
	<thead>
		<tr>
			<th>No</th>
			<th>Judul</th>
			<th>Berita</th>
			<th width="250px">Gambar</th>
			<th>Opsi</th>
		</tr>
	</thead>
	<tbody>
		<?php
			$no = 0;
			$sql = mysqli_query($con,"SELECT * FROM tb_berita ORDER BY ID DESC");
			while ($row = mysqli_fetch_array($sql)) {
				$no++;
		?>
				<tr>
					<td><?php echo $no;?></td>
					<td><?php echo $row['JUDUL'];?></td>
					<td><?php echo substr($row['BERITA'],0,350)." . . . . . ."; ?></td>
					<td><img src="../images/berita/<?php echo $row['IMAGE']; ?>" width="60%"></td>
					<td>
						<div class="bs-component" style="margin-bottom: 15px;">
							<div class="btn-group" role="group" aria-label="Basic example">
								<a href="?page=berita_edit&id=<?php echo $row['ID']; ?>" class="btn btn-warning btn-sm" ><i class="fa fa-pencil-square-o"></i></a>
								<a href="?page=berita_hapus&id=<?php echo $row['ID']; ?>" class="btn btn-danger btn-sm" ><i class="fa fa-trash"></i></a>
							</div>
			            </div>
					</td>
				</tr>
		<?php
			}
		?>
	</tbody>
</table>