<section class="upcoming-event-area section-gap mt-70 mb-30">
	<div class="container">
		<div class="row d-flex justify-content-center">
			<div class="menu-content pb-30 col-lg-10">
				<div class="title text-center">
					<h1 class="mb-10">Data Pad</h1>
					<hr style="border-color: #2e3192; border-width: 3px; max-width: 80px;">
				</div>
			</div>
		</div>						
		<div class="row">
          	<div class="title text-center">
          		<ul>
          			<?php
	              		$sql = mysqli_query($con,"SELECT * FROM tb_datapad");
	              		while($res = mysqli_fetch_array($sql))
	              		{
	              	?>
	              			<li style="margin: 30px"><?php echo $res['datapad']; ?>
                         <inframe 
                         src="<?php echo $res['file_pdf']; ?>"
                         width="100%" 
                         height="600">
                        </inframe></li>
	              	<?php
	              		}
	              	?>
          		</ul>
          	</div>												
		</div>
	</div>	
</section>