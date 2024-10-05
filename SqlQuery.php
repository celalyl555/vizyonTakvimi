<?php
 
 #vizyonda yeni sql query başlangıç
    $sqlEnEskiFilm = "SELECT * FROM filmler 
    WHERE statu = 1 AND vizyon_tarihi >= CURDATE() - INTERVAL 2 WEEK
    ORDER BY vizyon_tarihi ASC
    LIMIT 1";
    $stmtEnEskiFilm = $con->query($sqlEnEskiFilm);
    $enEskiFilm = $stmtEnEskiFilm->fetch(PDO::FETCH_ASSOC);

    // Vizyondaki diğer filmleri çekiyoruz, en eski film hariç
    $sqlFilmlerVizyon = "SELECT * FROM filmler 
    WHERE statu = 1 AND vizyon_tarihi >= CURDATE() - INTERVAL 2 WEEK 
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
    WHERE statu = 1 AND vizyon_tarihi <= CURDATE() + INTERVAL 2 WEEK 
    ORDER BY vizyon_tarihi DESC
    LIMIT 1";
    $stmtEnYeniFilm = $con->query($sqlEnYeniFilm);
    $enYeniFilm = $stmtEnYeniFilm->fetch(PDO::FETCH_ASSOC); // `fetchAll` yerine `fetch` kullanıyoruz çünkü tek bir kayıt alıyoruz

    // Yakın vizyondaki diğer filmleri çekiyoruz, en yeni film hariç
    $sqlFilmlerYakin = "SELECT * FROM filmler 
    WHERE statu = 1 AND vizyon_tarihi <= CURDATE() + INTERVAL 2 WEEK
    AND id != :enYeniFilmId
    ORDER BY vizyon_tarihi DESC
    LIMIT 3";
    $stmtFilmlerYakin = $con->prepare($sqlFilmlerYakin); // query yerine prepare
    $stmtFilmlerYakin->bindParam(':enYeniFilmId', $enYeniFilm['id'], PDO::PARAM_INT); // en yeni film id'sini bağlıyoruz
    $stmtFilmlerYakin->execute(); // prepared statement'ı çalıştırıyoruz
    $filmlerYakin = $stmtFilmlerYakin->fetchAll(PDO::FETCH_ASSOC); // sonuçları alıyoruz

#yakında sql query bitiş

#********************************************************************************
#haberler sql query başlangıç
    $sqlHaberler = "SELECT * FROM haberler ORDER BY tarih DESC LIMIT 4"; // En yeni 4 haberi al
    $stmtHaberler = $con->query($sqlHaberler);
    $haberler = $stmtHaberler->fetchAll(PDO::FETCH_ASSOC);
#haberler sql query bitiş

#*********************************************************************************
#film verileri max kişi sql query başlangıç
    $sqlFilmVerileri = "SELECT f.*, fi.film_adi
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