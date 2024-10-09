<?php 
include('../header.php');
include('../admin/conn.php');  
include('../SqlQueryHaber.php');  

//Tarih Ayarlamaları Kodu Başlangıç
$currentYear = date('Y'); 
$selectedYear = isset($_GET['year']) ? $_GET['year'] : $currentYear; 
$minYear = 2000;

$previousYear = ($selectedYear > $minYear) ? $selectedYear - 1 : $minYear;
$nextYear = $selectedYear < $currentYear ? $selectedYear + 1 : $currentYear;

// Tarih Ayarlamaları Kodu Bitti
try {
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
                (DAYOFWEEK(tarih) = 5 OR DAYOFWEEK(tarih) = 4)
                AND YEAR(tarih) = :selectedYear
            GROUP BY
                hafta, film_id
        ) AS filmHafta
        GROUP BY
            hafta
        ORDER BY
            hafta asc
    ");

    // Yıl parametresini bağlama
    $stmt->bindParam(':selectedYear', $selectedYear, PDO::PARAM_INT);
    $stmt->execute();
    $filmler = $stmt->fetchAll(PDO::FETCH_ASSOC);
    

    $haftaVerileri = [];

    foreach ($filmler as $film) {
        $hafta = $film['hafta'];
        
        // Eğer bu hafta için veri yoksa, diziyi oluştur
        if (!isset($haftaVerileri[$hafta])) {
            $haftaVerileri[$hafta] = [
                'toplam_kisi' => 0,
                'toplam_hasilat' => 0,
                'film_sayisi' => 0,
                'max_toplamKisi' => 0,
                'max_toplamHasilat' => 0,
                'kisi_degisimi' => null,
                'hasilat_degisimi' => null
            ];
        }
    
        // Haftalık toplamları güncelle
        $haftaVerileri[$hafta]['toplam_kisi'] += $film['toplam_kisi'];
        $haftaVerileri[$hafta]['toplam_hasilat'] += $film['toplam_hasilat'];
        $haftaVerileri[$hafta]['film_sayisi'] += $film['film_sayisi'];
        $haftaVerileri[$hafta]['max_toplamKisi'] += $film['max_toplamKisi'];
        $haftaVerileri[$hafta]['max_toplamHasilat'] += $film['max_toplamHasilat'];
    }
    
    // Değişim oranını hesapla
    $oncekiHafta = null;
    
    foreach ($haftaVerileri as $hafta => $veriler) {
        if ($oncekiHafta !== null) {
            // Kişi sayısındaki değişim oranı
            if ($haftaVerileri[$oncekiHafta]['toplam_kisi'] > 0) {
                $haftaVerileri[$hafta]['kisi_degisimi'] = (($veriler['toplam_kisi'] - $haftaVerileri[$oncekiHafta]['toplam_kisi']) / $haftaVerileri[$oncekiHafta]['toplam_kisi']) * 100;
            }
    
            // Hasılat değişim oranı
            if ($haftaVerileri[$oncekiHafta]['toplam_hasilat'] > 0) {
                $haftaVerileri[$hafta]['hasilat_degisimi'] = (($veriler['toplam_hasilat'] - $haftaVerileri[$oncekiHafta]['toplam_hasilat']) / $haftaVerileri[$oncekiHafta]['toplam_hasilat']) * 100;
            }
        }
    
        // Bir sonraki kıyaslama için önceki haftayı güncelle
        if ($veriler['toplam_kisi'] > 0 || $veriler['toplam_hasilat'] > 0) {
            $oncekiHafta = $hafta;
        }
    }
    
     
    

} catch (PDOException $e) {
    echo "Sorgu hatası: " . $e->getMessage();
}



?>
<!-- ============================================================================== -->

<!-- Table Area Start -->

<section class="haftaSection">

    <div class="haftaMain">

        <h2><i class="fa-solid fa-box-open"></i> Haftalık Film Verileri</h2>
        <p>Haftalara Göre Toplam Seyirci ve Hasılat Sayıları</p>

        <div class="status f-start">
            <div class="tabBtnBox">
                <a href="hafta/index" class="tabBtnBoxa active">Yıllara Göre</a>
            </div>
            <div class="tabBtnBox">
                <a href="hafta/index-hafta" class="tabBtnBoxa">Haftalara Göre</a>
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

                <!-- Tarih Ayarlamaları Kodu -->


                <div class="yearSelect">
                    <a href="hafta?year=<?= $previousYear; ?>"
                        class="yearBtn <?= ($selectedYear == $minYear) ? 'disabled' : 'activex'; ?>">
                        <i class="fa-solid fa-angles-left"></i> <?= $previousYear; ?>
                    </a>

                    <select name="centerBtn" id="centerBtn" class="centerBtn"
                        onchange="window.location.href='hafta?year=' + this.value;">
                        <?php for ($year = $currentYear; $year >= $minYear; $year--): ?>
                        <option value="<?= $year; ?>" <?= ($year == $selectedYear) ? 'selected' : ''; ?>><?= $year; ?>
                        </option>
                        <?php endfor; ?>
                    </select>

                    <a href="hafta?year=<?= $nextYear; ?>"
                        class="yearBtn <?= ($selectedYear >= $currentYear) ? 'disabled' : 'activex'; ?>">
                        <?= $nextYear; ?> <i class="fa-solid fa-angles-right"></i>
                    </a>
                </div>

                <!-- Tarih Ayarlamaları Kodu bitti-->

                <div class="containerAy">



                    <?php
date_default_timezone_set('Europe/Istanbul');

// Türkçe ay isimleri
$monthsFull = [
    1 => 'Ocak',
    2 => 'Şubat',
    3 => 'Mart',
    4 => 'Nisan',
    5 => 'Mayıs',
    6 => 'Haziran',
    7 => 'Temmuz',
    8 => 'Ağustos',
    9 => 'Eylül',
    10 => 'Ekim',
    11 => 'Kasım',
    12 => 'Aralık',
];

$monthsShort = [
    1 => 'Oca.',
    2 => 'Şub.',
    3 => 'Mar.',
    4 => 'Nis.',
    5 => 'May.',
    6 => 'Haz.',
    7 => 'Tem.',
    8 => 'Ağu.',
    9 => 'Eyl.',
    10 => 'Eki.',
    11 => 'Kas.',
    12 => 'Ara.',
];






// İlgili yılı al, yoksa mevcut yılı kullan
$weeksData = generateMonthsWeeks(isset($_GET['year']) ? $_GET['year'] : null);


// HTML çıktısını göster
?>


                   
                                <?php foreach ($weeksData as $month => $weeks): ?>
                                <div class="month">
                                    <h3><i class='fa-solid fa-calendar-week'></i> <?php echo $month; ?></h3>
                                    <table class="mt-0">
                                        <thead>
                                            <tr>
                                                <th>Hafta</th>
                                                <th>Tüm Filmler Seyirci</th>
                                                <th>Tüm Filmler Hasılat</th>
                                                <th>Film</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($weeks as $week): 
                                                $veri = $haftaVerileri[$week['week_number']];
                         ?>
                                                <tr>
                                                <td>
                                                    <a href="hafta/haftalar/<?php echo $week['week_number']."-".$selectedYear ?>" class="clicka">
                                                        <?php echo $week['week_number']; ?>. Hafta <br>
                                                        <?php echo $week['start'] . ' - ' . $week['end'] . ' ' . $week['month']; ?>
                                                    </a>
                                                </td>

                                                <td>
                                                    <?php if (isset($veri['toplam_kisi']) && !empty($veri['toplam_kisi'])): ?>
                                                    <?php 
                                             $degisimi = $veri['kisi_degisimi'] !== null ? $veri['kisi_degisimi'] : null;
                                             if ($degisimi !== null) {
                                                 if ($degisimi > 0) {
                                                     echo '<span class="asc"><i class="fa-solid fa-up-long"></i> ' . number_format($degisimi, 2) . '%</span>';
                                                 } elseif ($degisimi < 0) {
                                                     echo '<span class="decrease"><i class="fa-solid fa-down-long"></i> ' . number_format(abs($degisimi), 2) . '%</span>';
                                                 }
                                             }
                                            ?>
                                                    <?php echo number_format($veri['toplam_kisi'], 0, ',', '.'); ?>
                                                    <?php else: ?>
                                                    -
                                                    <?php endif; ?>
                                                </td>

                                                <td>
                                                    <?php if (isset($veri['toplam_hasilat']) && !empty($veri['toplam_hasilat'])): ?>
                                                    <?php 
                                             $degisimi = $veri['hasilat_degisimi'] !== null ? $veri['hasilat_degisimi'] : null;
                                             if ($degisimi !== null) {
                                                 if ($degisimi > 0) {
                                                     echo '<span class="asc"><i class="fa-solid fa-up-long"></i> ' . number_format($degisimi, 2) . '%</span>';
                                                 } elseif ($degisimi < 0) {
                                                     echo '<span class="decrease"><i class="fa-solid fa-down-long"></i> ' . number_format(abs($degisimi), 2) . '%</span>';
                                                 }
                                             }
                                         ?>
                                                    <?php echo number_format($veri['toplam_hasilat'], 2, ',', '.') . " ₺" ; ?>
                                                    <?php else: ?>
                                                    -
                                                    <?php endif; ?>
                                                </td>

                                                <td>
                                                    <?php if (isset($veri['film_sayisi']) && !empty($veri['film_sayisi'])): ?>
                                                    <?php echo $veri['film_sayisi']; ?>
                                                    <?php else: ?>
                                                    -
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                                <?php endforeach; ?>

                    </div>
                </div>


                <div class="newsRight bgnone">
                    <h2><i class="fa-solid fa-newspaper"></i> Güncel Haberler</h2>
                    <?php foreach($haberler3 as $haber){  ?>
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
    function ayIsmi($ayNumarasi) {
        // Ay isimleri dizisi (1'den 12'ye kadar)
        $aylar = [
            "01" => 'Ocak',
            "02" => 'Şubat',
            "03" => 'Mart',
            "04" => 'Nisan',
            "05" => 'Mayıs',
            "06" => 'Haziran',
            "07" => 'Temmuz',
            "08" => 'Ağustos',
            "09" => 'Eylül',
            "10" => 'Ekim',
            "11" => 'Kasım',
            "12" => 'Aralık'
        ];
    
        // Gönderilen ay numarasına karşılık gelen ay ismini döndür
        return $aylar[$ayNumarasi] ?? 'Geçersiz ay numarası';
    }
    // Ay ve hafta bilgilerini oluşturma fonksiyonu
    function generateMonthsWeeks($year = null) {
        global $monthsShort; // Kısaltmaları kullanmak için global değişkeni al
        global $monthsFull;
    
        // Eğer yıl belirtilmemişse, şu anki yılı kullan
        $currentYear = ($year) ? $year : date('Y');
        $currentMonth = date('n'); // Bulunduğumuz ay (sayı olarak)
        $currentDay = date('d');   // Bulunduğumuz gün (sayı olarak)
        $currentDate = new DateTime(); // Bugünün tarihi
        
        // Eğer yıl bugünkü yıl ise bugünkü tarihi al, değilse yılın sonunu al
        $startDate = ($currentYear == date('Y')) ? new DateTime() : new DateTime("$currentYear-12-31");
        $endDate = new DateTime("$currentYear-01-01"); // Yılın başı
        $weeksData = [];
    
        // Tarihleri tersten yılbaşına doğru gitmek için döngü
        while ($startDate >= $endDate) {
            $monthName = $monthsFull[(int)$startDate->format('n')] . ' ' . $startDate->format('Y');
            $monthWeeks = [];
    
            // O ayın başına gidip haftaları oluştur
            $monthStartDate = new DateTime($startDate->format('Y-m-01')); // Ayın ilk günü
            $monthEndDate = clone $monthStartDate;
    
            // Eğer bulunduğumuz ay ve yıl ise, ayın sonunu bugünkü güne ayarla, değilse ay sonuna ayarla
            if ((int)$monthEndDate->format('n') == $currentMonth && (int)$monthEndDate->format('Y') == date('Y')) {
                // Bulunduğumuz gün bugünkü gün olmalı
                $monthEndDate = new DateTime(); // Bugünü al
            } else {
                // Bulunulan ay değilse, ayın sonuna git
                $monthEndDate->modify('last day of this month'); // Ayın sonuna ayarla
            }
    
            // Haftaları kaydetmek için Cuma başlangıçlı döngü
            while ($monthStartDate <= $monthEndDate) {
                if ($monthStartDate->format('N') == 5) { // Cuma ise haftanın başı
                    $weekStart = clone $monthStartDate;
                    $weekEnd = clone $weekStart;
                    $weekEnd->modify('+6 days'); // Haftanın sonunu bul
    
                    // Haftayı kaydet
                    $monthWeeks[] = [
                        'week_number' => $weekStart->format('W'), // Yılın kaçıncı haftası
                        'start' => $weekStart->format('d'), // Hafta başlangıç günü
                        'end' => $weekEnd->format('d'), // Hafta bitiş günü
                        'month' => $monthsShort[(int)$weekEnd->format('m')], // Ay kısaltması
                    ];
                }
                $monthStartDate->modify('+1 day'); // Bir gün ileri git
            }
    
            // Haftaları büyükten küçüğe sırala
            usort($monthWeeks, function($a, $b) {
                return $b['week_number'] <=> $a['week_number'];
            });
    
            // Ayı ve haftaları ekle
            $weeksData[$monthName] = $monthWeeks;
    
            // Bir ay geriye git
            $startDate->modify('first day of last month');
        }
    
        return $weeksData;
    }
    
    
    
    include('../footer.php');?>

</body>

</html>