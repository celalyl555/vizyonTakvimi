<?php

//Tarih Ayarlamaları Kodu Başlangıç
$currentYear = date('Y'); 
$selectedYear = isset($_GET['year']) ? $_GET['year'] : $currentYear; 
$minYear = 2000;

$previousYear = ($selectedYear > $minYear) ? $selectedYear - 1 : $minYear;
$nextYear = $selectedYear < $currentYear ? $selectedYear + 1 : $currentYear;

$seourl = isset($_GET['url']) ? $_GET['url'] : '';
// Tarih Ayarlamaları Kodu Bitti
include('../SqlQueryDagitimDetay.php');
include('../header.php');

?>
    <!-- ============================================================================== -->
    
    <!-- Table Area Start -->

    <section class="haftaSection">

        <div class="haftaMain">

            <h2><i class="fa-solid fa-box-open"></i> <?php echo $dagitimAd['dagitimad']?> </h2>
            <p class="title"><?php echo $dagitimAd['dagitimad']?> Dağıtımcılığını Yaptığı Filmler</p>

  <!-- Tarih Ayarlamaları Kodu -->


                <div class="yearSelect"> <!-- c -->
                    <a href="dagitimci/dagitimci-detay/<?php echo $seourl; ?>?year=<?= $previousYear; ?>"
                        class="yearBtn <?= ($selectedYear == $minYear) ? 'disabled' : 'activex'; ?>">
                        <i class="fa-solid fa-angles-left"></i> <?= $previousYear; ?>
                    </a>

                    <select name="centerBtn" id="centerBtn" class="centerBtn"
                        onchange="window.location.href='dagitimci/dagitimci-detay/<?php echo $seourl; ?>?year=' + this.value;">
                        <?php for ($year = $currentYear; $year >= $minYear; $year--): ?>
                        <option value="<?= $year; ?>" <?= ($year == $selectedYear) ? 'selected' : ''; ?>><?= $year; ?>
                        </option>
                        <?php endfor; ?>
                    </select>

                    <a href="dagitimci/dagitimci-detay/<?php echo $seourl; ?>?year=<?= $nextYear; ?>"
                        class="yearBtn <?= ($selectedYear >= $currentYear) ? 'disabled' : 'activex'; ?>">
                        <?= $nextYear; ?> <i class="fa-solid fa-angles-right"></i>
                    </a>
                </div>

                <!-- Tarih Ayarlamaları Kodu bitti-->
        
            <div class="containerTable">
                <table id="movie-table">
                    <thead>
                        <tr>
                            <th><span class="sort" data-sort="film-name">Film Adı <i class="fas fa-sort"></i></span></th>
                            <th><span class="sort" data-sort="studio">Stüdyo & Şirket <i class="fas fa-sort"></i></span></th>
                            <th><span class="sort" data-sort="week">Hafta <i class="fas fa-sort"></i></span></th>
                            <th><span class="sort" data-sort="week-revenue">Lokasyon <i class="fas fa-sort"></i></span></th>
                            <th><span class="sort" data-sort="total-revenue">Toplam Hasılat <i class="fas fa-sort"></i></span></th>
                            <th><span class="sort" data-sort="total-audience">Toplam Seyirci <i class="fas fa-sort"></i></span></th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                  foreach ($filmHaftaListesi as $filmListe): ?>
                    <tr>
                        <td>
                            <div class="nameBox">
                                <img class="tableImg" src="kapakfoto/<?php echo $filmListe['kapak_resmi']; ?>" alt="">
                                <div>
                                    <a href="filmler/film-detay/<?php echo $filmListe['filmseo']; ?>" title="<?php echo $filmListe['film_adi']; ?>">
                                        <?php echo $filmListe['film_adi']; ?>
                                    </a><br>
                                    <small><?php echo formatDate($filmListe['vizyon_tarihi']); ?></small>
                                </div>
                            </div>
                        </td>
                
                        <!-- Stüdyo adı buraya yazılıyor (boşsa -) -->
                        <td><?php echo !empty($filmListe['stüdyoAdlar']) ? $filmListe['stüdyoAdlar'] : '-'; ?></td>
                        
                        <!-- Haftaları buraya yazıyoruz (boşsa -) -->
                        <td><?php echo !empty($filmListe['hafta']) ? $filmListe['hafta'] : '-'; ?></td>
                
                        <!-- En büyük sinema sayısı buraya yazılıyor (boşsa -) -->
                        <td><?php echo !empty($filmListe['lokasyon']) ? $filmListe['lokasyon'] : '-'; ?></td>
                
                        <!-- Toplam hasilat buraya yazılıyor (boşsa -) -->
                        <td><?php echo !empty($filmListe['toplamHasilat']) ? "₺ ".number_format($filmListe['toplamHasilat'], 2, ',', '.') : '-'; ?></td>
                
                        <!-- Toplam kişi sayısı buraya yazılıyor (boşsa -) -->
                        <td><?php echo !empty($filmListe['toplamSeyirci']) ? number_format($filmListe['toplamSeyirci'], 0, ',', '.') : '-'; ?></td>
                    </tr>
                <?php endforeach; ?>
                
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

?>
    <?php include('../footer.php');?>

</body>
</html>