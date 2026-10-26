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
        $nama_alumni = $_POST['nama_alumni'];
        $tahun_lulus = $_POST['tahun_lulus'];
        $pekerjaan = $_POST['pekerjaan'];
        $testimoni = $_POST['testimoni'];
        
        // Handle file upload
        $foto = '';
        if(isset($_FILES['foto']) && $_FILES['foto']['error'] == 0) {
            $target_dir = "../assets/img/alumni/";
            if(!file_exists($target_dir)) {
                mkdir($target_dir, 0777, true);
            }
            $foto = $target_dir . basename($_FILES["foto"]["name"]);
            move_uploaded_file($_FILES["foto"]["tmp_name"], $foto);
        }
        
        $stmt = $pdo->prepare("INSERT INTO alumni (nama_alumni, tahun_lulus, pekerjaan, testimoni, foto) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$nama_alumni, $tahun_lulus, $pekerjaan, $testimoni, $foto]);
        header('Location: alumni.php');
        exit();
    }
    
    if($_GET['action'] == 'edit' && isset($_GET['id']) && $_SERVER['REQUEST_METHOD'] == 'POST') {
        $id = $_GET['id'];
        $nama_alumni = $_POST['nama_alumni'];
        $tahun_lulus = $_POST['tahun_lulus'];
        $pekerjaan = $_POST['pekerjaan'];
        $testimoni = $_POST['testimoni'];
        
        // Handle file upload
        $foto = $_POST['foto_lama'];
        if(isset($_FILES['foto']) && $_FILES['foto']['error'] == 0) {
            $target_dir = "../assets/img/alumni/";
            if(!file_exists($target_dir)) {
                mkdir($target_dir, 0777, true);
            }
            $foto = $target_dir . basename($_FILES["foto"]["name"]);
            move_uploaded_file($_FILES["foto"]["tmp_name"], $foto);
            
            // Delete old photo
            if($_POST['foto_lama'] && file_exists($_POST['foto_lama'])) {
                unlink($_POST['foto_lama']);
            }
        }
        
        $stmt = $pdo->prepare("UPDATE alumni SET nama_alumni=?, tahun_lulus=?, pekerjaan=?, testimoni=?, foto=? WHERE id=?");
        $stmt->execute([$nama_alumni, $tahun_lulus, $pekerjaan, $testimoni, $foto, $id]);
        header('Location: alumni.php');
        exit();
    }
    
    if($_GET['action'] == 'delete' && isset($_GET['id'])) {
        $id = $_GET['id'];
        
        // Get photo path before delete
        $stmt = $pdo->prepare("SELECT foto FROM alumni WHERE id=?");
        $stmt->execute([$id]);
        $alumni = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // Delete photo file
        if($alumni['foto'] && file_exists($alumni['foto'])) {
            unlink($alumni['foto']);
        }
        
        $stmt = $pdo->prepare("DELETE FROM alumni WHERE id=?");
        $stmt->execute([$id]);
        header('Location: alumni.php');
        exit();
    }
}

// Get data for display
$alumni_list = $pdo->query("SELECT * FROM alumni ORDER BY tahun_lulus DESC, nama_alumni")->fetchAll(PDO::FETCH_ASSOC);

// Get data for edit
$edit_data = null;
if(isset($_GET['action']) && $_GET['action'] == 'edit' && isset($_GET['id'])) {
    $stmt = $pdo->prepare("SELECT * FROM alumni WHERE id=?");
    $stmt->execute([$_GET['id']]);
    $edit_data = $stmt->fetch(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Alumni - Admin Panel</title>
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
                            <a class="nav-link active" href="alumni.php">
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
                    <h1 class="h2">Data Alumni</h1>
                    <div class="btn-toolbar mb-2 mb-md-0">
                        <a href="?action=add" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Tambah Alumni
                        </a>
                    </div>
                </div>

                <!-- Add/Edit Form -->
                <?php if(isset($_GET['action']) && ($_GET['action'] == 'add' || $_GET['action'] == 'edit')): ?>
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary"><?php echo $edit_data ? 'Edit' : 'Tambah'; ?> Alumni</h6>
                    </div>
                    <div class="card-body">
                        <form method="POST" enctype="multipart/form-data">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Nama Alumni</label>
                                    <input type="text" class="form-control" name="nama_alumni" value="<?php echo $edit_data['nama_alumni'] ?? ''; ?>" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Tahun Lulus</label>
                                    <input type="number" class="form-control" name="tahun_lulus" value="<?php echo $edit_data['tahun_lulus'] ?? ''; ?>" min="1990" max="<?php echo date('Y'); ?>" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Pekerjaan</label>
                                    <input type="text" class="form-control" name="pekerjaan" value="<?php echo $edit_data['pekerjaan'] ?? ''; ?>" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Foto</label>
                                    <input type="file" class="form-control" name="foto" accept="image/*">
                                    <?php if($edit_data && $edit_data['foto']): ?>
                                        <div class="mt-2">
                                            <img src="<?php echo $edit_data['foto']; ?>" alt="Foto Alumni" class="img-thumbnail" style="max-height: 100px;">
                                            <input type="hidden" name="foto_lama" value="<?php echo $edit_data['foto']; ?>">
                                        </div>
                                    <?php endif; ?>
                                </div>
                                <div class="col-12 mb-3">
                                    <label class="form-label">Testimoni</label>
                                    <textarea class="form-control" name="testimoni" rows="3"><?php echo $edit_data['testimoni'] ?? ''; ?></textarea>
                                </div>
                            </div>
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-success">
                                    <i class="fas fa-save"></i> Simpan
                                </button>
                                <a href="alumni.php" class="btn btn-secondary">
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
                                        <th>Foto</th>
                                        <th>Nama Alumni</th>
                                        <th>Tahun Lulus</th>
                                        <th>Pekerjaan</th>
                                        <th>Testimoni</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $no = 1; foreach($alumni_list as $alumni): ?>
                                    <tr>
                                        <td><?php echo $no++; ?></td>
                                        <td>
                                            <?php if($alumni['foto']): ?>
                                                <img src="<?php echo $alumni['foto']; ?>" alt="Foto" class="img-thumbnail" style="max-height: 50px;">
                                            <?php else: ?>
                                                <i class="fas fa-user fa-2x text-muted"></i>
                                            <?php endif; ?>
                                        </td>
                                        <td><?php echo $alumni['nama_alumni']; ?></td>
                                        <td><?php echo $alumni['tahun_lulus']; ?></td>
                                        <td><?php echo $alumni['pekerjaan']; ?></td>
                                        <td>
                                            <?php 
                                            if($alumni['testimoni']) {
                                                echo substr($alumni['testimoni'], 0, 50) . '...';
                                            } else {
                                                echo '-';
                                            }
                                            ?>
                                        </td>
                                        <td>
                                            <a href="?action=edit&id=<?php echo $alumni['id']; ?>" class="btn btn-sm btn-warning">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <a href="?action=delete&id=<?php echo $alumni['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin menghapus data ini?')">
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