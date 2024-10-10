<?php 

include('../header.php');
include('../admin/conn.php');
$tarih = isset($_GET['url']) ? $_GET['url'] : '';

// Tarihi ay ve yıl olarak ayır
list($hafta, $yil) = explode('-', $tarih);

// Haftanın başlangıcını ve bitişini hesapla
$haftaBaslangic = new DateTime();
$haftaBaslangic->setISODate($yil, $hafta , 5); // ISO hafta standardına göre başlar
$haftaBaslangic->modify('-1 days'); // Haftanın son günü
$haftaBitis = clone $haftaBaslangic;
$haftaBitis->modify('-6 days'); // Haftanın ilk günü

// Tarih formatlarını ayarla
$endDateFormatted = $haftaBaslangic->format('Y-m-d');
$startDateFormatted = $haftaBitis->format('Y-m-d');

// Verileri ekrana yazdır
echo $endDateFormatted . "   ";
echo $startDateFormatted;

// Veritabanından yıllık verileri çek
$yillikVeriler = [];
$sql = "
    SELECT
        fv.film_id,
         
        MAX(fv.max_toplamHasilat) AS hafta_hasilat, -- Haftalık hasılat
        MAX(fv.max_toplamKisi) AS hafta_seyirci, -- Haftalık seyirci
        MAX(fv.sinema) AS salon_sayisi, -- Salon sayısı
        FLOOR(vizyon.toplam_hafta / 7) AS hafta_vizyon, -- Vizyon süresi (hafta olarak)
        MAX(fv.max_toplamHasilat) AS toplam_hasilat, -- Toplam hasılat
        MAX(fv.max_toplamKisi) AS toplam_seyirci, -- Toplam seyirci
        f.*,  
        sd.*,
         f.seo_url AS f_seo_url
    FROM (
        SELECT
            tarih,
            WEEK(tarih, 5) AS hafta,
            MAX(toplamKisi) AS max_toplamKisi,
            MAX(toplamHasilat) AS max_toplamHasilat,
            MAX(sinema) AS sinema, -- sinema sayısı
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
    LEFT JOIN filmler f ON fv.film_id = f.id -- Filmler tablosu ile join
    LEFT JOIN film_dagitim fd ON fv.film_id = fd.film_id -- Film dağıtım tablosu ile join
    LEFT JOIN sinemadagitim sd ON fd.dagitim_id = sd.iddagitim -- Sinema dağıtım tablosu ile join
    GROUP BY
        fv.film_id
    ORDER BY
        fv.hafta ASC
";

// Sorguyu hazırlama ve parametreleri bağlama
$stmt = $con->prepare($sql);
$stmt->bindParam(':startDate', $startDateFormatted);
$stmt->bindParam(':endDate', $endDateFormatted);

// Sorguyu çalıştır
$stmt->execute();
$filmler = $stmt->fetchAll(PDO::FETCH_ASSOC);

$topSeyirci = 0;
$topHasilat =0;
$topfilm=0;
foreach($filmler as $film){
    $topHasilat +=  $film['hafta_hasilat'] ;
    $topSeyirci +=  $film['hafta_seyirci'] ;
    $topfilm++;
}

?>

    <!-- ============================================================================== -->
    
    <!-- Table Area Start -->

    <section class="haftaSection">

        <div class="haftaMain">

            <h2><i class="fa-solid fa-box-open"></i> Haftalık Gişe Hasılatı</h2>
            <p class="title">Haftalık Film Gişe Verileri</p>


            <div class="status">
                <div class="statusBox">
                    <p class="title">Toplam Seyirci</p>
                    <p><strong><?php echo number_format($topSeyirci, 0, ',', '.')  ?></strong></p>
                </div>
                <div class="statusBox">
                    <p class="title">Toplam Hasılat</p>
                    <p><strong><?php echo number_format($topHasilat, 2, ',', '.') . ' ₺' ?></strong></p>
                </div>
                <div class="statusBox">
                    <p class="title">Film Sayısı</p>
                    <p><strong><?php echo $topfilm ?></strong></p>
                </div>
            </div>
            

            <div class="status">
                <div class="tabBtnBox">
                    <p class="tabBtnBoxHeader"><?php echo $hafta; ?>. Hafta Verileri</p>
                </div>
            </div>
        
            <div class="containerTable">
                <table id="movie-table">  
                    <thead>
                        <tr>
                            <th><span class="sort" data-sort="index"># <i class="fas fa-sort"></i></span></th>
                            <th><span class="sort" data-sort="film-name">Film Adı <i class="fas fa-sort"></i></span></th>
                            <th><span class="sort" data-sort="distributor">Dağıtımcı <i class="fas fa-sort"></i></span></th>
                            <th><span class="sort" data-sort="salon">Salon <i class="fas fa-sort"></i></span></th>
                            <th><span class="sort" data-sort="week">Hafta <i class="fas fa-sort"></i></span></th>
                            <th><span class="sort" data-sort="week-revenue">Hafta Hasılat <i class="fas fa-sort"></i></span></th>
                            <th><span class="sort" data-sort="week-audience">Hafta Seyirci <i class="fas fa-sort"></i></span></th>
                            <th><span class="sort" data-sort="total-revenue">Toplam Hasılat <i class="fas fa-sort"></i></span></th>
                            <th><span class="sort" data-sort="total-audience">Toplam Seyirci <i class="fas fa-sort"></i></span></th>
                        </tr>
                    </thead>
                    <tbody>

                        <?php
                        $temp =1;
                        foreach($filmler as $film){  ?> 
                      <tr>
    <td class="numberT"><?php echo !empty($temp) ? $temp : '-' ?></td>
    <td>
        <div class="nameBox">
            <img class="tableImg" src="kapakfoto/<?php echo !empty($film['kapak_resmi']) ? $film['kapak_resmi'] : 'default.jpg' ?>" alt="">
            <div> 
                <a href="filmler/film-detay/<?php echo !empty($film['f_seo_url']) ? $film['f_seo_url'] : '#' ?>" title="<?php echo !empty($film['film_adi']) ? $film['film_adi'] : '-' ?>">
                    <?php echo !empty($film['film_adi']) ? $film['film_adi'] : '-' ?>
                </a><br>
                <small><?php echo !empty($film['vizyon_tarihi']) ? formatDate($film['vizyon_tarihi']) : '-' ?></small>
            </div>
        </div>
    </td>
    <td>
        <a href="dagitimci/dagitimci-detay/<?php echo !empty($film['seo_url']) ? $film['seo_url'] : '#' ?>">
            <?php echo !empty($film['dagitimad']) ? $film['dagitimad'] : '-' ?>
        </a>
    </td>
    <td><?php echo !empty($film['salon_sayisi']) ? $film['salon_sayisi'] : '-' ?></td>
    <td><?php echo !empty($film['hafta_vizyon']) ? $film['hafta_vizyon'] : '-' ?></td>
    <td><?php echo !empty($film['hafta_hasilat']) ? number_format($film['hafta_hasilat'], 2, ',', '.') . ' ₺' : '-' ?></td>
    <td><?php echo !empty($film['hafta_seyirci']) ? number_format($film['hafta_seyirci'], 0, ',', '.') : '-' ?></td>
    <td><?php echo !empty($film['toplam_hasilat']) ? number_format($film['toplam_hasilat'], 2, ',', '.') . ' ₺' : '-' ?></td>
    <td><?php echo !empty($film['toplam_seyirci']) ? number_format($film['toplam_seyirci'], 0, ',', '.') : '-' ?></td>
</tr>

                        <?php   $temp++; } ?>
                    </tbody>
                </table>
            </div>
            
        </div>

    </section>

    <!-- Table Area End -->

    <!-- ============================================================================== -->

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
    include('../footer.php');?>

</body>
</html>