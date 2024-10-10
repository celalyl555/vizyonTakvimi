<?php
// veriadd.php
include('../conn.php');  


$veriid = $_POST['veriid'];
$filmid = $_POST['filmid'];
echo $filmid;

$sql = "DELETE FROM filmveriler WHERE id = :veriid";

$stmt = $con->prepare($sql);
$stmt->bindParam(':veriid', $veriid);

// Sorguyu çalıştır
$stmt->execute();


$sql = "SELECT 
MAX(toplamkisi) AS max_toplamkisi,
MAX(toplamhasilat) AS max_toplamhasilat
FROM 
filmveriler
WHERE 
film_id = :filmid";

$stmt = $con->prepare($sql);
$stmt->bindParam(':filmid', $filmid );
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


?>