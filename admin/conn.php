<?php  

$DATABASE_HOST = '185.92.2.129';
$DATABASE_USER = 'roott';
$DATABASE_PASS = 'S2Ukh3jTsd_4mHug';
$DATABASE_NAME = 'vizyontakvimi';

try {
    // PDO bağlantısı oluşturma
    $con = new PDO("mysql:host=$DATABASE_HOST;dbname=$DATABASE_NAME", $DATABASE_USER, $DATABASE_PASS);
    
    // Hata raporlama modunu ayarlama
    $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
 
} catch (PDOException $e) {
    // Bağlantı hatası durumunda hata mesajı
    exit('Failed to connect to MySQL: ' . $e->getMessage());
}

?>
