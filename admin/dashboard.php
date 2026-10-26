<?php
session_start();
if(!isset($_SESSION['admin'])) {
    header('Location: index.php');
    exit();
}

include '../includes/config.php';

// Hitung total data
$total_siswa = $pdo->query("SELECT COUNT(*) FROM siswa")->fetchColumn();
$total_guru = $pdo->query("SELECT COUNT(*) FROM guru")->fetchColumn();
$total_alumni = $pdo->query("SELECT COUNT(*) FROM alumni")->fetchColumn();
$total_kelas = $pdo->query("SELECT COUNT(*) FROM kelas")->fetchColumn();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - Yayasan TK</title>
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
                            <a class="nav-link active" href="dashboard.php">
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
                    <h1 class="h2">Dashboard</h1>
                    <div class="btn-toolbar mb-2 mb-md-0">
                        <span class="text-muted">Selamat datang, <?php echo $_SESSION['admin']['username']; ?></span>
                    </div>
                </div>

                <!-- Statistics Cards -->
                <div class="row mb-4">
                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card border-left-primary shadow h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Siswa</div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $total_siswa; ?></div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-child fa-2x text-gray-300"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card border-left-success shadow h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Total Guru</div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $total_guru; ?></div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-chalkboard-teacher fa-2x text-gray-300"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card border-left-info shadow h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Total Alumni</div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $total_alumni; ?></div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-user-graduate fa-2x text-gray-300"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card border-left-warning shadow h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Total Kelas</div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $total_kelas; ?></div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-layer-group fa-2x text-gray-300"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Aksi Cepat</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3 mb-3">
                                <a href="siswa.php?action=add" class="btn btn-primary btn-block">
                                    <i class="fas fa-plus"></i> Tambah Siswa
                                </a>
                            </div>
                            <div class="col-md-3 mb-3">
                                <a href="guru.php?action=add" class="btn btn-success btn-block">
                                    <i class="fas fa-plus"></i> Tambah Guru
                                </a>
                            </div>
                            <div class="col-md-3 mb-3">
                                <a href="alumni.php?action=add" class="btn btn-info btn-block">
                                    <i class="fas fa-plus"></i> Tambah Alumni
                                </a>
                            </div>
                            <div class="col-md-3 mb-3">
                                <a href="sekolah.php" class="btn btn-warning btn-block">
                                    <i class="fas fa-edit"></i> Edit Profil
                                </a>
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