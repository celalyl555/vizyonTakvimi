<?php
#Data Base Çekim işlemi
include('admin/conn.php');

#sinema dagitim select query
$sqldagitimListesi = "SELECT DISTINCT fv.film_id, f.film_adi, sd.seo_url, s.studyoad
FROM filmveriler fv
JOIN filmler f ON fv.film_id = f.id
JOIN sinemadagitim sd ON fv.dagitim_id = sd.iddagitim
JOIN film_studyolar fs ON fv.film_id = fs.film_id
JOIN `stüdyo` s ON fs.studyo_id = s.id
WHERE sd.seo_url = :seourl
AND YEAR(tarih) = :selectedyear";
$stmtDagitimListesi = $con->prepare($sqldagitimListesi); // Hazırlıyoruz
$stmtDagitimListesi->bindParam(':seourl', $seourl, PDO::PARAM_STR);
$stmtDagitimListesi->bindParam(':selectedyear', $selectedYear, PDO::PARAM_STR);
$stmtDagitimListesi->execute();
$dagitimListesi = $stmtDagitimListesi->fetchAll(PDO::FETCH_ASSOC);

#dagitim adı
$sqldagitimAd = "SELECT dagitimad 
FROM sinemadagitim
WHERE seo_url = :seourl";
$stmtDagitimAdi = $con->prepare($sqldagitimAd); // Hazırlıyoruz
$stmtDagitimAdi->bindParam(':seourl', $seourl, PDO::PARAM_STR);
$stmtDagitimAdi->execute();
$dagitimAd = $stmtDagitimAdi->fetch(PDO::FETCH_ASSOC);
 $baslangicTarihi = date('Y-01-01'); // Yılın başı
 $bugun = date('Y-m-d'); // Bugünün tarihi

 // Cumaları sayan SQL sorgusu
 $sqlHaftaSayisi = "SELECT fd.film_id, COUNT(DISTINCT WEEK(fd.tarih, 1)) AS hafta_sayisi
     FROM filmveriler fd
     WHERE fd.tarih >= :baslangic_tarihi AND fd.tarih <= :bugun
     AND DAYOFWEEK(fd.tarih) = 6 -- Cuma günü için
     GROUP BY fd.film_id";

 $stmtHaftaSayisi = $con->prepare($sqlHaftaSayisi);
 $stmtHaftaSayisi->bindParam(':baslangic_tarihi', $baslangicTarihi, PDO::PARAM_STR);
 $stmtHaftaSayisi->bindParam(':bugun', $bugun, PDO::PARAM_STR);
 $stmtHaftaSayisi->execute();

 // Sonuçları al
 $haftaVerileri = $stmtHaftaSayisi->fetchAll(PDO::FETCH_ASSOC);

 // Hafta sayıları dizisi
 $haftaSayilari = [];
 foreach ($haftaVerileri as $haftaVeri) {
     $haftaSayilari[$haftaVeri['film_id']] = $haftaVeri['hafta_sayisi'];
 }


$baslangicTarihi = "$selectedYear-01-01"; // Seçili yılın başı
$bitisTarihi = "$selectedYear-12-31"; // Seçili yılın sonu

$sqlLokasyon = "SELECT SUM(max_sinema) AS toplam_sinema
    FROM (
        SELECT fd.film_id, MAX(fd.sinema) AS max_sinema
        FROM filmveriler fd
        WHERE fd.dagitim_id = :dagitim_id
        AND fd.tarih >= :baslangic_tarihi 
        AND fd.tarih <= :bitis_tarihi  -- Tarih filtrelemesi ekleniyor
        GROUP BY fd.film_id
    ) AS subquery";

$stmtLokasyon = $con->prepare($sqlLokasyon);
$stmtLokasyon->bindParam(':dagitim_id', $dagitim_id, PDO::PARAM_INT);
$stmtLokasyon->bindParam(':baslangic_tarihi', $baslangicTarihi, PDO::PARAM_STR);
$stmtLokasyon->bindParam(':bitis_tarihi', $bitisTarihi, PDO::PARAM_STR);
$stmtLokasyon->execute();
$lokasyonData = $stmtLokasyon->fetch(PDO::FETCH_ASSOC);
$toplamSinema = $lokasyonData['toplam_sinema'];


?>