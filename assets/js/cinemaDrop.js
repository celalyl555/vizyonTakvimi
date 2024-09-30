function toggleDetails(element) {
    const cinemaDetails = element.nextElementSibling;
    cinemaDetails.style.display = cinemaDetails.style.display === 'flex' ? 'none' : 'flex';

    // Ok işaretini değiştirmek için aktif sınıf ekleyelim
    element.classList.toggle('active');
}