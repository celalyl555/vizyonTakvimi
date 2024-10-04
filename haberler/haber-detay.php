<?php
include('../admin/conn.php');
include('../header.php');

$haberId = 5;

try {
    // Veritabanından haber bilgilerini al
    $sql = "SELECT * FROM haberler WHERE idhaber = :haberId";
    $stmt = $con->prepare($sql);
    $stmt->bindParam(':haberId', $haberId, PDO::PARAM_INT);
    
    // Sorguyu çalıştır
    $stmt->execute();
    
    // Sonuçları al
    $haber = $stmt->fetch(PDO::FETCH_ASSOC);
    
    
} catch (PDOException $e) {
    echo "Veritabanı hatası: " . $e->getMessage();
}


?>

    <!-- ============================================================================== -->
    
    <!-- Table Area Start -->

    <section class="haftaSection">

        <div class="haftaMain">

            
            
        </div>

    </section>

    <!-- Table Area End -->

    <!-- ============================================================================== -->

    <!-- ============================================================================== -->
     
    <!-- News Area End -->

    <section class="pt-0">

        <div class="news">

            <div class="newsInside">

                <div class="newsLeft">
                    
                    <div class="newsTextArea">

                        <h2><?php echo $haber['baslik'];  ?></h2>
                        <p><i class="fa-regular fa-clock color1"></i> <?php echo $haber['tarih'];  ?></p>

                        <?php echo $haber['icerik'];  ?>
                    </div>
                    
                </div>

                <div class="newsRight bgnone">
                    <h2><i class="fa-solid fa-newspaper"></i> Güncel Haberler</h2>
                    <a href="" class="newsBoxHafta">
                        <div class="haftaImg">
                            <img src="assets/img/news/02.jpg" alt="">
                        </div>
                        <p>Üçlemenin finalini yapan Venom: Son Dans'tan yeni fragman yayınlandı</p>
                        <p class="date"><i class="fa-regular fa-clock"></i> 06 eylül 2024</p>
                    </a>
                    <a href="" class="newsBoxHafta">
                        <div class="haftaImg">
                            <img src="assets/img/news/02.jpg" alt="">
                        </div>
                        <p>Üçlemenin finalini yapan Venom: Son Dans'tan yeni fragman yayınlandı</p>
                        <p class="date"><i class="fa-regular fa-clock"></i> 06 eylül 2024</p>
                    </a>
                    <a href="" class="newsBoxHafta">
                        <div class="haftaImg">
                            <img src="assets/img/news/02.jpg" alt="">
                        </div>
                        <p>Üçlemenin finalini yapan Venom: Son Dans'tan yeni fragman yayınlandı</p>
                        <p class="date"><i class="fa-regular fa-clock"></i> 06 eylül 2024</p>
                    </a>
                    <a href="" class="newsBoxHafta">
                        <div class="haftaImg">
                            <img src="assets/img/news/02.jpg" alt="">
                        </div>
                        <p>Üçlemenin finalini yapan Venom: Son Dans'tan yeni fragman yayınlandı</p>
                        <p class="date"><i class="fa-regular fa-clock"></i> 06 eylül 2024</p>
                    </a>
                    <a href="" class="newsBoxHafta">
                        <div class="haftaImg">
                            <img src="assets/img/news/02.jpg" alt="">
                        </div>
                        <p>Üçlemenin finalini yapan Venom: Son Dans'tan yeni fragman yayınlandı</p>
                        <p class="date"><i class="fa-regular fa-clock"></i> 06 eylül 2024</p>
                    </a>
                </div>
            
            </div>

        </div>
    </section>

    <!-- News Area End -->

    <?php include('../footer.php');?>
</body>
</html>