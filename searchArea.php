<?php 
include('header.php');  
include('admin/conn.php');
include('SqlQueryFilm.php');
if (isset($_GET['value'])) {
    // URL'deki value parametresini al ve %20 karakterlerini boşluk olarak decode et
    $searchValue = urldecode($_GET['value']);
    echo $searchValue;
    
    // Arama kelimesini küçük harfe çevir
    $searchValueLower = strtolower($searchValue);
    
    try {
        // Filmler tablosundan arama
        $filmSorgu = $con->prepare("SELECT * FROM filmler WHERE LOWER(film_adi) LIKE LOWER(:searchValue)");
        $filmSorgu->bindValue(':searchValue', '%' . $searchValueLower . '%'); // % ile birlikte bağla
        $filmSorgu->execute();
        $filmler = $filmSorgu->fetchAll(PDO::FETCH_ASSOC); // Filmler sonuçlarını diziye ata
    
        // Haberler tablosundan arama
        $haberSorgu = $con->prepare("SELECT * FROM haberler WHERE LOWER(baslik) LIKE LOWER(:searchValue)");
        $haberSorgu->bindValue(':searchValue', '%' . $searchValueLower . '%'); // % ile birlikte bağla
        $haberSorgu->execute();
        $haberler = $haberSorgu->fetchAll(PDO::FETCH_ASSOC); // Haberler sonuçlarını diziye ata
    
        // Oyuncular tablosundan arama
        $oyuncuSorgu = $con->prepare("SELECT * FROM oyuncular WHERE LOWER(adsoyad) LIKE LOWER(:searchValue)");
        $oyuncuSorgu->bindValue(':searchValue', '%' . $searchValueLower . '%'); // % ile birlikte bağla
        $oyuncuSorgu->execute();
        $oyuncular = $oyuncuSorgu->fetchAll(PDO::FETCH_ASSOC); // Oyuncular sonuçlarını diziye ata
    
        $statu1Count = 0; // Statusu 1 olan film sayısını tutacak sayaç
        $statu2Count = 0;
if (!empty($filmler)) {
    foreach ($filmler as $film) {
        // Eğer statu 1 ise sayacı artır
        if ( $film['statu'] == 1) {
            $statu1Count++;
        }else{
            $statu2Count++;
        }
    }
}
    
    } catch (PDOException $e) {
        echo "Veritabanı bağlantı hatası: " . $e->getMessage();
    }
}
?>




<!-- ============================================================================== -->

<!-- Table Area Start -->

<section class="haftaSection">

    <div class="haftaMain">

        <h2><i class="fa-solid fa-magnifying-glass"></i> "<?php echo $searchValue; ?>" İçin Arama Sonuçları</h2>

    </div>

</section>

<!-- Table Area End -->

<!-- ============================================================================== -->

<!-- News Area End -->

<section class="pt-0">

    <div class="news pt-0">

        <div class="newsInside">

            <div class="newsLeft">
                <!-- Film -->
                <?php
               
                if (!empty($filmler) &&  $statu1Count >0 ) { ?>

                    <h3>Filmler</h3>
                <?php foreach ($filmler as $film) { 
                    if($film['statu']==1){ 
                    ?>
                <a href="filmler/film-detay/<?php echo $film['seo_url']; ?>" class="newsBox">
                    <div class="newsBoxImg">
                        <img src="kapakfoto/<?php echo $film['kapak_resmi']; ?>" alt="">
                    </div>
                    <div>
                        <p><i class="fa-solid fa-hourglass-half"></i><?php echo formatDate($film['vizyon_tarihi']); ?></p>
                        <h3><?php echo $film['film_adi']; ?> </h3>
                    </div>
                </a>
                <?php
             }
            } ?>
                <?php } ?> 


                <!-- Dizi -->
                <?php if (!empty($filmler)  &&  $statu2Count >0  ) { ?>
                    <h3>Diziler</h3>
                <?php foreach ($filmler as $film) { 
                    if($film['statu']==2){ 
                    ?>
                <a href="filmler/film-detay/<?php echo $film['seo_url']; ?>" class="newsBox">
                    <div class="newsBoxImg">
                        <img src="kapakfoto/<?php echo $film['kapak_resmi']; ?>" alt="">
                    </div>
                    <div>
                        <p><i class="fa-solid fa-hourglass-half"></i><?php echo formatDate($film['vizyon_tarihi']); ?></p>
                        <h3><?php echo $film['film_adi']; ?> </h3>
                    </div>
                </a>
                <?php
             }
            } ?>
                <?php } ?>


                    <!-- Haber -->

                <?php if (!empty($haberler)) { ?>
                    <h3>Haberler</h3>
                <?php foreach ($haberler as $haber) { ?>
                <a href="haberler/haber-detay/<?php echo $haber['seo_url']; ?>" class="newsBox">
                    <div class="newsBoxImg">
                        <img src="haberfoto/<?php echo $haber['haberfoto']; ?>" alt="">
                    </div>
                    <div>
                        <p><i class="fa-solid fa-hourglass-half"></i><?php echo formatDateTime($haber['tarih']); ?></p>
                        <h3><?php echo $haber['baslik']; ?> </h3>
                    </div>
                </a>
                <?php } ?>
                <?php } ?>


                <?php if (!empty($oyuncular)) { ?>
                    <h3>Oyuncular</h3>
                <?php foreach ($oyuncular as $oyuncu) { ?>
                <a href="kisiler/kisi-detay/<?php echo $oyuncu['seo_url']; ?>" class="newsBox">
                    <div class="newsBoxImg">
                        <img src="foto/<?php echo $oyuncu['resimyol']; ?>" alt="">
                    </div>
                    <div>
                        <p><i class="fa-solid fa-hourglass-half"></i><?php echo formatDate($oyuncu['dogum']); ?></p>
                        <h3><?php echo $oyuncu['adsoyad']; ?> </h3>
                    </div>
                </a>
                <?php } ?>
                <?php } ?>
                

                <!-- <div class="pageBtn">
                    <button class="pageBtns deactivePage"><i class="fa-solid fa-angles-left"></i></button>
                    <button class="pageBtns activePage">1</button>
                    <button class="pageBtns activePage">2</button>
                    <button class="pageBtns activePage">3</button>
                    <button class="pageBtns activePage"><i class="fa-solid fa-angles-right"></i></button>
                </div> -->

            </div>

           
            <div class="newsRight bgnone">
                <h2><i class="fa-solid fa-newspaper"></i> Vizyona Girecekler</h2>
                <?php
                    foreach($filmlerGenelYakin as $yakinFilmler):?>
                <a href="filmler/film-detay/<?php echo $yakinFilmler['seo_url']; ?>" class="newsBoxHafta">
                    <div class="haftaImg">
                        <img src="kapakfoto/<?php echo $yakinFilmler['kapak_resmi'];?>" alt="">
                    </div>
                    <p><?php echo $yakinFilmler['film_adi'];?></p>
                    <p class="date"><i
                            class="fa-regular fa-clock"></i> <?php echo formatDate($yakinFilmler['vizyon_tarihi']);?></p>
                </a>
                <?php endforeach;?>
            </div>

        </div>

    </div>
</section>

<!-- News Area End -->

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
}


include('footer.php');?>

</body>

</html>