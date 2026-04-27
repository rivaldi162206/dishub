<?php

	$id = $_GET['id'];
	//ANTI SQL INJECTION
	$id = mysqli_real_escape_string($con, stripslashes(strip_tags(htmlspecialchars($id,ENT_QUOTES))));
	$id = preg_replace('/\D/', '', $id);

	$sql = mysqli_query($con,"SELECT * FROM tb_berita WHERE ID = $id ");

	$data = mysqli_fetch_array($sql);

?>
<section class="blog-posts-area section-gap mt-70 mb-30">
	<div class="container">
		<div class="row">
			<div class="col-lg-8">
				<div class="row d-flex justify-content-center">
					<div class="menu-content">
						<div class="title text-center">
							<h3 class="mb-30"><?php echo $data['JUDUL']; ?></h3>
							<img src="../nigol/images/berita/<?php echo $data['IMAGE']; ?>" class="img-fluid mb-30 ml-5 mr-5">
						</div>
						<div style="text-align: left;">
							<p style="margin: 5px;">
								<?php echo $data['BERITA']; ?>
							</p>
						</div>
					</div>
				</div>
          	</div>
			<div class="col-lg-4 sidebar">
				<div class="single-widget recent-posts-widget">
					<h4 class="title">Berita Lainnya</h4>
					<div class="blog-list ">
						<?php
							$sql = mysqli_query($con,"SELECT * FROM tb_berita WHERE ID <> $id ORDER BY ID DESC LIMIT 6");

							while ($res = mysqli_fetch_array($sql)) 
							{
								$id = $res['ID'];
								$judul = $res['JUDUL'];
								$judulpage = str_replace(" ", "-", $judul);
								$judulpage = preg_replace('/[0-9]+/', '', $judulpage);
						    	$judulpage 	= preg_replace('/[^\p{L}\p{N}\s]/u', '-', $judulpage);
						?>
								<div class="single-recent-post d-flex flex-row mb-10">
									<div class="col-lg-4" style="padding-right: 0px;">
										<div class="recent-thumb">
											<img class="img-fluid" src="../nigol/images/berita/<?php echo $res['IMAGE']; ?>" alt="">
										</div>
									</div>
									<div class="col-lg-8">										
										<div class="recent-details" style="margin-left: 0px">
											<a href="../berita/<?php echo $id; ?>-<?php echo $judulpage; ?>.html">
												<h6>
													<?php echo $res['JUDUL']; ?>
												</h6>
											</a>
										</div>
									</div>
								</div>		
						<?php
							}
						?>												
					</div>								
				</div>
			</div>
		</div>
	</div>	
</section>