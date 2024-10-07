<?php include('../header.php');?>
    <!-- ============================================================================== -->
    
    <!-- Table Area Start -->

    <section class="haftaSection">

        <div class="haftaMain">

            <h2><i class="fa-solid fa-box-open"></i> UIP Türkiye</h2>
            <p class="title">UIP Türkiye Dağıtımcılığını Yaptığı Filmler</p>


            <div class="yearSelect mt-1">
                <a href="" class="yearBtn activex"><i class="fa-solid fa-angles-left"></i> 2023</a>
                <select name="centerBtn" id="centerBtn" class="centerBtn">
                    <option value="2024">2024</option>
                    <option value="2023">2023</option>
                    <option value="2022">2022</option>
                    <option value="2021">2021</option>
                </select>
                <a href="" class="yearBtn activex">2025 <i class="fa-solid fa-angles-right"></i></a>
            </div>
        
            <div class="containerTable">
                <table id="movie-table">
                    <thead>
                        <tr>
                            <th><span class="sort" data-sort="film-name">Film Adı <i class="fas fa-sort"></i></span></th>
                            <th><span class="sort" data-sort="studio">Stüdyo & Şirket <i class="fas fa-sort"></i></span></th>
                            <th><span class="sort" data-sort="week">Hafta <i class="fas fa-sort"></i></span></th>
                            <th><span class="sort" data-sort="week-revenue">Lokasyon <i class="fas fa-sort"></i></span></th>
                            <th><span class="sort" data-sort="total-revenue">Toplam Hasılat <i class="fas fa-sort"></i></span></th>
                            <th><span class="sort" data-sort="total-audience">Toplam Seyirci <i class="fas fa-sort"></i></span></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><div class="nameBox"><img class="tableImg" src="assets/img/news/04.jpg" alt=""><div> <a href="" title="Blade Runner : 2049">Blade Runner : 2049</a><br><small>6 Eylül 2024</small></div></div></td>
                            <td>Disney</td>
                            <td>1</td>
                            <td>320</td>
                            <td>₺10.071.952</td>
                            <td>50.203</td>
                        </tr>
                        <tr>
                            <td><div class="nameBox"><img class="tableImg" src="assets/img/mainImg/01.jpg" alt=""><div> <a href="" title="Deadpool & Wolverine">Deadpool & Wolverine</a><br><small>24 Temmuz 2024</small></div></div></td>
                            <td>Disney</td>
                            <td>7</td>
                            <td>321</td>
                            <td>₺240.916.350</td>
                            <td>1.355.599</td>
                        </tr>
                        <tr>
                            <td><div class="nameBox"><img class="tableImg" src="assets/img/news/02.jpg" alt=""><div> <a href="" title="Ters Yüz 2">Ters Yüz 2</a><br><small>14 Haziran 2024</small></div></div></td>
                            <td>Disney</td>
                            <td>13</td>
                            <td>322</td>
                            <td>₺381.752.103</td>
                            <td>2.307.488</td>
                        </tr>
                        <tr>
                            <td><div class="nameBox"><img class="tableImg" src="assets/img/news/02.jpg" alt=""><div> <a href="" title="Çılgın Hırsız 4">Çılgın Hırsız 4</a><br><small>5 Temmuz 2024</small></div></div></td>
                            <td>Disney</td>
                            <td>10</td>
                            <td>320</td>
                            <td>₺160.800.661</td>
                            <td>981.508</td>
                        </tr>
                        <tr>
                            <td><div class="nameBox"><img class="tableImg" src="assets/img/news/01.jpg" alt=""><div> <a href="" title="Cambaz">Cambaz</a><br><small>6 Eylül 2024</small></div></div></td>
                            <td>Disney</td>
                            <td>1</td>
                            <td>195</td>
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