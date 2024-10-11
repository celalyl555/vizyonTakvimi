// Güncel ayın bilgilerini al
const currentDate = new Date();
const currentMonth = currentDate.getMonth(); // 0-11 arası (0: Ocak, 11: Aralık)
const currentYear = currentDate.getFullYear();

// Ayın gün sayısını hesapla
const daysInMonth = new Date(currentYear, currentMonth + 1, 0).getDate();

// Günleri ve veri değerlerini oluştur
const labels = [];
const dataValues = [];

// Günleri oluştur ve veri değerlerini almak için PHP'den çek
const fetchDataForDays = async () => {
    const dayString = `${currentYear}-${String(currentMonth + 1).padStart(2, '0')}`;
    const labels = [];
    const dataValues = [];

    // AJAX ile günlük kullanıcı sayısını al
    await fetch(`controller/api.php?month=${dayString}`)
        .then(response => response.json())
        .then(data => {
            // Gelen verileri işleme
            for (let day = 1; day <= daysInMonth; day++) {
                labels.push(day + ' ' + ['Ocak', 'Şubat', 'Mart', 'Nisan', 'Mayıs', 'Haziran', 'Temmuz', 'Ağustos', 'Eylül', 'Ekim', 'Kasım', 'Aralık'][currentMonth]);

                // Günlük kullanıcı sayısını data'dan al
                dataValues.push(data[day] ? data[day].user_count : 0); // Eğer veri yoksa 0 ekle
            }
        })
        .catch(error => {
            console.error('Hata:', error);
            // Hata durumunda tüm verilere 0 ekle
            for (let day = 1; day <= daysInMonth; day++) {
                dataValues.push(0);
            }
        });

    // Chart.js konfigürasyonu
    var chart = document.getElementById('chart').getContext('2d'),
        gradient = chart.createLinearGradient(0, 0, 0, 450);

    gradient.addColorStop(0, 'rgba(0, 199, 214, 0.32)');
    gradient.addColorStop(0.3, 'rgba(0, 199, 214, 0.1)');
    gradient.addColorStop(1, 'rgba(0, 199, 214, 0)');

    var chartData = {
        labels: labels,
        datasets: [{
            label: 'Toplam Kullanıcı',
            backgroundColor: gradient,
            pointBackgroundColor: '#00c7d6',
            borderWidth: 1,
            borderColor: '#0e1a2f',
            data: dataValues // PHP'den gelen kullanıcı sayıları
        }]
    };

    var options = {
        responsive: true,
        maintainAspectRatio: true,
        animation: {
            easing: 'easeInOutQuad',
            duration: 520
        },
        scales: {
            yAxes: [{
                ticks: {
                    fontColor: '#5e6a81'
                },
                gridLines: {
                    color: 'rgba(200, 200, 200, 0.08)',
                    lineWidth: 1
                }
            }],
            xAxes: [{
                ticks: {
                    fontColor: '#5e6a81'
                }
            }]
        },
        elements: {
            line: {
                tension: 0.4
            }
        },
        legend: {
            display: false
        },
        point: {
            backgroundColor: '#00c7d6'
        },
        tooltips: {
            titleFontFamily: 'Poppins',
            backgroundColor: 'rgba(0,0,0,0.4)',
            titleFontColor: 'white',
            caretSize: 5,
            cornerRadius: 2,
            xPadding: 10,
            yPadding: 10
        }
    };

    var chartInstance = new Chart(chart, {
        type: 'line',
        data: chartData,
        options: options
    });
};

// Verileri çek ve grafiği oluştur
fetchDataForDays();
