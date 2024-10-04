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
    // Tabloları ve ilgili öğeleri bul
    const tables = document.querySelectorAll('.paginated-table');

    tables.forEach((table, index) => {
        const tbody = table.querySelector('tbody');
        const rows = Array.from(tbody.querySelectorAll('tr'));
        const totalEntries = rows.length;

        const select = document.getElementById(`rowsPerPageSelect${index + 1}`);
        const pagination = document.getElementById(`pagination${index + 1}`);
        const currentPageEntries = document.getElementById(`currentPageEntries${index + 1}`);
        const totalEntriesText = document.getElementById(`totalEntries${index + 1}`);
        totalEntriesText.innerText = totalEntries;

        let currentPage = 1;
        let rowsPerPage = parseInt(select.value);

        function updateTable() {
            // Tabloda hangi satırların görünmesi gerektiğini belirle
            let start = (currentPage - 1) * rowsPerPage;
            let end = start + rowsPerPage;
            rows.forEach((row, index) => {
                if (index >= start && index < end) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
            // Sayfa ve giriş metnini güncelle
            currentPageEntries.innerText = `${start + 1}-${Math.min(end, totalEntries)}`;
        }

        function createPagination() {
            pagination.innerHTML = '';
            let pageCount = Math.ceil(totalEntries / rowsPerPage);

            for (let i = 1; i <= pageCount; i++) {
                let li = document.createElement('li');
                li.classList.add('page-item');
                if (i === currentPage) li.classList.add('active');

                let a = document.createElement('a');
                a.classList.add('page-link');
                a.innerText = i;
                a.href = '#';
                a.addEventListener('click', function (e) {
                    e.preventDefault();
                    currentPage = i;
                    updateTable();
                    createPagination();
                });

                li.appendChild(a);
                pagination.appendChild(li);
            }
        }

        // Sayfa yüklenince tabloyu güncelle ve sayfalama oluştur
        updateTable();
        createPagination();

        // Satır sayısı değiştiğinde tabloyu yeniden yükle
        select.addEventListener('change', function () {
            rowsPerPage = parseInt(this.value);
            currentPage = 1; // Sayfa başa döner
            updateTable();
            createPagination();
        });
    });
});

