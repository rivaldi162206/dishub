<h3 class="page-title">Management User</h3>

<a href="?page=user_rec" class="btn btn-primary btn-sm" style="margin-bottom: 10px;"><i class="fa fa-plus-square-o"></i> New Record</a>
<table id="bootstrap-data-table" class="table table-hover table-bordered table-sm" id="sampleTable">
	<thead>
		<tr>
			<th width="50px">No</th>
			<th>User</th>
			<th width="80px">Opsi</th>
		</tr>
	</thead>
	<tbody>
		<?php
			$no = 0;
			$sql = mysqli_query($con,"SELECT * FROM sslogin ORDER BY LEVEL ASC");
			while ($row = mysqli_fetch_array($sql)) {
				$no++;
		?>
				<tr>
					<td><?php echo $no;?></td>
					<td><?php echo $row['SSUSER'];?></td>
					<?php 
						if ($row['LEVEL'] == 'administrator') {
					?>
						<td>
							<div class="bs-component" style="margin-bottom: 15px;">
								<div class="btn-group" role="group" aria-label="Basic example">
									<button type="button" class="btn btn-warning btn-sm" disabled=""><i class="fa fa-pencil-square-o"></i></button>
									<button type="button" class="btn btn-danger btn-sm" disabled=""><i class="fa fa-trash"></i></button>
								</div>
				            </div>
						</td>
					<?php
						}else{
					?>
							<td>
								<div class="bs-component" style="margin-bottom: 15px;">
									<div class="btn-group" role="group" aria-label="Basic example">
										<a href="?page=user_edit&id=<?php echo $row['ID']; ?>" class="btn btn-warning btn-sm" ><i class="fa fa-pencil-square-o"></i></a>
										<a href="?page=user_hapus&id=<?php echo $row['ID']; ?>" class="btn btn-danger btn-sm" ><i class="fa fa-trash"></i></a>
									</div>
					            </div>
							</td>
					<?php
						}
					?>
				</tr>
		<?php
			}
		?>
	</tbody>
</table>