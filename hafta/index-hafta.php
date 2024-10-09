<?php 
include('../header.php');
include('../admin/conn.php');
include('../SqlQueryHaber.php');  

$hafta = isset($_GET['week']) ? $_GET['week'] : date('W', strtotime('last Friday'));

$minYear = 2000;

 


// Sol ve sağ butonlar için haftaları belirle
$solHafta = $hafta > 1 ? $hafta - 1 : 1; // Haftayı 1'e düşürmemek için kontrol
$sagHafta = $hafta < 52 ? $hafta + 1 : 52; // Haftayı 52'ye aşmamayı sağla

// Seçili değerin kontrolü
$selected = function($value) use ($hafta) {
    return $value == $hafta ? 'selected' : '';
};



$baslangicYili = 2005;
$bitisYili = date('Y'); // Güncel yıl

// Yıllara göre verileri depolamak için boş bir dizi oluşturuyoruz
$yillikVeriler = [];

for ($yil = $baslangicYili; $yil <= $bitisYili; $yil++) {
    // Haftaları ayarlayın
    $haftaBaslangic = new DateTime();
    $haftaBaslangic->setISODate($yil, $hafta+1, 5);
    $haftaBaslangic->modify('-1 days');
    $haftaBitis = clone $haftaBaslangic;
    $haftaBitis->modify('-6 days');

    // Tarih formatlarını ayarla
    $endDateFormatted = $haftaBaslangic->format('Y-m-d');
    $startDateFormatted = $haftaBitis->format('Y-m-d');

    // SQL sorgusunu hazırlayın
    $stmt = $con->prepare("
        SELECT
            WEEK(tarih, 5) AS hafta,
            SUM(max_toplamKisi) AS toplam_kisi,
            SUM(max_toplamHasilat) AS toplam_hasilat,
            max_toplamHasilat,
            max_toplamKisi,
            COUNT(DISTINCT film_id) AS film_sayisi
        FROM (
            SELECT
                tarih,
                WEEK(tarih, 5) AS hafta,
                MAX(toplamKisi) AS max_toplamKisi,
                MAX(toplamHasilat) AS max_toplamHasilat,
                film_id
            FROM
                filmveriler
            WHERE 
                tarih BETWEEN :startDate AND :endDate
            GROUP BY
                hafta, film_id
        ) AS filmHafta
        GROUP BY
            hafta
        ORDER BY
            hafta ASC
    ");

    $stmt->bindParam(':startDate', $startDateFormatted);
    $stmt->bindParam(':endDate', $endDateFormatted);

    // Sorguyu çalıştır
    $stmt->execute();
    $filmler = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Yıllara göre verileri ayırarak diziye ekleyin
    $yillikVeriler[$yil] = $filmler;
}

// Şimdi yıllar arasındaki değişim oranlarını hesapla
foreach ($yillikVeriler as $yil => $haftaVerileri) {
    // İlk yıldan önceki veri olmadığı için atlanacak
    if ($yil > $baslangicYili) {
        foreach ($haftaVerileri as $haftaIndex => $veriler) {
            // Bir önceki yılın aynı haftasına erişmek için
            $oncekiYil = $yil - 1;

            // Önceki yıllarda veri bulana kadar geriye git
            while ($oncekiYil >= $baslangicYili && !isset($yillikVeriler[$oncekiYil][$haftaIndex])) {
                $oncekiYil--;
            }

            // Eğer önceki bir yılda aynı hafta için veri varsa karşılaştır
            if (isset($yillikVeriler[$oncekiYil][$haftaIndex])) {
                $oncekiVeriler = $yillikVeriler[$oncekiYil][$haftaIndex];

                // Kişi sayısındaki değişim oranı
                if ($oncekiVeriler['toplam_kisi'] > 0) {
                    $yillikVeriler[$yil][$haftaIndex]['kisi_degisimi'] = 
                        (($veriler['toplam_kisi'] - $oncekiVeriler['toplam_kisi']) / $oncekiVeriler['toplam_kisi']) * 100;
                }

                // Hasılat değişim oranı
                if ($oncekiVeriler['toplam_hasilat'] > 0) {
                    $yillikVeriler[$yil][$haftaIndex]['hasilat_degisimi'] = 
                        (($veriler['toplam_hasilat'] - $oncekiVeriler['toplam_hasilat']) / $oncekiVeriler['toplam_hasilat']) * 100;
                }
            }
        }
    }
}

// Verileri test etmek için yazdırın


?>


 

    <!-- ============================================================================== -->
    
    <!-- Table Area Start -->

    <section class="haftaSection">

        <div class="haftaMain">

            <h2><i class="fa-solid fa-box-open"></i> Haftalık Gişe Hasılatı</h2>
            <p>Haftalara Göre Toplam Seyirci ve Hasılat Sayıları</p>
            
            <div class="status f-start">
                <div class="tabBtnBox">
                    <a href="hafta/index" class="tabBtnBoxa">Yıllara Göre</a>
                </div>
                <div class="tabBtnBox">
                    <a href="index-hafta" class="tabBtnBoxa active">Haftalara Göre</a>
                </div>
            </div>
            
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

                <div class="yearSelect">
    <a href="hafta/index-hafta?week=<?php echo $solHafta; ?>" class="yearBtn activex">
        <i class="fa-solid fa-angles-left"></i> <?php echo $solHafta; ?>
    </a>
    
    <select name="centerBtn" id="centerBtn" class="centerBtn" onchange="window.location.href='hafta/index-hafta?week=' + this.value;">>
        <?php for ($i = 1; $i <= 52; $i++): ?>
            <option value="<?php echo $i; ?>" <?php echo $selected($i); ?>><?php echo $i; ?></option>
        <?php endfor; ?>
    </select>
    
    <a href="hafta/index-hafta?week=<?php echo $sagHafta; ?>" class="yearBtn activex">
        <?php echo $sagHafta; ?> <i class="fa-solid fa-angles-right"></i>
    </a>
</div>

                    <div class="containerAy">
                        
                        <div class="tab-content-hafta" id="seyirci">
                        <?php
function getWeekRange($year, $week) {
    // Cuma gününü hesapla
    $fridayDate = new DateTime();
    $fridayDate->setISODate($year, $week, 5); // 5, haftanın Cuma günü

    // Haftanın başlangıç ve bitiş tarihlerini hesapla
    $startOfWeek = clone $fridayDate; // Cuma gününden kopyala
    $endOfWeek = clone $fridayDate; // Cuma gününden kopyala
    $endOfWeek->modify('+6 days');  // Pazar gününe git

    // Tarihleri formatla (gün - ay)
    $startDateFormatted = $startOfWeek->format('d M.');
    $endDateFormatted = $endOfWeek->format('d M.');

    // Ay kısaltmalarını Türkçeye çevir
    $months = [
        'Jan' => 'Oca',
        'Feb' => 'Şub',
        'Mar' => 'Mar',
        'Apr' => 'Nis',
        'May' => 'May',
        'Jun' => 'Haz',
        'Jul' => 'Tem',
        'Aug' => 'Ağu',
        'Sep' => 'Eyl',
        'Oct' => 'Eki',
        'Nov' => 'Kas',
        'Dec' => 'Ara'
    ];

    // Ay kısaltmalarını Türkçeye dönüştür
    $startDateFormatted = str_replace(array_keys($months), array_values($months), $startDateFormatted);
    $endDateFormatted = str_replace(array_keys($months), array_values($months), $endDateFormatted);

    return [$startDateFormatted, $endDateFormatted];
}


    
echo '<div class="month">';
echo '<table class="mt-0">';
echo '<thead>';
echo '<tr>';
echo '<th>Yıl</th>';
echo '<th>Hafta</th>';
echo '<th>Tüm Filmler Seyirci</th>';
echo '<th>Tüm Filmler Hasılat</th>';
echo '<th>Film</th>';
echo '</tr>';
echo '</thead>';
echo '<tbody>';

// 2024'ten 2005'e kadar döngü
for ($year = 2024; $year >= 2005; $year--) {
    // Yıla ait verileri al
    $yearveri = isset($yillikVeriler[$year]) ? $yillikVeriler[$year] : [];
        if($year !=date("Y")){   
          // Hafta sayısını belirlemek için döngü
          for ($haftaIndex = 0; $haftaIndex < count($yearveri); $haftaIndex++) {
              $veri = $yearveri[$haftaIndex];
              if($veri['hafta']==$hafta){
              // Haftanın tarih aralığını al
              list($start, $end) = getWeekRange($year, $hafta);

              // Değişim oranlarını hesaplayın
              $kisiDegisimi = isset($veri['kisi_degisimi']) ? $veri['kisi_degisimi'] : null;
              $hasilatDegisimi = isset($veri['hasilat_degisimi']) ? $veri['hasilat_degisimi'] : null;

              echo '<tr>';
              echo "<td><a href=\"hafta/?year=$year\" class=\"clicka\">$year</a></td>";
              echo "<td><a href=\"hafta/haftalar/" . $veri['hafta'] . "-$year\" class=\"clicka\">$start - $end</a></td>";

              echo "<td>";
              if ($kisiDegisimi !== null) {
                $toplamKisi = isset($veri['toplam_kisi']) ? number_format($veri['toplam_kisi'], 0, ',', '.') : 0;

                if ($kisiDegisimi > 0) {
                    echo "<span class=\"asc\"><i class=\"fa-solid fa-up-long\"></i> %" . number_format($kisiDegisimi, 1) . "</span> " . $toplamKisi;
                } else {
                    echo "<span class=\"decrease\"><i class=\"fa-solid fa-down-long\"></i> %" . number_format(abs($kisiDegisimi), 1) . "</span> " . $toplamKisi;
                }
                
              } else {
                  echo (isset($veri['toplam_kisi']) ? number_format($veri['toplam_kisi'], 0, ',', '.') : 0);
              }
              echo "</td>";

              echo "<td>";
              if ($hasilatDegisimi !== null) {
                if ($hasilatDegisimi > 0) {
                    echo "<span class=\"asc\"><i class=\"fa-solid fa-up-long\"></i> %" . number_format($hasilatDegisimi, 1) . "</span> " . (isset($veri['toplam_hasilat']) ? number_format($veri['toplam_hasilat'], 2, ',', '.') . " ₺" : "0 ₺");
                } else {
                    echo "<span class=\"decrease\"><i class=\"fa-solid fa-down-long\"></i> %" . number_format(abs($hasilatDegisimi), 1) . "</span> " . (isset($veri['toplam_hasilat']) ? number_format($veri['toplam_hasilat'], 2, ',', '.') . " ₺" : "0 ₺");
                }
                
              } else {
                  echo (isset($veri['toplam_hasilat']) ?  number_format($veri['toplam_hasilat'], 2, ',', '.') . " ₺" : "0 ₺") ;
              }
              echo "</td>"; // Film Hasılat
              echo "<td>" . (isset($veri['film_sayisi']) ? $veri['film_sayisi'] : 0) . "</td>"; // Film sayısı yazılacak 
              echo '</tr>';

          }
          }
        }
}

echo '</tbody>';
echo '</table>';
echo '</div>';



// Belirtilen hafta değeri
  // Örneğin 41. hafta

?>

                        </div>
                       
                    </div>
                    
                </div>

                <div class="newsRight bgnone">
                    <h2><i class="fa-solid fa-newspaper"></i> Güncel Haberler</h2>
                    <?php foreach($haberler as $haber){  ?>
                    <a href="haberler/haber-detay/<?php echo $haber['seo_url']; ?>" class="newsBoxHafta">
                        <div class="haftaImg">
                            <img src="haberfoto/<?php echo $haber['haberfoto']; ?>" alt="">
                        </div>
                        <p><?php echo $haber['baslik']; ?></p>
                        <p class="date"><i class="fa-regular fa-clock"></i> <?php echo formatDate($haber['tarih']); ?></p>
                    </a>
                    <?php } ?>
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
   

    include('../footer.php');?>

</body>
</html>