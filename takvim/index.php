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
    <a href="javascript:void(0);" class="yearBtn" id="prevYear"><i class="fa-solid fa-angles-left"></i> <span id="prevYearText"></span></a>
    <select name="centerBtn" id="yearSelect"></select>
    <a href="javascript:void(0);" class="yearBtn" id="nextYear"><span id="nextYearText"></span> <i class="fa-solid fa-angles-right"></i></a>
</div>


<div class="yearSelect">
    <a href="javascript:void(0);" class="yearBtn " data-month="0">Oca</a>
    <a href="javascript:void(0);" class="yearBtn" data-month="1">Şub</a>
    <a href="javascript:void(0);" class="yearBtn" data-month="2">Mar</a>
    <a href="javascript:void(0);" class="yearBtn" data-month="3">Nis</a>
    <a href="javascript:void(0);" class="yearBtn" data-month="4">May</a>
    <a href="javascript:void(0);" class="yearBtn" data-month="5">Haz</a>
    <a href="javascript:void(0);" class="yearBtn" data-month="6">Tem</a>
    <a href="javascript:void(0);" class="yearBtn" data-month="7">Ağu</a>
    <a href="javascript:void(0);" class="yearBtn" data-month="8">Eyl</a>
    <a href="javascript:void(0);" class="yearBtn" data-month="9">Eki</a>
    <a href="javascript:void(0);" class="yearBtn" data-month="10">Kas</a>
    <a href="javascript:void(0);" class="yearBtn" data-month="11">Ara</a>
</div>


<div class="yearSelect">
    <p>Dağıtımcılar :</p>
    <?php
       echo '<select name="centerBtn" id="centerBtn">';
       echo '<option value="Tüm Dağıtımcılar" selected>Tüm Dağıtımcılar</option>';
       while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
           echo '<option value="' . htmlspecialchars($row['dagitimad']) . '">' . htmlspecialchars($row['dagitimad']) . '</option>';
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
function filterFilms() {
    const selectedDistributor = document.getElementById('centerBtn').value; // Seçilen dağıtımcıyı al
    const films = document.querySelectorAll('.tumBox'); 
    

    films.forEach(function(film) {
        // Her film kutusunun dağıtımcı bilgisine eriş
        const filmDistributor = film.getAttribute('data-distributor'); 
        console.log(filmDistributor);

        if (selectedDistributor === "Tüm Dağıtımcılar") {
            // Eğer "Tüm Dağıtımcılar" seçilmişse tüm filmleri göster
            film.style.display = 'block';
        } else {
            // Aksi takdirde, seçilen dağıtımcı ile uyuşmuyorsa gizle
            if (filmDistributor === selectedDistributor) {
                film.style.display = 'block'; // Eğer eşleşiyorsa göster
            } else {
                film.style.display = 'none'; // Eşleşmiyorsa gizle
            }
        }
    });

   
    checkFilmContainers();
}
function checkFilmContainers() {
    const filmContainers = document.querySelectorAll('.filmContainer2'); // Tüm filmContainer2 öğelerini seç

    filmContainers.forEach(function(container) {
        const boxes = container.querySelectorAll('.tumBox'); // Her container içindeki tumBox öğelerini seç
        let allHidden = true; // Başlangıçta tüm kutuların gizli olduğunu varsayıyoruz

        boxes.forEach(function(box) {
            if (box.style.display !== 'none') {
                allHidden = false; // Eğer en az bir kutu görünürse, allHidden'i false yap
            }
        });

        // Eğer tüm kutular gizli ise, filmContainer2'yi gizle
        if (allHidden) {
            container.style.display = 'none';
        } else {
            container.style.display = 'block'; // Eğer en az bir kutu görünürse, filmContainer2'yi göster
        }
    });
}

// Bu fonksiyonu, film kutularını filtreleyen fonksiyondan sonra çağırabilirsiniz.

 // Film container'ları kontrol et

document.getElementById('centerBtn').addEventListener('change', function() {
    filterFilms(); // Seçim değiştiğinde fonksiyonu çağır
});

</script>






<script>
// Mevcut ay ve yıl değişkenlerini saklayın
let selectedMonth = new Date().getMonth(); // Varsayılan olarak mevcut ayı al
let selectedYear = new Date().getFullYear(); // Varsayılan olarak mevcut yılı al
const currentDate = new Date();
window.onload = function() {
   // Mevcut tarihi al
    selectedYear = currentDate.getFullYear(); // Mevcut yılı al
    selectedMonth = currentDate.getMonth(); // Mevcut ayı al

    // Yıl seçim kutusunu güncelle
    document.getElementById('yearSelect').value = selectedYear;

    // Mevcut ay için takvimi güncelle
    calculateFridays(selectedYear, selectedMonth);
    updateYearButtons(selectedYear); // Yıl butonlarını güncelle
    updateActiveMonthButton(document.querySelector(`.yearBtn[data-month="${selectedMonth}"]`)); // Aktif butonu güncelle
};

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
    let d=0;
    let s=0;
    fridays.forEach(friday => {
    let hasFilmData = false; // Film olup olmadığını takip etmek için
    let tempHTML = ''; // Geçici HTML değişkeni

    // Film verilerini kontrol et ve geçici HTML'ye ekle
    filmData.forEach(film => {
        const filmVizyonTarihi = new Date(film.vizyon_tarihi); // Vizyon tarihini al
        const filmYear = filmVizyonTarihi.getFullYear();
        const filmMonth = filmVizyonTarihi.getMonth();
        const filmDay = filmVizyonTarihi.getDate();

        // Filmin vizyon tarihi bu Cuma gününden önceki hafta içinde mi
        if (filmYear === year && filmMonth === month && isSameWeek(friday, filmVizyonTarihi)) {
            hasFilmData = true; // Film bulundu

            tempHTML += `
            <div class="tumBox filmclass-` + s + ` " data-distributor="${film.dagitimlar}">
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

    // Eğer film verisi varsa takvimHeader ve ilgili film bilgilerini ekle
    if (hasFilmData) {
        infoHTML += `
        <div class="filmContainer2 ">
            <div class="takvimHeader">
                <i class="fa-regular fa-calendar"></i>
                <div id="weekInfo">
                    <h3>${friday.getDate()} ${fridayToTurkishMonth(friday.getMonth())} Cuma</h3>
                    <p class="title">${year} yılı ${getWeekNumber(friday)}. hafta</p>
                </div>
            </div>`;

        infoHTML += tempHTML; // Film verilerini ekle
        infoHTML += `</div>`; // Kapsayıcı divi kapat
        d = 1;
    }
    s++;
});

  if(d===0){
    infoHTML="Film verisi bulunamadı.";
  }
    // Haftaların bilgilerini göster
    document.getElementById('weekInfoContainer').innerHTML = infoHTML;

    filterFilms();
}

// Haftaların aynı olup olmadığını kontrol eden fonksiyon
function isSameWeek(friday, filmDate) {
    const diff = (friday - filmDate) / (1000 * 60 * 60 * 24);
    return diff >= 0 && diff < 7;
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

// Yıl seçim kutusunu güncelle
document.getElementById('yearSelect').addEventListener('change', function() {
    selectedYear = parseInt(this.value); // Seçilen yılı güncelle
    calculateFridays(selectedYear, selectedMonth); // Seçilen yıl ve mevcut ay için takvimi güncelle
    updateYearButtons(selectedYear); // Yıl butonlarını güncelle
});

// Ay butonuna tıklanınca takvimi güncelle
document.querySelectorAll('.yearBtn').forEach(button => {
    button.addEventListener('click', function() {
        selectedMonth = parseInt(this.dataset.month); // Seçilen ayı güncelle
        calculateFridays(selectedYear, selectedMonth); // İlgili ayı hesapla
        updateActiveMonthButton(this); // Aktif butonu güncelle
    });
});

// Ay butonlarının aktif durumunu güncelleyen fonksiyon
function updateActiveMonthButton(activeButton) {
    const monthButtons = document.querySelectorAll('.yearBtn');
    monthButtons.forEach(button => {
        button.classList.remove('activex'); // Tüm butonlardan 'activex' sınıfını kaldır
    });
    activeButton.classList.add('activex'); // Aktif butona 'activex' sınıfını ekle
}

// İlk yüklemede mevcut ay ve yıl için Cuma günlerini göster
calculateFridays(selectedYear, selectedMonth);

// Önceki yıl butonuna tıklanınca yıl seçimini geri al
document.getElementById('prevYear').addEventListener('click', function() {
    selectedMonth = currentDate.getMonth(); 
    console.log(selectedMonth);
    selectedYear--; // Yılı bir azalt
    document.getElementById('yearSelect').value = selectedYear; // Yıl seçim kutusunu güncelle
    calculateFridays(selectedYear, selectedMonth); // Yeni yılı hesapla
    updateYearButtons(selectedYear);  // Yıl butonlarını güncelle
    updateActiveMonthButton(document.querySelector(`.yearBtn[data-month="${selectedMonth}"]`));
});

// Sonraki yıl butonuna tıklanınca yıl seçimini ileri al
document.getElementById('nextYear').addEventListener('click', function() {
    selectedMonth = currentDate.getMonth(); 
    selectedYear++; // Yılı bir artır
    document.getElementById('yearSelect').value = selectedYear; // Yıl seçim kutusunu güncelle
    calculateFridays(selectedYear, selectedMonth); // Yeni yılı hesapla
    updateYearButtons(selectedYear);  // Yıl butonlarını güncelle
    updateActiveMonthButton(document.querySelector(`.yearBtn[data-month="${selectedMonth}"]`));
});

// Yılları ve takvimi başlat
populateYears();
calculateFridays(new Date().getFullYear(), new Date().getMonth());

function populateYears() {
    const currentYear = new Date().getFullYear();
    const selectYear = document.getElementById('yearSelect');

    // 5 yıl geriye ve 5 yıl ileriye kadar olan yılları ekle
    for (let i = currentYear - 6; i <= currentYear + 10; i++) {
        const option = document.createElement('option');
        option.value = i;
        option.text = i;
        if (i === currentYear) {
            option.selected = true;  // Mevcut yıl varsayılan olarak seçili
        }
        selectYear.appendChild(option);
    }

    // Mevcut yıl seçili olduğunda sol ve sağ yıl butonlarını güncelle
    updateYearButtons(currentYear);
}

function updateYearButtons(selectedYear) {
    document.getElementById('prevYearText').innerText = selectedYear - 1;
    document.getElementById('nextYearText').innerText = selectedYear + 1;
}
</script>



