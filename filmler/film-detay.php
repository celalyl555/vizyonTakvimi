<?php include('../header.php');?>
    <!-- ============================================================================== -->
    
    <!-- Table Area Start -->

    <section class="haftaSection">

        <div class="haftaMain">

            <h2>Blade Runner 2049: Bıçak Sırtı</h2>
            <p><i class="fa-regular fa-calendar color1"></i> 2017 <i class="fa-regular fa-clock color1"></i> 2s 44dk</p>
            
        </div>

    </section>

    <!-- Table Area End -->

    <!-- ============================================================================== -->

    <!-- Main Area Start -->

    <main class="mt-0 mh-0">

        <div class="container">

            <!-- Sag resim alanı -->
            <div class="galleryMovie">
                <div class="image-container heroMovie" onclick="openModal(0)">
                    <img src="assets/img/news/04.jpg" class="heroMovie">
                </div>
            
                <div class="image-container heroMovie1" onclick="openModal(1)">
                    <img src="assets/img/news/03.jpg" class="heroMovie1">
                </div>
            
                <div class="image-container img-radiusx" onclick="openModal(2)">
                    <img src="assets/img/news/03.jpg" class="img-radiusx">
                </div>
            
                <div class="image-container img-radius2x" onclick="openModal(3)">
                    <img src="assets/img/news/04.jpg" class="img-radius2x">
                    <div class="plusImg">
                        <p>Daha Fazla</p>
                        <i class="fa-solid fa-plus"></i>
                    </div>
                </div>
            </div>
            
            <!-- Modal -->
            <div id="imageModal" class="modal">
                <span class="close" onclick="closeModal()">&times;</span>
                <img class="modal-content" id="modalImg">
                <a class="prev" onclick="changeSlide(-1)">&#10094;</a>
                <a class="next" onclick="changeSlide(1)">&#10095;</a>
            
                <!-- Thumbnail images below the modal -->
                <div class="thumbnail-container">
                    <img class="thumbnail" src="assets/img/news/04.jpg" draggable="false" onclick="currentSlide(0)">
                    <img class="thumbnail" src="assets/img/news/03.jpg" draggable="false" onclick="currentSlide(1)">
                    <img class="thumbnail" src="assets/img/news/03.jpg" draggable="false" onclick="currentSlide(2)">
                    <img class="thumbnail" src="assets/img/news/04.jpg" draggable="false" onclick="currentSlide(3)">
                    <img class="thumbnail" src="assets/img/news/03.jpg" draggable="false" onclick="currentSlide(4)">
                    <img class="thumbnail" src="assets/img/news/04.jpg" draggable="false" onclick="currentSlide(5)">
                    <img class="thumbnail" src="assets/img/news/01.jpg" draggable="false" onclick="currentSlide(6)">
                    <img class="thumbnail" src="assets/img/news/03.jpg" draggable="false" onclick="currentSlide(7)">
                    <img class="thumbnail" src="assets/img/news/03.jpg" draggable="false" onclick="currentSlide(8)">
                    <img class="thumbnail" src="assets/img/news/03.jpg" draggable="false" onclick="currentSlide(9)">
                    <img class="thumbnail" src="assets/img/news/03.jpg" draggable="false" onclick="currentSlide(10)">
                    <img class="thumbnail" src="assets/img/news/03.jpg" draggable="false" onclick="currentSlide(11)">
                </div>
            </div>
    
        </div>

    </main>

    <!-- Main Area End -->

    <!-- ============================================================================== -->
     
    <!-- News Area End -->

    <section class="pt-0">

        <div class="news">

            <div class="newsInside">

                <div class="newsLeft">
                    
                    <div class="movieInfo">
                        <div>
                            <p class="titleMovie">Filmin Türü</p>
                            <p>Bilim-Kurgu, 3 Boyutlu, IMAX</p>
                        </div>
                        <div>
                            <p class="titleMovie">Yönetmen</p>
                            <p><i class="fa-solid fa-clapperboard"></i> Denis Villeneuve</p>
                        </div>
                        <div>
                            <p class="titleMovie">Senaryo</p>
                            <p><i class="fa-solid fa-pen-nib"></i> Michael Green, Hampton Fencher</p>
                        </div>
                        <div>
                            <p class="titleMovie">Oyuncular</p>
                            <p><i class="fa-solid fa-users"></i> Harrison Ford, Ryan Gosling, Robin Wright</p>
                        </div>
                        <div class="row-btw">
                            <div>
                                <p class="titleMovie">Görüntü Yönetmeni</p>
                                <p><i class="fa-solid fa-video"></i> Roger Deakins</p>
                            </div>
                            <div>
                                <p class="titleMovie">Kurgu</p>
                                <p><i class="fa-solid fa-circle-half-stroke"></i> Joe Walker</p>
                            </div>
                        </div>
                        <div class="row-btw">
                            <div>
                                <p class="titleMovie">Vizyon Tarihi</p>
                                <p><i class="fa-regular fa-calendar"></i> 6 Ekim 2017</p>
                            </div>
                            <div>
                                <p class="titleMovie">Stüdyo</p>
                                <p>Sony Pictures</p>
                            </div>
                        </div>
                        <div class="row-btw">
                            <div>
                                <p class="titleMovie">Sinema Dağıtım</p>
                                <p>Warner Bros. Türkiye</p>
                            </div>
                            <div>
                                <p class="titleMovie">Ülke</p>
                                <p>ABD</p>
                            </div>
                        </div>
                        <div>
                            <p class="titleMovie">Müzik</p>
                            <p><i class="fa-solid fa-music"></i> Hans Zimmer, Benjamin Wallfisch</p>
                        </div>
                    </div>

                    <div class="newsTextArea mt-1">
                        <h2><i class="fa-solid fa-video"></i> Filmin Konusu</h2>

                        <p>Denis Villeneuve'ün yönetiği Blade Runner 2049, Ridley Scott'ın 1982 yapımı bilim-kurgu klasiğinin izini sürüyor. Film, üstü örtülü bir sırrı keşfetmesiyle 34 yıldır kayıp olan Rick Deckard'ı arayışa geçen K isimli bir blade runner'ın hikâyesini anlatıyor.</p>

                        <p>Los Angeles Polis Departmanı'nda görev yapan Memur K, toplum yaşamını kaosa sokacak olan ve uzun zamandır saklı kalan bir sırrı açığa çıkartır. Bir felaketi önleyebilmesi için eski ödül avcısı Rick Deckard'ı bulup ondan bazı sorularına yanıt alması şarttır.</p>

                        <h2><i class="fa-solid fa-user-tie"></i> Filmin Kadrosu</h2>

                        <div class="search-containerx">
                            <label for="search">Tüm Seanslar <br> <span class="cinema-count">(213 sinema)</span></label>
                            <input type="text" id="search" oninput="filterCinemas()" placeholder="Ara...">
                            <ul id="cinema-list" class="dropdown-list">
                                <li>İstanbul Avrupa</li>
                                <li>İstanbul Asya</li>
                                <li>Başakşehir Cinetech Mall of İstanbul</li>
                                <li>Bayrampaşa Forum İstanbul Cinenova</li>
                                <li>Beylikdüzü Perla Vista Cinema Pink</li>
                            </ul>
                        </div>

                        <div class="yearSelect responsDays">
                            <a href="" class="col-center yearBtn active">
                                <p>Per</p>
                                <p>19 Eylül</p>
                            </a>
                            <a href="" class="col-center yearBtn activex">
                                <p>Cum</p>
                                <p>20 Eylül</p>
                            </a>
                            <a href="" class="col-center yearBtn activex">
                                <p>Cmt</p>
                                <p>21 Eylül</p>
                            </a>
                            <a href="" class="col-center yearBtn activex">
                                <p>Paz</p>
                                <p>22 Eylül</p>
                            </a>
                            <a href="" class="col-center yearBtn activex">
                                <p>Pzt</p>
                                <p>23 Eylül</p>
                            </a>
                            <a href="" class="col-center yearBtn activex">
                                <p>Sal</p>
                                <p>24 Eylül</p>
                            </a>
                            <a href="" class="col-center yearBtn activex">
                                <p>Çar</p>
                                <p>25 Eylül</p>
                            </a>
                        </div>

                        <div class="cinema-container">
                            <div class="cinema-item">
                                <div class="cinema-header" onclick="toggleDetails(this)">
                                    <div class="cinema-info">
                                        <h2>Ataşehir Cinematica</h2>
                                        <p>Atatürk Mah. Ertuğrul Gazi Sok. Metropol İstanbul AVM Bina No:22 Ataşehir/İstanbul</p>
                                    </div>
                                    <div class="toggle-icon"><i class="fa-solid fa-caret-down"></i></div>
                                </div>
                                <div class="cinema-details">
                                    <p>Dijital (2D), Türkçe</p>
                                    <div class="showtimes">
                                        <button>11:15</button>
                                        <button>13:45</button>
                                        <button>16:15</button>
                                        <button>19:30</button>
                                        <button>21:30</button>
                                        <button>21:30</button>
                                        <button>21:30</button>
                                        <button>21:30</button>
                                        <button>21:30</button>
                                        <button>21:30</button>
                                        <button>21:30</button>
                                        <button>21:30</button>
                                        <button>21:30</button>
                                        <button>21:30</button>
                                        <button>21:30</button>
                                    </div>
                                </div>
                            </div>
                    
                            <div class="cinema-item">
                                <div class="cinema-header" onclick="toggleDetails(this)">
                                    <div class="cinema-info">
                                        <h2>Kadıköy Paribu Cineverse (Nautilus)</h2>
                                        <p>Fatih Cad. No:1 Tepe Nautilus AVM Kadıköy/İstanbul</p>
                                    </div>
                                    <div class="toggle-icon"><i class="fa-solid fa-caret-down"></i></div>
                                </div>
                                <div class="cinema-details">
                                    <p>Dijital (2D), Türkçe</p>
                                    <div class="showtimes">
                                        <button>11:15</button>
                                        <button>19:30</button>
                                        <button>21:30</button>
                                    </div>
                                </div>
                            </div>
                    
                            <div class="cinema-item">
                                <div class="cinema-header" onclick="toggleDetails(this)">
                                    <div class="cinema-info">
                                        <h2>Kartal Paribu Cineverse (Anatolium Marmara)</h2>
                                        <p>Soğanlık Yeni Mah. Soğanlık D-100 Kuzey Yanyol Cad. No:72, 34865 Kartal/İstanbul</p>
                                    </div>
                                    <div class="toggle-icon"><i class="fa-solid fa-caret-down"></i></div>
                                </div>
                                <div class="cinema-details">
                                    <p>Dijital (2D), Türkçe</p>
                                    <div class="showtimes">
                                        <button>16:15</button>
                                        <button>19:30</button>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <h2><i class="fa-solid fa-user-tie"></i> Filmin Kadrosu</h2>

                        <div class="oyuncular">

                            <a href="filmler/oyuncu-detay.php">
                                <img src="assets/img/news/images1.jpg" alt="">
                                <p class="titleMovie">Ryan Gosling</p>
                            </a>
                            <a href="filmler/oyuncu-detay.php">
                                <img src="assets/img/news/images2.jpg" alt="">
                                <p class="titleMovie">Harrison Ford</p>
                            </a>

                            <a href="filmler/oyuncu-detay.php">
                                <img src="assets/img/news/images1.jpg" alt="">
                                <p class="titleMovie">Ryan Gosling</p>
                            </a>
                            <a href="filmler/oyuncu-detay.php">
                                <img src="assets/img/news/images2.jpg" alt="">
                                <p class="titleMovie">Harrison Ford</p>
                            </a>

                            <a href="filmler/oyuncu-detay.php">
                                <img src="assets/img/news/images1.jpg" alt="">
                                <p class="titleMovie">Ryan Gosling</p>
                            </a>
                            <a href="filmler/oyuncu-detay.php">
                                <img src="assets/img/news/images2.jpg" alt="">
                                <p class="titleMovie">Harrison Ford</p>
                            </a>

                        </div>

                    </div>

                </div>

                <div class="newsRight bgnone">

                    <div class="movieInfo">
                        <h3><i class="fa-solid fa-box"></i> Gişe Özeti</h3>
                        <div class="tab-content w-100">
                            <ul class="list">
                                <li>
                                    <div>
                                        <div class="infInside">
                                            <p class="titleMovie">SEYİRCİ</p>
                                            <div class="rowIns">
                                                <div>
                                                    <p>İlk Hafta Sonu</p>
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
                                    </div>
                                </li>
                                
                                <li>
                                    <div>
                                        <div class="infInside mt-1">
                                            <p class="titleMovie">HASILAT</p>
                                            <div class="rowIns">
                                                <div>
                                                    <p>İlk Hafta Sonu</p>
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
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <h2><i class="fa-solid fa-newspaper"></i> Güncel Haberler</h2>
                    <a href="" class="newsBoxHafta">
                        <div class="haftaImg">
                            <img src="assets/img/news/02.jpg" alt="">
                        </div>
                        <p>Üçlemenin finalini yapan Venom: Son Dans'tan yeni fragman yayınlandı</p>
                        <p class="date"><i class="fa-regular fa-clock"></i> 06 eylül 2024</p>
                    </a>
                    <a href="" class="newsBoxHafta">
                        <div class="haftaImg">
                            <img src="assets/img/news/02.jpg" alt="">
                        </div>
                        <p>Üçlemenin finalini yapan Venom: Son Dans'tan yeni fragman yayınlandı</p>
                        <p class="date"><i class="fa-regular fa-clock"></i> 06 eylül 2024</p>
                    </a>
                    <a href="" class="newsBoxHafta">
                        <div class="haftaImg">
                            <img src="assets/img/news/02.jpg" alt="">
                        </div>
                        <p>Üçlemenin finalini yapan Venom: Son Dans'tan yeni fragman yayınlandı</p>
                        <p class="date"><i class="fa-regular fa-clock"></i> 06 eylül 2024</p>
                    </a>
                </div>
            
            </div>

        </div>
    </section>

    <!-- News Area End -->

    <?php include('../footer.php');?>
</body>
</html>