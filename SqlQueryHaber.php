<?php
#********************************************************************************
#haberler sql query başlangıç
$sqlHaberler = "SELECT * FROM haberler WHERE statu = 1 ORDER BY tarih DESC LIMIT 4"; // En yeni 4 haberi al
$stmtHaberler = $con->query($sqlHaberler);
$haberler = $stmtHaberler->fetchAll(PDO::FETCH_ASSOC);

#********************************************************************************

$sqlHaberlerGenel = "SELECT * FROM haberler ORDER BY tarih DESC LIMIT 4"; // En yeni 4 haberi al
$stmtHaberlerGenel = $con->query($sqlHaberlerGenel);
$haberlerGenel = $stmtHaberlerGenel->fetchAll(PDO::FETCH_ASSOC);
#haberler sql query bitiş


?>