<?php
// Bugünden itibaren 7 gün
$bugun = new DateTime();
$gunler = [];
$gunler_turkce = ['Pazar', 'Pazartesi', 'Salı', 'Çarşamba', 'Perşembe', 'Cuma', 'Cumartesi'];
$aylar_turkce = [
    1 => 'Ocak', 
    2 => 'Şubat', 
    3 => 'Mart', 
    4 => 'Nisan', 
    5 => 'Mayıs', 
    6 => 'Haziran', 
    7 => 'Temmuz', 
    8 => 'Ağustos', 
    9 => 'Eylül', 
    10 => 'Ekim', 
    11 => 'Kasım', 
    12 => 'Aralık'
];

for ($i = 0; $i < 7; $i++) {
    $tarih = clone $bugun;
    $tarih->modify("+$i day");
    
    $gun_index = $tarih->format('w'); // Haftanın günü, 0 = Pazar, 1 = Pazartesi, ..., 6 = Cumartesi
    $ay_index = (int)$tarih->format('n'); // Ay numarası
    $gunler[] = [
        'gun' => $gunler_turkce[$gun_index],
        'tarih' => $tarih->format('d') . ' ' . $aylar_turkce[$ay_index] // Tarih formatı: gün ay (Türkçe)
    ];
}




$salonlar = "";

// İlk olarak, id parametresi varsa sorguyu çalıştır
if (isset($_GET['id']) && !empty($_GET['id'])) {
    // Ana SQL sorgusu
    $sql = "
        SELECT f.id AS film_id, f.seo_url, s.*
        FROM filmler f
        JOIN filmsalon s ON f.id = s.film_id
        WHERE f.seo_url = :seo_url
        AND s.id = :salon_id
    ";

    // Parametreler
    $params = [
        ':seo_url' => $seourl,
        ':salon_id' => $_GET['id']
    ];

    // Tarih parametresi varsa kullan, yoksa bugünün tarihini al
    $tarih = isset($_GET['tarih']) && !empty($_GET['tarih']) ? $_GET['tarih'] : date('Y-m-d');
echo "işlenen tarih : ". $tarih;
echo "işlenen id : ". $tarih;
    // SQL sorgusuna tarih filtresini ekle
    $sql .= " AND :tarih BETWEEN s.bas_tarih AND s.bit_tarih";
    $params[':tarih'] = $tarih;

    // Sorguyu hazırlayıp çalıştır
    $stmt = $con->prepare($sql);
    $stmt->execute($params);

    // Sonucu al
    $salonlar = $stmt->fetchAll(PDO::FETCH_ASSOC);
} 

// Eğer $row boşsa, city parametresi ile yeni bir sorgu yap
if (empty($salonlar) && isset($_GET['city']) && !empty($_GET['city'])) {
    // Ana SQL sorgusu
    $sql = "
        SELECT f.id AS film_id, f.seo_url, s.*
        FROM filmler f
        JOIN filmsalon s ON f.id = s.film_id
        WHERE f.seo_url = :seo_url
        AND s.sehir = :city
    ";

    // Parametreler
    $params = [
        ':seo_url' => $seourl,
        ':city' => $_GET['city']
    ];

    // Tarih parametresi varsa kullan, yoksa bugünün tarihini al
    $tarih = isset($_GET['tarih']) && !empty($_GET['tarih']) ? $_GET['tarih'] : date('Y-m-d');

    // SQL sorgusuna tarih filtresini ekle
    $sql .= " AND :tarih BETWEEN s.bas_tarih AND s.bit_tarih";
    $params[':tarih'] = $tarih;

    // Sorguyu hazırlayıp çalıştır
    $stmt = $con->prepare($sql);
    $stmt->execute($params);

    // Sonucu al
    $salonlar = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

   

?>




<div class="search-containerx">
    <label for="search">Filme ait <span class="cinema-count"><?php echo $salonSayisi; ?> adet</span> sinema bulundu</label>
    <input type="text" id="search" oninput="filterCinemas()" placeholder="Şehir veya Sinema Adı Yazınız.">
    <ul id="cinema-list" class="dropdown-list">

    <?php 
        // Şehirleri toplamak için geçici bir dizi oluşturuyoruz
        $sehirler = array();
        foreach ($rows as $row) {
            if (!in_array($row['sehir'], $sehirler)) {
                // Eğer şehir daha önce eklenmediyse dizimize ekliyoruz
                $sehirler[] = $row['sehir'];
        ?>
            <li class="cinema-item" data-id="<?php echo $row['sehir']; ?>">           
                <span class="cinema-name"><i class="fa-solid fa-city"></i>  <?php echo htmlspecialchars($row['sehir']); ?></span>
            </li>
        <?php 
            }
        }
        ?>

        <?php foreach ($rows as $row): ?>
            <li class="cinema-item" data-id="<?php echo $row['id']; ?>">           
                <span class="cinema-name"> <i class="fa-solid fa-clapperboard"></i>  <?php echo htmlspecialchars($row['sinema']); ?></span>
            </li>
        <?php endforeach; ?>
        
       
    </ul>
</div>




<div class="yearSelect responsDays">
    <?php 
    $today = new DateTime();
    foreach ($gunler as $key => $gun): ?>
        <a href="filmler/film-detay/<?php echo $seourl;echo (isset($_GET['id']) && !empty($_GET['id'])) ? "?id=" . $_GET['id'] : (isset($_GET['city']) && !empty($_GET['city']) ? "?city=" . $_GET['city'] : "");
 ?>?tarih=<?php echo $today->format('Y-m-d'); ?>" class="col-center yearBtn <?php echo $key === 0 ? 'active' : 'activex'; ?>">
            <p><?php echo $gun['gun']; ?></p>
            <p><?php echo $gun['tarih']; ?></p>
        </a>
    <?php 
$today->modify('+1 day');
endforeach; ?>
</div>

            <?php foreach ($salonlar as $salon){?>
                    <div class="cinema-container">
                        <div class="cinema-item">
                            <div class="cinema-header" onclick="toggleDetails(this)">
                                <div class="cinema-info">
                                    <h2> <?php echo $salon['sinema'];  ?> </h2>
                                    
                                </div>
                                <div class="toggle-icon"><i class="fa-solid fa-caret-down"></i></div>
                            </div>
                            <div class="cinema-details">
                                <p><?php echo $salon['format']. ", ".$salon['dil'];  ?></p>
                                <div class="showtimes">
                               
                                 <?php if(!empty($salon['seans1'])){?> <button><?php echo formatTime($salon['seans1']) ?></button><?php } ?>
                                 <?php if(!empty($salon['seans2'])){?> <button><?php echo formatTime($salon['seans2']) ?></button><?php } ?>
                                 <?php if(!empty($salon['seans3'])){?> <button><?php echo formatTime($salon['seans3']) ?></button><?php } ?>
                                 <?php if(!empty($salon['seans4'])){?> <button><?php echo formatTime($salon['seans4']) ?></button><?php } ?>
                                 <?php if(!empty($salon['seans5'])){?> <button><?php echo formatTime($salon['seans5']) ?></button><?php } ?>
                                 <?php if(!empty($salon['seans6'])){?> <button><?php echo formatTime($salon['seans6']) ?></button><?php } ?>
                                </div>
                            </div>
                        </div>
                    </div>
            <?php } function formatTime($time) {
    // Geçerli bir zaman olup olmadığını kontrol et
    if (DateTime::createFromFormat('H:i:s', $time) !== false) {
        // Saat ve dakikayı ayır
        $hour = date('H', strtotime($time)); // Saat
        $minute = date('i', strtotime($time)); // Dakika

        // İstediğiniz formatta birleştir
        return $hour . '.' . substr($minute, 0, 2); // 21.45 formatı
    } else {
        // Geçersiz zaman durumu
        return "Geçersiz zaman formatı";
    }
}?>      
                   
            