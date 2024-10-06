<?php
include('../conn.php');
// Hata ayarları
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        // Film ID'sini al
        $film_id = $_POST['film_id'];

        // Form verilerini alıyoruz  
        $filmadi = !empty($_POST['filmadedit']) ? $_POST['filmadedit'] : null;
        $filmkonu = !empty($_POST['filmkonu']) ? $_POST['filmkonu'] : null;
        $vizyonTarihi = !empty($_POST['vizyontaredit']) ? $_POST['vizyontaredit'] : null;
        $bitistar = !empty($_POST['bitistaredit']) ? $_POST['bitistaredit'] : null;
        $filsure = !empty($_POST['filmsureedit']) ? $_POST['filmsureedit'] : null;
        // Array olan veriler
        $dagitimListesi = isset($_POST['dagitimListesiedit']) && is_array($_POST['dagitimListesiedit']) ? $_POST['dagitimListesiedit'] : [];
        $studyoListesi = isset($_POST['studyoListesiedit']) && is_array($_POST['studyoListesiedit']) ? $_POST['studyoListesiedit'] : [];
        $ulkeListesi = isset($_POST['ulkeListesiedit']) && is_array($_POST['ulkeListesiedit']) ? $_POST['ulkeListesiedit'] : [];
        $filmturuListesi = isset($_POST['filmturuListesiedit']) && is_array($_POST['filmturuListesiedit']) ? $_POST['filmturuListesiedit'] : [];
        $yonetmenListesi = isset($_POST['yonetmenListesiedit']) && is_array($_POST['yonetmenListesiedit']) ? $_POST['yonetmenListesiedit'] : [];
        $senaryoListesi = isset($_POST['senaryoListesiedit']) && is_array($_POST['senaryoListesiedit']) ? $_POST['senaryoListesiedit'] : [];
        $gyonetmeniListesi = isset($_POST['goryonetmenListesiedit']) && is_array($_POST['goryonetmenListesiedit']) ? $_POST['goryonetmenListesiedit'] : [];
        $kurguListesi = isset($_POST['kurguListesiedit']) && is_array($_POST['kurguListesiedit']) ? $_POST['kurguListesiedit'] : [];
        $müzikListesi = isset($_POST['muzikListesiedit']) && is_array($_POST['muzikListesiedit']) ? $_POST['muzikListesiedit'] : [];
        $oyuncuListesi = isset($_POST['oyuncuListesiedit']) && is_array($_POST['oyuncuListesiedit']) ? $_POST['oyuncuListesiedit'] : [];
        $topHasilat = !empty($_POST['topHasilatedit']) ? $_POST['topHasilatedit'] : null;
        $topSeyirci = !empty($_POST['topSeyirciedit']) ? $_POST['topSeyirciedit'] : null;
        $seourl=seoUrl($filmadi);
echo $topHasilat;
        $kategoriIdMap = [
            'yonetmen' => 34,
            'senaryo' => 38,
            'gyonetmen' => 35,
            'kurgu' => 37,
            'müzik' => 36,
            'oyuncu' => 29,
            'yapimci' => 39,
        ];

        // Fotoğraflar için dizinler
        $kapakFotoDizin = "../../kapakfoto/";
        $galeriDizin = "../../galeri/";

        // Mevcut film bilgilerini güncelle
        $stmt = $con->prepare("UPDATE filmler SET film_adi = ?, vizyon_tarihi = ?,bitis_tarihi = ?, film_konu = ?, filmsure = ?, seo_url = ?, topHasilat = ?, topKisi = ? WHERE id = ?");
        $stmt->execute([$filmadi, $vizyonTarihi,$bitistar, $filmkonu, $filsure,  $seourl, $topHasilat, $topSeyirci, $film_id]);

        // Kapak fotoğrafını güncelleme
        if (!empty($_FILES['filmkapakedit']['name'][0])) {
            // Eski kapak fotoğrafını sil
            $stmt = $con->prepare("SELECT kapak_resmi FROM filmler WHERE id = ?");
            $stmt->execute([$film_id]);
            $eskiKapak = $stmt->fetchColumn();
            if ($eskiKapak && file_exists($kapakFotoDizin . $eskiKapak)) {
                unlink($kapakFotoDizin . $eskiKapak);
            }

            // Yeni kapak fotoğrafını yükle
            $orijinalAd = basename($_FILES['filmkapakedit']['name'][0]);
            $dosyaUzantisi = pathinfo($orijinalAd, PATHINFO_EXTENSION);
            $benzersizAd = time() . '.' . $dosyaUzantisi;
            $kapakFotoYolu = $kapakFotoDizin . $benzersizAd;

            if (move_uploaded_file($_FILES['filmkapakedit']['tmp_name'][0], $kapakFotoYolu)) {
                $stmt = $con->prepare("UPDATE filmler SET kapak_resmi = ? WHERE id = ?");
                $stmt->execute([$benzersizAd, $film_id]);
            } else {
                echo "Kapak fotoğrafı yüklenirken bir hata oluştu.";
            }
        }

        // Galeri fotoğraflarını güncelleme
        if (!empty($_FILES['filmgaleriedit']['name'][0])) {
            // Eski galeri fotoğraflarını sil
            $stmt = $con->prepare("SELECT resim_yolu FROM film_galeri WHERE film_id = ?");
            $stmt->execute([$film_id]);
            $eskiGaleriler = $stmt->fetchAll(PDO::FETCH_COLUMN);
            foreach ($eskiGaleriler as $eskiGaleri) {
                if (file_exists($galeriDizin . $eskiGaleri)) {
                    unlink($galeriDizin . $eskiGaleri);
                }
            }

            // Eski galeri fotoğraflarını veritabanından sil
            $stmt = $con->prepare("DELETE FROM film_galeri WHERE film_id = ?");
            $stmt->execute([$film_id]);
            $r=0;
            // Yeni galeri fotoğraflarını yükle
            foreach ($_FILES['filmgaleriedit']['name'] as $key => $galeriFotoAdi) {
                $dosyaUzantisi = pathinfo($galeriFotoAdi, PATHINFO_EXTENSION);
                $yeniFotoAdi = time() .$r. '.' . $dosyaUzantisi;
                $galeriFotoYolu = $galeriDizin . $yeniFotoAdi;

                if (move_uploaded_file($_FILES['filmgaleriedit']['tmp_name'][$key], $galeriFotoYolu)) {
                    $stmt = $con->prepare("INSERT INTO film_galeri (film_id, resim_yolu) VALUES (?, ?)");
                    $stmt->execute([$film_id, $yeniFotoAdi]);
                } else {
                    echo "Galeri fotoğrafı yüklenirken bir hata oluştu: " . $yeniFotoAdi;
                }
                $r++;
            }
        }

        // Dağıtım listesini güncelleme
        $stmt = $con->prepare("DELETE FROM film_dagitim WHERE film_id = ?");
        $stmt->execute([$film_id]);
        foreach ($dagitimListesi as $id) {
            $stmt = $con->prepare("INSERT INTO film_dagitim (film_id, dagitim_id) VALUES (?, ?)");
            $stmt->execute([$film_id, $id]);
        }

        // Stüdyo listesini güncelleme
        $stmt = $con->prepare("DELETE FROM film_studyolar WHERE film_id = ?");
        $stmt->execute([$film_id]);
        foreach ($studyoListesi as $id) {
            $stmt = $con->prepare("INSERT INTO film_studyolar (film_id, studyo_id) VALUES (?, ?)");
            $stmt->execute([$film_id, $id]);
        }

        // Ülke listesini güncelleme
        $stmt = $con->prepare("DELETE FROM film_ulkeler WHERE film_id = ?");
        $stmt->execute([$film_id]);
        foreach ($ulkeListesi as $id) {
            $stmt = $con->prepare("INSERT INTO film_ulkeler (film_id, ulke_id) VALUES (?, ?)");
            $stmt->execute([$film_id, $id]);
        }

        // Film türü listesini güncelleme
        $stmt = $con->prepare("DELETE FROM film_filmturu WHERE film_id = ?");
        $stmt->execute([$film_id]);
        foreach ($filmturuListesi as $id) {
            $stmt = $con->prepare("INSERT INTO film_filmturu (film_id, filmturu_id) VALUES (?, ?)");
            $stmt->execute([$film_id, $id]);
        }

       
        









// Yönetmen listesini güncelleme
if (!empty($yonetmenListesi)) {
    // Eski yönetmen kayıtlarını sil
    $stmt = $con->prepare("DELETE FROM oyuncuiliski WHERE film_id = ? AND kategori_id = ?");
    $stmt->execute([$film_id, $kategoriIdMap['yonetmen']]);

    // Yeni yönetmen kayıtlarını ekle
    foreach ($yonetmenListesi as $oyuncu_id) {
        $stmt = $con->prepare("INSERT INTO oyuncuiliski (film_id, oyuncu_id, kategori_id) VALUES (?, ?, ?)");
        $stmt->execute([$film_id, $oyuncu_id, $kategoriIdMap['yonetmen']]);
    }
}

// Senaryo listesini güncelleme
if (!empty($senaryoListesi)) {
    // Eski senaryo kayıtlarını sil
    $stmt = $con->prepare("DELETE FROM oyuncuiliski WHERE film_id = ? AND kategori_id = ?");
    $stmt->execute([$film_id, $kategoriIdMap['senaryo']]);

    // Yeni senaryo kayıtlarını ekle
    foreach ($senaryoListesi as $oyuncu_id) {
        $stmt = $con->prepare("INSERT INTO oyuncuiliski (film_id, oyuncu_id, kategori_id) VALUES (?, ?, ?)");
        $stmt->execute([$film_id, $oyuncu_id, $kategoriIdMap['senaryo']]);
    }
}

// Görüntü yönetmeni listesini güncelleme
if (!empty($gyonetmeniListesi)) {
    // Eski görüntü yönetmeni kayıtlarını sil
    $stmt = $con->prepare("DELETE FROM oyuncuiliski WHERE film_id = ? AND kategori_id = ?");
    $stmt->execute([$film_id, $kategoriIdMap['gyonetmen']]);

    // Yeni görüntü yönetmeni kayıtlarını ekle
    foreach ($gyonetmeniListesi as $oyuncu_id) {
        $stmt = $con->prepare("INSERT INTO oyuncuiliski (film_id, oyuncu_id, kategori_id) VALUES (?, ?, ?)");
        $stmt->execute([$film_id, $oyuncu_id, $kategoriIdMap['gyonetmen']]);
    }
}

// Kurgu listesini güncelleme
if (!empty($kurguListesi)) {
    // Eski kurgu kayıtlarını sil
    $stmt = $con->prepare("DELETE FROM oyuncuiliski WHERE film_id = ? AND kategori_id = ?");
    $stmt->execute([$film_id, $kategoriIdMap['kurgu']]);

    // Yeni kurgu kayıtlarını ekle
    foreach ($kurguListesi as $oyuncu_id) {
        $stmt = $con->prepare("INSERT INTO oyuncuiliski (film_id, oyuncu_id, kategori_id) VALUES (?, ?, ?)");
        $stmt->execute([$film_id, $oyuncu_id, $kategoriIdMap['kurgu']]);
    }
}

// Müzik listesini güncelleme
if (!empty($müzikListesi)) {
    // Eski müzik kayıtlarını sil
    $stmt = $con->prepare("DELETE FROM oyuncuiliski WHERE film_id = ? AND kategori_id = ?");
    $stmt->execute([$film_id, $kategoriIdMap['müzik']]);

    // Yeni müzik kayıtlarını ekle
    foreach ($müzikListesi as $oyuncu_id) {
        $stmt = $con->prepare("INSERT INTO oyuncuiliski (film_id, oyuncu_id, kategori_id) VALUES (?, ?, ?)");
        $stmt->execute([$film_id, $oyuncu_id, $kategoriIdMap['müzik']]);
    }
}

        // Oyuncu listesini güncelleme
        if (!empty($oyuncuListesi)) {
            // Eski oyuncu kayıtlarını sil
            $stmt = $con->prepare("DELETE FROM oyuncuiliski WHERE film_id = ? AND kategori_id = ?");
            $stmt->execute([$film_id, $kategoriIdMap['oyuncu']]);
        
            
                foreach ($oyuncuListesi as $oyuncu_id) {
                    $stmt = $con->prepare("INSERT INTO oyuncuiliski (film_id, oyuncu_id, kategori_id) VALUES (?, ?, ?)");
                    $stmt->execute([$film_id, $oyuncu_id, $kategoriIdMap['oyuncu']]);
                }
            
        }






        echo "Film başarıyla güncellendi!";
    } catch (PDOException $e) {
        echo "Veritabanı hatası: " . $e->getMessage();
    }
}



function seoUrl($haberAdi) {
    // Türkçe karakterleri İngilizce karakterlere çevir
    $turkce = array('Ç', 'Ş', 'Ğ', 'Ü', 'İ', 'Ö', 'ç', 'ş', 'ğ', 'ü', 'ı', 'ö');
    $ingilizce = array('C', 'S', 'G', 'U', 'I', 'O', 'c', 's', 'g', 'u', 'i', 'o');
    $seoAdi = str_replace($turkce, $ingilizce, $haberAdi);

    // Küçük harfe dönüştür
    $seoAdi = strtolower($seoAdi);

    // Harf ve sayılar dışındaki karakterleri kaldır
    $seoAdi = preg_replace('/[^a-z0-9\s-]/', '', $seoAdi);

    // Boşlukları ve birden fazla boşluğu tek tire ile değiştir
    $seoAdi = preg_replace('/\s+/', '-', $seoAdi);

    // Baş ve sondaki tireleri temizle
    $seoAdi = trim($seoAdi, '-');

    return $seoAdi;
}
?>
