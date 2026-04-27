<h3 class="page-title">Galeri</h3>

<a href="?page=galeri_rec" class="btn btn-primary btn-sm" style="margin-bottom: 10px;"><i class="fa fa-plus-square-o"></i> New Record</a>
<table id="bootstrap-data-table" class="table table-hover table-bordered" id="sampleTable">
	<thead>
		<tr>
			<th>No</th>
			<th>Judul</th>
			<th width="150px">Gambar</th>
			<th>Opsi</th>
		</tr>
	</thead>
	<tbody>
		<?php
			$no = 0;
			$sql = mysqli_query($con,"SELECT * FROM tb_galeri ORDER BY ID DESC");
			while ($row = mysqli_fetch_array($sql)) {
				$no++;
		?>
				<tr>
					<td><?php echo $no;?></td>
					<td><?php echo $row['JUDUL'];?></td>
					<td><img src="../images/galeri/<?php echo $row['IMAGE']; ?>" width="90%"></td>
					<td>
						<a href="?page=galeri_hapus&id=<?php echo $row['ID']; ?>" class="btn btn-danger btn-sm" ><i class="fa fa-trash"></i></a>
					</td>
				</tr>
		<?php
			}
		?>
	</tbody>
</table>