<!-- Hero Section Polos & Serasi + Icon Logout -->
<!-- Link Font Awesome (wajib agar ikon muncul) -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<style>
    .hero-section {
        background-color: white;
        color: black;
        border-radius: 10px;
        border: 1px solid #ddd;
    }
    .hero-section h1,
    .hero-section p {
        font-family: 'Segoe UI', sans-serif;
    }
    .btn-logout {
        font-weight: 500;
        transition: background-color 0.3s ease, transform 0.2s ease;
    }
    .btn-logout i {
        transition: transform 0.3s ease;
    }
    .btn-logout:hover {
        background-color: #c82333; /* Merah lebih gelap */
        transform: translateY(-2px);
    }
    .btn-logout:hover i {
        transform: translateX(4px) rotate(10deg);
    }
</style>

<div class="p-5 mb-4 shadow-sm hero-section">
    <div class="container py-4">
        <h1 class="display-5 fw-bold">
            Hello, Wong Tegal Kunir!
        </h1>
        <p class="col-md-8 fs-5">
            E-Arsip adalah program yang memudahkan Anda dalam mengarsip surat,
            dan akan terus berkembang untuk memenuhi kebutuhan Anda.
        </p>
        <hr class="my-4">
        <p class="mb-4">
            Gunakan menu di atas untuk mulai mengelola arsip Anda. Terima kasih telah menggunakan E-Arsip.
        </p>
        <a class="btn btn-danger btn-lg px-4 btn-logout" href="logout.php" role="button">
            Logout <i class="fa-solid fa-right-from-bracket ms-2"></i>
        </a>
    </div>
</div>
