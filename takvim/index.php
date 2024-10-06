<?php 
include('../admin/conn.php');
include('../header.php');
include('../SqlQueryFilm.php');

try {
    $sql = "
    SELECT 
        f.*, 
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
    GROUP BY 
        f.id;
    ";

    // Sorguyu çalıştıralım
    $stmt = $con->prepare($sql);
    $stmt->execute();
    
    // Sonuçları alalım
    $sonuclar = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Sonuçları yazdıralım
   
    
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

    <!-- Table Area End -->

    <!-- ============================================================================== -->

    <!-- ============================================================================== -->
     
    <!-- News Area End -->

    <section class="pt-0">

        <div class="news">

            <div class="newsInside">

                <div class="newsLeft">

                    <div class="yearSelect">
                        <a href="" class="yearBtn active"><i class="fa-solid fa-angles-left"></i> 2023</a>
                        <select name="centerBtn" id="centerBtn">
                            <option value="2024">2024</option>
                            <option value="2023">2023</option>
                            <option value="2022">2022</option>
                            <option value="2021">2021</option>
                        </select>
                        <a href="" class="yearBtn">2025 <i class="fa-solid fa-angles-right"></i></a>
                    </div>
                    <div class="yearSelect">
                        <a href="" class="yearBtn activex">Oca</a>
                        <a href="" class="yearBtn activex">Sub</a>
                        <a href="" class="yearBtn activex">Mar</a>
                        <a href="" class="yearBtn activex">Nis</a>
                        <a href="" class="yearBtn activex">May</a>
                        <a href="" class="yearBtn activex">Haz</a>
                        <a href="" class="yearBtn activex">Tem</a>
                        <a href="" class="yearBtn activex">Ağu</a>
                        <a href="" class="yearBtn active">Eyl</a>
                        <a href="" class="yearBtn activex">Eki</a>
                        <a href="" class="yearBtn activex">Kas</a>
                        <a href="" class="yearBtn activex">Ara</a>
                    </div>

                    <div class="yearSelect">
                        <p>Dağıtımcılar :</p>
                        <select name="centerBtn" id="centerBtn">
                            <option value="Tüm Dağıtımcılar" selected>Tüm Dağıtımcılar</option>
                            <option value="A90 Pictures">A90 Pictures</option>
                            <option value="Başka Sinema">Başka Sinema</option>
                            <option value="Bir Film">Bir Film</option>
                        </select>
                    </div>

                    <div class="containerAy mt-1">
                        <div class="tab-content-hafta">
                            <div class="month mt-1">

                                <div class="takvimHeader">
                                    <i class="fa-regular fa-calendar"></i>
                                    <div>
                                        <h3>6 eylül cuma</h3>
                                        <p class="title">2024 yılı 37. hafta</p>
                                    </div>
                                </div>

                                <div class="tum-zamanlar">
                                    <?php  foreach ($sonuclar as $satir) {?>
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
                                    <?php }  ?>     
                                    

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