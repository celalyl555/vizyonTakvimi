<?php 
include('../admin/conn.php');
include('../header.php');
include('../SqlQueryFilm.php');
?>

<!-- ============================================================================== -->

<!-- Table Area Start -->

<section class="haftaSection">

    <div class="haftaMain">

        <h2><i class="fa-solid fa-box-open"></i> Filmler</h2>
        <p>Filmler ile ilgili aradığınız herşey burada.</p>

    </div>

</section>

<!-- Table Area End -->

<!-- ============================================================================== -->

<!-- vizyon Area End -->

<section>
    <div class="vizyon">

        <div class="tabs2">
            <button class="tablinks active" onclick="openTab2(event, 'vizyondaYeni')"><i class="fa-solid fa-ticket"></i>
                Vizyonda Yeni</button>
            <button class="tablinks" onclick="openTab2(event, 'yakinda')"><i class="fa-solid fa-clock-rotate-left"></i>
                Yakında</button>
        </div>

        <div id="vizyondaYeni" class="tabcontent">

            <div class="vizyonSlier">
                <div class="vizyonLeft">
                    <button class="arrows left"><i class="fa-solid fa-caret-left"></i></button>
                    <?php if (!empty($enEskiFilm)): ?>
                    <a href="filmler/film-detay/<?php echo $enEskiFilm['seo_url']; ?>" class="mainvizyonImg">
                        <img src="kapakfoto/<?php echo $enEskiFilm['kapak_resmi']; ?>" alt="vizyon">
                        <div class="overlay1">
                            <span class="namevizyon"><?php echo $enEskiFilm['film_adi'];?></span>
                        </div>
                    </a>
                    <?php endif; ?>
                    <button class="arrows right"><i class="fa-solid fa-caret-right"></i></button>
                </div>
                <div class="vizyonRight">
                    <?php foreach ($filmlerVizyon as $film): ?>
                    <a href="filmler/film-detay/<?php echo $film['seo_url']; ?>" class="vizyonBox">
                        <div class="vizyonBoxImg">
                            <img src="kapakfoto/<?php echo $film['kapak_resmi'];?>" alt="">
                        </div>
                        <h3><?php echo $film['film_adi']; ?></h3>
                    </a>
                    <?php endforeach; ?>
                </div>
            </div>

        </div>

        <div id="yakinda" class="tabcontent" style="display:none;">
            <div class="vizyonSlier">
                <div class="vizyonLeft">
                    <button class="arrows left"><i class="fa-solid fa-caret-left"></i></button>
                    <?php if (!empty($enYeniFilm)): ?>
                    <a href="filmler/film-detay/<?php echo $enYeniFilm['seo_url']; ?>" class="mainvizyonImg">
                        <img src="kapakfoto/<?php echo $enYeniFilm['kapak_resmi']; ?>" alt="vizyon">
                        <div class="overlay1">
                            <span class="namevizyon"><?php echo $enYeniFilm['film_adi']?></span>
                            <p><?php echo formatDate($enYeniFilm['vizyon_tarihi']);?></p>
                        </div>
                    </a>
                    <?php endif; ?>
                    <button class="arrows right"><i class="fa-solid fa-caret-right"></i></button>
                </div>
                <div class="vizyonRight">
                    <?php foreach ($filmlerYakin as $film): ?>
                    <a href="filmler/film-detay/<?php echo $film['seo_url']; ?>" class="vizyonBox">
                        <div class="vizyonBoxImg">
                            <img src="kapakfoto/<?php echo $film['kapak_resmi']; ?>" alt="">
                        </div>
                        <div>
                            <h3><?php echo $film['film_adi']; ?></h3>
                            <p><?php echo formatDate($film['vizyon_tarihi']); ?></p>
                        </div>
                    </a>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

    </div>
</section>

<!-- vizyon Area End -->

<!-- ============================================================================== -->

<section>

    <div class="news">

        <h2><i class="fa-solid fa-film"></i> Filmler</h2>

        <div class="newsInside">

            <div class="newsMovie">

                <?php  foreach ($filmler11 as $film) { ?>
                <a href="filmler/film-detay/<?php echo $film['seo_url']; ?>" class="newsBox newsBoxMovie">
                    <div class="newsBoxImg">
                        <img src="kapakfoto/<?php echo $film['kapak_resmi']; ?>"
                            alt="kapakfoto/<?php echo $film['kapak_resmi']; ?>">
                    </div>
                    <div class="movieName">
                        <p><i class="fa-solid fa-hourglass-half"></i> <?php echo formatDate($film['vizyon_tarihi']); ?>
                        </p>
                        <h3><?php echo $film['film_adi']; ?></h3>
                    </div>
                </a>

                <?php } ?>
                <div class="pageBtn">
                    <button class="pageBtns" id="prevBtn" disabled><i class="fa-solid fa-angles-left"></i></button>
                    <span id="pageInfo">1 / <?php echo ceil(count($filmler11) / 8); ?></span> <!-- 8 veriye göre -->
                    <button class="pageBtns" id="nextBtn"><i class="fa-solid fa-angles-right"></i></button>
                </div>



            </div>

        </div>

    </div>
</section>

<!-- News Area End -->

<section>

    <div class="news">

        <h2><i class="fa-solid fa-film"></i> Filmler'den Haberler</h2>

        <div class="newsInside">



            <div class="newsLeft">
                <?php  foreach ($haberler2 as $haber) { ?>
                <a href="haberler/haber-detay/<?php echo $haber['seo_url']; ?>" class="newsBox newsItem">
                    <div class="newsBoxImg">
                        <img src="haberfoto/<?php echo $haber['haberfoto']; ?>"
                            alt="haberfoto/<?php echo $haber['haberfoto']; ?>">
                    </div>
                    <div>
                        <p><i class="fa-solid fa-hourglass-half"></i> <?php echo formatDateTime($haber['tarih']); ?></p>
                        <h3><?php echo $haber['baslik']; ?></h3>
                    </div>
                </a>

                <?php } ?>
                <div class="pageBtnn">
                    <button class="pageBtns" id="newsPrevBtn" disabled><i
                            class="fa-solid fa-angles-left"></i></button>
                    <span id="newsPageInfo">1 / <?php echo ceil(count($haberler2) / 4); ?></span> <!-- 4 veriye göre -->
                    <button class="pageBtns" id="newsNextBtn"><i class="fa-solid fa-angles-right"></i></button>
                </div>

            </div>

            <div class="newsRight">
                <div class="seyirci">
                    <div class="dateArea1">
                        <h3><i class="fa-solid fa-stopwatch"></i> En Çok İzlenenler</h3>
                    </div>
                    <ul class="list">
                        <li>
                            <?php 
                                $i = 1; // Sayaç başlatılıyor
                                foreach ($filmVerileri as $film): 
                                ?>
                            <a href="filmler/film-detay/<?php echo $film['seo_url']; ?>" class="aling-center">
                                <span><?php  echo $i++; ?></span>
                                <div class="infInside">
                                    <p><?php echo $film['film_adi']; // Toplam kişi sayısı ?></p>
                                </div>
                                <span><i class="fa-solid fa-caret-right"></i></span>
                            </a>
                            <?php endforeach; ?>
                        </li>
                    </ul>
                </div>
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
const itemsPerPage = 8; // Her sayfada gösterilecek film sayısı
const newsBoxes = document.querySelectorAll('.newsBoxMovie'); // Tüm film kutularını seç
const totalPages = Math.ceil(newsBoxes.length / itemsPerPage); // Toplam sayfa sayısını hesapla

// İlk sayfayı göster
showPage(currentPage);

// Sayfayı gösteren fonksiyon
function showPage(page) {
    const start = (page - 1) * itemsPerPage; // Başlangıç indexi
    const end = start + itemsPerPage; // Bitiş indexi

    // Tüm film kutularını gizle
    newsBoxes.forEach((box, index) => {
        box.style.display = (index >= start && index < end) ? 'flex' : 'none';
    });

    // Sayfa bilgilerini güncelle (sayfa numaralandırma göstermek istiyorsanız)
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


<script>
let currentNewsPage = 1; // Mevcut sayfa
const newsItemsPerPage = 4; // Her sayfada gösterilecek haber sayısı
const newsItems = document.querySelectorAll('.newsItem'); // Tüm haber kutularını seç
const totalNewsPages = Math.ceil(newsItems.length / newsItemsPerPage); // Toplam sayfa sayısını hesapla

// İlk sayfayı göster
showNewsPage(currentNewsPage);

// Sayfayı gösteren fonksiyon
function showNewsPage(page) {
    const start = (page - 1) * newsItemsPerPage; // Başlangıç indexi
    const end = start + newsItemsPerPage; // Bitiş indexi

    // Tüm haber kutularını gizle
    newsItems.forEach((item, index) => {
        item.style.display = (index >= start && index < end) ? 'flex' : 'none';
    });

    // Sayfa bilgilerini güncelle (sayfa numarasını ve toplam sayfayı göster)
    document.getElementById('newsPageInfo').textContent = `${page} / ${totalNewsPages}`;

    // Buton durumlarını ayarla (1. sayfada geri butonu devre dışı, son sayfada ileri butonu devre dışı)
    document.getElementById('newsPrevBtn').disabled = (page === 1);
    document.getElementById('newsNextBtn').disabled = (page === totalNewsPages);
}

// Önceki sayfa butonuna tıklama olayı
document.getElementById('newsPrevBtn').addEventListener('click', () => {
    if (currentNewsPage > 1) {
        currentNewsPage--;
        showNewsPage(currentNewsPage);
    }
});

// Sonraki sayfa butonuna tıklama olayı
document.getElementById('newsNextBtn').addEventListener('click', () => {
    if (currentNewsPage < totalNewsPages) {
        currentNewsPage++;
        showNewsPage(currentNewsPage);
    }
});
</script>