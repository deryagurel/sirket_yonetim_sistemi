<?php
session_start();
require_once 'baglanti.php';

// Güvenlik kontrolü
if (!isset($_SESSION['kullanici_id'])) {
    header("Location: index.php");
    exit;
}

$mesaj = "";

// Yeni Randevu Oluşturma Formu Tetiklendiyse (POST)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $musteri_adi = trim($_POST['musteri_adi']);
    $randevu_tarihi = $_POST['randevu_tarihi'];
    $randevu_saati = $_POST['randevu_saati'];

    if (!empty($musteri_adi) && !empty($randevu_tarihi) && !empty($randevu_saati)) {
        
        // 🌟 ÇAKIŞMA KONTROLÜ ALGORİTMASI 🌟
        // Aynı tarih ve saatte başka bir randevu var mı kontrol ediyoruz
        $kontrol = $db->prepare("SELECT id FROM randevular WHERE randevu_tarihi = ? AND randevu_saati = ?");
        $kontrol->execute([$randevu_tarihi, $randevu_saati]);
        
        if ($kontrol->rowCount() > 0) {
            $mesaj = "<div class='alert alert-danger'>⚠️ Hata: Seçilen tarih ve saatte başka bir randevu zaten mevcut! Çakışma engellendi.</div>";
        } else {
            // Çakışma yoksa randevuyu kaydet
            $ekle = $db->prepare("INSERT INTO randevular (musteri_adi, randevu_tarihi, randevu_saati) VALUES (?, ?, ?)");
            $ekle->execute([$musteri_adi, $randevu_tarihi, $randevu_saati]);
            $mesaj = "<div class='alert alert-success'>✅ Randevu başarıyla oluşturuldu!</div>";
        }
    }
}

// Mevcut randevuları listelemek için veritabanından çekiyoruz
// (Randevular tablosunu henüz SQL'e eklemedik, bir sonraki mesajda hemen ekleyeceğiz)
try {
    $sorgu = $db->query("SELECT * FROM randevular ORDER BY randevu_tarihi ASC, randevu_saati ASC");
    $randevular = $sorgu->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    $randevular = []; // Tablo henüz yoksa hata patlamasın diye
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>CoreOffice - Randevu Sistemi</title>
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
            <h4 class="text-center mb-4">CoreOffice</h4>
            <p class="text-center text-muted small">Hoş geldin,<br><strong><?php echo $_SESSION['kullanici_ad']; ?></strong></p>
            <hr>
            <a href="panel.php">🏠 Ana Sayfa</a>
            <a href="personel_listele.php">👥 Personel Yönetimi</a>
            <a href="stok_listele.php">📦 Stok Takibi</a>
            <a href="randevu_listele.php" class="active bg-secondary text-white">📅 Randevu Sistemi</a>
            <hr>
            <a href="panel.php?cikis=1" class="text-danger">❌ Güvenli Çıkış</a>
        </div>

        <div class="col-md-10 p-4">
            <h2>📅 Randevu Yönetim Modülü</h2>
            <p class="text-muted">Müşteri randevuları ve otomatik saat çakışma kontrol sistemi.</p>
            <hr>

            <?php echo $mesaj; ?>

            <div class="row">
                <div class="col-md-4">
                    <div class="card shadow-sm mb-4">
                        <div class="card-header bg-primary text-white">➕ Yeni Randevu Kaydı</div>
                        <div class="card-body">
                            <form action="randevu_listele.php" method="POST">
                                <div class="mb-3">
                                    <label class="form-label">Müşteri Adı Soyadı</label>
                                    <input type="text" name="musteri_adi" class="form-control" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Randevu Tarihi</label>
                                    <input type="date" name="randevu_tarihi" class="form-control" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Randevu Saati</label>
                                    <select name="randevu_saati" class="form-select" required>
                                        <option value="09:00">09:00</option>
                                        <option value="10:00">10:00</option>
                                        <option value="11:00">11:00</option>
                                        <option value="13:00">13:00</option>
                                        <option value="14:00">14:00</option>
                                        <option value="15:00">15:00</option>
                                        <option value="16:00">16:00</option>
                                    </select>
                                </div>
                                <button type="submit" class="btn btn-primary w-100">Randevu Oluştur</button>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="col-md-8">
                    <div class="card shadow-sm">
                        <div class="card-header bg-dark text-white">📋 Planlanan Randevu Listesi</div>
                        <div class="card-body p-0">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Müşteri</th>
                                        <th>Tarih</th>
                                        <th>Saat</th>
                                        <th>Durum</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if(empty($randevular)): ?>
                                        <tr><td colspan="4" class="text-center text-muted p-3">Henüz kaydedilmiş randevu yok veya veritabanı tablosu oluşturulmadı.</td></tr>
                                    <?php else: ?>
                                        <?php foreach($randevular as $randevu): ?>
                                            <tr>
                                                <td><strong><?php echo $randevu['musteri_adi']; ?></strong></td>
                                                <td><?php echo date('d.m.Y', strtotime($randevu['randevu_tarihi'])); ?></td>
                                                <td><span class="badge bg-info text-dark"><?php echo $randevu['randevu_saati']; ?></span></td>
                                                <td><span class="badge bg-success">Onaylandı</span></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

</body>
</html>