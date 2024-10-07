<?php
include('../admin/conn.php');
include('../header.php');
include('../SqlQueryHaber.php');


// seourl parametresini al
$seourl = isset($_GET['url']) ? $_GET['url'] : '';





try {
    // Veritabanından haber bilgilerini al
    $sql = "SELECT * FROM haberler WHERE seo_url = :haberId";
    $stmt = $con->prepare($sql);
    $stmt->bindParam(':haberId', $seourl);
    
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
                    <p><i class="fa-regular fa-clock color1"></i> <?php echo formatDateTime($haber['tarih']);  ?></p>

                    <?php echo $haber['icerik'];  ?>
                </div>

            </div>

            <div class="newsRight bgnone">
                <h2><i class="fa-solid fa-newspaper"></i> Güncel Haberler</h2>

                    <?php foreach ($haberlerGenel as $haber): 
                        if($seourl!= $haber['seo_url']){?>
                            <a href="haberler/haber-detay/<?php echo $haber['seo_url']; ?>" class="newsBoxHafta" data-id="<?php echo $haber['idhaber']; ?>">
                                <div class="haftaImg">
                                    <img src="haberfoto/<?php echo $haber['haberfoto']; ?>"
                                        alt="<?php echo htmlspecialchars($haber['baslik']); ?>">
                                </div>
                                <p><?php echo htmlspecialchars($haber['baslik']); ?></p>
                                <p class="date"><i class="fa-regular fa-clock"></i>
                                    <?php echo formatDateTime($haber['tarih']); ?></p>
                            </a>

                    <?php } endforeach; ?>


            </div>

        </div>

    </div>
</section>




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

include('../footer.php');?>
</body>

</html>