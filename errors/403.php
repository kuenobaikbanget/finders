<?php
// Set HTTP response code to 403
http_response_code(403);

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
    <title>403 - Akses Ditolak</title>
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
        
        <div class="w-24 h-24 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-6">
            <i class="fa-solid fa-user-lock text-4xl text-red-500"></i>
        </div>

        <h1 class="text-6xl font-bold text-gray-800 mb-2">403</h1>
        <h2 class="text-2xl font-bold text-gray-700 mb-3">Akses Ditolak</h2>
        
        <p class="text-gray-500 mb-8 leading-relaxed">
            Maaf, Anda tidak memiliki izin untuk mengakses halaman ini. Halaman ini khusus untuk administrator atau role tertentu.
        </p>

        <div class="flex flex-col gap-3">
            <a href="<?php echo $home_url; ?>" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-xl transition shadow-lg shadow-blue-200">
                <i class="fa-solid fa-house mr-2"></i> Kembali ke Beranda
            </a>
            <button onclick="window.history.back()" class="w-full bg-white border border-gray-200 text-gray-600 font-bold py-3 px-6 rounded-xl hover:bg-gray-50 transition">
                <i class="fa-solid fa-arrow-left mr-2"></i> Kembali ke Halaman Sebelumnya
            </button>
        </div>

    </div>

</body>
</html>