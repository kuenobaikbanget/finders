<?php
header('Content-Type: application/json');
require_once '../../config/db_connect.php';

$id_rs = isset($_GET['id_rs']) ? $_GET['id_rs'] : '';

if (!$id_rs) {
    echo json_encode(['error' => 'ID Rumah Sakit tidak ditemukan']);
    exit;
}

// Query untuk mendapatkan semua jadwal layanan di RS ini
$query = "SELECT 
    l.id_layanan,
    l.nama_layanan,
    l.kategori,
    j.id_jadwal,
    j.hari,
    j.jam_buka_praktek,
    j.jam_tutup_praktek,
    j.kuota_per_sesi
FROM data_layanan_rs l
LEFT JOIN data_jadwal_layanan j ON l.id_layanan = j.id_layanan
WHERE l.id_rs = '$id_rs'
ORDER BY 
    FIELD(j.hari, 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'),
    l.kategori,
    l.nama_layanan";

$result = mysqli_query($conn, $query);

if (!$result) {
    echo json_encode(['error' => 'Query error: ' . mysqli_error($conn)]);
    exit;
}

$jadwal_data = [];

while ($row = mysqli_fetch_assoc($result)) {
    $kategori = $row['kategori'] ?? 'Umum';
    
    if (!isset($jadwal_data[$kategori])) {
        $jadwal_data[$kategori] = [];
    }
    
    $nama_layanan = $row['nama_layanan'];
    
    if (!isset($jadwal_data[$kategori][$nama_layanan])) {
        $jadwal_data[$kategori][$nama_layanan] = [
            'id_layanan' => $row['id_layanan'],
            'jadwal' => []
        ];
    }
    
    // Jika ada jadwal
    if ($row['id_jadwal']) {
        $jadwal_data[$kategori][$nama_layanan]['jadwal'][] = [
            'hari' => $row['hari'],
            'jam_buka' => substr($row['jam_buka_praktek'], 0, 5), // Format HH:MM
            'jam_tutup' => substr($row['jam_tutup_praktek'], 0, 5),
            'kuota' => $row['kuota_per_sesi']
        ];
    }
}

echo json_encode($jadwal_data);
?>
