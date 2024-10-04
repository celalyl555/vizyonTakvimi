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
