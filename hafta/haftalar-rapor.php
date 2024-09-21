<?php include ("../header.php") ?>

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
                    <a href="haftalar.php" class="tabBtnBoxa">Özet Tablo</a>
                </div>
                <div class="tabBtnBox">
                    <a href="haftalar-rapor.php" class="tabBtnBoxa active">Hafta Raporu</a>
                </div>
            </div>
        
            <div class="containerTable">
                <p class="tableHeader">Haftalık Tüm Filmler Raporu</p>
                <table id="movie-table">
                    <thead>
                        <tr>
                            <th class="sort">#</th>
                            <th class="sort">Film</th>
                            <th class="sort">Seyirci</th>
                            <th class="sort">Seyirci %</th>
                            <th class="sort">Hasılat</th>
                            <th class="sort">Hasılat %</th>
                            <th class="sort">Ort. Bilet</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Yabancı Filmler</td>
                            <td>34</td>
                            <td>198.055</td>
                            <td>% 82,67</td>
                            <td>₺36.487.742</td>
                            <td>% 85,95</td>
                            <td>₺184,23</td>
                        </tr>
                        <tr class="border-btm">
                            <td>Yerli Filmler</td>
                            <td>34</td>
                            <td>198.055</td>
                            <td>% 82,67</td>
                            <td>₺36.487.742</td>
                            <td>% 85,95</td>
                            <td>₺184,23</td>
                        </tr>
                        <tr class="strong">
                            <td>Toplam</td>
                            <td>34</td>
                            <td>198.055</td>
                            <td>% 82,67</td>
                            <td>₺36.487.742</td>
                            <td>% 85,95</td>
                            <td>₺184,23</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="containerTable">
                <p class="tableHeader">Haftalık Yabancı Film Raporu</p>
                <table id="movie-table">
                    <thead>
                        <tr>
                            <th class="sort">#</th>
                            <th class="sort">Film</th>
                            <th class="sort">Seyirci</th>
                            <th class="sort">Seyirci %</th>
                            <th class="sort">Hasılat</th>
                            <th class="sort">Hasılat %</th>
                            <th class="sort">Ort. Bilet</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Stüdyo Filmleri</td>
                            <td>34</td>
                            <td>198.055</td>
                            <td>% 82,67</td>
                            <td>₺36.487.742</td>
                            <td>% 85,95</td>
                            <td>₺184,23</td>
                        </tr>
                        <tr class="border-btm">
                            <td>Diğer Yabancı Filmler</td>
                            <td>34</td>
                            <td>198.055</td>
                            <td>% 82,67</td>
                            <td>₺36.487.742</td>
                            <td>% 85,95</td>
                            <td>₺184,23</td>
                        </tr>
                        <tr class="strong">
                            <td>Toplam</td>
                            <td>34</td>
                            <td>198.055</td>
                            <td>% 82,67</td>
                            <td>₺36.487.742</td>
                            <td>% 85,95</td>
                            <td>₺184,23</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="containerTable">
                <p class="tableHeader">Haftalık Dağıtımcı Raporu</p>
                <table id="movie-table">
                    <thead>
                        <tr>
                            <th class="sort">#</th>
                            <th class="sort">Film</th>
                            <th class="sort">Seyirci</th>
                            <th class="sort">Seyirci %</th>
                            <th class="sort">Hasılat</th>
                            <th class="sort">Hasılat %</th>
                            <th class="sort">Ort. Bilet</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>TME Films</td>
                            <td>34</td>
                            <td>198.055</td>
                            <td>% 82,67</td>
                            <td>₺36.487.742</td>
                            <td>% 85,95</td>
                            <td>₺184,23</td>
                        </tr>
                        <tr>
                            <td>UIP Türkiye</td>
                            <td>34</td>
                            <td>198.055</td>
                            <td>% 82,67</td>
                            <td>₺36.487.742</td>
                            <td>% 85,95</td>
                            <td>₺184,23</td>
                        </tr>
                        <tr>
                            <td>Bir Film</td>
                            <td>34</td>
                            <td>198.055</td>
                            <td>% 82,67</td>
                            <td>₺36.487.742</td>
                            <td>% 85,95</td>
                            <td>₺184,23</td>
                        </tr>
                        <tr>
                            <td>CGV Mars D.</td>
                            <td>34</td>
                            <td>198.055</td>
                            <td>% 82,67</td>
                            <td>₺36.487.742</td>
                            <td>% 85,95</td>
                            <td>₺184,23</td>
                        </tr>
                        <tr class="border-btm">
                            <td>CJ ENM</td>
                            <td>34</td>
                            <td>198.055</td>
                            <td>% 82,67</td>
                            <td>₺36.487.742</td>
                            <td>% 85,95</td>
                            <td>₺184,23</td>
                        </tr>
                        <tr class="strong">
                            <td>Toplam</td>
                            <td>34</td>
                            <td>198.055</td>
                            <td>% 82,67</td>
                            <td>₺36.487.742</td>
                            <td>% 85,95</td>
                            <td>₺184,23</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            
        </div>

    </section>

    <!-- Table Area End -->

    <!-- ============================================================================== -->

    <?php include ("../footer.php") ?>

    <!-- ============================================================================== -->

</body>
</html>