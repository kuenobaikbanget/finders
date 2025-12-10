<style>
    .sidebar-preload, .sidebar-preload * {
        transition: none !important;
    }
</style>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        setTimeout(function() {
            const sidebar = document.querySelector('aside');
            if(sidebar) sidebar.classList.remove('sidebar-preload');
        }, 100);
    });
</script>

<aside class="sidebar-preload w-20 hover:w-64 bg-finders-blue text-white flex flex-col shadow-2xl z-50 fixed h-full group/sidebar transition-[width] duration-500 ease-in-out">
    
    <!-- User Profile Section - Top -->
    <div class="p-3 pt-6 pb-4">
        <?php if (isset($_SESSION['user_id'])): ?>
            <a href="profile.php" class="flex items-center gap-3 p-2 hover:bg-blue-800/50 rounded-xl transition-all duration-300 relative group/item">
                <div class="w-11 h-11 min-w-[44px] min-h-[44px] rounded-full border-2 border-white/30 bg-finders-blue flex items-center justify-center flex-shrink-0">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                </div>
                <div class="overflow-hidden whitespace-nowrap max-w-0 opacity-0 group-hover/sidebar:max-w-xs group-hover/sidebar:opacity-100 transition-all duration-500 ease-in-out">
                    <p class="text-base font-semibold truncate"><?= $_SESSION['user_name'] ?? 'User' ?></p>
                    <p class="text-xs text-blue-300">Akun Terverifikasi</p>
                </div>
                <!-- Tooltip -->
                <div class="absolute left-full ml-3 px-3 py-2 bg-gray-900 text-white text-sm rounded-lg opacity-0 group-hover/item:opacity-100 pointer-events-none transition-opacity duration-200 whitespace-nowrap z-50 group-hover/sidebar:hidden">
                    <?= $_SESSION['user_name'] ?? 'User' ?>
                    <div class="absolute top-1/2 -left-1 -translate-y-1/2 border-4 border-transparent border-r-gray-900"></div>
                </div>
            </a>
        <?php else: ?>
            <a href="login.php" class="flex items-center gap-3 p-2 hover:bg-blue-800/50 rounded-xl transition-all duration-300 relative group/item">
                <div class="w-11 h-11 min-w-[44px] min-h-[44px] rounded-full border-2 border-white/30 bg-finders-blue flex items-center justify-center flex-shrink-0">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                </div>
                <div class="overflow-hidden whitespace-nowrap max-w-0 opacity-0 group-hover/sidebar:max-w-xs group-hover/sidebar:opacity-100 transition-all duration-500 ease-in-out">
                    <p class="text-base font-semibold truncate">User</p>
                    <p class="text-xs text-blue-300">Akun Terverifikasi</p>
                </div>
                <!-- Tooltip -->
                <div class="absolute left-full ml-3 px-3 py-2 bg-gray-900 text-white text-sm rounded-lg opacity-0 group-hover/item:opacity-100 pointer-events-none transition-opacity duration-200 whitespace-nowrap z-50 group-hover/sidebar:hidden">
                    Login
                    <div class="absolute top-1/2 -left-1 -translate-y-1/2 border-4 border-transparent border-r-gray-900"></div>
                </div>
            </a>
        <?php endif; ?>
    </div>

    <!-- Navigation Menu -->
    <nav class="flex-1 py-4 space-y-1 px-2 group-hover/sidebar:px-3 transition-all duration-300">
        <?php
        $current_page = basename($_SERVER['PHP_SELF']);
        
        $menu_items = [
            ['url' => 'index.php', 'icon' => 'fa-house', 'label' => 'Beranda'],
            ['url' => 'layanan.php', 'icon' => 'fa-magnifying-glass', 'label' => 'Cari Layanan'],
            ['url' => 'rs_daftar.php', 'icon' => 'fa-hospital', 'label' => 'Daftar RS'],
            ['url' => 'booking.php', 'icon' => 'fa-list-check', 'label' => 'Buat Janji'],
        ];
        
        foreach ($menu_items as $item):
            $is_active = ($current_page === $item['url']);
            $active_class = $is_active 
                ? 'bg-white/10 text-white' 
                : 'text-white/80 hover:bg-blue-800/50 hover:text-white';
        ?>
        <a href="<?= $item['url'] ?>" class="flex items-center pl-5 pr-3 group-hover/sidebar:px-3 py-3 rounded-xl transition-all duration-300 relative group/item <?= $active_class ?>" title="<?= $item['label'] ?>">
            <i class="fa-solid <?= $item['icon'] ?> text-lg w-6 text-center flex-shrink-0"></i>
            <span class="font-medium whitespace-nowrap overflow-hidden max-w-0 opacity-0 ml-0 group-hover/sidebar:max-w-xs group-hover/sidebar:opacity-100 group-hover/sidebar:ml-4 transition-all duration-500 ease-in-out"><?= $item['label'] ?></span>
            <!-- Tooltip -->
            <div class="absolute left-full ml-3 px-3 py-2 bg-gray-900 text-white text-sm rounded-lg opacity-0 group-hover/item:opacity-100 pointer-events-none transition-opacity duration-200 whitespace-nowrap z-50 group-hover/sidebar:hidden">
                <?= $item['label'] ?>
                <div class="absolute top-1/2 -left-1 -translate-y-1/2 border-4 border-transparent border-r-gray-900"></div>
            </div>
        </a>
        <?php endforeach; ?>

        <!-- Divider -->
        <div class="border-t border-blue-700/50 my-3"></div>

        <!-- Additional Menu -->
        <a href="riwayat_pengajuan.php" class="flex items-center pl-5 pr-3 group-hover/sidebar:px-3 py-3 rounded-xl transition-all duration-300 relative group/item <?= $current_page === 'riwayat_pengajuan.php' ? 'bg-white/10 text-white' : 'text-white/80 hover:bg-blue-800/50 hover:text-white' ?>" title="Riwayat Pengajuan">
            <i class="fa-solid fa-clock-rotate-left text-lg w-6 text-center flex-shrink-0"></i>
            <span class="font-medium whitespace-nowrap overflow-hidden max-w-0 opacity-0 ml-0 group-hover/sidebar:max-w-xs group-hover/sidebar:opacity-100 group-hover/sidebar:ml-4 transition-all duration-500 ease-in-out">Riwayat Pengajuan</span>
            <!-- Tooltip -->
            <div class="absolute left-full ml-3 px-3 py-2 bg-gray-900 text-white text-sm rounded-lg opacity-0 group-hover/item:opacity-100 pointer-events-none transition-opacity duration-200 whitespace-nowrap z-50 group-hover/sidebar:hidden">
                Riwayat Pengajuan
                <div class="absolute top-1/2 -left-1 -translate-y-1/2 border-4 border-transparent border-r-gray-900"></div>
            </div>
        </a>
    </nav>

    <!-- Logout Button - Bottom -->
    <div class="p-3 mt-auto">
        <a href="logout.php" class="flex items-center pl-5 pr-3 group-hover/sidebar:px-3 py-3 text-white/80 hover:text-white hover:bg-blue-800/50 rounded-xl transition-all duration-300 relative group/item" title="Logout">
            <i class="fa-solid fa-right-from-bracket text-lg w-6 text-center flex-shrink-0"></i>
            <span class="font-medium whitespace-nowrap overflow-hidden max-w-0 opacity-0 ml-0 group-hover/sidebar:max-w-xs group-hover/sidebar:opacity-100 group-hover/sidebar:ml-3 transition-all duration-500 ease-in-out">Logout</span>
            <!-- Tooltip -->
            <div class="absolute left-full ml-3 px-3 py-2 bg-gray-900 text-white text-sm rounded-lg opacity-0 group-hover/item:opacity-100 pointer-events-none transition-opacity duration-200 whitespace-nowrap z-50 group-hover/sidebar:hidden">
                Logout
                <div class="absolute top-1/2 -left-1 -translate-y-1/2 border-4 border-transparent border-r-gray-900"></div>
            </div>
        </a>
    </div>
</aside>

<!-- Spacer for fixed sidebar -->
<div class="w-20 flex-shrink-0 transition-all duration-500"></div>