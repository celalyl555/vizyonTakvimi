<?php include('../header.php');?>
    <!-- ============================================================================== -->
    
    <!-- Table Area Start -->

    <section class="haftaSection">

        <div class="haftaMain">

            <h2><i class="fa-solid fa-file-signature"></i> Bizimle İletişime Geçin</h2>
            <p class="title">Talebiniz için aşağıdaki form doldurulmalıdır</p>

            <div class="reklam-form">
                <form action="">
                    <label for="name">ADINIZ SOYADINIZ:</label>
                    <input type="text" id="name" name="name" placeholder="Adınızı ve Soyadınızı Giriniz">

                    <label for="firm">FİRMA ADI:</label>
                    <input type="text" id="firm" name="firm" placeholder="Firma Adı Giriniz">

                    <label for="mail">E-POSTA ADRESİ:</label>
                    <input type="email" id="mail" name="mail" placeholder="Ornek@mail.com">

                    <label for="phone">TELEFON NUMARASI:</label>
                    <input type="tel" id="phone" name="phone" placeholder="05** *** ** **">

                    <label for="detay">KONU</label>
                    <textarea id="detay" name="detay" placeholder="İletişim Konusunu Giriniz"></textarea>

                    <label for="not">NOTUNUZ</label>
                    <textarea id="not" name="not" placeholder="Notunuz Varsa Belirtiniz"></textarea>

                    <button class="submitForm">Gönder</button>
                </form>
            </div>
            
        </div>

    </section>

    <!-- Table Area End -->

    <!-- ============================================================================== -->
    <?php include('../footer.php');?>

</body>
</html>