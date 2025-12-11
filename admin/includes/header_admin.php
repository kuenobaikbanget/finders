<header class="bg-white border-b border-gray-200 px-6 py-4 mb-6 rounded-xl shadow-sm">
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-gray-800"><?= $page_title ?? 'Dashboard' ?></h2>
            <p class="text-sm text-gray-500 mt-1"><?= $page_subtitle ?? 'Kelola sistem FindeRS' ?></p>
        </div>
        <div class="flex items-center gap-4">
            <!-- Notification Bell -->
            <button class="relative p-2 text-gray-600 hover:bg-gray-100 rounded-lg transition">
                <i class="fa-solid fa-bell text-xl"></i>
                <span class="absolute top-1 right-1 w-2 h-2 bg-red-500 rounded-full"></span>
            </button>
            
            <!-- Current Date -->
            <div class="text-right">
                <p class="text-xs text-gray-500">Hari ini</p>
                <p class="text-sm font-semibold text-gray-700"><?= date('d M Y') ?></p>
            </div>
        </div>
    </div>
</header>
