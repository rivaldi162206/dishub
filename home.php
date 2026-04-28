<div id="carouselExampleControls" class="carousel slide pt-50" data-ride="carousel">
  <div class="carousel-inner">
    <div class="carousel-item active">
      <img class="d-block w-100" src="img/slider1.png" alt="First slide">
    </div>
    <!-- <div class="carousel-item">
      		<img class="d-block w-100" src="img/slider1.jpg" alt="First slide">
    	</div> -->
    <div class="carousel-item">
      <img class="d-block w-100" src="img/slider2.png" alt="Second slide">
    </div>
    <!-- <div class="carousel-item">
      <img class="d-block w-100" src="img/slider3.jpg" alt="Third slide">
    </div> -->
    <div class="carousel-item">
      <img class="d-block w-100" src="img/slider4.png" alt="Third slide">
    </div>
  </div>
  <a class="carousel-control-prev" href="#carouselExampleControls" role="button" data-slide="prev">
    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
    <span class="sr-only">Previous</span>
  </a>
  <a class="carousel-control-next" href="#carouselExampleControls" role="button" data-slide="next">
    <span class="carousel-control-next-icon" aria-hidden="true"></span>
    <span class="sr-only">Next</span>
  </a>
  <h1 style="text-align: center; text-shadow: 2px 2px 5px black;">selamat datang di website Dishub Kabupaten Bangkalan </h1>
</div>
<!-- layanan Area -->
<section class="section-gap" style="background-color: #f5f5f5">
  <div class="container">
    <div class="row d-flex justify-content-center">
      <div class="menu-content pb-30 col-lg-10">
        <div class="title text-center">
          <h1 class="mb-10" ">Layanan</h1>
          <hr style="border-color: #2e3192; border-width: 3px; max-width: 80px;">
        </div>
      </div>
    </div>
    <div class="row" style="box-shadow: 0 10px 40px rgba(0,0,0,0.6);">
      <div class="col-lg-4 mt-10">
        <a href="/dishub/kir.html"><img src="img/UJIKIRtes.png" class="img-fluid"></a>
      </div>
      <div class="col-lg-4 mt-10">
        <a href="/dishub/parkir.html"><img src="img/parkirtes.png" class="img-fluid"></a>
      </div>
      <div class="col-lg-4 mt-10">
        <a href="/dishub/cctv.html"><img src="img/cctvtes.png" class="img-fluid"></a>
      </div>
    </div>
  </div>
</section>

<!-- Start upcoming-event Area -->
<section class="upcoming-event-area section-gap" id="events">
  <div class="container">
    <div class="row">
      <div class="menu-content pb-10 col-lg-12">
        <div class="title text-center" style="background-color:#1e293b ;">
          <h3 class="mb-10 pb-10 pt-10" style="color: white; box-shadow: 0 10px 20px rgba(0,0,0,0.6);">Berita Terbaru</h3>
        </div>
      </div>
    </div>
    <div class="row mb-15">
      <?php
      include 'database.php';
      $sql_berita = mysqli_query($con, 'SELECT * FROM tb_berita ORDER BY ID DESC LIMIT 4');

      while ($res_berita = mysqli_fetch_array($sql_berita)) {
        $id = $res_berita['ID'];
        $judul = $res_berita['JUDUL'];
        $judulpage = str_replace(" ", "-", $judul);
        $judulpage = preg_replace('/[0-9]+/', '', $judulpage);
        $judulpage   = preg_replace('/[^\p{L}\p{N}\s]/u', '-', $judulpage);
      ?>
        <div class="col-lg-3">
          <center>
            <img class="img-fluid mb-10" src="nigol/images/berita/<?php echo $res_berita['IMAGE']; ?>" alt="">
          </center>
          <a href="berita/<?php echo $id; ?>-<?php echo $judulpage; ?>.html">
            <h5><?php echo $res_berita['JUDUL']; ?></h5>
          </a>
          <div style="text-align: justify; margin-top: 10px;">
            <?php echo substr($res_berita['BERITA'], 0, 150) . ". . . "; ?>
          </div>
        </div>
      <?php
      }
      ?>
    </div>
  </div>
</section>
<!-- End upcoming-event Area -->

<!-- Start exibition Area -->
<section class="exibition-area section-gap" id="exhibitions">
  <div class="container">
    <div class="row d-flex justify-content-center">
      <div class="menu-content pb-30 col-lg-10">
        <div class="title text-center">
          <h1 class="mb-10">Galeri Foto</h1>
          <hr style="border-color: #2e3192; border-width: 3px; max-width: 80px;">
        </div>
      </div>
    </div>
    <div class="row">
      <div class="active-exibition-carusel">
        <?php
        $sql = mysqli_query($con, "SELECT * FROM tb_galeri ORDER BY ID DESC LIMIT 12");

        while ($res = mysqli_fetch_array($sql)) {
        ?>
          <div class="single-exibition item">
            <img src="nigol/images/galeri/<?php echo $res['IMAGE']; ?>" alt="">
            <h4 class="mt-20 text-center" style="color: black; font-weight: 300"><?php echo $res['JUDUL']; ?></h4>
          </div>
        <?php
        }
        ?>
      </div>
    </div>
  </div>
</section>
<!-- End exibition Area -->


<!-- sosmed Area -->
<section class="section-gap">
  <div class="container">
    <div class="row">
      <div class="col-lg-4 mt-10">
        <div class="card cta cta--featured">
          <span class="header-line gradient-color-1"></span>
          <div class="card-block">
            <h3 class="card-title no-margin-top">Twitter<span class="fa fa-twitter pull-right" style="color: #1DA1F2;"></span></h3>
            <h6 class="card-subtitle text-muted">Sosial Media Dinas Perhubungan</h6>
          </div>
          <div style="margin-left: 5px; margin-right: 5px;">
            <a class="twitter-timeline" href="https://twitter.com/bangkalandishub" height="415px">Tweets by Dishub Kab. Bangkalan</a>
            <script async src="//platform.twitter.com/widgets.js" charset="utf-8"></script>
          </div>
        </div>
      </div>
      <div class="col-lg-4 mt-10">
        <div class="card cta cta--featured">
          <span class="header-line gradient-color-1"></span>
          <div class="card-block">
            <h3 class="card-title no-margin-top">GPR<span class="fa fa-map pull-right" style="color: #FC636C;"></span></h3>
            <h6 class="card-subtitle text-muted">Governance Public Relations</h6>
          </div>
          <div style="margin-left: 5px; margin-right: 5px; padding: 0; overflow: auto; height: 420px!important;">
            <div id="gpr-kominfo-widget-container" style="background-color: #67676700!important;"></div>
          </div>
        </div>
      </div>
      <div class="col-lg-4 mt-10">
        <div class="card cta cta--featured">
          <span class="header-line gradient-color-1"></span>
          <div class="card-block">
            <h3 class="card-title no-margin-top">Link Terkait<span class="fa fa-globe pull-right" style="color: #55ce2d;"></span></h3>
            <h6 class="card-subtitle text-muted">Link Website Pemerintahan</h6>
          </div>
          <div style="margin-left: 5px; margin-right: 5px; height: 420px!important;">
            <a href="http://bangkalankab.go.id" target="_blank"><img src="img/PEMKAB BKL.png" class="img-fluid" style="margin-bottom: 5px;"></a>
            <a href="http://www.dephub.go.id/" target="_blank"><img src="img/KEMENHUBB.png" class="img-fluid" style="margin-bottom: 5px;"></a>
            <a href="http://dishub.jatimprov.go.id/" target="_blank"><img src="img/DISHUB JATIM.png" class="img-fluid" style="margin-bottom: 5px;"></a>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>