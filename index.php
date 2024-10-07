
<?php 
include('header.php');
include('admin/conn.php');
include('generate_vapid.php');
include('SqlQueryFilm.php');
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
                    <p><i class="fa-regular fa-calendar"></i> 06-08 Eylül 2024</p>
                </div>
                <div class="tabs">
                    <button class="tab-button active" onclick="openTab1(event, 'seyirci')">Seyirci</button>
                    <button class="tab-button" onclick="openTab1(event, 'hasilat')">Hasılat</button>
                </div>
    
                <div class="tab-content" id="seyirci">
                    <ul class="list">
                        <?php 
                        $sayacSeyirci = 1;
                        foreach ($filmVerileri as $film):
                            if ($sayacSeyirci > 5) break; // Sadece ilk 5 kaydı göstermek için döngüyü kır
                        ?>
                        <li>
                            <a href="filmler/film-detay/<?php echo $film['seo_url']?>">
                                <span><?php echo $sayacSeyirci++?></span>
                                <div class="infInside">
                                    <p><?php echo $film['film_adi']?></p>
                                    <div class="rowIns">
                                        <div>
                                            <p>Hafta Sonu</p>
                                            <div class="rowIns2">
                                                <i class="fa-regular fa-user"></i>
                                                <p>32.457</p>
                                            </div>
                                        </div>
                                        <div class="endTxt">
                                            <p>Toplam</p>
                                            <div class="rowIns2">
                                                <i class="fa-regular fa-user"></i>
                                                <p><?php echo $film['toplamkisi']?></p>
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
                        foreach ($filmVerileri as $film):
                            if ($sayacHasilat > 5) break; // Sadece ilk 5 kaydı göstermek için döngüyü kır
                        ?>
                        <li>
                            <a href="filmler/film-detay/<?php echo $film['seo_url']?>">
                                <span> <?php echo $sayacHasilat++ ?></span>
                                <div class="infInside">
                                    <p><?php echo $film['film_adi']?></p>
                                    <div class="rowIns">
                                        <div>
                                            <p>Hafta Sonu</p>
                                            <div class="rowIns2">
                                                <i class="fa-regular fa-user"></i>
                                                <p>32.457</p>
                                            </div>
                                        </div>
                                        <div class="endTxt">
                                            <p>Toplam</p>
                                            <div class="rowIns2">
                                                <i class="fa-regular fa-user"></i>
                                                <p><?php echo $film['toplamhasilat']?></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                <a href="hafta/haftalar.php" class="tumu">Tümü <i class="fa-solid fa-caret-right"></i></a>
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