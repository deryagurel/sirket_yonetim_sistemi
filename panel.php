<?php
// Oturumu başlatıyoruz ki giriş yapan kişiyi tanıyalım
session_start();

// GÜVENLİK KONTROLÜ: Eğer giriş yapılmamışsa (hafızada kullanıcı ID'si yoksa)
if (!isset($_SESSION['kullanici_id'])) {
    // Kullanıcıyı hemen giriş sayfasına geri gönder ve kodları durdur
    header("Location: index.php");
    exit;
}

// Çıkış yap butonuna basıldıysa oturumu kapat
if (isset($_GET['cikis'])) {
    session_destroy();
    header("Location: index.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Şirket Yönetim Paneli</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; }
        .sidebar { height: 100vh; background-color: #212529; color: white; padding-top: 20px; }
        .sidebar a { color: #rgba(255,255,255,0.75); text-decoration: none; padding: 10px 20px; display: block; }
        .sidebar a:hover { background-color: #343a40; color: white; }
        .card-box { padding: 20px; border-radius: 10px; color: white; margin-bottom: 20px; }
    </style>
</head>
<body>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-2 sidebar px-0">
            <h4 class="text-center mb-4">Şirket Paneli</h4>
            <p class="text-center text-muted small">Hoş geldin,<br><strong><?php echo $_SESSION['kullanici_ad']; ?></strong></p>
            <hr>
            <a href="panel.php" class="active bg-secondary text-white">🏠 Ana Sayfa</a>
            <a href="#">👥 Personel Yönetimi</a>
            <a href="stok_listele.php">📦 Stok Takibi</a>
            <a href="#">📅 Randevu Sistemi</a>
            <hr>
            <a href="panel.php?cikis=1" class="text-danger">❌ Güvenli Çıkış</a>
        </div>

        <div class="col-md-10 p-4">
            <h2>Yönetim Paneli Özet Verileri</h2>
            <p class="text-muted">Sistemdeki genel durum aşağıda listelenmiştir.</p>
            <hr>

            <div class="row mt-4">
                <div class="col-md-4">
                    <div class="card-box bg-primary">
                        <h5>Toplam Personel</h5>
                        <h3>2</h3>
                        <p class="mb-0">Aktif çalışan sayısı</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card-box bg-success">
                        <h5>Toplam Ürün Çeşidi</h5>
                        <h3>3</h3>
                        <p class="mb-0">Stoktaki farklı ürünler</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card-box bg-warning text-dark">
                        <h5>Kritik Stok Uyarısı</h5>
                        <h3>1</h3>
                        <p class="mb-0">Sınırın altına düşen ürünler</p>
                    </div>
                </div>
            </div>

            <div class="card mt-4">
                <div class="card-header bg-dark text-white">Sistem Bilgisi (Rol Kontrolü)</div>
                <div class="card-body">
                    <p>Şu anki yetki rolünüz: <strong><span class="badge bg-info text-dark"><?php echo $_SESSION['kullanici_rol']; ?></span></strong></p>
                    
                    <?php if ($_SESSION['kullanici_rol'] == 'admin'): ?>
                        <div class="alert alert-success">
                            🌟 <strong>Admin Yetkisi:</strong> Bu mesajı sadece rolü 'admin' olanlar görebilir. Personel düzenleme yetkiniz var.
                        </div>
                    <?php else: ?>
                        <div class="alert alert-secondary">
                            🔒 <strong>Personel Yetkisi:</strong> Bazı kritik alanları (silme/düzenleme) görme yetkiniz kısıtlıdır.
                        </div>
                    <?php endif; ?>
                </div>
            </div>

        </div>
    </div>
</div>

</body>
</html>