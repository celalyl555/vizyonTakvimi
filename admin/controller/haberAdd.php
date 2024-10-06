<?php
include('../conn.php'); // PDO bağlantı dosyanız

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $baslik = isset($_POST['baslik']) ? $_POST['baslik'] : '';
    $seourl=seoUrl($baslik);
    $icerik = isset($_POST['icerik']) ? $_POST['icerik'] : '';   
    $fotograf = isset($_FILES['kapakfoto']) ? $_FILES['kapakfoto'] : '';
    $statu = isset($_POST['statu']) ? $_POST['statu'] : '';

    if (!empty($baslik) && !empty($icerik) && !empty($fotograf)) {
        try {
            $tarih = date('Y-m-d H:i:s');
            $kapakFotoDizin = "../../haberfoto/";
            $fotografAdi = "";

            if (!empty($fotograf['name'])) {
                $fotoSayisi = count($fotograf['name']);
                for ($i = 0; $i < $fotoSayisi; $i++) {
                    $orijinalAd = basename($fotograf['name'][$i]);
                    $dosyaUzantisi = pathinfo($orijinalAd, PATHINFO_EXTENSION);
                    $benzersizAd = time() . '_' . uniqid() . '.' . $dosyaUzantisi;
                    $kapakFotoYolu = $kapakFotoDizin . $benzersizAd;

                    if (move_uploaded_file($fotograf['tmp_name'][$i], $kapakFotoYolu)) {
                        $fotografAdi = $benzersizAd;
                    } else {
                        echo "Fotoğraf yüklenirken hata: " . $fotograf['name'][$i];
                    }
                }
            } else {
                echo "Fotoğraf seçilmedi.";
            }

            $sql = "INSERT INTO haberler (baslik, icerik, tarih, haberfoto,statu,seo_url) VALUES (:baslik, :icerik, :tarih, :haberfoto, :statu,:seo_url)";
            $stmt = $con->prepare($sql);
            $stmt->bindParam(':baslik', $baslik);
            $stmt->bindParam(':icerik', $icerik);
            $stmt->bindParam(':tarih', $tarih);
            $stmt->bindParam(':haberfoto', $fotografAdi);
            $stmt->bindParam(':statu', $statu);
            $stmt->bindParam(':seo_url', $seourl);
            if ($stmt->execute()) {
                echo "Haber başarıyla kaydedildi.";
            } else {
                echo "Haber kaydedilirken bir hata oluştu.";
            }
        } catch (PDOException $e) {
            echo "Veritabanı hatası: " . $e->getMessage();
        }
    } else {
        echo "1";
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
