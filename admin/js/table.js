$(document).ready(function(){
	// Activate tooltip
	$('[data-toggle="tooltip"]').tooltip();
	
	// Select/Deselect checkboxes
	var checkbox = $('table tbody input[type="checkbox"]');
	$("#selectAll").click(function(){
		if(this.checked){
			checkbox.each(function(){
				this.checked = true;                        
			});
		} else{
			checkbox.each(function(){
				this.checked = false;                        
			});
		} 
	});
	checkbox.click(function(){
		if(!this.checked){
			$("#selectAll").prop("checked", false);
		}
	});
});
document.addEventListener('DOMContentLoaded', function () {
    const tables = document.querySelectorAll('.paginated-table');

    tables.forEach((table, index) => {
        const tbody = table.querySelector('tbody');
        const rows = Array.from(tbody.querySelectorAll('tr'));
        const totalEntries = rows.length;

        const select = document.getElementById(`rowsPerPageSelect${index}`);
        if (!select) {
            console.error(`Seçim elemanı bulunamadı: rowsPerPageSelect${index}`);
            return; // Eğer seçim elemanı mevcut değilse, işlemi durdur
        }

        const pagination = document.getElementById(`pagination${index}`);
        const currentPageEntries = document.getElementById(`currentPageEntries${index}`);
        const totalEntriesText = document.getElementById(`totalEntries${index}`);

        if (!totalEntriesText || !currentPageEntries || !pagination) {
            console.error(`ID'leri bulunamadı: currentPageEntries${index}, totalEntries${index}, pagination${index}`);
            return; // Eğer öğeler mevcut değilse, işlemi durdur
        }

        totalEntriesText.innerText = totalEntries;

        let currentPage = 1;
        let rowsPerPage = parseInt(select.value);

        function updateTable() {
            let start = (currentPage - 1) * rowsPerPage;
            let end = start + rowsPerPage;
            rows.forEach((row, index) => {
                row.style.display = (index >= start && index < end) ? '' : 'none';
            });
            currentPageEntries.innerText = `${start + 1}-${Math.min(end, totalEntries)}`;
        }

        function createPagination() {
            pagination.innerHTML = '';
            let pageCount = Math.ceil(totalEntries / rowsPerPage);

            // "İlk" butonunu ekle
            if (currentPage > 1) {
                let firstButton = document.createElement('li');
                firstButton.classList.add('page-item');
                let firstLink = document.createElement('a');
                firstLink.classList.add('page-link');
                firstLink.innerText = '<<'; // "İlk" butonu
                firstLink.href = '#';
                firstLink.addEventListener('click', function (e) {
                    e.preventDefault();
                    currentPage = 1; // İlk sayfaya git
                    updateTable();
                    createPagination();
                });
                firstButton.appendChild(firstLink);
                pagination.appendChild(firstButton);
            }

            // Sayfa numaralarını göster
            let startPage = Math.max(currentPage - 2, 1);
            let endPage = Math.min(startPage + 4, pageCount);

            for (let i = startPage; i <= endPage; i++) {
                createPageButton(i);
            }

            // "Son" butonunu ekle
            if (currentPage < pageCount) {
                let lastButton = document.createElement('li');
                lastButton.classList.add('page-item');
                let lastLink = document.createElement('a');
                lastLink.classList.add('page-link');
                lastLink.innerText = '>>'; // "Son" butonu
                lastLink.href = '#';
                lastLink.addEventListener('click', function (e) {
                    e.preventDefault();
                    currentPage = pageCount; // Son sayfaya git
                    updateTable();
                    createPagination();
                });
                lastButton.appendChild(lastLink);
                pagination.appendChild(lastButton);
            }
        }

        function createPageButton(pageNumber) {
            let li = document.createElement('li');
            li.classList.add('page-item');
            if (pageNumber === currentPage) li.classList.add('active');

            let a = document.createElement('a');
            a.classList.add('page-link');
            a.innerText = pageNumber;
            a.href = '#';
            a.addEventListener('click', function (e) {
                e.preventDefault();
                currentPage = pageNumber;
                updateTable();
                createPagination();
            });

            li.appendChild(a);
            pagination.appendChild(li);
        }

        updateTable();
        createPagination();

        select.addEventListener('change', function () {
            rowsPerPage = parseInt(this.value);
            currentPage = 1; // Sayfa başa döner 
            updateTable();
            createPagination();
        });
    });
});
