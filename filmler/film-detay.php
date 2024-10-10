<?php 
include('../admin/conn.php');
include('../header.php');
include('../SqlQueryHaber.php');
$seourl = isset($_GET['url']) ? $_GET['url'] : '';


try {
    // 'filmler' ve 'filmsalon' tablolarını birleştirerek verileri al
    $sql = "
        SELECT f.id AS film_id, f.seo_url, s.*
        FROM filmler f
        JOIN filmsalon s ON f.id = s.film_id
        WHERE f.seo_url = :seo_url
    ";

    $stmt = $con->prepare($sql);
    $stmt->bindParam(':seo_url', $seourl);
    $stmt->execute();
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $salonSayisi = count($rows);
 
    $sql = "
    SELECT 
        fv2.tarih,
        fv2.toplamhasilat,
        fv2.toplamkisi
    FROM 
        filmler f
    JOIN 
        filmveriler fv2 ON f.id = fv2.film_id
    WHERE 
        f.seo_url = :seourl
    ORDER BY 
        fv2.tarih ASC
    ";
    
    $stmt = $con->prepare($sql);
    $stmt->execute(['seourl' => $seourl]);
    
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    $toplamHasilat = 0; // toplamhasilat için başlangıç değeri
    $toplamKisi = 0;    // toplamkisi için başlangıç değeri
    
    $büyükDeğerhasilat =0; 
    $büyükKisi=0;
    
    foreach ($results as $result) {
        if ($result['toplamhasilat'] > $toplamHasilat) {
            $toplamHasilat = $result['toplamhasilat'];
        }
        
        // toplamkisi sütunundaki en büyük değeri bul
        if ($result['toplamkisi'] > $toplamKisi) {
            $toplamKisi = $result['toplamkisi'];
        }
    }
    
    

    usort($results, function($a, $b) {
        return strtotime($a['tarih']) - strtotime($b['tarih']);
    });
    
    $cumartesitophasilat=0;
    $cumartesitopkisi=0;
    $pazartophasilat=0;
    $pazartopkisi=0;
    foreach ($results as $result){
        $datetime = new DateTime($result['tarih']);
        $gun = $datetime->format('w');
        if ($gun == 6) {
            $cumartesitophasilat=$result['toplamhasilat'];
            $cumartesitopkisi=$result['toplamkisi'];
            break;
        }  
    }
    foreach ($results as $result){
        $datetime = new DateTime($result['tarih']);

        $gun = $datetime->format('w');

        if ($gun == 0) {
            $pazartophasilat=$result['toplamhasilat'];
            $pazartopkisi=$result['toplamkisi'];
            break;
        }  
    }

 
    if ($cumartesitophasilat > $pazartophasilat) {
        $büyükDeğerhasilat = $cumartesitophasilat;
    } else {
        $büyükDeğerhasilat = $pazartophasilat;
    }
   
    if ($pazartopkisi > $cumartesitopkisi) {
        $büyükKisi = $pazartopkisi;
    } else {
        $büyükKisi = $cumartesitopkisi;
    }

    echo $büyükKisi."    .". $büyükDeğerhasilat;





    
} catch (PDOException $e) {
    echo "Sorgu hatası: " . $e->getMessage();
}




    // Veritabanından haber bilgilerini al
    $sql = "SELECT id FROM filmler WHERE seo_url = :haberId";
    $stmt = $con->prepare($sql);
    $stmt->bindParam(':haberId', $seourl);
    
    // Sorguyu çalıştır
    $stmt->execute();
    
    // Sonuçları al
    $haber = $stmt->fetch(PDO::FETCH_ASSOC);
    $param =  $haber['id'];
    include('../SqlGetFilm.php');
  
   

?>
<!-- ============================================================================== -->

<!-- Table Area Start -->

<section class="haftaSection">

    <div class="haftaMain">

        <h2><?php echo $filmler2['film_adi'];?></h2>
        <p><i class="fa-regular fa-calendar color1"></i> <?php echo formatDate($filmler2['vizyon_tarihi']);?> <?php if (isset($filmler2['filmsure']) && $filmler2['filmsure'] > 0) {
    echo '<i class="fa-regular fa-clock color1"></i> ' . dakikaToSaat($filmler2['filmsure']); } ?>
        </p>

    </div>

</section>

<!-- Table Area End -->

<!-- ============================================================================== -->

<!-- Main Area Start -->

<main class="mt-0 mh-0">

    <div class="container">

        <!-- Sag resim alanı -->
        <div class="galleryMovie">
            <div class="image-container heroMovie" onclick="openModal(0)">
                <img src="kapakfoto/<?php echo $filmler2['kapak_resmi']?>" class="heroMovie">
            </div>

            <?php
// Örnek string ifadesi

// Virgül ile ayır ve diziye ata
$resimler = explode(', ', $filmler2['resimler']);

// Görsel sınıflarını tanımla
$classes = ['heroMovie1', 'img-radiusx', 'img-radius2x'];

// İlk 3 resmi yazdır
for ($i = 1; $i <= 3; $i++) {
    echo '<div class="image-container ' . $classes[$i - 1] . '" onclick="openModal(' . $i . ')">';
    echo '<img src="galeri/' . $resimler[$i - 1] . '" class="' . $classes[$i - 1] . '">';
    
    // Son resim için "Daha Fazla" metni ve simgesi ekle
    if ($i == 3) {
        echo '<div class="plusImg">';
        echo '<p>Daha Fazla</p>';
        echo '<i class="fa-solid fa-plus"></i>';
        echo '</div>';
    }
    
    echo '</div>';
}
?>


        </div>

        <!-- Modal -->
        <div id="imageModal" class="modal">
            <span class="close" onclick="closeModal()">&times;</span>
            <img class="modal-content" id="modalImg">
            <a class="prev" onclick="changeSlide(-1)">&#10094;</a>
            <a class="next" onclick="changeSlide(1)">&#10095;</a>

            <!-- Thumbnail images below the modal -->
            <div class="thumbnail-container">
                <img class="thumbnail" src="kapakfoto/<?php echo $filmler2['kapak_resmi']?>" draggable="false"
                    onclick="currentSlide(0)">
                <?php 
                    for ($j = 0; $j < count($resimler); $j++) { // Dizi boyutu kadar döngü
                        echo '<img class="thumbnail" src="galeri/' . $resimler[$j] . '" draggable="false" onclick="currentSlide(' . ($j + 1) . ')" />';
                    }
                ?>
            </div>
        </div>

    </div>

</main>

<!-- Main Area End -->

<!-- ============================================================================== -->

<!-- News Area End -->

<section class="pt-0">

    <div class="news">

        <div class="newsInside">

            <div class="newsLeft">

                <div class="movieInfo">
                    <?php if (!empty($filmler2['filmturleri'])): ?>
                    <div>
                        <p class="titleMovie">Filmin Türü</p>
                        <p><?php echo $filmler2['filmturleri']; ?></p>
                    </div>
                    <?php endif; ?>

                    <?php if (!empty($yonetmenler)) { ?>
                    <div>
                        <p class="titleMovie">Yönetmen</p>
                        <p><i class="fa-solid fa-clapperboard"></i>
                            <?php 
                                
                                    $adSoyadlar = array_column($yonetmenler, 'adsoyad'); 
                                    echo implode(', ', $adSoyadlar); ?>



                        </p>
                    </div>
                    <?php  }  ?>



                    <?php if (!empty($senaryolar)) { ?>
                    <div>
                        <p class="titleMovie">Senaryo</p>
                        <p><i class="fa-solid fa-pen-nib"></i>
                            <?php 
          
                $adSoyadlar = array_column($senaryolar, 'adsoyad'); // 'adsoyad' sütununu al
                echo implode(', ', $adSoyadlar); // Senaryo yazarlarını virgülle ayırarak yazdır
                ?>
                        </p>
                    </div>
                    <?php } 
                ?>


                    <div class="row-btw">
                        <?php if (!empty($GörüntüYönetmeni)) {  ?>
                        <div>
                            <p class="titleMovie">Görüntü Yönetmeni</p>
                            <p><i class="fa-solid fa-video"></i>
                                <?php 
                                        if (!empty($GörüntüYönetmeni)) { 
                                            $adSoyadlar = array_column($GörüntüYönetmeni, 'adsoyad'); 
                                            echo implode(', ', $adSoyadlar); 
                                        } 
                                ?>
                            </p>
                        </div>
                        <?php } ?>

                        <?php if (!empty($Kurgu)) {  ?>
                        <div>
                            <p class="titleMovie">Kurgu</p>
                            <p><i class="fa-solid fa-circle-half-stroke"></i>
                                <?php 
                                    
                                        $adSoyadlar = array_column($Kurgu, 'adsoyad'); // 'adsoyad' sütununu al
                                        echo implode(', ', $adSoyadlar); // Kurgu adlarını virgülle ayırarak yazdır
                                    
                                ?>
                            </p>
                        </div>
                        <?php } ?>

                    </div>















                    <?php  if (!empty($filmler2['vizyon_tarihi'])): ?>
                    <div class="row-btw">
                        <div>
                            <p class="titleMovie">Vizyon Tarihi</p>
                            <p><i class="fa-regular fa-calendar"></i>
                                <?php echo formatDate($filmler2['vizyon_tarihi']); ?></p>
                        </div>
                        <?php endif; ?>

                        <?php if (!empty($filmler2['studyolar'])): ?>
                        <div>
                            <p class="titleMovie">Stüdyo</p>
                            <p><?php echo $filmler2['studyolar']; ?></p>
                        </div>
                    </div>
                    <?php endif; ?>

                    <?php if (!empty($filmler2['dagitim'])): ?>
                    <div class="row-btw">
                        <div>
                            <p class="titleMovie">Sinema Dağıtım</p>
                            <p><?php echo $filmler2['dagitim']; ?></p>
                        </div>
                        <?php endif; ?>

                        <?php if (!empty($filmler2['ulkeler'])): ?>
                        <div>
                            <p class="titleMovie">Ülke</p>
                            <p><?php echo $filmler2['ulkeler']; ?></p>
                        </div>
                    </div>
                    <?php endif; ?>

                    <?php if (!empty($Müzik)): ?>
                    <div>
                        <p class="titleMovie">Müzik</p>
                        <p><i class="fa-solid fa-circle-half-stroke"></i>
                            <?php 
                               
                                     $adSoyadlar = array_column($Müzik, 'adsoyad'); // 'adsoyad' sütununu al
                                     echo implode(', ', $adSoyadlar); // Kurgu adlarını virgülle ayırarak yazdır
                                 
                            ?>
                        </p>
                    </div>
                    <?php endif; ?>
                </div>


                <div class="newsTextArea mt-1">
                    <h2><i class="fa-solid fa-video"></i> Filmin Konusu</h2>
                    <?php if (!empty($filmler2['film_konu'])): ?>
                    <p><?php echo $filmler2['film_konu'];  ?></p>
                    <?php endif; ?>


                    <?php 
                    if( $salonSayisi>0){ include('salon.php');  }
                   
                    
                    
                    ?>



                    <h2><i class="fa-solid fa-user-tie"></i> Filmin Kadrosu</h2>



                    <div class="oyuncular">

                        <?php   foreach ($Oyuncu as $oyuncu) {
                           echo '<a href="kisiler/kisi-detay/' . $oyuncu['seo_url'] . '">';
                           echo '<img src="foto/' . $oyuncu['resimyol'] . '" alt="">';
                           echo '<p class="titleMovie">' . $oyuncu['adsoyad'] . '</p>';
                           echo '</a>';
                        }  ?>


                    </div>








                </div>

            </div>

            <div class="newsRight bgnone">
            <?php
// Değerlerin kontrolü
if (!empty($büyükKisi) || !empty($toplamKisi) || !empty($büyükDeğerhasilat) || !empty($toplamHasilat)) {
    ?>
                <div class="movieInfo">
                    <h3><i class="fa-solid fa-box"></i> Gişe Özeti</h3>
                    <div class="tab-content w-100">
                        <ul class="list">
                            <li>
                                <div>
                                    <div class="infInside">
                                        <p class="titleMovie">SEYİRCİ</p>
                                        <div class="rowIns">
                                            <div>
                                                <p>İlk Hafta Sonu</p>
                                                <div class="rowIns2">
                                                    <i class="fa-regular fa-user"></i>
                                                    <p><?php echo $büyükKisi ? number_format($büyükKisi, 0, ',', '.') : '-'; ?>
                                                    </p>
                                                </div>
                                            </div>
                                            <div class="endTxt">
                                                <p>Toplam</p>
                                                <div class="rowIns2">
                                                    <i class="fa-regular fa-user"></i>
                                                    <p><?php echo $toplamKisi ? number_format($toplamKisi, 0, ',', '.') : '-'; ?>
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li>

                            <li>
                                <div>
                                    <div class="infInside mt-1">
                                        <p class="titleMovie">HASILAT</p>
                                        <div class="rowIns">
                                            <div>
                                                <p>İlk Hafta Sonu</p>
                                                <div class="rowIns2">
                                                    <i class="fa-solid fa-money-bill"></i>
                                                    <p><?php echo $büyükDeğerhasilat ?  number_format($büyükDeğerhasilat, 2, ',', '.').' ₺ '  : '-'; ?>
                                                    </p>
                                                </div>
                                            </div>
                                            <div class="endTxt">
                                                <p>Toplam</p>
                                                <div class="rowIns2">
                                                    <i class="fa-solid fa-money-bill"></i>
                                                    <p><?php echo $toplamHasilat ?  number_format($toplamHasilat, 2, ',', '.').' ₺ ' : '-'; ?>
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        </ul>

                    </div>
                </div>
                <?php } ?>
                <h2><i class="fa-solid fa-newspaper"></i> Güncel Haberler</h2>
                <?php
                                foreach ($haberler as $haber) {
                    echo '<a href="haberler/haber-detay/' . $haber['seo_url'] . '" class="newsBoxHafta">';
                    echo '    <div class="haftaImg">';
                    echo '        <img src="haberfoto/' . $haber['haberfoto'] . '" alt="">';
                    echo '    </div>';
                    echo '    <p>' . $haber['baslik'] . '</p>';
                    echo '    <p class="date"><i class="fa-regular fa-clock"></i> ' . formatDateTime($haber['tarih']) . '</p>';
                    echo '</a>';
                }
                ?>


            </div>

        </div>

    </div>
</section>

<!-- News Area End -->

<?php
    function formatDateTime($dateTimeString) {
        // Ay isimlerini tanımla
        $months = [
            "Ocak", "Şubat", "Mart", "Nisan", "Mayıs", "Haziran",
            "Temmuz", "Ağustos", "Eylül", "Ekim", "Kasım", "Aralık"
        ];
    
        // Tarih ve saati ayır
        $dateTimeParts = explode(" ", $dateTimeString);
        $dateParts = explode("-", $dateTimeParts[0]);
        $timeParts = explode(":", $dateTimeParts[1]);
    
        $year = $dateParts[0];
        $month = (int)$dateParts[1] - 1; // Aylar 0-11 arasında indekslenir
        $day = (int)$dateParts[2];
    
        $hour = (int)$timeParts[0];
        $minute = (int)$timeParts[1];
    
        // Formatlanmış tarihi ve saati döndür
        return $day . ' ' . $months[$month] . ' ' . $year . ' ' . sprintf('%02d', $hour) . ':' . sprintf('%02d', $minute);
    }
    
    function formatDate($dateString) {
        // Ay isimlerini tanımla
        $months = [
            "Ocak", "Şubat", "Mart", "Nisan", "Mayıs", "Haziran",
            "Temmuz", "Ağustos", "Eylül", "Ekim", "Kasım", "Aralık"
        ];
    
        // Tarih parçalarını ayır
        $dateParts = explode("-", $dateString);
        $year = $dateParts[0];
        $month = (int)$dateParts[1] - 1; // Aylar 0-11 arasında indekslenir
        $day = (int)$dateParts[2];
    
        // Formatlanmış tarihi döndür
        return $day . ' ' . $months[$month] . ' ' . $year;
    }
    
 
function dakikaToSaat($dakika) {
    // Saat ve dakika hesaplama
    $saat = floor($dakika / 60); // Tam saat
    $dk = $dakika % 60; // Kalan dakika

    // Sonucu döndür
    return sprintf("%2ds %02ddk", $saat, $dk);
}



    
    include('../footer.php');?>
</body>

</html>