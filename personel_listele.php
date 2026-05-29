<?php
session_start();
require_once 'baglanti.php';

// Güvenlik kontrolü: Giriş yapmayan göremez
if (!isset($_SESSION['kullanici_id'])) {
    header("Location: index.php");
    exit;
}

// Veritabanından personelleri VE departman isimlerini INNER JOIN ile çekiyoruz
$sorgu = $db->query("
    SELECT p.*, d.departman_adi 
    FROM personeller p 
    LEFT JOIN departmanlar d ON p.departman_id = d.id
");
$personeller = $sorgu->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Personel Listesi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; }
        .sidebar { height: 100vh; background-color: #212529; color: white; padding-top: 20px; }
        .sidebar a { color: rgba(255,255,255,0.75); text-decoration: none; padding: 10px 20px; display: block; }
        .sidebar a:hover { background-color: #343a40; color: white; }
    </style>
</head>
<body>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-2 sidebar px-0">
            <h4 class="text-center mb-4">Şirket Paneli</h4>
            <p class="text-center text-muted small">Hoş geldin,<br><strong><?php echo $_SESSION['kullanici_ad']; ?></strong></p>
            <hr>
            <a href="panel.php">🏠 Ana Sayfa</a>
            <a href="personel_listele.php" class="active bg-secondary text-white">👥 Personel Yönetimi</a>
            <a href="stok_listele.php">📦 Stok Takibi</a>
            <a href="#">📅 Randevu Sistemi</a>
            <hr>
            <a href="panel.php?cikis=1" class="text-danger">❌ Güvenli Çıkış</a>
        </div>

        <div class="col-md-10 p-4">
            <div class="d-flex justify-content-between align-items-center">
                <h2>Personel Listesi</h2>
                <a href="personel_ekle.php" class="btn btn-success">+ Yeni Personel Ekle</a>
            </div>
            <p class="text-muted">Şirkette kayıtlı tüm personellerin listesi ve yetki rolleri.</p>
            <hr>

            <div class="card shadow-sm">
                <div class="card-body p-0">
                    <table class="table table-hover table-striped mb-0">
                        <thead class="table-dark">
                            <tr>
                                <th>ID</th>
                                <th>Ad Soyad</th>
                                <th>E-posta</th>
                                <th>Telefon</th>
                                <th>Departman</th>
                                <th>Maaş</th>
                                <th>Rol</th>
                                <th>İşlemler</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($personeller as $personel): ?>
                                <tr>
                                    <td><?php echo $personel['id']; ?></td>
                                    <td><strong><?php echo $personel['ad_soyad']; ?></strong></td>
                                    <td><?php echo $personel['eposta']; ?></td>
                                    <td><?php echo $personel['telefon']; ?></td>
                                    <td>
                                        <span class="badge bg-secondary">
                                            <?php echo $personel['departman_adi'] ?? 'Atanmamış'; ?>
                                        </span>
                                    </td>
                                    <td><?php echo number_format($personel['maas'], 2, ',', '.'); ?> ₺</td>
                                    <td>
                                        <span class="badge <?php echo $personel['rol'] == 'admin' ? 'bg-danger' : 'bg-primary'; ?>">
                                            <?php echo $personel['rol']; ?>
                                        </span>
                                    </td>
                                    <td>
                                        <?php if($_SESSION['kullanici_rol'] == 'admin'): ?>
                                            <a href="#" class="btn btn-sm btn-warning">Düzenle</a>
                                            <a href="#" class="btn btn-sm btn-danger">Sil</a>
                                        <?php else: ?>
                                            <span class="text-muted small"><label class="badge bg-light text-dark">Yetki Yok</label></span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</div>

</body>
</html>