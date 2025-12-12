<?php
header('Content-Type: application/json');
require_once '../../config/db_connect.php';

$id_layanan = isset($_GET['id_layanan']) ? $_GET['id_layanan'] : '';
$tanggal = isset($_GET['tanggal']) ? $_GET['tanggal'] : '';

if (!$id_layanan || !$tanggal) {
    echo json_encode([]);
    exit;
}

// 1. Tentukan Hari dari Tanggal yang dipilih
// Format 'l' menghasilkan: Sunday, Monday, etc. Perlu di-map ke Indonesia
$timestamp = strtotime($tanggal);
$english_day = date('l', $timestamp);
$hari_map = [
    'Sunday' => 'Minggu', 'Monday' => 'Senin', 'Tuesday' => 'Selasa', 
    'Wednesday' => 'Rabu', 'Thursday' => 'Kamis', 'Friday' => 'Jumat', 'Saturday' => 'Sabtu'
];
$hari_indo = $hari_map[$english_day];

// 2. Ambil Jadwal Operasional dari Database
$query_jadwal = mysqli_query($conn, "SELECT * FROM data_jadwal_layanan WHERE id_layanan='$id_layanan' AND hari='$hari_indo'");
$jadwal = mysqli_fetch_assoc($query_jadwal);

// Jika hari itu tutup (tidak ada jadwal)
if (!$jadwal) {
    echo json_encode(['error' => 'Libur', 'message' => 'Layanan tidak tersedia pada hari ' . $hari_indo]);
    exit;
}

// 3. Generate Slot Waktu (Looping per 2 jam)
$slots = [];
$start_time = strtotime($jadwal['jam_buka_praktek']);
$end_time = strtotime($jadwal['jam_tutup_praktek']);
$duration = 2 * 60 * 60; // 2 Jam (detik)

while ($start_time + $duration <= $end_time) {
    $jam_mulai_str = date("H:i", $start_time);
    $jam_selesai_str = date("H:i", $start_time + $duration);

    // 4. Hitung Kuota Terpakai
    $query_cek = mysqli_query($conn, "SELECT COUNT(*) as total FROM data_penjadwalan 
                                      WHERE id_layanan='$id_layanan' 
                                      AND tanggal_kunjungan='$tanggal' 
                                      AND jam_mulai='$jam_mulai_str:00'
                                      AND status != 'Dibatalkan'");
    
    $terisi = mysqli_fetch_assoc($query_cek)['total'];
    $sisa = $jadwal['kuota_per_sesi'] - $terisi;

    $slots[] = [
        'jam_mulai' => $jam_mulai_str,
        'jam_selesai' => $jam_selesai_str,
        'label' => "$jam_mulai_str - $jam_selesai_str",
        'sisa' => $sisa,
        'status' => ($sisa > 0) ? 'Tersedia' : 'Penuh'
    ];

    $start_time += $duration; // Lompat ke sesi berikutnya
}

echo json_encode($slots);
?>