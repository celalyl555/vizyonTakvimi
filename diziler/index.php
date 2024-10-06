<?php 
include('../admin/conn.php');
include('../header.php');
include('../SqlQueryDizi.php');
?>
<!-- 51, 94 yusufun yapacağı yerler. -->
    <!-- ============================================================================== -->
    
    <!-- Table Area Start -->

    <section class="haftaSection">

        <div class="haftaMain">

            <h2><i class="fa-solid fa-couch"></i> Diziler</h2>
            <p>Diziler ile ilgili aradığınız herşey burada.</p>
            
        </div>

    </section>

    <!-- Table Area End -->

    <!-- ============================================================================== -->

    <!-- vizyon Area End -->

    <section>
        <div class="vizyon">
        
            <div id="vizyondaYeni" class="tabcontent">
            <!-- 2 hafta öncesine kadar -->
                <div class="vizyonSlier">
                    <div class="vizyonLeft">
                        <button class="arrows left"><i class="fa-solid fa-caret-left"></i></button>
                        <?php
                        if (!empty($enEskiDizi)): ?>
                        <a href="diziler/dizi-detay/<?php echo $enEskiDizi['seo_url']; ?>" class="mainvizyonImg">
                            <img src="kapakfoto/<?php echo $enEskiDizi['kapak_resmi'];?>" alt="vizyon">
                            <div class="overlay1">
                            <span class="namevizyon">
                                <?php 
                                // Film adı boşsa veya null ise "boş" mesajını göster
                                if (!empty($enEskiDizi['film_adi'])) {
                                    echo $enEskiDizi['film_adi'];
                                } else {
                                    // echo 'yusuf burayı da doldursana';
                                }
                                ?>
                            </span>
                            </div>
                        </a>
                        <?php endif; ?>

                        <button class="arrows right"><i class="fa-solid fa-caret-right"></i></button>
                    </div>
                    <div class="vizyonRight">
                    <?php foreach ($dizilerVizyon as $dizi): ?>
                        <a href="diziler/dizi-detay/<?php echo $dizi['seo_url']; ?>" class="vizyonBox">
                            <div class="vizyonBoxImg">
                                <img src="kapakfoto/<?php echo $dizi['kapak_resmi'];?>" alt="">
                            </div>
                            <h3><?php echo $dizi['film_adi'];?></h3>
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

            <h2><i class="fa-solid fa-couch"></i> Diziler'den Haberler</h2>

            <div class="newsInside">

                <div class="newsLeft">
                <?php foreach ($haberler as $haber) :?>
                    <a href="haberler/haber-detay/<?php echo $haber['seo_url']; ?>" class="newsBox">
                        <div class="newsBoxImg">
                            <img src="haberfoto/<?php echo $haber['haberfoto']; ?>" alt="haberfoto/<?php echo $film['haberfoto']; ?>">
                        </div>
                        <div>
                            <p><i class="fa-solid fa-hourglass-half"></i> <?php echo formatDateTime($haber['tarih']); ?></p>
                            <h3><?php echo $haber['baslik']; ?></h3>
                        </div>
                    </a>
                <?php endforeach; ?>
                    
                    <div class="pageBtn">
                        <button class="pageBtns deactivePage"><i class="fa-solid fa-angles-left"></i></button>
                        <button class="pageBtns activePage">1</button>
                        <button class="pageBtns activePage">2</button>
                        <button class="pageBtns activePage">3</button>
                        <button class="pageBtns activePage"><i class="fa-solid fa-angles-right"></i></button>
                    </div>
                    
                </div>

                <div class="newsRight">
                    <div class="seyirci">
                        <div class="dateArea1">
                            <h3><i class="fa-solid fa-stopwatch"></i> En Çok İzlenenler</h3>
                        </div>
                        <ul class="list">
                            <?php 
                            $i = 1; // Sayaç başlatılıyor
                            foreach ($filmVerileri as $film): 
                            ?>
                            <li>
                                <a href="diziler/dizi-detay/<?php echo $film['seo_url']; ?>" class="aling-center">
                                    <span><?php echo $i++;?></span>
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
    <?php include('../footer.php');?>

</body>
</html>