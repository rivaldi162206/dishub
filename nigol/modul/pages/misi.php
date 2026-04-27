<h3 class="page-title">Misi</h3>

<a href="?page=misi_rec" class="btn btn-primary btn-xs" style="margin-bottom: 10px;"><i class="fa fa-plus-square-o"></i> New Record</a>
<table id="bootstrap-data-table" class="table table-striped table-bordered">
	<thead>
		<th width="30px">No.</th>
		<th>Misi</th>
		<th width="100px">Option</th>
	</thead>
	<tbody>
		<?php
			$no = 0;
			$sql = mysqli_query($con,"SELECT * FROM tb_misi ORDER BY ID ASC");
			while ($row = mysqli_fetch_array($sql)) {
				$no++;
		?>
				<tr>
					<td><?php echo $no;?></td>
					<td><?php echo $row['MISI'];?></td>
					<td>
						<div class="row">
							<div class="col-md-6">
								<a href="?page=misi_edit&id=<?php echo $row['ID']; ?>" class="btn btn-warning btn-sm" ><i class="fa fa-pencil-square-o"></i></a>
							</div>
							<div class="col-md-6">
								<a href="?page=misi_hapus&id=<?php echo $row['ID']; ?>" class="btn btn-danger btn-sm" ><i class="fa fa-trash"></i></a>
							</div>
						</div>
					</td>
				</tr>
		<?php
			}
		?>
	</tbody>
</table>