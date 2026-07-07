<?php
require_once dirname(__DIR__, 2) . '/config/koneksi.php';

$company = [
    'id' => null,
    'nama_perusahaan' => 'WashWoosh',
    'logo' => null,
    'alamat' => 'Jl. Contoh No.123, Kecamatan Dapa',
    'no_telp' => '+62 812 3456 7890',
    'email' => 'info@washwoosh.com',
    'jam_operasional' => 'Senin - Minggu 08:00 - 19:00',
    'deskripsi' => 'Layanan cuci kendaraan modern yang cepat, bersih, dan ramah lingkungan.',
    'visi' => 'Menjadi layanan cuci kendaraan pilihan utama di kawasan kami.',
    'misi' => 'Memberikan layanan berkualitas dengan harga terjangkau dan pelayanan terbaik.'
];

try {
    $stmt = $pdo->query('SELECT * FROM tb_company_profile ORDER BY updated_at DESC LIMIT 1');
    $companyRow = $stmt->fetch();
    if ($companyRow) {
        $company = array_merge($company, $companyRow);
    }
} catch (PDOException $e) {
    // Fallback ke data default.
}
?>
