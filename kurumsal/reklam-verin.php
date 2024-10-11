<?php include('../header.php');?>
    <!-- ============================================================================== -->
    
    <!-- Table Area Start -->

    <section class="haftaSection">

        <div class="haftaMain">

            <h2><i class="fa-solid fa-plus"></i> Reklam Verin</h2>
            <p class="title">Talebiniz için aşağıdaki form doldurulmalıdır</p>
        
            <p class="title mt-1">Web sitemizde reklamınız yayınlansın istiyorsanız, detaylarıyla beraber bize aşağıdaki form vasıtasıyla ulaşabilirsiniz. Size en kısa sürede dönüş yapılacaktır.</p>

            <div class="reklam-form">
                <form>
                    <label for="name">ADINIZ SOYADINIZ:</label>
                    <input type="text" id="name" name="name" placeholder="Adınızı ve Soyadınızı Giriniz">

                    <label for="firm">FİRMA ADI:</label>
                    <input type="text" id="firm" name="firm" placeholder="Firma Adı Giriniz">

                    <label for="mail">E-POSTA ADRESİ:</label>
                    <input type="email" id="mail" name="mail" placeholder="Ornek@mail.com">

                    <label for="phone">TELEFON NUMARASI:</label>
                    <input type="tel" id="phone" name="phone" placeholder="05** *** ** **">

                    <label for="detay">VERMEK İSTEDİĞİNİZ REKLAM İÇİN DETAYLAR</label>
                    <textarea id="detay" name="detay" placeholder="(Film Adı, Tarih Aralığı gibi)"></textarea>

                    <label for="not">NOTUNUZ</label>
                    <textarea id="not" name="not" placeholder="Notunuz Varsa Belirtiniz"></textarea>

                    <button class="submitForm">Gönder</button>
                </form>
            </div>
            
        </div>

    </section>

    <!-- Table Area End -->

    <!-- ============================================================================== -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
       $(document).ready(function() {
    $('.submitForm').on('click', function(event) {
        event.preventDefault(); // Formun normal gönderimini engelle

        // Formdan verileri al
        var name = $('#name').val();
        var firma = $('#firm').val();
        var mail = $('#mail').val();
        var phone = $('#phone').val(); // Telefon numarasını da al
        var detay = $('#detay').val();
        var not = $('#not').val();

        // Boş alanları kontrol et
        if (name === "" || firma === "" || mail === "" || phone === "" || detay === "" || not === "") {
            alert("Lütfen tüm alanları doldurunuz.");
            return; // Alanlar boşsa formu gönderme
        }

        // Alanlar doluysa AJAX ile formu gönder
        $.ajax({
            url: 'kurumsal/MailManager/ReklamSubmit.php',
            method: 'POST',
            data: {
                name: name,
                firma: firma,
                mail: mail,
                phone: phone,
                detay: detay,
                not: not
            },
            success: function(response) {
                if (response == 1) { // "==" ile eşitlik kontrolü yap
                    alert("Reklam formu talebiniz alınmıştır.");
                    location.reload();
                } else {
                    alert(response);
                }
            }
        });
    });
});

    </script>
    <?php include('../footer.php');?>
</body>
</html>