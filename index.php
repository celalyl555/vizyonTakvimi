
<?php 
include('header.php');
include('admin/conn.php');
?>
    <!-- ============================================================================== -->
    <!-- Main Area  Start -->

<!-- SQL Query -->
 <?php
    $sqlFilmlerVizyon = "SELECT * FROM filmler 
    WHERE vizyon_tarihi >= CURDATE() - INTERVAL 2 WEEK";
    $stmtFilmlerVizyon = $con->query($sqlFilmlerVizyon);
    $filmlerVizyon = $stmtFilmlerVizyon->fetchAll(PDO::FETCH_ASSOC);

    $sqlFilmlerYakin = "SELECT * FROM filmler 
    WHERE vizyon_tarihi <= CURDATE() + INTERVAL 2 WEEK";
    $stmtFilmlerYakin = $con->query($sqlFilmlerYakin);
    $filmlerYakin = $stmtFilmlerYakin->fetchAll(PDO::FETCH_ASSOC);

    $sqlHaberler = "SELECT * FROM haberler ORDER BY tarih DESC LIMIT 4"; // En yeni 4 haberi al
    $stmtHaberler = $con->query($sqlHaberler);
    $haberler = $stmtHaberler->fetchAll(PDO::FETCH_ASSOC);

    ?>
<!-- sql query final -->
    <main>

        <div class="container">
            <!-- haberler filmler ve dizilerden veriler  en güncel 4 tanesini sırala -->
            <!-- Sol resim alanı -->
            <div class="gallery">
                <a href="#" class="image-container hero">
                    <img src="assets/img/news/04.jpg" class="hero">
                    <div class="overlay">
                        <span class="category">CYF Tech</span>
                        <p>CYF Tech ABD: Beterböcek'ten 110 milyon dolar</p>
                    </div>
                </a>
        
                <a href="#" class="image-container hero2 img-radius">
                    <img src="assets/img/mainImg/01.jpg" class="img-radius">
                    <div class="overlay">
                        <span class="category">Yerli Dizi</span>
                        <p>Kızılcık Şerbeti dizisinin 3. sezon başlangıç tarihi açıklandı</p>
                    </div>
                </a>
        
                <a href="#" class="image-container img-radius3">
                    <img src="assets/img/mainImg/01.jpg" class="img-radius3">
                    <div class="overlay">
                        <span class="category">Fragman</span>
                        <p>Gelin Takımı filminden fragman yayınlandı</p>
                    </div>
                </a>
        
                <a href="#" class="image-container img-radius2">
                    <img src="assets/img/mainImg/01.jpg" class="img-radius2">
                    <div class="overlay">
                        <span class="category">Vizyonda</span>
                        <p>Vizyona bu hafta: 13 yeni film gösterimde!</p>
                    </div>
                </a>
            </div>
            <!-- burada bitiyor -->
    
            <!-- Sağ sekmeli alan -->
            <div class="tab-section">
                <div class="dateArea">
                    <p><i class="fa-regular fa-calendar"></i> 06-08 Eylül 2024</p>
                </div>
                <div class="tabs">
                    <button class="tab-button active" onclick="openTab1(event, 'seyirci')">Seyirci</button>
                    <button class="tab-button" onclick="openTab1(event, 'hasilat')">Hasılat</button>
                </div>
    
                <div class="tab-content" id="seyirci">
                    <ul class="list">
                        <!-- burada başlıyor 5 tane loopa sok en güncel veriler gelecek, hasılatiçin de aynı (film veriler tablosu)   -->
                        <li>
                            <a href="">
                                <span>1</span>
                                <div class="infInside">
                                    <p>Beterböcek Beterböcek</p>
                                    <div class="rowIns">
                                        <div>
                                            <p>Hafta Sonu</p>
                                            <div class="rowIns2">
                                                <i class="fa-regular fa-user"></i>
                                                <p>32.457</p>
                                            </div>
                                        </div>
                                        <div class="endTxt">
                                            <p>Toplam</p>
                                            <div class="rowIns2">
                                                <i class="fa-regular fa-user"></i>
                                                <p>32.457</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </li>
                        
                        <li>
                            <a href="">
                                <span>2</span>
                                <div class="infInside">
                                    <p>Deadpool & Wolverine</p>
                                    <div class="rowIns">
                                        <div>
                                            <p>Hafta Sonu</p>
                                            <div class="rowIns2">
                                                <i class="fa-regular fa-user"></i>
                                                <p>25.839</p>
                                            </div>
                                        </div>
                                        <div class="endTxt">
                                            <p>Toplam</p>
                                            <div class="rowIns2">
                                                <i class="fa-regular fa-user"></i>
                                                <p>1.339.216</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </li>
                    
                        <li>
                            <a href="">
                                <span>3</span>
                                <div class="infInside">
                                    <p>Ters Yüz 2</p>
                                    <div class="rowIns">
                                        <div>
                                            <p>Hafta Sonu</p>
                                            <div class="rowIns2">
                                                <i class="fa-regular fa-user"></i>
                                                <p>19.714</p>
                                            </div>
                                        </div>
                                        <div class="endTxt">
                                            <p>Toplam</p>
                                            <div class="rowIns2">
                                                <i class="fa-regular fa-user"></i>
                                                <p>2.301.228</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </li>
                    
                        <li>
                            <a href="">
                                <span>4</span>
                                <div class="infInside">
                                    <p>Çılgın Hırsız 4</p>
                                    <div class="rowIns">
                                        <div>
                                            <p>Hafta Sonu</p>
                                            <div class="rowIns2">
                                                <i class="fa-regular fa-user"></i>
                                                <p>16.082</p>
                                            </div>
                                        </div>
                                        <div class="endTxt">
                                            <p>Toplam</p>
                                            <div class="rowIns2">
                                                <i class="fa-regular fa-user"></i>
                                                <p>976.868</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </li>
                    
                        <li>
                            <a href="">
                                <span>5</span>
                                <div class="infInside">
                                    <p>Cambaz</p>
                                    <div class="rowIns">
                                        <div>
                                            <p>Hafta Sonu</p>
                                            <div class="rowIns2">
                                                <i class="fa-regular fa-user"></i>
                                                <p>11.506</p>
                                            </div>
                                        </div>
                                        <div class="endTxt">
                                            <p>Toplam</p>
                                            <div class="rowIns2">
                                                <i class="fa-regular fa-user"></i>
                                                <p>11.506</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </li>
                    </ul>                    
                </div>
    
                <div class="tab-content" id="hasilat" style="display:none;">
                    <ul class="list">
                    <li>
                            <a href="">
                                <span>1</span>
                                <div class="infInside">
                                    <p>Beterböcek Beterböcek</p>
                                    <div class="rowIns">
                                        <div>
                                            <p>Hafta Sonu</p>
                                            <div class="rowIns2">
                                                <i class="fa-regular fa-user"></i>
                                                <p>32.457</p>
                                            </div>
                                        </div>
                                        <div class="endTxt">
                                            <p>Toplam</p>
                                            <div class="rowIns2">
                                                <i class="fa-regular fa-user"></i>
                                                <p>32.457</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </li>
                        
                        <li>
                            <a href="">
                                <span>2</span>
                                <div class="infInside">
                                    <p>Deadpool & Wolverine</p>
                                    <div class="rowIns">
                                        <div>
                                            <p>Hafta Sonu</p>
                                            <div class="rowIns2">
                                                <i class="fa-regular fa-user"></i>
                                                <p>25.839</p>
                                            </div>
                                        </div>
                                        <div class="endTxt">
                                            <p>Toplam</p>
                                            <div class="rowIns2">
                                                <i class="fa-regular fa-user"></i>
                                                <p>1.339.216</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </li>
                    
                        <li>
                            <a href="">
                                <span>3</span>
                                <div class="infInside">
                                    <p>Ters Yüz 2</p>
                                    <div class="rowIns">
                                        <div>
                                            <p>Hafta Sonu</p>
                                            <div class="rowIns2">
                                                <i class="fa-regular fa-user"></i>
                                                <p>19.714</p>
                                            </div>
                                        </div>
                                        <div class="endTxt">
                                            <p>Toplam</p>
                                            <div class="rowIns2">
                                                <i class="fa-regular fa-user"></i>
                                                <p>2.301.228</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </li>
                    
                        <li>
                            <a href="">
                                <span>4</span>
                                <div class="infInside">
                                    <p>Çılgın Hırsız 4</p>
                                    <div class="rowIns">
                                        <div>
                                            <p>Hafta Sonu</p>
                                            <div class="rowIns2">
                                                <i class="fa-regular fa-user"></i>
                                                <p>16.082</p>
                                            </div>
                                        </div>
                                        <div class="endTxt">
                                            <p>Toplam</p>
                                            <div class="rowIns2">
                                                <i class="fa-regular fa-user"></i>
                                                <p>976.868</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </li>
                    
                        <li>
                            <a href="">
                                <span>5</span>
                                <div class="infInside">
                                    <p>Cambaz</p>
                                    <div class="rowIns">
                                        <div>
                                            <p>Hafta Sonu</p>
                                            <div class="rowIns2">
                                                <i class="fa-regular fa-user"></i>
                                                <p>11.506</p>
                                            </div>
                                        </div>
                                        <div class="endTxt">
                                            <p>Toplam</p>
                                            <div class="rowIns2">
                                                <i class="fa-regular fa-user"></i>
                                                <p>11.506</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </li>
                    </ul>
                </div>
                <a href="hafta/haftalar.html" class="tumu">Tümü <i class="fa-solid fa-caret-right"></i></a>
            </div>
        </div>

    </main>

    <!-- Main Area End -->
    
    <!-- ============================================================================== -->
    
    <!-- vizyon Area End -->

    <section>
        <div class="vizyon">

            <div class="tabs2">
                <button class="tablinks active" onclick="openTab2(event, 'vizyondaYeni')"><i class="fa-solid fa-ticket"></i> Vizyonda Yeni</button><!-- vizyondan öncesi 2 hafta öncesi-->
                <button class="tablinks" onclick="openTab2(event, 'yakinda')"><i class="fa-solid fa-clock-rotate-left"></i> Yakında</button><!-- vizyondan öncesi 2 hafta sonrası-->
            </div>
        
            <div id="vizyondaYeni" class="tabcontent">
                <?php

                ?>
                <div class="vizyonSlier">
                    <div class="vizyonLeft">
                        <button class="arrows left"><i class="fa-solid fa-caret-left"></i></button>

                        <?php if (!empty($filmlerVizyon)): ?>
                            <a href="#1" class="mainvizyonImg">
                                <img src="assets/img/mainImg/01.jpg" alt="vizyon">
                                <div class="overlay1">
                                    <span class="namevizyon"><?php echo $filmlerVizyon[0]['film_adi']; ?></span>
                                </div>
                            </a>
                        <?php endif; ?>

                        <button class="arrows right"><i class="fa-solid fa-caret-right"></i></button>
                    </div>
                    <div class="vizyonRight">
                        <?php foreach ($filmlerVizyon as $film): ?>
                            <a href="<?php echo $film['id']; ?>" class="vizyonBox">
                                <div class="vizyonBoxImg">
                                    <img src="kapakfoto/<?php echo $film['kapak_resmi']; ?>" alt="<?php echo $film['id']; ?>">
                                </div>
                                <h3><?php echo $film['film_adi']; ?></h3>
                            </a>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php
                
                ?>
            </div>
        
            <div id="yakinda" class="tabcontent" style="display:none;">
                <div class="vizyonSlier">
                    <div class="vizyonLeft">
                        <button class="arrows left"><i class="fa-solid fa-caret-left"></i></button>
                        <?php if (!empty($filmlerYakin)): ?>
                            <a href="#11" class="mainvizyonImg">
                                <img src="assets/img/mainImg/01.jpg" alt="vizyon">
                                <div class="overlay1">
                                    <span class="namevizyon">Filmin Adı</span>
                                    <p><?php echo $film['vizyon_tarihi']; ?></p>
                                </div>
                            </a>
                            
                        <?php endif; ?>
                        <button class="arrows right"><i class="fa-solid fa-caret-right"></i></button>
                    </div>
                    <div class="vizyonRight">
                    <?php foreach ($filmlerYakin as $film): ?>
                        <a href="<?php echo $film['id']; ?>" class="vizyonBox">
                            <div class="vizyonBoxImg">
                                <img src="kapakfoto/<?php echo $film['kapak_resmi']; ?>" alt="<?php echo $film['id']; ?>">
                            </div>
                            <div>
                                <h3><?php echo $film['film_adi']; ?></h3>
                                <p><?php echo $film['vizyon_tarihi']; ?></p>
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
     
    <!-- News Area End -->
    <section>
  
        <div class="news">

            <h2><i class="fa-solid fa-newspaper"></i> Filmler'den Haberler</h2>

            <div class="newsInside">

                <div class="newsLeft">
                    <!-- foreach 4 tane olacak en güncel 4 taneyi göster  -->
                    <?php foreach ($haberler as $haber) { ?>
                        <a href="#<?= $haber['idhaber']; ?>" class="newsBox">
                            <div class="newsBoxImg">
                                <img src="haberfoto/<?php echo $haber['haberfoto']; ?>" alt="haberfoto/<?php echo $film['haberfoto']; ?>" >
                            </div>
                            <div>
                                <p><i class="fa-solid fa-hourglass-half"></i> <?= htmlspecialchars($haber['tarih']); ?></p>
                                <h3><?php echo $haber['baslik']; ?></h3>
                            </div>
                        </a>
                    <?php } ?>
                    <!-- kapanış -->
                    <a href="" class="tumuBtn">Tüm Haberler <i class="fa-solid fa-right-long"></i></a>
                    
                </div>
                <!-- foreach kapanış -->
                <div class="newsRight">
                    <div class="seyirci">
                        <div class="dateArea1">
                            <h3><i class="fa-solid fa-stopwatch"></i> En Çok İzlenenler</h3> <!-- en çok izlenen ilk 20 tane film (film verileri)-->
                        </div>
                        <ul class="list">
                            <li>
                                <a href="" class="aling-center">
                                    <span>1</span>
                                    <div class="infInside">
                                        <p>Blade Runner 2049</p>
                                    </div>
                                    <span><i class="fa-solid fa-caret-right"></i></span>
                                </a>
                            </li>
                            <li>
                                <a href="" class="aling-center">
                                    <span>2</span>
                                    <div class="infInside">
                                        <p>Soysuzlar Çetesi</p>
                                    </div>
                                    <span><i class="fa-solid fa-caret-right"></i></span>
                                </a>
                            </li>
                            <li>
                                <a href="" class="aling-center">
                                    <span>3</span>
                                    <div class="infInside">
                                        <p>Pardon</p>
                                    </div>
                                    <span><i class="fa-solid fa-caret-right"></i></span>
                                </a>
                            </li>
                            <li>
                                <a href="" class="aling-center">
                                    <span>4</span>
                                    <div class="infInside">
                                        <p>Star Wars</p>
                                    </div>
                                    <span><i class="fa-solid fa-caret-right"></i></span>
                                </a>
                            </li>
                            
                        </ul>                    
                    </div>
                </div>
            
            </div>

        </div>
    </section>

    <!-- News Area End -->

    <!-- ============================================================================== -->

    <?php include('footer.php');?>

</body>
</html>