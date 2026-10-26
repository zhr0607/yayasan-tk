<?php
session_start();
if(!isset($_SESSION['admin'])) {
    header('Location: index.php');
    exit();
}

include '../includes/config.php';

// Handle CRUD operations
if(isset($_GET['action'])) {
    if($_GET['action'] == 'add' && $_SERVER['REQUEST_METHOD'] == 'POST') {
        $nis = $_POST['nis'];
        $nama_siswa = $_POST['nama_siswa'];
        $id_kelas = $_POST['id_kelas'];
        $tanggal_lahir = $_POST['tanggal_lahir'];
        $alamat = $_POST['alamat'];
        $nama_orang_tua = $_POST['nama_orang_tua'];
        
        $stmt = $pdo->prepare("INSERT INTO siswa (nis, nama_siswa, id_kelas, tanggal_lahir, alamat, nama_orang_tua) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$nis, $nama_siswa, $id_kelas, $tanggal_lahir, $alamat, $nama_orang_tua]);
        header('Location: siswa.php');
        exit();
    }
    
    if($_GET['action'] == 'edit' && isset($_GET['id']) && $_SERVER['REQUEST_METHOD'] == 'POST') {
        $id = $_GET['id'];
        $nis = $_POST['nis'];
        $nama_siswa = $_POST['nama_siswa'];
        $id_kelas = $_POST['id_kelas'];
        $tanggal_lahir = $_POST['tanggal_lahir'];
        $alamat = $_POST['alamat'];
        $nama_orang_tua = $_POST['nama_orang_tua'];
        
        $stmt = $pdo->prepare("UPDATE siswa SET nis=?, nama_siswa=?, id_kelas=?, tanggal_lahir=?, alamat=?, nama_orang_tua=? WHERE id=?");
        $stmt->execute([$nis, $nama_siswa, $id_kelas, $tanggal_lahir, $alamat, $nama_orang_tua, $id]);
        header('Location: siswa.php');
        exit();
    }
    
    if($_GET['action'] == 'delete' && isset($_GET['id'])) {
        $id = $_GET['id'];
        $stmt = $pdo->prepare("DELETE FROM siswa WHERE id=?");
        $stmt->execute([$id]);
        header('Location: siswa.php');
        exit();
    }
}

// Get data for display
$siswa_list = $pdo->query("SELECT s.*, k.nama_kelas FROM siswa s JOIN kelas k ON s.id_kelas = k.id ORDER BY k.nama_kelas, s.nama_siswa")->fetchAll(PDO::FETCH_ASSOC);
$kelas_list = $pdo->query("SELECT * FROM kelas")->fetchAll(PDO::FETCH_ASSOC);

// Get data for edit
$edit_data = null;
if(isset($_GET['action']) && $_GET['action'] == 'edit' && isset($_GET['id'])) {
    $stmt = $pdo->prepare("SELECT * FROM siswa WHERE id=?");
    $stmt->execute([$_GET['id']]);
    $edit_data = $stmt->fetch(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Siswa - Admin Panel</title>
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
                            <a class="nav-link active" href="siswa.php">
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
                    <h1 class="h2">Data Siswa</h1>
                    <div class="btn-toolbar mb-2 mb-md-0">
                        <a href="?action=add" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Tambah Siswa
                        </a>
                    </div>
                </div>

                <!-- Add/Edit Form -->
                <?php if(isset($_GET['action']) && ($_GET['action'] == 'add' || $_GET['action'] == 'edit')): ?>
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary"><?php echo $edit_data ? 'Edit' : 'Tambah'; ?> Siswa</h6>
                    </div>
                    <div class="card-body">
                        <form method="POST">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">NIS</label>
                                    <input type="text" class="form-control" name="nis" value="<?php echo $edit_data['nis'] ?? ''; ?>" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Nama Siswa</label>
                                    <input type="text" class="form-control" name="nama_siswa" value="<?php echo $edit_data['nama_siswa'] ?? ''; ?>" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Kelas</label>
                                    <select class="form-select" name="id_kelas" required>
                                        <option value="">Pilih Kelas</option>
                                        <?php foreach($kelas_list as $kelas): ?>
                                            <option value="<?php echo $kelas['id']; ?>" <?php echo ($edit_data['id_kelas'] ?? '') == $kelas['id'] ? 'selected' : ''; ?>>
                                                <?php echo $kelas['nama_kelas']; ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Tanggal Lahir</label>
                                    <input type="date" class="form-control" name="tanggal_lahir" value="<?php echo $edit_data['tanggal_lahir'] ?? ''; ?>" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Alamat</label>
                                    <textarea class="form-control" name="alamat" rows="2" required><?php echo $edit_data['alamat'] ?? ''; ?></textarea>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Nama Orang Tua</label>
                                    <input type="text" class="form-control" name="nama_orang_tua" value="<?php echo $edit_data['nama_orang_tua'] ?? ''; ?>" required>
                                </div>
                            </div>
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-success">
                                    <i class="fas fa-save"></i> Simpan
                                </button>
                                <a href="siswa.php" class="btn btn-secondary">
                                    <i class="fas fa-times"></i> Batal
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
                <?php endif; ?>

                <!-- Data Table -->
                <div class="card shadow">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>NIS</th>
                                        <th>Nama Siswa</th>
                                        <th>Kelas</th>
                                        <th>Tanggal Lahir</th>
                                        <th>Nama Orang Tua</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $no = 1; foreach($siswa_list as $siswa): ?>
                                    <tr>
                                        <td><?php echo $no++; ?></td>
                                        <td><?php echo $siswa['nis']; ?></td>
                                        <td><?php echo $siswa['nama_siswa']; ?></td>
                                        <td><?php echo $siswa['nama_kelas']; ?></td>
                                        <td><?php echo date('d-m-Y', strtotime($siswa['tanggal_lahir'])); ?></td>
                                        <td><?php echo $siswa['nama_orang_tua']; ?></td>
                                        <td>
                                            <a href="?action=edit&id=<?php echo $siswa['id']; ?>" class="btn btn-sm btn-warning">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <a href="?action=delete&id=<?php echo $siswa['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin menghapus data ini?')">
                                                <i class="fas fa-trash"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>