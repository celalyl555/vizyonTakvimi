<?php
include('admin/conn.php');


$haberId = 18;

try {
    // Veritabanından haber bilgilerini al
    $sql = "SELECT * FROM haberler WHERE idhaber = :haberId";
    $stmt = $con->prepare($sql);
    $stmt->bindParam(':haberId', $haberId, PDO::PARAM_INT);
    
    // Sorguyu çalıştır
    $stmt->execute();
    
    // Sonuçları al
    $haber = $stmt->fetch(PDO::FETCH_ASSOC);
    
    
} catch (PDOException $e) {
    echo "Veritabanı hatası: " . $e->getMessage();
}

 echo $haber['icerik'];  
?>