<?php 
include('../header.php');
include('../admin/conn.php'); 
include('../SqlQueryFilm.php'); 


$hafta = date('W'); // Yılın kaçıncı haftası
$haftaningunu = date('N'); 
$currentDate = date('Y-m-d'); // Bugünün tarihi
$currentDayMonth = date('m-d'); // Bugünkü ay ve gün (örn: "10-08")
$yearr =date("Y");
// SQL sorgusu: Bugün itibarıyla önceki gün ve ay bilgilerine sahip verileri almak için
$sql = "SELECT 
            YEAR(vizyon_tarihi) AS yil, 
            SUM(topHasilat) AS toplam_hasilat, 
            SUM(topKisi) AS toplam_kisi,
            COUNT(*) AS toplam_film, 
            CASE 
                WHEN SUM(topKisi) > 0 THEN SUM(topHasilat) / SUM(topKisi)
                ELSE 0 
            END AS ortalama_bilet
        FROM filmler 
        WHERE statu = 1 
        AND (DATE_FORMAT(vizyon_tarihi, '%m-%d') < '$currentDayMonth')
       
        GROUP BY yil 
        ORDER BY yil ASC";

try {
    $stmt = $con->prepare($sql);
    $stmt->execute();
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    die("Sorgu hatası: " . $e->getMessage());
}





$sql2 = "SELECT 
            YEAR(vizyon_tarihi) AS yil, 
            SUM(topHasilat) AS toplam_hasilat, 
            SUM(topKisi) AS toplam_kisi,
            COUNT(*) AS toplam_film, 
            CASE 
                WHEN SUM(topKisi) > 0 THEN SUM(topHasilat) / SUM(topKisi)
                ELSE 0 
            END AS ortalama_bilet
        FROM filmler 
        WHERE statu=1 
       
        GROUP BY yil 
        ORDER BY yil asc";

try {
    $stmt2 = $con->prepare($sql2);
    $stmt2->execute();
    $rows2 = $stmt2->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    die("Sorgu hatası: " . $e->getMessage());
}
?>

<!-- ============================================================================== -->

<!-- Table Area Start -->

<section class="haftaSection">

    <div class="haftaMain">

        <h2><i class="fa-solid fa-box-open"></i> Yıllık Gişe Hasılatı</h2>
        <p>Yıllara Göre Toplam Seyirci ve Hasılat Sayıları</p>

        <h3 class="infoYear">Yılların ilk <span><?php echo $hafta." hafta ".$haftaningunu ." günü "; ?></span>
            karşılaştırması</h3>
        <p class="silent">Tablo, yılın başından bugüne kadar olan dönemi, geçen yılın aynı dönemiyle kıyaslamaktadır.
        </p>

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
                <div class="containerAy">
                    <div class="tab-content-hafta">
                        <div class="month">
                            <table class="mt-0" id="myTable">
                                <thead>
                                    <tr>
                                        <th>Yıl</th>
                                        <th>Toplam Hasılat</th>
                                        <th>Toplam Seyirci</th>
                                        <th>Toplam Film</th>
                                        <th>Ort. Bilet</th>
                                    </tr>
                                </thead>
                                <tbody>
    <?php 
    $previous_row = null; // Önceki yılın verisi için değişken
    $last_row = end($rows); // Son yılın verisini bulmak için

    foreach($rows as $row) { 
        // İlk yıl için değişim oranı hesaplamıyoruz
        if ($previous_row) {
            // Değişim oranını hesapla
            $hasilat_change = (($row['toplam_hasilat'] - $previous_row['toplam_hasilat']) / $previous_row['toplam_hasilat']) * 100;
            $kisi_change = (($row['toplam_kisi'] - $previous_row['toplam_kisi']) / $previous_row['toplam_kisi']) * 100;
        
            // Değişim oranı için ikon ve sınıf belirle
            $hasilat_icon = $hasilat_change < 0 ? 'fa-down-long' : 'fa-up-long';
            $hasilat_color = $hasilat_change < 0 ? 'decrease' : 'asc';
            $kisi_icon = $kisi_change < 0 ? 'fa-down-long' : 'fa-up-long';
            $kisi_color = $kisi_change < 0 ? 'decrease' : 'asc';
        } else {
            // İlk satır için ikonlar boş, renkler nötr
            $hasilat_icon = '';
            $kisi_icon = '';
            $hasilat_color = '';
            $kisi_color = '';
        }
    ?>
    <tr>
        <td><span class="clicka"><?php echo $row['yil']; ?></span></td>
        <td>
            <span class="<?php echo $hasilat_color; ?>">
                <i class="fa-solid <?php echo $hasilat_icon; ?>"></i> 
                <?php 
                // İlk yıl haricinde değişim sıfır değilse yazdır
                if ($previous_row && $hasilat_change != 0) {
                    echo number_format($hasilat_change, 2, ',', '.') . " %"; 
                }
                ?>
            </span>
            <?php echo number_format($row['toplam_hasilat'], 2, ',', '.'); ?>
        </td>
        <td>
            <span class="<?php echo $kisi_color; ?>">
                <i class="fa-solid <?php echo $kisi_icon; ?>"></i> 
                <?php 
                // İlk yıl haricinde değişim sıfır değilse yazdır
                if ($previous_row && $kisi_change != 0) {
                    echo number_format($kisi_change, 2, ',', '.') . " %"; 
                }
                ?>
            </span>
            <?php echo number_format($row['toplam_kisi'], 3, '.', '.'); ?>
        </td>
        <td><?php echo $row['toplam_film']; ?></td>
        <td><?php echo '₺' . number_format($row['ortalama_bilet'], 2, ',', '.'); ?></td>
    </tr>
    <?php 
        // Mevcut satırı önceki satır olarak kaydet
        $previous_row = $row; 
    } 
    ?>
    
</tbody>
                            </table>

                        </div>

                        <h3>Tüm Yıllar Karşılaştırması</h3>

                        <div class="month">
                        <table class="mt-0" id="myTable2">
                                <thead>
                                    <tr>
                                        <th>Yıl</th>
                                        <th>Toplam Hasılat</th>
                                        <th>Toplam Seyirci</th>
                                        <th>Toplam Film</th>
                                        <th>Ort. Bilet</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $previous_row = null; // Önceki yılın verisi için değişken
                                        foreach($rows2 as $row1) {
                                          if($row1['yil']!=$yearr) {
                                                     if ($previous_row) {
                                                         // Değişim oranını hesapla
                                                         $hasilat_change = (($row1['toplam_hasilat'] - $previous_row['toplam_hasilat']) / $previous_row['toplam_hasilat']) * 100;
                                                         $kisi_change = (($row1['toplam_kisi'] - $previous_row['toplam_kisi']) / $previous_row['toplam_kisi']) * 100;
                                                    
                                                         // Değişim oranı için ikon ve sınıf belirle
                                                         $hasilat_icon = $hasilat_change < 0 ? 'fa-down-long' : 'fa-up-long';
                                                         $hasilat_color = $hasilat_change < 0 ? 'decrease' : 'asc';
                                                         $kisi_icon = $kisi_change < 0 ? 'fa-down-long' : 'fa-up-long';
                                                         $kisi_color = $kisi_change < 0 ? 'decrease' : 'asc';
                                                    
                                                     } else {
                                                         // İlk satır için ikonlar boş
                                                         $hasilat_icon = '';
                                                         $kisi_icon = '';
                                                     }
                                                 ?>
                                             <tr>
                                                 <td><span class="clicka"><?php echo $row1['yil']; ?></span></td>
                                                 <td>
                                                     <span class="<?php echo $hasilat_color; ?>">
                                                         <i class="fa-solid <?php echo $hasilat_icon; ?>"></i> <?php 
                                                      // Eğer değişim sıfır değilse yazdır
                                                      if ($previous_row) {
                                                          echo number_format($hasilat_change, 2, ',', '.') . " %"; 
                                                      }
                                                      ?>
                                                           </span>
                                                           <?php echo number_format($row1['toplam_hasilat'], 2, ',', '.'); ?>
                                                       </td>
                                                       <td>
                                                           <span class="<?php echo $kisi_color; ?>">
                                                               <i class="fa-solid <?php echo $kisi_icon; ?>"></i> <?php 
                                                      // Eğer değişim sıfır değilse yazdır
                                                      if ($previous_row) {
                                                          echo number_format($kisi_change, 2, ',', '.') . " %"; 
                                                      }
                                                      ?>
                                                     </span>
                                                     <?php echo number_format($row1['toplam_kisi'], 3, '.', '.'); ?>
                                                 </td>
                                                 <td><?php echo $row1['toplam_film']; ?></td>
                                                 <td><?php echo '₺' . number_format($row1['ortalama_bilet'], 2, ',', '.'); ?></td>
                                             </tr>
                                                                                     <?php 
                                                     $previous_row = $row1; // Mevcut satırı önceki satır olarak kaydet
                                                 }
                                                 
                                                }         
                                                 ?>
                                </tbody>
                            </table>
                        </div>

                    </div>

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
}

include('../footer.php');?>

</body>

</html>
<script>
sortTable(0);

function sortTable(columnIndex) {
    const table = document.getElementById("myTable");
    const tbody = table.tBodies[0];
    const rows = Array.from(tbody.rows);
    const isAscending = tbody.getAttribute("data-sort") === "asc";
    const directionModifier = isAscending ? 1 : -1;

    // Sıralama
    const sortedRows = rows.sort((a, b) => {
        const aColText = a.cells[columnIndex].innerText.replace('₺', '').replace('.', '').replace(',', '.');
        const bColText = b.cells[columnIndex].innerText.replace('₺', '').replace('.', '').replace(',', '.');

        return (aColText > bColText ? 1 : -1) * directionModifier;
    });

    // Satırları temizle ve yeni sıralı satırları ekle
    while (tbody.firstChild) {
        tbody.removeChild(tbody.firstChild);
    }
    tbody.append(...sortedRows);

    // Yönü değiştir
    tbody.setAttribute("data-sort", isAscending ? "desc" : "asc");
}
</script>


<script>
sortTable2(0);

function sortTable2(columnIndex) {
    const table = document.getElementById("myTable2");
    const tbody = table.tBodies[0];
    const rows = Array.from(tbody.rows);
    const isAscending = tbody.getAttribute("data-sort") === "asc";
    const directionModifier = isAscending ? 1 : -1;

    // Sıralama
    const sortedRows = rows.sort((a, b) => {
        const aColText = a.cells[columnIndex].innerText.replace('₺', '').replace('.', '').replace(',', '.');
        const bColText = b.cells[columnIndex].innerText.replace('₺', '').replace('.', '').replace(',', '.');

        return (aColText > bColText ? 1 : -1) * directionModifier;
    });

    // Satırları temizle ve yeni sıralı satırları ekle
    while (tbody.firstChild) {
        tbody.removeChild(tbody.firstChild);
    }
    tbody.append(...sortedRows);

    // Yönü değiştir
    tbody.setAttribute("data-sort", isAscending ? "desc" : "asc");
}
</script>