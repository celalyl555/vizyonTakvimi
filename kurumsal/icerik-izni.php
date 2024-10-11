<?php include('../header.php');?>
    <!-- ============================================================================== -->
    
    <!-- Table Area Start -->

    <section class="haftaSection">

        <div class="haftaMain">

            <h2><i class="fa-regular fa-circle-check"></i> İçerik İzni</h2>
            <p class="title">Talebiniz için aşağıdaki form doldurulmalıdır</p>
        
            <p class="title mt-1">Sitedeki içeriklerin izin alınmadan kullanılması kesinlikle yasaktır. İzin için lütfen aşağıdaki formu eksiksiz olarak doldurmanızı rica ederiz.</p>

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

                    <label for="alan">KULLANILACAK ALAN:</label>
                    <input type="text" id="alan" name="alan" placeholder="Web Sitesi / Uygulama">

                    <label for="detay">KULLANIM AMACI</label>
                    <textarea id="detay" name="detay" placeholder="Kullanım Amacınızı Giriniz"></textarea>

                    <label for="not">NOTUNUZ</label>
                    <textarea id="not" name="not" placeholder="Notunuz Varsa Belirtiniz"></textarea>

                    <button class="submitForm">Gönder</button>
                </form>
            </div>
            
        </div>

    </section>

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
                var alan = $('#alan').val();
                var detay = $('#detay').val();
                var not = $('#not').val();
                
                    $.ajax({
                    url: 'kurumsal/MailManager/ReklamSubmit.php',
                    method: 'POST',
                    data: {
                        name: name,
                        firma: firma,
                        mail: mail,
                        phone: phone,
                        alan: alan,
                        detay: detay,
                        not: not
                    },
                    success: function(response) {
                        if (response = 1) {
                            alert("Talebiniz alınmıştır.")
                            location.reload();
                        } else {
                            alert(response);
                        }

                    }
                });
            });
        });
    </script>
    <!-- Table Area End -->

    <!-- ============================================================================== -->

    <?php include('../footer.php');?>

</body>
</html>