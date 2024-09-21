document.addEventListener('DOMContentLoaded', () => {
    const table = document.querySelector('#movie-table');
    const headers = table.querySelectorAll('th .sort');
    const tableBody = table.querySelector('tbody');
    const rows = Array.from(tableBody.querySelectorAll('tr'));

    headers.forEach(header => {
        header.addEventListener('click', () => {
            const sortBy = header.dataset.sort;
            const direction = header.classList.contains('asc') ? -1 : 1;

            rows.sort((a, b) => {
                const cellA = a.querySelector(`td:nth-child(${header.parentElement.cellIndex + 1})`).innerText.toLowerCase();
                const cellB = b.querySelector(`td:nth-child(${header.parentElement.cellIndex + 1})`).innerText.toLowerCase();

                const aValue = parseValue(cellA);
                const bValue = parseValue(cellB);

                return (aValue > bValue ? 1 : -1) * direction;
            });

            tableBody.innerHTML = '';
            rows.forEach(row => tableBody.appendChild(row));

            headers.forEach(h => {
                h.classList.remove('asc', 'desc', 'active');
                h.querySelector('i').className = 'fas fa-sort';
            });

            header.classList.add(direction === 1 ? 'asc' : 'desc', 'active');
            header.querySelector('i').className = direction === 1 ? 'fas fa-caret-up' : 'fas fa-caret-down';
        });
    });

    function parseValue(value) {
        // Handle numeric values, especially those with commas or special characters
        if (value.includes('₺')) {
            return parseFloat(value.replace(/[^0-9,]/g, '').replace(',', '.'));
        } else if (value.includes('.')) {
            return parseFloat(value.replace('.', ''));
        } else {
            return parseInt(value) || value;
        }
    }
});



// Other Table

document.addEventListener('DOMContentLoaded', function() {
    const tabButtonshafta = document.querySelectorAll('.tab-button-hafta');
    const tabContentshafta = document.querySelectorAll('.tab-content-hafta');

    tabButtonshafta.forEach(button => {
        button.addEventListener('click', () => {
            // Tüm butonlardan 'active' sınıfını kaldır
            tabButtonshafta.forEach(btn => btn.classList.remove('active'));
            // Tıklanan butona 'active' sınıfını ekle
            button.classList.add('active');

            // Tüm içerikleri gizle
            tabContentshafta.forEach(content => content.classList.add('hidden'));

            // İlgili tab içeriğini göster
            const tabId = button.getAttribute('data-tab');
            document.getElementById(tabId).classList.remove('hidden');
        });
    });

    // İlk sekmeyi varsayılan olarak aktif yap
    document.querySelector('.tab-button-hafta.active').click();
});
