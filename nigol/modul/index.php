<?php
	session_start();
	include '../database.php';
	date_default_timezone_set('Asia/Jakarta');
	$datetime = date('Y-m-d H:i:s');
	if(!empty($_SESSION['id'])){
		$ssid   = $_SESSION['id'];
		$ssuser = $_SESSION['user'];
		$sspass = $_SESSION['pass'];
		$sslevel  = $_SESSION['level'];

	}else{
		echo"<meta http-equiv='refresh' content='1; url=http://dishub.bangkalankab.go.id/nigol'>";
	}
?>
<!doctype html>
<html lang="en">

<head>
	<title>dishub.bangkalankab.go.id</title>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
	<!-- VENDOR CSS -->
	<link rel="stylesheet" href="../assets/vendor/bootstrap/css/bootstrap.min.css">

	<link rel="stylesheet" href="../assets/vendor/font-awesome/css/font-awesome.min.css">
	<link rel="stylesheet" href="../assets/vendor/linearicons/style.css">
	<link rel="stylesheet" href="../assets/vendor/chartist/css/chartist-custom.css">
	<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap.min.css">
	<!-- MAIN CSS -->
	<link rel="stylesheet" href="../assets/css/main.css">
	<!-- FOR DEMO PURPOSES ONLY. You should remove this in your project -->
	<link rel="stylesheet" href="../assets/css/demo.css">
	<link href="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.11/summernote.css" rel="stylesheet">
	<!-- GOOGLE FONTS -->
	<link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700" rel="stylesheet">


	
	<!-- ICONS -->
	<link rel="apple-touch-icon" sizes="76x76" href="assets/img/apple-icon.png">
	<link rel="icon" type="image/png" sizes="96x96" href="../assets/img/icon/dishub.png">
</head>

<body>
	<!-- WRAPPER -->
	<div id="wrapper">
		<!-- NAVBAR -->
		<nav class="navbar navbar-default navbar-fixed-top">
			<div class="brand">
				<a href="index.html">DINAS PERHUBUNGAN</a>
			</div>
			<div class="container-fluid">
				<div class="navbar-btn">
					<button type="button" class="btn-toggle-fullwidth"><i class="lnr lnr-arrow-left-circle"></i></button>
				</div>
				<div id="navbar-menu">
					<ul class="nav navbar-nav navbar-right">
						<li class="dropdown">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown"><?php  if(!empty($ssid)){echo "  ".  $ssuser;}?><i class="icon-submenu lnr lnr-chevron-down"></i></a>
							<ul class="dropdown-menu">
								<li><a href="?page=user_res"><i class="lnr lnr-cog"></i> <span>Settings</span></a></li>
								<li><a href="?page=keluar"><i class="lnr lnr-exit"></i> <span>Logout</span></a></li>
							</ul>
						</li>
					</ul>
				</div>
			</div>
		</nav>
		<!-- END NAVBAR -->
		<!-- LEFT SIDEBAR -->
		<div id="sidebar-nav" class="sidebar">
			<div class="sidebar-scroll">
				<nav>
					<ul class="nav">
						<li><a href="?page=home" class="active"><i class="lnr lnr-home"></i> <span>Home</span></a></li>
						<li><a href="?page=berita" class=""><i class="fa fa-newspaper-o" aria-hidden="true"></i><span>Berita</span></a></li>
						<li><a href="?page=dokumen" class=""><i class="fa fa-book" aria-hidden="true"></i><span>Dokumen</span></a></li>
						<li><a href="?page=galeri" class=""><i class="fa fa-photo" aria-hidden="true"></i> <span>Galeri</span></a></li>
						<li>
							<a href="#subPages" data-toggle="collapse" class="collapsed"><i class="fa fa-building-o" aria-hidden="true"></i> <span>Profil</span> <i class="icon-submenu lnr lnr-chevron-left"></i></a>
							<div id="subPages" class="collapse ">
								<ul class="nav">
									<li><a href="?page=visi" class=""><i class="fa fa-arrow-circle-o-right" aria-hidden="true"></i>Visi</a></li>
									<li><a href="?page=misi" class=""><i class="fa fa-arrow-circle-o-right" aria-hidden="true"></i>Misi</a></li>
									<!-- <li><a href="?page=tupoksi" class=""><i class="fa fa-arrow-circle-o-right" aria-hidden="true"></i>Tupoksi</a></li> -->
								</ul>
							</div>
						</li>
						<?php 
							if ($sslevel == 'administrator'){?>
								<li><a href="?page=user" class=""><i class="fa fa-address-card-o" aria-hidden="true"></i> <span>Management Users</span></a></li>
								<li><a href="?page=log_sys" class=""><i class="fa fa-address-card-o" aria-hidden="true"></i> <span>Log Users</span></a></li>
						<?php } ?>
					</ul>
				</nav>
			</div>
		</div>
		<!-- END LEFT SIDEBAR -->
		<!-- MAIN -->
		<div class="main">
			<!-- MAIN CONTENT -->
			<div class="main-content">
				<div class="container-fluid">
					<?php include 'routes.php'; ?>
				</div>
			</div>
			<!-- END MAIN CONTENT -->
		</div>
		<!-- END MAIN -->
		<div class="clearfix"></div>
		<footer>
			<div class="container-fluid">
				<p class="copyright">&copy; 2019 Dinas Perhubungan Kabupaten Bangkalan</p>
			</div>
		</footer>
	</div>
	<!-- END WRAPPER -->
	<!-- Javascript -->
	<script src="../assets/vendor/jquery/jquery.min.js"></script>
	<script src="../assets/vendor/bootstrap/js/bootstrap.min.js"></script>
	<!-- <script src="../assets/vendor/jquery-slimscroll/jquery.slimscroll.min.js"></script> -->
	<script src="../assets/vendor/jquery.easy-pie-chart/jquery.easypiechart.min.js"></script>
	<script src="../assets/vendor/chartist/js/chartist.min.js"></script>
	<script type="text/javascript" src="https://cdn.datatables.net/v/dt/dt-1.10.18/datatables.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.11/summernote.js"></script>
	<script type="text/javascript">
		$(document).ready(function() {
		    $('#bootstrap-data-table').DataTable();
		    $('#summernote').summernote({
		    	placeHolder : 'Ketikkan Text Berita di Sini',
		    	tabsize: 2,
        		height: 150
			});
		} );
	</script>
</body>

</html>
