# CoreOffice - Şirket Yönetim Otomasyonu 📊

Bu proje, 2 yıllık **Bilgisayar Programcılığı** programı staj bitirme projesi olarak; backend süreçleri, ilişkisel veritabanı mimarisi ve yetkilendirme algoritmalarını uygulamalı olarak göstermek amacıyla geliştirilmiş hafif ve dinamik bir web otomasyonudur.

## 🚀 Özellikler & Modüller

- **Güvenli Giriş & Oturum Yönetimi (`Session`):** Yetkisiz kullanıcıların panel linklerine doğrudan erişimi engellenmiştir.
- **Rol Tabanlı Yetkilendirme (RBAC):** Sisteme giriş yapan kullanıcının `admin` veya `personel` olma durumuna göre sayfadaki kritik işlemler (düzenleme, silme, ekleme) dinamik olarak sınırlandırılır.
- **Personel Yönetimi & İlişkisel Listeleme:** Personeller veritabanından çekilirken `LEFT JOIN` kullanılarak bağlı oldukları departman isimleriyle birlikte dinamik bir tabloda listelenir.
- **Kritik Stok Takip Algoritması:** Ürünlerin anlık stok miktarı, veritabanında belirlenen kritik limitin altına düştüğü an sistem satırı otomatik olarak `table-danger` sınıfıyla kırmızıya boyar ve görsel uyarı tetikler.
- **Randevu Sistemi & Çakışma Önleyici Filtre:** Yeni randevu eklenirken seçilen tarih ve saatte başka bir randevu olup olmadığı SQL üzerinde `rowCount()` ile taranır. Eğer çakışma varsa kayıt engellenir ve kullanıcı uyarılır.

## 🛠️ Kullanılan Teknolojiler

- **Backend:** PHP (PDO Mimari)
- **Database:** MySQL
- **Frontend:** HTML5, CSS3, Bootstrap 5

## 💻 Kurulum

1. WampServer veya XAMPP sunucunuzun `www` veya `htdocs` klasörünün içerisine projeyi kopyalayın.
2. `localhost/phpmyadmin` üzerinden `sirket_yonetim_sistemi` adında bir veritabanı oluşturun.
3. Proje klasöründeki veya SQL sekmesindeki tabloları içe aktarın.
4. Tarayıcıdan `localhost/sirket_yonetim_sistemi/index.php` adresine giderek sistemi çalıştırın.

**Test Hesapları:**
- **Admin Girişi:** admin@sirket.com / Şifre: 123456
- **Personel Girişi:** ahmet@sirket.com / Şifre: 123456
### 🎥 Proje Videosu
[Projenin arayüz ve çalışma testini izlemek için buraya tıklayın](https://youtu.be/-VUTXOU8zjo?si=1wh8dATnXGoa0vcg)






