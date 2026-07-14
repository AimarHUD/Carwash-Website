<?php
require_once 'includes/company_data.php';
$current_page = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
<<<<<<< HEAD
<<<<<<< HEAD
    <title>Tentang WashWoosh</title>
   <link rel="stylesheet" href="assets/css/style.css">
=======
    <title>Tentang <?= htmlspecialchars($company['nama_perusahaan'] ?? 'WashWoosh') ?></title>
    <link rel="stylesheet" href="assets/css/style.css">
>>>>>>> 46a06ca1e9a16150fc2436413507605f5aec4049
=======
    <title>Tentang Kami - Carwash Woosh</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        body { background-color: #add8e6 !important; min-height: 100vh; display: flex; flex-direction: column; }
        .team-img { width: 150px; height: 150px; object-fit: cover; border: 5px solid #ffffff; box-shadow: 0 4px 10px rgba(0,0,0,0.1); }
        .card-friendly { border-top: 5px solid #0d6efd !important; }
    </style>
>>>>>>> e6a6569151981da952e9a5d8a1ee9995d5b44df4
</head>
<body>

<?php include 'includes/site_header.php'; ?>

<main class="py-5 flex-grow-1">
    <div class="container-fluid px-5">
        <!-- Deskripsi, Visi, Misi -->
        <div class="card p-4 p-md-5 border-0 shadow-sm rounded-4 bg-white mb-5 card-friendly">
            <h1 class="fw-bold text-primary mb-4">Tentang Carwash Woosh</h1>
            <p class="fs-5 text-muted">Carwash Woosh hadir sebagai solusi premium dalam dunia perawatan kendaraan , berawal dari sebuah visi sederhana untuk memberikan standar baru dalam kebersihan dan kenyamanan bagi para pemilik kendaraan. Kami percaya bahwa kendaraan bukan sekadar alat transportasi, melainkan aset berharga yang mencerminkan gaya hidup dan kepribadian pemiliknya. Oleh karena itu, sejak hari pertama berdiri, kami mendedikasikan diri untuk memberikan pelayanan yang melampaui sekadar "cuci mobil biasa."

Di Carwash Woosh, kami memadukan presisi teknologi modern dengan sentuhan tenaga ahli yang terlatih secara profesional. Kami menyadari bahwa setiap jenis kendaraan—baik itu mobil keluarga, kendaraan niaga, hingga mobil hobi yang berharga—membutuhkan penanganan khusus yang berbeda. Dengan menggunakan peralatan terkini, bahan pembersih ramah lingkungan yang aman bagi cat kendaraan, serta prosedur operasional standar (SOP) yang ketat, kami memastikan setiap inci kendaraan Anda mendapatkan perawatan yang optimal dan perlindungan maksimal.

Kami sangat menjunjung tinggi nilai efisiensi tanpa mengorbankan kualitas. Kami memahami bahwa waktu Anda sangat berharga; oleh karena itu, sistem antrean dan proses pengerjaan kami dirancang sedemikian rupa agar Anda mendapatkan hasil maksimal dalam waktu yang terukur. Lebih dari sekadar hasil akhir yang mengkilap, kami mengutamakan pengalaman pelanggan. Mulai dari ruang tunggu yang nyaman, keramahan staf kami, hingga transparansi dalam setiap layanan yang kami tawarkan, kami berkomitmen untuk menciptakan hubungan jangka panjang dengan setiap pelanggan kami.

Seiring berjalannya waktu, Carwash Woosh terus bertransformasi menjadi pusat perawatan kendaraan yang modern. Kami tidak hanya fokus pada estetika eksterior, tetapi juga pada kesehatan interior dan kenyamanan berkendara Anda. Kami bangga dapat melayani komunitas lokal dan terus berinovasi untuk menjadi yang terdepan, memberikan ketenangan pikiran kepada Anda setiap kali Anda meninggalkan outlet kami dengan siap menempuh perjalanan berikutnya.
            </p></p>
            

<div class="row mt-5 g-4 mb-5 pb-5">
    <div class="col-md-6">
        <div class="bg-light p-4 rounded-3 h-100">
            <h4 class="fw-bold text-primary"><i class="fas fa-eye me-2"></i>Visi Kami</h4>
            <ul class="list-unstyled mt-3 text-muted">
                <li class="mb-2">• Menjadi standar utama layanan perawatan kendaraan nasional.</li>
                <li class="mb-2">• Pelopor layanan cuci ramah lingkungan berbasis teknologi.</li>
                <li>• Memberikan dampak positif bagi komunitas dan lingkungan.</li>
                <li>- Memberikan LAyanan yang nyaman untuk pelanggan.</li>
            </ul>
        </div>
    </div>
    <div class="col-md-6">
        <div class="bg-light p-4 rounded-3 h-100">
            <h4 class="fw-bold text-primary"><i class="fas fa-rocket me-2"></i>Misi Kami</h4>
            <ul class="list-unstyled mt-3 text-muted">
                <li class="mb-2">• Memberikan pelayanan prima yang cepat dan mendetail.</li>
                <li class="mb-2">• Membangun tim yang profesional dan berdedikasi.</li>
                <li class="mb-2">• Menggunakan teknologi yang nyaman dan canggih.</li>
                <li>• Inovasi berkelanjutan dengan peralatan modern.</li>
            </ul>
        </div>
    </div>
</div>

</main>

<footer class="bg-dark text-white py-4 mt-auto text-center">
    <p class="mb-0">&copy; <?= date('Y') ?> Carwash Woosh. Semua hak dilindungi.</p>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>