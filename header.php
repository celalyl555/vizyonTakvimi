

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vizyon Takvimi</title>

    <link rel="shortcut icon" href="assets/img/logo/ico.png" id="favicon">

    <base href="/vizyontakvimi/">
    <!-- myStyle Area -->
    <link rel="stylesheet" href="assets/css/header.css">
    <link rel="stylesheet" href="assets/css/mainStyle.css">
    <link rel="stylesheet" href="assets/css/hafta.css">
    <link rel="stylesheet" href="assets/css/footer.css">

    <!-- Library Area  -->
    <script src="https://kit.fontawesome.com/be694eddd8.js" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
</head>
<body>

    <!-- Header Area Start -->
    
    <header>

        <div class="top-header">
            <a class="logoImg" href="index">
                <img src="assets/img/logo/logo.png" alt="Logo">
            </a>
            <div class="headerInfo">
                <div class="search-container">
                    <input type="text" placeholder="Ara..." id="searchInput" class="search-input">
                    <button id="searchButton" class="search-button"><i class="fa-solid fa-magnifying-glass" aria-hidden="true"></i></button>
                </div>
            </div>

            <button class="mobile-btn"><i id="menu-icon" class="fa-solid fa-bars"></i></button>

            <div id="navbar-mobile" class="navbar-mobile">
                <a href="filmler"><i class="fa-solid fa-film"></i> Filmler</a>
                <a href="diziler"><i class="fa-solid fa-couch"></i> Diziler</a>
                <a href="haberler"><i class="fa-solid fa-newspaper"></i> Haberler</a>
                <a href="takvim"><i class="fa-regular fa-calendar"></i> Takvim</a>
                <a href="vizyon-takvim-tablosu"><i class="fa-solid fa-calendar-days"></i> Vizyon Takvimi Tablosu</a>
                <p><i class="fa-solid fa-box-archive"></i> Gişe</p>
                <a href="dagitimci" class="bgclr"><i class="fa-regular fa-paper-plane"></i> Dağıtımcılar</a>
                <a href="tum-zamanlar" class="bgclr"><i class="fa-solid fa-timeline"></i> Tüm Zamanlar</a>
                <a href="hafta" class="bgclr"><i class="fa-solid fa-calendar-week"></i> Hafta</a></li>
                <a href="yil" class="bgclr"><i class="fa-regular fa-calendar"></i> Yıllık</a></li>
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
                    <a class="navA" href="filmler"><i class="fa-solid fa-film"></i> Filmler</a>
                </li>
                <li>
                    <a class="navA" href="diziler"><i class="fa-solid fa-couch"></i> Diziler</a>
                </li>
                <li>
                    <a class="navA" href="haberler"><i class="fa-solid fa-newspaper"></i> Haberler</a>
                </li>
                <li>
                    <a class="navA" href="takvim"><i class="fa-regular fa-calendar"></i> Takvim</a>
                </li>
                <li>
                    <a class="navA" href="vizyon-takvim-tablosu"><i class="fa-solid fa-calendar-days"></i> Vizyon Takvimi Tablosu</a>
                </li>
                <li>
                    <button class="navA" id="mainButton"><i class="fa-solid fa-box-archive"></i> Gişe <i class="fa-solid fa-caret-down"></i></button>
                    <!-- Submenu -->
                    <ul class="submenu" id="submenu">
                        <li><a class="navA clr1" href="dagitimci"><i class="fa-regular fa-paper-plane"></i> Dağıtımcılar</a></li>
                        <li><a class="navA clr1" href="tum-zamanlar"><i class="fa-solid fa-timeline"></i> Tüm Zamanlar</a></li>
                        <li><a class="navA clr1" href="hafta"><i class="fa-solid fa-calendar-week"></i> Hafta</a></li>
                        <li><a class="navA clr1" href="yil"><i class="fa-regular fa-calendar"></i> Yıllık</a></li>
                    </ul>
                </li>
            </ul>
        </nav>

    </header>

    <!-- Header Area End -->