<?php 
include('../admin/conn.php');  
include('../header.php');
include('../SqlQueryHaber.php');
//Tarih Ayarlamaları Kodu Başlangıç
$currentYear = date('Y'); 
$selectedYear = isset($_GET['year']) ? $_GET['year'] : $currentYear; 
$minYear = 2000;

$previousYear = ($selectedYear > $minYear) ? $selectedYear - 1 : $minYear;
$nextYear = $selectedYear < $currentYear ? $selectedYear + 1 : $currentYear;

// Tarih Ayarlamaları Kodu Bitti
try {
$sql = "
    SELECT 
        d.iddagitim,
        d.dagitimad,
        d.seo_url,
        COUNT(f.id) AS toplam_film_sayisi,
        SUM(f.topKisi) AS toplam_kisi,
        SUM(f.topHasilat) AS toplam_hasilat,
       
        ROUND(
            (SUM(f.topKisi) / (SELECT SUM(f2.topKisi) FROM filmler f2 WHERE YEAR(f2.vizyon_tarihi) = :selectedYear)) * 100, 2
        ) AS yuzde_payi,
        ROUND(
            (SUM(f.topHasilat) / (SELECT SUM(f2.topHasilat) FROM filmler f2 WHERE YEAR(f2.vizyon_tarihi) = :selectedYear)) * 100, 2
        ) AS yuzde_payi2,
        (SELECT SUM(f3.topHasilat) FROM filmler f3 WHERE YEAR(f3.vizyon_tarihi) = :selectedYear) AS toplam_hasilat_tum,
        (SELECT SUM(f4.topKisi) FROM filmler f4 WHERE YEAR(f4.vizyon_tarihi) = :selectedYear) AS toplam_kisi_tum
    FROM 
        sinemadagitim d
    LEFT JOIN 
        film_dagitim fd ON d.iddagitim = fd.dagitim_id
    LEFT JOIN 
        filmler f ON fd.film_id = f.id
    WHERE 
        YEAR(f.vizyon_tarihi) = :selectedYear AND f.statu = 1
    GROUP BY 
        d.iddagitim, d.dagitimad
";

$stmt = $con->prepare($sql);
$stmt->bindParam(':selectedYear', $selectedYear, PDO::PARAM_INT);
$stmt->execute();
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);


} catch (PDOException $e) {
    // Hata durumunda mesaj yazdır
    echo "Veritabanı hatası: " . $e->getMessage();
} catch (Exception $e) {
    // Diğer hatalar için genel yakalama
    echo "Bir hata oluştu: " . $e->getMessage();
}


$toplamHasilat=0;
$toplamKisi =0;
$toplamYeniFilm = 0;
$toplamizyonFilm = 0;
foreach($results as $row){
    $toplamHasilat += $row['toplam_hasilat'];
    $toplamKisi += $row['toplam_kisi'];
    $toplamizyonFilm += $row['toplam_film_sayisi'];
}




?>





<!-- ============================================================================== -->

<!-- Table Area Start -->

<section class="haftaSection">

    <div class="haftaMain">

        <h2><i class="fa-solid fa-box-open"></i> Dağıtımcılar Gişe Hasılatı</h2>
        <p>Türkiye'de faaliyet gösteren Dağıtımcılar için veriler</p>

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


                <!-- Tarih Ayarlamaları Kodu -->


                <div class="yearSelect">
                    <a href="dagitimci?year=<?= $previousYear; ?>"
                        class="yearBtn <?= ($selectedYear == $minYear) ? 'disabled' : 'activex'; ?>">
                        <i class="fa-solid fa-angles-left"></i> <?= $previousYear; ?>
                    </a>

                    <select name="centerBtn" id="centerBtn" class="centerBtn"
                        onchange="window.location.href='dagitimci?year=' + this.value;">
                        <?php for ($year = $currentYear; $year >= $minYear; $year--): ?>
                        <option value="<?= $year; ?>" <?= ($year == $selectedYear) ? 'selected' : ''; ?>><?= $year; ?>
                        </option>
                        <?php endfor; ?>
                    </select>

                    <a href="dagitimci?year=<?= $nextYear; ?>"
                        class="yearBtn <?= ($selectedYear >= $currentYear) ? 'disabled' : 'activex'; ?>">
                        <?= $nextYear; ?> <i class="fa-solid fa-angles-right"></i>
                    </a>
                </div>

                <!-- Tarih Ayarlamaları Kodu bitti-->



                <div class="containerAy">
                    <div class="tabs-hafta">
                        <button class="tab-button-hafta active" data-tab="seyirci">Seyirci</button>
                        <button class="tab-button-hafta" data-tab="hasilat">Hasılat</button>
                    </div>
                    <div class="tab-content-hafta" id="seyirci">

                        <div class="status">
                            <div class="statusBox">
                                <p class="title">Toplam Seyirci</p>
                                <p><strong><?php echo number_format($toplamKisi, 0, ',', '.'); ?></strong></p>
                            </div>
                           
                            <div class="statusBox">
                                <p class="title">Vizyondaki Film</p>
                                <p><strong><?php echo $toplamizyonFilm; ?></strong></p>
                            </div>
                        </div>

                        <div class="month">
                            <table class="mt-0">
                                <thead>
                                    <tr>
                                        <th>Dağıtımcı</th>
                                        <th>Toplam Seyirci</th>
                                        <th>Yüzde Payı</th>
                                       
                                        <th>Vizyondaki Film</th>
                                    </tr>
                                </thead>
                                <tbody>
                                
                                <?php foreach($results as $result){  
                                    
                                    ?>
                                    
                                    <tr>
                                        <td><a href="dagitimci/dagitimci-detay/<?php echo $result['seo_url']; ?>" class="clicka"><?php echo $result['dagitimad']; ?></a></td>
                                        <td><strong><?php echo !empty($result['toplam_kisi']) && $result['toplam_kisi'] != 0 ? number_format($result['toplam_kisi'], 0, ',', '.') : '-'; ?></strong></td>
                                        <td>% <?php echo !empty($result['yuzde_payi']) && $result['yuzde_payi'] != 0 ? $result['yuzde_payi'] : '0'; ?></td>
                                        <td><?php echo !empty($result['toplam_film_sayisi']) && $result['toplam_film_sayisi'] != 0 ? $result['toplam_film_sayisi'] : '-'; ?></td>
                                    </tr>

                                 
                                <?php } ?>

                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="tab-content-hafta hidden" id="hasilat">

                        <div class="status">
                            <div class="statusBox">
                                <p class="title">Toplam Hasılat</p>
                                <p><strong><?php echo !empty($toplamHasilat) && $toplamHasilat != 0 ? '₺ ' . number_format($toplamHasilat, 2, ',', '.') : '-';?></strong></p>
                            </div>
                            <div class="statusBox">
                                
                               
                            </div>
                            <div class="statusBox">
                                <p class="title">Vizyondaki Film</p>
                                <p><strong><?php echo $toplamizyonFilm; ?></strong></p>
                            </div>
                        </div>

                        <div class="month">
                            <table class="mt-0">
                                <thead>
                                    <tr>
                                        <th>Dağıtımcı</th>
                                        <th>Toplam Hasılat</th>
                                        <th>Yüzde Payı</th>
                                       
                                        <th>Vizyondaki Film</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php foreach($results as $result){  
                                    
                                    ?>
                                    
                                    <tr>
                                        <td><a href="dagitimci/dagitimci-detay/<?php echo $result['seo_url']; ?>" class="clicka"><?php echo $result['dagitimad']; ?></a></td>
                                        <td><strong><?php  echo !empty($result['toplam_hasilat']) && $result['toplam_hasilat'] != 0 ? '₺ ' . number_format($result['toplam_hasilat'], 2, ',', '.') : '-';
 ?></strong></td>
                                        <td>% <?php echo !empty($result['yuzde_payi2']) && $result['yuzde_payi2'] != 0 ? $result['yuzde_payi2'] : '0'; ?></td>
                                        <td><?php echo !empty($result['toplam_film_sayisi']) && $result['toplam_film_sayisi'] != 0 ? $result['toplam_film_sayisi'] : '-'; ?></td>
                                    </tr>

                                 
                                <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>

            <div class="newsRight bgnone">
                <?php foreach($haberler as $haber){   ?>
                <h2><i class="fa-solid fa-newspaper"></i> Güncel Haberler</h2>
                <a href="haberler/haber-detay/<?php echo $haber['seo_url']; ?>" class="newsBoxHafta">
                    <div class="haftaImg">
                    <img src="haberfoto/<?php echo $haber['haberfoto']; ?>" alt="">
                    </div>
                    <p><?php echo $haber['baslik']; ?></p>
                    <p class="date"><i class="fa-regular fa-clock"></i>  <?php echo formatDateTime($haber['tarih']); ?></p>
                </a>
                <?php }  ?>
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
include('../footer.php');?>

</body>

</html>