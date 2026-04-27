<?php
	include 'database.php';
	$page = isset($_GET['tompesconk']) ? $_GET['tompesconk'] : null;
	switch ($page) {
		case 'visi-misi':
			include 'visi-misi.php';
			break;
		case 'galeri':
			include 'galeri.php';
			break;
		case 'struktur-organisasi':
			include 'struktur-organisasi.php';
			break;
		case 'berita':
			include 'berita.php';
			break;
		case 'berita-detail':
			include 'berita-detail.php';
			break;
		case 'renstra':
			include 'renstra.php';
			break;
		case 'renja':
			include 'renja.php';
			break;
		case 'sakip':
			include 'sakip.php';
			break;
		case 'lakip':
			include 'lakip.php';
			break;
		case 'iku':
			include 'iku.php';
			break;
		case 'pk':
			include 'pk.php';
			break;
		case 'kir':
			include 'kir.php';
			break;
		case 'parkir':
			include 'parkir.php';
			break;
		case 'cctv':
			include 'cctv.php';
			break;
		case'perundangan':
		    include 'perundangan.php';
		    break;
		case'wewenang-dishub':
		    include 'wewenang-dishub.php';
		    break;
		case 'data-pad':
			include 'data-pad.php';
			break;
		case 'data-uji-kendaraan':
			include 'data-uji-kendaraan.php';
			break;
		default :
			include 'home.php';
	}
?>