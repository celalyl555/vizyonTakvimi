<?php  
include('../conn.php');  // Veritabanı bağlantısını içeren dosya

class OyuncuController {
    private $dbConnection;

    // Constructor ile veritabanı bağlantısını al
    public function __construct($con) {
        $this->dbConnection = $con;

        // Bağlantı kontrolü
        if (!$this->dbConnection) {
            throw new Exception("Veritabanı bağlantısı sağlanamadı.");
        }
    }

    public function kategoriEkle($kategoriAdi) {
        try {
            $stmt = $this->dbConnection->prepare("INSERT INTO kategori (kategoriAd) VALUES (:kategoriAd)");

            // Veriyi bağla ve sorguyu çalıştır
            $stmt->bindParam(':kategoriAd', $kategoriAdi, PDO::PARAM_STR);

            if ($stmt->execute()) {
                echo "Kategori başarıyla eklendi: " . htmlspecialchars($kategoriAdi);
            } else {
                echo "Kategori eklenirken hata oluştu.";
            }
        } catch (PDOException $e) {
            echo "Kategori eklenirken hata oluştu: " . $e->getMessage();
        }
    }

    public function kategoriSil($kategoriid) {
        try {
            $stmt = $this->dbConnection->prepare("DELETE FROM kategori WHERE idKategori = :kategoriid");
            $stmt->bindParam(':kategoriid', $kategoriid, PDO::PARAM_INT);

            if ($stmt->execute()) {
                if ($stmt->rowCount() > 0) {
                    echo "Kategori başarıyla silindi.";
                } else {
                    echo "Silinecek kategori bulunamadı.";
                }
            } else {
                echo "Kategori silinirken hata oluştu.";
            }
        } catch (PDOException $e) {
            echo "Kategori silinirken hata oluştu: " . $e->getMessage();
        }
    }

    public function oyuncuSil($oyuncuid) {
        try {
            $stmt = $this->dbConnection->prepare("DELETE FROM oyuncular WHERE idoyuncu = :oyuncuid");
            $stmt->bindParam(':oyuncuid', $oyuncuid, PDO::PARAM_INT);

            if ($stmt->execute()) {
                if ($stmt->rowCount() > 0) {
                    echo "Oyuncu başarıyla silindi.";
                } else {
                    echo "Silinecek Oyuncu bulunamadı.";
                }
            } else {
                echo "Oyuncu silinirken hata oluştu.";
            }
        } catch (PDOException $e) {
            echo "Oyuncu silinirken hata oluştu: " . $e->getMessage();
        }
    }



    public function oyuncuGuncelle($oyuncuId, $olumTarihi) {
        try {
            $stmt = $this->dbConnection->prepare("UPDATE oyuncular SET olum = :olumTarihi WHERE idoyuncu = :oyuncuId");
            $stmt->bindParam(':olumTarihi', $olumTarihi, PDO::PARAM_STR);
            $stmt->bindParam(':oyuncuId', $oyuncuId, PDO::PARAM_INT);
    
            if ($stmt->execute()) {
                if ($stmt->rowCount() > 0) {
                    echo "Oyuncu bilgileri başarıyla güncellendi.";
                } else {
                    echo "Güncellenecek oyuncu bulunamadı.";
                }
            } else {
                echo "Oyuncu güncellenirken hata oluştu.";
            }
        } catch (PDOException $e) {
            echo "Oyuncu güncellenirken hata oluştu: " . $e->getMessage();
        }
    }
    
}

// Formdan veri geldiğinde bu kısmı tetikleyin.
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $oyuncuController = new OyuncuController($con); // Tek bir instance oluşturalım

    if (isset($_POST['kategori_adi'])) {
        $kategoriAdi = $_POST['kategori_adi'];
        $oyuncuController->kategoriEkle($kategoriAdi);
    } elseif (isset($_POST['kategoriid'])) {
        $kategoriid = $_POST['kategoriid'];
        $oyuncuController->kategoriSil($kategoriid);
    } elseif (isset($_POST['oyuncuid'])) {
        $oyuncuid = $_POST['oyuncuid'];
        $oyuncuController->oyuncuSil($oyuncuid);
    } elseif (isset($_POST['olum_tarihi']) && isset($_POST['oyuncu_id'])) {
        // Ölüm tarihi güncelleme
        $olumTarihi = $_POST['olum_tarihi'];
        $oyuncuId = $_POST['oyuncu_id'];
        $oyuncuController->oyuncuGuncelle($oyuncuId, $olumTarihi);
    }
}
?>
