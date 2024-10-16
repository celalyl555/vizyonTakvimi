<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../../vendor/autoload.php';

// Formdan gelen verileri al
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = htmlspecialchars($_POST['name']);
    $firm = htmlspecialchars($_POST['firma']);
    $mail_address = htmlspecialchars($_POST['mail']);
    $phone = htmlspecialchars($_POST['phone']);
    $alan = htmlspecialchars($_POST['alan']);
    $detay = htmlspecialchars($_POST['detay']);
    $not = htmlspecialchars($_POST['not']);
// Verileri yazdır
    $mail = new PHPMailer(true);

    try {
        // Mail server ayarları
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com'; // SMTP sunucu adresi
        $mail->SMTPAuth   = true;
        $mail->Username   = 'cyftech2024@gmail.com'; // SMTP kullanıcı adı
        $mail->Password   = 'udpm uxck oyvj zwyx'; // SMTP şifresi
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;
        $mail->CharSet = 'UTF-8';  // Karakter setini UTF-8 yap

        // Gönderici bilgisi
        $mail->setFrom($mail_address, 'içerik Başvuru');
        $mail->addReplyTo($mail_address, $name);
        
      // Alıcı bilgisi
        $mail->addAddress("cyftech2024@gmail.com"); // Formdan gelen e-posta adresi

        // Mail içeriği
        $mail->isHTML(true);
        $mail->Subject = 'Yeni içerik Talebi';
        $mail->Body    = "
            <h2>Yeni Reklam Talebi</h2>
            <p><b>Ad:</b> $name</p>
            <p><b>Firma:</b> $firm</p>
            <p><b>E-posta:</b> $mail_address</p>
            <p><b>Telefon:</b> $phone</p>
            <p><b>Kullanılacak Alan:</b> $alan</p>
            <p><b>Reklam Detayı:</b> $detay</p>
            <p><b>Not:</b> $not</p>
        ";

        // Mail gönderme
        $mail->send();
        echo '1';
    } catch (Exception $e) {
        echo "Mail gönderilemedi. Hata: {$mail->ErrorInfo}";
    }
} else {
    echo 'Geçersiz istek.';
}
?>
