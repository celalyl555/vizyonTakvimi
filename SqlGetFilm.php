<?php

try {
    // SQL sorgusu
    $sql = "SELECT f.film_adi, f.id, f.vizyon_tarihi, f.bitis_tarihi, f.filmsure, f.film_konu, f.kapak_resmi, 
    COALESCE(GROUP_CONCAT(DISTINCT ft.filmturu SEPARATOR ', '), '') AS filmturleri, 
    COALESCE(GROUP_CONCAT(DISTINCT s.studyoad SEPARATOR ', '), '') AS studyolar,
    COALESCE(GROUP_CONCAT(DISTINCT sd.dagitimad SEPARATOR ', '), '') AS dagitim,
    COALESCE(GROUP_CONCAT(DISTINCT sd.iddagitim SEPARATOR ', '), '') AS dagitim_id,
    COALESCE(GROUP_CONCAT(DISTINCT u.country_name SEPARATOR ', '), '') AS ulkeler,
    COALESCE(GROUP_CONCAT(DISTINCT g.resim_yolu SEPARATOR ', '), '') AS resimler,
    COALESCE(GROUP_CONCAT(DISTINCT CONCAT(o.adsoyad, ' (', k.kategoriAd, ')', ' (ID: ', o.idoyuncu, ')', ' (Resim: ', o.resimyol, ')', ' (SEO URL: ', o.seo_url, ')') SEPARATOR ', '), '') AS oyuncular
    FROM filmler f
    JOIN film_filmturu fft ON f.id = fft.film_id
    JOIN filmturleri ft ON fft.filmturu_id = ft.idfilm
    LEFT JOIN film_dagitim fd ON f.id = fd.film_id
    LEFT JOIN sinemadagitim sd ON fd.dagitim_id = sd.iddagitim
    LEFT JOIN film_studyolar fs ON f.id = fs.film_id
    LEFT JOIN stüdyo s ON fs.studyo_id = s.id
    LEFT JOIN film_ulkeler fu ON f.id = fu.film_id
    LEFT JOIN ulke u ON fu.ulke_id = u.id
    LEFT JOIN film_galeri g ON f.id = g.film_id
    LEFT JOIN oyuncuiliski ol ON f.id = ol.film_id
    LEFT JOIN oyuncular o ON ol.oyuncu_id = o.idoyuncu
    LEFT JOIN kategori k ON ol.kategori_id = k.idKategori
    WHERE f.id = :film_id
    GROUP BY f.id";

    $stmt = $con->prepare($sql);
    $stmt->execute(['film_id' => $param]);
    $filmler2 = $stmt->fetch(PDO::FETCH_ASSOC);

    // Eğer film verisi bulunamazsa hata mesajı göster
    if (!$filmler2) {
        echo "Film bulunamadı.";
    }

    // Ekstra sorgular
    $stmt = $con->prepare('SELECT * FROM filmveriler WHERE film_id = :film_id');
    $stmt->execute(['film_id' => $param]);
    $veriler2 = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $stmt = $con->prepare('SELECT * FROM filmsalon WHERE film_id = :film_id');
    $stmt->execute(['film_id' => $param]);
    $salonlar = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    // Hata mesajını yakala ve ekrana yazdır
    echo "Hata: " . $e->getMessage();
}

// Oyuncular verisini işleme
if (isset($filmler2['oyuncular'])) {
    $oyuncuString = $filmler2['oyuncular'];

    // Eğer oyuncu verisi string ise, explode ile parçala
    if (is_string($oyuncuString)) {
        $oyuncular = explode(', ', $oyuncuString);
    } else {
        $oyuncular = $oyuncuString; // Zaten dizi ise doğrudan kullan
    }

    // Kategorilere göre oyuncuları ayırmak için dizi
    $kategoriOyuncular = [
        'Yönetmen' => [],
        'Senaryo Yazarı' => [],
        'Görüntü Yönetmeni' => [],
        'Müzik' => [],
        'Kurgu' => [],
        'Aktör' => []
    ];

    // Oyuncuları kategorilere göre ayırma işlemi
    foreach ($oyuncular as $oyuncuKategori) {
        if (preg_match('/^(.*?)\s*\((.*?)\)\s*\(ID:\s*(\d+)\)\s*\(Resim:\s*(.*?)\)\s*\(SEO URL:\s*(.*?)\)$/', $oyuncuKategori, $matches)) {
            $oyuncuAd = trim($matches[1]);
            $kategori = trim($matches[2]);
            $oyuncuId = trim($matches[3]);
            $resimYol = trim($matches[4]);
            $seoUrl = trim($matches[5]);

            // Kategori dizisine oyuncuyu ekle
            if (isset($kategoriOyuncular[$kategori])) {
                $kategoriOyuncular[$kategori][] = [
                    'adsoyad' => $oyuncuAd,
                    'id' => $oyuncuId,
                    'resimyol' => $resimYol,
                    'seo_url' => $seoUrl
                ];
            }
        }
    }
}

// Eğer $kategoriOyuncular boşsa her bir kategoriye boş bir dizi ata
$yonetmenler = isset($kategoriOyuncular['Yönetmen']) ? $kategoriOyuncular['Yönetmen'] : [];
$senaryolar = isset($kategoriOyuncular['Senaryo Yazarı']) ? $kategoriOyuncular['Senaryo Yazarı'] : [];
$GörüntüYönetmeni = isset($kategoriOyuncular['Görüntü Yönetmeni']) ? $kategoriOyuncular['Görüntü Yönetmeni'] : [];
$Müzik = isset($kategoriOyuncular['Müzik']) ? $kategoriOyuncular['Müzik'] : [];
$Kurgu = isset($kategoriOyuncular['Kurgu']) ? $kategoriOyuncular['Kurgu'] : [];
$Oyuncu = isset($kategoriOyuncular['Aktör']) ? $kategoriOyuncular['Aktör'] : [];

?>
