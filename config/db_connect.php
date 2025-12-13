<?php
// config/db_connect.php

// Matikan laporan error default PHP agar user tidak melihat path file
mysqli_report(MYSQLI_REPORT_OFF);

$host = "localhost";
$user = "root";
$pass = "";
$db   = "finder_rs";

try {
    $conn = mysqli_connect($host, $user, $pass, $db);
} catch (Exception $e) {
    // Jika koneksi gagal, tampilkan tampilan Error 500
    ?>
    <!DOCTYPE html>
    <html lang="id">
    <head>
        <title>Database Error</title>
        <script src="https://cdn.tailwindcss.com"></script>
    </head>
    <body class="bg-gray-100 h-screen flex items-center justify-center p-4">
        <div class="bg-white p-8 rounded-2xl shadow-xl max-w-md text-center">
            <div class="text-red-500 text-5xl mb-4">
                <i class="fa-solid fa-database"></i>
            </div>
            <h1 class="text-2xl font-bold text-gray-800 mb-2">Gagal Terhubung ke Server</h1>
            <p class="text-gray-500 mb-4">Sistem sedang mengalami gangguan koneksi database. Silakan coba beberapa saat lagi.</p>
            <button onclick="location.reload()" class="bg-red-500 text-white px-6 py-2 rounded-lg font-bold hover:bg-red-600 transition">Muat Ulang</button>
        </div>
    </body>
    </html>
    <?php
    exit; // Hentikan script
}
?>