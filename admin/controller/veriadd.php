<?php
// veriadd.php
include('../conn.php');  

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $filmid = $_POST['filmid'];
    $dagitimid = $_POST['dagitimid'];
    $topHasilat = $_POST['topHasilat32'];
    $topSeyirci = $_POST['topSeyirci32'];
    $haftaTarihi = $_POST['haftaTarihi'];

    // Boş veri kontrolü
    if (empty($filmid) || empty($dagitimid) || empty($topHasilat) || empty($topSeyirci) || empty($haftaTarihi)) {
        echo "Boş alanları doldurunuz.";
        exit; // İşlemden çık
    } else {
        $sql = "INSERT INTO filmveriler (film_id, dagitim_id, tarih, toplamkisi, toplamhasilat) VALUES (:filmid, :dagitimid, :haftaTarihi, :topSeyirci, :topHasilat)";

        $stmt = $con->prepare($sql);
        $stmt->bindParam(':filmid', $filmid);
        $stmt->bindParam(':dagitimid', $dagitimid);
        $stmt->bindParam(':haftaTarihi', $haftaTarihi);
        $stmt->bindParam(':topSeyirci', $topSeyirci);
        $stmt->bindParam(':topHasilat', $topHasilat);

        // Sorguyu çalıştır
        if ($stmt->execute()) {
            $sql = "SELECT 
                        MAX(toplamkisi) AS max_toplamkisi,
                        MAX(toplamhasilat) AS max_toplamhasilat
                    FROM 
                        filmveriler
                    WHERE 
                        film_id = :filmid";

            $stmt = $con->prepare($sql);
            $stmt->bindParam(':filmid', $filmid);
            $stmt->execute(); // Bu satır eksikti
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            // Fetch sonucunu kontrol et
            if ($result) {
                $max_toplamkisi = $result['max_toplamkisi'];
                $max_toplamhasilat = $result['max_toplamhasilat'];

                $update_sql = "UPDATE filmler 
                               SET topKisi = :max_toplamkisi, topHasilat = :max_toplamhasilat
                               WHERE id = :filmid";

                $update_stmt = $con->prepare($update_sql);
                $update_stmt->bindParam(':max_toplamkisi', $max_toplamkisi);
                $update_stmt->bindParam(':max_toplamhasilat', $max_toplamhasilat);
                $update_stmt->bindParam(':filmid', $filmid);
                $update_stmt->execute();
            } else {
                echo "Veri bulunamadı.";
            }
        } else {
            echo "Veri kaydetme işlemi başarısız.";
        }
    }
} else {
    echo "Geçersiz istek.";
}
?>
