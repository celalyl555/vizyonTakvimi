<?php 
include('../admin/conn.php');
include('../header.php');
include('../SqlQueryFilm.php');
?>

<!-- ============================================================================== -->

<!-- Table Area Start -->

<section class="haftaSection">

    <div class="haftaMain">

        <h2><i class="fa-regular fa-newspaper"></i> Haberler</h2>
        <p>Türkiye'de faaliyet gösteren Dağıtımcılar için veriler</p>

    </div>

</section>

<!-- Table Area End -->

<!-- ============================================================================== -->

<!-- News Area End -->

<section class="pt-0">

    <div class="news">

        <div class="newsInside">

            <div class="newsLeft">
                <div id="newsContainer">
                    <?php foreach ($haberlerGenel1 as $index => $haber): ?>
                        <a href="haberler/haber-detay/<?php echo $haber['seo_url']; ?>" class="newsBox" data-index="<?php echo $index; ?>">
                            <div class="newsBoxImg">
                                <img src="haberfoto/<?php echo $haber['haberfoto']?>" alt="">
                            </div>
                            <div>
                                <p><i class="fa-solid fa-hourglass-half"></i>
                                    <?php echo formatDateTime($haber['tarih']);?></p>
                                <h3><?php echo $haber['baslik'];?></h3>
                            </div>
                        </a>
                    <?php endforeach; ?>
                </div>

                <div class="pageBtn">
                    <button class="pageBtns" id="prevBtn" disabled><i class="fa-solid fa-angles-left"></i></button>
                    <span id="pageInfo">1 / <?php echo ceil(count($haberlerGenel1) / 5); ?></span>
                    <button class="pageBtns" id="nextBtn"><i class="fa-solid fa-angles-right"></i></button>
                </div>
            </div>






            <div class="newsRight bgnone">
                <h2><i class="fa-solid fa-newspaper"></i> Vizyona Girecekler</h2>
                <?php
                    foreach($filmlerGenelYakin as $yakinFilmler):?>
                <a href="filmler/film-detay/<?php echo $yakinFilmler['seo_url']; ?>" class="newsBoxHafta">
                    <div class="haftaImg">
                        <img src="kapakfoto/<?php echo $yakinFilmler['kapak_resmi'];?>" alt="">
                    </div>
                    <p><?php echo $yakinFilmler['film_adi'];?></p>
                    <p class="date"><i
                            class="fa-regular fa-clock"></i> <?php echo formatDate($yakinFilmler['vizyon_tarihi']);?></p>
                </a>
                <?php endforeach;?>
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

<script>

let currentPage = 1; // Mevcut sayfa
const itemsPerPage = 5; // Her sayfada gösterilecek haber sayısı
const newsBoxes = document.querySelectorAll('.newsBox'); // Tüm haber kutularını seç
const totalPages = Math.ceil(newsBoxes.length / itemsPerPage); // Toplam sayfa sayısını hesapla

// İlk sayfayı göster
showPage(currentPage);

// Sayfayı gösteren fonksiyon
function showPage(page) {
    const start = (page - 1) * itemsPerPage; // Başlangıç indexi
    const end = start + itemsPerPage; // Bitiş indexi

    // Tüm haber kutularını gizle
    newsBoxes.forEach((box, index) => {
        box.style.display = (index >= start && index < end) ? 'flex' : 'none';
    });

    // Sayfa bilgilerini güncelle
    document.getElementById('pageInfo').textContent = `${page} / ${totalPages}`;

    // Buton durumlarını ayarla
    document.getElementById('prevBtn').disabled = (page === 1);
    document.getElementById('nextBtn').disabled = (page === totalPages);
}

// Önceki sayfa butonuna tıklama olayı
document.getElementById('prevBtn').addEventListener('click', () => {
    if (currentPage > 1) {
        currentPage--;
        showPage(currentPage);
    }
});

// Sonraki sayfa butonuna tıklama olayı
document.getElementById('nextBtn').addEventListener('click', () => {
    if (currentPage < totalPages) {
        currentPage++;
        showPage(currentPage);
    }
});

</script>