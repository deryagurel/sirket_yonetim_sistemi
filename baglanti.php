<?php
// Hataları ekranda görmek için (Geliştirme aşamasında çok işimize yarar)
error_reporting(E_ALL);
ini_set('display_errors', 1);

$host = "localhost";
$kullanici = "root";
$sifre = "";
$veritabanı = "sirket_yonetim_sistemi";

try {
    // PDO ile veritabanı bağlantısı (C#'taki SqlConnection gibi düşünebilirsin)
    $db = new PDO("mysql:host=$host;dbname=$veritabanı;charset=utf8", $kullanici, $sifre);
    
    // Hata modunu aktifleştirelim ki bir hata olursa PHP bize söylesin
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
} catch (PDOException $e) {
    echo "Veritabanı bağlantı hatası: " . $e->getMessage();
    die();
}
?>