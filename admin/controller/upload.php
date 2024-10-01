<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (isset($_FILES['upload'])) {
    $file = $_FILES['upload'];
    $uploadDirectory = '../../habericerik/';

    // Klasör yoksa oluştur
    if (!is_dir($uploadDirectory)) {
        mkdir($uploadDirectory, 0755, true);
    }

    $filePath = $uploadDirectory . basename($file['name']);

    // Dosya yükleme sırasında bir hata varsa kontrol et
    if ($file['error'] !== UPLOAD_ERR_OK) {
        echo json_encode(array(
            'error' => array(
                'message' => 'Dosya yüklenirken bir hata oluştu: ' . $file['error']
            )
        ));
        exit;
    }

    // Dosyayı kaydet
    if (move_uploaded_file($file['tmp_name'], $filePath)) {
        $url = 'http://localhost/vizyontakvimi/habericerik/' . basename($file['name']);
        echo json_encode(array(
            'uploaded' => 1, 
            'url' => $url
        ));
    } else {
        echo json_encode(array(
            'uploaded' => 0,
            'error' => array(
                'message' => 'Dosya kaydedilirken bir hata oluştu.'
            )
        ));
    }
}    
?>
