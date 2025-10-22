<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    
    <!-- Bootstrap 4 dari CDN -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

    <title>E-Arsip | Lutfi - Passah</title>
  </head>
  <body>

    <!-- Awal Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    	<div class="container">
    		<a class="navbar-brand" href="#">E-Arsip</a>
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
        aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav mr-auto">
          <li class="nav-item active">
            <a class="nav-link" href="?halaman">Beranda<span class="sr-only">(current)</span></a>
          </li>
          <li class="nav-item"><a class="nav-link" href="?halaman=departemen">Data Departemen</a></li>
          <li class="nav-item"><a class="nav-link" href="?halaman=pengirim_surat">Data Pengirim Surat</a></li>
          <li class="nav-item"><a class="nav-link" href="?halaman=arsip_surat">Data Arsip Surat</a></li>
      </div>
    	</div>

    <div class="container">	
    	<!-- FORM SEARCH DI POJOK KANAN -->
          <form class="form-inline ml-auto">
            <input class="form-control mr-sm-2" type="search" placeholder="Search" aria-label="Search">
            <button class="btn btn-outline-light my-2 my-sm-0" type="submit">Search</button>
          </form>
	</div>          
    </nav>
    <!-- Akhir Navbar -->

    <!-- Awal Container -->
    <div class="container mt-4">