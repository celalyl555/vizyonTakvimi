<?php 
include('../header.php');
include('../admin/conn.php');
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
            hafta,
            SUM(max_toplamKisi) AS toplam_kisi,
            SUM(max_toplamHasilat) AS toplam_hasilat
        FROM (
            SELECT
                YEARWEEK(tarih, 5) AS hafta,
                MAX(toplamKisi) AS max_toplamKisi,  -- Her film için maksimum kişi sayısı
                MAX(toplamHasilat) AS max_toplamHasilat -- Her film için maksimum hasılat
            FROM
                filmveriler
            WHERE 
                DAYOFWEEK(tarih) = 5 OR DAYOFWEEK(tarih) = 4
            GROUP BY
                hafta, film_id  -- Film ID'sine göre grupla
        ) AS filmHafta
        GROUP BY
            hafta  -- Haftaya göre gruplandır
        ORDER BY
            hafta DESC
    ");

    $stmt->execute();
    $filmler = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Sonuçları ekrana yazdırma
    foreach ($filmler as $film) {
        echo "Hafta: " . $film['hafta'] . " - Toplam Kişi: " . $film['toplam_kisi'] . " - Toplam Hasılat: " . $film['toplam_hasilat'] . "<br>";
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
<div class="haftaMain">
     

    <div class="status f-start">
        <div class="tabBtnBox">
            <a href="hafta/index" class="tabBtnBoxa active">Yıllara Göre</a>
        </div>
        <div class="tabBtnBox">
            <a href="hafta/index-hafta" class="tabBtnBoxa">Haftalara Göre</a>
        </div>
    </div>

    <?php foreach ($weeksData as $month => $weeks): ?>
        <div class="month" >
            <h3><i class='fa-solid fa-calendar-week'></i> <?php echo $month; ?></h3>
            <table class="mt-0">
                <thead>
                    <tr>
                        <th>Hafta</th>
                        <th>İlk 10 Film Seyirci</th>
                        <th>Tüm Filmler Seyirci</th>
                        <th>İlk 10 Film Hasılat</th>
                        <th>Tüm Filmler Hasılat</th>
                        <th>Film</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($weeks as $week): ?>
                        <tr>
                            <td>
                                <a href="haftalar.php" class="clicka">
                                    <?php echo $week['week_number']; ?>. Hafta <br>
                                    <?php echo $week['start'] . ' - ' . $week['end'] . ' ' . $week['month']; ?>
                                </a>
                            </td>
                            <td>
                                <span class="asc"><i class="fa-solid fa-up-long"></i> %21.6</span> 202.152
                            </td>
                            <td>
                                <span class="asc"><i class="fa-solid fa-up-long"></i> %16.8</span> 239.578
                            </td>
                            <td><span class="decrease"><i class="fa-solid fa-down-long"></i> %9.0</span> 257.693</td>
                            <td><span class="decrease"><i class="fa-solid fa-down-long"></i> %6.6</span> 288.081</td>
                            <td><?php echo rand(1, 100); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endforeach; ?>
</div>



                            
                        </div>




                      
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

    <?php 
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
                        'month' => $monthsShort[(int)$weekStart->format('m')], // Ay kısaltması
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