<?php
// Oturumu (Session) başlatıyoruz, giriş yapan kullanıcıyı hafızada tutmak için gerekli
session_start();
// Az önce yaptığımız veritabanı bağlantı dosyasını buraya çağırıyoruz
require_once 'baglanti.php';

$hata_mesaji = "";

// Eğer giriş butonuna basıldıysa (POST edildiyse)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $eposta = trim($_POST['eposta']);
    $sifre = trim($_POST['sifre']);

    if (!empty($eposta) && !empty($sifre)) {
        // Veritabanından bu e-posta adresine sahip personeli buluyoruz
        $sorgu = $db->prepare("SELECT * FROM personeller WHERE eposta = ?");
        $sorgu->execute([$eposta]);
        $kullanici = $sorgu->fetch(PDO::FETCH_ASSOC);

        // Kullanıcı bulunduysa ve şifresi eşleşiyorsa (Şimdilik düz metin kontrolü yapıyoruz basit olsun diye)
        if ($kullanici && $kullanici['sifre'] == $sifre) {
            // Oturum bilgilerini dolduruyoruz (C#'taki global değişkenler gibi)
            $_SESSION['kullanici_id'] = $kullanici['id'];
            $_SESSION['kullanici_ad'] = $kullanici['ad_soyad'];
            $_SESSION['kullanici_rol'] = $kullanici['rol'];

            // Giriş başarılı! Şimdi Admin/Personel paneline yönlendiriyoruz (Henüz bu sayfayı açmadık ama hazırlayalım)
            header("Location: panel.php");
            exit;
        } else {
            $hata_mesaji = "E-posta veya şifre hatalı!";
        }
    } else {
        $hata_mesaji = "Lütfen tüm alanları doldurun!";
    }
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Şirket Yönetim Sistemi - Giriş</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f4f6f9; height: 100vh; display: flex; align-items: center; justify-content: center; }
        .login-card { width: 100%; max-width: 400px; padding: 20px; border-radius: 10px; box-shadow: 0px 4px 10px rgba(0,0,0,0.1); background: white; }
    </style>
</head>
<body>

<div class="login-card">
    <h3 class="text-center mb-4">Giriş Yap</h3>

    <?php if(!empty($hata_mesaji)): ?>
        <div class="alert alert-danger"><?php echo $hata_mesaji; ?></div>
    <?php endif; ?>

    <form action="index.php" method="POST">
        <div class="mb-3">
            <label class="form-label">E-posta Adresi</label>
            <input type="email" name="eposta" class="form-control" placeholder="ornek@sirket.com" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Şifre</label>
            <input type="password" name="sifre" class="form-control" placeholder="******" required>
        </div>
        <button type="submit" class="btn btn-primary w-100">Sisteme Giriş Yap</button>
    </form>
</div>

</body>
</html>