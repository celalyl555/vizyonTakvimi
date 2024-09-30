<?php include('../header.php');?>
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
                        <a href="#1" class="mainvizyonImg">
                            <img src="assets/img/mainImg/01.jpg" alt="vizyon">
                            <div class="overlay1">
                                <span class="namevizyon">Filmin Adı</span>
                            </div>
                        </a>
                        <button class="arrows right"><i class="fa-solid fa-caret-right"></i></button>
                    </div>
                    <div class="vizyonRight">
                        <a href="giderayak" class="vizyonBox">
                            <div class="vizyonBoxImg">
                                <img src="assets/img/news/01.jpg" alt="">
                            </div>
                            <h3>giderayak</h3>
                        </a>
                        <a href="Soysuzlar" class="vizyonBox">
                            <div class="vizyonBoxImg">
                                <img src="assets/img/news/02.jpg" alt="">
                            </div>
                            <h3>Soysuzlar Çetesi</h3>
                        </a>
                        <a href="#3" class="vizyonBox">
                            <div class="vizyonBoxImg">
                                <img src="assets/img/mainImg/01.jpg" alt="">
                            </div>
                            <h3>Filmin Adı 3</h3>
                        </a>
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