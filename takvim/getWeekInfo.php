<?php
function getFridaysAndWeekNumber($year, $month) {
    $startDate = strtotime("$year-$month-01");
    $endDate = strtotime("$year-$month-" . date('t', $startDate)); // Ayın son günü

    $fridays = [];
    while ($startDate <= $endDate) {
        // Cuma günlerini kontrol et
        if (date('N', $startDate) == 5) {
            $fridayDate = date('j F l', $startDate); // Günü, ayı ve gün adını al
            $weekNumber = date('W', $startDate); // Haftanın numarasını al
            $fridays[] = ['date' => $fridayDate, 'week' => $weekNumber];
        }
        // Bir sonraki güne geç
        $startDate = strtotime("+1 day", $startDate);
    }
    return $fridays;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $year = $_POST['year'];
    $month = $_POST['month'];

    // Cuma günlerini ve hafta numaralarını hesapla
    $fridays = getFridaysAndWeekNumber($year, $month);

    // İlk Cuma gününü referans alarak döndür
    if (!empty($fridays)) {
        echo '<h3>' . $fridays[0]['date'] . '</h3>';
        echo '<p class="title">' . $year . ' yılı ' . $fridays[0]['week'] . '. hafta</p>';
    } else {
        echo 'Bu ayda Cuma günü bulunmuyor.';
    }
}
?>
