<section class="blog-posts-area section-gap mt-70 mb-30">
	<div class="container">
		<div class="row">
			<div class="col-lg-12 post-list blog-post-list">
				<div class="title text-center">
					<h1 class="mb-10">Berita</h1>
					<hr style="border-color: #2e3192; border-width: 3px; max-width: 80px; margin-bottom: 30px;">
				</div>
				<div class="row mb-15">
					<?php
						$sql = mysqli_query($con,"SELECT * FROM tb_berita ORDER BY ID DESC");

						while ($res = mysqli_fetch_array($sql))
						{
							$id = $res['ID'];
							$judul = $res['JUDUL'];
							$judulpage = str_replace(" ", "-", $judul);
							$judulpage = preg_replace('/[0-9]+/', '', $judulpage);
						    $judulpage 	= preg_replace('/[^\p{L}\p{N}\s]/u', '-', $judulpage);
					?>
							
							<div class="col-lg-3">									
								<a href="berita/<?php echo $id; ?>-<?php echo $judulpage; ?>.html">
									<img class="img-fluid mb-10" src="/dishub/nigol/images/berita/<?php echo $res['IMAGE']; ?>" alt="No Image">
									<p style="color: black;"><?php echo $judul; ?></p>
								</a>								
							</div>								
					<?php
						}
					?>
				</div>				
			</div>
		</div>
	</div>	
</section>