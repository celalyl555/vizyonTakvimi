<?php
include('../conn.php');  



// İstemciden gelen ay bilgisini al
if (isset($_GET['month'])) {
    $month = $_GET['month']; // Örneğin: 2024-10

    // Ayın başlangıç ve bitiş tarihlerini ayarla
    $startDate = $month . '-01';
    $endDate = $month . '-' . date('t', strtotime($month)); // O ayın gün sayısını al

    // Kullanıcı ziyaretlerini gün bazında almak için sorgu
    $stmt = $con->prepare("
        SELECT DATE(visit_date) AS visit_date, user_count 
        FROM user_visits 
        WHERE visit_date BETWEEN :startDate AND :endDate
    ");
    $stmt->execute(['startDate' => $startDate, 'endDate' => $endDate]);

    // Gelen verileri işleme
    $userVisits = [];
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $day = (int) date('d', strtotime($row['visit_date']));
        $userVisits[$day] = $row; // Gün numarasını anahtar olarak kullan
    }

    // JSON formatında döndür
    echo json_encode($userVisits);
} else {
    echo json_encode(['error' => 'Ay bilgisi eksik']); // Ay bilgisi yoksa hata döndür
}
?>
