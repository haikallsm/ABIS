<?php
session_start();
require_once __DIR__ . '/../config/database.php';

$username = $_POST['username'] ?? '';
$password = $_POST['password'] ?? '';

$user = fetchOne(
    "SELECT * FROM users WHERE username = :u AND is_active = 1",
    ['u' => $username]
);

if (!$user || !password_verify($password, $user['password'])) {
    die("Login gagal. <a href='login.php'>Coba lagi</a>");
}

// SIMPAN SESSION
$_SESSION['user'] = [
    'id' => $user['id'],
    'username' => $user['username'],
    'role' => $user['role'],
    'name' => $user['full_name']
];

// REDIRECT SESUAI ROLE
if ($user['role'] === 'admin') {
    header("Location: riwayat-surat.php");
} else {
    header("Location: index.php");
}
exit;
