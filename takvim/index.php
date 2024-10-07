<?php 
include('../admin/conn.php');
include('../header.php');
include('../SqlQueryFilm.php');

try {
    $sql = "SELECT f.*,
    GROUP_CONCAT(DISTINCT ftur.filmturu SEPARATOR ', ') AS filmturleri,
    GROUP_CONCAT(DISTINCT s.dagitimad SEPARATOR ', ') AS dagitimlar
    FROM 
        filmler f 
   
    LEFT JOIN 
        film_filmturu ft ON f.id = ft.film_id
    LEFT JOIN 
        filmturleri ftur ON ft.filmturu_id = ftur.idfilm
    LEFT JOIN 
        film_dagitim d ON f.id = d.film_id
    LEFT JOIN 
        sinemadagitim s ON d.dagitim_id = s.iddagitim
    WHERE 
        f.statu = 1
    GROUP BY 
        f.id;
";


    // Sorguyu çalıştıralım
    $stmt = $con->prepare($sql);
    $stmt->execute();
    
    // Sonuçları alalım
    $sonuclar = $stmt->fetchAll(PDO::FETCH_ASSOC);
 
    // Sonuçları yazdıralım
   
    $sql = "SELECT dagitimad, iddagitim FROM sinemadagitim";
    $stmt = $con->query($sql);

} catch (PDOException $e) {
    echo "Bağlantı hatası: " . $e->getMessage();
}

?>

<!-- ============================================================================== -->

<!-- Table Area Start -->

<section class="haftaSection">

    <div class="haftaMain">

        <h2><i class="fa-regular fa-calendar"></i> Takvim</h2>
        <p>Filmlerin vizyon tarihleri</p>

    </div>

</section>
<section class="pt-0">

    <div class="news">

        <div class="newsInside">

            <div class="newsLeft">

                <div class="yearSelect">
                    <a href="javascript:void(0);" class="yearBtn" id="prevYear"><i class="fa-solid fa-angles-left"></i>
                        2023</a>
                    <select name="centerBtn" id="yearSelect">
                        <option value="2024">2024</option>
                        <option value="2023">2023</option>
                        <option value="2022">2022</option>
                        <option value="2021">2021</option>
                    </select>
                    <a href="javascript:void(0);" class="yearBtn" id="nextYear">2025 <i
                            class="fa-solid fa-angles-right"></i></a>
                </div>
                <div class="yearSelect">
                    <a href="javascript:void(0);" class="yearBtn activex" data-month="0">Oca</a>
                    <a href="javascript:void(0);" class="yearBtn activex" data-month="1">Şub</a>
                    <a href="javascript:void(0);" class="yearBtn activex" data-month="2">Mar</a>
                    <a href="javascript:void(0);" class="yearBtn activex" data-month="3">Nis</a>
                    <a href="javascript:void(0);" class="yearBtn activex" data-month="4">May</a>
                    <a href="javascript:void(0);" class="yearBtn activex" data-month="5">Haz</a>
                    <a href="javascript:void(0);" class="yearBtn activex" data-month="6">Tem</a>
                    <a href="javascript:void(0);" class="yearBtn activex" data-month="7">Ağu</a>
                    <a href="javascript:void(0);" class="yearBtn active" data-month="8">Eyl</a>
                    <a href="javascript:void(0);" class="yearBtn activex" data-month="9">Eki</a>
                    <a href="javascript:void(0);" class="yearBtn activex" data-month="10">Kas</a>
                    <a href="javascript:void(0);" class="yearBtn activex" data-month="11">Ara</a>
                </div>

                <div class="yearSelect">
                    <p>Dağıtımcılar :</p>
                    <?php
                       echo '<select name="centerBtn" id="centerBtn">';
                       echo '<option value="Tüm Dağıtımcılar" selected>Tüm Dağıtımcılar</option>';
                   
                       // Verileri döngü ile option etiketlerine yazdır
                       while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                           echo '<option value="' . htmlspecialchars($row['iddagitim']) . '">' . htmlspecialchars($row['dagitimad']) . '</option>';
                       }
                   
                       echo '</select>';
                  
                       ?>
                </div>
                <div id="filmData" data-filmler='<?php echo htmlspecialchars(json_encode($sonuclar), ENT_QUOTES, 'UTF-8'); ?>'></div>

                <div class="containerAy mt-1">
                    <div class="tab-content-hafta">
                        <div class="month mt-1" id="weekInfoContainer">

                            <div class="takvimHeader">
                                <i class="fa-regular fa-calendar"></i>
                                <div id="weekInfo">
                                    <!-- Haftanın ve tarihlerin gösterileceği alan -->
                                    <h3>6 Eylül Cuma</h3>
                                    <p class="title">2024 yılı 37. hafta</p>
                                </div>
                            </div>

                            <div class="tum-zamanlar">
                                <?php foreach ($sonuclar as $satir) { ?>
                                <div class="tumBox">
                                    <div class="tumBoxLeft">
                                        <a href="" class="tumBoxLeftImg">
                                            <img src="kapakfoto/<?php echo $satir['kapak_resmi']; ?>" alt="">
                                        </a>
                                        <div class="col-gap">
                                            <div>
                                                <a href="" class="movieTitle1"><?php echo $satir['film_adi']; ?></a>
                                            </div>
                                            <div>
                                                <p class="title"><?php echo $satir['filmturleri']; ?></p>
                                            </div>
                                            <div>
                                                <p><strong>Dağıtımcı</strong></p>
                                                <a href="" class="title"><?php echo $satir['dagitimlar']; ?></a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php } ?>
                            </div>


                        </div>

                    </div>

                </div>

            </div>

            <div class="newsRight bgnone">
                <h2><i class="fa-solid fa-newspaper"></i> Vizyona Girecekler</h2>
                <?php
                        foreach($filmlerGenelYakin as $yakinFilmler):?>
                <a href="" class="newsBoxHafta">
                    <div class="haftaImg">
                        <img src="kapakfoto/<?php echo $yakinFilmler['kapak_resmi'];?>" alt="">
                    </div>
                    <p><?php echo $yakinFilmler['film_adi'];?></p>
                    <p class="date"><i
                            class="fa-regular fa-clock"></i><?php echo formatDate($yakinFilmler['vizyon_tarihi']);?></p>
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
// Filmleri JSON formatında al
const filmDataElement = document.getElementById('filmData');
const filmData = JSON.parse(filmDataElement.getAttribute('data-filmler'));

// Cuma günlerini ve hafta bilgilerini hesaplayan fonksiyon
function calculateFridays(year, month) {
    const fridays = [];
    const firstDay = new Date(year, month, 1);
    let day = firstDay;

    // Ay içindeki tüm Cuma günlerini bul
    while (day.getMonth() === month) {
        if (day.getDay() === 5) { // 5 = Cuma
            fridays.push(new Date(day));
        }
        day.setDate(day.getDate() + 1);
    }

    // Haftaları ve tarihleri göster
    let infoHTML = '';

    fridays.forEach(friday => {
        let weekNumber = getWeekNumber(friday);
        infoHTML += `
        <div class="takvimHeader">
            <i class="fa-regular fa-calendar"></i>
            <div id="weekInfo">
                <h3>${friday.getDate()} ${fridayToTurkishMonth(friday.getMonth())} Cuma</h3>
                <p class="title">${year} yılı ${weekNumber}. hafta</p>
            </div>
        </div>`;

        // Film verilerini kontrol et ve ekle
        filmData.forEach(film => {
            const filmVizyonTarihi = new Date(film.vizyon_tarihi); // Vizyon tarihini al
            if (filmVizyonTarihi >= friday && filmVizyonTarihi < new Date(friday.getTime() + 86400000)) { // 1 gün içinde
                infoHTML += `
                <div class="tumBox">
                    <div class="tumBoxLeft">
                        <a href="filmler/film-detay/${film.seo_url}" class="tumBoxLeftImg">
                            <img src="kapakfoto/${film.kapak_resmi}" alt="">
                        </a>
                        <div class="col-gap">
                            <div>
                                <a href="filmler/film-detay/${film.seo_url}" class="movieTitle1">${film.film_adi}</a>
                            </div>
                            <div>
                                <p class="title">${film.filmturleri}</p>
                            </div>
                            <div>
                                <p><strong>Dağıtımcı</strong></p>
                                <a href="" class="title">${film.dagitimlar}</a>
                            </div>
                        </div>
                    </div>
                </div>`;
            }
        });
    });

    // Haftaların bilgilerini göster
    document.getElementById('weekInfoContainer').innerHTML = infoHTML;
}

// Diğer fonksiyonlar
function getWeekNumber(date) {
    const startDate = new Date(date.getFullYear(), 0, 1);
    const days = Math.floor((date - startDate) / (24 * 60 * 60 * 1000));
    return Math.ceil((date.getDay() + 1 + days) / 7);
}

function fridayToTurkishMonth(month) {
    const months = ["Ocak", "Şubat", "Mart", "Nisan", "Mayıs", "Haziran", "Temmuz", "Ağustos", "Eylül", "Ekim", "Kasım", "Aralık"];
    return months[month];
}

// Ay butonlarına tıklama olayı ekle
document.querySelectorAll('.yearBtn').forEach(button => {
    button.addEventListener('click', function() {
        const month = parseInt(this.dataset.month);
        const year = new Date().getFullYear(); // Mevcut yılı kullan
        calculateFridays(year, month); // İlgili ayı hesapla
        updateActiveButton(this); // Aktif butonu güncelle
    });
});

// Aktif butonu güncelleyen fonksiyon
function updateActiveButton(activeButton) {
    document.querySelectorAll('.yearBtn').forEach(button => {
        button.classList.remove('active'); // Tüm butonların aktif sınıfını kaldır
    });
    activeButton.classList.add('active'); // Tıklanan butona aktif sınıfı ekle
}

// İlk yüklemede mevcut ay ve yıl için Cuma günlerini göster
calculateFridays(new Date().getFullYear(), new Date().getMonth());

</script>
