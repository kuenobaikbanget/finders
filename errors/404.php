<?php
// Set HTTP response code to 404
http_response_code(404);

// Tentukan base URL untuk redirect yang benar
$base_url = '/finders/';
if(isset($_SERVER['REQUEST_URI']) && strpos($_SERVER['REQUEST_URI'], '/admin/') !== false) {
    $home_url = $base_url . 'admin/index.php';
} else {
    $home_url = $base_url . 'index.php';
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 - Halaman Tidak Ditemukan</title>
    <script src="https://cdn.tailwindcss.com"></script> 
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .fade-in {
            animation: fadeIn 0.5s ease-out;
        }
    </style>
</head>
<body class="bg-gray-50 h-screen flex items-center justify-center p-4">

    <div class="max-w-md w-full bg-white rounded-3xl shadow-xl overflow-hidden text-center p-8 fade-in">
        
        <div class="w-24 h-24 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-6 animate-bounce">
            <i class="fa-solid fa-map-location-dot text-4xl text-blue-500"></i>
        </div>

        <h1 class="text-6xl font-bold text-gray-800 mb-2">404</h1>
        <h2 class="text-2xl font-bold text-gray-700 mb-3">Halaman Tidak Ditemukan</h2>
        
        <p class="text-gray-500 mb-6 leading-relaxed">
            Ups! Sepertinya Anda tersesat. Halaman yang Anda cari mungkin sudah dihapus, dipindahkan, atau tidak tersedia.
        </p>

        <div class="space-y-3">
            <a href="<?php echo $home_url; ?>" class="inline-block w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-xl transition shadow-lg shadow-blue-200">
                <i class="fa-solid fa-house mr-2"></i> Kembali ke Beranda
            </a>
            
            <button onclick="window.history.back()" class="inline-block w-full bg-gray-200 hover:bg-gray-300 text-gray-700 font-bold py-3 px-6 rounded-xl transition">
                <i class="fa-solid fa-arrow-left mr-2"></i> Kembali ke Halaman Sebelumnya
            </button>
        </div>

    </div>

</body>
</html>