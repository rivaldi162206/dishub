<section class="upcoming-event-area section-gap mt-70">
	<div class="container">
		<div class="row d-flex justify-content-center">
			<div class="menu-content pb-30 col-lg-10">
				<div class="title text-center">
					<h1 class="mb-10">WEWENANG DINAS PERHUBUNGAN</h1>
					<p>Tugas dalam mengatur lalu lintas dan angkutan jalan, pengelolaan sarana serta prasarana transportasi, 
						peningkatan keselamatan dan ketertiban berlalu lintas, hingga pemberian pelayanan publik di bidang perhubungan merupakan tanggung jawab penting yang harus dijalankan secara optimal. Seluruh aspek tersebut bertujuan untuk menciptakan sistem transportasi yang tertib, aman, nyaman, dan efisien, sehingga dapat mendukung mobilitas 
						masyarakat serta menunjang kelancaran aktivitas ekonomi dan sosial secara berkelanjutan.</p>
					<hr style="border-color: #2e3192; border-width: 3px; max-width: 80px;">
				</div>
			</div>
		</div>						
		<div class="row">
			<div class="col-lg-6">
              	<h3>Fungsi Utama</h3>
              	<?php
	              		$sql = mysqli_query($con,"SELECT * FROM tb_fungsi");
	              		while($res = mysqli_fetch_array($sql))
	              		{
	              	?>
	              			<li><?php echo $res['Fungsi']; ?></li>
	              	<?php
	              		}
	              	?>
          	</div>

			<div class="col-lg-6">
				<h3>Tujuan Utama</h3>
				<?php
	              		$sql = mysqli_query($con,"SELECT * FROM tb_tujuan");
	              		while($res = mysqli_fetch_array($sql))
	              		{
	              	?>
	              			<li><?php echo $res['Tujuan']; ?></li>
	              	<?php
	              		}
	              	?>
				

											
		</div>
	</div>	
</section>