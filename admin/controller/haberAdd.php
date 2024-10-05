<?php
include('../conn.php'); // PDO bağlantı dosyanız

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $baslik = isset($_POST['baslik']) ? $_POST['baslik'] : '';
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

            $sql = "INSERT INTO haberler (baslik, icerik, tarih, haberfoto,statu) VALUES (:baslik, :icerik, :tarih, :haberfoto, :statu)";
            $stmt = $con->prepare($sql);
            $stmt->bindParam(':baslik', $baslik);
            $stmt->bindParam(':icerik', $icerik);
            $stmt->bindParam(':tarih', $tarih);
            $stmt->bindParam(':haberfoto', $fotografAdi);
            $stmt->bindParam(':statu', $statu);
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
?>
