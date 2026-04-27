<section class="upcoming-event-area section-gap mt-70 mb-30">
	<div class="container">
		<div class="row d-flex justify-content-center">
			<div class="menu-content pb-30 col-lg-10">
				<div class="title text-center">
					<h1 class="mb-10">Galeri</h1>
					<hr style="border-color: #2e3192; border-width: 3px; max-width: 80px;">
				</div>
			</div>
		</div>						
		<div id="" class="col-lg-12">
			<div class="row">
				<?php
					$sql = mysqli_query($con,"SELECT * FROM tb_galeri ORDER BY ID DESC");

					while ($res = mysqli_fetch_array($sql))
					{
				?>
						<div class="col-lg-3">
							<a class="single-gallery" href="nigol/images/galeri/<?php echo $res['IMAGE']; ?>">
								<img src="nigol/images/galeri/<?php echo $res['IMAGE']; ?>" class="img-fluid">
								<p style="text-align: center; color: black;"><?php echo $res['JUDUL']; ?></p>
							</a>
						</div>
				<?php
					}
				?>
			</div>			
		</div>
	</div>	
</section>