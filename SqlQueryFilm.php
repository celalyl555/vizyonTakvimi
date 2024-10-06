<?php
 
 #vizyonda yeni sql query başlangıç
    $sqlEnEskiFilm = "SELECT * FROM filmler 
    WHERE statu = 1 
    AND vizyon_tarihi BETWEEN CURDATE() - INTERVAL 2 WEEK AND CURDATE()
    ORDER BY vizyon_tarihi ASC
    LIMIT 1";
    $stmtEnEskiFilm = $con->query($sqlEnEskiFilm);
    $enEskiFilm = $stmtEnEskiFilm->fetch(PDO::FETCH_ASSOC);

    // Vizyondaki diğer filmleri çekiyoruz, en eski film hariç
    $sqlFilmlerVizyon = "SELECT * FROM filmler 
    WHERE statu = 1 
    AND vizyon_tarihi BETWEEN CURDATE() - INTERVAL 2 WEEK AND CURDATE() 
    AND id != :enEskiFilmId
    ORDER BY vizyon_tarihi ASC 
    LIMIT 3"; 
    $stmtFilmlerVizyon = $con->prepare($sqlFilmlerVizyon); // query yerine prepare
    $stmtFilmlerVizyon->bindParam(':enEskiFilmId', $enEskiFilm['id'], PDO::PARAM_INT); // en eski film id'sini bağlıyoruz
    $stmtFilmlerVizyon->execute(); // prepared statement'ı çalıştırıyoruz
    $filmlerVizyon = $stmtFilmlerVizyon->fetchAll(PDO::FETCH_ASSOC); // sonuçları alıyoruz
#vizyonda yeni sql query bitiş

#*******************************************************************************

#yakında sql query başlangıç
    $sqlEnYeniFilm = "SELECT * FROM filmler 
    WHERE statu = 1 
    AND vizyon_tarihi BETWEEN CURDATE() AND CURDATE() + INTERVAL 2 WEEK 
    ORDER BY vizyon_tarihi ASC
    LIMIT 1";
    $stmtEnYeniFilm = $con->query($sqlEnYeniFilm);
    $enYeniFilm = $stmtEnYeniFilm->fetch(PDO::FETCH_ASSOC); // Tek kayıt alıyoruz

    // Yakın vizyondaki diğer filmleri çekiyoruz, en yeni film hariç
    $sqlFilmlerYakin = "SELECT * FROM filmler 
    WHERE statu = 1 
    AND vizyon_tarihi BETWEEN CURDATE() AND CURDATE() + INTERVAL 2 WEEK 
    AND id != :enYeniFilmId
    ORDER BY vizyon_tarihi ASC
    LIMIT 3";
    $stmtFilmlerYakin = $con->prepare($sqlFilmlerYakin); 
    $stmtFilmlerYakin->bindParam(':enYeniFilmId', $enYeniFilm['id'], PDO::PARAM_INT); 
    $stmtFilmlerYakin->execute(); 
    $filmlerYakin = $stmtFilmlerYakin->fetchAll(PDO::FETCH_ASSOC);


#********************************************************************************
    $sqlFilmlerGenelYakin = "SELECT * FROM filmler 
    WHERE vizyon_tarihi BETWEEN CURDATE() AND CURDATE() + INTERVAL 2 WEEK 
    ORDER BY vizyon_tarihi ASC
    LIMIT 5";
    $stmtFilmlerGenelYakin = $con->query($sqlFilmlerGenelYakin);
    $filmlerGenelYakin = $stmtFilmlerGenelYakin->fetchAll(PDO::FETCH_ASSOC);

#yakında sql query bitiş
$sqlFilmler= "SELECT * FROM filmler  WHERE statu = 1 ORDER BY vizyon_tarihi DESC";
$stmtFilmler= $con->query($sqlFilmler);
$filmler11 = $stmtFilmler->fetchAll(PDO::FETCH_ASSOC);
#********************************************************************************
#haberler sql query başlangıç
    $sqlHaberler = "SELECT * FROM haberler WHERE statu = 1 ORDER BY tarih DESC LIMIT 4"; // En yeni 4 haberi al
    $stmtHaberler = $con->query($sqlHaberler);
    $haberler = $stmtHaberler->fetchAll(PDO::FETCH_ASSOC);

    $sqlHaberler = "SELECT * FROM haberler WHERE statu = 1 ORDER BY tarih DESC"; // En yeni 4 haberi al
    $stmtHaberler = $con->query($sqlHaberler);
    $haberler2 = $stmtHaberler->fetchAll(PDO::FETCH_ASSOC);

#********************************************************************************

    $sqlHaberlerGenel = "SELECT * FROM haberler ORDER BY tarih DESC LIMIT 4"; // En yeni 4 haberi al
    $stmtHaberlerGenel = $con->query($sqlHaberlerGenel);
    $haberlerGenel = $stmtHaberlerGenel->fetchAll(PDO::FETCH_ASSOC);

    $sqlHaberlerGenel1 = "SELECT * FROM haberler ORDER BY tarih DESC"; // En yeni 4 haberi al
    $stmtHaberlerGenel1 = $con->query($sqlHaberlerGenel1);
    $haberlerGenel1 = $stmtHaberlerGenel1->fetchAll(PDO::FETCH_ASSOC);
#haberler sql query bitiş

#*********************************************************************************
#film verileri max kişi sql query başlangıç
    $sqlFilmVerileri = "SELECT f.*, fi.film_adi,fi.seo_url
    FROM filmveriler f
    INNER JOIN (
        SELECT film_id, MAX(toplamkisi) AS max_kisi
        FROM filmveriler
        GROUP BY film_id
    ) AS max_filmler ON f.film_id = max_filmler.film_id
    AND f.toplamkisi = max_filmler.max_kisi
    INNER JOIN filmler fi ON f.film_id = fi.id
    GROUP BY f.film_id
    ORDER BY f.toplamkisi DESC
    LIMIT 20";

    $stmtFilmVerileri = $con->query($sqlFilmVerileri);
    $filmVerileri = $stmtFilmVerileri->fetchAll(PDO::FETCH_ASSOC);
#film verileri max kişi sql query başlangıç

?>