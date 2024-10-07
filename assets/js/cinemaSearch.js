document.getElementById('search').addEventListener('input', function() {
    filterCinemas(this.value);
});

// Input'a tıklandığında eğer içinde metin varsa dropdown'ı tekrar aç
document.getElementById('search').addEventListener('click', function() {
    const inputValue = this.value.trim(); // Boşlukları yok saymak için trim kullanıyoruz
    if (inputValue !== "") {
        filterCinemas(inputValue); // Eğer inputta metin varsa filtrelemeyi tekrar çalıştır
    }
});

document.getElementById('cinema-list').addEventListener('click', function(e) {
    if (e.target.tagName === 'LI') {
        var selectedCinema = e.target.textContent; // Seçilen değeri input alanına yazdır
        var cinemaId = e.target.getAttribute('data-id'); // Seçilen sinemanın ID'sini al

        document.getElementById('search').value = selectedCinema; // Seçilen değeri input alanına yaz
        this.style.display = 'none'; // Dropdown'ı gizle

        // Mevcut URL'yi al
        var currentUrl = window.location.href;
        var url = new URL(currentUrl);

        // URL'deki mevcut id ve city parametrelerini kaldır
        url.searchParams.delete('id');
        url.searchParams.delete('city');

        // Eğer sinema adı mı yoksa şehir mi seçildiğini belirle
        if (cinemaId.match(/^\d+$/)) {
            // ID sayısal ise sinema seçilmiştir, id parametresini ekle
            url.searchParams.append('id', cinemaId);
        } else {
            // ID sayısal değilse şehir seçilmiştir, city parametresini ekle
            url.searchParams.append('city', cinemaId);
        }

        // Yeni URL'yi güncelle ve sayfayı yenile
        window.location.href = url.toString();
    }
});


// Dropdown dışında bir yere tıklanırsa dropdown'ı kapat
document.addEventListener('click', function(event) {
    const searchInput = document.getElementById('search');
    const cinemaList = document.getElementById('cinema-list');
    if (!searchInput.contains(event.target) && !cinemaList.contains(event.target)) {
        cinemaList.style.display = 'none'; // Dropdown'ı gizle
    }
});

function filterCinemas(input) {
    const cinemaList = document.getElementById('cinema-list');
    const listItems = cinemaList.getElementsByTagName('li'); // Mevcut tüm <li> elemanlarını al
    
    let hasMatch = false;
    
    for (let i = 0; i < listItems.length; i++) {
        const cinemaName = listItems[i].textContent.toLowerCase(); // <li> içindeki metni küçük harfe çeviriyoruz
        if (cinemaName.includes(input.toLowerCase())) { // Kullanıcı girdisini küçük harfe çevirip karşılaştırıyoruz
            listItems[i].style.display = 'block'; // Eşleşen item'leri göster
            hasMatch = true;
        } else {
            listItems[i].style.display = 'none'; // Eşleşmeyen item'leri gizle
        }
    }

    // Eğer eşleşen bir sonuç varsa dropdown'ı göster, yoksa gizle
    cinemaList.style.display = hasMatch ? 'block' : 'none';
}