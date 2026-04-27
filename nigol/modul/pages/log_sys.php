<h3 class="page-title">Log Users</h3>


<table id="bootstrap-data-table" class="table table-hover table-bordered">
	<thead>
		<tr>
			<th>No</th>
			<th>Akun</th>
			<th>Log</th>
			<th>Waktu</th>
		</tr>
	</thead>
	<tbody>
		<?php
			$no = 0;
			$sql = mysqli_query($con,"SELECT AKUN,LOG,DAYOFMONTH(WAKTU) AS HARI ,MONTHNAME(WAKTU) AS BULAN,YEAR(WAKTU) AS TAHUN,HOUR(WAKTU) AS JAM,MINUTE(WAKTU) AS MENIT,SECOND(WAKTU) AS DETIK FROM sslog ORDER BY ID DESC");
			while ($row = mysqli_fetch_array($sql)) {
				$no++;
		?>
				<tr>
					<td><?php echo $no;?></td>
					<td><?php echo $row['AKUN'];?></td>
					<td><?php echo $row['LOG']; ?></td>
					<td><?php echo "<i class='fa fa-calendar'></i>  ".$row['HARI'].' '.$row['BULAN'].' '.$row['TAHUN'].'      ||      <i class="fa fa-clock-o"></i>  '.$row['JAM'].' : '.$row['MENIT'].' : '.$row['DETIK'].' WIB'; ?></td>
				</tr>
		<?php
			}
		?>
	</tbody>
</table>