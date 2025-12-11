<?php
session_start();
require_once '../config/db_connect.php';

// Cek Login Admin
if(!isset($_SESSION['admin_id'])) {
    header("Location: ../login.php");
    exit;
}

// Ambil data dari form
$mode = $_POST['mode'] ?? '';
$id_admin = $_SESSION['admin_id'];

// ===========================
// FUNGSI UPLOAD FOTO
// ===========================
function uploadFoto($file, $foto_lama = null) {
    $target_dir = "../assets/img/";
    $allowed_types = ['image/jpeg', 'image/jpg', 'image/png'];
    $max_size = 2 * 1024 * 1024; // 2MB
    
    // Jika tidak ada file yang diupload, gunakan foto lama atau default
    if($file['error'] == UPLOAD_ERR_NO_FILE) {
        return $foto_lama ?? 'default_rs.jpg';
    }
    
    // Validasi tipe file
    if(!in_array($file['type'], $allowed_types)) {
        $_SESSION['error_message'] = "Format file tidak didukung. Gunakan JPG, JPEG, atau PNG.";
        return false;
    }
    
    // Validasi ukuran file
    if($file['size'] > $max_size) {
        $_SESSION['error_message'] = "Ukuran file terlalu besar. Maksimal 2MB.";
        return false;
    }
    
    // Generate nama file unik
    $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
    $new_filename = 'rs_' . time() . '_' . uniqid() . '.' . $extension;
    $target_file = $target_dir . $new_filename;
    
    // Upload file
    if(move_uploaded_file($file['tmp_name'], $target_file)) {
        // Hapus foto lama jika ada dan bukan default
        if($foto_lama && $foto_lama != 'default_rs.jpg' && file_exists($target_dir . $foto_lama)) {
            unlink($target_dir . $foto_lama);
        }
        return $new_filename;
    } else {
        $_SESSION['error_message'] = "Gagal mengupload foto.";
        return false;
    }
}

// ===========================
// PROSES TAMBAH DATA
// ===========================
if($mode == 'tambah') {
    
    $nama_rs = mysqli_real_escape_string($conn, $_POST['nama_rs']);
    $alamat = mysqli_real_escape_string($conn, $_POST['alamat']);
    $wilayah = mysqli_real_escape_string($conn, $_POST['wilayah']);
    $no_telpon = mysqli_real_escape_string($conn, $_POST['no_telpon']);
    $deskripsi = mysqli_real_escape_string($conn, $_POST['deskripsi']);
    
    // Validasi deskripsi minimal 50 karakter
    if(strlen($deskripsi) < 50) {
        $_SESSION['error_message'] = "Deskripsi minimal 50 karakter!";
        header("Location: rs_data.php");
        exit;
    }
    
    // Upload foto
    $foto = uploadFoto($_FILES['foto']);
    if($foto === false) {
        header("Location: rs_data.php");
        exit;
    }
    
    // Insert ke database
    $query = "INSERT INTO data_rumah_sakit 
              (nama_rs, alamat, wilayah, no_telpon, deskripsi, foto, id_admin, dibuat_pada) 
              VALUES 
              ('$nama_rs', '$alamat', '$wilayah', '$no_telpon', '$deskripsi', '$foto', '$id_admin', NOW())";
    
    if(mysqli_query($conn, $query)) {
        $_SESSION['success_message'] = "Data rumah sakit berhasil ditambahkan!";
    } else {
        $_SESSION['error_message'] = "Gagal menambahkan data: " . mysqli_error($conn);
    }
    
    header("Location: rs_data.php");
    exit;
}

// ===========================
// PROSES EDIT DATA
// ===========================
elseif($mode == 'edit') {
    
    $id_rs = mysqli_real_escape_string($conn, $_POST['id_rs']);
    $nama_rs = mysqli_real_escape_string($conn, $_POST['nama_rs']);
    $alamat = mysqli_real_escape_string($conn, $_POST['alamat']);
    $wilayah = mysqli_real_escape_string($conn, $_POST['wilayah']);
    $no_telpon = mysqli_real_escape_string($conn, $_POST['no_telpon']);
    $deskripsi = mysqli_real_escape_string($conn, $_POST['deskripsi']);
    $foto_lama = $_POST['foto_lama'];
    
    // Validasi deskripsi minimal 50 karakter
    if(strlen($deskripsi) < 50) {
        $_SESSION['error_message'] = "Deskripsi minimal 50 karakter!";
        header("Location: rs_data.php");
        exit;
    }
    
    // Upload foto baru (jika ada)
    $foto = uploadFoto($_FILES['foto'], $foto_lama);
    if($foto === false) {
        header("Location: rs_data.php");
        exit;
    }
    
    // Update database
    $query = "UPDATE data_rumah_sakit SET 
              nama_rs = '$nama_rs',
              alamat = '$alamat',
              wilayah = '$wilayah',
              no_telpon = '$no_telpon',
              deskripsi = '$deskripsi',
              foto = '$foto',
              diperbarui_pada = NOW()
              WHERE id_rs = '$id_rs'";
    
    if(mysqli_query($conn, $query)) {
        $_SESSION['success_message'] = "Data rumah sakit berhasil diupdate!";
    } else {
        $_SESSION['error_message'] = "Gagal mengupdate data: " . mysqli_error($conn);
    }
    
    header("Location: rs_data.php");
    exit;
}

// ===========================
// JIKA MODE TIDAK VALID
// ===========================
else {
    $_SESSION['error_message'] = "Mode tidak valid!";
    header("Location: rs_data.php");
    exit;
}
?>