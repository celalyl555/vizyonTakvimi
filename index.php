
<?php 
include('header.php');
include('admin/conn.php');
include('generate_vapid.php');
include('SqlQueryFilm.php');

$hafta = date('W', strtotime('last Friday'));
$yil = date('Y');

// Haftanın başlangıcını ve bitişini hesapla
$haftaBaslangic = new DateTime();
$haftaBaslangic->setISODate($yil, $hafta+1, 5); // Haftanın son günü (Cuma)
$haftaBaslangic->modify('-1 days'); // Haftanın son günü (Cuma)
$haftaBitis = clone $haftaBaslangic;
$haftaBitis->modify('-6 days'); // Haftanın ilk günü (Cumartesi bir hafta öncesi)

// Cumartesi ve pazar günleri için tarih aralığı oluştur
$cumartesi = new DateTime();
$cumartesi->setISODate($yil, $hafta, 6); // Cumartesi
$pazar = new DateTime();
$pazar->setISODate($yil, $hafta, 7); // Pazar

// Tarih formatlarını ayarla
$endDateFormatted = $haftaBaslangic->format('Y-m-d');
$startDateFormatted = $haftaBitis->format('Y-m-d');
$cumartesiFormatted = $cumartesi->format('Y-m-d');
$pazarFormatted = $pazar->format('Y-m-d');


$sql = "
    SELECT
        fv.film_id,
        MAX(fv.max_toplamHasilat) AS hafta_hasilat,
        MAX(fv.max_toplamKisi) AS hafta_seyirci,
        MAX(fv.sinema) AS salon_sayisi,
        FLOOR(vizyon.toplam_hafta / 7) AS hafta_vizyon,
        MAX(fv.max_toplamHasilat) AS toplam_hasilat,
        MAX(fv.max_toplamKisi) AS toplam_seyirci,
        f.*,  
        sd.*,
        f.seo_url AS f_seo_url,
        (SELECT MAX(toplamKisi) FROM filmveriler WHERE tarih BETWEEN :cumartesi AND :pazar AND film_id = fv.film_id) AS haftasonu_topkisi,
        (SELECT MAX(toplamHasilat) FROM filmveriler WHERE tarih BETWEEN :cumartesi AND :pazar AND film_id = fv.film_id) AS haftasonu_tophasilat
    FROM (
        SELECT
            tarih,
            WEEK(tarih, 5) AS hafta,
            MAX(toplamKisi) AS max_toplamKisi,
            MAX(toplamHasilat) AS max_toplamHasilat,
            MAX(sinema) AS sinema,
            film_id
        FROM
            filmveriler
        WHERE 
            tarih BETWEEN :startDate AND :endDate
        GROUP BY
            tarih, film_id
    ) AS fv
    LEFT JOIN (
        SELECT
            film_id,
            COUNT(film_id) AS toplam_hafta
        FROM
            filmveriler
        GROUP BY
            film_id
    ) AS vizyon ON fv.film_id = vizyon.film_id
    LEFT JOIN filmler f ON fv.film_id = f.id
    LEFT JOIN film_dagitim fd ON fv.film_id = fd.film_id
    LEFT JOIN sinemadagitim sd ON fd.dagitim_id = sd.iddagitim
    GROUP BY
        fv.film_id
    ORDER BY
        fv.hafta ASC
";

// Sorguyu hazırlama ve parametreleri bağlama
$stmt = $con->prepare($sql);
$stmt->bindParam(':startDate', $startDateFormatted);
$stmt->bindParam(':endDate', $endDateFormatted);
$stmt->bindParam(':cumartesi', $cumartesiFormatted);
$stmt->bindParam(':pazar', $pazarFormatted);

// Sorguyu çalıştır
$stmt->execute();
$filmler = $stmt->fetchAll(PDO::FETCH_ASSOC);

$topSeyirci = 0;
$topHasilat = 0;
$topFilm = 0;
$haftasonuTopKisi = 0;
$haftasonuTopHasilat = 0;

foreach($filmler as $film){
    $topHasilat += $film['hafta_hasilat'];
    $topSeyirci += $film['hafta_seyirci'];
    $haftasonuTopKisi += $film['haftasonu_topkisi'];
    $haftasonuTopHasilat += $film['haftasonu_tophasilat'];
    $topFilm++;
}

// Haftasonu seyirci ve hasılat sonuçlarını ekrana yazdır
echo "Haftasonu Toplam Seyirci: " . $haftasonuTopKisi . "<br>";
echo "Haftasonu Toplam Hasılat: " . $haftasonuTopHasilat . "<br>";


// Ay isimlerini İngilizceden Türkçeye çevirme
$englishMonth = date('M'); // Bulunduğumuz ayın İngilizce kısa adını alır

// İngilizce ayların Türkçe karşılıkları
$months = [
    'Jan' => 'Ocak',
    'Feb' => 'Şubat',
    'Mar' => 'Mart',
    'Apr' => 'Nisan',
    'May' => 'Mayıs',
    'Jun' => 'Haziran',
    'Jul' => 'Temmuz',
    'Aug' => 'Ağustos',
    'Sep' => 'Eylül',
    'Oct' => 'Ekim',
    'Nov' => 'Kasım',
    'Dec' => 'Aralık'
];

// Türkçe karşılığını bulmak
$turkishMonth = $months[$englishMonth];

// Başlangıç ve bitiş günlerini ayarla
$basday = date('d', strtotime($startDateFormatted)); // Başlangıç günü
$bitday = date('d', strtotime($endDateFormatted)); // Bitiş günü
$yil = date('Y', strtotime($startDateFormatted)); // Yıl

// Tarihi oluşturuyoruz
$tar = $basday . " - " . $bitday . " " . $turkishMonth . " " . $yil;








$currentDate = date('Y-m-d');

// Kullanıcının çerezi kontrol et
if (!isset($_COOKIE['user_visited'])) {
    // Veritabanında bu tarihle bir kayıt var mı kontrol et
    $stmt = $con->prepare("SELECT * FROM user_visits WHERE visit_date = :visit_date");
    $stmt->execute(['visit_date' => $currentDate]);
    $visit = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($visit) {
        // Eğer kayıt varsa kullanıcı sayısını artır
        $newCount = $visit['user_count'] + 1;
        $updateStmt = $con->prepare("UPDATE user_visits SET user_count = :user_count WHERE visit_date = :visit_date");
        $updateStmt->execute(['user_count' => $newCount, 'visit_date' => $currentDate]);
    } else {
        // Eğer kayıt yoksa yeni bir kayıt oluştur
        $insertStmt = $con->prepare("INSERT INTO user_visits (visit_date, user_count) VALUES (:visit_date, 1)");
        $insertStmt->execute(['visit_date' => $currentDate]);
    }

    // Kullanıcının ziyaret ettiğini belirten bir çerez ayarla
    setcookie('user_visited', '1', time() + (86400 * 1), "/"); // 1 gün boyunca geçerli
}

// Kullanıcı sayısını veritabanından al
$stmt = $con->prepare("SELECT * FROM user_visits WHERE visit_date = :visit_date");
$stmt->execute(['visit_date' => $currentDate]);
$visit = $stmt->fetch(PDO::FETCH_ASSOC);

// Günlük kullanıcı sayısını yazdır
echo "Bugün siteyi ziyaret eden kullanıcı sayısı: " . $visit['user_count'];







?>

    <!-- ============================================================================== -->
    <!-- Main Area  Start -->

    <main>

        <div class="container">
            <!-- Sol resim alanı -->
            <div class="gallery">

                <a href="haberler/haber-detay/<?php echo $haberlerGenel[0]['seo_url']; ?>" class="image-container hero">
                    <img src="haberfoto/<?php echo $haberlerGenel[0]['haberfoto']; ?>" class="hero">
                    <div class="overlay">
                        <span class="category"><?php echo $haberlerGenel[0]['baslik']; ?></span>
                       
                    </div>
                </a>
        
                <a href="haberler/haber-detay/<?php echo $haberlerGenel[1]['seo_url']; ?>" class="image-container hero2 img-radius">
                    <img src="haberfoto/<?php echo $haberlerGenel[1]['haberfoto']; ?>" class="img-radius">
                    <div class="overlay">
                        <span class="category"><?php echo $haberlerGenel[1]['baslik']; ?></span>
                       
                    </div>
                </a>
        
                <a href="haberler/haber-detay/<?php echo $haberlerGenel[2]['seo_url']; ?>" class="image-container img-radius3">
                    <img src="haberfoto/<?php echo $haberlerGenel[2]['haberfoto']; ?>" class="img-radius3">
                    <div class="overlay">
                        <span class="category"><?php echo $haberlerGenel[2]['baslik']; ?></span>
                     
                    </div>
                </a>
        
                <a href="haberler/haber-detay/<?php echo $haberlerGenel[3]['seo_url']; ?>" class="image-container img-radius2">
                    <img src="haberfoto/<?php echo $haberlerGenel[3]['haberfoto']; ?>" class="img-radius2">
                    <div class="overlay">
                        <span class="category"><?php echo $haberlerGenel[3]['baslik']; ?></span>
                      
                    </div>
                </a>
            </div>
    
            <!-- Sağ sekmeli alan -->
            <div class="tab-section">
                <div class="dateArea">
                    <p><i class="fa-regular fa-calendar"></i> <?php echo $tar;  ?></p> 
                </div>
                <div class="tabs">
                    <button class="tab-button active" onclick="openTab1(event, 'seyirci')">Seyirci</button>
                    <button class="tab-button" onclick="openTab1(event, 'hasilat')">Hasılat</button>
                </div>
    
                <div class="tab-content" id="seyirci">
                    <ul class="list">
                        <?php 
                        $sayacSeyirci = 1;
                        foreach ($filmler as $film):
                            if ($sayacSeyirci > 5) break; // Sadece ilk 5 kaydı göstermek için döngüyü kır
                        ?>
                        <li>
                        <a href="filmler/film-detay/<?php echo $film['f_seo_url']?>">
    <span><?php echo $sayacSeyirci++?></span>
    <div class="infInside">
        <p><?php echo isset($film['film_adi']) ? $film['film_adi'] : '-'; ?></p>
        <div class="rowIns">
            <div>
                <p>Hafta Sonu</p>
                <div class="rowIns2">
                    <i class="fa-regular fa-user"></i> 
                    <p>
                        <?php 
                            echo isset($film['haftasonu_topkisi']) 
                                ? number_format($film['haftasonu_topkisi'], 0, ',', '.') 
                                : '-'; 
                        ?>
                    </p>
                </div>
            </div>
            <div class="endTxt">
                <p>Toplam</p>
                <div class="rowIns2">
                    <i class="fa-regular fa-user"></i>
                    <p>
                        <?php 
                            echo isset($film['hafta_seyirci']) 
                                ? number_format($film['hafta_seyirci'], 0, ',', '.') 
                                : '-'; 
                        ?>
                    </p>
                </div>
            </div>
            
        </div>
    </div>
</a>

                        </li>
                        <?php endforeach; ?>
                    </ul>                    
                </div>
    
                <div class="tab-content" id="hasilat" style="display:none;">
                    <ul class="list">
                        <?php 
                        $sayacHasilat = 1;
                        foreach ($filmler as $film):
                            if ($sayacHasilat > 5) break; // Sadece ilk 5 kaydı göstermek için döngüyü kır
                        ?>
                        <li>
                        <a href="filmler/film-detay/<?php echo $film['f_seo_url']?>">
    <span><?php echo $sayacHasilat++ ?></span>
    <div class="infInside">
        <p><?php echo isset($film['film_adi']) ? $film['film_adi'] : '-'; ?></p>
        <div class="rowIns">
            <div>
                <p>Hafta Sonu</p>
                <div class="rowIns2">
                <i class="fa-solid fa-box"></i>
                    <p>
                        <?php 
                            echo isset($film['haftasonu_tophasilat']) 
                                ? number_format($film['haftasonu_tophasilat'], 2, ',', '.') . ' ₺' 
                                : '-'; 
                        ?>
                    </p>
                </div>
            </div>
            <div class="endTxt">
                <p>Toplam</p>
                <div class="rowIns2">
                <i class="fa-solid fa-box"></i>
                    <p>
                        <?php 
                            echo isset($film['hafta_hasilat']) 
                                ? number_format($film['hafta_hasilat'], 2, ',', '.') . ' ₺' 
                                : '-'; 
                        ?>
                    </p>
                </div>
            </div>
        </div>
    </div>
</a>

                        </li>
                        <?php endforeach; ?>
                     
                    </ul>
                </div>
                <a href="hafta/haftalar/<?php echo $hafta.'-'.$yil; ?>" class="tumu">Tümü <i class="fa-solid fa-caret-right"></i></a>
            </div>
        </div>

    </main>

    <!-- Main Area End -->
    
    <!-- ============================================================================== -->
    
    <!-- vizyon Area End -->
   



  
    <section>
        <div class="vizyon">

            <div class="tabs2">
                <button class="tablinks active" onclick="openTab2(event, 'vizyondaYeni')"><i class="fa-solid fa-ticket"></i> Vizyonda Yeni</button><!-- vizyondan öncesi 2 hafta öncesi-->
                <button class="tablinks" onclick="openTab2(event, 'yakinda')"><i class="fa-solid fa-clock-rotate-left"></i> Yakında</button><!-- vizyondan öncesi 2 hafta sonrası-->
            </div>
        
            <div id="vizyondaYeni" class="tabcontent">
                <?php

                ?>
                <div class="vizyonSlier">
                    <div class="vizyonLeft">
                        <button class="arrows left"><i class="fa-solid fa-caret-left"></i></button>

                        <?php if (!empty($enEskiFilm)): ?>
                            <a href="filmler/film-detay/<?php echo $enEskiFilm['seo_url'];?>" class="mainvizyonImg">
                                <img src="kapakfoto/<?php echo $enEskiFilm['kapak_resmi']; ?>" alt="vizyon"> <!-- İlgili film resmini kullan -->
                                <div class="overlay1">
                                    <span class="namevizyon"><?php echo $enEskiFilm['film_adi']; ?></span>
                                </div>
                            </a>
                        <?php endif; ?>

                        <button class="arrows right"><i class="fa-solid fa-caret-right"></i></button>
                    </div>
                    <div class="vizyonRight">
                        <?php foreach ($filmlerVizyon as $film): ?>
                            <a href="filmler/film-detay/<?php echo $film['seo_url'];?>" class="vizyonBox">
                                <div class="vizyonBoxImg">
                                    <img src="kapakfoto/<?php echo $film['kapak_resmi']; ?>" alt="<?php echo $film['id']; ?>">
                                </div>
                                <h3><?php echo $film['film_adi']; ?></h3>
                            </a>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php
                
                ?>
            </div>
        
            <div id="yakinda" class="tabcontent" style="display:none;">
                <div class="vizyonSlier">
                    <div class="vizyonLeft">
                        <button class="arrows left"><i class="fa-solid fa-caret-left"></i></button>
                        <?php if (!empty($enYeniFilm)): ?>
                            <a href="filmler/film-detay/<?php echo $enYeniFilm['seo_url'];?>" class="mainvizyonImg">
                                <img src="kapakfoto/<?php echo $enYeniFilm['kapak_resmi'];?>" alt="vizyon">
                                <div class="overlay1">
                                    <span class="namevizyon"><?php echo $enYeniFilm['film_adi'];  ?></span>
                                    <p><?php echo formatDate($enYeniFilm['vizyon_tarihi']); ?></p>
                                </div>
                            </a>
                            
                        <?php endif; ?>
                        <button class="arrows right"><i class="fa-solid fa-caret-right"></i></button>
                    </div>
                    <div class="vizyonRight">
                    <?php foreach ($filmlerYakin as $film): ?>
                         <a href="filmler/film-detay/<?php echo $film['seo_url'];?>" class="vizyonBox">
                            <div class="vizyonBoxImg">
                                <img src="kapakfoto/<?php echo $film['kapak_resmi']; ?>" alt="<?php echo $film['id']; ?>">
                            </div>
                            <div>
                                <h3><?php echo $film['film_adi']; ?></h3>
                                <p><?php echo formatDate($film['vizyon_tarihi']); ?></p>
                            </div>
                        </a>
                    <?php endforeach; ?>
                    </div>
                </div>
            </div>

        </div>
    </section>

    <!-- vizyon Area End -->
    
    <!-- ============================================================================== -->
     
    <!-- News Area End -->
    <section>
  
        <div class="news">

            <h2><i class="fa-solid fa-newspaper"></i> Haberler</h2>

            <div class="newsInside">

                <div class="newsLeft">
                    <!-- foreach 4 tane olacak en güncel 4 taneyi göster  -->
                    <?php foreach ($haberler as $haber) { ?>
                        <a href="haberler/haber-detay/<?= $haber['seo_url']; ?>" class="newsBox">
                            <div class="newsBoxImg">
                                <img src="haberfoto/<?php echo $haber['haberfoto']; ?>" alt="haberfoto/<?php echo $film['haberfoto']; ?>" >
                            </div>
                            <div>
                                <p><i class="fa-solid fa-hourglass-half"></i> <?php echo formatDateTime($haber['tarih']); ?></p>
                                <h3><?php echo $haber['baslik']; ?></h3>
                            </div>
                        </a>
                    <?php } ?>
                    <!-- kapanış -->
                    <a href="haberler" class="tumuBtn">Tüm Haberler <i class="fa-solid fa-right-long"></i></a>
                </div>
                <div class="newsRight">
                    <div class="seyirci">
                        <div class="dateArea1">
                            <h3><i class="fa-solid fa-stopwatch"></i> En Çok İzlenenler</h3> <!-- en çok izlenen ilk 20 tane film (film verileri)-->
                        </div>
                        <ul class="list">
                        <?php 
                        $i = 1;
                        foreach ($encokizlenenfilmler as $film): 
                        ?>
                            <li>
                                <a href="filmler/film-detay/<?php echo $film['seo_url'];?>" class="align-center">
                                    <span><?php  echo $i++; ?></span> <!-- Film ID'si -->
                                    <div class="infInside">
                                        <p><?php echo $film['film_adi'];?></p>
                                    </div>
                                    <span><i class="fa-solid fa-caret-right"></i></span>
                                </a>
                            </li>
                        <?php endforeach; ?>
                        </ul>                    
                    </div>
                </div>
            
            </div>

        </div>
    </section>

    <!-- News Area End -->

    <!-- ============================================================================== -->
<!-- sonradan eklenen fonksiyonlar (fatih kayacı) -->
 
<?php 
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
}?>
    <?php include('footer.php');?>
</body>
</html>