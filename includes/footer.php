<!-- Footer -->
<footer class="mt-12 mb-0 bg-white rounded-t-[2rem] shadow-sm border border-gray-100">
    <div class="container mx-auto px-6 lg:px-12 py-12">
        <!-- Footer Top -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 mb-12">
            <!-- Brand -->
            <div class="lg:col-span-1">
                <a href="index.php" class="flex items-center gap-2 mb-4">
                    <img src="assets/img/FindeRS_Logo.png" alt="FindeRS Logo" class="w-10 h-10 object-contain">
                    <span class="text-xl font-bold text-finders-blue">Finde<span class="text-finders-green">RS</span></span>
                </a>
                <p class="text-gray-500 text-sm leading-relaxed mb-4">
                    Platform pencarian rumah sakit dan layanan kesehatan terintegrasi di Indonesia.
                </p>
                <div class="flex items-center gap-3">
                    <a href="#" class="w-10 h-10 bg-blue-100 text-finders-blue rounded-full flex items-center justify-center hover:bg-blue-200 transition-all duration-300 transform hover:scale-110">
                        <i class="fa-brands fa-facebook-f"></i>
                    </a>
                    <a href="#" class="w-10 h-10 bg-blue-100 text-finders-blue rounded-full flex items-center justify-center hover:bg-blue-200 transition-all duration-300 transform hover:scale-110">
                        <i class="fa-brands fa-twitter"></i>
                    </a>
                    <a href="#" class="w-10 h-10 bg-blue-100 text-finders-blue rounded-full flex items-center justify-center hover:bg-blue-200 transition-all duration-300 transform hover:scale-110">
                        <i class="fa-brands fa-instagram"></i>
                    </a>
                </div>
            </div>

            <!-- Quick Links -->
            <div>
                <h4 class="font-bold text-gray-800 mb-4">Menu Cepat</h4>
                <ul class="space-y-3">
                    <li><a href="index.php" class="text-gray-500 hover:text-finders-green transition text-sm">Beranda</a></li>
                    <li><a href="rs_daftar.php" class="text-gray-500 hover:text-finders-green transition text-sm">Cari Rumah Sakit</a></li>
                    <li><a href="layanan.php" class="text-gray-500 hover:text-finders-green transition text-sm">Layanan Kesehatan</a></li>
                    <li><a href="booking.php" class="text-gray-500 hover:text-finders-green transition text-sm">Booking Online</a></li>
                </ul>
            </div>

            <!-- Support -->
            <div>
                <h4 class="font-bold text-gray-800 mb-4">Bantuan</h4>
                <ul class="space-y-3">
                    <li><a href="#" class="text-gray-500 hover:text-finders-green transition text-sm">Pusat Bantuan</a></li>
                    <li><a href="#" class="text-gray-500 hover:text-finders-green transition text-sm">FAQ</a></li>
                    <li><a href="#" class="text-gray-500 hover:text-finders-green transition text-sm">Kebijakan Privasi</a></li>
                    <li><a href="#" class="text-gray-500 hover:text-finders-green transition text-sm">Syarat & Ketentuan</a></li>
                </ul>
            </div>

            <!-- Contact -->
            <div>
                <h4 class="font-bold text-gray-800 mb-4">Kontak</h4>
                <ul class="space-y-3">
                    <li class="flex items-center gap-3 text-gray-500 text-sm">
                        <i class="fa-solid fa-envelope text-finders-green"></i>
                        support@finders.id
                    </li>
                    <li class="flex items-center gap-3 text-gray-500 text-sm">
                        <i class="fa-solid fa-phone text-finders-green"></i>
                        (021) 1234-5678
                    </li>
                    <li class="flex items-start gap-3 text-gray-500 text-sm">
                        <i class="fa-solid fa-location-dot text-finders-green mt-1"></i>
                        <span>Jl. Kesehatan No. 123<br>Jakarta Pusat, 10110</span>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Footer Bottom -->
        <div class="border-t border-gray-100 pt-8 text-center">
            <p class="text-gray-400 text-sm">
                &copy; <?= date('Y') ?> FindeRS Healthcare System. All rights reserved.
            </p>
        </div>
    </div>
</footer>