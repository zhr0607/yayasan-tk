<?php
// Fungsi untuk membuat hash password
function createPasswordHash($password) {
    return password_hash($password, PASSWORD_DEFAULT);
}

// Fungsi untuk mengganti password admin
function changeAdminPassword($pdo, $username, $newPassword) {
    $hash = createPasswordHash($newPassword);
    $stmt = $pdo->prepare("UPDATE admin SET password = ? WHERE username = ?");
    return $stmt->execute([$hash, $username]);
}

// Fungsi untuk menambah user admin baru
function addAdminUser ($pdo, $username, $password) {
    // Cek apakah username sudah ada
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM admin WHERE username = ?");
    $stmt->execute([$username]);
    if($stmt->fetchColumn() > 0) {
        return false; // Username sudah ada
    }
    $hash = createPasswordHash($password);
    $stmt = $pdo->prepare("INSERT INTO admin (username, password) VALUES (?, ?)");
    return $stmt->execute([$username, $hash]);
}
?>