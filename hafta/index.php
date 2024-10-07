<?php include('../header.php');?>
    <!-- ============================================================================== -->
    
    <!-- Table Area Start -->

    <section class="haftaSection">

        <div class="haftaMain">

            <h2><i class="fa-solid fa-box-open"></i> Haftalık Gişe Hasılatı</h2>
            <p>Haftalara Göre Toplam Seyirci ve Hasılat Sayıları</p>
            
            <div class="status f-start">
                <div class="tabBtnBox">
                    <a href="hafta/index.php" class="tabBtnBoxa active">Yıllara Göre</a>
                </div>
                <div class="tabBtnBox">
                    <a href="hafta/index-hafta.php" class="tabBtnBoxa">Haftalara Göre</a>
                </div>
            </div>
            
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
                        <a href="" class="yearBtn activex"><i class="fa-solid fa-angles-left"></i> 2023</a>
                        <select name="centerBtn" id="centerBtn" class="centerBtn">
                            <option value="2024">2024</option>
                            <option value="2023">2023</option>
                            <option value="2022">2022</option>
                            <option value="2021">2021</option>
                        </select>
                        <a href="" class="yearBtn activex">2025 <i class="fa-solid fa-angles-right"></i></a>
                    </div>

                    <div class="containerAy">
                        <div class="tabs-hafta">
                            <button class="tab-button-hafta activex" data-tab="seyirci">Seyirci</button>
                            <button class="tab-button-hafta" data-tab="hasilat">Hasılat</button>
                        </div>
                        <div class="tab-content-hafta" id="seyirci">
                            <div class="month">
                                <h3><i class="fa-solid fa-calendar-week"></i> Eylül</h3>
                                <table class="mt-0">
                                    <thead>
                                        <tr>
                                            <th>Hafta</th>
                                            <th>İlk 10 Film Seyirci</th>
                                            <th>Tüm Filmler Seyirci</th>
                                            <th>Film</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td><a href="haftalar.php" class="clicka">37. Hafta <br> 06-12 Eyl.</a></td>
                                            <td><span class="asc"><i class="fa-solid fa-up-long"></i> %21.6</span> 202.152</td>
                                            <td><span class="asc"><i class="fa-solid fa-up-long"></i> %16.8</span> 239.578</td>
                                            <td>69</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="month">
                                <h3><i class="fa-solid fa-calendar-week"></i> Ağustos</h3>
                                <table class="mt-0">
                                    <thead>
                                        <tr>
                                            <th>Hafta</th>
                                            <th>İlk 10 Film Seyirci</th>
                                            <th>Tüm Filmler Seyirci</th>
                                            <th>Film</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td><a href="haftalar.php" class="clicka">36. Hafta <br> 30 Ağu.-05 Eyl.</a></td>
                                            <td><span class="decrease"><i class="fa-solid fa-down-long"></i> %9.0</span> 257.693</td>
                                            <td><span class="decrease"><i class="fa-solid fa-down-long"></i> %6.6</span> 288.081</td>
                                            <td>65</td>
                                        </tr>
                                        <tr>
                                            <td><a href="haftalar.php" class="clicka">35. Hafta <br> 30 Ağu.-05 Eyl.</a></td>
                                            <td><span class="decrease"><i class="fa-solid fa-down-long"></i> %9.0</span> 257.693</td>
                                            <td><span class="decrease"><i class="fa-solid fa-down-long"></i> %6.6</span> 288.081</td>
                                            <td>65</td>
                                        </tr>
                                        <tr>
                                            <td><a href="haftalar.php" class="clicka">34. Hafta <br> 30 Ağu.-05 Eyl.</a></td>
                                            <td><span class="decrease"><i class="fa-solid fa-down-long"></i> %9.0</span> 257.693</td>
                                            <td><span class="decrease"><i class="fa-solid fa-down-long"></i> %6.6</span> 288.081</td>
                                            <td>65</td>
                                        </tr>
                                        <tr>
                                            <td><a href="haftalar.php" class="clicka">33. Hafta <br> 30 Ağu.-05 Eyl.</a></td>
                                            <td><span class="decrease"><i class="fa-solid fa-down-long"></i> %9.0</span> 257.693</td>
                                            <td><span class="decrease"><i class="fa-solid fa-down-long"></i> %6.6</span> 288.081</td>
                                            <td>65</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="tab-content-hafta hidden" id="hasilat">
                            <div class="month">
                                <h3><i class="fa-solid fa-calendar-week"></i> Eylül</h3>
                                <table class="mt-0">
                                    <thead>
                                        <tr>
                                            <th>Hafta</th>
                                            <th>İlk 10 Film Hasılat</th>
                                            <th>Tüm Filmler Hasılat</th>
                                            <th>Film</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td><a href="haftalar.php" class="clicka">37. Hafta <br> 06-12 Eyl.</a></td>
                                            <td><span class="decrease"><i class="fa-solid fa-down-long"></i> %21.6</span> 202.152</td>
                                            <td><span class="decrease"><i class="fa-solid fa-down-long"></i> %16.8</span> 239.578</td>
                                            <td>69</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="month">
                                <h3><i class="fa-solid fa-calendar-week"></i> Ağustos</h3>
                                <table class="mt-0">
                                    <thead>
                                        <tr>
                                            <th>Hafta</th>
                                            <th>İlk 10 Film Hasılat</th>
                                            <th>Tüm Filmler Hasılat</th>
                                            <th>Film</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td><a href="haftalar.php" class="clicka">36. Hafta <br> 30 Ağu.-05 Eyl.</a></td>
                                            <td><span class="decrease"><i class="fa-solid fa-down-long"></i> %9.0</span> 257.693</td>
                                            <td><span class="decrease"><i class="fa-solid fa-down-long"></i> %6.6</span> 288.081</td>
                                            <td>65</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    
                </div>

                <div class="newsRight bgnone">
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