<?php
include 'includes/config.php';
include 'includes/header.php';

// Ambil data sekolah
$stmt = $pdo->query("SELECT * FROM sekolah LIMIT 1");
$sekolah = $stmt->fetch(PDO::FETCH_ASSOC);

// Ambil data kelas
$stmt = $pdo->query("SELECT * FROM kelas");
$kelas_list = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Ambil data siswa
$stmt = $pdo->query("SELECT s.*, k.nama_kelas FROM siswa s JOIN kelas k ON s.id_kelas = k.id ORDER BY k.nama_kelas, s.nama_siswa");
$siswa_list = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Ambil data guru
$stmt = $pdo->query("SELECT * FROM guru ORDER BY jabatan, nama_guru");
$guru_list = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Ambil data alumni
$stmt = $pdo->query("SELECT * FROM alumni ORDER BY tahun_lulus DESC");
$alumni_list = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!-- Breadcrumb -->
<nav aria-label="breadcrumb" class="bg-light py-2">
    <div class="container">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="index.php">Beranda</a></li>
            <li class="breadcrumb-item active">Yayasan TK</li>
        </ol>
    </div>
</nav>

<!-- Data Sekolah -->
<section class="py-5">
    <div class="container">
        <h2 class="text-center mb-4">Profil Yayasan TK</h2>
        <div class="card shadow">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h4><?php echo $sekolah['nama_sekolah']; ?></h4>
                        <p><strong>Alamat:</strong> <?php echo $sekolah['alamat']; ?></p>
                        <p><strong>Telepon:</strong> <?php echo $sekolah['telepon']; ?></p>
                        <p><strong>Email:</strong> <?php echo $sekolah['email']; ?></p>
                    </div>
                    <div class="col-md-6">
                        <img src="assets/img/sekolah.jpg" alt="Sekolah" class="img-fluid rounded">
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-md-6">
                        <h5>Visi</h5>
                        <p><?php echo $sekolah['visi']; ?></p>
                    </div>
                    <div class="col-md-6">
                        <h5>Misi</h5>
                        <p><?php echo nl2br($sekolah['misi']); ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Data Siswa -->
<section class="py-5 bg-light">
    <div class="container">
        <h2 class="text-center mb-4">Data Siswa</h2>
        <?php foreach($kelas_list as $kelas): ?>
            <div class="card shadow mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><?php echo $kelas['nama_kelas']; ?></h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>NIS</th>
                                    <th>Nama Siswa</th>
                                    <th>Tanggal Lahir</th>
                                    <th>Alamat</th>
                                    <th>Nama Orang Tua</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                $no = 1;
                                foreach($siswa_list as $siswa): 
                                    if($siswa['id_kelas'] == $kelas['id']): 
                                ?>
                                <tr>
                                    <td><?php echo $no++; ?></td>
                                    <td><?php echo $siswa['nis']; ?></td>
                                    <td><?php echo $siswa['nama_siswa']; ?></td>
                                    <td><?php echo date('d-m-Y', strtotime($siswa['tanggal_lahir'])); ?></td>
                                    <td><?php echo $siswa['alamat']; ?></td>
                                    <td><?php echo $siswa['nama_orang_tua']; ?></td>
                                </tr>
                                <?php 
                                    endif;
                                endforeach; 
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</section>

<!-- Data Guru -->
<section class="py-5">
    <div class="container">
        <h2 class="text-center mb-4">Data Guru & Staff</h2>
        <div class="row">
            <?php foreach($guru_list as $guru): ?>
                <div class="col-md-4 mb-4">
                    <div class="card h-100 shadow">
                        <img src="<?php echo $guru['foto'] ?: 'https://picsum.photos/seed/guru'.$guru['id'].'/300/300.jpg'; ?>" 
                             class="card-img-top" alt="<?php echo $guru['nama_guru']; ?>">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo $guru['nama_guru']; ?></h5>
                            <p class="card-text">
                                <strong>NIP:</strong> <?php echo $guru['nip']; ?><br>
                                <strong>Jabatan:</strong> <?php echo $guru['jabatan']; ?><br>
                                <strong>Pendidikan:</strong> <?php echo $guru['pendidikan']; ?>
                            </p>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Data Alumni -->
<section class="py-5 bg-light">
    <div class="container">
        <h2 class="text-center mb-4">Data Alumni</h2>
        <div class="row">
            <?php foreach($alumni_list as $alumni): ?>
                <div class="col-md-6 mb-4">
                    <div class="card h-100 shadow">
                        <div class="row g-0">
                            <div class="col-md-4">
                                <img src="<?php echo $alumni['foto'] ?: 'https://picsum.photos/seed/alumni'.$alumni['id'].'/200/200.jpg'; ?>" 
                                     class="img-fluid rounded-start h-100" alt="<?php echo $alumni['nama_alumni']; ?>">
                            </div>
                            <div class="col-md-8">
                                <div class="card-body">
                                    <h5 class="card-title"><?php echo $alumni['nama_alumni']; ?></h5>
                                    <p class="card-text">
                                        <strong>Tahun Lulus:</strong> <?php echo $alumni['tahun_lulus']; ?><br>
                                        <strong>Pekerjaan:</strong> <?php echo $alumni['pekerjaan']; ?>
                                    </p>
                                    <?php if($alumni['testimoni']): ?>
                                        <p class="card-text"><small class="text-muted">"<?php echo $alumni['testimoni']; ?>"</small></p>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>