<?php
// PDO bağlantınızı buraya ekleyin
include('../conn.php'); // PDO bağlantı dosyanız

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Verileri alın
    $baslik = isset($_POST['baslik']) ? $_POST['baslik'] : '';
    $icerik = isset($_POST['icerik']) ? $_POST['icerik'] : '';

    // Boş alanları kontrol et
    if (!empty($baslik) && !empty($icerik)) {
        try {
            // Güncel tarih ve saati al
            $tarih = date('Y-m-d H:i:s'); // YYYY-MM-DD HH:MM:SS formatında tarih

            // Veritabanına ekle
            $sql = "INSERT INTO haberler (baslik, icerik, tarih) VALUES (:baslik, :icerik, :tarih)";
            $stmt = $con->prepare($sql);
            $stmt->bindParam(':baslik', $baslik);
            $stmt->bindParam(':icerik', $icerik);
            $stmt->bindParam(':tarih', $tarih);

            if ($stmt->execute()) {
                echo "Haber başarıyla kaydedildi.";
            } else {
                echo "Haber kaydedilirken bir hata oluştu.";
            }
        } catch (PDOException $e) {
            echo "Veritabanı hatası: " . $e->getMessage();
        }
    } else {
        echo "Lütfen tüm alanları doldurun.";
    }
}
?>