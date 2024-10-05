<?php
require 'vendor/autoload.php';

// Kullanıcı tanımlı fonksiyonlar
function generateRandomKey($length = 32) {
    return base64_encode(random_bytes($length));
}


    // Rastgele anahtarları oluştur
    $privateKey = '46UdAORoCqSTsFg3eFXwGDM/5nn1hAFRbhC6zInSZZY='; // 32 byte uzunluğunda
    $publicKey = 'HdXC0VmBThzoDatOHOr5JL2WQcpFOBMkLH+I2G5tVwI=';  // 32 byte uzunluğunda
    echo "<script>
    var publicKey = '{$publicKey}';
   
  </script>";
    echo "Public Key: " . $publicKey . PHP_EOL;
    echo "Private Key: " . $privateKey . PHP_EOL;

?>

