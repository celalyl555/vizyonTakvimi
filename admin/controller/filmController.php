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

    public function filmturuEkle($filmturu) {
        try {
            $stmt = $this->dbConnection->prepare("INSERT INTO filmturleri (filmturu) VALUES (:filmturu)");

            // Veriyi bağla ve sorguyu çalıştır
            $stmt->bindParam(':filmturu', $filmturu, PDO::PARAM_STR);

            if ($stmt->execute()) {
                echo "Kategori başarıyla eklendi: " . htmlspecialchars($filmturu);
            } else {
                echo "Kategori eklenirken hata oluştu.";
            }
        } catch (PDOException $e) {
            echo "Kategori eklenirken hata oluştu: " . $e->getMessage();
        }
    }

    public function filmturuiSil($filmturuid) {
        try {
            $stmt = $this->dbConnection->prepare("DELETE FROM filmturleri WHERE idfilm = :filmturuid");
            $stmt->bindParam(':filmturuid', $filmturuid, PDO::PARAM_INT);

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

    public function studyoEkle($studyo) {
        try {
            $stmt = $this->dbConnection->prepare("INSERT INTO stüdyo (studyoad) VALUES (:studyo)");

            // Veriyi bağla ve sorguyu çalıştır
            $stmt->bindParam(':studyo', $studyo, PDO::PARAM_STR);

            if ($stmt->execute()) {
                echo "Kategori başarıyla eklendi: " . htmlspecialchars($studyo);
            } else {
                echo "Kategori eklenirken hata oluştu.";
            }
        } catch (PDOException $e) {
            echo "Kategori eklenirken hata oluştu: " . $e->getMessage();
        }
    }


   
    public function studyoSil($studyoid) {
        try {
            $stmt = $this->dbConnection->prepare("DELETE FROM stüdyo WHERE id = :studyoid");
            $stmt->bindParam(':studyoid', $studyoid, PDO::PARAM_INT);

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


    public function dagitimEkle($dagitim) {
        // Dağıtım değerinin geçerli olduğunu kontrol et
        if (empty($dagitim)) {
            echo "Dağıtım adı boş olamaz!";
            return;
        }
    
        $seourl = $this->generateSeoUrl($dagitim);
    
        try {
            $stmt = $this->dbConnection->prepare("INSERT INTO sinemadagitim (dagitimad, seo_url) VALUES (:dagitim, :seourl)");
    
            // Veriyi bağla ve sorguyu çalıştır
            $stmt->bindParam(':dagitim', $dagitim, PDO::PARAM_STR);
            $stmt->bindParam(':seourl', $seourl, PDO::PARAM_STR);
    
            if ($stmt->execute()) {
                echo "Kategori başarıyla eklendi: " . htmlspecialchars($dagitim);
            } else {
                echo "Kategori eklenirken hata oluştu.";
            }
        } catch (PDOException $e) {
            // Hata ayıklama için hata mesajını göster
            echo "Kategori eklenirken hata oluştu: " . htmlspecialchars($e->getMessage());
        }
    }
    
    function generateSeoUrl($dagitimAdi) {
        // Türkçe karakterleri İngilizce karakterlere çevir
        $turkce = array('Ç', 'Ş', 'Ğ', 'Ü', 'İ', 'Ö', 'ç', 'ş', 'ğ', 'ü', 'ı', 'ö');
        $ingilizce = array('C', 'S', 'G', 'U', 'I', 'O', 'c', 's', 'g', 'u', 'i', 'o');
        
        // Türkçe karakterleri değiştir
        $seoAdi = str_replace($turkce, $ingilizce, $dagitimAdi);
    
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
    
   
    public function dagitimSil($dagitimid) {
        try {
            $stmt = $this->dbConnection->prepare("DELETE FROM sinemadagitim WHERE iddagitim = :dagitimid");
            $stmt->bindParam(':dagitimid', $dagitimid, PDO::PARAM_INT);

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

    public function filmSil($filmSil) {
        try {

                     $stmtSelect = $this->dbConnection->prepare("SELECT kapak_resmi FROM filmler WHERE id = :filmSil");
             $stmtSelect->bindParam(':filmSil', $filmSil, PDO::PARAM_INT);
             $stmtSelect->execute();

             // Kapak resmini al
             $kapakResmi = $stmtSelect->fetchColumn();
             // Eğer kapak resmi mevcutsa, dosyayı sil
            
                 unlink("../../kapakfoto/".$kapakResmi); // Kapak resmini sil
           
         
            $stmt = $this->dbConnection->prepare("DELETE FROM filmler WHERE id = :filmSil");
            $stmt->bindParam(':filmSil', $filmSil, PDO::PARAM_INT);
            $stmt->execute();
             // Sonra filmler tablosundan sil
             $stmt2 = $this->dbConnection->prepare("DELETE FROM film_filmturu WHERE film_id = :filmSil");
             $stmt2->bindParam(':filmSil', $filmSil, PDO::PARAM_INT);
             $stmt2->execute();

             $stmt3 = $this->dbConnection->prepare("DELETE FROM film_dagitim WHERE film_id = :filmSil");
             $stmt3->bindParam(':filmSil', $filmSil, PDO::PARAM_INT);
             $stmt3->execute();



                // Silinecek film galerilerinin resim yollarını çek
    $stmtSelect = $this->dbConnection->prepare("SELECT resim_yolu FROM film_galeri WHERE film_id = :filmSil");
    $stmtSelect->bindParam(':filmSil', $filmSil, PDO::PARAM_INT);
    $stmtSelect->execute();
    
    // Resim yollarını al
    $resimler = $stmtSelect->fetchAll(PDO::FETCH_COLUMN);

    // Resim dosyalarını sunucudan sil
    foreach ($resimler as $resimYolu) {
       
            unlink("../../galeri/".$resimYolu); // Dosyayı sil
        
    }
             $stmt4 = $this->dbConnection->prepare("DELETE FROM film_galeri WHERE film_id = :filmSil");
             $stmt4->bindParam(':filmSil', $filmSil, PDO::PARAM_INT);
             $stmt4->execute();

             $stmt5 = $this->dbConnection->prepare("DELETE FROM film_studyolar WHERE film_id = :filmSil");
             $stmt5->bindParam(':filmSil', $filmSil, PDO::PARAM_INT);
             $stmt5->execute();

             $stmt6 = $this->dbConnection->prepare("DELETE FROM film_ulkeler WHERE film_id = :filmSil");
             $stmt6->bindParam(':filmSil', $filmSil, PDO::PARAM_INT);
             $stmt6->execute();

             $stmt7 = $this->dbConnection->prepare("DELETE FROM oyuncuiliski WHERE film_id = :filmSil");
             $stmt7->bindParam(':filmSil', $filmSil, PDO::PARAM_INT);
             $stmt7->execute();


          
        } catch (PDOException $e) {
            echo "Kategori silinirken hata oluştu: " . $e->getMessage();
        }
    }



    public function haberSil($haberid) {
        try {

            $stmtSelect = $this->dbConnection->prepare("SELECT haberfoto FROM haberler WHERE idhaber = :haberid");
            $stmtSelect->bindParam(':haberid', $haberid, PDO::PARAM_INT);
            $stmtSelect->execute();
            
            // Resim yollarını al
            $resimler = $stmtSelect->fetchAll(PDO::FETCH_COLUMN);
        
            // Resim dosyalarını sunucudan sil
            foreach ($resimler as $resimYolu) {
               
                    unlink("../../haberfoto/".$resimYolu); // Dosyayı sil
                

            }

            $stmt = $this->dbConnection->prepare("DELETE FROM haberler WHERE idhaber = :haberid");
            $stmt->bindParam(':haberid', $haberid, PDO::PARAM_INT);

            if ($stmt->execute()) {
                if ($stmt->rowCount() > 0) {
                    echo "Haber başarıyla silindi.";
                } else {
                    echo "Silinecek Haber bulunamadı.";
                }
            } else {
                echo "Haber silinirken hata oluştu.";
            }
        } catch (PDOException $e) {
            echo "Haber silinirken hata oluştu: " . $e->getMessage();
        }
    }
    
}

// Formdan veri geldiğinde bu kısmı tetikleyin.
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $oyuncuController = new OyuncuController($con); // Tek bir instance oluşturalım

    if (isset($_POST['filmturu'])) {
        $filmturu = $_POST['filmturu'];
        $oyuncuController->filmturuEkle($filmturu);
    } elseif (isset($_POST['filmturuid'])) {
        $filmturuid = $_POST['filmturuid'];
        $oyuncuController->filmturuiSil($filmturuid);
    } elseif (isset($_POST['studyo'])) {
        $studyo = $_POST['studyo'];
        $oyuncuController->studyoEkle($studyo);
    } elseif (isset($_POST['studyoid'])) {
        // Ölüm tarihi güncelleme
        $studyoid = $_POST['studyoid'];
        $oyuncuController->studyoSil($studyoid);
    }elseif (isset($_POST['dagitim'])) {
        // Ölüm tarihi güncelleme
        $dagitim = $_POST['dagitim'];
        $oyuncuController->dagitimEkle($dagitim);
    } elseif (isset($_POST['dagitimid'])) {
        // Ölüm tarihi güncelleme
        $dagitimid = $_POST['dagitimid'];
        $oyuncuController->dagitimSil($dagitimid);
    }elseif (isset($_POST['filmSil'])) {
        // Ölüm tarihi güncelleme
        $filmSil = $_POST['filmSil'];
        $oyuncuController->filmSil($filmSil);
    }
    elseif (isset($_POST['haberid'])) {
        // Ölüm tarihi güncelleme
        $haberid = $_POST['haberid'];
        $oyuncuController->haberSil($haberid);
    }
}
?>
