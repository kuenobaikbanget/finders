<form action="jadwal_proses.php" method="POST" class="space-y-4">

    <h2 class="text-xl font-semibold text-gray-700 mb-4">
        Tambah Jadwal Pelayanan
    </h2>

    <div>
        <label class="text-sm text-gray-600">Nama Pasien</label>
        <input type="text" name="nama" 
               class="w-full p-2 border rounded-xl mt-1">
    </div>

    <div>
        <label class="text-sm text-gray-600">Rumah Sakit</label>
        <select name="rs" class="w-full p-2 border rounded-xl mt-1">
            <option value="">-- pilih --</option>
            <!-- Data rumah sakit dari DB -->
            <?php 
            include '../koneksi.php';
            $rs = mysqli_query($conn, "SELECT * FROM rumah_sakit");
            while($d=mysqli_fetch_array($rs)) {
                echo "<option value='$d[id]'>$d[nama_rs]</option>";
            }
            ?>
        </select>
    </div>

    <div>
        <label class="text-sm text-gray-600">Layanan</label>
        <select name="layanan" class="w-full p-2 border rounded-xl mt-1">
            <option value="">-- pilih --</option>
            <?php 
            $lay = mysqli_query($conn, "SELECT * FROM layanan");
            while($d=mysqli_fetch_array($lay)) {
                echo "<option value='$d[id]'>$d[nama_layanan]</option>";
            }
            ?>
        </select>
    </div>

    <div>
        <label class="text-sm text-gray-600">Tanggal</label>
        <input type="date" name="tanggal" 
               class="w-full p-2 border rounded-xl mt-1">
    </div>

    <button type="submit" 
            class="w-full bg-green-600 text-white py-2 rounded-xl hover:bg-green-700">
        Simpan
    </button>

</form>
