<?php
session_start();
// Veritabanı bağlantısını içerir
include('conn.php');

try {
    // Kullanıcı adı ile veritabanından kullanıcıyı sorgulama
    $stmt = $con->prepare('SELECT id, password FROM accounts WHERE username = :username');
    // Parametreyi bağla
    $stmt->bindParam(':username', $_POST['username'], PDO::PARAM_STR);
    $stmt->execute();

    // Sonuçları kontrol et
    if ($stmt->rowCount() > 0) {
        // Sonuçları al
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $id = $row['id'];
        $password = $row['password'];

        // Şifre doğrulama
        if (password_verify($_POST['password'], $password)) {
            // Giriş başarılı, session oluşturma
            session_regenerate_id(true); // Güvenlik için yeni session ID oluştur
            $_SESSION['loggedin'] = TRUE;
            $_SESSION['name'] = htmlspecialchars($_POST['username']); // Kullanıcı adını güvenli hale getir
            $_SESSION['id'] = $id;

            // Giriş başarılı yönlendirme
            header('Location: home');
            exit(); // Yönlendirmeden sonra scripti sonlandır
        } else {
            // Yanlış şifre
            echo 'Yanlış kullanıcı adı veya şifre!';
        }
    } else {
        // Kullanıcı adı yanlış
        echo 'Yanlış kullanıcı adı veya şifre!';
    }
} catch (PDOException $e) {
    // Hata yakalama
    exit('Veritabanı hatası: ' . $e->getMessage());
}
?>
