<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>

    <!-- myStyle Area -->
    <link rel="stylesheet" href="../assets/css/header.css">
    <link rel="stylesheet" href="../assets/css/mainStyle.css">
    <link rel="stylesheet" href="../assets/css/hafta.css">
    <link rel="stylesheet" href="../assets/css/footer.css">

    <!-- Library Area -->
    <script src="https://kit.fontawesome.com/be694eddd8.js" crossorigin="anonymous"></script>
</head>
<body>

    <!-- Header Area Start -->
    
    <header>

        <div class="top-header">
            <a class="logoImg" href="/">
                <img src="../assets/img/logo/logo.png" alt="Logo">
            </a>
            <div class="headerInfo">
                <div class="search-container">
                    <input type="text" placeholder="Ara..." id="searchInput" class="search-input">
                    <button id="searchButton" class="search-button"><i class="fa-solid fa-magnifying-glass" aria-hidden="true"></i></button>
                </div>
            </div>

            <button class="mobile-btn"><i id="menu-icon" class="fa-solid fa-bars"></i></button>

            <div id="navbar-mobile" class="navbar-mobile">
                <a href="#"><i class="fa-solid fa-film"></i> Filmler</a>
                <a href="#"><i class="fa-solid fa-newspaper"></i> Haberler</a>
                <a href="#"><i class="fa-regular fa-calendar"></i> Takvim</a>
                <a href="#"><i class="fa-solid fa-calendar-days"></i> Vizyon Takvimi Tablosu</a>
                <p><i class="fa-solid fa-box-archive"></i> Gişe</p>
                <a href="#" class="bgclr"><i class="fa-regular fa-paper-plane"></i> Dağıtımcılar</a>
                <a href="#" class="bgclr"><i class="fa-solid fa-timeline"></i> Tüm Zamanlar</a>
                <a href="#" class="bgclr"><i class="fa-solid fa-calendar-week"></i> Hafta</a></li>
                <a href="#" class="bgclr"><i class="fa-regular fa-calendar"></i> Yıllık</a></li>
                <div class="headerInfo1">
                    <div class="search-container">
                        <input type="text" placeholder="Ara..." id="searchInput" class="search-input">
                        <button id="searchButton" class="search-button"><i class="fa-solid fa-magnifying-glass" aria-hidden="true"></i></button>
                    </div>
                </div>
            </div>
        </div>

        <nav class="bottom-header">
            <ul class="bottomUl">
                <li>
                    <a class="navA" href="/"><i class="fa-solid fa-film"></i> Filmler</a>
                </li>
                <li>
                    <a class="navA" href="/"><i class="fa-solid fa-newspaper"></i> Haberler</a>
                </li>
                <li>
                    <a class="navA" href="/"><i class="fa-regular fa-calendar"></i> Takvim</a>
                </li>
                <li>
                    <a class="navA" href="/"><i class="fa-solid fa-calendar-days"></i> Vizyon Takvimi Tablosu</a>
                </li>
                <li>
                    <button class="navA" id="mainButton"><i class="fa-solid fa-box-archive"></i> Gişe <i class="fa-solid fa-caret-down"></i></button>
                    <!-- Submenu -->
                    <ul class="submenu" id="submenu">
                        <li><a class="navA clr1" href="/"><i class="fa-regular fa-paper-plane"></i> Dağıtımcılar</a></li>
                        <li><a class="navA clr1" href="/"><i class="fa-solid fa-timeline"></i> Tüm Zamanlar</a></li>
                        <li><a class="navA clr1" href="/"><i class="fa-solid fa-calendar-week"></i> Hafta</a></li>
                        <li><a class="navA clr1" href="/"><i class="fa-regular fa-calendar"></i> Yıllık</a></li>
                    </ul>
                </li>
            </ul>
        </nav>

    </header>

    <!-- Header Area End -->

    <!-- ============================================================================== -->
    
    <!-- Table Area Start -->

    <section class="haftaSection">

        <div class="haftaMain">
            
            <h2><i class="fa-solid fa-magnifying-glass"></i> "Aranan Kelime" İçin Arama Sonuçları</h2>

        </div>

    </section>

    <!-- Table Area End -->

    <!-- ============================================================================== -->

    <!-- News Area End -->

    <section class="pt-0">

        <div class="news pt-0">

            <div class="newsInside">

                <div class="newsLeft">

                    <a href="../haberler/haber-detay.html" class="newsBox">
                        <div class="newsBoxImg">
                            <img src="../assets/img/mainImg/01.jpg" alt="">
                        </div>
                        <div>
                            <p><i class="fa-solid fa-hourglass-half"></i> 04 Ağustos 2024</p>
                            <h3>Dedemin Gözyaşları filminin fragmanı yayınlandı</h3>
                        </div>
                    </a>
                    <a href="../haberler/haber-detay.html" class="newsBox">
                        <div class="newsBoxImg">
                            <img src="../assets/img/mainImg/01.jpg" alt="">
                        </div>
                        <div>
                            <p><i class="fa-solid fa-hourglass-half"></i> 04 Ağustos 2024</p>
                            <h3>Dedemin Gözyaşları filminin fragmanı yayınlandı</h3>
                        </div>
                    </a>
                    <a href="../haberler/haber-detay.html" class="newsBox">
                        <div class="newsBoxImg">
                            <img src="../assets/img/mainImg/01.jpg" alt="">
                        </div>
                        <div>
                            <p><i class="fa-solid fa-hourglass-half"></i> 04 Ağustos 2024</p>
                            <h3>Dedemin Gözyaşları filminin fragmanı yayınlandı</h3>
                        </div>
                    </a>
                    <a href="../haberler/haber-detay.html" class="newsBox">
                        <div class="newsBoxImg">
                            <img src="../assets/img/mainImg/01.jpg" alt="">
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

                <div class="newsRight bgnone">
                    <h2><i class="fa-solid fa-newspaper"></i> Vizyona Girecekler</h2>
                    <a href="" class="newsBoxHafta">
                        <div class="haftaImg">
                            <img src="../assets/img/news/02.jpg" alt="">
                        </div>
                        <p>Venom 3</p>
                        <p class="date"><i class="fa-regular fa-clock"></i> 06 eylül 2024</p>
                    </a>
                    <a href="" class="newsBoxHafta">
                        <div class="haftaImg">
                            <img src="../assets/img/news/02.jpg" alt="">
                        </div>
                        <p>Pardon</p>
                        <p class="date"><i class="fa-regular fa-clock"></i> 06 eylül 2024</p>
                    </a>
                    <a href="" class="newsBoxHafta">
                        <div class="haftaImg">
                            <img src="../assets/img/news/02.jpg" alt="">
                        </div>
                        <p>Arog</p>
                        <p class="date"><i class="fa-regular fa-clock"></i> 06 eylül 2024</p>
                    </a>
                    <a href="" class="newsBoxHafta">
                        <div class="haftaImg">
                            <img src="../assets/img/news/02.jpg" alt="">
                        </div>
                        <p>Yahşi Batı</p>
                        <p class="date"><i class="fa-regular fa-clock"></i> 06 eylül 2024</p>
                    </a>
                    <a href="" class="newsBoxHafta">
                        <div class="haftaImg">
                            <img src="../assets/img/news/02.jpg" alt="">
                        </div>
                        <p>Blade Runner</p>
                        <p class="date"><i class="fa-regular fa-clock"></i> 06 eylül 2024</p>
                    </a>
                </div>
            
            </div>

        </div>
    </section>

    <!-- News Area End -->

    <!-- Footer Area Start -->

    <footer>

        <div class="footer">

            <ul class="linkUl">
                <li>
                    <a href="#">Reklam Verin</a>
                </li>
                <li>
                    <a href="#">İçerik İzni</a>
                </li>
                <li>
                    <a href="#">Hakkımızda</a>
                </li>
                <li>
                    <a href="#">İletişim</a>
                </li>
            </ul>

            <div class="socialArea">
                <div class="socialBox">
                    <h3><i class="fa-solid fa-link"></i> Sosyal Medya</h3>
                    <ul>
                        <li>
                            <a href="#"><i class="fa-brands fa-square-instagram"></i></a>
                        </li>
                        <li>
                            <a href="#"><i class="fa-brands fa-square-youtube"></i></a>
                        </li>
                        <li>
                            <a href="#"><i class="fa-brands fa-square-facebook"></i></a>
                        </li>
                        <li>
                            <a href="#"><i class="fa-brands fa-square-x-twitter"></i></a>
                        </li>
                    </ul>
                </div>
                <div class="socialBox">
                    <div class="appArea">
                        <h3><i class="fa-solid fa-mobile-screen-button"></i> Mobil Uygulama</h3>
                        <a href="" class="appBoxImg">
                            <img src="../assets/img/news/01.jpg" alt="">
                        </a>
                    </div>
                </div>
            </div>
            
            <a href="" class="logoImg">
                <img src="../assets/img/logo/beyaz.png" alt="">
            </a>

            <p>© Created by <a href="https://www.cyftech.com.tr" target="_blank">CYFtech</a></p>

        </div>

    </footer>

    <!-- Footer Area End -->

    <!-- ============================================================================== -->
    <!-- ============================================================================== -->
    <!-- ============================================================================== -->


    <!-- myScript Area Start -->

    <!-- Header Mobile Button Area -->
    <script src="../assets/js/headerMobile.js"></script>
    <!-- seyirci hasilat area -->
    <script src="../assets/js/seyirciHasilat.js"></script>
    <!-- vizyon area -->
    <script src="../assets/js/vizyon.js"></script>
    <!-- slider Area -->
    <script src="../assets/js/slider.js"></script>
    <!-- Table Area -->
    <script src="../assets/js/table.js"></script>

    <!-- ============================================================================== -->

</body>
</html>