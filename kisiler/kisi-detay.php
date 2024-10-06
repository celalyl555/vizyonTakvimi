<?php 
include('../admin/conn.php');
include('../header.php');
include('../SqlQueryHaber.php');

 // SEO URL parametresi alınıyor
 $seourl = isset($_GET['url']) ? $_GET['url'] : '';
 try {
    

    // Veritabanından haber bilgilerini al
    $sql = "SELECT * FROM oyuncular WHERE seo_url = :haberId";
    $stmt = $con->prepare($sql);
    $stmt->bindParam(':haberId', $seourl);
    
    // Sorguyu çalıştır
    $stmt->execute();
    
    // Sonuçları al
    $kisi = $stmt->fetch(PDO::FETCH_ASSOC);
    
     

    if ($kisi) {
        $idOyuncu = $kisi['idoyuncu'];

        // Oyuncunun kategorilerini bulmak için kayıt_kategori ve kategori tablosundan sorgulama yapıyoruz
        $queryKategori = "SELECT kategori.kategoriAd 
                          FROM kayit_kategori 
                          JOIN kategori ON kayit_kategori.kategori_id = kategori.idKategori 
                          WHERE kayit_kategori.kayit_id = :idOyuncu";

        $stmtKategori = $con->prepare($queryKategori);
        $stmtKategori->bindParam(':idOyuncu', $idOyuncu);
        $stmtKategori->execute();

        // Kategorileri alıyoruz
        $kategoriler = $stmtKategori->fetchAll(PDO::FETCH_ASSOC);

        
    }
    
     // Veritabanından oyuncu ilişki tablosu çekildi
     $sql2 = "SELECT DISTINCT  film_id FROM oyuncuiliski WHERE oyuncu_id = :oyuncuid";
     $stmt2 = $con->prepare($sql2);
     $stmt2->bindParam(':oyuncuid', $idOyuncu);
    
     // Sorguyu çalıştır
     $stmt2->execute();
    
     // Sonuçları al
     $filmlerId = $stmt2->fetchAll(PDO::FETCH_ASSOC);
    
    
    $ids = array_column($filmlerId, 'film_id');
    $placeholders = implode(',', array_fill(0, count($ids), '?')); // Hazırlık sorgusu için yer tutucular

    // SQL sorgusu
    $sql = "SELECT * FROM filmler WHERE id IN ($placeholders)";
    $stmt = $con->prepare($sql);
    $stmt->execute($ids);
    // Sonuçları alma
    $filmler = $stmt->fetchAll(PDO::FETCH_ASSOC);
   
   
    
  



} catch (PDOException $e) {
    echo "Veritabanı hatası: " . $e->getMessage();
}

?>


<!-- Table Area Start -->

<section class="haftaSection">

    <div class="haftaMain">

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

                <div class="oyuncuBox">
                    <div class="oyuncuBoxImg">
                        <img src="foto/<?php echo $kisi['resimyol']; ?>" alt="">
                    </div>
                    <div class="col-gap w-70">
                        <h1><?php echo $kisi['adsoyad']; ?></h1>
                        <?php   if (!empty($kategoriler)) {
                                // Kategori adlarını tutmak için bir dizi oluşturuyoruz
                                $kategoriAdlari = [];

                                // Kategori adlarını diziye ekliyoruz
                                foreach ($kategoriler as $kategori) {
                                    $kategoriAdlari[] = $kategori['kategoriAd'];
                                }
                            
                                // Kategori adlarını virgül ile birleştiriyoruz
                                $kategoriMetni = implode(', ', $kategoriAdlari);
                                ?>
                        <div class="mt-1">
                            <p class="titleMovie"><?php echo $kategoriMetni; ?></p>
                        </div>
                        <?php } ?>


                        <?php


// Yaş hesaplama fonksiyonu
function yasHesapla($dogumTarihi, $olumTarihi = null) {
    $dogum = new DateTime($dogumTarihi);
    $simdi = $olumTarihi ? new DateTime($olumTarihi) : new DateTime(); // Ölüm tarihi varsa o gün, yoksa bugünün tarihi kullanılır
    $yas = $simdi->diff($dogum); // Farkı hesapla

    return $yas->y; // Yıl olarak yaş döndürülür
}

// Veritabanından çekilen doğum ve ölüm tarihleri
$dogumTarihi = $kisi['dogum'] ?? null; // Doğum tarihi
$olumTarihi = $kisi['olum'] ?? null;   // Ölüm tarihi

// Eğer doğum tarihi geçersiz değilse ve 0000-00-00 değilse
if (!empty($dogumTarihi) && $dogumTarihi != '0000-00-00') {
    // Ölüm tarihi de varsa
    if (!empty($olumTarihi) && $olumTarihi != '0000-00-00') {
        // Doğum ve ölüm tarihini yazdır, öldüğünde kaç yaşında olduğunu hesapla
        echo '<div class="row-btw mt-1">
                <div>
                    <p class="titleMovie">Doğum - Ölüm Tarihi</p>
                    <p><i class="fa-regular fa-calendar"></i> ' .formatDate($dogumTarihi) . ' - ' . formatDate($olumTarihi) . '</p>
                </div>
                <div>
                    <p class="titleMovie">Öldüğü Yaş</p>
                    <p>' . yasHesapla($dogumTarihi, $olumTarihi) . '</p>
                </div>
            </div>';
    } else {
        // Sadece doğum tarihini yazdır ve şu anki yaşını hesapla
        echo '<div class="row-btw mt-1">
                <div>
                    <p class="titleMovie">Doğum Tarihi</p>
                    <p><i class="fa-regular fa-calendar"></i> ' . formatDate($dogumTarihi) . '</p>
                </div>
                <div>
                    <p class="titleMovie">Yaş</p>
                    <p>' . yasHesapla($dogumTarihi) . '</p>
                </div>
            </div>';
    }
}
?>




                    </div>
                </div>

                <div class="containerTable mt-1">
                    <h2 class="mt-1"><i class="fa-solid fa-clapperboard"></i> Filmografi</h2>
                    <table id="movie-table">
                        <thead>
                            <tr>
                                <th><span class="sort" data-sort="film-name">Film Adı <i class="fas fa-sort"></i></span>
                                </th>
                                <th><span class="sort" data-sort="visyon-date">Vizyon Tarihi <i
                                            class="fas fa-sort"></i></span></th>
                                <th><span class="sort" data-sort="total-revenue">Toplam Hasılat <i
                                            class="fas fa-sort"></i></span></th>
                                <th><span class="sort" data-sort="total-audience">Toplam Seyirci <i
                                            class="fas fa-sort"></i></span></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php      foreach ($filmler as $film) {
                                  $topHasilat = number_format($film['topHasilat'], 2, ',', '.') . ' ₺'; 
                                  $topKisi = isset($film['topKisi']) && !empty($film['topKisi']) ? $film['topKisi'] : "-";
                                echo '<tr>
                                        <td>
                                            <div class="nameBox">
                                                <img class="tableImg" src="kapakfoto/' . htmlspecialchars($film['kapak_resmi']) . '" alt="">
                                                <div>
                                                    <a href="" title="' . htmlspecialchars($film['film_adi']) . '">' . htmlspecialchars($film['film_adi']) . '</a><br>
                                                    <small>' .formatDate($film['vizyon_tarihi']) . '</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>' . formatDate($film['vizyon_tarihi']) . '</td>
                                      <td>' . $topHasilat . '</td>
                                      
                                      <td>' . $topKisi . '</td>
                                      
                                      </tr>';
                            }

                            echo '</table>';
                            ?>



                        </tbody>
                    </table>
                </div>

            </div>

            <div class="newsRight bgnone">
                <h2><i class="fa-solid fa-newspaper"></i> Güncel Haberler</h2>

                <?php foreach ($haberlerGenel as $haber): 
                        if($seourl!= $haber['seo_url']){?>
                <a href="haberler/haber-detay/<?php echo $haber['seo_url']; ?>" class="newsBoxHafta"
                    data-id="<?php echo $haber['idhaber']; ?>">
                    <div class="haftaImg">
                        <img src="haberfoto/<?php echo $haber['haberfoto']; ?>"
                            alt="<?php echo htmlspecialchars($haber['baslik']); ?>">
                    </div>
                    <p><?php echo htmlspecialchars($haber['baslik']); ?></p>
                    <p class="date"><i class="fa-regular fa-clock"></i>
                        <?php echo formatDateTime($haber['tarih']); ?></p>
                </a>

                <?php } endforeach; ?>

            </div>

        </div>

    </div>
</section>

<!-- News Area End -->

<?php 

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
}
 
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

include('../footer.php');?>

</body>

</html>