<?php
session_start();
include '../config/db_connect.php';

// Cek apakah user sudah login
if(!isset($_SESSION['user_id'])) {
    // Simpan URL tujuan untuk redirect setelah login
    $_SESSION['redirect_after_login'] = $_SERVER['REQUEST_URI'];
    header("Location: ../login.php");
    exit;
}

// Ambil data dari form
$id_user = $_SESSION['user_id'];
$nama = mysqli_real_escape_string($conn, $_POST['nama']);
$email = mysqli_real_escape_string($conn, $_POST['email']);
$no_telpon = mysqli_real_escape_string($conn, $_POST['no_telpon']);
$password_lama = isset($_POST['password_lama']) ? trim($_POST['password_lama']) : ''; 
$password_baru = $_POST['password_baru'] ?? '';

// Ambil data user saat ini dari database
$query = mysqli_query($conn, "SELECT * FROM akun_user WHERE id_user = '$id_user'");
if (!$query || mysqli_num_rows($query) == 0) {
    // Jika user tidak ditemukan, logout paksa
    session_destroy();
    header("Location: ../login.php");
    exit;
}
$user = mysqli_fetch_array($query);

// --- LOGIKA VERIFIKASI ---

// Skenario 1: User mengisi Password Lama (Saat Edit Email / Telepon / Password)
if(!empty($password_lama)) {
    // Verifikasi password dengan bcrypt
    if (!password_verify($password_lama, $user['password'])) {
        $_SESSION['msg_type'] = 'error';
        $_SESSION['msg_content'] = 'Password lama yang Anda masukkan salah!';
        header("Location: ../profile.php");
        exit;
    }
} 
// Skenario 2: User TIDAK mengisi Password Lama (Saat Edit Nama)
else {
    // Pastikan user benar-benar hanya mengubah Nama.
    // Jika Email atau Telepon berubah tapi tidak ada password, tolak.
    if($_POST['email'] != $user['email'] || $_POST['no_telpon'] != $user['no_telpon'] || !empty($password_baru)) {
        $_SESSION['msg_type'] = 'error';
        $_SESSION['msg_content'] = 'Demi keamanan, Anda wajib memasukkan password lama untuk mengubah Email, Nomor Telepon, atau Password!';
        header("Location: ../profile.php");
        exit;
    }
}

// --- PROSES UPDATE ---

// Cek apakah email sudah digunakan user lain (hanya jika email berubah)
if($email != $user['email']) {
    $check_email = mysqli_query($conn, "SELECT id_user FROM akun_user WHERE email = '$email' AND id_user != '$id_user'");
    if(mysqli_num_rows($check_email) > 0) {
        $_SESSION['msg_type'] = 'error';
        $_SESSION['msg_content'] = 'Email sudah digunakan oleh user lain!';
        header("Location: ../profile.php");
        exit;
    }
}

// Update data user
$update_query = "UPDATE akun_user SET 
                 nama = '$nama',
                 email = '$email',
                 no_telpon = '$no_telpon'";

// Jika password baru diisi, validasi dan update password
if(!empty($password_baru)) {
    // Validasi konfirmasi password (jika ada)
    if(isset($_POST['konfirmasi_password_baru'])) {
        $konfirmasi_password_baru = $_POST['konfirmasi_password_baru'];
        if($password_baru !== $konfirmasi_password_baru) {
            $_SESSION['msg_type'] = 'error';
            $_SESSION['msg_content'] = 'Password baru dan konfirmasi password tidak cocok!';
            header("Location: ../profile.php");
            exit;
        }
    }
    
    // Hash password baru dengan bcrypt
    $password_hash = password_hash($password_baru, PASSWORD_DEFAULT);
    $update_query .= ", password = '$password_hash'";
}

$update_query .= " WHERE id_user = '$id_user'";

if(mysqli_query($conn, $update_query)) {
    $_SESSION['msg_type'] = 'success';
    $_SESSION['msg_content'] = 'Profil berhasil diperbarui!';
    
    // Update session nama jika nama berubah
    $_SESSION['nama'] = $nama;
} else {
    $_SESSION['msg_type'] = 'error';
    $_SESSION['msg_content'] = 'Terjadi kesalahan saat memperbarui profil!';
}

header("Location: ../profile.php");
exit;
?>