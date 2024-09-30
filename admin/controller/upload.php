<?php
if (isset($_FILES['upload'])) {
    $file = $_FILES['upload'];
    $uploadDirectory = '../../habericerik/';

    // Klasör yoksa oluştur
    if (!is_dir($uploadDirectory)) {
        mkdir($uploadDirectory, 0755, true);
    }

    // Dosya yolunu belirle
    $filePath = $uploadDirectory . basename($file['name']);

    // Dosya yükleme işlemi sırasında bir hata varsa kontrol et
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
        $url = '/habericerik/' . basename($file['name']);
        echo json_encode(array('url' => $url));
    } else {
        echo json_encode(array(
            'error' => array(
                'message' => 'Dosya kaydedilirken bir hata oluştu.'
            )
        ));
    }
}
?>
