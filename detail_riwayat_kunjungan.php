<!-- Floating Window Detail Kunjungan -->
<div id="detailModal" class="fixed inset-0 z-[100] hidden">
    <div class="absolute inset-0 bg-black/60 backdrop-blur-sm transition-opacity" onclick="closeDetailModal()"></div>
    <div class="absolute inset-0 flex items-center justify-center p-4 overflow-y-auto">
        <div class="relative bg-white rounded-3xl shadow-2xl max-w-2xl w-full animate-fade-in-up" onclick="event.stopPropagation()">
            <!-- Modal Header -->
            <div class="bg-gradient-to-r from-blue-600 to-blue-700 p-6 rounded-t-3xl">
                <div class="flex justify-between items-start">
                    <div>
                        <h3 class="text-2xl font-bold text-white flex items-center gap-3">
                            <i class="fa-solid fa-clipboard-list"></i>
                            Detail Kunjungan
                        </h3>
                        <p class="text-blue-100 text-sm mt-1">Informasi lengkap janji temu Anda</p>
                    </div>
                    <button onclick="closeDetailModal()" class="text-white/80 hover:text-white transition">
                        <i class="fa-solid fa-times text-2xl"></i>
                    </button>
                </div>
            </div>

            <!-- Modal Body -->
            <div class="p-6 space-y-6 max-h-[70vh] overflow-y-auto">

                <!-- Info Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    
                    <!-- Rumah Sakit -->
                    <div class="bg-gradient-to-br from-blue-50 to-blue-100/50 p-4 rounded-xl border border-blue-200">
                        <div class="flex items-start gap-3">
                            <div class="w-10 h-10 bg-blue-500 rounded-lg flex items-center justify-center flex-shrink-0">
                                <i class="fa-solid fa-hospital text-white"></i>
                            </div>
                            <div>
                                <p class="text-xs text-blue-600 font-bold uppercase mb-1">Rumah Sakit</p>
                                <p id="modalRsName" class="font-bold text-gray-800 text-sm"></p>
                            </div>
                        </div>
                    </div>

                    <!-- Layanan -->
                    <div class="bg-gradient-to-br from-green-50 to-green-100/50 p-4 rounded-xl border border-green-200">
                        <div class="flex items-start gap-3">
                            <div class="w-10 h-10 bg-green-500 rounded-lg flex items-center justify-center flex-shrink-0">
                                <i class="fa-solid fa-stethoscope text-white"></i>
                            </div>
                            <div>
                                <p class="text-xs text-green-600 font-bold uppercase mb-1">Layanan</p>
                                <p id="modalLayanan" class="font-bold text-gray-800 text-sm"></p>
                            </div>
                        </div>
                    </div>

                    <!-- Nama Pasien -->
                    <div class="bg-gradient-to-br from-purple-50 to-purple-100/50 p-4 rounded-xl border border-purple-200">
                        <div class="flex items-start gap-3">
                            <div class="w-10 h-10 bg-purple-500 rounded-lg flex items-center justify-center flex-shrink-0">
                                <i class="fa-solid fa-user text-white"></i>
                            </div>
                            <div>
                                <p class="text-xs text-purple-600 font-bold uppercase mb-1">Nama Pasien</p>
                                <p id="modalPasien" class="font-bold text-gray-800 text-sm"></p>
                            </div>
                        </div>
                    </div>

                    <!-- NIK -->
                    <div class="bg-gradient-to-br from-orange-50 to-orange-100/50 p-4 rounded-xl border border-orange-200">
                        <div class="flex items-start gap-3">
                            <div class="w-10 h-10 bg-orange-500 rounded-lg flex items-center justify-center flex-shrink-0">
                                <i class="fa-solid fa-id-card text-white"></i>
                            </div>
                            <div>
                                <p class="text-xs text-orange-600 font-bold uppercase mb-1">NIK</p>
                                <p id="modalNik" class="font-bold text-gray-800 text-sm"></p>
                            </div>
                        </div>
                    </div>

                </div>

                <!-- Tanggal Kunjungan -->
                <div class="bg-gradient-to-br from-indigo-50 to-indigo-100/50 p-5 rounded-xl border border-indigo-200">
                    <div class="flex items-center gap-4">
                        <div class="w-16 h-16 bg-indigo-500 rounded-xl flex flex-col items-center justify-center text-white">
                            <span id="modalTanggal" class="text-2xl font-bold leading-none"></span>
                            <span id="modalBulan" class="text-xs font-medium"></span>
                        </div>
                        <div>
                            <p class="text-xs text-indigo-600 font-bold uppercase mb-1">Tanggal Kunjungan</p>
                            <p id="modalTanggalLengkap" class="font-bold text-gray-800"></p>
                            <p id="modalHari" class="text-sm text-gray-600"></p>
                        </div>
                    </div>
                </div>

                <!-- Queue Number & Estimasi (Jika dikonfirmasi) -->
                <div id="modalQueueSection" class="hidden">
                    <div class="grid grid-cols-2 gap-4">
                        <div class="bg-gradient-to-br from-blue-50 to-blue-100/50 p-4 rounded-xl border-2 border-blue-300 text-center">
                            <p class="text-xs text-blue-600 font-bold uppercase mb-2">No. Antrean</p>
                            <p id="modalQueue" class="text-3xl font-bold text-blue-600"></p>
                        </div>
                        <div class="bg-gradient-to-br from-gray-50 to-gray-100/50 p-4 rounded-xl border border-gray-300 text-center">
                            <p class="text-xs text-gray-600 font-bold uppercase mb-2">Estimasi Waktu</p>
                            <p id="modalEstimasi" class="text-lg font-bold text-gray-700"></p>
                        </div>
                    </div>
                </div>

                <!-- Catatan -->
                <div class="bg-gray-50 p-4 rounded-xl border border-gray-200">
                    <p class="text-xs text-gray-600 font-bold uppercase mb-2 flex items-center gap-2">
                        <i class="fa-solid fa-note-sticky"></i> Catatan
                    </p>
                    <p id="modalCatatan" class="text-sm text-gray-700"></p>
                </div>

                <!-- Status Badge -->
                <div class="flex justify-center">
                    <span id="modalStatus" class="px-6 py-2 rounded-full text-sm font-bold border flex items-center gap-2">
                        <i id="modalStatusIcon" class="fa-solid"></i>
                        <span id="modalStatusText"></span>
                    </span>
                </div>

                <!-- Dibuat Pada -->
                <div class="text-center pt-4 border-t border-gray-200">
                    <p class="text-xs text-gray-500">
                        Dibuat pada: <span id="modalDibuatPada" class="font-semibold text-gray-700"></span>
                    </p>
                </div>

            </div>

            <!-- Modal Footer -->
            <div class="bg-gray-50 p-4 rounded-b-3xl border-t border-gray-200">
                <button onclick="closeDetailModal()" class="w-full px-6 py-3 bg-gray-600 hover:bg-gray-700 text-white font-bold rounded-xl transition">
                    Tutup
                </button>
            </div>
        </div>
    </div>
</div>
