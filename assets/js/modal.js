var slideIndex = 0;
var isAnimating = false; // Animasyon sırasında başka işlem yapılmasın diye kontrol

// Modal açma fonksiyonu
function openModal(index) {
    var modal = document.getElementById("imageModal");
    modal.style.display = "flex";
    currentSlide(index); // İlk açılan resmin indexini ayarla
}

// Modal kapama fonksiyonu
function closeModal() {
    var modal = document.getElementById("imageModal");
    modal.style.display = "none";
}

// Geçerli slide'ı ayarla
function currentSlide(index) {
    showSlide(slideIndex = index);
}

// Slide'lar arasında gezinme fonksiyonu
function changeSlide(n) {
    if (!isAnimating) { // Animasyon sırasında başka slayt değişimi olmasın
        showSlide(slideIndex += n);
    }
}

// Slide gösterme fonksiyonu
function showSlide(n) {
    var modalImg = document.getElementById("modalImg");
    var thumbnails = document.querySelectorAll('.thumbnail');

    // Eğer n, küçük resimlerin sayısından büyükse başa döner
    if (n >= thumbnails.length) {
        slideIndex = 0;
    }
    // Eğer n negatifse sona döner
    if (n < 0) {
        slideIndex = thumbnails.length - 1;
    }

    // Animasyon başlatmadan önce fade-out efekti ekle
    modalImg.classList.add('fade-out');
    isAnimating = true; // Animasyon başladı

    // Animasyon tamamlanınca resmi değiştir ve fade-in efekti başlat
    setTimeout(function() {
        modalImg.src = thumbnails[slideIndex].src;

        // Fade-out efektini kaldır ve fade-in ekle
        modalImg.classList.remove('fade-out');
        modalImg.classList.add('fade-in');

        // Animasyonun bitmesini bekleyip tekrar işlemi aç
        setTimeout(function() {
            modalImg.classList.remove('fade-in'); // Fade-in tamamlandıktan sonra sınıfı temizle
            isAnimating = false; // Animasyon bitti, yeni işlem yapılabilir
        }, 300); // Animasyon süresi 500ms
    }, 300); // Fade-out süresi 500ms

    // Küçük resimlerin aktif durumunu güncelle
    thumbnails.forEach(function (thumb, i) {
        thumb.classList.remove("active-thumbnail");
        if (i === slideIndex) {
            thumb.classList.add("active-thumbnail");
            // Seçili küçük resmi ortala
            thumb.scrollIntoView({ behavior: "smooth", inline: "center" });
        }
    });
}


const thumbnailContainer = document.querySelector('.thumbnail-container');
let isDragging = false;
let startX;
let scrollLeft;

// Mouse ile tıklayıp kaydırma işlemi için gerekli event'ler
thumbnailContainer.addEventListener('mousedown', (e) => {
    isDragging = true;
    thumbnailContainer.classList.add('active');
    startX = e.pageX - thumbnailContainer.offsetLeft;
    scrollLeft = thumbnailContainer.scrollLeft;
});

thumbnailContainer.addEventListener('mouseleave', () => {
    isDragging = false;
    thumbnailContainer.classList.remove('active');
});

thumbnailContainer.addEventListener('mouseup', () => {
    isDragging = false;
    thumbnailContainer.classList.remove('active');
});

thumbnailContainer.addEventListener('mousemove', (e) => {
    if (!isDragging) return; // Fare basılı değilse işleme devam etme
    e.preventDefault();
    const x = e.pageX - thumbnailContainer.offsetLeft;
    const walk = (x - startX) * 2; // 2 kat hızda kaydırma
    thumbnailContainer.scrollLeft = scrollLeft - walk;
});