<?php
session_start();
if(!isset($_SESSION['admin'])) {
    header('Location: index.php');
    exit();
}

include '../includes/config.php';
$error = '';
$success = '';

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $password_lama = $_POST['password_lama'];
    $password_baru = $_POST['password_baru'];
    $konfirmasi = $_POST['konfirmasi_password'];
    
    // Validasi
    if(empty($password_lama) || empty($password_baru) || empty($konfirmasi)) {
        $error = "Semua field harus diisi!";
    } elseif($password_baru !== $konfirmasi) {
        $error = "Password baru tidak cocok!";
    } else {
        // Cek password lama
        $stmt = $pdo->prepare("SELECT * FROM admin WHERE username = ?");
        $stmt->execute([$_SESSION['admin']['username']]);
        $admin = $stmt->fetch();
        
        if(password_verify($password_lama, $admin['password'])) {
            // Update password
            $hash = password_hash($password_baru, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("UPDATE admin SET password = ? WHERE id = ?");
            $stmt->execute([$hash, $admin['id']]);
            $success = "Password berhasil diubah!";
        } else {
            $error = "Password lama salah!";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Ubah Password - Admin Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="../assets/css/style.css" rel="stylesheet">
</head>
<body class="admin-panel">
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <nav class="col-md-2 d-none d-md-block sidebar">
                <div class="position-sticky pt-3">
                    <h5 class="text-center mb-4">Admin Panel</h5>
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link" href="dashboard.php">
                                <i class="fas fa-tachometer-alt"></i> Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="sekolah.php">
                                <i class="fas fa-school"></i> Data Sekolah
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="siswa.php">
                                <i class="fas fa-child"></i> Data Siswa
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="guru.php">
                                <i class="fas fa-chalkboard-teacher"></i> Data Guru
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="alumni.php">
                                <i class="fas fa-user-graduate"></i> Data Alumni
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" href="ubah_password.php">
                                <i class="fas fa-key"></i> Ubah Password
                            </a>
                        </li>
                        <li class="nav-item mt-auto">
                            <a class="nav-link" href="logout.php">
                                <i class="fas fa-sign-out-alt"></i> Logout
                            </a>
                        </li>
                    </ul>
                </div>
            </nav>

            <!-- Main Content -->
            <main class="col-md-10 ms-sm-auto px-md-4">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">Ubah Password</h1>
                </div>

                <div class="row justify-content-center">
                    <div class="col-md-6">
                        <div class="card shadow">
                            <div class="card-header py-3">
                                <h6 class="m-0 font-weight-bold text-primary">Form Ubah Password</h6>
                            </div>
                            <div class="card-body">
                                <?php if($error): ?>
                                    <div class="alert alert-danger"><?php echo $error; ?></div>
                                <?php endif; ?>
                                
                                <?php if($success): ?>
                                    <div class="alert alert-success"><?php echo $success; ?></div>
                                <?php endif; ?>

                                <form method="POST">
                                    <div class="mb-3">
                                        <label class="form-label">Password Lama</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                            <input type="password" class="form-control" name="password_lama" required>
                                        </div>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label class="form-label">Password Baru</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                            <input type="password" class="form-control" name="password_baru" required>
                                        </div>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label class="form-label">Konfirmasi Password Baru</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                            <input type="password" class="form-control" name="konfirmasi_password" required>
                                        </div>
                                    </div>
                                    
                                    <div class="d-grid">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-save"></i> Ubah Password
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>