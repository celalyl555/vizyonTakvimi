// Kategori ekleme

$(document).ready(function() {
    $('#submitBtn').click(function(e) {
      e.preventDefault(); // Sayfanın yeniden yüklenmesini engeller

      // Kategori adını al
      var kategoriAdi = $('#kategori_adi').val();

      // AJAX isteğini yap
      $.ajax({
        url: 'controller/oyuncuController.php',
        type: 'POST',
        data: { kategori_adi: kategoriAdi },
        success: function(response) {
          // Başarılı olursa yapılacak işlemler
        
          const button = document.getElementById('addkategoriModal');
            button.click();
            localStorage.setItem("uri", 'content2');
            location.reload();
        },
        error: function(xhr, status, error) {
          // Hata olursa yapılacak işlemler
          console.log('Hata: ' + error);
        }
      });
    });
  });


  // Kategori Silme 

  $(document).ready(function() {
    $('#kategoriSil').click(function(e) {
      e.preventDefault(); // Sayfanın yeniden yüklenmesini engeller

      // Kategori adını al
      var kategoriid = $('#kategoriid').val();
      // AJAX isteğini yap
      $.ajax({
        url: 'controller/oyuncuController.php',
        type: 'POST',
        data: { kategoriid: kategoriid },
        success: function(response) {
          // Başarılı olursa yapılacak işlemler
          
          const button = document.getElementById('deleteEmployeeModalKategori');
            button.click();
            localStorage.setItem("uri", 'content2');
            location.reload();
        },
        error: function(xhr, status, error) {
          // Hata olursa yapılacak işlemler
          console.log('Hata: ' + error);
        }
      });
    });
  });


    // Oyuncu Ekleme


    $(document).ready(function() {
        $('#kayitForm').on('submit', function(e) {
            e.preventDefault(); // Formun varsayılan gönderim işlemini durdur

            var formData = new FormData(this); // Formdaki tüm verileri al

            $.ajax({
                url: 'controller/oyuncuAdd.php', // Form verilerinin gönderileceği PHP dosyası
                type: 'POST',
                data: formData,
                contentType: false, // İçerik tipi formData ile ayarlanmalı
                processData: false, // Verilerin işlenmesini devre dışı bırak
                success: function(response) {
                    // Başarılı ise dönen cevaba göre işlem yap
                   
                    const button = document.getElementById('addoyuncugeri');
                button.click();
                localStorage.setItem("uri", 'content2');
                location.reload();
                },
                error: function(xhr, status, error) {
                    // Hata olursa yapılacaklar
                    console.error(xhr.responseText);
                    console.log("Bir hata oluştu: " + error);
                }
            });
        });
    });

   
    // Oyuncu Silme

    $(document).ready(function() {
        $('#oyuncuSil').click(function(e) {
          e.preventDefault(); // Sayfanın yeniden yüklenmesini engeller
    
          // Kategori adını al
          var oyuncuid = $('#kategoriid').val();
       
          $.ajax({
            url: 'controller/oyuncuController.php',
            type: 'POST',
            data: {oyuncuid:oyuncuid },
            success: function(response) {
              // Başarılı olursa yapılacak işlemler
              
              const button = document.getElementById('deleteoyuncugeri');
                button.click();
                localStorage.setItem("uri", 'content2');
                location.reload();
            },
            error: function(xhr, status, error) {
              // Hata olursa yapılacak işlemler
              console.log('Hata: ' + error);
            }
          });
        });
      });


      // oyuncu Düzenleme

      $(document).ready(function() {
        $('#submitBtnn').click(function() {
            // Form verilerini topla
            var formData = $('#oyuncuForm').serialize();

            // AJAX isteği gönder
            $.ajax({
                url: 'controller/oyuncuController.php',
                type: 'POST',
                data: formData,
                success: function(response) {
                    const button = document.getElementById('oyuncueditgeri');
                    button.click();
                    localStorage.setItem("uri", 'content2');
                    location.reload();
                },
                error: function(xhr, status, error) {
                    // Hata durumunda yapılacak işlemler
                  console.log("Hata oluştu: " + error);
                }
            });
        });
    });

    // FİLMLER SAYFASI 

    //Film türü ekleme 

    $(document).ready(function() {
      $('#submitBtnfilmturu').click(function(e) {
        e.preventDefault(); // Sayfanın yeniden yüklenmesini engeller
        // Kategori adını al
        var filmturu = $('#film_turu').val();
  
        // AJAX isteğini yap
        $.ajax({
          url: 'controller/filmController.php',
          type: 'POST',
          data: { filmturu: filmturu },
          success: function(response) {
            // Başarılı olursa yapılacak işlemler
          
            const button = document.getElementById('addfilmturuModal');
              button.click();
              localStorage.setItem("uri", 'content3');
              location.reload();
          },
          error: function(xhr, status, error) {
            // Hata olursa yapılacak işlemler
            console.log('Hata: ' + error);
          }
        });
      });
    });

    $(document).ready(function() {
      $('#submitBtndizituru').click(function(e) {
        e.preventDefault(); // Sayfanın yeniden yüklenmesini engeller
        // Kategori adını al
        var filmturu = $('#dizi_turu').val();
  
        // AJAX isteğini yap
        $.ajax({
          url: 'controller/filmController.php',
          type: 'POST',
          data: { filmturu: filmturu },
          success: function(response) {
            // Başarılı olursa yapılacak işlemler
          
          
              localStorage.setItem("uri", 'content4');
              location.reload();
          },
          error: function(xhr, status, error) {
            // Hata olursa yapılacak işlemler
            console.log('Hata: ' + error);
          }
        });
      });
    });

    // Film türü Silme 

  $(document).ready(function() {
    $('#filmturuSil').click(function(e) {
      e.preventDefault(); // Sayfanın yeniden yüklenmesini engeller

      // Kategori adını al
      var filmturuid = $('#kategoriid').val();
   
      $.ajax({
        url: 'controller/filmController.php',
        type: 'POST',
        data: { filmturuid: filmturuid },
        success: function(response) {
          // Başarılı olursa yapılacak işlemler
          
          const button = document.getElementById('deleteEmployeeModalfilmturugeri');
            button.click();
            localStorage.setItem("uri", 'content3');
            location.reload();
        },
        error: function(xhr, status, error) {
          // Hata olursa yapılacak işlemler
          console.log('Hata: ' + error);
        }
      });
    });
  });


  $(document).ready(function() {
    $('#dizituruSil').click(function(e) {
      e.preventDefault(); // Sayfanın yeniden yüklenmesini engeller

      // Kategori adını al
      var filmturuid = $('#kategoriid').val();
   
      $.ajax({
        url: 'controller/filmController.php',
        type: 'POST',
        data: { filmturuid: filmturuid },
        success: function(response) {
          // Başarılı olursa yapılacak işlemler
          
        
            localStorage.setItem("uri", 'content4');
            location.reload();
        },
        error: function(xhr, status, error) {
          // Hata olursa yapılacak işlemler
          console.log('Hata: ' + error);
        }
      });
    });
  });

      //Stüdyo ekleme

      $(document).ready(function() {
        $('#submitBtnstudyo').click(function(e) {
          e.preventDefault(); // Sayfanın yeniden yüklenmesini engeller
          // Kategori adını al
          var studyo = $('#studyo').val();
    
          // AJAX isteğini yap
          $.ajax({
            url: 'controller/filmController.php',
            type: 'POST',
            data: { studyo: studyo },
            success: function(response) {
              // Başarılı olursa yapılacak işlemler
            
              const button = document.getElementById('addstudyoModal');
                button.click();
                localStorage.setItem("uri", 'content3');
                location.reload();
            },
            error: function(xhr, status, error) {
              // Hata olursa yapılacak işlemler
              console.log('Hata: ' + error);
            }
          });
        });
      });
  

          // Studyo Silme 

  $(document).ready(function() {
    $('#studyoSil').click(function(e) {
      e.preventDefault(); // Sayfanın yeniden yüklenmesini engeller

      // Kategori adını al
      var studyoid= $('#kategoriid').val();
   
      $.ajax({
        url: 'controller/filmController.php',
        type: 'POST',
        data: { studyoid: studyoid },
        success: function(response) {
          // Başarılı olursa yapılacak işlemler
          
          const button = document.getElementById('deleteEmployeeModalstudyogeri');
            button.click();
            localStorage.setItem("uri", 'content3');
            location.reload();
        },
        error: function(xhr, status, error) {
          // Hata olursa yapılacak işlemler
          console.log('Hata: ' + error);
        }
      });
    });
  });




  //Dağıtım şirketi  ekleme

  $(document).ready(function() {
    $('#submitBtndagitim').click(function(e) {
      e.preventDefault(); // Sayfanın yeniden yüklenmesini engeller
      // Kategori adını al
      var dagitim = $('#dagitim').val();

      // AJAX isteğini yap
      $.ajax({
        url: 'controller/filmController.php',
        type: 'POST',
        data: { dagitim: dagitim },
        success: function(response) {
          // Başarılı olursa yapılacak işlemler
          const button = document.getElementById('addModaldagitim');
            button.click();
            localStorage.setItem("uri", 'content3');
            location.reload();
        },
        error: function(xhr, status, error) {
          // Hata olursa yapılacak işlemler
          console.log('Hata: ' + error);
        }
      });
    });
  });


        // Dağıtım  Silme 

        $(document).ready(function() {
          $('#dagitimSil').click(function(e) {
            e.preventDefault(); // Sayfanın yeniden yüklenmesini engeller
      
            // Kategori adını al
            var dagitimid= $('#kategoriid').val();
         
            $.ajax({
              url: 'controller/filmController.php',
              type: 'POST',
              data: { dagitimid: dagitimid },
              success: function(response) {
                // Başarılı olursa yapılacak işlemler
                
                const button = document.getElementById('deleteEmployeeModaldagitimgeri');
                  button.click();
                  localStorage.setItem("uri", 'content3');
                  location.reload();
              },
              error: function(xhr, status, error) {
                // Hata olursa yapılacak işlemler
                console.log('Hata: ' + error);
              }
            });
          });
        });
      
        // Film kayıt
        $(document).ready(function () {
          $('#filmForm').on('submit', function (e) {
              e.preventDefault(); // Formun varsayılan submit işlemini durdurur.
      
              // Form verilerini toplama
              var formData = new FormData(this);
              // Multi-select alanlarındaki seçilen değerleri toplama
              $('.multiselect .checkboxes input[type="checkbox"]:checked').each(function () {
                  formData.append($(this).attr('id'), $(this).val());
              });
              formData.append('statu', 1);
              $.ajax({
                url: "controller/filmAdd.php", // PHP dosyasının yolu
                type: "POST",
                data: formData,
                contentType: false, 
                processData: false, 
                success: function(response){
                  alert(response);
                    if(response.trim()==="" || response.trim===null){
                      localStorage.setItem("uri", 'content3');
                      location.reload();
                    }
                  
                },
                error: function(jqXHR, textStatus, errorThrown){
                    alert("Bir hata oluştu: " + textStatus + " " + errorThrown);
                }
               });
          }); 
      });  
      
 // Dizi  kayıt
 $(document).ready(function () {
  $('#diziForm').on('submit', function (e) {
      e.preventDefault(); // Formun varsayılan submit işlemini durdurur.

      // Form verilerini toplama
      var formData = new FormData(this);
      // Multi-select alanlarındaki seçilen değerleri toplama
      $('.multiselect .checkboxes input[type="checkbox"]:checked').each(function () {
          formData.append($(this).attr('id'), $(this).val());
      });
      formData.append('statu', 2);
      $.ajax({
        url: "controller/filmAdd.php", // PHP dosyasının yolu
        type: "POST",
        data: formData,
        contentType: false, 
        processData: false, 
        success: function(response){
          alert(response);
            if(response.trim()==="" || response.trim===null){
              localStorage.setItem("uri", 'content4');
              location.reload();
            }
          
        },
        error: function(jqXHR, textStatus, errorThrown){
            alert("Bir hata oluştu: " + textStatus + " " + errorThrown);
        }
       });
  }); 
});  

   // Film Güncelleme

   $(document).ready(function () {
    $('#filmdetay').on('submit', function (e) {
        e.preventDefault(); // Formun varsayılan submit işlemini durdurur.

        // Form verilerini toplama
        var formData = new FormData(this);
        // Multi-select alanlarındaki seçilen değerleri toplama
        $('.multiselect .checkboxes input[type="checkbox"]:checked').each(function () {
            formData.append($(this).attr('id'), $(this).val());
        });

        $.ajax({
          url: "controller/filmEdit.php", // PHP dosyasının yolu
          type: "POST",
          data: formData,
          contentType: false, 
          processData: false, 
          success: function(response){
            alert(response);
          
            localStorage.setItem("uri", 'content3');
            location.reload();
            
          },
          error: function(jqXHR, textStatus, errorThrown){
              alert("Bir hata oluştu: " + textStatus + " " + errorThrown);
          }
         });


    }); 
});  


   // Dizi Güncelleme

   $(document).ready(function () {
    $('#dizidetay').on('submit', function (e) {
        e.preventDefault(); // Formun varsayılan submit işlemini durdurur.

        // Form verilerini toplama
        var formData = new FormData(this);
        // Multi-select alanlarındaki seçilen değerleri toplama
        $('.multiselect .checkboxes input[type="checkbox"]:checked').each(function () {
            formData.append($(this).attr('id'), $(this).val());
        });

        $.ajax({
          url: "controller/filmEdit.php", // PHP dosyasının yolu
          type: "POST",
          data: formData,
          contentType: false, 
          processData: false, 
          success: function(response){
            alert(response);
          
            localStorage.setItem("uri", 'content3');
            location.reload();
            
          },
          error: function(jqXHR, textStatus, errorThrown){
              alert("Bir hata oluştu: " + textStatus + " " + errorThrown);
          }
         });


    }); 
});  


       // Film Sil

  $(document).ready(function() {
    $('#filmSil').click(function(e) {
      e.preventDefault(); // Sayfanın yeniden yüklenmesini engeller

      // Kategori adını al
      var filmSil= $('#kategoriid').val();
      $.ajax({
        url: 'controller/filmController.php',
        type: 'POST',
        data: { filmSil: filmSil },
        success: function(response) {
          
          // Başarılı olursa yapılacak işlemler
          const button = document.getElementById('deletefilmgeri');
            button.click();
            localStorage.setItem("uri", 'content3');    
            location.reload();
        },
        error: function(xhr, status, error) {
          // Hata olursa yapılacak işlemler
          console.log('Hata: ' + error);
        }
      });
    });
  });

    // Film Sil

    $(document).ready(function() {
      $('#diziSil').click(function(e) {
        e.preventDefault(); // Sayfanın yeniden yüklenmesini engeller
  
        // Kategori adını al
        var filmSil= $('#kategoriid').val();
        $.ajax({
          url: 'controller/filmController.php',
          type: 'POST',
          data: { filmSil: filmSil },
          success: function(response) {
            
            
              localStorage.setItem("uri", 'content4');    
              location.reload();
          },
          error: function(xhr, status, error) {
            // Hata olursa yapılacak işlemler
            console.log('Hata: ' + error);
          }
        });
      });
    });
  






// haberler ekleme


$(document).ready(function() {
  $('#formHaberler').on('submit', function(event) {
      event.preventDefault(); // Sayfanın yenilenmesini önler

      var haberBaslik = $('#haberBaslik').val();
      var haberIcerik = $('#haberIcerik').val();
      var kapakFotoInput = $('input[name="kapakfoto[]"]'); // name ile inputu seç
     
      var kapakFoto = kapakFotoInput[0].files; // Dosyaları seç

      // FormData nesnesi oluştur
      var formData = new FormData();
      formData.append('baslik', haberBaslik);
      formData.append('icerik', haberIcerik);

      // Dosyaları FormData'ya ekle
      for (var i = 0; i < kapakFoto.length; i++) {
          formData.append('kapakfoto[]', kapakFoto[i]);
      }

      $.ajax({
          url: 'controller/haberAdd.php',
          type: 'POST',
          data: formData,
          processData: false,  
          contentType: false,  
          success: function(response) {
            if(response==="1"){
              alert("Tüm Alanları Doldurunuz.")
            }else{
              localStorage.setItem("uri", 'content7');
              location.reload();
            }
           
          },
          error: function(jqXHR, textStatus, errorThrown) {
              alert('Hata: ' + errorThrown);
          }
      });
  });
});

 // haber Sil

 $(document).ready(function() {
  $('#haberSil').click(function(e) {
    e.preventDefault(); // Sayfanın yeniden yüklenmesini engeller

    // Kategori adını al
    var haberid= $('#kategoriid').val();
    $.ajax({
      url: 'controller/filmController.php',
      type: 'POST',
      data: { haberid: haberid },
      success: function(response) {
   
        // Başarılı olursa yapılacak işlemler
        
          localStorage.setItem("uri", 'content7');
          location.reload();
      },
      error: function(xhr, status, error) {
        // Hata olursa yapılacak işlemler
        console.log('Hata: ' + error);
      }
    });
  });
});



// excel ekel

$(document).ready(function() {
  $('#submitForm').on('click', function(e) {
      e.preventDefault();

      var formData = new FormData();
      var fileInput = $('#formFile')[0].files[0]; // Seçilen dosya

      // Gizli inputlardan değerleri alalım
      var filmId = $('input[name="filmid"]').val();
      var dagitimId = $('input[name="dagitimid"]').val();

      if (fileInput) {
          // FormData'ya dosyayı ekleyelim
          formData.append('excelFile', fileInput);
          
          // Gizli inputlardan gelen değerleri FormData'ya ekleyelim
          formData.append('filmid', filmId);
          formData.append('dagitimid', dagitimId);

          // AJAX isteğini gönderelim
          $.ajax({
              url: 'controller/excelAdd.php', // PHP dosyanızın yolu
              type: 'POST',
              data: formData,
              processData: false, // Form verisini stringe çevirmesini engeller
              contentType: false, // Jquery'in içerik türü ayarlamasını engeller
              success: function(response) {
                  console.log(response);
                 alert("Excel verileri başarıyla aktarıldı.");
              },
              error: function(jqXHR, textStatus, errorThrown) {
                  alert('Dosya yüklenirken bir hata oluştu.');
                  console.log('Error: ' + textStatus + ' - ' + errorThrown);
              }
          });
      } else {
          alert('Lütfen bir dosya seçin!');
      }
  });
});



// Sinema salonu excel ekle 

$(document).ready(function() {
  $('#submitForm2').on('click', function(e) {
      e.preventDefault();

      var formData = new FormData();
      var fileInput = $('#formFile2')[0].files[0]; // Seçilen dosya

      // Gizli inputlardan değerleri alalım
      var filmId = $('input[name="filmidd"]').val();
      var dagitimId = $('input[name="dagitimidd"]').val();
      var basdate = $('input[name="basdate"]').val();
      var bitdate = $('input[name="bitisdate"]').val();
  
      if (fileInput) {
          // FormData'ya dosyayı ekleyelim
          formData.append('excelFile', fileInput);
          
          // Gizli inputlardan gelen değerleri FormData'ya ekleyelim
          formData.append('filmid', filmId);
          formData.append('dagitimid', dagitimId);
          formData.append('basdate', basdate);
          formData.append('bitdate', bitdate);
          
          // Progress bar'ı göster
          $('#progress-container').show();
          $('#progress-bar').css('width', '0%'); // Başlangıçta %0

          // AJAX isteğini gönderelim
          $.ajax({
              url: 'controller/excelAdd2.php', // PHP dosyanızın yolu
              type: 'POST',
              data: formData,
              processData: false, // Form verisini stringe çevirmesini engeller
              contentType: false, // Jquery'in içerik türü ayarlamasını engeller
              
              // AJAX isteği sırasında ilerlemeyi güncelle
              xhr: function() {
                  var xhr = new window.XMLHttpRequest();
                  xhr.upload.addEventListener("progress", function(evt) {
                      if (evt.lengthComputable) {
                          var percentComplete = evt.loaded / evt.total;
                          percentComplete = parseInt(percentComplete * 100);
                          // Progress bar'ı güncelle
                          $('#progress-bar').css('width', percentComplete + '%');
                      }
                  }, false);
                  return xhr;
              },

              success: function(response) {
                  console.log(response);
                  alert("Excel verileri başarıyla aktarıldı.");
                  $('#progress-container').hide(); // Başarılı yüklemeden sonra progress bar'ı gizle
              },
              error: function(jqXHR, textStatus, errorThrown) {
                  alert('Dosya yüklenirken bir hata oluştu.');
                  console.log('Error: ' + textStatus + ' - ' + errorThrown);
                  $('#progress-container').hide(); // Hata durumunda da progress bar'ı gizle
              }
          });
      } else {
          alert('Lütfen bir dosya seçin!');
      }
  });
});


