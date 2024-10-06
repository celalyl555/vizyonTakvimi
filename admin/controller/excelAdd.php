<?php
require '../../vendor/autoload.php';  
include('../conn.php');  // PDO bağlantısı

error_reporting(E_ALL);
ini_set('display_errors', 1);

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Shared\Date;

$filmId = isset($_POST['filmid']) ? $_POST['filmid'] : null;
$dagitimId = isset($_POST['dagitimid']) ? $_POST['dagitimid'] : null;
$statu = isset($_POST['statu']) ? $_POST['statu'] : null;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_FILES['excelFile']) && $_FILES['excelFile']['error'] == UPLOAD_ERR_OK) {
        $tmpFilePath = $_FILES['excelFile']['tmp_name'];

        try {
            $spreadsheet = IOFactory::load($tmpFilePath);
            $sheet = $spreadsheet->getActiveSheet();

            // Veritabanı ekleme işlemi için PDO sorgusu
            $stmt = $con->prepare("INSERT INTO filmveriler (film_id, dagitim_id, tarih, sinema, perde, kisi, hasilat, toplamkisi, toplamhasilat, statu) 
                                   VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                                   
            // Tarih kontrolü için sorgu
            $checkStmt = $con->prepare("SELECT COUNT(*) FROM filmveriler WHERE tarih = ? AND film_id = ? AND dagitim_id = ?");

            // En büyük toplam hasılat ve toplam kişi değerlerini saklamak için değişkenler
            $maxToplamHasilat = 0;
            $maxToplamKisi = 0;

            // Veritabanı işlemine başlama (Transaction)
            $con->beginTransaction();

            // Son satırı bul ve döngü ile işle
            $lastRow = $sheet->getHighestRow();
            echo "Son Satır: $lastRow<br>"; // Son satırı yazdır

            for ($row = 1; $row <= $lastRow; $row++) {
                // Tarih hücresinin hesaplanmış değerini al
                $tarihCellValue = $sheet->getCell('B' . $row)->getCalculatedValue();
                if ($tarihCellValue instanceof \PhpOffice\PhpSpreadsheet\RichText\RichText) {
                    $tarih = $tarihCellValue->getPlainText();
                } elseif (is_numeric($tarihCellValue)) {
                    $tarih = Date::excelToDateTimeObject($tarihCellValue)->format('Y-m-d');
                } else {
                    echo "Satır $row'da tarih geçerli değil, atlanıyor.<br>";
                    continue; // Geçersiz tarih varsa bu satırı atla
                }

                // Diğer hücrelerden hesaplanmış değerleri al
                $sinema = $sheet->getCell('C' . $row)->getCalculatedValue();
                $perde = $sheet->getCell('D' . $row)->getCalculatedValue();
                $kisi = $sheet->getCell('E' . $row)->getCalculatedValue();
                $hasilat = $sheet->getCell('F' . $row)->getCalculatedValue();
                $topkisi = $sheet->getCell('G' . $row)->getCalculatedValue();
                $tophasilat = $sheet->getCell('H' . $row)->getCalculatedValue();

                // Hiçbir değerin boş olmaması gerekiyor (0 da kabul edilecek)
                if (is_null($tarih) || $tarih === '' || is_null($sinema) || $sinema === '' ||
                    is_null($perde) || $perde === '' || is_null($kisi) || $kisi === '' || 
                    is_null($hasilat) || $hasilat === '' || is_null($topkisi) || $topkisi === '' || 
                    is_null($tophasilat) || $tophasilat === '') {
                    echo "Satır $row'da bir veya daha fazla alan boş, atlanıyor.<br>";
                    continue; // Geçerli bir değer yoksa bu satırı atla
                }

                // Daha önce kayıtlı olup olmadığını kontrol et
                $checkStmt->execute([$tarih, $filmId, $dagitimId]);
                $count = $checkStmt->fetchColumn();

                if ($count > 0) {
                    echo "Satır $row'da tarih ($tarih) zaten mevcut, atlanıyor.<br>";
                    continue; // Daha önce mevcutsa bu satırı atla
                }

                // Her satır için SQL sorgusunu çalıştır
                $stmt->execute([$filmId, $dagitimId, $tarih, $sinema, $perde, $kisi, $hasilat, $topkisi, $tophasilat, $statu]);

                // En büyük değerleri güncelle
                if ($tophasilat > $maxToplamHasilat) {
                    $maxToplamHasilat = $tophasilat;
                }
                if ($topkisi > $maxToplamKisi) {
                    $maxToplamKisi = $topkisi;
                }

                // Başarıyla kaydedilen satır hakkında bilgi ver
                echo "Satır $row başarıyla kaydedildi.<br>";
            }

            // Veritabanı işlemlerini tamamla (Commit)
            $con->commit();

            echo 'Veriler başarıyla kaydedildi.<br>';

            // En büyük toplam hasılat ve toplam kişi değerlerini filmler tablosuna güncelle
            if ($maxToplamHasilat > 0 || $maxToplamKisi > 0) {
                $updateStmt = $con->prepare("UPDATE filmler SET topHasilat = ?, topKisi = ? WHERE id = ?");
                $updateStmt->execute([$maxToplamHasilat, $maxToplamKisi, $filmId]);
            }

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
?>
