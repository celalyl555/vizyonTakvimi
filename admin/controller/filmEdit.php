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

// Array olan veriler
$dagitimListesi = isset($_POST['dagitimListesiedit']) && is_array($_POST['dagitimListesiedit']) ? $_POST['dagitimListesiedit'] : null;
$studyoListesi = isset($_POST['studyoListesiedit']) && is_array($_POST['studyoListesiedit']) ? $_POST['studyoListesiedit'] : null;
$ulkeListesi = isset($_POST['ulkeListesiedit']) && is_array($_POST['ulkeListesiedit']) ? $_POST['ulkeListesiedit'] : null;
$filmturuListesi = isset($_POST['filmturuListesiedit']) && is_array($_POST['filmturuListesiedit']) ? $_POST['filmturuListesiedit'] : null;

$yonetmenListesi = isset($_POST['yonetmenListesiedit']) && is_array($_POST['yonetmenListesiedit']) ? $_POST['yonetmenListesiedit'] : null;
$senaryoListesi = isset($_POST['senaryoListesiedit']) && is_array($_POST['senaryoListesiedit']) ? $_POST['senaryoListesiedit'] : null;
$gyonetmeniListesi = isset($_POST['goryonetmenListesiedit']) && is_array($_POST['goryonetmenListesiedit']) ? $_POST['goryonetmenListesiedit'] : null;
$kurguListesi = isset($_POST['kurguListesiedit']) && is_array($_POST['kurguListesiedit']) ? $_POST['kurguListesiedit'] : null;
$müzikListesi = isset($_POST['muzikListesiedit']) && is_array($_POST['muzikListesiedit']) ? $_POST['muzikListesiedit'] : null;
$oyuncuListesi = isset($_POST['oyuncuListesiedit']) && is_array($_POST['oyuncuListesiedit']) ? $_POST['oyuncuListesiedit'] : null;


        $kategoriIdMap = [
            'yonetmen' => 34,
            'senaryo' => 38,
            'gyonetmen' => 35,
            'kurgu' => 37,
            'müzik' => 36,
            'oyuncu' => 29,
        ];

        // Fotoğraflar için dizinler
        $kapakFotoDizin = "../../kapakfoto/";
        $galeriDizin = "../../galeri/";

        // Mevcut film bilgilerini güncelle
        $stmt = $con->prepare("UPDATE filmler SET film_adi = ?, vizyon_tarihi = ?, film_konu = ? WHERE id = ?");
        $stmt->execute([$filmadi, $vizyonTarihi, $filmkonu, $film_id]);

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

            // Yeni galeri fotoğraflarını yükle
            foreach ($_FILES['filmgaleriedit']['name'] as $key => $galeriFotoAdi) {
                $dosyaUzantisi = pathinfo($galeriFotoAdi, PATHINFO_EXTENSION);
                $yeniFotoAdi = time() . $key . '_' . preg_replace('/[^a-zA-Z0-9_]/') . '.' . $dosyaUzantisi;
                $galeriFotoYolu = $galeriDizin . $yeniFotoAdi;

                if (move_uploaded_file($_FILES['filmgaleriedit']['tmp_name'][$key], $galeriFotoYolu)) {
                    $stmt = $con->prepare("INSERT INTO film_galeri (film_id, resim_yolu) VALUES (?, ?)");
                    $stmt->execute([$film_id, $yeniFotoAdi]);
                } else {
                    echo "Galeri fotoğrafı yüklenirken bir hata oluştu: " . $yeniFotoAdi;
                }
            }
        }

        // Diğer ilişkileri güncelleme
        function updateRelation($con, $table, $column, $film_id, $list) {
            foreach ($list as $id) {
                $stmt = $con->prepare("UPDATE $table SET $column = ? WHERE film_id = ?");
                $stmt->execute([$id, $film_id]);
            }
        }

        if (!empty($dagitimListesi)) {
            updateRelation($con, 'film_dagitim', 'dagitim_id', $film_id, $dagitimListesi);
        }

        if (!empty($studyoListesi)) {
            updateRelation($con, 'film_studyolar', 'studyo_id', $film_id, $studyoListesi);
        }

        if (!empty($ulkeListesi)) {
            updateRelation($con, 'film_ulkeler', 'ulke_id', $film_id, $ulkeListesi);
        }

        if (!empty($filmturuListesi)) {
            updateRelation($con, 'film_filmturu', 'filmturu_id', $film_id, $filmturuListesi);
        }

        // Oyuncu ilişkileri için
        function updateActorRelation($con, $table, $column, $film_id, $list, $category) {
            foreach ($list as $id) {
                $stmt = $con->prepare("UPDATE $table SET $column = ? WHERE film_id = ? AND kategori_id = ?");
                $stmt->execute([$id, $film_id, $category]);
            }
        }

        if (!empty($yonetmenListesi)) {
            updateActorRelation($con, 'oyuncuiliski', 'oyuncu_id', $film_id, $yonetmenListesi, $kategoriIdMap['yonetmen']);
        }

        if (!empty($senaryoListesi)) {
            updateActorRelation($con, 'oyuncuiliski', 'oyuncu_id', $film_id, $senaryoListesi, $kategoriIdMap['senaryo']);
        }

        if (!empty($gyonetmeniListesi)) {
            updateActorRelation($con, 'oyuncuiliski', 'oyuncu_id', $film_id, $gyonetmeniListesi, $kategoriIdMap['gyonetmen']);
        }

        if (!empty($kurguListesi)) {
            updateActorRelation($con, 'oyuncuiliski', 'oyuncu_id', $film_id, $kurguListesi, $kategoriIdMap['kurgu']);
        }

        if (!empty($müzikListesi)) {
            updateActorRelation($con, 'oyuncuiliski', 'oyuncu_id', $film_id, $müzikListesi, $kategoriIdMap['müzik']);
        }

        if (!empty($oyuncuListesi)) {
            updateActorRelation($con, 'oyuncuiliski', 'oyuncu_id', $film_id, $oyuncuListesi, $kategoriIdMap['oyuncu']);
        }

        echo "Film Güncellendi";
    } catch (Exception $e) {
        echo $e->getMessage();
    }
}
?>
``
