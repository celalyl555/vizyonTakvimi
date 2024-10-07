<?php
include('../admin/conn.php');
include('../header.php'); 
include('../SqlQueryHaber.php');



try {
    $sql = "SELECT f.*,
    GROUP_CONCAT(DISTINCT ftur.filmturu SEPARATOR ', ') AS filmturleri,
    GROUP_CONCAT(DISTINCT s.dagitimad SEPARATOR ', ') AS dagitimlar,
    GROUP_CONCAT(DISTINCT st.studyoad SEPARATOR ', ') AS stüdyolar
    FROM 
        filmler f 
    LEFT JOIN 
        film_filmturu ft ON f.id = ft.film_id
    LEFT JOIN 
        filmturleri ftur ON ft.filmturu_id = ftur.idfilm
    LEFT JOIN 
        film_dagitim d ON f.id = d.film_id
    LEFT JOIN 
        sinemadagitim s ON d.dagitim_id = s.iddagitim
    LEFT JOIN 
        film_studyolar fs ON f.id = fs.film_id
    LEFT JOIN 
        stüdyo st ON fs.studyo_id = st.id
    WHERE 
        f.statu = 1  
    GROUP BY 
        f.id;";

    $stmt = $con->prepare($sql);
    
    $stmt->execute();
    
    $sonuclar = $stmt->fetchAll(PDO::FETCH_ASSOC);

    usort($sonuclar, function ($a, $b) {
        return $b['topHasilat'] <=> $a['topHasilat']; // azalan sıralama
    });
  
    // İlk 5 filmi yeni bir diziye aktarın
    $ilkBesFilmHasilat = array_slice($sonuclar, 0, 4);
    

    usort($sonuclar, function ($a, $b) {
        return $b['topKisi'] <=> $a['topKisi']; // azalan sıralama
    });

    $ilkBesFilmKisi = array_slice($sonuclar, 0, 4);
   
 


    $filtrelenmisFilmler = array_filter($sonuclar, function ($film) {
        return $film['topKisi'] > 1000000; // 1 milyondan fazla olan filmleri seç
    });
    
 
    
    usort($filtrelenmisFilmler, function ($a, $b) {
        return $b['topHasilat'] <=> $a['topHasilat']; 
    });
    
    
    print_r($filtrelenmisFilmler);




} catch (PDOException $e) {
    echo "Hata: " . $e->getMessage();
}





?>

<!-- ============================================================================== -->

<!-- Table Area Start -->

<section class="haftaSection">

    <div class="haftaMain">

        <h2><i class="fa-solid fa-box-open"></i> Gişe Hasılatı Tüm Zamanlar</h2>
        <p>İzlenme, gişe hasılatı ve ödüller gibi farklı kategorilerde tüm filmler için rekor listeleri.</p>

    </div>

</section>

<section class="pt-0">

    <div class="news">

        <div class="newsInside">

            <div class="newsLeft">
                <div class="containerAy">
                    <div class="tab-content-hafta">
                        <div class="month">

                            <h3 class="infoYear"><span><i class="fa-solid fa-box-open"></i> Seyirci Rekorları - Top 5</span></h3>

                            <?php foreach( $ilkBesFilmKisi as $kisiler ){  ?>
                            <div class="tum-zamanlar">

                                <div class="tumBox">
                                    <div class="tumBoxLeft">
                                        <a href="filmler/film-detay/<?php echo $kisiler['seo_url']; ?>"
                                            class="tumBoxLeftImg">
                                            <img src="kapakfoto/<?php echo $kisiler['kapak_resmi']; ?>" alt="">
                                        </a>
                                        <div class="gap-10">
                                            <a
                                                href="filmler/film-detay/<?php echo $kisiler['seo_url']; ?>"><?php echo $kisiler['film_adi']; ?></a>
                                            <a href="" class="titledagitim"><?php echo $kisiler['dagitimlar']; ?></a>
                                            <p><i class="fa-regular fa-clock"></i>
                                                <?php echo formatDate($kisiler['vizyon_tarihi']); ?></p>
                                        </div>
                                    </div>
                                    <div>
                                        <p class="title1">Rekor</p>
                                        <p><strong><?php echo $kisiler['topKisi']; ?><i class="fa-solid fa-user-group"></i></strong></p>
                                    </div>
                                </div>

                            </div>

                            <?php }  ?>

                            <h3 class="infoYear"><span><i class="fa-solid fa-box-open"></i> Toplam Gişe Rekorları - Top
                                    5</span></h3>


                            <?php foreach( $ilkBesFilmHasilat as $hasiliat ){  ?>
                            <div class="tum-zamanlar">

                                <div class="tumBox">
                                    <div class="tumBoxLeft">
                                        <a href="filmler/film-detay/<?php echo $hasiliat['seo_url']; ?>"
                                            class="tumBoxLeftImg">
                                            <img src="kapakfoto/<?php echo $hasiliat['kapak_resmi']; ?>" alt="">
                                        </a>
                                        <div class="gap-10">
                                            <a
                                                href="filmler/film-detay/<?php echo $hasiliat['seo_url']; ?>"><?php echo $hasiliat['film_adi']; ?></a>
                                            <a href="" class="titledagitim"><?php echo $hasiliat['dagitimlar']; ?></a>
                                            <p><i class="fa-regular fa-clock"></i>
                                                <?php echo formatDate($hasiliat['vizyon_tarihi']); ?></p>
                                        </div>
                                    </div>
                                    <div>
                                        <p class="title1">Rekor</p>
                                        <p><strong><?php echo number_format($hasiliat['topHasilat'], 2) . ' ₺'; ?></strong></p>
                                    </div>
                                </div>

                            </div>

                            <?php }  ?>



                            <h3 class="infoYear"><span><i class="fa-solid fa-box-open"></i> Milyonu Aşan Filmler</span>
                            </h3>
                            <?php foreach( $filtrelenmisFilmler as $filmkisi ){  ?>
                            <div class="tum-zamanlar">

                                <div class="tumBox">
                                    <div class="tumBoxLeft">
                                        <a href="filmler/film-detay/<?php echo $filmkisi['seo_url']; ?>"
                                            class="tumBoxLeftImg">
                                            <img src="kapakfoto/<?php echo $filmkisi['kapak_resmi']; ?>" alt="">
                                        </a>
                                        <div class="gap-10">
                                            <a
                                                href="filmler/film-detay/<?php echo $filmkisi['seo_url']; ?>"><?php echo $filmkisi['film_adi']; ?></a>
                                            <a href="" class="titledagitim"><?php echo $filmkisi['dagitimlar']; ?></a>
                                            <p><i class="fa-regular fa-clock"></i>
                                                <?php echo formatDate($filmkisi['vizyon_tarihi']); ?></p>
                                        </div>
                                    </div>
                                    <div>
                                        <p class="title1">Rekor</p>
                                        <p><strong><?php  echo $filmkisi['topKisi'] ; ?><i class="fa-solid fa-user-group"></i></strong></p>
                                    </div>
                                </div>

                            </div>
                            <?php }  ?>



                        </div>

                    </div>

                </div>

            </div>

            <div class="newsRight bgnone">
                <h2><i class="fa-solid fa-newspaper"></i> Güncel Haberler</h2>

                <?php foreach( $haberler3 as $haber ){  ?>
                <a href="haberler/haber-detay/<?php echo $haber['seo_url']; ?>" class="newsBoxHafta">
                    <div class="haftaImg">
                        <img src="haberfoto/<?php echo $haber['haberfoto']; ?>" alt="">
                    </div>
                    <p><?php echo $haber['baslik']; ?></p>
                    <p class="date"><i class="fa-regular fa-clock"></i> <?php echo formatDateTime($haber['tarih']); ?></p>
                </a>
                <?php }  ?>
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
    
    include('../footer.php');?>

</body>

</html>