<?php
session_start();
require_once 'baglanti.php';

// Güvenlik: Sadece admin olanlar personel ekleyebilsin!
if (!isset($_SESSION['kullanici_id']) || $_SESSION['kullanici_rol'] != 'admin') {
    header("Location: panel.php");
    exit;
}

$mesaj = "";

// Form gönderildiyse (POST)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $ad_soyad = trim($_POST['ad_soyad']);
    $eposta = trim($_POST['eposta']);
    $sifre = trim($_POST['sifre']);
    $telefon = trim($_POST['telefon']);
    $maas = trim($_POST['maas']);
    $departman_id = $_POST['departman_id'];
    $rol = $_POST['rol'];

    if (!empty($ad_soyad) && !empty($eposta) && !empty($sifre)) {
        // C#'taki komut parametreleri (Parameters.AddWithValue) mantığıyla güvenli ekleme yapıyoruz
        $sorgu = $db->prepare("INSERT INTO personeller (ad_soyad, eposta, sifre, telefon, maas, rol, departman_id) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $ekle = $sorgu->execute([$ad_soyad, $eposta, $sifre, $telefon, $maas, $rol, $departman_id]);

        if ($ekle) {
            $mesaj = "<div class='alert alert-success'>Personel başarıyla eklendi! <a href='personel_listele.php'>Listeye Dön</a></div>";
        } else {
            $mesaj = "<div class='alert alert-danger'>Bir hata oluştu.</div>";
        }
    }
}

// Departmanları dinamik olarak select kutusuna çekmek için veritabanından alıyoruz
$dept_sorgu = $db->query("SELECT * FROM departmanlar");
$departmanlar = $dept_sorgu->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Yeni Personel Ekle</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light p-4">

<div class="container" style="max-width: 600px;">
    <div class="card shadow-sm">
        <div class="card-header bg-success text-white">
            <h4 class="mb-0">➕ Yeni Personel Kaydı</h4>
        </div>
        <div class="card-body">
            <?php echo $mesaj; ?>
            <form action="personel_ekle.php" method="POST">
                <div class="mb-3">
                    <label class="form-label">Ad Soyad</label>
                    <input type="text" name="ad_soyad" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">E-posta (Giriş için kullanıcı adı olacak)</label>
                    <input type="email" name="eposta" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Giriş Şifresi</label>
                    <input type="password" name="sifre" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Telefon</label>
                    <input type="text" name="telefon" class="form-control">
                </div>
                <div class="mb-3">
                    <label class="form-label">Maaş</label>
                    <input type="number" step="0.01" name="maas" class="form-control" value="17002.00">
                </div>
                <div class="mb-3">
                    <label class="form-label">Departman</label>
                    <select name="departman_id" class="form-select">
                        <?php foreach($departmanlar as $dept): ?>
                            <option value="<?php echo $dept['id']; ?>"><?php echo $dept['departman_adi']; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Sistem Yetki Rolü</label>
                    <select name="rol" class="form-select">
                        <option value="personel">Personel (Kısıtlı Yetki)</option>
                        <option value="admin">Admin (Tam Yetki)</option>
                    </select>
                </div>
                <div class="d-flex justify-content-between">
                    <a href="personel_listele.php" class="btn btn-secondary">İptal Et</a>
                    <button type="submit" class="btn btn-success">Personeli Kaydet</button>
                </div>
            </form>
        </div>
    </div>
</div>

</body>
</html>