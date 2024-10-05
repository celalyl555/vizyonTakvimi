<?php
include('admin/conn.php');
$subscription = json_decode(file_get_contents('php://input'), true);

try {


    $stmt = $con->prepare("INSERT INTO subscriptions (endpoint, p256dh_key, auth_key) VALUES (:endpoint, :p256dh_key, :auth_key)");
    $stmt->execute([
        ':endpoint' => $subscription['endpoint'],
        ':p256dh_key' => $subscription['keys']['p256dh'],
        ':auth_key' => $subscription['keys']['auth'],
    ]);

    echo "Abonelik kaydedildi.";
} catch (PDOException $e) {
    echo "Hata: " . $e->getMessage();
}
?>
