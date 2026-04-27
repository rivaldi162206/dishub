<?php

	include '../database.php';
	$page = isset($_GET['page']) ? $_GET['page'] : null;

	switch ($page) {

		//DATA BERITA
		case 'berita':
			include 'pages/berita.php';
			break;

		case 'berita_rec':
			include 'pages/berita_rec.php';
			break;

		case 'berita_edit':
			include 'pages/berita_edit.php';
			break;

		case 'berita_hapus':
			include 'pages/berita_hapus.php';
			break;

		//DATA DOKUMEN
		case 'dokumen':
			include 'pages/dokumen.php';
			break;

		case 'dokumen_rec':
			include 'pages/dokumen_rec.php';
			break;

		case 'dokumen_edit':
			include 'pages/dokumen_edit.php';
			break;

		case 'dokumen_hapus':
			include 'pages/dokumen_hapus.php';
			break;

		//DATA GALERI
		case 'galeri':
			include 'pages/galeri.php';
			break;

		case 'galeri_rec':
			include 'pages/galeri_rec.php';
			break;

		case 'galeri_hapus':
			include 'pages/galeri_hapus.php';
			break;
			

		case 'visi':
			include 'pages/visi.php';
			break;

		case 'misi':
			include 'pages/misi.php';
			break;

		case 'misi_rec':
			include 'pages/misi_rec.php';
			break;

		case 'misi_edit':
			include 'pages/misi_edit.php';
			break;

		case 'misi_hapus':
			include 'pages/misi_hapus.php';
			break;

		//MANAGEMENT USERS
		case 'user':
			include 'pages/user.php';
			break;

		case 'user_rec':
			include 'pages/user_rec.php';
			break;

		case 'user_edit':
			include 'pages/user_edit.php';
			break;

		case 'user_hapus':
			include 'pages/user_hapus.php';
			break;

		case 'user_res':
			include 'pages/user_res.php';
			break;

		case 'user_res_dat':
			include 'pages/user_res_dat.php';
			break;

		case 'log_sys':
			include 'pages/log_sys.php';
			break;

		case 'keluar':
			include '../logout.php';
			break;
		
		default:
			include 'pages/home.php';
			break;
	}

?>