<?php
session_start();
require_once '../../config/db_connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $identifier = mysqli_real_escape_string($conn, $_POST['identifier']); // Email
    $password = $_POST['password'];

    // 1. Cek di tabel USER (Pasien/Umum) - Ambil data user berdasarkan email
    $query_user = "SELECT * FROM akun_user WHERE email = '$identifier'";
    $result_user = mysqli_query($conn, $query_user);

    if (mysqli_num_rows($result_user) > 0) {
        $data = mysqli_fetch_assoc($result_user);
        
        // Verifikasi password dengan bcrypt
        if (password_verify($password, $data['password'])) {
            // Login Berhasil sebagai User
            
            // Set Session
            $_SESSION['user_id'] = $data['id_user'];
            $_SESSION['user_name'] = $data['nama'];
            $_SESSION['role'] = 'pasien';
            
            // Redirect ke halaman yang dituju sebelumnya (jika ada)
            if(isset($_SESSION['redirect_after_login'])) {
                $redirect_url = $_SESSION['redirect_after_login'];
                unset($_SESSION['redirect_after_login']); // Hapus session redirect
                
                // Jika URL sudah lengkap dengan path /finders/, gunakan langsung
                // Jika tidak, tambahkan prefix ../../
                if(strpos($redirect_url, '/finders/') === 0) {
                    // URL sudah lengkap, ambil bagian setelah /finders/
                    $redirect_url = str_replace('/finders/', '', $redirect_url);
                    header("Location: ../../$redirect_url");
                } else {
                    // URL relatif, gunakan langsung
                    header("Location: ../../$redirect_url");
                }
            } else {
                header("Location: ../../index.php");
            }
            exit;
        }
    }

    // 2. Cek di tabel ADMIN (Opsional, jika login admin lewat pintu yang sama)
    $query_admin = "SELECT * FROM akun_admin WHERE (email = '$identifier' OR username = '$identifier')";
    $result_admin = mysqli_query($conn, $query_admin);

    if (mysqli_num_rows($result_admin) > 0) {
        $data = mysqli_fetch_assoc($result_admin);
        
        // Verifikasi password dengan bcrypt
        if (password_verify($password, $data['password'])) {
            $_SESSION['admin_id'] = $data['id_admin'];
            $_SESSION['admin_name'] = $data['username'];
            $_SESSION['role'] = $data['role'];

            // Redirect ke halaman yang dituju sebelumnya (jika ada dan halaman admin)
            if(isset($_SESSION['redirect_after_login'])) {
                $redirect_url = $_SESSION['redirect_after_login'];
                
                // Cek apakah redirect URL adalah halaman admin
                if(strpos($redirect_url, '/admin/') !== false || strpos($redirect_url, 'admin/') !== false) {
                    unset($_SESSION['redirect_after_login']); // Hapus session redirect
                    
                    // Jika URL sudah lengkap dengan path /finders/, gunakan langsung
                    if(strpos($redirect_url, '/finders/') === 0) {
                        // URL sudah lengkap, ambil bagian setelah /finders/
                        $redirect_url = str_replace('/finders/', '', $redirect_url);
                        header("Location: ../../$redirect_url");
                    } else {
                        // URL relatif, gunakan langsung
                        header("Location: ../../$redirect_url");
                    }
                    exit;
                }
            }
            
            // Default redirect ke admin dashboard
            header("Location: ../../admin/index.php");
            exit;
        }
    }

    // Jika Gagal Login
    echo "<script>
        alert('Email atau Password salah!'); 
        window.location.href='../../login.php';
    </script>";

} else {
    header("Location: ../../login.php");
}
?>