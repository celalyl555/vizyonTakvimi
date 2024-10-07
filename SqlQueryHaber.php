<?php
#********************************************************************************
#haberler sql query başlangıç
$sqlHaberler = "SELECT * FROM haberler WHERE statu = 1 ORDER BY tarih DESC LIMIT 4"; // En yeni 4 haberi al
$stmtHaberler = $con->query($sqlHaberler);
$haberler = $stmtHaberler->fetchAll(PDO::FETCH_ASSOC);

#********************************************************************************

$sqlHaberlerGenel = "SELECT * FROM haberler ORDER BY tarih DESC LIMIT 5"; // En yeni 4 haberi al
$stmtHaberlerGenel = $con->query($sqlHaberlerGenel);
$haberlerGenel = $stmtHaberlerGenel->fetchAll(PDO::FETCH_ASSOC);
#haberler sql query bitiş

#haberler sql query başlangıç
$sqlHaberler3 = "SELECT * FROM haberler WHERE statu = 1 ORDER BY tarih DESC LIMIT 7"; // En yeni 4 haberi al
$stmtHaberler3 = $con->query($sqlHaberler3);
$haberler3 = $stmtHaberler3->fetchAll(PDO::FETCH_ASSOC);
?>