<?php
#Data Base Çekim işlemi
include('admin/conn.php');

#sinema dagitim select query
$sqlFilmHaftalari = "SELECT fv.film_id, f.film_adi, sd.seo_url, s.studyoad, 
                     COUNT(DISTINCT YEARWEEK(fv.tarih, 5)) AS kac_hafta_cuma
                     FROM filmveriler fv
                     JOIN filmler f ON fv.film_id = f.id
                     JOIN sinemadagitim sd ON fv.dagitim_id = sd.iddagitim
                     JOIN film_studyolar fs ON fv.film_id = fs.film_id
                     JOIN `stüdyo` s ON fs.studyo_id = s.id
                     WHERE sd.seo_url = :seourl
                     AND YEAR(fv.tarih) = :selectedyear
                     GROUP BY fv.film_id, sd.seo_url, s.studyoad";

$stmtFilmHaftalari = $con->prepare($sqlFilmHaftalari); 
$stmtFilmHaftalari->bindParam(':seourl', $seourl, PDO::PARAM_STR);
$stmtFilmHaftalari->bindParam(':selectedyear', $selectedYear, PDO::PARAM_STR);
$stmtFilmHaftalari->execute();
$filmHaftaListesi = $stmtFilmHaftalari->fetchAll(PDO::FETCH_ASSOC); // Fetch all results

#dagitim adı
$sqldagitimAd = "SELECT dagitimad 
FROM sinemadagitim
WHERE seo_url = :seourl";

$stmtDagitimAdi = $con->prepare($sqldagitimAd); // Hazırlıyoruz
$stmtDagitimAdi->bindParam(':seourl', $seourl, PDO::PARAM_STR);
$stmtDagitimAdi->execute();
$dagitimAd = $stmtDagitimAdi->fetch(PDO::FETCH_ASSOC);



/*
$sqlLokasyon = "SELECT SUM(max_sinema) AS toplam_sinema
FROM (
    SELECT film_id, dagitim_id, MAX(sinema) AS max_sinema
    FROM filmveriler
    WHERE YEAR(tarih) = :selectedyear
    AND dagitim_id = dagitim_id
    GROUP BY film_id
) AS subquery";
$stmtLokasyon = $con->prepare($sqlLokasyon);
$stmtLokasyon->bindParam(':selectedyear', $selectedYear, PDO::PARAM_STR);
$stmtLokasyon->execute();
$lokasyonData = $stmtLokasyon->fetch(PDO::FETCH_ASSOC);

*/

#******************************** kalsın ************************************
 // haftaları sayan SQL sorgusu
// $sqlHaftaSayisi = "SELECT film_id, dagitim_id, COUNT(DISTINCT YEARWEEK(tarih, 5)) AS kac_hafta_cuma
// FROM filmveriler fv
// JOIN sinemadagitim s ON fv.dagitim_id = s.iddagitim
// WHERE YEAR(tarih) = :selectedyear
// AND s.seo_url = :seourl
// GROUP BY film_id, dagitim_id";

// $stmtHaftaSayisi = $con->prepare($sqlHaftaSayisi); // Hazırlıyoruz
// $stmtHaftaSayisi->bindParam(':seourl', $seourl, PDO::PARAM_STR);
// $stmtHaftaSayisi->bindParam(':selectedyear', $selectedyear, PDO::PARAM_STR);
// $stmtHaftaSayisi->execute();
// $haftaSayisi = $stmtHaftaSayisi->fetch(PDO::FETCH_ASSOC);

//buraya kadar hafta sayısını sayar
?>