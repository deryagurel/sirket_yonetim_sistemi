<?php
session_start();
require_once 'baglanti.php';

// Güvenlik kontrolü: Giriş yapmayan göremez
if (!isset($_SESSION['kullanici_id'])) {
    header("Location: index.php");
    exit;
}

// Veritabanındaki ürünleri çekiyoruz
$sorgu = $db->query("SELECT * FROM urunler");
$urunler = $sorgu->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Stok Takip Sistemi</title>
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
            <a href="personel_listele.php">👥 Personel Yönetimi</a>
            <a href="stok_listele.php" class="active bg-secondary text-white">📦 Stok Takibi</a>
            <a href="#">📅 Randevu Sistemi</a>
            <hr>
            <a href="panel.php?cikis=1" class="text-danger">❌ Güvenli Çıkış</a>
        </div>

        <div class="col-md-10 p-4">
            <div class="d-flex justify-content-between align-items-center">
                <h2>Stok Takip Otomasyonu</h2>
                <button class="btn btn-primary" onclick="alert('Stok güncelleme fonksiyonu hazır!')">🔄 Stok Hareket Raporu</button>
            </div>
            <p class="text-muted">Ürünlerin anlık durumları ve kritik stok kontrolleri.</p>
            <hr>

            <div class="card shadow-sm">
                <div class="card-body p-0">
                    <table class="table table-hover mb-0 align-middle">
                        <thead class="table-dark">
                            <tr>
                                <th>ID</th>
                                <th>Ürün Adı</th>
                                <th>Mevcut Stok</th>
                                <th>Kritik Limit</th>
                                <th>Fiyat</th>
                                <th>Durum</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($urunler as $urun): ?>
                                <?php 
                                    // KRİTİK STOK ALGORİTMASI
                                    // Eğer stok adedi kritik limite eşit veya daha azsa uyarı tetiklensin
                                    $kritik_durum = ($urun['stok_adedi'] <= $urun['kritik_limit']);
                                ?>
                                <tr class="<?php echo $kritik_durum ? 'table-danger' : ''; ?>">
                                    <td><?php echo $urun['id']; ?></td>
                                    <td><strong><?php echo $urun['urun_adi']; ?></strong></td>
                                    <td><?php echo $urun['stok_adedi']; ?> Adet</td>
                                    <td><?php echo $urun['kritik_limit']; ?> Adet</td>
                                    <td><?php echo number_format($urun['fiyat'], 2, ',', '.'); ?> ₺</td>
                                    <td>
                                        <?php if($kritik_durum): ?>
                                            <span class="badge bg-danger animate-pulse">⚠️ KRİTİK STOK! ÜRÜN ALINMALI</span>
                                        <?php else: ?>
                                            <span class="badge bg-success">✅ Stok Yeterli</span>
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