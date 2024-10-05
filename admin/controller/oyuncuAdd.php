<?php
// Veritabanı bağlantısı
include('../conn.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $adSoyad = $_POST['adSoyad'];
    $dogumTarihi = $_POST['dogumTarihi'];
    $olumTarihi = $_POST['olumTarihi'] ? $_POST['olumTarihi'] : null;
    $kategoriListesi = $_POST['kategori'];

    // Resim yükleme işlemi
    if (isset($_FILES['gorsel'])) {
        $hedefKlasor = '../../foto/';
        $dosyaAdi = basename($_FILES['gorsel']['name']);
        $uzanti = pathinfo($dosyaAdi, PATHINFO_EXTENSION);
    
        // Dosya adını düzenleyin (örnek: kullanıcı adı ve zaman damgası ile)
        $yeniDosyaAdi = preg_replace('/[^a-zA-Z0-9_-]/', '_', $adSoyad) . '_' . time() . '.' . $uzanti; // Örnek: Ahmet_Yilmaz_1635423332.jpg
        $hedefDosya = $hedefKlasor . $yeniDosyaAdi;
        $dosyaTipi = strtolower(pathinfo($hedefDosya, PATHINFO_EXTENSION));

        // Dosya yükleme hatalarını kontrol etmek için
        if ($_FILES['gorsel']['error'] !== UPLOAD_ERR_OK) {
            // Hata kodlarına göre mesajlar
            switch ($_FILES['gorsel']['error']) {
                case UPLOAD_ERR_INI_SIZE:
                    $hataMesaji = "Dosya, php.ini'de tanımlanan maximum boyuttan büyük.";
                    break;
                case UPLOAD_ERR_FORM_SIZE:
                    $hataMesaji = "Dosya, HTML formunda tanımlanan maximum boyuttan büyük.";
                    break;
                case UPLOAD_ERR_PARTIAL:
                    $hataMesaji = "Dosya sadece kısmen yüklendi.";
                    break;
                case UPLOAD_ERR_NO_FILE:
                    $hataMesaji = "Herhangi bir dosya yüklenmedi.";
                    break;
                case UPLOAD_ERR_NO_TMP_DIR:
                    $hataMesaji = "Geçici klasör eksik.";
                    break;
                case UPLOAD_ERR_CANT_WRITE:
                    $hataMesaji = "Dosya diske yazılamadı.";
                    break;
                case UPLOAD_ERR_EXTENSION:
                    $hataMesaji = "Dosya yükleme bir PHP uzantısı tarafından durduruldu.";
                    break;
                default:
                    $hataMesaji = "Bilinmeyen bir hata oluştu.";
                    break;
            }
            echo "Resim yükleme hatası: " . $hataMesaji;
        } else {
            // Dosya formatı kontrolü
            
                // Dosyayı belirtilen klasöre yükleme
                if (move_uploaded_file($_FILES['gorsel']['tmp_name'], $hedefDosya)) {
                    $sql = "INSERT INTO oyuncular (adsoyad, dogum, olum, resimyol) VALUES (:adSoyad, :dogumTarihi, :olumTarihi, :resimYolu)";
                    $stmt = $con->prepare($sql);
                    $stmt->execute([
                        ':adSoyad' => $adSoyad,
                        ':dogumTarihi' => $dogumTarihi,
                        ':olumTarihi' => $olumTarihi,
                        ':resimYolu' => $yeniDosyaAdi
                    ]);

                    $kayitId = $con->lastInsertId();

                    foreach ($kategoriListesi as $kategoriId) {
                        $sqlKategori = "INSERT INTO kayit_kategori (kayit_id, kategori_id) VALUES (:kayit_id, :kategori_id)";
                        $stmtKategori = $con->prepare($sqlKategori);
                        $stmtKategori->execute([
                            ':kayit_id' => $kayitId,
                            ':kategori_id' => $kategoriId
                        ]);
                    }

                    echo "Kayıt başarıyla eklendi.";
                } else {
                    echo "Resim sunucuya yüklenirken bir hata oluştu.";
                }
            
        }
    } else {
        echo "Resim yüklenemedi.";
    }
}
?>
