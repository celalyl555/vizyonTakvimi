<?php
require '../../vendor/autoload.php';  
include('../conn.php');  // PDO bağlantısı

error_reporting(E_ALL);
ini_set('display_errors', 1);

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Shared\Date;

$filmId = isset($_POST['filmid']) ? $_POST['filmid'] : null;
$dagitimId = isset($_POST['dagitimid']) ? $_POST['dagitimid'] : null;
$basdate = isset($_POST['basdate']) ? $_POST['basdate'] : null;
$bitdate = isset($_POST['bitdate']) ? $_POST['bitdate'] : null;


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_FILES['excelFile']) && $_FILES['excelFile']['error'] == UPLOAD_ERR_OK) {
        $tmpFilePath = $_FILES['excelFile']['tmp_name'];

        try {
            $spreadsheet = IOFactory::load($tmpFilePath);
            $sheet = $spreadsheet->getActiveSheet();

            // Veritabanı ekleme işlemi için PDO sorgusu
            $stmt = $con->prepare("INSERT INTO filmsalon (sehir, sinema, film_id, format, dil, seans1, seans2, seans3, seans4,seans5,seans6,bas_tarih,bit_tarih) 
                                   VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            

            // Veritabanı işlemine başlama (Transaction)
            $con->beginTransaction();

            // Son satırı bul ve döngü ile işle
            $lastRow = $sheet->getHighestRow();
            echo "Son Satır: $lastRow<br>"; // Son satırı yazdır

            for ($row = 2; $row <= $lastRow; $row++) {
                
                $sehir = $sheet->getCell('A' . $row)->getCalculatedValue();
                $sinema = $sheet->getCell('B' . $row)->getCalculatedValue();
                $film = $sheet->getCell('C' . $row)->getCalculatedValue();
                $format = $sheet->getCell('D' . $row)->getCalculatedValue();
                $dil = $sheet->getCell('E' . $row)->getCalculatedValue();



                $seans1 = formatSeansToTime($sheet->getCell('F' . $row)->getFormattedValue());
                $seans2 = formatSeansToTime($sheet->getCell('G' . $row)->getFormattedValue());
                $seans3 = formatSeansToTime($sheet->getCell('H' . $row)->getFormattedValue());
                $seans4 = formatSeansToTime($sheet->getCell('I' . $row)->getFormattedValue());
                $seans5 = formatSeansToTime($sheet->getCell('J' . $row)->getFormattedValue());
                $seans6 = formatSeansToTime($sheet->getCell('K' . $row)->getFormattedValue());
                
                // Her satır için SQL sorgusunu çalıştır
                $stmt->execute([$sehir, $sinema, $filmId, $format, $dil, $seans1, $seans2, $seans3, $seans4, $seans5, $seans6,$basdate,$bitdate]);


                    // Hiçbir değerin boş olmaması gerekiyor (0 da kabul edilecek)
                    if (is_null($sehir) || $sehir === '' || is_null($sinema) || $sinema === '') {
                            echo "Satır $row'da bir veya daha fazla alan boş, atlanıyor.<br>";
                            continue; // Geçerli bir değer yoksa bu satırı atla
                    }



                // Başarıyla kaydedilen satır hakkında bilgi ver
                echo "Satır $row başarıyla kaydedildi.<br>";
            }

            // Veritabanı işlemlerini tamamla (Commit)
            $con->commit();

            echo 'Veriler başarıyla kaydedildi';
        } catch (Exception $e) {
            // Hata durumunda işlemi geri al (Rollback)
            $con->rollBack();
            echo 'Bir hata oluştu: ' . $e->getMessage();
        }
        
    } else {
        echo 'Dosya yükleme hatası';
    }
} else {
    echo 'Geçersiz istek';
}


function formatSeansToTime($seans) {
    // Eğer hücre boşsa direkt boş string döner
    if (empty($seans)) {
        return null; // Boş seanslar için null döndür
    }

    // Zamanı içeren bir DateTime objesi oluşturmaya çalış
    try {
        // Eğer Excel zaman formatında bir değer (örneğin, 14:00, 14:00:00) varsa
        // Zaman formatını 'H:i:s' olarak ayarla (saat:dakika:saniye)
        $seansTime = new DateTime($seans);
        return $seansTime->format('H:i:s');
    } catch (Exception $e) {
        // Eğer DateTime bir hata atarsa, null döndür
        return null;
    }
}