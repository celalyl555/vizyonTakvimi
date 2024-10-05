<?php
 
 #vizyonda yeni sql query başlangıç
    $sqlEnEskiDizi = "SELECT * FROM filmler 
    WHERE statu = 2 
    AND vizyon_tarihi BETWEEN CURDATE() - INTERVAL 2 WEEK AND CURDATE()
    ORDER BY vizyon_tarihi ASC
    LIMIT 1";
    $stmtEnEskiDizi = $con->query($sqlEnEskiDizi);
    $enEskiDizi = $stmtEnEskiDizi->fetch(PDO::FETCH_ASSOC);

    $sqlDizilerVizyon = "SELECT * FROM filmler 
    WHERE statu = 2 
    AND vizyon_tarihi BETWEEN CURDATE() - INTERVAL 2 WEEK AND CURDATE() 
    AND id != :enEskiDizi
    ORDER BY vizyon_tarihi ASC 
    LIMIT 3"; 
    $stmtDizilerVizyon = $con->prepare($sqlDizilerVizyon);
    $stmtDizilerVizyon->bindParam(':enEskiDizi', $enEskiDizi['id'], PDO::PARAM_INT);
    $stmtDizilerVizyon->execute();
    $dizilerVizyon = $stmtDizilerVizyon->fetchAll(PDO::FETCH_ASSOC);
#vizyonda yeni sql query bitiş

#*******************************************************************************

#yakında sql query başlangıç
    $sqlEnYeniDizi = "SELECT * FROM filmler 
    WHERE statu = 2 
    AND vizyon_tarihi BETWEEN CURDATE() AND CURDATE() + INTERVAL 2 WEEK 
    ORDER BY vizyon_tarihi DESC
    LIMIT 1";
    $stmtEnYeniDizi = $con->query($sqlEnYeniDizi);
    $enYeniDizi = $stmtEnYeniDizi->fetch(PDO::FETCH_ASSOC); // Tek kayıt alıyoruz

    // Yakın vizyondaki diğer filmleri çekiyoruz, en yeni film hariç
    $sqlDizilerYakin = "SELECT * FROM filmler 
    WHERE statu = 2 
    AND vizyon_tarihi BETWEEN CURDATE() AND CURDATE() + INTERVAL 2 WEEK 
    AND id != :enYeniDiziId
    ORDER BY vizyon_tarihi DESC
    LIMIT 3";
    $stmtDizilerYakin = $con->prepare($sqlDizilerYakin); 
    $stmtDizilerYakin->bindParam(':enYeniDiziId', $enYeniDizi['id'], PDO::PARAM_INT); 
    $stmtDizilerYakin->execute(); 
    $dizilerYakin = $stmtDizilerYakin->fetchAll(PDO::FETCH_ASSOC);

#yakında sql query bitiş

#********************************************************************************
#haberler sql query başlangıç
    $sqlHaberler = "SELECT * FROM haberler WHERE statu = 2 ORDER BY tarih DESC LIMIT 4"; // En yeni 4 haberi al
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