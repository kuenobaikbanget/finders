<?php 
session_start();

$rs_name = $_GET['rs'] ?? 'Rumah Sakit';
$layanan = $_GET['layanan'] ?? 'Layanan Medis';
$tanggal = $_GET['tgl'] ?? date('Y-m-d');
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pendaftaran Berhasil - FindeRS</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="assets/styles/style_user.css">
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center p-4 lg:p-8" 
      style="background-image: url('assets/img/home_background.jpg'); background-size: cover; background-position: center;">

    <div class="absolute inset-0 bg-blue-900/40 backdrop-blur-sm z-0"></div>

    <div class="bg-white/90 backdrop-blur-md w-full max-w-5xl rounded-[2rem] shadow-2xl overflow-hidden relative z-10 flex flex-col lg:flex-row min-h-[550px] animate-fade-in-up">
        
        <div class="w-full lg:w-5/12 relative min-h-[200px] lg:min-h-full bg-[#1e3a8a]">
            <img src="assets/img/rumahsakit_bg.png" alt="Hospital Building" class="absolute inset-0 w-full h-full object-cover opacity-80 mix-blend-overlay">
            <div class="absolute inset-0 bg-gradient-to-t from-[#1e3a8a]/90 to-transparent"></div>
            
            <div class="absolute top-8 left-8 z-20">
                <h1 class="text-3xl font-bold text-white leading-tight drop-shadow-md">
                    Pengajuan <br>Kunjungan
                </h1>
            </div>
        </div>

        <div class="w-full lg:w-7/12 p-8 lg:p-12 flex flex-col items-center justify-center text-center bg-gray-50/50">
            
            <div class="w-20 h-20 bg-green-50 rounded-full flex items-center justify-center mb-6 animate-bounce-slow border-4 border-green-100">
                <i class="fa-solid fa-check text-4xl text-finders-green"></i>
            </div>

            <h2 class="text-2xl lg:text-3xl font-bold text-gray-800 mb-2 uppercase tracking-wide">
                PENDAFTARAN BERHASIL
            </h2>
            
            <p class="text-gray-500 mb-8 max-w-md">
                Nomor antrian Anda akan dikirimkan melalui WhatsApp dan dapat dicek pada menu Riwayat.
            </p>

            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 w-full max-w-md mb-8 relative overflow-hidden">
                <div class="absolute top-0 left-0 w-1 h-full bg-finders-green"></div>
                <div class="grid grid-cols-2 gap-y-4 text-left text-sm">
                    
                    <div>
                        <span class="block text-gray-400 text-xs font-bold uppercase mb-1">RS Tujuan</span>
                        <span class="font-bold text-gray-800 text-base block truncate pr-2"><?= htmlspecialchars($rs_name) ?></span>
                    </div>

                    <div class="text-right">
                        <span class="block text-gray-400 text-xs font-bold uppercase mb-1">Layanan</span>
                        <span class="font-bold text-gray-800 text-base"><?= htmlspecialchars($layanan) ?></span>
                    </div>

                    <div class="col-span-2 border-t border-gray-100 my-1"></div>

                    <div>
                        <span class="block text-gray-400 text-xs font-bold uppercase mb-1">Tanggal</span>
                        <span class="font-bold text-gray-800 text-base"><?= date('d F Y', strtotime($tanggal)) ?></span>
                    </div>

                    <div class="text-right">
                        <span class="block text-gray-400 text-xs font-bold uppercase mb-1">Status</span>
                        <span class="inline-block px-3 py-1 bg-yellow-50 text-yellow-600 border border-yellow-100 text-xs font-bold rounded-lg">
                            MENUNGGU
                        </span>
                    </div>
                </div>
            </div>

            <div class="flex flex-col sm:flex-row gap-4 w-full max-w-md">
                <a href="index.php" class="flex-1 bg-finders-green hover:bg-green-600 text-white font-bold py-3 px-6 rounded-xl shadow-lg shadow-green-500/20 transition-all hover:-translate-y-1 text-center flex items-center justify-center gap-2">
                    <i class="fa-solid fa-house"></i> Dashboard
                </a>
                
                <a href="riwayat_pengajuan.php" class="flex-1 bg-white border-2 border-gray-200 hover:border-finders-blue text-gray-600 hover:text-finders-blue font-bold py-3 px-6 rounded-xl transition-all text-center flex items-center justify-center gap-2">
                    <i class="fa-solid fa-clock-rotate-left"></i> Riwayat
                </a>
            </div>

        </div>
    </div>

</body>
</html>