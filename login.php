<?php
session_start();

if(!isset($_SESSION['redirect_after_login']) && isset($_GET['redirect'])) {
    $_SESSION['redirect_after_login'] = $_GET['redirect'];
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    
    <link rel="stylesheet" href="assets/styles/style_logreg.css">
</head>
<body class="h-screen w-full flex overflow-hidden bg-white">

    <div class="w-full lg:w-5/12 flex flex-col justify-center items-center px-8 lg:px-16 bg-white z-10 shadow-xl lg:shadow-none relative overflow-hidden">
        
        <div class="absolute top-0 left-0 -ml-10 -mt-10 w-40 h-40 bg-green-100 rounded-full opacity-50 blur-3xl animate-fade-in-down"></div>
        <div class="absolute bottom-0 right-0 -mr-10 -mb-10 w-40 h-40 bg-blue-100 rounded-full opacity-50 blur-3xl animate-fade-in-up"></div>

        <a href="index.php" class="absolute top-6 left-8 z-20 hover:opacity-80 transition cursor-pointer animate-fade-in-down" title="Kembali ke Beranda">
            <img src="assets/img/FindeRS_Logo.png" alt="FindeRS Logo" class="h-10 w-auto">
        </a>

        <div class="w-full max-w-md relative z-10 mt-10 animate-fade-in-up delay-100">
            
            <div class="mb-8 text-center">
                <h1 class="text-3xl font-bold text-finders-blue mb-2">Selamat Datang!</h1>
                <p class="text-gray-500 text-sm">Silakan login untuk mengakses akun Anda.</p>
            </div>

            <form action="api/auth/login.php" method="POST" class="space-y-5">
                
                <div class="animate-fade-in-up delay-200">
                    <label class="block text-xs font-medium text-gray-700 ml-4 mb-1">Email</label>
                    <div class="relative input-group">
                        <span class="input-icon">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                            </svg>
                        </span>
                        <input type="text" name="identifier" required 
                            class="input-enhanced block w-full pl-10 pr-6 py-3 bg-gray-50 border border-gray-200 rounded-full focus:outline-none focus:ring-2 focus:ring-green-400 focus:border-transparent transition text-sm shadow-sm hover:shadow-md" 
                            placeholder="user@example.com">
                    </div>
                </div>

                <div class="animate-fade-in-up delay-300">
                    <label class="block text-xs font-medium text-gray-700 ml-4 mb-1">Password</label>
                    <div class="relative input-group">
                        <span class="input-icon">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                            </svg>
                        </span>
                        <input type="password" name="password" required 
                            class="input-enhanced block w-full pl-10 pr-6 py-3 bg-gray-50 border border-gray-200 rounded-full focus:outline-none focus:ring-2 focus:ring-green-400 focus:border-transparent transition text-sm shadow-sm hover:shadow-md" 
                            placeholder="••••••••">
                    </div>
                </div>

                <div class="flex items-center justify-between px-4 animate-fade-in-up delay-300">
                    <div class="flex items-center gap-2">
                        <input id="remember-me" name="remember-me" type="checkbox" class="custom-checkbox">
                        <label for="remember-me" class="block text-xs text-gray-600 cursor-pointer">
                            Ingat saya
                        </label>
                    </div>
                    <div class="text-xs">
                        <a href="#" class="font-medium text-finders-blue hover:text-green-500 transition">Lupa password?</a>
                    </div>
                </div>

                <button type="submit" 
                    class="btn-pulse w-full py-3 px-4 border border-transparent rounded-full shadow-lg text-sm font-bold text-white bg-[#00D348] hover:bg-[#00b03b] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition duration-300 transform hover:-translate-y-1 hover:scale-105 mt-4 animate-fade-in-up delay-300">
                    <span class="flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>
                        </svg>
                        LOGIN SEKARANG
                    </span>
                </button>

                <div class="text-center mt-6 animate-fade-in-up delay-500">
                    <p class="text-xs text-gray-500">
                        Belum punya akun? <a href="register.php" class="font-bold text-finders-blue hover:text-green-600 transition underline decoration-transparent hover:decoration-green-600">Buat Akun Baru</a>
                    </p>
                </div>
            </form>
        </div>

        <div class="absolute bottom-4 w-full text-center animate-fade-in-up delay-500">
            <p class="text-[10px] text-gray-300">
                &copy; 2025 FindeRS Healthcare System.
            </p>
        </div>
    </div>

    <div class="hidden lg:flex lg:w-7/12 relative bg-finders-blue items-center justify-center overflow-hidden">
        
        <img src="assets/img/rumahsakit_bg.png" alt="Hospital Hallway" class="absolute inset-0 w-full h-full object-cover animate-fade-in-right">
        
        <div class="absolute inset-0 bg-blue-900/85 animate-fade-in-right"></div>
        
        <div class="absolute top-0 right-0 -mr-16 -mt-16 w-64 h-64 bg-green-400 rounded-full opacity-10 blob-anim blur-2xl"></div>
        <div class="absolute top-0 right-0 w-32 h-32 bg-white rounded-bl-full opacity-10"></div>
        
        <div class="absolute bottom-0 left-0 -ml-10 -mb-10 w-48 h-48 bg-finders-green rounded-full opacity-10 blob-anim blur-xl" style="animation-delay: 1s;"></div>
        <div class="absolute bottom-0 left-0 w-24 h-24 bg-white rounded-tr-full opacity-10"></div>

        <div class="relative z-10 flex flex-col items-center justify-center h-full px-16 text-white text-center animate-fade-in-up delay-200">
             <div class="bg-white/10 p-4 rounded-full mb-6 backdrop-blur-sm border border-white/20 shadow-lg transform hover:rotate-12 transition duration-500">
                 <svg class="w-12 h-12 text-finders-green" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.384-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path>
                </svg>
             </div>

            <h2 class="text-4xl lg:text-5xl font-bold leading-tight mb-4 drop-shadow-lg">
                Solusi Cepat <br>
                <span class="text-finders-green">Kesehatan Anda</span>
            </h2>
            
            <p class="text-blue-100 text-lg max-w-lg leading-relaxed font-light drop-shadow-md">
                Temukan rumah sakit terdekat, cek ketersediaan layanan, dan kelola janji temu medis Anda dengan mudah.
            </p>

            <!-- Feature List -->
            <div class="mt-8 text-left w-full max-w-sm">
                <div class="feature-item animate-fade-in-up delay-300">
                    <div class="feature-icon">
                        <svg class="w-5 h-5 text-finders-green" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                    </div>
                    <span class="text-sm text-blue-100">Cari rumah sakit terdekat</span>
                </div>
                <div class="feature-item animate-fade-in-up delay-300">
                    <div class="feature-icon">
                        <svg class="w-5 h-5 text-finders-green" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <span class="text-sm text-blue-100">Booking jadwal dokter</span>
                </div>
                <div class="feature-item animate-fade-in-up delay-500">
                    <div class="feature-icon">
                        <svg class="w-5 h-5 text-finders-green" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <span class="text-sm text-blue-100">Layanan cepat & terpercaya</span>
                </div>
            </div>
        </div>
    </div>

</body>
</html>