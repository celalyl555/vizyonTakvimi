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


                <div class="yearSelect"> 
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
                    <?php foreach ($filmHaftaListesi as $filmListe): ?>
                        <tr>
                            <td>
                                <div class="nameBox">
                                    <img class="tableImg" src="assets/img/news/04.jpg" alt="">
                                    <div>
                                        <a href="" title="<?php echo $filmListe['film_adi']; ?>">
                                            <?php echo $filmListe['film_adi']; ?>
                                        </a><br>
                                        <small>6 Eylül 2024</small>
                                    </div>
                                </div>
                            </td>
                            <td><?php echo $filmListe['studyoad']; ?></td>
                            
                            <!-- Haftaları buraya yazıyoruz (kac_hafta_cuma) -->
                            <td><?php echo isset($filmListe['kac_hafta_cuma']) ? $filmListe['kac_hafta_cuma'] : 'Veri Yok'; ?></td>
                            
                            <!-- Sinema verisi buraya yazılıyor -->
                            <td><?php echo isset($lokasyonData['']) ? $lokasyonData['toplam_sinema'] : 'Veri Yok'; ?></td>
                            
                            <td>₺10.071.952</td>
                            <td>50.203</td>
                        </tr>
                    <?php endforeach; ?>

                    </tbody>
                </table>
            </div>
            
        </div>

    </section>

    <!-- Table Area End -->

    <!-- ============================================================================== -->

    <?php include('../footer.php');?>

</body>
</html>