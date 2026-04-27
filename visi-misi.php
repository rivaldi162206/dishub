<section class="upcoming-event-area section-gap mt-70 mb-30">
	<div class="container">
		<div class="row d-flex justify-content-center">
			<div class="menu-content pb-30 col-lg-10">
				<div class="title text-center">
					<h1 class="mb-10">Visi dan Misi</h1>
					<hr style="border-color: #2e3192; border-width: 3px; max-width: 80px;">
				</div>
			</div>
		</div>						
		<div class="row">
			<div class="col-lg-6">
              	<h3>VISI</h3>
              	<?php
              		$sql = mysqli_query($con,"SELECT * FROM tb_visi");
              		$res = mysqli_fetch_array($sql);
              	?>
              	<p>"<?php echo $res['VISI']; ?>"</p>
          	</div>
          	<div class="col-lg-6">
          		<h3>MISI</h3>
          		<ul>
          			<?php
	              		$sql = mysqli_query($con,"SELECT * FROM tb_misi");
	              		while($res = mysqli_fetch_array($sql))
	              		{
	              	?>
	              			<li><?php echo $res['MISI']; ?></li>
	              	<?php
	              		}
	              	?>
          		</ul>
          	</div>												
		</div>
	</div>	
</section>