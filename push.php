<?php
require 'vendor/autoload.php';

use Minishlink\WebPush\WebPush;
use Minishlink\WebPush\Subscription;

include('admin/conn.php'); // Veritabanı bağlantısı

// VAPID anahtarları
$auth = [
    'VAPID' => [
        'subject' => 'mailto:celalyl555@gmail.com',
        'publicKey' => 'HdXC0VmBThzoDatOHOr5JL2WQcpFOBMkLH+I2G5tVwI=',
        'privateKey' => '46UdAORoCqSTsFg3eFXwGDM/5nn1hAFRbhC6zInSZZY=',
    ],
];

$webPush = new WebPush($auth);

// Veritabanından abonelik bilgilerini al
try {
    $stmt = $con->query("SELECT * FROM subscriptions");
    $subscriptions = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Her aboneye bildirim gönder
    foreach ($subscriptions as $sub) {
        $subscription = Subscription::create([
            'endpoint' => $sub['endpoint'],
            'keys' => [
                'p256dh' => $sub['p256dh_key'],
                'auth' => $sub['auth_key'],
            ],
        ]);

        $webPush->sendNotification(
            $subscription,
            json_encode(['title' => 'Yeni Bildirim', 'body' => 'Bu bir push bildirimdir!']) // Bildirim içeriği
        );
    }

    // Bildirimleri gönderme işlemi
    $responses = $webPush->flush();
    $success = true;

    foreach ($responses as $report) {
        $endpoint = $report->getRequest()->getUri()->__toString();
        if ($report->isSuccess()) {
            echo "Bildirim gönderildi: {$endpoint}\n";
        } else {
            $success = false;
            echo "Bildirim gönderilemedi: {$endpoint}: {$report->getReason()}\n";
        }
    }

    // JSON yanıtı döndür
    header('Content-Type: application/json');
    echo json_encode(['success' => $success]);

} catch (Exception $e) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => $e->getMessage()]); // Hata mesajı
}
exit; // Çıkış yap
?>
