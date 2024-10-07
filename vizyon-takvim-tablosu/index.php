<?php
include('../admin/conn.php');
include('../header.php');

try {
    $today = date('Y-m-d');

// Geçmiş en yakın Cuma gününü bulma
$previousFriday = date('Y-m-d', strtotime('last friday', strtotime($today)));

// Bugünden bir yıl sonrasını al ve en yakın Cuma gününü bul
$nextYear = date('Y-m-d', strtotime('+1 year', strtotime($today)));
$nextFriday = date('Y-m-d', strtotime('next friday', strtotime($nextYear)));

// Eğer GET parametreleri varsa onları al, yoksa yukarıda hesapladığımız tarihleri kullan
$start = $_GET['start'] ?? $previousFriday;
$end = $_GET['end'] ?? $nextFriday;
    // Film verilerini ve dağıtımcıları al
    $sql = "SELECT f.*,
    GROUP_CONCAT(DISTINCT ftur.filmturu SEPARATOR ', ') AS filmturleri,
    GROUP_CONCAT(DISTINCT s.dagitimad SEPARATOR ', ') AS dagitimlar,
    GROUP_CONCAT(DISTINCT st.studyoad SEPARATOR ', ') AS stüdyolar
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
    LEFT JOIN 
        film_studyolar fs ON f.id = fs.film_id
    LEFT JOIN 
        stüdyo st ON fs.studyo_id = st.id
    WHERE 
        f.statu = 1 AND 
        f.vizyon_tarihi BETWEEN :startt AND :endd
    GROUP BY 
        f.id;";


    
    $stmt = $con->prepare($sql);
    $stmt->bindParam(':startt', $start);
    $stmt->bindParam(':endd', $end);
    $stmt->execute();
    $sonuclar = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Dağıtımcıları al
    $sql = "SELECT dagitimad, iddagitim FROM sinemadagitim";
    $stmt = $con->query($sql);
    $dagitimlar = $stmt->fetchAll(PDO::FETCH_ASSOC); // Tüm satırları al

    // Filmleri tarihine göre grupla
    $filmlerByDate = [];
    $firstNineDagitimlar = array_slice($dagitimlar, 0, 9); // İlk 9 dağıtımcıyı alın

    foreach ($sonuclar as $film) {
        $vizyonTarihi = $film['vizyon_tarihi']; // Vizyon tarihini uygun sütundan al
        $dagitimlar = explode(', ', $film['dagitimlar']); // Dağıtımcıları al

        // Tarihe göre film ekle
        if (!isset($filmlerByDate[$vizyonTarihi])) {
            $filmlerByDate[$vizyonTarihi] = [];
        }

        // Dağıtımcıyı kontrol et ve filme ekle
        foreach ($dagitimlar as $dagitim) {
            $filmlerByDate[$vizyonTarihi][$dagitim][] = $film;
        }
    }

    // Diğer filmler için (ilk 9 dağıtımcıya ait olmayan filmleri burada kontrol edin)
    $otherFilmsByDate = [];
    foreach ($sonuclar as $film) {
        $vizyonTarihi = $film['vizyon_tarihi'];
        $dagitimlar = explode(', ', $film['dagitimlar']); // Dağıtımcıları al

        // Eğer film ilk 9 dağıtımcıdan birine ait değilse
        foreach ($dagitimlar as $dagitim) {
            if (!in_array($dagitim, array_column($firstNineDagitimlar, 'dagitimad'))) {
                if (!isset($otherFilmsByDate[$vizyonTarihi])) {
                    $otherFilmsByDate[$vizyonTarihi] = [];
                }
                $otherFilmsByDate[$vizyonTarihi][] = $film;
                break; // Bir film sadece bir kez "Diğer Filmler"e eklenmelidir
            }
        }
    }
} catch (PDOException $e) {
    echo "Bağlantı hatası: " . htmlspecialchars($e->getMessage());
}
?>



<!-- ============================================================================== -->

<!-- Table Area Start -->

<section class="haftaSection">

    <div class="haftaMain">

        <h2><i class="fa-regular fa-calendar-days"></i> Vizyon Takvimi Tablosu</h2>
        <p class="title">Filmlerin vizyon tarihleri</p>

        <div class="settingsBox">
            <div class="settingsBox center-f start-f">
                <p>Filmlerin vizyon tarih aralığını seçebilirsiniz.</p>
            </div>
            <form method="GET" action="">
    <div class="settingsBox end-f">
        <div>
            <label for="start">Başlangıç</label>
            <input type="date" id="start" name="start" required>
        </div>

        <div>
            <label for="end">Bitiş</label>
            <input type="date" id="end" name="end" required>
        </div>
        <div>
            <button class="cfBtn active" type="submit">Filtrele</button>
        </div>
    </div>
</form>

        </div>

        <div class="containerTable">


        <table id="movie-table-tum">
    <thead>
        <tr>
            <th>Tarih</th>
            <?php 
            // İlk 9 veriyi al
            foreach ($firstNineDagitimlar as $row) { ?>
                <th><?php echo htmlspecialchars($row['dagitimad']); ?></th>
            <?php } ?>
            <th>Diğer Filmler</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($filmlerByDate as $vizyonTarihi => $dagitimlar) : ?>
        <tr>
            <td class="numbertT"><?php echo date('d.m.Y', strtotime($vizyonTarihi)); ?></td>
            <?php 
            foreach ($firstNineDagitimlar as $row) {
                $dagitimAd = $row['dagitimad']; // Dağıtımcı adını alıyoruz
                echo '<td>';
                
                // Vizyon tarihine göre gelen filmleri kontrol et
                if (isset($dagitimlar[$dagitimAd])) {
                    foreach ($dagitimlar[$dagitimAd] as $film) {
                        // Stüdyo adı al
                        $studyolar = !empty($film['stüdyolar']) ? '<span style="color: red;">' . htmlspecialchars($film['stüdyolar']) . '</span>' : '<p class="titleDown red">Stüdyo verisi yok</p>';
                        
                        echo '<div class="moviesTd">
                        <a href="filmler/film-detay/' . htmlspecialchars($film['seo_url']) . '">' . htmlspecialchars($film['film_adi']) . '</a>
                        <p class="titleDown">' . $studyolar . '</p> <!-- Stüdyo bilgisi burada gösterilecek -->
                        </div>';
                    }
                }
                echo '</td>';
            }
            ?>
            <td>
                <?php 
                if (isset($otherFilmsByDate[$vizyonTarihi])) {
                    foreach ($otherFilmsByDate[$vizyonTarihi] as $film) {
                        // Diğer filmler için stüdyo adı al
                        $studyolarOther = !empty($film['stüdyolar']) ? '<span style="color: red;">' . htmlspecialchars($film['stüdyolar']) . '</span>' : '<p class="titleDown red">Stüdyo verisi yok</p>';
                        
                        echo '<div class="moviesTd">
                               <a href="filmler/film-detay/' . htmlspecialchars($film['seo_url']) . '">' . htmlspecialchars($film['film_adi']) . '</a>
                                <p class="titleDown">' . $studyolarOther . '</p> <!-- Diğer filmler için stüdyo bilgisi -->
                              </div>';
                    }
                }
                ?>
            </td>
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
<div class="settingsBox end-f">
    <div>
        <label for="start">Başlangıç</label>
        <input type="date" id="start" required>
    </div>

    <div>
        <label for="end">Bitiş</label>
        <input type="date" id="end" required>
    </div>
</div>


<script>
$(function() {
    // Bugünün tarihi
    const today = new Date();
    const urlParams = new URLSearchParams(window.location.search);
    const start = urlParams.get('start');
    const end = urlParams.get('end');

    // Bugünün tarihine en yakın geçmiş Cuma gününü bulma
    const lastFriday = new Date(today);
    lastFriday.setDate(today.getDate() - ((today.getDay() + 2) % 7));

    if (start) {
        document.getElementById('start').value = start;
    } else {
        $('#start').val(lastFriday.toISOString().split('T')[0]);
    }

    // Bugünden 1 yıl sonra en yakın Cuma gününü bulma
    const nextYear = new Date(today);
    nextYear.setFullYear(today.getFullYear() + 1);
    const nextFriday = new Date(nextYear);
    nextFriday.setDate(nextFriday.getDate() + (5 - nextFriday.getDay() + 7) % 7);

    if (end) {
        document.getElementById('end').value = end;
    } else {
        $('#end').val(nextFriday.toISOString().split('T')[0]);
    }
});

</script>



</body>

</html>