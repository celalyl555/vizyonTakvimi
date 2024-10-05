<?php 
include('../admin/conn.php');
include('../header.php');?>

<!-- Sql query start -->
<?php

 #vizyonda yeni sql query başlangıç
 $sqlEnEskiFilm = "SELECT * FROM filmler 
 WHERE statu = 1 AND vizyon_tarihi >= CURDATE() - INTERVAL 2 WEEK
 ORDER BY vizyon_tarihi ASC
 LIMIT 1";
 $stmtEnEskiFilm = $con->query($sqlEnEskiFilm);
 $enEskiFilm = $stmtEnEskiFilm->fetch(PDO::FETCH_ASSOC);

 $sqlFilmlerVizyon = "SELECT * FROM filmler 
 WHERE statu = 1 AND vizyon_tarihi >= CURDATE() - INTERVAL 2 WEEK 
 ORDER BY vizyon_tarihi ASC 
 LIMIT 3"; 
 $stmtFilmlerVizyon = $con->query($sqlFilmlerVizyon);
 $filmlerVizyon = $stmtFilmlerVizyon->fetchAll(PDO::FETCH_ASSOC);
#vizyonda yeni sql query bitiş

#*******************************************************************************

#yakında sql query başlangıç
 $sqlEnYeniFilm = "SELECT * FROM filmler 
 WHERE statu = 1 AND vizyon_tarihi <= CURDATE() + INTERVAL 2 WEEK 
 ORDER BY vizyon_tarihi DESC
 LIMIT 1";
 $stmtEnYeniFilm = $con->query($sqlEnYeniFilm);
 $enYeniFilm = $stmtEnYeniFilm->fetchAll(PDO::FETCH_ASSOC);
 
 $sqlFilmlerYakin = "SELECT * FROM filmler 
 WHERE statu = 1 AND vizyon_tarihi <= CURDATE() + INTERVAL 2 WEEK
 ORDER BY vizyon_tarihi DESC
 LIMIT 3";
 $stmtFilmlerYakin = $con->query($sqlFilmlerYakin);
 $filmlerYakin = $stmtFilmlerYakin->fetchAll(PDO::FETCH_ASSOC);

#yakında sql query bitiş

#********************************************************************************
#haberler sql query başlangıç
 $sqlHaberler = "SELECT * FROM haberler ORDER BY tarih DESC LIMIT 4"; // En yeni 4 haberi al
 $stmtHaberler = $con->query($sqlHaberler);
 $haberler = $stmtHaberler->fetchAll(PDO::FETCH_ASSOC);
#haberler sql query bitiş

#*********************************************************************************
#film verileri max kişi sql query başlangıç
 $sqlFilmVerileri = "SELECT f.*, fi.film_adi
 FROM filmveriler f
 INNER JOIN (
     SELECT film_id, MAX(toplamkisi) AS max_kisi
     FROM filmveriler
     GROUP BY film_id
 ) AS max_filmler ON f.film_id = max_filmler.film_id
 AND f.toplamkisi = max_filmler.max_kisi
 INNER JOIN filmler fi ON f.film_id = fi.id
 GROUP BY f.film_id
 ORDER BY f.toplamkisi DESC
 LIMIT 20";

 $stmtFilmVerileri = $con->query($sqlFilmVerileri);
 $filmVerileri = $stmtFilmVerileri->fetchAll(PDO::FETCH_ASSOC);

?>
<!-- Sql query finish-->


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
                <button class="tablinks active" onclick="openTab2(event, 'vizyondaYeni')"><i class="fa-solid fa-ticket"></i> Vizyonda Yeni</button>
                <button class="tablinks" onclick="openTab2(event, 'yakinda')"><i class="fa-solid fa-clock-rotate-left"></i> Yakında</button>
            </div>
        
            <div id="vizyondaYeni" class="tabcontent">

                <div class="vizyonSlier">
                    <div class="vizyonLeft">
                        <button class="arrows left"><i class="fa-solid fa-caret-left"></i></button>
                        <?php if (!empty($enEskiFilm)): ?>
                            <a href="#1" class="mainvizyonImg">
                                <img src="../kapakfoto/<?php echo $enEskiFilm['kapak_resmi']; ?>" alt="vizyon">
                                <div class="overlay1">
                                    <span class="namevizyon"><?php echo $enEskiFilm['film_adi'];?></span>
                                </div>
                            </a>
                        <?php endif; ?>
                        <button class="arrows right"><i class="fa-solid fa-caret-right"></i></button>
                    </div>
                    <div class="vizyonRight">
                    <?php foreach ($filmlerVizyon as $film): ?>
                        <a href="giderayak" class="vizyonBox">
                            <div class="vizyonBoxImg">
                                <img src="../kapakfoto/<?php echo $film['kapak_resmi']; ?>" alt="">
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
                        <a href="#11" class="mainvizyonImg">
                            <img src="assets/img/mainImg/01.jpg" alt="vizyon">
                            <div class="overlay1">
                                <span class="namevizyon">Filmin Adı</span>
                                <p>01 Ağustos 2024</p>
                            </div>
                        </a>
                        <button class="arrows right"><i class="fa-solid fa-caret-right"></i></button>
                    </div>
                    <div class="vizyonRight">
                        <a href="#giderayak" class="vizyonBox">
                            <div class="vizyonBoxImg">
                                <img src="assets/img/news/01.jpg" alt="">
                            </div>
                            <div>
                                <h3>giderayak</h3>
                                <p>02 Ağustos 2024</p>
                            </div>
                        </a>
                        <a href="#Soysuzlar" class="vizyonBox">
                            <div class="vizyonBoxImg">
                                <img src="assets/img/news/02.jpg" alt="">
                            </div>
                            <div>
                                <h3>Soysuzlar Çetesi</h3>
                                <p>03 Ağustos 2024</p>
                            </div>
                        </a>
                        <a href="#333" class="vizyonBox">
                            <div class="vizyonBoxImg">
                                <img src="assets/img/mainImg/01.jpg" alt="">
                            </div>
                            <div>
                                <h3>Filmin Adı 3</h3>
                                <p>04 Ağustos 2024</p>
                            </div>
                        </a>
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

            <h2><i class="fa-solid fa-film"></i> Filmler'den Haberler</h2>

            <div class="newsInside">

                <div class="newsLeft">

                    <a href="haberler/haber-detay.php" class="newsBox">
                        <div class="newsBoxImg">
                            <img src="assets/img/mainImg/01.jpg" alt="">
                        </div>
                        <div>
                            <p><i class="fa-solid fa-hourglass-half"></i> 04 Ağustos 2024</p>
                            <h3>Dedemin Gözyaşları filminin fragmanı yayınlandı</h3>
                        </div>
                    </a>
                    <a href="haberler/haber-detay.php" class="newsBox">
                        <div class="newsBoxImg">
                            <img src="assets/img/mainImg/01.jpg" alt="">
                        </div>
                        <div>
                            <p><i class="fa-solid fa-hourglass-half"></i> 04 Ağustos 2024</p>
                            <h3>Dedemin Gözyaşları filminin fragmanı yayınlandı</h3>
                        </div>
                    </a>
                    <a href="haberler/haber-detay.php" class="newsBox">
                        <div class="newsBoxImg">
                            <img src="assets/img/mainImg/01.jpg" alt="">
                        </div>
                        <div>
                            <p><i class="fa-solid fa-hourglass-half"></i> 04 Ağustos 2024</p>
                            <h3>Dedemin Gözyaşları filminin fragmanı yayınlandı</h3>
                        </div>
                    </a>
                    <a href="haberler/haber-detay.php" class="newsBox">
                        <div class="newsBoxImg">
                            <img src="assets/img/mainImg/01.jpg" alt="">
                        </div>
                        <div>
                            <p><i class="fa-solid fa-hourglass-half"></i> 04 Ağustos 2024</p>
                            <h3>Dedemin Gözyaşları filminin fragmanı yayınlandı</h3>
                        </div>
                    </a>
                    
                    <div class="pageBtn">
                        <button class="pageBtns deactivePage"><i class="fa-solid fa-angles-left"></i></button>
                        <button class="pageBtns activePage">1</button>
                        <button class="pageBtns activePage">2</button>
                        <button class="pageBtns activePage">3</button>
                        <button class="pageBtns activePage"><i class="fa-solid fa-angles-right"></i></button>
                    </div>
                    
                </div>

                <div class="newsRight">
                    <div class="seyirci">
                        <div class="dateArea1">
                            <h3><i class="fa-solid fa-stopwatch"></i> En Çok İzlenenler</h3>
                        </div>
                        <ul class="list">
                            <li>
                                <a href="filmler/film-detay.php" class="aling-center">
                                    <span>1</span>
                                    <div class="infInside">
                                        <p>Blade Runner 2049</p>
                                    </div>
                                    <span><i class="fa-solid fa-caret-right"></i></span>
                                </a>
                            </li>
                            <li>
                                <a href="filmler/film-detay.php" class="aling-center">
                                    <span>2</span>
                                    <div class="infInside">
                                        <p>Soysuzlar Çetesi</p>
                                    </div>
                                    <span><i class="fa-solid fa-caret-right"></i></span>
                                </a>
                            </li>
                            <li>
                                <a href="filmler/film-detay.php" class="aling-center">
                                    <span>3</span>
                                    <div class="infInside">
                                        <p>Pardon</p>
                                    </div>
                                    <span><i class="fa-solid fa-caret-right"></i></span>
                                </a>
                            </li>
                            <li>
                                <a href="filmler/film-detay.php" class="aling-center">
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

    <?php include('../footer.php');?>

</body>
</html>