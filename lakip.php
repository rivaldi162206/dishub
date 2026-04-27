<section class="upcoming-event-area section-gap mt-70 mb-30">
	<div class="container">					
		<div class="row">
			<div class="col-lg-12">
				<div class="row d-flex justify-content-center">
					<div class="menu-content pb-30 col-lg-10">
						<div class="title text-center">
							<h2 class="mb-10">Laporan Akuntabilitas Kinerja Instansi Pemerintah</h2>
							<hr style="border-color: #2e3192; border-width: 3px; max-width: 80px;">
						</div>
					</div>
				</div>

				<div class="row">
					<?php
						$sql = mysqli_query($con,"SELECT * FROM tb_dokumen WHERE KATEGORI = 'lakip' ");

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
		</div>
	</div>	
</section>