<?php include('../header.php');?>

    <!-- ============================================================================== -->
    
    <!-- Table Area Start -->

    <section class="haftaSection">

        <div class="haftaMain">

            <h2><i class="fa-regular fa-calendar-days"></i> Vizyon Takvimi Tablosu</h2>
            <p class="title">Filmlerin vizyon tarihleri</p>

            <div class="settingsBox">
                <div class="settingsBox center-f">
                    <button class="cfBtn active">Hepsi</button>
                    <button class="cfBtn red">Yerli Film</button>
                    <button class="cfBtn blue">Yabancı Film</button>
                    <button class="cfBtn green">Animasyon</button>
                    <button class="cfBtn yellow">3D Film</button>
                    <button class="cfBtn purple">MPA</button>
                </div>
                <div class="settingsBox end-f">
                    <div>
                        <label for="start">Başlangıç</label>
                        <input type="date" id="start">
                    </div>

                    <div>
                        <label for="start">Bitiş</label>
                        <input type="date" id="start">
                    </div>
                </div>
            </div>
        
            <div class="containerTable">
                <table id="movie-table-tum">
                    <thead>
                        <tr>
                            <th>Tarih</th>
                            <th>UIP</th>
                            <th>TME Films</th>
                            <th>A90 Pictures</th>
                            <th>CJ ENM</th>
                            <th>CGV Mars</th>
                            <th>Bir Film</th>
                            <th>Başka Sinema</th>
                            <th>Özen Film</th>
                            <th>Diğer Yerli</th>
                            <th>Diğer Yabancı</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="numbertT">13.09.2024</td>
                            <td>
                                <div class="moviesTd">
                                    <a href="">Speak No Evil</a>
                                    <p class="titleDown red">Universal</p>
                                </div>
                            </td>
                            <td></td>
                            <td>
                                <div class="moviesTd">
                                    <p class="categoryMovie">yerli</p>
                                    <a href="">İzliyorlar</a>
                                    <p class="titleDown blue">Halakoei Film</p>
                                </div>
                            </td>
                            <td></td>
                            <td>
                                <div class="moviesTd">
                                    <p class="categoryMovie">yerli</p>
                                    <a href="">İzliyorlar</a>
                                    <p class="titleDown blue">Halakoei Film</p>
                                </div>
                                <div class="moviesTd">
                                    <p class="categoryMovie">yerli</p>
                                    <a href="">İzliyorlar</a>
                                    <p class="titleDown blue">Halakoei Film</p>
                                </div>
                                <div class="moviesTd">
                                    <p class="categoryMovie">yerli</p>
                                    <a href="">İzliyorlar</a>
                                    <p class="titleDown blue">Halakoei Film</p>
                                </div>
                            </td>
                            <td></td>
                            <td>
                                <div class="moviesTd">
                                    <a href="">Speak No Evil</a>
                                    <p class="titleDown red">Universal</p>
                                </div>
                                <div class="moviesTd">
                                    <a href="">Speak No Evil</a>
                                    <p class="titleDown red">Universal</p>
                                </div>
                            </td>
                            <td></td>
                            <td>
                                <div class="moviesTd">
                                    <a href="">Speak No Evil</a>
                                    <p class="titleDown red">Universal</p>
                                </div>
                            </td>
                            <td></td>
                        </tr>
                        <tr>
                            <td class="numbertT">20.09.2024</td>
                            <td>
                                <div class="moviesTd">
                                    <a href="">Speak No Evil</a>
                                    <p class="titleDown red">Universal</p>
                                </div>
                            </td>
                            <td></td>
                            <td>
                                <div class="moviesTd">
                                    <p class="categoryMovie">yerli</p>
                                    <a href="">İzliyorlar</a>
                                    <p class="titleDown blue">Halakoei Film</p>
                                </div>
                            </td>
                            <td></td>
                            <td></td>
                            <td>
                                <div class="moviesTd">
                                    <p class="categoryMovie">yerli</p>
                                    <a href="">İzliyorlar</a>
                                    <p class="titleDown blue">Halakoei Film</p>
                                </div>
                                <div class="moviesTd">
                                    <p class="categoryMovie">yerli</p>
                                    <a href="">İzliyorlar</a>
                                    <p class="titleDown blue">Halakoei Film</p>
                                </div>
                                <div class="moviesTd">
                                    <p class="categoryMovie">yerli</p>
                                    <a href="">İzliyorlar</a>
                                    <p class="titleDown blue">Halakoei Film</p>
                                </div>
                            </td>
                            <td>
                                <div class="moviesTd">
                                    <a href="">Speak No Evil</a>
                                    <p class="titleDown red">Universal</p>
                                </div>
                                <div class="moviesTd">
                                    <a href="">Speak No Evil</a>
                                    <p class="titleDown red">Universal</p>
                                </div>
                            </td>
                            <td></td>
                            <td></td>
                            <td>
                                <div class="moviesTd">
                                    <a href="">Speak No Evil</a>
                                    <p class="titleDown red">Universal</p>
                                </div>
                            </td>
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