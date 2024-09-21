const mobileBtn = document.querySelector('.mobile-btn');
const navbarMobile = document.getElementById('navbar-mobile');
const menuIcon = document.getElementById('menu-icon');

mobileBtn.addEventListener('click', () => {
    // Menüyü göster/gizle
    navbarMobile.classList.toggle('show');

    // İkonu değiştir
    if (menuIcon.classList.contains('fa-bars')) {
        menuIcon.classList.remove('fa-bars');
        menuIcon.classList.add('fa-times'); // Çarpı ikonu
    } else {
        menuIcon.classList.remove('fa-times');
        menuIcon.classList.add('fa-bars'); // Menü ikonu
    }
});


// click menu

const mainButton = document.getElementById('mainButton');
const submenu = document.getElementById('submenu');

// Toggle the submenu on button click
mainButton.addEventListener('click', (event) => {
    event.stopPropagation(); // Prevent event bubbling to document
    submenu.style.display = submenu.style.display === 'block' ? 'none' : 'block';
});

// Close the submenu when clicking outside of it
document.addEventListener('click', (event) => {
    if (!mainButton.contains(event.target) && !submenu.contains(event.target)) {
        submenu.style.display = 'none';
    }
});