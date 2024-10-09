<?php
#Data Base Çekim işlemi
include('admin/conn.php');

$sqlFilmHaftalari = "SELECT 
    fv.dagitim_id,
    fv.film_id, 
    f.film_adi, 
    f.vizyon_tarihi,  -- Vizyon tarihi ekleniyor
    f.kapak_resmi,  -- Kapak resmi ekleniyor
    sd.seo_url, 
    s.studyoad, 
    COUNT(DISTINCT YEARWEEK(fv.tarih, 5)) AS kac_hafta_cuma,
    COALESCE(MAX(fv.toplamhasilat), 0) AS toplam_hasilat,
    SUM(fv.kisi) AS toplam_kisi,
    MAX(sinema_en_buyuk.toplam_sinema) AS toplam_en_buyuk_sinema
FROM 
    filmveriler fv
JOIN 
    filmler f ON fv.film_id = f.id
JOIN 
    sinemadagitim sd ON fv.dagitim_id = sd.iddagitim
JOIN 
    film_studyolar fs ON fv.film_id = fs.film_id
JOIN 
    `stüdyo` s ON fs.studyo_id = s.id
LEFT JOIN (
    SELECT 
        film_id, 
        dagitim_id, 
        MAX(sinema) AS toplam_sinema
    FROM 
        filmveriler
    GROUP BY 
        film_id, 
        dagitim_id
) AS sinema_en_buyuk ON fv.film_id = sinema_en_buyuk.film_id AND fv.dagitim_id = sinema_en_buyuk.dagitim_id
WHERE 
    sd.seo_url = :seourl
    AND YEAR(fv.tarih) = :selectedyear
GROUP BY 
    fv.film_id, sd.seo_url, s.studyoad, f.vizyon_tarihi, f.kapak_resmi";


$stmtFilmHaftalari = $con->prepare($sqlFilmHaftalari); 
$stmtFilmHaftalari->bindParam(':seourl', $seourl, PDO::PARAM_STR);
$stmtFilmHaftalari->bindParam(':selectedyear', $selectedYear, PDO::PARAM_STR);
$stmtFilmHaftalari->execute();
$filmHaftaListesi = $stmtFilmHaftalari->fetchAll(PDO::FETCH_ASSOC); // Fetch all results

$sqltoplamsinema = "SELECT 
    film_id,
    SUM(max_sinema) AS toplam_max_sinema
FROM (
    SELECT 
        film_id,
        MAX(sinema) AS max_sinema
    FROM 
        filmveriler
    GROUP BY 
        film_id,
        dagitim_id
) AS max_sinema_values
GROUP BY 
    film_id";

$stmtToplamSinema = $con->prepare($sqltoplamsinema); 
$stmtToplamSinema->execute();
$toplamSinema = $stmtToplamSinema->fetchAll(PDO::FETCH_ASSOC);

// Anahtar-kilit modeli oluşturma
$anahtarKilitDizi = [];
foreach ($toplamSinema as $row) {
    $anahtarKilitDizi[$row['film_id']] = $row['toplam_max_sinema'];
}


#dagitim adı
$sqldagitimAd = "SELECT dagitimad 
FROM sinemadagitim
WHERE seo_url = :seourl";

$stmtDagitimAdi = $con->prepare($sqldagitimAd); // Hazırlıyoruz
$stmtDagitimAdi->bindParam(':seourl', $seourl, PDO::PARAM_STR);
$stmtDagitimAdi->execute();
$dagitimAd = $stmtDagitimAdi->fetch(PDO::FETCH_ASSOC);



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