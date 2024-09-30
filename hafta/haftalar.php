<?php include('../header.php');?>

    <!-- ============================================================================== -->
    
    <!-- Table Area Start -->

    <section class="haftaSection">

        <div class="haftaMain">

            <h2><i class="fa-solid fa-box-open"></i> Haftalık Gişe Hasılatı</h2>
            <p class="title">Haftalık Film Gişe Verileri</p>

            <div class="status">
                <div class="statusBox">
                    <p class="title">Toplam Seyirci</p>
                    <p><strong>239.578</strong></p>
                </div>
                <div class="statusBox">
                    <p class="title">Toplam Hasılat</p>
                    <p><strong>₺42.450.078</strong></p>
                </div>
                <div class="statusBox">
                    <p class="title">Film Sayısı</p>
                    <p><strong>69</strong></p>
                </div>
            </div>
            
            <div class="status">
                <div class="tabBtnBox">
                    <a href="haftalar.php" class="tabBtnBoxa active">Özet Tablo</a>
                </div>
                <div class="tabBtnBox">
                    <a href="hafta/haftalar-rapor.php" class="tabBtnBoxa">Hafta Raporu</a>
                </div>
            </div>
        
            <div class="containerTable">
                <table id="movie-table">
                    <thead>
                        <tr>
                            <th><span class="sort" data-sort="index"># <i class="fas fa-sort"></i></span></th>
                            <th><span class="sort" data-sort="film-name">Film Adı <i class="fas fa-sort"></i></span></th>
                            <th><span class="sort" data-sort="distributor">Dağıtımcı <i class="fas fa-sort"></i></span></th>
                            <th><span class="sort" data-sort="salon">Salon <i class="fas fa-sort"></i></span></th>
                            <th><span class="sort" data-sort="week">Hafta <i class="fas fa-sort"></i></span></th>
                            <th><span class="sort" data-sort="week-revenue">Hafta Hasılat <i class="fas fa-sort"></i></span></th>
                            <th><span class="sort" data-sort="week-audience">Hafta Seyirci <i class="fas fa-sort"></i></span></th>
                            <th><span class="sort" data-sort="total-revenue">Toplam Hasılat <i class="fas fa-sort"></i></span></th>
                            <th><span class="sort" data-sort="total-audience">Toplam Seyirci <i class="fas fa-sort"></i></span></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="numberT">1</td>
                            <td><div class="nameBox"><img class="tableImg" src="assets/img/mainImg/01.jpg" alt=""><div> <a href="" title="Beterböcek Beterböcek">Beterböcek Beterböcek</a><br><small>6 Eylül 2024</small></div></div></td>
                            <td><a href="">TME</a></td>
                            <td>526</td>
                            <td>1</td>
                            <td>₺10.071.952</td>
                            <td>50.203</td>
                            <td>₺10.071.952</td>
                            <td>50.203</td>
                        </tr>
                        <tr>
                            <td class="numberT">2</td>
                            <td><div class="nameBox"><img class="tableImg" src="assets/img/mainImg/01.jpg" alt=""><div> <a href="" title="Deadpool & Wolverine">Deadpool & Wolverine</a><br><small>24 Temmuz 2024</small></div></div></td>
                            <td><a href="">UIP</a></td>
                            <td>274</td>
                            <td>7</td>
                            <td>₺7.609.767</td>
                            <td>42.222</td>
                            <td>₺240.916.350</td>
                            <td>1.355.599</td>
                        </tr>
                        <tr>
                            <td class="numberT">3</td>
                            <td><div class="nameBox"><img class="tableImg" src="assets/img/news/02.jpg" alt=""><div> <a href="" title="Ters Yüz 2">Ters Yüz 2</a><br><small>14 Haziran 2024</small></div></div></td>
                            <td><a href="">UIP</a></td>
                            <td>238</td>
                            <td>13</td>
                            <td>₺4.507.455</td>
                            <td>25.974</td>
                            <td>₺381.752.103</td>
                            <td>2.307.488</td>
                        </tr>
                        <tr>
                            <td class="numberT">4</td>
                            <td><div class="nameBox"><img class="tableImg" src="assets/img/news/02.jpg" alt=""><div> <a href="" title="Çılgın Hırsız 4">Çılgın Hırsız 4</a><br><small>5 Temmuz 2024</small></div></div></td>
                            <td><a href="">UIP</a></td>
                            <td>186</td>
                            <td>10</td>
                            <td>₺3.743.719</td>
                            <td>20.722</td>
                            <td>₺160.800.661</td>
                            <td>981.508</td>
                        </tr>
                        <tr>
                            <td class="numberT">5</td>
                            <td><div class="nameBox"><img class="tableImg" src="assets/img/news/01.jpg" alt=""><div> <a href="" title="Cambaz">Cambaz</a><br><small>6 Eylül 2024</small></div></div></td>
                            <td>Bir Film</td>
                            <td>142</td>
                            <td>1</td>
                            <td>₺3.763.836</td>
                            <td>20.347</td>
                            <td>₺3.763.836</td>
                            <td>20.347</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            
        </div>

    </section>

    <!-- Table Area End -->

    <!-- ============================================================================== -->

    <?php include('../footer.php');?>

</body>
</html>