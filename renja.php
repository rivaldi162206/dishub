<section class="upcoming-event-area section-gap mt-70 mb-30">
	<div class="container">					
		<div class="row">
			<div class="col-lg-8">
				<div class="row d-flex justify-content-center">
					<div class="menu-content pb-30 col-lg-10">
						<div class="title text-center">
							<h2 class="mb-10">RENJA</h2>
							<hr style="border-color: #2e3192; border-width: 3px; max-width: 80px;">
						</div>
					</div>
				</div>

				<div class="row">
					<?php
						$sql = mysqli_query($con,"SELECT * FROM tb_dokumen WHERE KATEGORI = 'renja' ");

						while ($res = mysqli_fetch_array($sql)) 
						{
					?>								
							<div class="col-lg-4 mb-10">
								<div style="text-align: center;">
									<a href="nigol/images/file/<?php echo $res['DOKUMEN']; ?>">
										<img class="img-fluid" src="nigol/images/file/<?php echo $res['IMAGE']; ?>" alt="" style="width: 100%; border: 3px solid #313491;">
										<h4 style="font-size: 16px; font-weight: 500; margin-top: 10px;"><?php echo $res['JUDUL']; ?></h4>
									</a>
								</div>
							</div>
					<?php
						}
					?>
				</div>	
              	<!-- <embed src="http://images.bangkalankab.go.id/lakip/RPJMD.pdf" width="100%" height="700px!important" type="application/pdf" > -->
          	</div>
          	
			<div class="col-lg-4 sidebar">
				<div class="single-widget recent-posts-widget">
					<div class="title text-center" style="background-color: #ffc519;">
						<h4 class="title mb-10 pb-10 pt-10" style="color: white;">Dokumen Lainnya</h4>
					</div>
					<div class="row">
						<div class="col-lg-6 mb-10">
							<img class="img-fluid" src="img/PDF.png" alt="" style="width: 100%">
							<div style="text-align: center;">
								<a href="?page=renstra">
									<h4 style="font-size: 16px; font-weight: 500; margin-top: 10px;">SAKIP</h4>
								</a>
							</div>
						</div>
						<div class="col-lg-6 mb-10">
							<img class="img-fluid" src="img/PDF.png" alt="" style="width: 100%">
							<div style="text-align: center;">
								<a href="?page=renja">
									<h4 style="font-size: 16px; font-weight: 500; margin-top: 10px;">RENSTRA</h4>
								</a>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-lg-6 mb-10">
							<img class="img-fluid" src="img/PDF.png" alt="" style="width: 100%">
							<div style="text-align: center;">
								<a href="?page=lakip">
									<h4 style="font-size: 16px; font-weight: 500; margin-top: 10px;">LAKIP</h4>
								</a>
							</div>
						</div>
						<div class="col-lg-6 mb-10">
							<img class="img-fluid" src="img/PDF.png" alt="" style="width: 100%">
							<div style="text-align: center;">
								<a href="?page=lkpj">
									<h4 style="font-size: 16px; font-weight: 500; margin-top: 10px;">LKPJ</h4>
								</a>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-lg-6 mb-10">
							<img class="img-fluid" src="img/PDF.png" alt="" style="width: 100%">
							<div style="text-align: center;">
								<a href="?page=ikk">
									<h4 style="font-size: 16px; font-weight: 500; margin-top: 10px;">IKK</h4>
								</a>
							</div>
						</div>
						<div class="col-lg-6 mb-10">
							<img class="img-fluid" src="img/PDF.png" alt="" style="width: 100%">
							<div style="text-align: center;">
								<a href="?page=spm">
									<h4 style="font-size: 16px; font-weight: 500; margin-top: 10px;">SPM</h4>
								</a>
							</div>
						</div>
					</div>									
				</div>
			</div>											
		</div>
	</div>	
</section>