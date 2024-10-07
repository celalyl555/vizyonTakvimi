<?php 
include('../admin/conn.php');
include('../header.php');
include('../SqlQueryDizi.php');
?>
<!-- 51, 94 yusufun yapacağı yerler. -->
    <!-- ============================================================================== -->
    
    <!-- Table Area Start -->

    <section class="haftaSection">

        <div class="haftaMain">

            <h2><i class="fa-solid fa-couch"></i> Diziler</h2>
            <p>Diziler ile ilgili aradığınız herşey burada.</p>
            
        </div>

    </section>

    <!-- Table Area End -->

    <!-- ============================================================================== -->

    <!-- vizyon Area End -->

    <section>
        <div class="vizyon">
        
            <div id="vizyondaYeni" class="tabcontent">
            <!-- 2 hafta öncesine kadar -->
                <div class="vizyonSlier">
                    <div class="vizyonLeft">
                        <button class="arrows left"><i class="fa-solid fa-caret-left"></i></button>
                        <?php
                        if (!empty($enEskiDizi)): ?>
                        <a href="diziler/dizi-detay/<?php echo $enEskiDizi['seo_url']; ?>" class="mainvizyonImg">
                            <img src="kapakfoto/<?php echo $enEskiDizi['kapak_resmi'];?>" alt="vizyon">
                            <div class="overlay1">
                            <span class="namevizyon">
                                <?php 
                                // Film adı boşsa veya null ise "boş" mesajını göster
                                if (!empty($enEskiDizi['film_adi'])) {
                                    echo $enEskiDizi['film_adi'];
                                } else {
                                    // echo 'yusuf burayı da doldursana';
                                }
                                ?>
                            </span>
                            </div>
                        </a>
                        <?php endif; ?>

                        <button class="arrows right"><i class="fa-solid fa-caret-right"></i></button>
                    </div>
                    <div class="vizyonRight">
                    <?php foreach ($dizilerVizyon as $dizi): ?>
                        <a href="diziler/dizi-detay/<?php echo $dizi['seo_url']; ?>" class="vizyonBox">
                            <div class="vizyonBoxImg">
                                <img src="kapakfoto/<?php echo $dizi['kapak_resmi'];?>" alt="">
                            </div>
                            <h3><?php echo $dizi['film_adi'];?></h3>
                        </a>
                        <?php endforeach; ?>
                    </div>
                </div>

            </div>

        </div>
    </section>
    <section>

<div class="news">

    <h2><i class="fa-solid fa-couch"></i> Diziler</h2>

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
    <!-- vizyon Area End -->

    <!-- ============================================================================== -->
     
    <!-- News Area End -->

    <section>

        <div class="news">

            <h2><i class="fa-solid fa-couch"></i> Diziler'den Haberler</h2>

            <div class="newsInside">

                  <div class="newsLeft">
                <?php  foreach ($haberler3 as $haber) { ?>
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
                
                <div class="newsRight bgnone">
                <h2><i class="fa-solid fa-newspaper"></i> Popüler Haberler</h2>
                <?php
                    foreach($haberler2 as $haber):?>
                <a href="haberler/haber-detay/<?php echo $haber['seo_url']; ?>" class="newsBoxHafta">
                    <div class="haftaImg">
                        <img src="haberfoto/<?php echo $haber['haberfoto'];?>" alt="">
                    </div>
                    <p><?php echo $haber['baslik'];?></p> 
                    <p class="date"> <i class="fa-regular fa-clock"></i> <?php echo formatDate($haber['tarih']);?></p>
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
</body>
</html>